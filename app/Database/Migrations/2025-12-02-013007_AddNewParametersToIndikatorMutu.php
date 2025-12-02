<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNewParametersToIndikatorMutu extends Migration
{
    public function up()
    {
        $fields = [
            'dimensi_mutu' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
                'null' => true,
                'after' => 'judul_indikator'
            ],
            'tujuan' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'dimensi_mutu'
            ],
            'standar_target_pencapaian' => [
                'type' => 'VARCHAR',
                'constraint' => 10,
                'null' => true,
                'after' => 'satuan_target_pencapaian'
            ],
        ];
        $this->forge->addColumn('indikator_mutu', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('indikator_mutu', ['dimensi_mutu', 'tujuan', 'standar_target_pencapaian']);
    }
}
