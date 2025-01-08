<?php

namespace App\Controllers;

use App\Entities\BookEntity;
use Exception;

class Books extends BaseController
{
    protected function getBook(int $bookId)
    {
        $book = bookModel()->find($bookId);

        if ($book === null) {
            throw new Exception("Book with ID {$bookId} does not exist");
        }

        return $book;
    }

    /**
     * GET /book/$bookId
     */
    public function view(int $bookId)
    {
        $book = $this->getBook($bookId);

        $data = [
            'crumbs' => [
                ['Find a book', 'find/book'],
            ],
            'current' => $book->title,
            'book'    => $book,
        ];

        return view('books/view', $data);
    }

    /**
     * POST /book/$bookId
     */
    public function update(int $bookId)
    {
        $book = bookModel()->find($bookId);
        if (! $book) {
            throw new Exception("Book with book_id {$bookId} does not exist");
        }

        $db = db_connect();
        $db->transStart();

        $this->updateBookEntity($book);
        if ($book->hasChanged()) {
            $bookUpdate = bookModel()->update($book->book_id, $book);
            if (! $bookUpdate) {
                return redirect()->back()->withInput()->with('alert', 'error-book');
            }
        }

        $this->syncBookSeries($book);

        $syncAuthors = $this->syncBookAuthors($book, $alert);
        if (! $syncAuthors) {
            redirect()->back()->withInput()->with('alert', $alert);
        }

        $db->transComplete();

        return redirect()->back()->with('alert', 'success');
    }

    /**
     * GET /books/new
     */
    public function new()
    {
        $book        = new BookEntity();
        $book->title = trim(htmlspecialchars($this->request->getGet('title')));

        $data = [
            'current' => 'Add a new book',
            'book'    => $book,
        ];

        return view('books/new', $data);
    }

    /**
     * POST /book/new
     */
    public function insert()
    {
        $book = new BookEntity();

        $db = db_connect();
        $db->transStart();

        $this->updateBookEntity($book);
        $insert = bookModel()->insert($book);
        if (! $insert) {
            return redirect()->back()->withInput()->with('alert', 'error')->with('errors', bookModel()->validation->getErrors());
        }
        $book->book_id = $insert;

        $this->syncBookSeries($book);

        $syncAuthors = $this->syncBookAuthors($book, $alert);
        if (! $syncAuthors) {
            redirect()->back()->withInput()->with('alert', $alert);
        }

        $db->transComplete();

        return redirect()->to("book/{$book->book_id}")->with('alert', 'insert-success');
    }

    /**
     * Update book entity with POST fields
     */
    private function updateBookEntity(BookEntity &$book): BookEntity
    {
        $book->title      = $this->request->getPost('title');
        $book->subtitle   = $this->request->getPost('subtitle');
        $book->part       = $this->request->getPost('part');
        $book->section_id = $this->request->getPost('section_id');
        $book->count      = $this->request->getPost('count');
        $book->price      = $this->request->getPost('price');
        $book->note       = $this->request->getPost('note');

        return $book;
    }

    private function syncBookSeries($book): bool
    {
        // todo

        return true;
    }

    /**
     * Sync book authors from POST request, create new authors if needed
     *
     * @param mixed $book
     * @param mixed $alert
     */
    private function syncBookAuthors($book, &$alert): bool
    {
        // Existing Authors
        $authorIds = $this->request->getPost('author_ids') ?? [];

        if (count($authorIds) === 0) {
            $alert = 'no-authors';

            return false;
        }

        $existingAuthors = booksAuthorsModel()->syncBookAuthorIds($book->book_id, $authorIds);
        if (! $existingAuthors) {
            $alert = 'error-authors';

            return false;
        }

        // Create new authors
        $createAuthors = $this->request->getPost('create_authors');
        if ($createAuthors !== null) {
            foreach ($createAuthors as $createAuthor) {
                $author       = new \App\Entities\AuthorEntity();
                $author->name = $createAuthor;

                // Find exact match or create new author
                $match = authorModel()->where('name', $author->name)->first();

                if ($match === null) {
                    $insertAuthor = authorModel()->insert($author);
                    if (! $insertAuthor) {
                        $alert = 'error-authors';

                        return false;
                    }
                } else {
                    $insertAuthor = $match->author_id;
                }

                $addAuthor = booksAuthorsModel()
                    ->set('book_id', $book->book_id)
                    ->set('author_id', $insertAuthor)
                    ->insert();
                if (! $addAuthor) {
                    $alert = 'error-authors';

                    return false;
                }
            }
        }

        return true;
    }

    /**
     * GET /find/book
     */
    public function find()
    {
        $data = [
            'current' => 'Find a book',
        ];

        return view('books/find', $data);
    }

    /**
     * GET /find/book/ajax
     */
    public function findAjax()
    {
        // Query
        $minChars   = 3;
        $maxResults = (int) $this->request->getGet('max');
        $query      = trim($this->request->getGet('q'));

        if (strlen($query) < $minChars) {
            return json_encode([
                'msg'       => 'query-too-short',
                'min-chars' => $minChars,
            ]);
        }

        // Find
        $bookModel = model('BookModel')->join('series', 'series.series_id = books.series_id', 'left');
        $words     = explode(' ', $query);

        foreach ($words as $word) {
            $bookModel->like('search_string', $word);
        }
        $bookModel->orderBy('title');
        $books = $bookModel->findAll();

        // No results
        if (count($books) === 0) {
            return json_encode([
                'msg'   => 'no-results',
                'query' => $query,
            ]);
        }

        $this->similarSort($books, $query);

        // Return
        $return = [
            'message'       => 'ok',
            'count'         => count($books),
            'query'         => $query,
            'sortableQuery' => sortableTitle($query),
            'more'          => (count($books) > $maxResults),
        ];

        // Results
        $results = [];
        if ($maxResults > 0) {
            $books           = array_slice($books, 0, $maxResults);
            $return['shown'] = count($books);
        }

        foreach ($books as $book) {
            $results[] = [
                'book_id'    => $book->book_id,
                'title'      => $book->title,
                'subtitle'   => $book->subtitle,
                'part'       => $book->part,
                'series'     => $book->series_title,
                'authors'    => $book->getAuthors(),
                'section_id' => $book->section_id,
            ];
        }

        $return['results'] = $results;

        // Return
        return json_encode($return, JSON_PRETTY_PRINT);
    }

    /**
     * Sort books by similarity to the search string
     */
    protected function similarSort(array &$books, string $query): void
    {
        $query = sortableTitle($query);

        usort($books, static function ($a, $b) use ($query) {
            $distanceA = levenshtein($query, $a->searchString);
            $distanceB = levenshtein($query, $b->searchString);

            if ($distanceA === $distanceB) {
                return 0;
            }
            if ($distanceA < $distanceB) {
                return -1;
            }

            return 1;
        });
    }
}
