<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLivestreamEnabledToInvitations extends Migration
{
    public function up()
    {
        $fields = [
            'livestream_enabled' => [
                'type' => 'INTEGER',
                'default' => 0,
                'after' => 'livestream_description',
            ],
        ];
        
        $this->forge->addColumn('invitations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('invitations', 'livestream_enabled');
    }
}

