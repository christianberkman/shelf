<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SeriesEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    /**
     * Set functions
     */
    public function setSeriesTitle(string $value): self
    {
        $this->attributes['series_title'] = sortableTitle($value);

        return $this;
    }

    public function setNote(string $value): self
    {
        $this->attributes['note'] = trim(strip_tags($value));

        return $this;
    }
}
