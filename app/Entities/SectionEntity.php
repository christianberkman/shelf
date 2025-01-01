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
        $this->attributes['name'] = htmlspecialchars($value);

        return $this;
    }

    public function setNote(string $value): self
    {
        $this->attributes['note'] = htmlspecialchars($value);

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
