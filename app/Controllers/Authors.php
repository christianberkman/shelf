<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Authors extends BaseController
{
    /**
     * GET /authors/$authorId
     */
    public function view(int $authorId)
    {
        $authorModel = model('AuthorModel');
        $author = $authorModel->find($authorId);
        if($author ===null) throw new \Exception("Author with ID {$authorId} does not exist");

        $bookIds = (model('BooksAuthorsModel'))->where('author_id', $authorId)->findColumn('book_id');
        $books = (model('BookModel'))->whereIn('book_id', $bookIds)->findAll();

        $data = [
            'crumbs' => [
                ['FInd an author', '/authors/find'],
            ],
            'current' => $author->name,
            'author' => $author,
            'books' => $books,
        ];

        return view('authors/view', $data);
    }

    /**
     * POST /authors/$authorId
     */
    public function update(int $authorId)
    {
        $authorModel = model('AuthorModel');
        $author = $authorModel->find($authorId);
        if ($author === null) throw new \Exception("Author with ID {$authorId} does not exist");
        
        $author->name = $this->request->getPost('name');

        if($author->hasChanged())
        {
            $update = $authorModel->update($authorId, $author);
            if($update)
            {
                return redirect()->back()->with('alert', 'success');
            }

            return redirect()->back()->with('alert', 'error');
        }

        return redirect()->back();
    }
    
    /**
     * GET /authors/find
     */
    public function find()
    {
        $data = [
            'current' => 'Find an author',
        ];

        return view('authors/find/form', $data);
    }
    
    
    /**
     * GET /authors/ajax
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
       $authorModel = model('AuthorModel');
       $words     = explode(' ', $query);

       foreach ($words as $word) {
           $authorModel->like('name', $word);
       }
       $authorModel->orderBy('name');
       $authors = $authorModel->findAll();

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
           $authors           = array_slice($authors, 0, $maxResults);
           $return['shown'] = count($authors);
       }

       foreach ($authors as $author) {
           $results[] = [
               'author_id'    => $author->author_id,
               'name' => $author->name,
               'count' => $author->getBookCount(),
           ];
       }

       $return['results'] = $results;

       // Return
       return json_encode($return, JSON_PRETTY_PRINT); 
    }
}
