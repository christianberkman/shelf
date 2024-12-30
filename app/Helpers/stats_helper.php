<?php

if (! function_exists('bookCount')) {
    /**
     * Get number of books (titles)
     */
    function bookCount(?string $sectionId = null): ?int
    {
        $bookModel = model('BookModel');

        if (! empty($sectionId)) {
            $bookModel = $bookModel->where('section_id', $sectionId);
        }

        $books = $bookModel->select('count(book_id) as `count`')->asArray()->first();

        return $books['count'];
    }
}

if (! function_exists('copyCount')) {
    /**
     * Get number of copies
     */
    function copyCount(?string $sectionId = null): ?int
    {
        $bookModel = model('BookModel');

        if (! empty($sectionId)) {
            $bookModel = $bookModel->where('section_id', $sectionId);
        }

        $books = $bookModel->select('sum(count) as `count`')->asArray()->first();

        return $books['count'];
    }
}

if (! function_exists('seriesCount')) {
    /**
     * Get number of series
     */
    function seriesCount(): ?int
    {
        $seriesModel = model('SeriesModel');

        $series = $seriesModel->select('count(series_id) as `count`')->asArray()->first();

        return $series['count'];
    }
}

if (! function_exists('authorCount')) {
    /**
     * Get number of authors
     */
    function authorCount(): ?int
    {
        $authorModel = model('AuthorModel');

        $authors = $authorModel->select('count(author_id) as `count`')->asArray()->first();

        return $authors['count'];
    }
}

if (! function_exists('sectionCount')) {
    /**
     * Get number of sections
     */
    function sectionCount(): ?int
    {
        $sectionModel = model('SectionModel');

        $sections = $sectionModel->select('count(section_id) as `count`')->asArray()->first();

        return $sections['count'];
    }
}
