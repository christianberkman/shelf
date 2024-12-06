<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Test\Fabricator;

class FakeAuhtor extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'Shelf';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'fake:author';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Fake one or more authors';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'fake:author [number]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'number' => 'Number of authors to fake',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $count = (int) ($params[0] ?? 1);
        if ($count < 1) {
            $count = 1;
        }

        $authorModel = model('AuthorModel');

        $fabricator = new Fabricator($authorModel);
        $authors    = $fabricator->make($count);

        $insert = $authorModel->insertBatch($authors);

        if ($insert) {
            if ($count === 1) {
                d($authors[0]->toArray());
                $db = db_connect();
                CLI::write($db->getLastQuery());
                CLI::newLine();
            }

            CLI::write("{$count} Fake authors inserted", 'green');

            return EXIT_SUCCESS;
        }
        CLI::write('Error faking books', 'red');

        return EXIT_ERROR;
    }
}
