<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIdAreaPengukuranToFormulirIkp extends Migration
{
    public function up()
    {
        $this->forge->addColumn('formulir_ikp', [
            'id_area_pengukuran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'ruangan', // tambahkan setelah field ruangan
            ],
        ]);
        
        // You could add a foreign key if needed, but sometimes it's simpler without it if the previous tables didn't use strict foreign keys.
        // Let's add it to be safe.
        // $this->forge->addForeignKey('id_area_pengukuran', 'area_pengukuran', 'id', 'SET NULL', 'CASCADE');
    }

    public function down()
    {
        $this->forge->dropColumn('formulir_ikp', 'id_area_pengukuran');
    }
}
