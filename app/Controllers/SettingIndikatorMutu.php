<?php

namespace App\Controllers;

use App\Models\SettingIndikatorMutuModel;
use App\Models\IndikatorMutuModel;
use App\Models\AreaPengukuranModel;

class SettingIndikatorMutu extends BaseController
{
    protected $settingIndikatorMutuModel;
    protected $indikatorMutuModel;
    protected $areaPengukuranModel;

    public function __construct()
    {
        $this->settingIndikatorMutuModel = new SettingIndikatorMutuModel();
        $this->indikatorMutuModel = new IndikatorMutuModel();
        $this->areaPengukuranModel = new AreaPengukuranModel();
    }

    public function index()
    {
        $data['setting_indikator'] = $this->settingIndikatorMutuModel->getWithRelations();
        return view('setting_indikator_mutu/index', $data);
    }

    public function create()
    {
        $data['indikator_mutu'] = $this->indikatorMutuModel->findAll();
        $data['area_pengukuran'] = $this->areaPengukuranModel->getActive();
        return view('setting_indikator_mutu/create', $data);
    }

    public function store()
    {
        $indikator_mutu_id = $this->request->getPost('indikator_mutu_id');
        $area_pengukuran_ids = $this->request->getPost('area_pengukuran_id');

        if (empty($indikator_mutu_id)) {
            return redirect()->back()->withInput()->with('errors', ['Indikator Mutu harus dipilih']);
        }

        if (empty($area_pengukuran_ids) || !is_array($area_pengukuran_ids)) {
            return redirect()->back()->withInput()->with('errors', ['Area Pengukuran harus dipilih minimal satu']);
        }

        // Check for existing combinations to prevent duplicates
        $existingAreas = $this->settingIndikatorMutuModel
            ->where('indikator_mutu_id', $indikator_mutu_id)
            ->findAll();
        
        $existingAreaIds = array_column($existingAreas, 'area_pengukuran_id');
        $duplicates = array_intersect($area_pengukuran_ids, $existingAreaIds);
        
        if (!empty($duplicates)) {
            // Get area names for error message
            $duplicateNames = [];
            foreach ($duplicates as $dupId) {
                $area = $this->areaPengukuranModel->find($dupId);
                if ($area) {
                    $duplicateNames[] = $area['area_pengukuran'];
                }
            }
            return redirect()->back()->withInput()->with('errors', [
                'Area Pengukuran berikut sudah terdaftar untuk indikator ini: ' . implode(', ', $duplicateNames)
            ]);
        }

        // Insert multiple records
        foreach ($area_pengukuran_ids as $area_id) {
            $data = [
                'indikator_mutu_id' => $indikator_mutu_id,
                'area_pengukuran_id' => $area_id,
            ];

            if (!$this->settingIndikatorMutuModel->save($data)) {
                return redirect()->back()->withInput()->with('errors', $this->settingIndikatorMutuModel->errors());
            }
        }

        return redirect()->to('setting-indikator-mutu')->with('message', 'Setting berhasil ditambahkan');
    }

    public function edit($id)
    {
        $data['setting_indikator'] = $this->settingIndikatorMutuModel->find($id);
        
        if (!$data['setting_indikator']) {
            return redirect()->to('setting-indikator-mutu')->with('error', 'Data tidak ditemukan');
        }

        $data['indikator_mutu'] = $this->indikatorMutuModel->findAll();
        $data['area_pengukuran'] = $this->areaPengukuranModel->getActive();
        
        // Get all areas for this indikator
        $data['selected_areas'] = $this->settingIndikatorMutuModel
            ->where('indikator_mutu_id', $data['setting_indikator']['indikator_mutu_id'])
            ->findAll();
        
        return view('setting_indikator_mutu/edit', $data);
    }

    public function update($id)
    {
        $setting = $this->settingIndikatorMutuModel->find($id);
        
        if (!$setting) {
            return redirect()->to('setting-indikator-mutu')->with('error', 'Data tidak ditemukan');
        }

        $indikator_mutu_id = $this->request->getPost('indikator_mutu_id');
        $area_pengukuran_ids = $this->request->getPost('area_pengukuran_id');

        if (empty($indikator_mutu_id)) {
            return redirect()->back()->withInput()->with('errors', ['Indikator Mutu harus dipilih']);
        }

        if (empty($area_pengukuran_ids) || !is_array($area_pengukuran_ids)) {
            return redirect()->back()->withInput()->with('errors', ['Area Pengukuran harus dipilih minimal satu']);
        }

        // Remove duplicates from input array itself
        $area_pengukuran_ids = array_unique($area_pengukuran_ids);

        // Delete all existing settings for this indikator
        $this->settingIndikatorMutuModel->where('indikator_mutu_id', $indikator_mutu_id)->delete();

        // Insert new settings
        foreach ($area_pengukuran_ids as $area_id) {
            $data = [
                'indikator_mutu_id' => $indikator_mutu_id,
                'area_pengukuran_id' => $area_id,
            ];

            if (!$this->settingIndikatorMutuModel->save($data)) {
                return redirect()->back()->withInput()->with('errors', $this->settingIndikatorMutuModel->errors());
            }
        }

        return redirect()->to('setting-indikator-mutu')->with('message', 'Setting berhasil diperbarui');
    }

    public function delete($id)
    {
        $setting = $this->settingIndikatorMutuModel->find($id);
        
        if (!$setting) {
            return redirect()->to('setting-indikator-mutu')->with('error', 'Data tidak ditemukan');
        }

        // Delete all settings for this indikator
        if (!$this->settingIndikatorMutuModel->where('indikator_mutu_id', $setting['indikator_mutu_id'])->delete()) {
            return redirect()->to('setting-indikator-mutu')->with('error', 'Gagal menghapus data');
        }

        return redirect()->to('setting-indikator-mutu')->with('message', 'Setting berhasil dihapus');
    }
}
