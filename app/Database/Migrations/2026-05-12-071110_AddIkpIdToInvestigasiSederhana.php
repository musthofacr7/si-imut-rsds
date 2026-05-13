<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddIkpIdToInvestigasiSederhana extends Migration
{
    public function up()
    {
        $this->forge->addColumn('investigasi_sederhana', [
            'ikp_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'id',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('investigasi_sederhana', 'ikp_id');
    }
}
