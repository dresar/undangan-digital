<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCheckInCardFieldsToInvitations extends Migration
{
    public function up()
    {
        $fields = [
            'check_in_card_enabled' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 0,
                'null' => true,
            ],
            'check_in_card_instructions' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        // Filter hanya kolom yang belum ada
        $fieldsToAdd = [];
        foreach ($fields as $fieldName => $fieldConfig) {
            if (!$this->db->fieldExists($fieldName, 'invitations')) {
                $fieldsToAdd[$fieldName] = $fieldConfig;
            }
        }
        
        // Tambahkan semua kolom sekaligus jika ada yang belum ada
        if (!empty($fieldsToAdd)) {
            $this->forge->addColumn('invitations', $fieldsToAdd);
        }
    }

    public function down()
    {
        $fields = [
            'check_in_card_enabled',
            'check_in_card_instructions',
        ];

        // Cek apakah kolom ada sebelum menghapus
        foreach ($fields as $fieldName) {
            if ($this->db->fieldExists($fieldName, 'invitations')) {
                $this->forge->dropColumn('invitations', $fieldName);
            }
        }
    }
}

