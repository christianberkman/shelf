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
    protected $validationMessages   = [];
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
