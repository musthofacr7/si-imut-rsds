<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Authorization\GroupModel;

class AuthSeeder extends Seeder
{
    public function run()
    {
        // Create Groups
        $groupModel = new GroupModel();
        
        $groupModel->skipValidation(true)->insert([
            'name'        => 'administrator',
            'description' => 'Super Admin',
        ]);
        
        $groupModel->skipValidation(true)->insert([
            'name'        => 'pj-mutu',
            'description' => 'Penanggung Jawab Mutu',
        ]);

        // Create Admin User
        $userModel = new UserModel();

        $user = new User([
            'email'    => 'admin@example.com',
            'username' => 'admin',
            'password' => 'admin',
            'active'   => 1,
        ]);

        $userModel->save($user);

        // Assign User to Group
        $user = $userModel->where('username', 'admin')->first();
        $groupModel->addUserToGroup($user->id, $groupModel->getGroupsForUser($user->id) ? 1 : 1); // Assuming ID 1 is administrator
        
        // Better way to assign group by name to avoid ID assumption issues
        $db = \Config\Database::connect();
        $groupId = $db->table('auth_groups')->where('name', 'administrator')->get()->getRow()->id;
        $groupModel->addUserToGroup($user->id, $groupId);
    }
}
