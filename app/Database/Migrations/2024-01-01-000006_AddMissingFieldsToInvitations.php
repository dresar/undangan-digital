<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMissingFieldsToInvitations extends Migration
{
    public function up()
    {
        $fields = [
            'groom_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bride_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'groom_instagram' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bride_instagram' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'reception_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'reception_end_time' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'countdown_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'location_map_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'location_map_search' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'calendar_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'video_id' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'livestream_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'livestream_id' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'livestream_schedule' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'livestream_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'gallery_images' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'story_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'cover_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'couple_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'venue_message' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'apology_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'thank_you_text' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'event_name_1' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'event_name_2' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'event_date_1' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'event_date_2' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'event_time_1' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'event_time_2' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'dress_code' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'dress_code_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'og_image' => [
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
            'groom_image',
            'bride_image',
            'groom_instagram',
            'bride_instagram',
            'reception_date',
            'reception_end_time',
            'countdown_date',
            'location_map_url',
            'location_map_search',
            'calendar_url',
            'video_id',
            'livestream_url',
            'livestream_id',
            'livestream_schedule',
            'livestream_description',
            'gallery_images',
            'story_text',
            'cover_message',
            'couple_description',
            'venue_message',
            'apology_text',
            'thank_you_text',
            'event_name_1',
            'event_name_2',
            'event_date_1',
            'event_date_2',
            'event_time_1',
            'event_time_2',
            'dress_code',
            'dress_code_image',
            'og_image',
        ];

        // Cek apakah kolom ada sebelum menghapus
        foreach ($fields as $fieldName) {
            if ($this->db->fieldExists($fieldName, 'invitations')) {
                $this->forge->dropColumn('invitations', $fieldName);
            }
        }
    }
}

