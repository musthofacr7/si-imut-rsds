<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModifyNumeratorDenumeratorToDecimal extends Migration
{
    public function up()
    {
        // Modify numerator and denumerator columns to DECIMAL type
        $this->forge->modifyColumn('input_indikator_rs', [
            'numerator' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
            'denumerator' => [
                'type' => 'DECIMAL',
                'constraint' => '10,2',
                'null' => true,
            ],
        ]);
    }

    public function down()
    {
        // Revert back to INT type
        $this->forge->modifyColumn('input_indikator_rs', [
            'numerator' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'denumerator' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
        ]);
    }
}
