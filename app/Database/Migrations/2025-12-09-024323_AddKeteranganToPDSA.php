<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddKeteranganToPDSA extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pdsa', [
            'keterangan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'deskripsi'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pdsa', 'keterangan');
    }
}
