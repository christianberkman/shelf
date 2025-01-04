<?php

namespace App\Controllers;

use Exception;

class Authors extends BaseController
{
    protected $model;
    protected $author;

    private function getAuthor(int $authorId)
    {
        $author = authorModel()->find($authorId);

        if ($author === null) {
            throw new Exception("Author with ID {$authorId} does not exist");
        }

        return $author;
    }

    /**
     * GET /author/new
     */
    public function new()
    {
        $data = [
            'current' => 'Add new author',
        ];

        return view('authors/new', $data);
    }

    /**
     * POST /author/new
     */
    public function insert()
    {
        $name = $this->request->getPost('name');
        if (empty($name)) {
            return redirect()->to('author/new');
        }

        $author       = new \App\Entities\AuthorEntity();
        $author->name = $name;

        // Find exact match
        $match = authorModel()->where('name', $author->name)->first();
        if ($match) {
            return redirect()->to("author/{$match->author_id}")->with('alert', 'duplicate');
        }

        // Insert
        $insert = authorModel()->insert($author);
        if (! $insert) {
            return redirect()->back()->withInput()->with('alert', 'error')->with('errors', authorModel()->validation->getErrors());
        }

        return redirect()->to("author/{$insert}")->with('alert', 'added');
    }

    /**
     * GET /author/$authorId
     */
    public function view(int $authorId)
    {
        $author = $this->getAuthor($authorId);

        $bookIds = booksAuthorsModel()->where('author_id', $author->author_id)->findColumn('book_id');
        $books   = bookModel()->whereIn('book_id', $bookIds ?? [])->findAll();

        $data = [
            'crumbs' => [
                ['FInd an author', '/find/author'],
            ],
            'current' => $author->name,
            'author'  => $author,
            'books'   => $books,
        ];

        return view('authors/view', $data);
    }

    /**
     * POST /author/$authorId
     */
    public function update(int $authorId)
    {
        $author = $this->getAuthor($authorId);

        $author->name = $this->request->getPost('name');

        if ($author->hasChanged()) {
            $update = authorModel()->update($authorId, $author);
            if ($update) {
                return redirect()->back()->with('alert', 'success')->withInput();
            }

            return redirect()->back()->withInput()->with('alert', 'error')->with('errors', authorModel()->validation->getErrors());
        }

        return redirect()->back();
    }

    /**
     * GET /author/$authorId/delete
     */
    public function delete(int $authorId)
    {
        $author = $this->getAuthor($authorId);

        if ($author->bookCount > 0) {
            throw new Exception('Author is attached to one or more books');
        }

        $delete = authorModel()->delete($authorId);

        if ($delete) {
            return redirect()->to('find/author')->with('alert', 'delete-success');
        }

        return redirect()->back()->with('alert', 'delete-error');
    }

    /**
     * GET /find/author
     */
    public function find()
    {
        $data = [
            'current' => 'Find an author',
        ];

        return view('authors/find', $data);
    }

    /**
     * GET /find/author/ajax
     */
    public function ajax()
    {
        // Query
        $minChars   = 3;
        $maxResults = (int) ($this->request->getGet('max') ?? 10);
        $query      = trim($this->request->getGet('q'));

        if (strlen($query) < $minChars) {
            return json_encode([
                'msg'       => 'query-too-short',
                'min-chars' => $minChars,
            ]);
        }

        // Find
        $words = explode(' ', $query);

        foreach ($words as $word) {
            authorModel()->like('name', $word);
        }
        authorModel()->orderBy('name');
        $authors = authorModel()->findAll();

        // No results
        if (count($authors) === 0) {
            return json_encode([
                'msg'           => 'no-results',
                'query'         => $query,
                'sortableQuery' => sortableAuthor($query),
            ]);
        }

        // Return
        $return = [
            'message'       => 'ok',
            'count'         => count($authors),
            'query'         => $query,
            'more'          => (count($authors) > $maxResults),
            'sortableQuery' => sortableAuthor($query),
            'exactMatch'    => in_array(sortableAuthor($query), array_column($authors, 'name'), true),
        ];

        // Results
        $results = [];
        if ($maxResults > 0) {
            $authors         = array_slice($authors, 0, $maxResults);
            $return['shown'] = count($authors);
        }

        foreach ($authors as $author) {
            $results[] = [
                'author_id' => $author->author_id,
                'name'      => $author->name,
                'count'     => $author->getBookCount(),
            ];
        }

        $return['results'] = $results;

        // Return
        return json_encode($return, JSON_PRETTY_PRINT);
    }
}
