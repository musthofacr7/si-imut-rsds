<?php

namespace App\Controllers;

use App\Models\AreaPengukuranModel;

class AreaPengukuran extends BaseController
{
    protected $areaPengukuranModel;

    public function __construct()
    {
        $this->areaPengukuranModel = new AreaPengukuranModel();
    }

    public function index()
    {
        $data['area_pengukuran'] = $this->areaPengukuranModel->findAll();
        return view('area_pengukuran/index', $data);
    }

    public function create()
    {
        return view('area_pengukuran/create');
    }

    public function store()
    {
        if (!$this->validate($this->areaPengukuranModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'area_pengukuran' => $this->request->getPost('area_pengukuran'),
            'status' => $this->request->getPost('status'),
        ];

        if (!$this->areaPengukuranModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->areaPengukuranModel->errors());
        }

        return redirect()->to('area-pengukuran')->with('message', 'Area Pengukuran berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['area_pengukuran'] = $this->areaPengukuranModel->find($id);
        
        if (!$data['area_pengukuran']) {
            return redirect()->to('area-pengukuran')->with('error', 'Data tidak ditemukan');
        }

        return view('area_pengukuran/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->areaPengukuranModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'area_pengukuran' => $this->request->getPost('area_pengukuran'),
            'status' => $this->request->getPost('status'),
        ];

        if (!$this->areaPengukuranModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->areaPengukuranModel->errors());
        }

        return redirect()->to('area-pengukuran')->with('message', 'Area Pengukuran berhasil diperbarui');
    }

    public function delete($id)
    {
        if (!$this->areaPengukuranModel->delete($id)) {
            return redirect()->to('area-pengukuran')->with('error', 'Gagal menghapus data');
        }

        return redirect()->to('area-pengukuran')->with('message', 'Area Pengukuran berhasil dihapus');
    }
}
