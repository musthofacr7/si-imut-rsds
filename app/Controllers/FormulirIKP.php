<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\FormulirIKPModel;

class FormulirIKP extends BaseController
{
    protected $ikpModel;

    public function __construct()
    {
        $this->ikpModel = new FormulirIKPModel();
    }

    /**
     * Redirect ke daftar IKP
     */
    public function index()
    {
        return redirect()->to('formulir-ikp/daftar');
    }

    /**
     * Daftar semua laporan IKP
     */
    public function daftar()
    {
        $builder = $this->ikpModel
            ->select('formulir_ikp.*, investigasi_sederhana.id as inv_id, investigasi_sederhana.grading_ulang as grading_akhir, area_pengukuran.area_pengukuran')
            ->join('investigasi_sederhana', 'investigasi_sederhana.ikp_id = formulir_ikp.id AND investigasi_sederhana.deleted_at IS NULL', 'left')
            ->join('area_pengukuran', 'area_pengukuran.id = formulir_ikp.id_area_pengukuran', 'left');

        if (in_groups('pj-mutu') && !in_groups('administrator')) {
            $db = \Config\Database::connect();
            $userArea = $db->table('user_area_pengukuran')
                ->where('user_id', user_id())
                ->get()->getResultArray();
            $areaIds = array_column($userArea, 'area_pengukuran_id');
            if (!empty($areaIds)) {
                $builder->whereIn('formulir_ikp.id_area_pengukuran', $areaIds);
            } else {
                $builder->where('1=0'); // Tidak ada area, tampilkan data kosong
            }
        }

        $ikpList = $builder->orderBy('formulir_ikp.created_at', 'DESC')->findAll();

        $data = [
            'title'   => 'Daftar IKP',
            'ikpList' => $ikpList,
        ];
        return view('formulir_ikp/daftar', $data);
    }

    /**
     * Form tambah laporan IKP baru
     */
    public function create()
    {
        $db = \Config\Database::connect();
        if (in_groups('pj-mutu') && !in_groups('administrator')) {
            $areaList = $db->table('area_pengukuran')
                ->join('user_area_pengukuran', 'user_area_pengukuran.area_pengukuran_id = area_pengukuran.id')
                ->where('user_area_pengukuran.user_id', user_id())
                ->get()->getResultArray();
        } else {
            $areaList = $db->table('area_pengukuran')->get()->getResultArray();
        }

        $data = [
            'title' => 'Tambah Laporan IKP',
            'area_pengukuran' => $areaList,
        ];
        return view('formulir_ikp/create', $data);
    }

    /**
     * Simpan laporan IKP baru ke database
     */
    public function store()
    {
        $rules = [
            'nama_pasien' => 'required',
            'tgl_insiden' => 'required',
            'insiden'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tindakanOleh = $this->request->getPost('tindakan_oleh');

        $this->ikpModel->save([
            // Data Pasien
            'nama_pasien'       => $this->request->getPost('nama_pasien'),
            'no_mr'             => $this->request->getPost('no_mr'),
            'id_area_pengukuran'=> $this->request->getPost('id_area_pengukuran'),
            'ruangan'           => $this->request->getPost('ruangan'),
            'umur'              => $this->request->getPost('umur'),
            'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
            'tgl_masuk'         => $this->request->getPost('tgl_masuk'),
            'jam_masuk'         => $this->request->getPost('jam_masuk'),
            'biaya'             => $this->request->getPost('biaya'),
            // Rincian Kejadian
            'tgl_insiden'       => $this->request->getPost('tgl_insiden'),
            'jam_insiden'       => $this->request->getPost('jam_insiden'),
            'insiden'           => $this->request->getPost('insiden'),
            'kronologis'        => $this->request->getPost('kronologis'),
            'jenis_insiden'     => $this->request->getPost('jenis_insiden'),
            'pelapor'           => $this->request->getPost('pelapor'),
            'pelapor_lain'      => $this->request->getPost('pelapor_lain'),
            'insiden_pada'      => $this->request->getPost('insiden_pada'),
            'insiden_pada_lain' => $this->request->getPost('insiden_pada_lain'),
            'pasien_jenis'      => $this->request->getPost('pasien_jenis'),
            'pasien_jenis_lain' => $this->request->getPost('pasien_jenis_lain'),
            'lokasi'            => $this->request->getPost('lokasi'),
            'spesialisasi'      => $this->request->getPost('spesialisasi'),
            'spesialisasi_lain' => $this->request->getPost('spesialisasi_lain'),
            'unit_penyebab'     => $this->request->getPost('unit_penyebab'),
            'akibat'            => $this->request->getPost('akibat'),
            'tindakan_segera'   => $this->request->getPost('tindakan_segera'),
            'tindakan_oleh'     => $tindakanOleh ? json_encode($tindakanOleh) : null,
            'tim_terdiri'       => $this->request->getPost('tim_terdiri'),
            'petugas_lainnya'   => $this->request->getPost('petugas_lainnya'),
            'pernah_terjadi'    => $this->request->getPost('pernah_terjadi'),
            'langkah_pencegahan'=> $this->request->getPost('langkah_pencegahan'),
            // Tanda Tangan
            'pembuat_laporan'   => $this->request->getPost('pembuat_laporan'),
            'paraf_pembuat'     => $this->request->getPost('paraf_pembuat'),
            'tgl_lapor'         => $this->request->getPost('tgl_lapor'),
            'penerima_laporan'  => $this->request->getPost('penerima_laporan'),
            'paraf_penerima'    => $this->request->getPost('paraf_penerima'),
            'tgl_terima'        => $this->request->getPost('tgl_terima'),
            // Grading
            'grading'           => $this->request->getPost('grading'),
            // Meta
            'user_id'           => user_id(),
        ]);

        return redirect()->to('formulir-ikp/daftar')->with('message', 'Laporan IKP berhasil disimpan.');
    }

    /**
     * Tampilkan detail laporan IKP
     */
    public function view($id)
    {
        $ikp = $this->ikpModel->find($id);
        if (!$ikp) {
            return redirect()->to('formulir-ikp/daftar')->with('error', 'Data tidak ditemukan.');
        }

        // Decode JSON tindakan_oleh
        if (!empty($ikp['tindakan_oleh'])) {
            $ikp['tindakan_oleh'] = json_decode($ikp['tindakan_oleh'], true);
        } else {
            $ikp['tindakan_oleh'] = [];
        }

        $db = \Config\Database::connect();
        $area = $db->table('area_pengukuran')->where('id', $ikp['id_area_pengukuran'])->get()->getRowArray();
        $ikp['area_pengukuran'] = $area ? $area['area_pengukuran'] : '';

        $data = [
            'title' => 'Detail Laporan IKP',
            'ikp'   => $ikp,
        ];
        return view('formulir_ikp/view', $data);
    }

    /**
     * Form edit laporan IKP
     */
    public function edit($id)
    {
        $ikp = $this->ikpModel->find($id);
        if (!$ikp) {
            return redirect()->to('formulir-ikp/daftar')->with('error', 'Data tidak ditemukan.');
        }

        // Decode JSON tindakan_oleh
        if (!empty($ikp['tindakan_oleh'])) {
            $ikp['tindakan_oleh'] = json_decode($ikp['tindakan_oleh'], true);
        } else {
            $ikp['tindakan_oleh'] = [];
        }

        $db = \Config\Database::connect();
        if (in_groups('pj-mutu') && !in_groups('administrator')) {
            $areaList = $db->table('area_pengukuran')
                ->join('user_area_pengukuran', 'user_area_pengukuran.area_pengukuran_id = area_pengukuran.id')
                ->where('user_area_pengukuran.user_id', user_id())
                ->get()->getResultArray();
        } else {
            $areaList = $db->table('area_pengukuran')->get()->getResultArray();
        }

        $data = [
            'title' => 'Edit Laporan IKP',
            'ikp'   => $ikp,
            'area_pengukuran' => $areaList,
        ];
        return view('formulir_ikp/edit', $data);
    }

    /**
     * Update laporan IKP di database
     */
    public function update($id)
    {
        $ikp = $this->ikpModel->find($id);
        if (!$ikp) {
            return redirect()->to('formulir-ikp/daftar')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'nama_pasien' => 'required',
            'tgl_insiden' => 'required',
            'insiden'     => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tindakanOleh = $this->request->getPost('tindakan_oleh');

        $this->ikpModel->update($id, [
            // Data Pasien
            'nama_pasien'       => $this->request->getPost('nama_pasien'),
            'no_mr'             => $this->request->getPost('no_mr'),
            'id_area_pengukuran'=> $this->request->getPost('id_area_pengukuran'),
            'ruangan'           => $this->request->getPost('ruangan'),
            'umur'              => $this->request->getPost('umur'),
            'jenis_kelamin'     => $this->request->getPost('jenis_kelamin'),
            'tgl_masuk'         => $this->request->getPost('tgl_masuk'),
            'jam_masuk'         => $this->request->getPost('jam_masuk'),
            'biaya'             => $this->request->getPost('biaya'),
            // Rincian Kejadian
            'tgl_insiden'       => $this->request->getPost('tgl_insiden'),
            'jam_insiden'       => $this->request->getPost('jam_insiden'),
            'insiden'           => $this->request->getPost('insiden'),
            'kronologis'        => $this->request->getPost('kronologis'),
            'jenis_insiden'     => $this->request->getPost('jenis_insiden'),
            'pelapor'           => $this->request->getPost('pelapor'),
            'pelapor_lain'      => $this->request->getPost('pelapor_lain'),
            'insiden_pada'      => $this->request->getPost('insiden_pada'),
            'insiden_pada_lain' => $this->request->getPost('insiden_pada_lain'),
            'pasien_jenis'      => $this->request->getPost('pasien_jenis'),
            'pasien_jenis_lain' => $this->request->getPost('pasien_jenis_lain'),
            'lokasi'            => $this->request->getPost('lokasi'),
            'spesialisasi'      => $this->request->getPost('spesialisasi'),
            'spesialisasi_lain' => $this->request->getPost('spesialisasi_lain'),
            'unit_penyebab'     => $this->request->getPost('unit_penyebab'),
            'akibat'            => $this->request->getPost('akibat'),
            'tindakan_segera'   => $this->request->getPost('tindakan_segera'),
            'tindakan_oleh'     => $tindakanOleh ? json_encode($tindakanOleh) : null,
            'tim_terdiri'       => $this->request->getPost('tim_terdiri'),
            'petugas_lainnya'   => $this->request->getPost('petugas_lainnya'),
            'pernah_terjadi'    => $this->request->getPost('pernah_terjadi'),
            'langkah_pencegahan'=> $this->request->getPost('langkah_pencegahan'),
            // Tanda Tangan
            'pembuat_laporan'   => $this->request->getPost('pembuat_laporan'),
            'paraf_pembuat'     => $this->request->getPost('paraf_pembuat'),
            'tgl_lapor'         => $this->request->getPost('tgl_lapor'),
            'penerima_laporan'  => $this->request->getPost('penerima_laporan'),
            'paraf_penerima'    => $this->request->getPost('paraf_penerima'),
            'tgl_terima'        => $this->request->getPost('tgl_terima'),
            // Grading
            'grading'           => $this->request->getPost('grading'),
        ]);

        return redirect()->to('formulir-ikp/daftar')->with('message', 'Laporan IKP berhasil diperbarui.');
    }

    /**
     * Hapus laporan IKP (soft delete)
     */
    public function delete($id)
    {
        $ikp = $this->ikpModel->find($id);
        if (!$ikp) {
            return redirect()->to('formulir-ikp/daftar')->with('error', 'Data tidak ditemukan.');
        }

        // Hapus IKP
        $this->ikpModel->delete($id);

        // Cari dan hapus Investigasi Sederhana terkait
        $invModel = new \App\Models\InvestigasiSederhanaModel();
        $inv = $invModel->where('ikp_id', $id)->first();
        if ($inv) {
            $invModel->delete($inv['id']);
        }

        return redirect()->to('formulir-ikp/daftar')->with('message', 'Laporan IKP dan Investigasi Sederhana (jika ada) berhasil dihapus.');
    }

    /**
     * Tandai laporan IKP sudah dilaporkan (Admin only)
     */
    public function markReported($id)
    {
        if (!in_groups('administrator')) {
            return redirect()->to('formulir-ikp/daftar')->with('error', 'Hanya administrator yang dapat melakukan aksi ini.');
        }

        $ikp = $this->ikpModel->find($id);
        if (!$ikp) {
            return redirect()->to('formulir-ikp/daftar')->with('error', 'Data tidak ditemukan.');
        }

        $this->ikpModel->update($id, ['is_dilaporkan' => 1]);

        return redirect()->to('formulir-ikp/daftar')->with('message', 'Laporan IKP berhasil ditandai sebagai sudah dilaporkan.');
    }

    /**
     * Batal tandai laporan IKP sudah dilaporkan (Admin only)
     */
    public function unmarkReported($id)
    {
        if (!in_groups('administrator')) {
            return redirect()->to('formulir-ikp/daftar')->with('error', 'Hanya administrator yang dapat melakukan aksi ini.');
        }

        $ikp = $this->ikpModel->find($id);
        if (!$ikp) {
            return redirect()->to('formulir-ikp/daftar')->with('error', 'Data tidak ditemukan.');
        }

        $this->ikpModel->update($id, ['is_dilaporkan' => 0]);

        return redirect()->to('formulir-ikp/daftar')->with('message', 'Status laporan IKP berhasil dibatalkan menjadi belum dilaporkan.');
    }
}
