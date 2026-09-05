<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateGuestbookTable extends Migration
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
            ],
            'name' => [
                'type' => 'TEXT',
            ],
            'message' => [
                'type' => 'TEXT',
            ],
            'photo' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_approved' => [
                'type' => 'INTEGER',
                'default' => 0,
            ],
            'ip_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_agent' => [
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
        $this->forge->addKey('invitation_id');
        $this->forge->addKey('is_approved');
        $this->forge->createTable('guestbooks');
    }

    public function down()
    {
        $this->forge->dropTable('guestbooks');
    }
}

