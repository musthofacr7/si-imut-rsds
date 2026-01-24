<?php

namespace App\Controllers;

use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;
use Myth\Auth\Authorization\GroupModel;
use App\Models\AreaPengukuranModel;
use App\Models\UserAreaPengukuranModel;

class Users extends BaseController
{
    protected $userModel;
    protected $groupModel;
    protected $areaPengukuranModel;
    protected $userAreaPengukuranModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->groupModel = new GroupModel();
        $this->areaPengukuranModel = new AreaPengukuranModel();
        $this->userAreaPengukuranModel = new UserAreaPengukuranModel();
    }

    public function index()
    {
        $users = $this->userModel->findAll();
        
        // Get groups for each user
        foreach ($users as $user) {
            $user->groups = $this->groupModel->getGroupsForUser($user->id);
            $user->areas = $this->userAreaPengukuranModel->getUserAreas($user->id);
        }

        return view('users/index', ['users' => $users]);
    }

    public function create()
    {
        $data['area_pengukuran'] = $this->areaPengukuranModel->getActive();
        return view('users/create', $data);
    }

    public function store()
    {
        $rules = [
            'username' => 'required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username]',
            'email'    => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
            'role'     => 'required'
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = new User($this->request->getPost());
        $user->activate();

        if (! $this->userModel->save($user)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        $user = $this->userModel->where('email', $this->request->getPost('email'))->first();
        
        // Assign group
        $groupId = $this->groupModel->where('name', $this->request->getPost('role'))->first()->id;
        $this->groupModel->addUserToGroup($user->id, $groupId);

        // Assign area pengukuran if role is pj-mutu
        if ($this->request->getPost('role') == 'pj-mutu' && $this->request->getPost('area_pengukuran_id')) {
            $db = \Config\Database::connect();
            $db->table('user_area_pengukuran')->insert([
                'user_id' => $user->id,
                'area_pengukuran_id' => $this->request->getPost('area_pengukuran_id'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('users')->with('message', 'User created successfully');
    }

    public function edit($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('users')->with('error', 'User not found');
        }

        $groups = $this->groupModel->getGroupsForUser($id);
        $user->role = !empty($groups) ? reset($groups)['name'] : '';

        // Get user's area pengukuran
        $db = \Config\Database::connect();
        $userArea = $db->table('user_area_pengukuran')->where('user_id', $id)->get()->getRowArray();
        $user->area_pengukuran_id = $userArea ? $userArea['area_pengukuran_id'] : null;

        $data['user'] = $user;
        $data['area_pengukuran'] = $this->areaPengukuranModel->getActive();
        return view('users/edit', $data);
    }

    public function update($id)
    {
        $user = $this->userModel->find($id);
        if (! $user) {
            return redirect()->to('users')->with('error', 'User not found');
        }

        $rules = [
            'username' => "required|alpha_numeric_space|min_length[3]|max_length[30]|is_unique[users.username,id,$id]",
            'email'    => "required|valid_email|is_unique[users.email,id,$id]",
            'role'     => 'required'
        ];

        if ($this->request->getPost('update_password')) {
            $rules['password'] = 'required|min_length[8]';
            $rules['pass_confirm'] = 'required|matches[password]';
        }

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'username' => $this->request->getPost('username'),
            'email'    => $this->request->getPost('email'),
        ];

        if ($this->request->getPost('update_password')) {
            $data['password'] = $this->request->getPost('password');
        }

        $user->fill($data);

        if ($user->hasChanged()) {
            if (! $this->userModel->save($user)) {
                return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
            }
        }

        // Update group
        $this->groupModel->removeUserFromAllGroups($id);
        $groupId = $this->groupModel->where('name', $this->request->getPost('role'))->first()->id;
        $this->groupModel->addUserToGroup($id, $groupId);

        // Update area pengukuran if role is pj-mutu
        $db = \Config\Database::connect();
        $db->table('user_area_pengukuran')->where('user_id', $id)->delete();
        if ($this->request->getPost('role') == 'pj-mutu' && $this->request->getPost('area_pengukuran_id')) {
            $db->table('user_area_pengukuran')->insert([
                'user_id' => $id,
                'area_pengukuran_id' => $this->request->getPost('area_pengukuran_id'),
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        }

        return redirect()->to('users')->with('message', 'User updated successfully');
    }

    public function delete($id)
    {
        $this->userModel->delete($id);
        return redirect()->to('users')->with('message', 'User deleted successfully');
    }

    public function changePassword()
    {
        return view('users/change_password');
    }

    public function updatePassword()
    {
        $rules = [
            'password' => 'required|min_length[8]',
            'pass_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $user = user();
        $user->password = $this->request->getPost('password');

        if (! $this->userModel->save($user)) {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }

        return redirect()->to('profile/change-password')->with('message', 'Password updated successfully');
    }
}
