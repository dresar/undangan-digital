<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddExtendedFieldsToInvitations extends Migration
{
    public function up()
    {
        $fields = [
            'cover_image' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'groom_name' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bride_name' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'groom_parents' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'bride_parents' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'wedding_date' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'wedding_location' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'wedding_address' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'contact_phone' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'contact_email' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'contact_whatsapp' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'music_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'video_url' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'is_featured' => [
                'type' => 'INTEGER',
                'default' => 0,
            ],
            'tags' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'category' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'meta_description' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'meta_keywords' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'custom_css' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'custom_js' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'published_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'expires_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'password' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'max_views' => [
                'type' => 'INTEGER',
                'default' => 0,
                'null' => true,
            ],
            'rsvp_enabled' => [
                'type' => 'INTEGER',
                'default' => 1,
            ],
            'analytics_enabled' => [
                'type' => 'INTEGER',
                'default' => 1,
            ],
            'social_sharing_enabled' => [
                'type' => 'INTEGER',
                'default' => 1,
            ],
            'print_enabled' => [
                'type' => 'INTEGER',
                'default' => 1,
            ],
            'qr_code_enabled' => [
                'type' => 'INTEGER',
                'default' => 1,
            ],
            'guestbook_enabled' => [
                'type' => 'INTEGER',
                'default' => 0,
            ],
            'template_id' => [
                'type' => 'INTEGER',
                'null' => true,
            ],
            'language' => [
                'type' => 'TEXT',
                'default' => 'id',
            ],
            'timezone' => [
                'type' => 'TEXT',
                'default' => 'Asia/Jakarta',
            ],
        ];

        $this->forge->addColumn('invitations', $fields);
    }

    public function down()
    {
        $fields = [
            'cover_image',
            'groom_name',
            'bride_name',
            'groom_parents',
            'bride_parents',
            'wedding_date',
            'wedding_location',
            'wedding_address',
            'contact_phone',
            'contact_email',
            'contact_whatsapp',
            'music_url',
            'video_url',
            'is_featured',
            'tags',
            'category',
            'meta_description',
            'meta_keywords',
            'custom_css',
            'custom_js',
            'published_at',
            'expires_at',
            'password',
            'max_views',
            'rsvp_enabled',
            'analytics_enabled',
            'social_sharing_enabled',
            'print_enabled',
            'qr_code_enabled',
            'guestbook_enabled',
            'template_id',
            'language',
            'timezone',
        ];

        $this->forge->dropColumn('invitations', $fields);
    }
}

