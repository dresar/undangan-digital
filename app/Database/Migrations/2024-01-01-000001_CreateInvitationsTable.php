<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvitationsTable extends Migration
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
            'slug' => [
                'type' => 'TEXT',
                'unique' => true,
            ],
            'title' => [
                'type' => 'TEXT',
            ],
            'content_json' => [
                'type' => 'TEXT',
            ],
            'theme_config' => [
                'type' => 'TEXT',
            ],
            'status' => [
                'type' => 'TEXT',
                'default' => 'draft',
            ],
            'views_count' => [
                'type' => 'INTEGER',
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
            'updated_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('invitations');
    }

    public function down()
    {
        $this->forge->dropTable('invitations');
    }
}

