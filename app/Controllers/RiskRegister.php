<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RiskRegisterModel;
use Myth\Auth\Models\UserModel;

class RiskRegister extends BaseController
{
    protected $riskRegisterModel;
    protected $userModel;

    public function __construct()
    {
        $this->riskRegisterModel = new RiskRegisterModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Init query
        $builder = $this->riskRegisterModel->select('risk_register.*, users.username, users.email')
            ->join('users', 'users.id = risk_register.user_id', 'left');

        // Filter for PJ Mutu: Only show Risk Register from users in the same Area Pengukuran
        if (in_groups('pj-mutu')) {
            $db = \Config\Database::connect();
            $myId = user_id();
            
            // Get My Area ID(s)
            $myAreas = $db->table('user_area_pengukuran')
                ->select('area_pengukuran_id')
                ->where('user_id', $myId)
                ->get()
                ->getResultArray();
                
            $areaIds = array_column($myAreas, 'area_pengukuran_id');
            
            if (!empty($areaIds)) {
                // Find all users in these areas
                $usersInMyArea = $db->table('user_area_pengukuran')
                    ->select('user_id')
                    ->whereIn('area_pengukuran_id', $areaIds)
                    ->get()
                    ->getResultArray();
                
                $userIds = array_column($usersInMyArea, 'user_id');
                
                // Add filter
                if (!empty($userIds)) {
                    $builder->whereIn('risk_register.user_id', $userIds);
                } else {
                    $builder->where('risk_register.user_id', $myId);
                }
            } else {
                 $builder->where('risk_register.user_id', $myId);
            }
        }

        $riskRegister = $builder->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Daftar Risk Register',
            'risk_register' => $riskRegister,
        ];

        return view('risk_register/index', $data);
    }

    public function create()
    {
        if (!in_groups('pj-mutu')) {
           return redirect()->back()->with('error', 'Hanya PJ Mutu yang dapat membuat Risk Register.');
        }

        $data = [
            'title' => 'Input Risk Register',
        ];

        return view('risk_register/create', $data);
    }

    public function store()
    {
        if (!in_groups('pj-mutu')) {
           return redirect()->back()->with('error', 'Unauthorized.');
        }

        $rules = [
            'file_risk_register' => [
                'rules' => 'uploaded[file_risk_register]|max_size[file_risk_register,10240]|mime_in[file_risk_register,application/pdf,application/force-download,application/x-download,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]|ext_in[file_risk_register,pdf,doc,docx,xls,xlsx]',
                'label' => 'File Risk Register'
            ],
            'deskripsi' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file_risk_register');
        $fileName = $file->getRandomName();
        $file->move('uploads/risk_register', $fileName);

        $this->riskRegisterModel->save([
            'user_id' => user_id(),
            'file_risk_register' => $fileName,
            'deskripsi' => $this->request->getPost('deskripsi'),
            'validasi' => 0, // Default pending
        ]);

        return redirect()->to('risk-register')->with('message', 'Risk Register berhasil ditambahkan.');
    }

    public function view($id)
    {
        $risk_register = $this->riskRegisterModel->select('risk_register.*, users.username')
            ->join('users', 'users.id = risk_register.user_id', 'left')
            ->find($id);

        if (!$risk_register) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail Risk Register',
            'risk_register' => $risk_register,
        ];

        return view('risk_register/view', $data);
    }

    public function edit($id)
    {
        $risk_register = $this->riskRegisterModel->find($id);
        if (!$risk_register) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Prevent PJ Mutu from accessing edit page if already validated
        if (in_groups('pj-mutu') && $risk_register['validasi'] == 1) {
             return redirect()->to('risk-register')->with('error', 'Data yang sudah divalidasi tidak dapat diubah.');
        }

        $data = [
            'title' => 'Edit / Validasi Risk Register',
            'risk_register' => $risk_register,
        ];

        return view('risk_register/edit', $data);
    }

    public function update($id)
    {
        $risk_register = $this->riskRegisterModel->find($id);
        if (!$risk_register) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Logic for Administrator (Validation)
        if (in_groups('administrator')) {
            $validasi = $this->request->getPost('validasi'); // 1 or 0
            $komentar = $this->request->getPost('komentar_admin');
            
            if ($validasi == '0' && empty($komentar)) {
                 return redirect()->back()->withInput()->with('error', 'Komentar wajib diisi jika menolak validasi.');
            }

            $updateData = [
                'validasi' => $validasi,
                'komentar_admin' => $komentar,
                'tgl_validasi' => date('Y-m-d H:i:s'),
            ];
             $this->riskRegisterModel->update($id, $updateData);

             return redirect()->to('risk-register')->with('message', 'Validasi berhasil disimpan.');
        }

        // Logic for PJ Mutu (Update File/Desc)
        if (in_groups('pj-mutu')) {
            // Can only update if it belongs to them (optional check but good)
            if ($risk_register['user_id'] != user_id()) {
                 return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengubah data ini.');
            }

            // Prevent edit if already validated (accepted)
            if ($risk_register['validasi'] == 1) {
                return redirect()->back()->with('error', 'Data yang sudah divalidasi tidak dapat diubah.');
            }
            
            $rules = [
                'deskripsi' => 'required',
            ];
            
            $file = $this->request->getFile('file_risk_register');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                 $rules['file_risk_register'] = 'max_size[file_risk_register,10240]|mime_in[file_risk_register,application/pdf,application/force-download,application/x-download,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet]|ext_in[file_risk_register,pdf,doc,docx,xls,xlsx]';
            }

            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }

            $updateData = [
                'deskripsi' => $this->request->getPost('deskripsi'),
                'keterangan' => $this->request->getPost('keterangan'), // Save revision note
                'validasi' => 0, // Reset to pending/unvalidated on update?? Usually yes if re-uploaded.
            ];

            if ($file && $file->isValid() && !$file->hasMoved()) {
                $fileName = $file->getRandomName();
                $file->move('uploads/risk_register', $fileName);
                $updateData['file_risk_register'] = $fileName;
                // Delete old file? Maybe.
            }

            $this->riskRegisterModel->update($id, $updateData);
            return redirect()->to('risk-register')->with('message', 'Risk Register berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Akses ditolak.');
    }

    public function delete($id)
    {
        if (!in_groups('administrator')) {
             return redirect()->back()->with('error', 'Hanya Administrator yang dapat menghapus.');
        }

        $this->riskRegisterModel->delete($id);
        return redirect()->to('risk-register')->with('message', 'Risk Register berhasil dihapus.');
    }
}
