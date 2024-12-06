<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class AuthorEntity extends Entity
{
    protected $datamap = [];
    protected $casts   = [
        'name' => 'string',
    ];

    /**
     * Set functions
     */
    public function setName(string $value): self
    {
        // Capitalize first in every word
        $name = ucwords($value);

        // Move surname before initials
        $name = preg_replace('/^(([A-Z]\.?\s?)+)\s(.+)$/', '$3, $1', $name);

        // Convert single letters to initials
        $name = preg_replace(('/\b([a-zA-Z])\b/'), '$1.', $name);

        // Remove whitespace from initials
        $name = preg_replace('/([A-Z])\. /m', '$1.', $name);

        // Remove double whitespace
        $name = preg_replace('/\s+/', ' ', $name);

        // Remove double full stop
        $name = preg_replace('/\.\./', '.', $name);

        // Trim
        $name = trim($name);

        $this->attributes['name'] = $name;

        return $this;
    }
}
