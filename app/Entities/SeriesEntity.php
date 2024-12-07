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
    public function setSeriesTitle(string $value): self{
        // Remove forbidden characters
        $value = preg_replace('/[\r\n]+/m', '', $value);
        
        $this->attributes['series_title'] = strip_tags($value);

        return $this;
    }
    
     public function setNote(string $value): self{
        $this->attributes['note'] = trim(strip_tags($value));
        return $this;
    }

    
}
