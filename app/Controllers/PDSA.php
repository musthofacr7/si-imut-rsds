<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PDSAModel;
use Myth\Auth\Models\UserModel;

class PDSA extends BaseController
{
    protected $pdsaModel;
    protected $userModel;

    public function __construct()
    {
        $this->pdsaModel = new PDSAModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        // Init query
        $builder = $this->pdsaModel->select('pdsa.*, users.username, users.email')
            ->join('users', 'users.id = pdsa.user_id', 'left');

        // Filter for PJ Mutu: Only show PDSA from users in the same Area Pengukuran
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
                    $builder->whereIn('pdsa.user_id', $userIds);
                } else {
                    // Start of corner case: I have an area, but no users found? (Impossible since I am one)
                    $builder->where('pdsa.user_id', $myId);
                }
            } else {
                 // Corner case: User has no area assigned? Show only their own uploads.
                 $builder->where('pdsa.user_id', $myId);
            }
        }

        $pdsa = $builder->orderBy('created_at', 'DESC')->findAll();

        $data = [
            'title' => 'Daftar PDSA',
            'pdsa' => $pdsa,
        ];

        return view('pdsa/index', $data);
    }

    public function create()
    {
        if (!in_groups('pj-mutu')) {
           return redirect()->back()->with('error', 'Hanya PJ Mutu yang dapat membuat PDSA.');
        }

        $data = [
            'title' => 'Input PDSA',
        ];

        return view('pdsa/create', $data);
    }

    public function store()
    {
        if (!in_groups('pj-mutu')) {
           return redirect()->back()->with('error', 'Unauthorized.');
        }

        $rules = [
            'file_pdsa' => [
                'rules' => 'uploaded[file_pdsa]|max_size[file_pdsa,10240]|mime_in[file_pdsa,application/pdf,application/force-download,application/x-download,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/jpeg,image/png,image/jpg]|ext_in[file_pdsa,pdf,doc,docx,xls,xlsx,jpg,jpeg,png]',
                'label' => 'File PDSA'
            ],
            'deskripsi' => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('file_pdsa');
        $fileName = $file->getRandomName();
        $file->move('uploads/pdsa', $fileName);

        $this->pdsaModel->save([
            'user_id' => user_id(),
            'file_pdsa' => $fileName,
            'deskripsi' => $this->request->getPost('deskripsi'),
            'validasi' => 0, // Default pending
        ]);

        return redirect()->to('pdsa')->with('message', 'PDSA berhasil ditambahkan.');
    }

    public function view($id)
    {
        $pdsa = $this->pdsaModel->select('pdsa.*, users.username')
            ->join('users', 'users.id = pdsa.user_id', 'left')
            ->find($id);

        if (!$pdsa) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $data = [
            'title' => 'Detail PDSA',
            'pdsa' => $pdsa,
        ];

        return view('pdsa/view', $data);
    }

    public function edit($id)
    {
        $pdsa = $this->pdsaModel->find($id);
        if (!$pdsa) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Prevent PJ Mutu from accessing edit page if already validated
        if (in_groups('pj-mutu') && $pdsa['validasi'] == 1) {
             return redirect()->to('pdsa')->with('error', 'Data yang sudah divalidasi tidak dapat diubah.');
        }

        $data = [
            'title' => 'Edit / Validasi PDSA',
            'pdsa' => $pdsa,
        ];

        return view('pdsa/edit', $data);
    }

    public function update($id)
    {
        $pdsa = $this->pdsaModel->find($id);
        if (!$pdsa) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        // Logic for Administrator (Validation)
        if (in_groups('administrator')) {
            $validasi = $this->request->getPost('validasi'); // 1 or 0
            $komentar = $this->request->getPost('komentar_admin');

            // Logic: If Rejected (0) or Pending, checks if strictly rejected?
            // User request: "administrator menolak di wajibkan isi komentar"
            // Let's assume Validasi 1=Accept, 0=Reject.
            // If admin sets to 0 (Reject), comment is mandatory.

            // Wait, previous request said "validasi berisi true dan false atau 1 dan 0".
            // So 1 = Accepted, 0 = Rejected/Pending.
            // If changing to 0 (Reject), require comment.
            
            if ($validasi == '0' && empty($komentar)) {
                 return redirect()->back()->withInput()->with('error', 'Komentar wajib diisi jika menolak validasi.');
            }

            $updateData = [
                'validasi' => $validasi,
                'komentar_admin' => $komentar,
                'tgl_validasi' => date('Y-m-d H:i:s'),
            ];
             $this->pdsaModel->update($id, $updateData);

             return redirect()->to('pdsa')->with('message', 'Validasi berhasil disimpan.');
        }

        // Logic for PJ Mutu (Update File/Desc)
        if (in_groups('pj-mutu')) {
            // Can only update if it belongs to them (optional check but good)
            if ($pdsa['user_id'] != user_id()) {
                 return redirect()->back()->with('error', 'Anda tidak memiliki hak akses untuk mengubah data ini.');
            }

            // Prevent edit if already validated (accepted)
            if ($pdsa['validasi'] == 1) {
                return redirect()->back()->with('error', 'Data yang sudah divalidasi tidak dapat diubah.');
            }
            
            $rules = [
                'deskripsi' => 'required',
            ];
            
            $file = $this->request->getFile('file_pdsa');
            if ($file && $file->isValid() && !$file->hasMoved()) {
                 $rules['file_pdsa'] = 'max_size[file_pdsa,10240]|mime_in[file_pdsa,application/pdf,application/force-download,application/x-download,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,image/jpeg,image/png,image/jpg]|ext_in[file_pdsa,pdf,doc,docx,xls,xlsx,jpg,jpeg,png]';
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
                $file->move('uploads/pdsa', $fileName);
                $updateData['file_pdsa'] = $fileName;
                // Delete old file? Maybe.
            }

            $this->pdsaModel->update($id, $updateData);
            return redirect()->to('pdsa')->with('message', 'PDSA berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Akses ditolak.');
    }

    public function delete($id)
    {
        if (!in_groups('administrator')) {
             return redirect()->back()->with('error', 'Hanya Administrator yang dapat menghapus.');
        }

        $this->pdsaModel->delete($id);
        return redirect()->to('pdsa')->with('message', 'PDSA berhasil dihapus.');
    }
}
