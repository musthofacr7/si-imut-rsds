<?php

namespace App\Controllers;

use App\Models\JenisIndikatorModel;

class JenisIndikator extends BaseController
{
    protected $jenisIndikatorModel;

    public function __construct()
    {
        $this->jenisIndikatorModel = new JenisIndikatorModel();
    }

    public function index()
    {
        $data['jenis_indikator'] = $this->jenisIndikatorModel->findAll();
        return view('jenis_indikator/index', $data);
    }

    public function create()
    {
        return view('jenis_indikator/create');
    }

    public function store()
    {
        if (!$this->validate($this->jenisIndikatorModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'jenis_indikator' => $this->request->getPost('jenis_indikator'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        if (!$this->jenisIndikatorModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->jenisIndikatorModel->errors());
        }

        return redirect()->to('jenis-indikator')->with('message', 'Jenis Indikator berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['jenis_indikator'] = $this->jenisIndikatorModel->find($id);
        
        if (!$data['jenis_indikator']) {
            return redirect()->to('jenis-indikator')->with('error', 'Data tidak ditemukan');
        }

        return view('jenis_indikator/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->jenisIndikatorModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'jenis_indikator' => $this->request->getPost('jenis_indikator'),
            'deskripsi' => $this->request->getPost('deskripsi'),
        ];

        if (!$this->jenisIndikatorModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->jenisIndikatorModel->errors());
        }

        return redirect()->to('jenis-indikator')->with('message', 'Jenis Indikator berhasil diperbarui');
    }

    public function delete($id)
    {
        if (!$this->jenisIndikatorModel->delete($id)) {
            return redirect()->to('jenis-indikator')->with('error', 'Gagal menghapus data');
        }

        return redirect()->to('jenis-indikator')->with('message', 'Jenis Indikator berhasil dihapus');
    }
}
