<?php

namespace App\Controllers;

class Books extends BaseController
{
    public function find()
    {
        return view('books/find/form');
    }

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
