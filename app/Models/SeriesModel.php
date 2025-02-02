<?php

namespace App\Models;

use CodeIgniter\Model;
use Faker\Generator;

class SeriesModel extends Model
{
    protected $table            = 'series';
    protected $primaryKey       = 'series_id';
    protected $useAutoIncrement = true;
    protected $returnType       = \App\Entities\SeriesEntity::class;
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'series_title', 'note',
    ];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts            = [];
    protected array $castHandlers     = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'series_title' => 'required|string|is_unique[series.series_title]',
        'note'         => 'permit_empty|string',
    ];
    protected $validationMessages = [
        'series_title' => [
            'is_unique' => 'The Series Title needs to be unique',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Filter by title query
     */
    public function filterByTitle(string $query): self
    {
        $words = explode(' ', $query);

        foreach ($words as $word) {
            seriesModel()->like('series_title', $word);
        }

        return $this;
    }

    /**
     * Include book_count per series_id
     */
    public function withBookCount(): self
    {
        return $this
            ->select('series.*')
            ->select('count(book_id) as `book_count`')
            ->join('books', 'books.series_id = series.series_id', 'left')
            ->groupBy('series.series_id');
    }

    /**
     * Fabricator
     */
    public function fake(Generator &$faker): array
    {
        return [
            'series_title' => $faker->catchPhrase(),
            'note'         => 'Generated by fake:series command',
        ];
    }
}
