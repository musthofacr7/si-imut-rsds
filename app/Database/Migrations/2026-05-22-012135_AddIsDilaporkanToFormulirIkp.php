<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIsDilaporkanToFormulirIkp extends Migration
{
    public function up()
    {
        $this->forge->addColumn('formulir_ikp', [
            'is_dilaporkan' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('formulir_ikp', 'is_dilaporkan');
    }
}
