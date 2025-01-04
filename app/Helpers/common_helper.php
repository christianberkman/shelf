<?php

if (! function_exists('alert')) {
    /**
     * Return the alert view
     */
    function alert(string $heading, string $body, ?string $class = 'primary'): string
    {
        return view('alert', ['heading' => $heading, 'body' => $body, 'class' => $class]);
    }
}

if (! function_exists('bookModel')) {
    /**
     * Return shared instance of App\Models\BookModel
     */
    function bookModel()
    {
        return model('BookModel');
    }
}

if (! function_exists('authorModel')) {
    /**
     * Return shared instance of App\Models\AuthorModel
     */
    function authorModel()
    {
        return model('AuthorModel');
    }
}

if (! function_exists('booksAuthorsModel')) {
    /**
     * Return shared instance of App\Models\BooksAuthorsModel
     */
    function booksAuthorsModel()
    {
        return model('BooksAuthorsModel');
    }
}

if (! function_exists('sectionModel')) {
    /**
     * Return shared instance of App\Models\SectionModel
     */
    function seriesModel()
    {
        return model('SectionModel');
    }
}

if (! function_exists('seriesModel')) {
    /**
     * Return shared instance of App\Models\SeriesModel
     */
    function seriesModel()
    {
        return model('SeriesModel');
    }
}
