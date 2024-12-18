<?php

/**
 * Format helper
 */

/**
 * Strip tags, remove tabs and newlines
 */
if (! function_exists('sanitizeString')) {
    function sanitizeString(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        // Remove forbidden characters
        $output = preg_replace('/[\r\n\t]+/m', '', $value);

        $output = strip_tags($output);

        return trim($output);
    }
}

/**
 * Move articles the, a and an to the end of the string
 * e.g. 'The bean tree' --> 'Bean tree, the'
 */
if (! function_exists('moveArticle')) {
    function moveArticle(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $output = null;

        // Move the, a and and from beginning to end of title
        $pattern = '/^(The|the|A|a|An|an)\s(.*)/';
        $match   = preg_match($pattern, $value, $matches);
        if ($match) {
            d($matches);
            $output = ucfirst($matches[2]) . ', ' . strtolower($matches[1]);
        }

        $output = ucfirst($output);

        return trim($output);
    }
}

/**
 * Performs sanitizeString and moveArticle
 */
if (! function_exists('formatAsTitle')) {
    function formatAsTitle(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        $title = sanitizeString($value);

        return moveArticle($title);
    }
}

/**
 * Format as author
 * Examples:
 *    W H Shakespeare -> Shakespeare, W.H.
 *    Shakespeare W.H. -> Shakespeare, W.H.
 *    Shakespeare W H -> Shakespear, W.H.
 */
if (! function_exists('formatAsAuthor')) {
    function formatAsAuthor(?string $value): ?string
    {
        if ($value === null) {
            return null;
        }

        // Sanitize
        $author = sanitizeString($value);

        // Remove double spaces
        $author = preg_replace('/(\s)+/', ' ', $author);

        // Add space after comma
        $author = preg_replace('/,([a-zA-Z])/', ', $1', $author);

        // Capitalize first in every word
        $author = ucwords($author, ' -/');

        // Make initials
        $author = preg_replace(('/\b([A-Z])\b\.?/'), '$1.', $author);

        // Move initials behind surname
        $author = preg_replace('/^(([A-Z]\. )+)(.*)/', '$3, $1', $author);

        // Add comma after surname
        $author = preg_replace('/( ?([A-Z]\. ?)+)$/', ',$1', $author);

        // Remove double comma
        $author = preg_replace('/,,/', ',', $author);

        // Trim
        return trim($author);
    }
}
