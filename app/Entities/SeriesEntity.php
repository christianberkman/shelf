<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Throwable;

class SeriesEntity extends Entity
{
    protected $datamap = [];
    protected $dates   = ['created_at', 'updated_at', 'deleted_at'];
    protected $casts   = [];

    /**
     * Get functions
     */
    public function getBookCount(): int
    {
        try {
            $books = bookModel()
                ->select('COUNT(book_id) as `count`')
                ->where('series_id', $this->attributes['series_id'])
                ->asArray()
                ->first();

            return $books['count'];
        } catch (Throwable $e) {
            return 0;
        }
    }

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
