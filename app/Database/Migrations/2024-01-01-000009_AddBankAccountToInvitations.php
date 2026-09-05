<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddBankAccountToInvitations extends Migration
{
    public function up()
    {
        $fields = [
            'bank_name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'null' => true,
                'after' => 'livestream_enabled',
            ],
            'bank_account_number' => [
                'type' => 'VARCHAR',
                'constraint' => 50,
                'null' => true,
                'after' => 'bank_name',
            ],
            'bank_account_name' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'bank_account_number',
            ],
            'donation_enabled' => [
                'type' => 'INTEGER',
                'default' => 0,
                'after' => 'bank_account_name',
            ],
        ];
        
        $this->forge->addColumn('invitations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('invitations', ['bank_name', 'bank_account_number', 'bank_account_name', 'donation_enabled']);
    }
}

