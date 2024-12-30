<?php

namespace App\Publishers;

use CodeIgniter\Publisher\Publisher;

/**
 * Publishes the composer package christianberkma/bookf-format to app/Libraries
 */
class BootstrapIconsPublisher extends Publisher
{
    protected $source      = VENDORPATH . 'twbs/bootstrap-icons';
    protected $destination = FCPATH . 'assets/bootstrap-icons';

    public function publish(): bool
    {
        return $this
            ->addPath('font/')
            ->merge(true);
    }
}
