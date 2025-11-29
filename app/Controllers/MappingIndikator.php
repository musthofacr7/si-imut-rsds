<?php

namespace App\Controllers;

use App\Models\SettingIndikatorMutuModel;
use App\Models\AreaPengukuranModel;
use App\Models\IndikatorMutuModel;

class MappingIndikator extends BaseController
{
    protected $settingIndikatorMutuModel;
    protected $areaPengukuranModel;

    public function __construct()
    {
        $this->settingIndikatorMutuModel = new SettingIndikatorMutuModel();
        $this->areaPengukuranModel = new AreaPengukuranModel();
    }

    public function index()
    {
        $areaId = $this->request->getGet('area_id');

        $data['area_pengukuran'] = $this->areaPengukuranModel->getActive();
        $data['selected_area'] = $areaId;

        $builder = $this->settingIndikatorMutuModel
            ->select('setting_indikator_mutu.*, indikator_mutu.judul_indikator, area_pengukuran.area_pengukuran')
            ->join('indikator_mutu', 'indikator_mutu.id = setting_indikator_mutu.indikator_mutu_id')
            ->join('area_pengukuran', 'area_pengukuran.id = setting_indikator_mutu.area_pengukuran_id');

        if ($areaId) {
            $builder->where('setting_indikator_mutu.area_pengukuran_id', $areaId);
        }

        $data['mapping_data'] = $builder->orderBy('area_pengukuran.area_pengukuran', 'ASC')
                                        ->orderBy('indikator_mutu.judul_indikator', 'ASC')
                                        ->findAll();

        return view('mapping_indikator/index', $data);
    }

    public function getDetail($id)
    {
        $indikatorModel = new IndikatorMutuModel();
        $data = $indikatorModel->getWithJenisIndikator($id);

        if ($data) {
            return $this->response->setJSON([
                'success' => true,
                'data' => $data
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Data tidak ditemukan'
        ]);
    }
}
