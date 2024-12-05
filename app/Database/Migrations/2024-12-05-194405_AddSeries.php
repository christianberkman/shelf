<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSeries extends Migration
{
    public function up()
    {
        /**
         * Table: series
         */
        $this->forge->addField([
            'series_id' => [
                'type' => 'MEDIUMINT',
                'auto_increment' => true,
                'null' => false,
            ],
            'series_title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => false,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('series_id');
        $this->forge->createTable('series');

        /**
         * Table: books
         */
        $booksFields = [
            'series_id' => [
                'type' => 'MEDIUMINT',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('books', $booksFields);
        
        $this->forge->addForeignKey('series_id', 'series','series_id');
        $this->forge->processIndexes('books');
    }

    public function down()
    {
        $this->forge->dropForeignKey('books', 'books_series_id_foreign');
        $this->forge->dropColumn('books', 'series_id');
        
        $this->forge->dropTable('series');
    }
}
