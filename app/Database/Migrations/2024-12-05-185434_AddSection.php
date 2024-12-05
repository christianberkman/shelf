<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSection extends Migration
{
    public function up(): void
    {
        /** 
         * Table: sections
         */
        $this->forge->addField([
            'section_id' => [
                'type'       => 'VARCHAR',
                'constraint' => '5',
                'null'       => false,
            ],
            'name' => [
                'type'       => 'VARCHAR',
                'constraint' => 32,
                'null'       => false,
            ],
            'note' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ]);

        $this->forge->addPrimaryKey('section_id');
        $this->forge->createTable('sections');

        /**
         * Books
         */
        $booksFields = [
            'section_id' => [
                'type' => 'VARCHAR', 
                'constraint' => 5,
                'null' => false,
            ],
        ];

        $this->forge->addColumn('books', $booksFields);
        $this->forge->addForeignKey('section_id', 'sections', 'section_id');
        $this->forge->processIndexes('books');
    }

    public function down(): void
    {
        $this->forge->dropForeignKey('books', 'books_section_id_foreign');
        $this->forge->dropColumn('books', 'section_id');
        
        $this->forge->dropTable('sections');

    }
}
