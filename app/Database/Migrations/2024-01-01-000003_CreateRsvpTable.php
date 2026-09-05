<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateRsvpTable extends Migration
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
            'email' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'phone' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'attendance' => [
                'type' => 'TEXT',
            ],
            'guest_count' => [
                'type' => 'INTEGER',
                'default' => 1,
            ],
            'message' => [
                'type' => 'TEXT',
                'null' => true,
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
        $this->forge->createTable('rsvps');
    }

    public function down()
    {
        $this->forge->dropTable('rsvps');
    }
}

