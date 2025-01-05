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

if (! function_exists('hasValidationError')) {
    /**
     * Return true if the field is listed amount the errors
     */
    function hasValidationError(string $field, ?array $errors = null)
    {
        if ($errors === null) {
            $errors = session('errors') ?? [];
        }

        return array_key_exists($field, $errors);
    }
}

if (! function_exists('validationMessage')) {
    /**
     * Return the validation message for the field if set
     */
    function validationMessage(string $field, ?array $errors = null)
    {
        if ($errors === null) {
            $errors = session('errors') ?? [];
        }

        if (! array_key_exists($field, $errors)) {
            return null;
        }

        return $errors[$field];
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
        return model('SeriesModel');
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
