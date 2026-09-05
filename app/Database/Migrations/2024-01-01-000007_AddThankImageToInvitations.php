<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddThankImageToInvitations extends Migration
{
    public function up()
    {
        $fields = [
            'thank_image' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'thank_you_text',
            ],
        ];
        
        $this->forge->addColumn('invitations', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('invitations', 'thank_image');
    }
}

