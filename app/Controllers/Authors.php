<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class Authors extends BaseController
{
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
