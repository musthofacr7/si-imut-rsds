<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSettingIndikatorMutuTable extends Migration
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
        // Add unique constraint to prevent duplicate combinations
        $this->forge->addUniqueKey(['indikator_mutu_id', 'area_pengukuran_id']);
        $this->forge->createTable('setting_indikator_mutu');
    }

    public function down()
    {
        $this->forge->dropTable('setting_indikator_mutu');
    }
}
