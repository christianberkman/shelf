<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAuthors extends Migration
{
    public function up(): void
    {
        /**
         * Table: authors
         */
        $this->forge->addField([
            'author_id' => [
                'type'           => 'MEDIUMINT',
                'auto_increment' => true,
                'null'           => false,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 128,
                'null'       => false,
            ],
        ]);

        $this->forge->addPrimaryKey('author_id');
        $this->forge->createTable('authors');

        /**
         * Table: books_authors
         */
        $this->forge->addField([
            'rid' => [
                'type'           => 'MEDIUMINT',
                'auto_increment' => true,
                'null'           => false,
            ],
            'book_id' => [
                'type' => 'MEDIUMINT',
                'null' => false,
            ],
            'author_id' => [
                'type' => 'MEDIUMINT',
                'null' => false,
            ],
        ]);

        $this->forge->addPrimaryKey('rid');
        $this->forge->addForeignKey('book_id', 'books', 'book_id');
        $this->forge->addForeignKey('author_id', 'authors', 'author_id');

        $this->forge->createTable('books_authors');
    }

    public function down(): void
    {
        $this->forge->dropTable('books_authors');

        $this->forge->dropTable('authors');
    }
}
