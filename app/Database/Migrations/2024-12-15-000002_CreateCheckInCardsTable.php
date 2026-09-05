<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateCheckInCardsTable extends Migration
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
            'guest_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
            ],
            'qr_code' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'qr_code_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'checked_in' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
            ],
            'checked_in_at' => [
                'type' => 'DATETIME',
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
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey('invitation_id');
        $this->forge->addKey('qr_code');
        $this->forge->createTable('check_in_cards');
    }

    public function down()
    {
        $this->forge->dropTable('check_in_cards');
    }
}

