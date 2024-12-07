<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SectionEntity extends Entity
{
    protected $datamap = [];
    protected $casts   = [];

    /**
     * Set functions
     */
    public function setName(string $value): self
    {
        $this->attributes['name'] = strip_tags(ucfirst($value));

        return $this;
    }

    /**
     * Get functions
     */
    public function getProposedId(): ?string
    {
        if (empty($this->attributes['name'])) {
            return null;
        }

        return substr(strtoupper($this->attributes['name']), 0, 3);
    }
}
