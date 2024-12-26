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
        $this->attributes['name'] = sortableAuthor($value);

        return $this;
    }
}
