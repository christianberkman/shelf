<?php
namespace App\Publishers;
use CodeIgniter\Publisher\Publisher;

/**
 * Publishes the composer package christianberkma/bookf-format to app/Libraries
 */

 class BookFormatPublisher extends Publisher
 {
    
    protected $source = VENDORPATH . 'christianberkman/sortable-book/src/';
    
    protected $destination = APPPATH . 'Libraries/';

    public function publish(): bool
    {
        return $this
            ->addFile($this->source . 'sortable-book.php')
            ->merge(true);
    }
 }