<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInputIndikatorRsTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type' => 'DATE',
            ],
            'indikator_mutu_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'area_pengukuran_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'numerator' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'denumerator' => [
                'type' => 'INT',
                'constraint' => 11,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('indikator_mutu_id', 'indikator_mutu', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('area_pengukuran_id', 'area_pengukuran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('input_indikator_rs');
    }

    public function down()
    {
        $this->forge->dropTable('input_indikator_rs');
    }
}
