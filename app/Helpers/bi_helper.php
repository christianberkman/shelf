<?php

if (! function_exists('bi')) {
    /**
     * Return bootstrap-icon HTML
     * 
     * @string|null $alias bootstrap-icon alias
     */
    function bi(?string $alias = null): string
    {
        if ($alias === null) {
            $alias = 'bootstrap';
        }

        $icon = match ($alias) {
            'add'      => 'plus',
            'check'    => 'check-lg',
            'delete'   => 'trash',
            'find'     => 'search',
            'manage'   => 'gear',
            'sections' => 'collection',
            'view'     => 'eye',

            default => $alias
        };

        return "<i class=\"bi bi-{$icon}\"></i>";
    }
}
