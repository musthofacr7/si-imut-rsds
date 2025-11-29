<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserAreaPengukuranTable extends Migration
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
            'user_id' => [
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
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('area_pengukuran_id', 'area_pengukuran', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('user_area_pengukuran');
    }

    public function down()
    {
        $this->forge->dropTable('user_area_pengukuran');
    }
}
