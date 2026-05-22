<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\LaporanKPCModel;

class LaporanKPC extends BaseController
{
    protected $kpcModel;

    public function __construct()
    {
        $this->kpcModel = new LaporanKPCModel();
    }

    public function index()
    {
        return redirect()->to('laporan-kpc/daftar');
    }

    public function daftar()
    {
        $builder = $this->kpcModel
            ->select('laporan_kpc.*, area_pengukuran.area_pengukuran')
            ->join('area_pengukuran', 'area_pengukuran.id = laporan_kpc.id_area_pengukuran', 'left');

        if (in_groups('pj-mutu') && !in_groups('administrator')) {
            $db = \Config\Database::connect();
            $userArea = $db->table('user_area_pengukuran')
                ->where('user_id', user_id())
                ->get()->getResultArray();
            $areaIds = array_column($userArea, 'area_pengukuran_id');
            if (!empty($areaIds)) {
                $builder->whereIn('laporan_kpc.id_area_pengukuran', $areaIds);
            } else {
                $builder->where('1=0'); // Tidak ada area, tampilkan data kosong
            }
        }

        $kpcList = $builder->orderBy('laporan_kpc.created_at', 'DESC')->findAll();

        $data = [
            'title'   => 'Daftar KPC',
            'kpcList' => $kpcList,
        ];
        return view('laporan_kpc/daftar', $data);
    }

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
            'title' => 'Tambah Laporan KPC',
            'area_pengukuran' => $areaList,
        ];
        return view('laporan_kpc/create', $data);
    }

    public function store()
    {
        $rules = [
            'id_area_pengukuran' => 'required',
            'tgl_kpc'            => 'required',
            'insiden'            => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tindakanOleh = $this->request->getPost('tindakan_oleh');

        $this->kpcModel->save([
            'id_area_pengukuran' => $this->request->getPost('id_area_pengukuran'),
            'tgl_kpc'            => $this->request->getPost('tgl_kpc'),
            'jam_kpc'            => $this->request->getPost('jam_kpc'),
            'insiden'            => $this->request->getPost('insiden'),
            'pelapor'            => $this->request->getPost('pelapor'),
            'pelapor_lain'       => $this->request->getPost('pelapor_lain'),
            'lokasi'             => $this->request->getPost('lokasi'),
            'unit_terkait'       => $this->request->getPost('unit_terkait'),
            'tindakan'           => $this->request->getPost('tindakan'),
            'tindakan_oleh'      => $tindakanOleh ? json_encode($tindakanOleh) : null,
            'tim_terdiri'        => $this->request->getPost('tim_terdiri'),
            'petugas_lainnya'    => $this->request->getPost('petugas_lainnya'),
            'pernah_terjadi'     => $this->request->getPost('pernah_terjadi'),
            'langkah_pencegahan' => $this->request->getPost('langkah_pencegahan'),
            'pembuat_laporan'    => $this->request->getPost('pembuat_laporan'),
            'paraf_pembuat'      => $this->request->getPost('paraf_pembuat'),
            'penerima_laporan'   => $this->request->getPost('penerima_laporan'),
            'paraf_penerima'     => $this->request->getPost('paraf_penerima'),
            'user_id'            => user_id(),
        ]);

        return redirect()->to('laporan-kpc/daftar')->with('message', 'Laporan KPC berhasil disimpan.');
    }

    public function view($id)
    {
        $kpc = $this->kpcModel->find($id);
        if (!$kpc) {
            return redirect()->to('laporan-kpc/daftar')->with('error', 'Data tidak ditemukan.');
        }

        // Decode JSON tindakan_oleh
        if (!empty($kpc['tindakan_oleh'])) {
            $kpc['tindakan_oleh'] = json_decode($kpc['tindakan_oleh'], true);
        } else {
            $kpc['tindakan_oleh'] = [];
        }

        $db = \Config\Database::connect();
        $area = $db->table('area_pengukuran')->where('id', $kpc['id_area_pengukuran'])->get()->getRowArray();
        $kpc['area_pengukuran'] = $area ? $area['area_pengukuran'] : '';

        $data = [
            'title' => 'Detail Laporan KPC',
            'kpc'   => $kpc,
        ];
        return view('laporan_kpc/view', $data);
    }

    public function edit($id)
    {
        $kpc = $this->kpcModel->find($id);
        if (!$kpc) {
            return redirect()->to('laporan-kpc/daftar')->with('error', 'Data tidak ditemukan.');
        }

        // Decode JSON tindakan_oleh
        if (!empty($kpc['tindakan_oleh'])) {
            $kpc['tindakan_oleh'] = json_decode($kpc['tindakan_oleh'], true);
        } else {
            $kpc['tindakan_oleh'] = [];
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
            'title' => 'Edit Laporan KPC',
            'kpc'   => $kpc,
            'area_pengukuran' => $areaList,
        ];
        return view('laporan_kpc/edit', $data);
    }

    public function update($id)
    {
        $kpc = $this->kpcModel->find($id);
        if (!$kpc) {
            return redirect()->to('laporan-kpc/daftar')->with('error', 'Data tidak ditemukan.');
        }

        $rules = [
            'id_area_pengukuran' => 'required',
            'tgl_kpc'            => 'required',
            'insiden'            => 'required',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $tindakanOleh = $this->request->getPost('tindakan_oleh');

        $this->kpcModel->update($id, [
            'id_area_pengukuran' => $this->request->getPost('id_area_pengukuran'),
            'tgl_kpc'            => $this->request->getPost('tgl_kpc'),
            'jam_kpc'            => $this->request->getPost('jam_kpc'),
            'insiden'            => $this->request->getPost('insiden'),
            'pelapor'            => $this->request->getPost('pelapor'),
            'pelapor_lain'       => $this->request->getPost('pelapor_lain'),
            'lokasi'             => $this->request->getPost('lokasi'),
            'unit_terkait'       => $this->request->getPost('unit_terkait'),
            'tindakan'           => $this->request->getPost('tindakan'),
            'tindakan_oleh'      => $tindakanOleh ? json_encode($tindakanOleh) : null,
            'tim_terdiri'        => $this->request->getPost('tim_terdiri'),
            'petugas_lainnya'    => $this->request->getPost('petugas_lainnya'),
            'pernah_terjadi'     => $this->request->getPost('pernah_terjadi'),
            'langkah_pencegahan' => $this->request->getPost('langkah_pencegahan'),
            'pembuat_laporan'    => $this->request->getPost('pembuat_laporan'),
            'paraf_pembuat'      => $this->request->getPost('paraf_pembuat'),
            'penerima_laporan'   => $this->request->getPost('penerima_laporan'),
            'paraf_penerima'     => $this->request->getPost('paraf_penerima'),
        ]);

        return redirect()->to('laporan-kpc/daftar')->with('message', 'Laporan KPC berhasil diperbarui.');
    }

    public function delete($id)
    {
        $kpc = $this->kpcModel->find($id);
        if (!$kpc) {
            return redirect()->to('laporan-kpc/daftar')->with('error', 'Data tidak ditemukan.');
        }

        $this->kpcModel->delete($id);

        return redirect()->to('laporan-kpc/daftar')->with('message', 'Laporan KPC berhasil dihapus.');
    }
}
