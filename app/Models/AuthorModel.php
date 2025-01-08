<?php

namespace App\Models;

use CodeIgniter\Model;
use Faker\Generator;

class AuthorModel extends Model
{
    protected $table                  = 'authors';
    protected $primaryKey             = 'author_id';
    protected $useAutoIncrement       = true;
    protected $returnType             = \App\Entities\AuthorEntity::class;
    protected $useSoftDeletes         = false;
    protected $protectFields          = true;
    protected $allowedFields          = ['name'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts            = [];
    protected array $castHandlers     = [];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'name' => 'required|string|is_unique[authors.name]',
    ];
    protected $validationMessages = [
        'name' => [
            'is_unique' => 'Author\'s name must be unique',
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
     * Add book count
     */
    public function withBookCount(): self
    {
        return $this
            ->select('authors.*')
            ->select('count(book_id) as `book_count`')
            ->join('books_authors', 'authors.author_id = books_authors.author_id', 'left')
            ->groupBy('authors.author_id');
    }

    /**
     * Filter by name
     */
    public function filterByName(string $query): self
    {
        $words = explode(' ', $query);

        foreach ($words as $word) {
            $this->like('name', $word);
        }

        $this->orderBy('name');

        return $this;
    }

    /**
     * Fabricator
     *
     * @return array<string, string>
     */
    public function fake(Generator &$faker): array
    {
        // Initials
        $initialsCount = random_int(0, 3);
        $initials      = '';

        for ($i = 1; $i <= $initialsCount; $i++) {
            $initials .= ' ' . $faker->randomLetter();
        }

        // LastName, Initials
        $name = $faker->lastName() . (! empty($initials) ? ", {$initials}" : '');

        return ['name' => $name];
    }
}
