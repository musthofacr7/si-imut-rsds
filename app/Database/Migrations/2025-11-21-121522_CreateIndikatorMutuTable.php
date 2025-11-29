<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateIndikatorMutuTable extends Migration
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
            'judul_indikator' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'jenis_indikator_id' => [
                'type' => 'INT',
                'constraint' => 11,
                'unsigned' => true,
            ],
            'definisi_operasional' => [
                'type' => 'TEXT',
            ],
            'numerator' => [
                'type' => 'TEXT',
            ],
            'denumerator' => [
                'type' => 'TEXT',
            ],
            'target_pencapaian' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'kriteria_inklusi' => [
                'type' => 'TEXT',
            ],
            'kriteria_eksklusi' => [
                'type' => 'TEXT',
            ],
            'sumber_data' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'frekuensi_pengumpulan_data' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'periode_analisis_data' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'rencana_analisis' => [
                'type' => 'TEXT',
            ],
            'instrumen_pengambilan_data' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
            ],
            'area_pengukuran' => [
                'type' => 'VARCHAR',
                'constraint' => 255,
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
        $this->forge->addForeignKey('jenis_indikator_id', 'jenis_indikator', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('indikator_mutu');
    }

    public function down()
    {
        $this->forge->dropTable('indikator_mutu');
    }
}
