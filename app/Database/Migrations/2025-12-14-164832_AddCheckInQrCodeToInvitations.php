<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCheckInQrCodeToInvitations extends Migration
{
    public function up()
    {
        $fields = [
            'check_in_qr_code' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'check_in_card_instructions',
            ],
            'check_in_qr_code_image' => [
                'type' => 'VARCHAR',
                'constraint' => 500,
                'null' => true,
                'after' => 'check_in_qr_code',
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
            'check_in_qr_code',
            'check_in_qr_code_image',
        ];

        // Cek apakah kolom ada sebelum menghapus
        foreach ($fields as $fieldName) {
            if ($this->db->fieldExists($fieldName, 'invitations')) {
                $this->forge->dropColumn('invitations', $fieldName);
            }
        }
    }
}
