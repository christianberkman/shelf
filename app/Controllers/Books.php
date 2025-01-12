<?php

namespace App\Controllers;

use App\Entities\AuthorEntity;
use App\Entities\BookEntity;
use App\Entities\SeriesEntity;
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
     * GET /books/$bookId
     */
    public function view(int $bookId)
    {
        $book = $this->getBook($bookId);

        $data = [
            'crumbs' => [
                ['Find a book', 'books/find'],
            ],
            'current' => $book->title,
            'book'    => $book,
        ];

        return view('books/view', $data);
    }

    /**
     * POST /books/$bookId
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

        $updateSeries = $this->updateBookSeries($book);
        if (! $updateSeries) {
            redirect()->back()->withInput()->with('alert', 'error-series');
        }

        if ($book->hasChanged()) {
            $bookUpdate = bookModel()->update($book->book_id, $book);
            if (! $bookUpdate) {
                return redirect()->back()->withInput()->with('alert', 'error-book');
            }
        }

        $this->updateBookSeries($book);

        $syncAuthors = $this->syncBookAuthors($book, $alert);
        if (! $syncAuthors) {
            return redirect()->back()->withInput()->with('alert', $alert);
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
     * POST /books/new
     */
    public function insert()
    {
        $book = new BookEntity();

        $db = db_connect();
        $db->transStart();

        $this->updateBookEntity($book);

        $updateSeries = $this->updateBookSeries($book);
        if (! $updateSeries) {
            redirect()->back()->withInput()->with('alert', 'error-series');
        }

        $insert = bookModel()->insert($book);
        if (! $insert) {
            return redirect()->back()->withInput()->with('alert', 'error')->with('errors', bookModel()->validation->getErrors());
        }
        $book->book_id = $insert;

        $syncAuthors = $this->syncBookAuthors($book, $alert);
        if (! $syncAuthors) {
            return redirect()->back()->withInput()->with('alert', $alert);
        }

        $db->transComplete();

        return redirect()->to("books/{$book->book_id}")->with('alert', 'insert-success');
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

    /**
     * Update [books.series_id] or create a new series if needed
     *
     * @param [type] $book
     */
    private function updateBookSeries(&$book): bool
    {
        $seriesId = $this->request->getPost('series_id');

        // Create new series
        $addSeries = $this->request->getPost('series_add');
        if (! empty($addSeries)) {
            $series               = new SeriesEntity();
            $series->series_title = $addSeries;

            $match = seriesModel()->where('series_title', $series->series_title)->first();

            if ($match === null) {
                $insert = seriesModel()->insert($series);
                if (! $insert) {
                    return false;
                }
                $seriesId = $insert;
            } else {
                $seriesId = $match->series_id;
            }
        }

        $book->series_id = $seriesId;

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
        $authorIds     = $this->request->getPost('author_ids') ?? [];
        $createAuthors = $this->request->getPost('create_authors') ?? [];

        // At least one (existing or new) author
        if (count($authorIds) + count($createAuthors) === 0) {
            $alert = 'no-authors';

            return false;
        }

        // Existing authors
        $existingAuthors = booksAuthorsModel()->syncBookAuthorIds($book->book_id, $authorIds);
        if (! $existingAuthors) {
            $alert = 'error-authors';

            return false;
        }

        // Create new authors
        if ($createAuthors !== null) {
            foreach ($createAuthors as $createAuthor) {
                $author       = new AuthorEntity();
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
     * GET /books/find
     */
    public function find()
    {
        $data = [
            'current' => 'Find a book',
        ];

        return view('books/find', $data);
    }

    /**
     * GET /books/finds/all
     */
    public function browse()
    {
        // Sort
        $sort = $this->request->getGet('sort');

        switch ($sort) {
            case 'title':
            default:
                bookModel()->orderBy('title');
                break;

            case 'section':
                bookModel()->orderBy('section_id');
                break;

            case 'count_asc':
                bookModel()->orderBy('count ASC');
                break;

            case 'count_desc':
                bookModel()->orderBy('count DESC');
                break;
        }

        // Query
        $query = $this->request->getGet('query');
        if ($query !== null) {
            bookModel()->filterBySearchString($query);
        }

        // Section
        $sectionId = $this->request->getGet('section_id');
        if ($sectionId !== null) {
            $section = sectionModel()->find($sectionId);
            if ($section !== null) {
                bookModel()->where('section_id', $sectionId);
            }
        }

        $books = bookModel()->paginate(20);

        $data = [
            'sort'      => $sort,
            'sectionId' => $sectionId,
            'query'     => $query,
            'current'   => 'Browse Books',
            'books'     => $books,
            'pager'     => bookModel()->pager,
        ];

        return view('books/all', $data);
    }

    /**
     * GET /books/find/json
     */
    public function json()
    {
        // Query
        $minChars      = 3;
        $maxResults    = (int) $this->request->getGet('max');
        $query         = trim($this->request->getGet('q'));
        $sortableQuery = sortableTitle($query);

        if (strlen($query) < $minChars) {
            return json_encode([
                'msg'       => 'query-too-short',
                'min-chars' => $minChars,
            ]);
        }

        // Find
        $books = bookModel()
            ->join('series', 'series.series_id = books.series_id', 'left')
            ->filterBySearchString($query)
            ->findAll();

        // No results
        if (count($books) === 0) {
            return json_encode([
                'msg'           => 'no-results',
                'query'         => $query,
                'sortableQuery' => sortableTitle($query),
            ]);
        }

        $this->similarSort($books, $sortableQuery);

        // Return
        $return = [
            'message'       => 'ok',
            'count'         => count($books),
            'q'             => $query,
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
