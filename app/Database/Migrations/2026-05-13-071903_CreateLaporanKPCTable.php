<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLaporanKPCTable extends Migration
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
            'id_area_pengukuran' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'tgl_kpc' => [
                'type' => 'DATE',
                'null' => true,
            ],
            'jam_kpc' => [
                'type' => 'TIME',
                'null' => true,
            ],
            'insiden' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pelapor' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'pelapor_lain' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'lokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'unit_terkait' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'tindakan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'tindakan_oleh' => [
                'type' => 'JSON',
                'null' => true,
            ],
            'tim_terdiri' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'petugas_lainnya' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'pernah_terjadi' => [
                'type'       => 'ENUM',
                'constraint' => ['ya', 'tidak'],
                'null'       => true,
            ],
            'langkah_pencegahan' => [
                'type' => 'TEXT',
                'null' => true,
            ],
            'pembuat_laporan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'paraf_pembuat' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'penerima_laporan' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'paraf_penerima' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'user_id' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
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
        // Kita tidak selalu mengaktifkan foreign key constraint agar tidak kaku, 
        // tapi logikanya berelasi dengan area_pengukuran dan users.
        $this->forge->createTable('laporan_kpc');
    }

    public function down()
    {
        $this->forge->dropTable('laporan_kpc');
    }
}
