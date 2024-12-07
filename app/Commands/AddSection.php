<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Throwable;

class AddSection extends BaseCommand
{
    /**
     * The Command's Group
     *
     * @var string
     */
    protected $group = 'App';

    /**
     * The Command's Name
     *
     * @var string
     */
    protected $name = 'add:section';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Add a section';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'add:section';

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $sectionModel = model('SectionModel');

        $sectionCount = count($sectionModel->findAll());
        CLI::write("There are currently {$sectionCount} sections in the database.");
        CLI::newLine();
        CLI::write('Add a new section:', 'green');

        // New section
        $section = new \App\Entities\SectionEntity();

        while (empty($section->name)) {
            $section->name = CLI::prompt("\tSection name");

            if (empty($section->name)) {
                CLI::write('Section name cannot be empty', 'red');
            }
        }

        while (empty($section->section_id)) {
            $section->section_id = CLI::prompt("\tSection ID", $section->getProposedId());

            if (empty($section->section_id)) {
                CLI::write('Section ID cannot be empty', 'red');
            }
        }
        CLI::newLine();

        // Insert
        try {
            $insert = $sectionModel->insert($section);
        } catch (Throwable $e) {
            CLI::write('Error inserting section:', 'red');
            CLI::write("\t{$e->getMessage()}", 'red');

            return EXIT_ERROR;
        }

        if (! $insert) {
            CLI::write('Error inserting section:', 'red');
            d($sectionModel->validation->getErrors());
        }

        CLI::write("Section with ID '{$insert}' inserted.", 'green');

        return EXIT_SUCCESS;
    }
}
