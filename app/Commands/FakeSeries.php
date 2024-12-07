<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Test\Fabricator;

class FakeSeries extends BaseCommand
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
    protected $name = 'fake:series';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Fake one or more series';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'fake:series [count]';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'count' => 'Number of series to fake',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [];

    /**
     * Actually execute a command.
     *
     * @param array $params
     */
    public function run(array $params)
    {
        $count = (int) ($params[0] ?? 1);
        if($count < 1) $count = 1;

        $seriesModel = model('SeriesModel');

        $fabricator = new Fabricator($seriesModel);
        $series = $fabricator->make($count);

        $insert = $seriesModel->insertBatch($series);

        if($insert){
            
            if($count == 1){
                d($series[0]->toArray());
                CLI::newLine();
            }
            
            CLI::write("{$count} Fake series inserted", 'green');

            return EXIT_SUCCESS;
        } else{
            CLI::write('Error inserting series');

            return EXIT_ERROR;
        }
    }
}
