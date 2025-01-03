<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use Exception;

class AuthorEntity extends Entity
{
    protected $datamap = [];
    protected $casts   = [
        'name' => 'string',
    ];

    /**
     * Get functions
     */
    public function getBookCount(): int
    {
        try{
            $books = (model('BooksAuthorsModel'))
                ->select('COUNT(book_id) as `count`')
                ->where('author_id', $this->attributes['author_id'])
                ->asArray()
                ->first();

            return $books['count'];
        } catch(Throwable $e){
            return 0;
        }
    }

    /**
     * Set functions
     */
    public function setName(string $value): self
    {
        $this->attributes['name'] = sortableAuthor($value);

        return $this;
    }
}
