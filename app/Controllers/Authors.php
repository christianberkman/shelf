<?php

namespace App\Controllers;

use Exception;

class Authors extends BaseController
{
    protected $model;
    protected $author;

    private function getAuthor(int $authorId)
    {
        $author = (model('AuthorModel'))->find($authorId);

        if ($author === null) {
            throw new Exception("Author with ID {$authorId} does not exist");
        }

        return $author;
    }

    /**
     * GET /author/$authorId
     */
    public function view(int $authorId)
    {
        $author = $this->getAuthor($authorId);

        $bookIds = (model('BooksAuthorsModel'))->where('author_id', $author->author_id)->findColumn('book_id');
        $books   = (model('BookModel'))->whereIn('book_id', $bookIds)->findAll();

        $data = [
            'crumbs' => [
                ['FInd an author', '/authors/find'],
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
            $update = (model('AuthorModel'))->update($authorId, $author);
            if ($update) {
                return redirect()->back()->with('alert', 'success');
            }

            return redirect()->back()->with('alert', 'error');
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

        $delete = (model('AuthorModel'))->delete($authorId);

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
        $maxResults = (int) $this->request->getGet('max');
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
            (model('AuthorModel'))->like('name', $word);
        }
        (model('AuthorModel'))->orderBy('name');
        $authors = (model('AuthorModel'))->findAll();

        // No results
        if (count($authors) === 0) {
            return json_encode([
                'msg'   => 'no-results',
                'query' => $query,
            ]);
        }

        // Return
        $return = [
            'message' => 'ok',
            'count'   => count($authors),
            'query'   => $query,
            'more'    => (count($authors) > $maxResults),
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
