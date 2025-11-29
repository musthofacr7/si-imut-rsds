<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePjMutuRoleName extends Migration
{
    public function up()
    {
        $this->db->table('auth_groups')
            ->where('name', 'pj_mutu')
            ->update(['name' => 'pj-mutu']);
    }

    public function down()
    {
        $this->db->table('auth_groups')
            ->where('name', 'pj-mutu')
            ->update(['name' => 'pj_mutu']);
    }
}
