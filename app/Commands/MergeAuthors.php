<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Throwable;

class MergeAuthors extends BaseCommand
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
    protected $name = 'merge:authors';

    /**
     * The Command's Description
     *
     * @var string
     */
    protected $description = 'Scan for suspected duplicate authors and interactively merge them';

    /**
     * The Command's Usage
     *
     * @var string
     */
    protected $usage = 'merge:authors';

    /**
     * The Command's Arguments
     *
     * @var array
     */
    protected $arguments = [
        'threshold' => 'Threshold for the levenshtein distance (default: 3)',
    ];

    /**
     * The Command's Options
     *
     * @var array
     */
    protected $options = [
        '--ignore-initials' => 'Ignore initials, may increase suspected duplicates',
        '--reset'           => 'Reset scan progress',
        '--start'           => 'Start at author index',
    ];

    protected int $threshold = 2;
    protected $authors       = [];
    protected int $start     = 0;
    protected $lookUp        = [];
    protected $deletedIds    = [];

    /**
     * Actually execute a command.
     */
    public function run(array $params)
    {
        helper('common');

        // Theshold
        if (array_key_exists('threshold', $params)) {
            $paramThreshold = (int) ($params[0] ?? 0);
            if ($paramThreshold >= 1) {
                $this->threshold = $paramThreshold;
            }
        }

        // Reset
        if (array_key_exists('reset', $params)) {
            $db = db_connect();
            $db->table('authors')->set('duplicate_check', 0)->update();
        }

        // Find all authors
        CLI::print('Finding all authors... ');
        $this->authors = authorModel()
            ->asArray()
            ->orderBy('name')
            ->findAll();
        $authorCount = count($this->authors);
        CLI::print("[{$authorCount} authors]", 'green');
        CLI::newLine();

        // Start
        if (array_key_exists('start', $params)) {
            $this->start = (int) $params['start'] ?? 0;
            CLI::write('Will start at author ' . $this->start);
        }

        CLI::newLine();
        CLI::prompt('Press enter to continue...');

        // Flatten authors
        $this->flattenAuthors($this->authors);

        // Progress
        $curStep = 0;

        foreach ($this->authors as $index => $author) {
            CLI::clearScreen();

            CLI::showProgress($curStep++, $authorCount);

            // Skip if author has been deleted during this session
            if (in_array($author['author_id'], $this->deletedIds, true)) {
                continue;
            }

            // Skip if author is before start index
            if ($index <= $this->start) {
                continue;
            }

            $suspects     = $this->scan($author);
            $suspectCount = count($suspects);

            if ($suspectCount === 0) {
                continue;
            }

            CLI::showProgress(false);
            CLI::write('Author index: ' . $index);
            CLI::newLine();

            CLI::print('Found ');
            CLI::print($author['name'], 'light_blue');
            CLI::print(' with ');
            CLI::print($suspectCount, 'light_blue');
            CLI::print(' suspects');

            foreach ($suspects as $suspect) {
                $doMerge = $this->prompt($author, $suspect);
                if ($doMerge !== false) {
                    if ($doMerge === 'author') {
                        $merge = $this->merge($author, $suspect);
                    } else {
                        $merge = $this->merge($suspect, $author);
                    }

                    if ($merge) {
                        CLI::print('Merged', 'green');
                    } else {
                        CLI::print('Error', 'red');
                    }
                } else {
                    CLI::print('Skipped', 'light_red');
                }
            }
        }

        CLI::newLine();
        CLI::write('Done', 'green');
    }

    /**
     * Flatten the author name. Remove initials, blank space, symbols
     */
    protected function flattenAuthors(array &$authors)
    {
        foreach ($authors as $author) {
            $name = strtolower($author['name']);

            // Explode
            $explode  = explode(',', $name);
            $surname  = $explode[0] ?? '';
            $initials = $explode[1] ?? '';

            // Flatten
            $author['flat_name']     = preg_replace('/[^a-z0-9]/', '', $surname);
            $author['flat_initials'] = preg_replace('/[^a-z0-9]/', '', $initials);

            $flatAuthors[] = $author;
        }

        $authors = $flatAuthors;
    }

    /**
     * Scan $author for matches in all $authors starting with the same letters and having the same initials
     */
    protected function scan(array $author): array
    {
        $suspects = [];

        foreach ($this->authors as $compareAuthor) {
            // Register combination
            $this->lookUp[$author['author_id']][] = $compareAuthor['author_id'];

            // Skip if deleted
            if (in_array($author['author_id'], $this->deletedIds, true)) {
                continue;
            }

            // Skip if combination is already checked
            if (array_key_exists($compareAuthor['author_id'], $this->lookUp)) {
                if (in_array($author['author_id'], $this->lookUp[$compareAuthor['author_id']], true)) {
                    continue;
                }
            }

            // Skip if first letter is different
            if (substr($author['flat_name'], 0, 1) !== substr($compareAuthor['flat_name'], 0, 1)) {
                continue;
            }

            // Skip if IDs match
            if ($author['author_id'] === $compareAuthor['author_id']) {
                continue;
            }

            // Skip if initials do not match
            if ($author['flat_initials'] !== $compareAuthor['flat_initials']) {
                continue;
            }

            // Skip if levenshtein distance is above the threshold
            $distance = levenshtein($author['flat_name'], $compareAuthor['flat_name']);
            if ($distance > $this->threshold) {
                continue;
            }

            $suspects[] = $compareAuthor;
        }

        return $suspects;
    }

    /**
     * Prompt to merge two authors
     *
     * @param array $author  Author to merge to
     * @param array $suspect Author to be merged
     *
     * @return bool
     */
    public function prompt(array $author, array $suspect): false|string
    {
        CLI::newLine();
        CLI::print("Author A:\t");
        CLI::print($author['name'], 'light_blue');
        CLI::print(PHP_EOL);
        CLI::print("Author B:\t");
        CLI::print($suspect['name'], 'light_green');
        CLI::newLine();

        // Auto merge if flat names match
        if ($author['flat_name'] === $suspect['flat_name']) {
            return 'author';
        }

        $prompt = CLI::prompt('Select author to merge into (n for none):', ['n', 'a', 'b']);
        $prompt = strtolower($prompt);

        if ($prompt === 'a') {
            return 'author';
        }
        if ($prompt === 'b') {
            return 'suspect';
        }

        return false;
    }

    protected function merge(array $author, array $suspect): bool
    {
        try {
            // Update author_id in [books_authors]
            booksAuthorsModel()
                ->where('author_id', $suspect['author_id'])
                ->set('author_id', $author['author_id'])
                ->withDeleted()
                ->update();

            // Delete suspect author id from [authors]
            authorModel()->delete($suspect['author_id']);

            // Add to deleted array
            $this->deletedIds[] = $suspect['author_id'];
        } catch (Throwable $e) {
            return false;
        }

        return true;
    }
}
