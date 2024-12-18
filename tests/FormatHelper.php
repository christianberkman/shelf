<?php

namespace Tests;

use CodeIgniter\Test\CIUnitTestCase;

/**
 * @internal
 */
final class FormatHelper extends CIUnitTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        helper('format');
    }

    public function testSimple(): void
    {
        $cases = ['Berkman C.', 'Berkman C', 'C. Berkman', 'C Berkman'];

        foreach ($cases as $case) {
            $this->assertSame('Berkman, C.', formatAsAuthor($case));
        }
    }

    public function testTwoInitials(): void
    {
        $cases = ['Schinnell, K. M.', 'Schinnell K M', 'K. M. Schinnell', 'K M Schinnell', 'Schinnell, K M.', 'Schinnell K. M'];

        foreach ($cases as $case) {
            $this->assertSame('Schinnell, K. M.', formatAsAuthor($case), "Failed: {$case}");
        }
    }

    public function testHypen(): void
    {
        $cases = ['Schinnell-Berkman, K. M.', 'Schinnell-berkman k m', 'schinnell-berkman K. m', 'k m schinnell-berkman', 'K. m schinnell-Berkman'];

        foreach ($cases as $case) {
            $this->assertSame('Schinnell-Berkman, K. M.', formatAsAuthor($case), "Failed: {$case}");
        }
    }

    public function testSpaces(): void
    {
        $cases = ['   Berkman C.', 'Berkman    C', 'C.     Berkman', 'C   Berkman', 'Berkman    C'];

        foreach ($cases as $case) {
            $this->assertSame('Berkman, C.', formatAsAuthor($case));
        }
    }
}
