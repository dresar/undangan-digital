<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAddressToGuestbooks extends Migration
{
    public function up()
    {
        $fields = [
            'address' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'name',
            ],
        ];
        
        $this->forge->addColumn('guestbooks', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('guestbooks', 'address');
    }
}

