<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTemplatesTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'name' => [
                'type' => 'TEXT',
            ],
            'slug' => [
                'type' => 'TEXT',
                'unique' => true,
            ],
            'description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'preview_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'template_config' => [
                'type' => 'TEXT',
            ],
            'is_active' => [
                'type' => 'INTEGER',
                'default' => 1,
            ],
            'is_premium' => [
                'type' => 'INTEGER',
                'default' => 0,
            ],
            'category' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('templates');
    }

    public function down()
    {
        $this->forge->dropTable('templates');
    }
}

