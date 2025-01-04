<?php

namespace App\Controllers;

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
        d($this->request->getPost());

        $db = db_connect();
        $db->transStart();

        // Book information
        $book = $this->getBook($bookId);

        $book->title    = $this->request->getPost('title');
        $book->subtitle = $this->request->getPost('subtitle');
        $book->part     = $this->request->getPost('part');
        $book->count    = $this->request->getPost('count');
        $book->price    = $this->request->getPost('price');
        $book->note     = $this->request->getPost('note');

        if ($book->hasChanged()) {
            $bookUpdate = bookModel()->update($book->book_id, $book);
            if (! $bookUpdate) {
                return redirect()->back()->withInput()->with('alert', 'error-book');
            }
        }

        // Series
        // ...todo...

        // Existing Authors
        $authorIds = $this->request->getPost('author_ids') ?? [];

        if (count($authorIds) === 0) {
            return redirect()->back()->withInput()->with('alert', 'no-authors');
        }

        $existingAuthors = booksAuthorsModel()->syncBookAuthorIds($book->book_id, $authorIds);
        if (! $existingAuthors) {
            return redirect()->back()->withInput()->with('alert', 'error-authors');
        }

        // Create new authors
        $createAuthors = $this->request->getPost('create_authors');

        if ($createAuthors !== null) {
            foreach ($createAuthors as $createAuthor) {
                $author       = new \App\Entities\AuthorEntity();
                $author->name = $createAuthor;

                $insertAuthor = authorModel()->insert($author);
                if (! $insertAuthor) {
                    return redirect()->back()->withInput()->with('alert', 'error-authors');
                }

                $addAuthor = booksAuthorsModel()
                    ->set('book_id', $book->book_id)
                    ->set('author_id', $insertAuthor)
                    ->insert();
                if (! $addAuthor) {
                    return redirect()->back()->withInput()->with('alert', 'error-authors');
                }
            }
        }

        $db->transComplete();

        return redirect()->back()->with('alert', 'success');
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
        $bookModel = model('BookModel')->join('series', 'series.series_id = books.series_id');
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

        // Return
        $return = [
            'message' => 'ok',
            'count'   => count($books),
            'query'   => $query,
            'more'    => (count($books) > $maxResults),
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
}
