<?php

namespace App\Controllers;

use App\Models\IndikatorMutuModel;
use App\Models\JenisIndikatorModel;

class IndikatorMutu extends BaseController
{
    protected $indikatorMutuModel;
    protected $jenisIndikatorModel;

    public function __construct()
    {
        $this->indikatorMutuModel = new IndikatorMutuModel();
        $this->jenisIndikatorModel = new JenisIndikatorModel();
    }

    public function index()
    {
        $data['indikator_mutu'] = $this->indikatorMutuModel->getWithJenisIndikator();
        return view('indikator_mutu/index', $data);
    }

    public function create()
    {
        $data['jenis_indikator'] = $this->jenisIndikatorModel->findAll();
        return view('indikator_mutu/create', $data);
    }

    public function store()
    {
        if (!$this->validate($this->indikatorMutuModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'judul_indikator' => $this->request->getPost('judul_indikator'),
            'dimensi_mutu' => $this->request->getPost('dimensi_mutu'),
            'tujuan' => $this->request->getPost('tujuan'),
            'jenis_indikator_id' => $this->request->getPost('jenis_indikator_id'),
            'definisi_operasional' => $this->request->getPost('definisi_operasional'),
            'numerator' => $this->request->getPost('numerator'),
            'denumerator' => $this->request->getPost('denumerator'),
            'target_pencapaian' => $this->request->getPost('target_pencapaian'),
            'satuan_target_pencapaian' => $this->request->getPost('satuan_target_pencapaian'),
            'standar_target_pencapaian' => $this->request->getPost('standar_target_pencapaian'),
            'kriteria_inklusi' => $this->request->getPost('kriteria_inklusi'),
            'kriteria_eksklusi' => $this->request->getPost('kriteria_eksklusi'),
            'sumber_data' => $this->request->getPost('sumber_data'),
            'frekuensi_pengumpulan_data' => $this->request->getPost('frekuensi_pengumpulan_data'),
            'periode_analisis_data' => $this->request->getPost('periode_analisis_data'),
            'rencana_analisis' => $this->request->getPost('rencana_analisis'),
            'instrumen_pengambilan_data' => $this->request->getPost('instrumen_pengambilan_data'),
            'area_pengukuran' => $this->request->getPost('area_pengukuran'),
            'status' => $this->request->getPost('status') ?: 'aktif',
        ];

        if (!$this->indikatorMutuModel->save($data)) {
            return redirect()->back()->withInput()->with('errors', $this->indikatorMutuModel->errors());
        }

        return redirect()->to('indikator-mutu')->with('message', 'Indikator Mutu berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['indikator_mutu'] = $this->indikatorMutuModel->find($id);
        
        if (!$data['indikator_mutu']) {
            return redirect()->to('indikator-mutu')->with('error', 'Data tidak ditemukan');
        }

        $data['jenis_indikator'] = $this->jenisIndikatorModel->findAll();
        return view('indikator_mutu/edit', $data);
    }

    public function update($id)
    {
        if (!$this->validate($this->indikatorMutuModel->getValidationRules())) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'judul_indikator' => $this->request->getPost('judul_indikator'),
            'dimensi_mutu' => $this->request->getPost('dimensi_mutu'),
            'tujuan' => $this->request->getPost('tujuan'),
            'jenis_indikator_id' => $this->request->getPost('jenis_indikator_id'),
            'definisi_operasional' => $this->request->getPost('definisi_operasional'),
            'numerator' => $this->request->getPost('numerator'),
            'denumerator' => $this->request->getPost('denumerator'),
            'target_pencapaian' => $this->request->getPost('target_pencapaian'),
            'satuan_target_pencapaian' => $this->request->getPost('satuan_target_pencapaian'),
            'standar_target_pencapaian' => $this->request->getPost('standar_target_pencapaian'),
            'kriteria_inklusi' => $this->request->getPost('kriteria_inklusi'),
            'kriteria_eksklusi' => $this->request->getPost('kriteria_eksklusi'),
            'sumber_data' => $this->request->getPost('sumber_data'),
            'frekuensi_pengumpulan_data' => $this->request->getPost('frekuensi_pengumpulan_data'),
            'periode_analisis_data' => $this->request->getPost('periode_analisis_data'),
            'rencana_analisis' => $this->request->getPost('rencana_analisis'),
            'instrumen_pengambilan_data' => $this->request->getPost('instrumen_pengambilan_data'),
            'area_pengukuran' => $this->request->getPost('area_pengukuran'),
            'status' => $this->request->getPost('status') ?: 'aktif',
        ];

        if (!$this->indikatorMutuModel->update($id, $data)) {
            return redirect()->back()->withInput()->with('errors', $this->indikatorMutuModel->errors());
        }

        return redirect()->to('indikator-mutu')->with('message', 'Indikator Mutu berhasil diperbarui');
    }

    public function show($id)
    {
        $data['indikator_mutu'] = $this->indikatorMutuModel->find($id);

        if (!$data['indikator_mutu']) {
            return redirect()->to('indikator-mutu')->with('error', 'Data tidak ditemukan');
        }

        $data['jenis_indikator'] = $this->jenisIndikatorModel->findAll();
        return view('indikator_mutu/show', $data);
    }

    public function delete($id)
    {
        // Check if indicator has input data
        if ($this->indikatorMutuModel->hasInputData($id)) {
            return redirect()->to('indikator-mutu')->with('error', 'Indikator tidak dapat dihapus karena sudah memiliki data input. Silakan non-aktifkan indikator jika tidak ingin digunakan lagi.');
        }

        if (!$this->indikatorMutuModel->delete($id)) {
            return redirect()->to('indikator-mutu')->with('error', 'Gagal menghapus data');
        }

        return redirect()->to('indikator-mutu')->with('message', 'Indikator Mutu berhasil dihapus');
    }
}
