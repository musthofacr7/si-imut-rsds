<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\MasterSatuanIndikatorModel;

class MasterSatuanIndikator extends BaseController
{
    protected $satuanModel;

    public function __construct()
    {
        $this->satuanModel = new MasterSatuanIndikatorModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Master Satuan Indikator',
            'satuan' => $this->satuanModel->findAll(),
        ];

        return view('master_satuan_indikator/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Satuan Indikator',
            'validation' => \Config\Services::validation(),
        ];

        return view('master_satuan_indikator/create', $data);
    }

    public function store()
    {
        if (!$this->validate($this->satuanModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->satuanModel->save([
            'nama_satuan' => $this->request->getPost('nama_satuan'),
        ]);

        return redirect()->to('/master-satuan-indikator')->with('success', 'Data berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit Satuan Indikator',
            'satuan' => $this->satuanModel->find($id),
            'validation' => \Config\Services::validation(),
        ];

        if (empty($data['satuan'])) {
            return redirect()->to('/master-satuan-indikator')->with('error', 'Data tidak ditemukan.');
        }

        return view('master_satuan_indikator/edit', $data);
    }

    public function update($id)
    {
        $rules = $this->satuanModel->getValidationRules();
        
        // Adjust rule for unique check to ignore current ID
        $rules['nama_satuan'] = "required|max_length[50]|is_unique[master_satuan_indikator.nama_satuan,id,{$id}]";

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $this->satuanModel->update($id, [
            'nama_satuan' => $this->request->getPost('nama_satuan'),
        ]);

        return redirect()->to('/master-satuan-indikator')->with('success', 'Data berhasil diperbarui.');
    }

    public function delete($id)
    {
        $this->satuanModel->delete($id);
        return redirect()->to('/master-satuan-indikator')->with('success', 'Data berhasil dihapus.');
    }
}
