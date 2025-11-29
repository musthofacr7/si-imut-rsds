<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddSatuanTargetToIndikatorMutu extends Migration
{
    public function up()
    {
        $this->forge->addColumn('indikator_mutu', [
            'satuan_target_pencapaian' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
                'default'    => '%',
                'after'      => 'target_pencapaian',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('indikator_mutu', 'satuan_target_pencapaian');
    }
}
