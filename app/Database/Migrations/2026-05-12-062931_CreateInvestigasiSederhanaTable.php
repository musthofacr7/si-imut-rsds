<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateInvestigasiSederhanaTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            // Penyebab Langsung Insiden
            'alat' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tempat_kerja' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'prosedur' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Penyebab yang melatarbelakangi / akar masalah insiden
            'individu' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tempat_kerja_akar' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'organisasi' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            // Rekomendasi (JSON)
            'rekomendasi' => [
                'type' => 'TEXT', // Will store JSON encoded array
                'null' => true,
            ],
            // Tindakan yang akan dilakukan (JSON)
            'tindakan' => [
                'type' => 'TEXT', // Will store JSON encoded array
                'null' => true,
            ],
            // Manager / Kepala Bagian / Kepala Unit
            'manajer_nama' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'ttd_manajer' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'tgl_mulai_investigasi' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'tgl_selesai_investigasi' => [
                'type' => 'DATE',
                'null' => true,
            ],
            // Manajemen Risiko
            'investigasi_lengkap' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true, // 'ya', 'tidak'
            ],
            'tgl_investigasi_lengkap' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'investigasi_lanjut' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'null'       => true, // 'ya', 'tidak'
            ],
            'grading_ulang' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'null'       => true, // 'hijau', 'kuning', 'merah'
            ],
            // Timestamps
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'deleted_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('investigasi_sederhana');
    }

    public function down()
    {
        $this->forge->dropTable('investigasi_sederhana');
    }
}
