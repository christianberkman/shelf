<?php

namespace App\Publishers;

use CodeIgniter\Publisher\Publisher;

/**
 * Publishes the composer package christianberkma/bookf-format to app/Libraries
 */
class JQueryPublisher extends Publisher
{
    protected $destination = FCPATH . 'assets/jquery';

    public function publish(): bool
    {
        return
            $this
                ->addUri('https://code.jquery.com/jquery-3.7.1.min.js')
                ->merge(true);
    }
}
