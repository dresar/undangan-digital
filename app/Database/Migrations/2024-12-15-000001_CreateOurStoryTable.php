<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateOurStoryTable extends Migration
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
            'invitation_id' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'year' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
            ],
            'title' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'story_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'story_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'display_order' => [
                'type' => 'INTEGER',
                'constraint' => 11,
                'default' => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invitation_id');
        $this->forge->createTable('our_story');
    }

    public function down()
    {
        $this->forge->dropTable('our_story');
    }
}

