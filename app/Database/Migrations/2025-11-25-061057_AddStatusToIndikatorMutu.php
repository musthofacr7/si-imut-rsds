<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatusToIndikatorMutu extends Migration
{
    public function up()
    {
        $fields = [
            'status' => [
                'type' => 'ENUM',
                'constraint' => ['aktif', 'tidak aktif'],
                'default' => 'aktif',
                'after' => 'area_pengukuran',
            ],
        ];
        
        $this->forge->addColumn('indikator_mutu', $fields);
        
        // Add index for better query performance
        $this->forge->addKey('status', false, false, 'idx_status');
    }

    public function down()
    {
        $this->forge->dropColumn('indikator_mutu', 'status');
    }
}
