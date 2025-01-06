<?php

namespace App\Controllers;

use Exception;

class Series extends BaseController
{
    protected $model;
    protected $serie;

    private function getSeries(int $seriesId)
    {
        $serie = seriesModel()->find($seriesId);

        if ($serie === null) {
            throw new Exception("Serie with ID {$seriesId} does not exist");
        }

        return $serie;
    }

    /**
     * GET /serie/new
     */
    public function new()
    {
        $data = [
            'current'     => 'Add new serie',
            'seriesTitle' => $this->request->getGet('series_title'),
        ];

        return view('series/new', $data);
    }

    /**
     * POST /serie/new
     */
    public function insert()
    {
        $series_title = $this->request->getPost('series_title');
        if (empty($series_title)) {
            return redirect()->to('series/new');
        }

        $series               = new \App\Entities\SeriesEntity();
        $series->series_title = $series_title;

        // Find exact match
        $match = seriesModel()->where('series_title', $series->series_title)->first();
        if ($match) {
            return redirect()->to("series/{$match->series_id}")->with('alert', 'duplicate');
        }

        // Insert
        $insert = seriesModel()->insert($series);
        if (! $insert) {
            return redirect()->back()->withInput()->with('alert', 'error')->with('errors', seriesModel()->validation->getErrors());
        }

        return redirect()->to("series/{$insert}")->with('alert', 'added');
    }

    /**
     * GET /serie/$seriesId
     */
    public function view(int $seriesId)
    {
        $series = $this->getSeries($seriesId);

        $bookIds = bookModel()->where('series_id', $series->series_id)->findColumn('book_id');
        $books   = bookModel()->whereIn('book_id', $bookIds ?? [])->orderBy('title')->findAll();

        $data = [
            'crumbs' => [
                ['Find a series', '/find/series'],
            ],
            'current' => $series->series_title,
            'series'  => $series,
            'books'   => $books,
        ];

        return view('series/view', $data);
    }

    /**
     * POST /serie/$seriesId
     */
    public function update(int $seriesId)
    {
        $series = $this->getSeries($seriesId);

        $series->series_title = $this->request->getPost('series_title');

        if ($series->hasChanged()) {
            $update = seriesModel()->update($seriesId, $series);
            if ($update) {
                return redirect()->back()->with('alert', 'success')->withInput();
            }

            return redirect()->back()->withInput()->with('alert', 'error')->with('errors', seriesModel()->validation->getErrors());
        }

        return redirect()->back();
    }

    /**
     * GET /serie/$seriesId/delete
     */
    public function delete(int $seriesId)
    {
        $series = $this->getSeries($seriesId);

        if ($series->bookCount > 0) {
            throw new Exception('Series is attached to one or more books');
        }

        $delete = seriesModel()->delete($seriesId);

        if ($delete) {
            return redirect()->to('find/series')->with('alert', 'delete-success');
        }

        return redirect()->back()->with('alert', 'delete-error');
    }

    /**
     * GET /find/serie
     */
    public function find()
    {
        $data = [
            'current' => 'Find a series',
        ];

        return view('series/find', $data);
    }

    /**
     * GET /find/series/all?q=$q
     */
    public function all()
    {
        // Sort
        $sort = $this->request->getGet('sort');

        switch ($sort) {
            default:
            case 'title':
                seriesModel()->orderBy('series_title');
                break;

            case 'count_desc':
                seriesModel()->withBookCount()->orderBy('book_count DESC');
                break;

            case 'count_asc':
                seriesModel()->withBookCount()->orderBy('book_count ASC');
                break;
        }

        // Query
        $query = $this->request->getGet('q');
        if ($query !== null) {
            $series = seriesModel()->filterByTitle($query);
        }

        $series = seriesModel()->paginate(20);

        $data = [
            'query'   => $query,
            'sort'    => $sort,
            'current' => 'Browse Series',
            'series'  => $series,
            'pager'   => seriesModel()->pager,
        ];

        return view('series/all', $data);
    }

    /**
     * GET /find/serie/ajax
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

        $series = seriesModel()->orderBy('series_title')->filterByTitle($query)->findAll();

        // No results
        if (count($series) === 0) {
            return json_encode([
                'msg'           => 'no-results',
                'query'         => $query,
                'sortableQuery' => sortableTitle($query),
            ]);
        }

        // Return
        $return = [
            'message'       => 'ok',
            'count'         => count($series),
            'query'         => $query,
            'more'          => (count($series) > $maxResults),
            'sortableQuery' => sortableTitle($query),
            'exactMatch'    => in_array(sortableTitle($query), array_column($series, 'series_title'), true),
        ];

        // Results
        $results = [];
        if ($maxResults > 0) {
            $series          = array_slice($series, 0, $maxResults);
            $return['shown'] = count($series);
        }

        foreach ($series as $serie) {
            $results[] = [
                'series_id'    => $serie->series_id,
                'series_title' => $serie->series_title,
                'count'        => $serie->getBookCount(),
            ];
        }

        $return['results'] = $results;

        // Return
        return json_encode($return, JSON_PRETTY_PRINT);
    }
}
