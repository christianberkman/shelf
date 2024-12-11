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

        // Capitalize first in every word
        $author = ucwords($value);

        // Move surname before initials
        $author = preg_replace('/^(([A-Z]\.?\s?)+)\s(.+)$/', '$3, $1', $author);

        // Convert single letters to initials
        $author = preg_replace(('/\b([a-zA-Z])\b/'), '$1.', $author);

        // Remove whitespace from initials
        $author = preg_replace('/([A-Z])\. /m', '$1.', $author);

        // Remove double whitespace
        $author = preg_replace('/\s+/', ' ', $author);

        // Remove double full stop
        $author = preg_replace('/\.\./', '.', $author);

        // Add comma after surname
        $author = preg_replace('/^([\w\s]+)\s([A-Z](?:\.|\s[A-Z](?:\.|$))+)$/', '$1, $2', $author);

        // Trim
        return trim($author);
    }
}
