<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPjMutuRole extends Migration
{
    public function up()
    {
        $this->db->table('auth_groups')->insert([
            'name' => 'pj_mutu',
            'description' => 'Penanggung Jawab Mutu - Can only access quality indicator pages'
        ]);
    }

    public function down()
    {
        $this->db->table('auth_groups')->where('name', 'pj_mutu')->delete();
    }
}
