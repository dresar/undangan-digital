<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTemplatePathToTemplates extends Migration
{
    public function up()
    {
        $fields = [
            'template_path' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'css_files' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'js_files' => [
                'type' => 'TEXT',
                'null' => true,
            ],
        ];

        $this->forge->addColumn('templates', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('templates', ['template_path', 'css_files', 'js_files']);
    }
}

