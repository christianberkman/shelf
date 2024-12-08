<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FindBook extends BaseCommand
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
    protected $name = 'find:book';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Find a book';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'find:book';

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        CLI::write('Find a book');

        $bookModel = model('BookModel');
        $listItem  = 0;

        // Query and get options
        while ($listItem === '0') {
            $query = CLI::prompt("\tQuery");

            $books = $bookModel->searchString($query)->findALl(5);

            // Create list of options
            $listItems   = [];
            $listItems[] = 'Refine query';

            foreach ($books as $book) {
                $listItems[] = $book->getDisplayTitle();
            }

            $listItem = CLI::promptByKey('Choice: ', $listItems);
            CLI::newLine();
        }

        $book = $books[$listItem - 1];

        // Display book
        $tableRows = [
            ['id', $book->book_id],
            ['title', $book->title],
            ['subtitle', $book->subtitle],
            ['series', $book->getSeriesTitle()],
            ['part', $book->part],
            ['authors', $book->getAuthors()],
        ];

        CLI::table($tableRows, ['Attribue', 'Value']);
    }
}
