<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Test\Fabricator;

class FakeSection extends BaseCommand
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
    protected $name = 'fake:section';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Fake a sections';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'fake:section';

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        // Fabricate fake section
        $sectionModel = model('SectionModel');
        $section      = new \App\Entities\SectionEntity();

        $fabricator          = new Fabricator($sectionModel);
        $section->name       = $fabricator->getFaker()->word();
        $section->section_id = $section->getProposedId();

        // Insert
        try {
            $insert = $sectionModel->insert($section);
        } catch (\Throwable $e) {
            CLI::write('Error inserting section:', 'red');
            CLI::write("\t{$e->getMessage()}", 'red');

            return EXIT_ERROR;
        }

        if (! $insert) {
            CLI::write('Error inserting section:', 'red');
            d($sectionModel->validation->getErrors());
        }

        CLI::write("Section '{$section->name} with ID '{$insert}' inserted.", 'green');

        return EXIT_SUCCESS;
    }
}
