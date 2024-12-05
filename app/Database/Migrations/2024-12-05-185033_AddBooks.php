<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBooks extends Migration
{
    public function up(): void
    {
        /**
         * Table: books
         */
        $this->forge->addField([
            'book_id' => [
                'type'           => 'MEDIUMINT',
                'auto_increment' => true,
                'null'           => false,
            ],
            'title' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => false,
            ],
            'subtitle' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'part' => [
                'type'       => 'VARCHAR',
                'constraint' => 16,
                'null'       => true,
            ],
            'count' => [
                'type' => 'TINYINT',
                'null' => true,
            ],
            'price' => [
                'type' => 'DECIMAL',
                'null' => true,
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

        $this->forge->addPrimaryKey('book_id');
        $this->forge->createTable('books');
    }

    public function down(): void
    {
        $this->forge->dropTable('books');
    }
}
