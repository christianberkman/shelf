<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Test\Fabricator;

class FakeBook extends BaseCommand
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
    protected $name = 'fake:book';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Fake one or more books';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'fake:book [number]';

    /**
     * The Command's Arguments
     *
     * @var array<string, string>
     */
    protected $arguments = ['number' => 'Number of books'];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        $count = (int) ($params[0] ?? 1);
        if ($count <= 0) {
            $count = 1;
        }

        CLI::write("Inserting {$count} fake books...");

        $bookModel = model('BookModel');

        $fabricator = new Fabricator($bookModel);
        $books      = $fabricator->make($count);

        // Link author to book
        $authorModel       = model('AuthorModel');
        $booksAuthorsModel = model('BooksAuthorsModel');
        $authorIds         = $authorModel->findColumn('author_id');

        $bookCount = 0;
        foreach ($books as $book) {
            $bookId = $bookModel->insert($book);
            if (! $bookId) {
                CLI::write('Error faking books (BooksModel)', 'red');

                return EXIT_ERROR;
            }

            $authorCount = random_int(0, 3);

            for ($i = 1; $i <= $authorCount; $i++) {
                $data = [
                    'book_id'   => $bookId,
                    'author_id' => $authorIds[array_rand($authorIds)],
                ];
                $insert = $booksAuthorsModel->insert($data);
                if (! $insert) {
                    CLI::write('Error faking books (BooksAuthorsModel)', 'red');

                    return EXIT_ERROR;
                }
            }

            $bookCount++;
            CLI::showProgress($bookCount, $count);
        }

        CLI::showProgress(false);

        if ($count === 1) {
            d($books[0]->toArray());
            CLI::newLine();
        }

        CLI::write("{$count} Fake books inserted", 'green');

        return EXIT_SUCCESS;
    }
}
