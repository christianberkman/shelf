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
