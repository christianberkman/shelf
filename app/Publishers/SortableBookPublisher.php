<?php

namespace App\Publishers;

use CodeIgniter\Publisher\Publisher;

/**
 * Publishes the composer package christianberkma/bookf-format to app/Libraries
 */
class SortableBookPublisher extends Publisher
{
    protected $source      = VENDORPATH . 'christianberkman/sortable-books/src/';
    protected $destination = APPPATH . 'Libraries/';

    public function publish(): bool
    {
        return $this
            ->addFile($this->source . 'sortable-book.php')
            ->merge(true);
    }
}
