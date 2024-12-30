<?php

namespace App\Publishers;

use CodeIgniter\Publisher\Publisher;

/**
 * Publishes the composer package christianberkma/bookf-format to app/Libraries
 */
class BootstrapPublisher extends Publisher
{
    protected $source      = VENDORPATH . 'twitter/bootstrap';
    protected $destination = FCPATH . 'assets/bootstrap';

    public function publish(): bool
    {
        return $this
            ->addPath('dist/')
            ->retainPattern('*.min.*')
            ->merge(true);
    }
}
