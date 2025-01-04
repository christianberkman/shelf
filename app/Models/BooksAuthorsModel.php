<?php

namespace App\Models;

use CodeIgniter\Model;
use Throwable;

class BooksAuthorsModel extends Model
{
    protected $table                  = 'books_authors';
    protected $primaryKey             = 'rid';
    protected $useAutoIncrement       = true;
    protected $returnType             = 'array';
    protected $useSoftDeletes         = false;
    protected $protectFields          = true;
    protected $allowedFields          = ['book_id', 'author_id'];
    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;
    protected array $casts            = [];
    protected array $castHandlers     = [];

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules = [
        'book_id'   => 'required|integer',
        'author_id' => 'required|integer',
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

    public function syncBookAuthorIds(int $bookId, array $authorIds): bool
    {
        $this->db->transStart();

        try {
            $this
                ->where('book_id', $bookId)
                ->delete();

            foreach ($authorIds as $authorId) {
                $author = authorModel()->find($authorId);
                if (! $author) {
                    return false;
                }

                $this
                    ->set('book_id', $bookId)
                    ->set('author_id', $authorId)
                    ->insert();
            }
        } catch (Throwable $e) {
            return false;
        }

        $this->db->transComplete();

        return true;
    }
}
