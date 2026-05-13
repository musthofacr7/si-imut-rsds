<?php

namespace App\Controllers;

use App\Models\InvestigasiSederhanaModel;

class InvestigasiSederhana extends BaseController
{
    protected $investigasiModel;

    public function __construct()
    {
        $this->investigasiModel = new InvestigasiSederhanaModel();
    }

    public function index()
    {
        return redirect()->to('/formulir-ikp/daftar');
    }

    public function create($ikp_id = null)
    {
        // Jika ikp_id ada, cek apakah investigasi sudah ada
        if ($ikp_id) {
            $existing = $this->investigasiModel->where('ikp_id', $ikp_id)->first();
            if ($existing) {
                return redirect()->to('/investigasi-sederhana/edit/' . $existing['id']);
            }
        }

        $data = [
            'title' => 'Buat Investigasi Sederhana Baru',
            'ikp_id' => $ikp_id,
        ];
        return view('investigasi_sederhana/create', $data);
    }

    public function store()
    {
        // Proses Rekomendasi array menjadi JSON
        $rekomendasiData = [];
        $reqRekomendasi = $this->request->getPost('rekomendasi') ?? [];
        $reqPjRekomendasi = $this->request->getPost('pj_rekomendasi') ?? [];
        $reqTglRekomendasi = $this->request->getPost('tgl_rekomendasi') ?? [];
        foreach ($reqRekomendasi as $index => $rek) {
            if (!empty($rek)) {
                $rekomendasiData[] = [
                    'rekomendasi' => $rek,
                    'pj_rekomendasi' => $reqPjRekomendasi[$index] ?? '',
                    'tgl_rekomendasi' => $reqTglRekomendasi[$index] ?? '',
                ];
            }
        }

        // Proses Tindakan array menjadi JSON
        $tindakanData = [];
        $reqTindakan = $this->request->getPost('tindakan') ?? [];
        $reqPjTindakan = $this->request->getPost('pj_tindakan') ?? [];
        $reqTglTindakan = $this->request->getPost('tgl_tindakan') ?? [];
        foreach ($reqTindakan as $index => $tind) {
            if (!empty($tind)) {
                $tindakanData[] = [
                    'tindakan' => $tind,
                    'pj_tindakan' => $reqPjTindakan[$index] ?? '',
                    'tgl_tindakan' => $reqTglTindakan[$index] ?? '',
                ];
            }
        }

        $data = [
            'ikp_id' => $this->request->getPost('ikp_id') ?: null,
            'alat' => $this->request->getPost('alat'),
            'tempat_kerja' => $this->request->getPost('tempat_kerja'),
            'prosedur' => $this->request->getPost('prosedur'),
            'individu' => $this->request->getPost('individu'),
            'tempat_kerja_akar' => $this->request->getPost('tempat_kerja_akar'),
            'organisasi' => $this->request->getPost('organisasi'),
            'rekomendasi' => json_encode($rekomendasiData),
            'tindakan' => json_encode($tindakanData),
            'manajer_nama' => $this->request->getPost('manajer_nama'),
            'ttd_manajer' => $this->request->getPost('ttd_manajer'),
            'tgl_mulai_investigasi' => $this->request->getPost('tgl_mulai_investigasi'),
            'tgl_selesai_investigasi' => $this->request->getPost('tgl_selesai_investigasi'),
            'investigasi_lengkap' => $this->request->getPost('investigasi_lengkap'),
            'tgl_investigasi_lengkap' => $this->request->getPost('tgl_investigasi_lengkap'),
            'investigasi_lanjut' => $this->request->getPost('investigasi_lanjut'),
            'grading_ulang' => $this->request->getPost('grading_ulang'),
        ];

        $this->investigasiModel->save($data);

        session()->setFlashdata('message', 'Data Investigasi Sederhana berhasil disimpan.');
        return redirect()->to('/formulir-ikp/daftar');
    }

    public function view($id)
    {
        $investigasi = $this->investigasiModel->find($id);
        if (!$investigasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data Investigasi tidak ditemukan');
        }

        // Decode JSON
        $investigasi['rekomendasi'] = json_decode($investigasi['rekomendasi'] ?? '[]', true);
        $investigasi['tindakan'] = json_decode($investigasi['tindakan'] ?? '[]', true);

        $data = [
            'title' => 'Detail Investigasi Sederhana',
            'inv' => $investigasi,
        ];
        return view('investigasi_sederhana/view', $data);
    }

    public function edit($id)
    {
        $investigasi = $this->investigasiModel->find($id);
        if (!$investigasi) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Data Investigasi tidak ditemukan');
        }

        // Decode JSON
        $investigasi['rekomendasi'] = json_decode($investigasi['rekomendasi'] ?? '[]', true);
        $investigasi['tindakan'] = json_decode($investigasi['tindakan'] ?? '[]', true);

        $data = [
            'title' => 'Edit Investigasi Sederhana',
            'inv' => $investigasi,
        ];
        return view('investigasi_sederhana/edit', $data);
    }

    public function update($id)
    {
        // Proses Rekomendasi array menjadi JSON
        $rekomendasiData = [];
        $reqRekomendasi = $this->request->getPost('rekomendasi') ?? [];
        $reqPjRekomendasi = $this->request->getPost('pj_rekomendasi') ?? [];
        $reqTglRekomendasi = $this->request->getPost('tgl_rekomendasi') ?? [];
        foreach ($reqRekomendasi as $index => $rek) {
            if (!empty($rek)) {
                $rekomendasiData[] = [
                    'rekomendasi' => $rek,
                    'pj_rekomendasi' => $reqPjRekomendasi[$index] ?? '',
                    'tgl_rekomendasi' => $reqTglRekomendasi[$index] ?? '',
                ];
            }
        }

        // Proses Tindakan array menjadi JSON
        $tindakanData = [];
        $reqTindakan = $this->request->getPost('tindakan') ?? [];
        $reqPjTindakan = $this->request->getPost('pj_tindakan') ?? [];
        $reqTglTindakan = $this->request->getPost('tgl_tindakan') ?? [];
        foreach ($reqTindakan as $index => $tind) {
            if (!empty($tind)) {
                $tindakanData[] = [
                    'tindakan' => $tind,
                    'pj_tindakan' => $reqPjTindakan[$index] ?? '',
                    'tgl_tindakan' => $reqTglTindakan[$index] ?? '',
                ];
            }
        }

        $data = [
            'id' => $id,
            'alat' => $this->request->getPost('alat'),
            'tempat_kerja' => $this->request->getPost('tempat_kerja'),
            'prosedur' => $this->request->getPost('prosedur'),
            'individu' => $this->request->getPost('individu'),
            'tempat_kerja_akar' => $this->request->getPost('tempat_kerja_akar'),
            'organisasi' => $this->request->getPost('organisasi'),
            'rekomendasi' => json_encode($rekomendasiData),
            'tindakan' => json_encode($tindakanData),
            'manajer_nama' => $this->request->getPost('manajer_nama'),
            'ttd_manajer' => $this->request->getPost('ttd_manajer'),
            'tgl_mulai_investigasi' => $this->request->getPost('tgl_mulai_investigasi'),
            'tgl_selesai_investigasi' => $this->request->getPost('tgl_selesai_investigasi'),
            'investigasi_lengkap' => $this->request->getPost('investigasi_lengkap'),
            'tgl_investigasi_lengkap' => $this->request->getPost('tgl_investigasi_lengkap'),
            'investigasi_lanjut' => $this->request->getPost('investigasi_lanjut'),
            'grading_ulang' => $this->request->getPost('grading_ulang'),
        ];

        $this->investigasiModel->save($data);

        session()->setFlashdata('message', 'Data Investigasi Sederhana berhasil diupdate.');
        return redirect()->to('/formulir-ikp/daftar');
    }

    public function delete($id)
    {
        $this->investigasiModel->delete($id);
        session()->setFlashdata('message', 'Data Investigasi Sederhana berhasil dihapus.');
        return redirect()->to('/formulir-ikp/daftar');
    }
}
