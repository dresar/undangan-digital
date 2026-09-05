<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAnalyticsTable extends Migration
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
            'event_type' => [
                'type' => 'TEXT',
            ],
            'ip_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'user_agent' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'referrer' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'country' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'city' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'device_type' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'browser' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'os' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invitation_id');
        $this->forge->addKey('event_type');
        $this->forge->addKey('created_at');
        $this->forge->createTable('analytics');
    }

    public function down()
    {
        $this->forge->dropTable('analytics');
    }
}

