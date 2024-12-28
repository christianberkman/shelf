<?php
namespace App\Publishers;
use CodeIgniter\Publisher\Publisher;

/**
 * Publishes the composer package christianberkma/bookf-format to app/Libraries
 */

 class HandlebarsPublisher extends Publisher
 {
    
    protected $destination = FCPATH . 'assets/handlebars';

    public function publish(): bool
    {
        return 
            $this
                ->addUri('https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/4.7.8/handlebars.min.js')
                ->merge(true);
    }
 }