<?php

namespace App\Controllers;

use App\Models\SettingIndikatorMutuModel;
use App\Models\InputIndikatorRsModel;
use App\Models\UserAreaPengukuranModel;
use App\Models\AreaPengukuranModel;

class MonitoringInput extends BaseController
{
    protected $settingIndikatorMutuModel;
    protected $inputIndikatorRsModel;
    protected $userAreaPengukuranModel;
    protected $areaPengukuranModel;

    public function __construct()
    {
        $this->settingIndikatorMutuModel = new SettingIndikatorMutuModel();
        $this->inputIndikatorRsModel = new InputIndikatorRsModel();
        $this->userAreaPengukuranModel = new UserAreaPengukuranModel();
        $this->areaPengukuranModel = new AreaPengukuranModel();
    }

    public function index()
    {
        $selectedMonth = $this->request->getGet('month') ?? date('m');
        $selectedYear = $this->request->getGet('year') ?? date('Y');

        // 1. Get all settings (What SHOULD be input)
        // Groups: Area -> Indikator
        $settings = $this->settingIndikatorMutuModel
            ->select('setting_indikator_mutu.*, 
                      indikator_mutu.judul_indikator, 
                      area_pengukuran.area_pengukuran')
            ->join('indikator_mutu', 'indikator_mutu.id = setting_indikator_mutu.indikator_mutu_id')
            ->join('area_pengukuran', 'area_pengukuran.id = setting_indikator_mutu.area_pengukuran_id')
            ->orderBy('area_pengukuran.area_pengukuran', 'ASC')
            ->orderBy('indikator_mutu.judul_indikator', 'ASC')
            ->findAll();

        // 2. Prepare data structure
        $monitoringData = [];

        foreach ($settings as $setting) {
            $areaId = $setting['area_pengukuran_id'];
            $indikatorId = $setting['indikator_mutu_id'];
            $areaName = $setting['area_pengukuran'];
            $indikatorName = $setting['judul_indikator'];

            // 3. Check for existing inputs in the selected month
            $inputCount = $this->inputIndikatorRsModel
                ->where('indikator_mutu_id', $indikatorId)
                ->where('area_pengukuran_id', $areaId)
                ->where('MONTH(tanggal)', $selectedMonth)
                ->where('YEAR(tanggal)', $selectedYear)
                ->countAllResults();

            // 4. Get PJ Mutu for this area
            $pjMutuUsers = $this->userAreaPengukuranModel
                ->select('users.username, users.email')
                ->join('users', 'users.id = user_area_pengukuran.user_id')
                ->where('user_area_pengukuran.area_pengukuran_id', $areaId)
                ->findAll();
            
            $pjMutuNames = array_map(function($user) {
                return $user['username']; // Or full name if available
            }, $pjMutuUsers);
            
            $pjMutuString = empty($pjMutuNames) ? '-' : implode(', ', $pjMutuNames);

            $monitoringData[] = [
                'area' => $areaName,
                'indikator' => $indikatorName,
                'pj_mutu' => $pjMutuString,
                'input_count' => $inputCount,
                'status' => $inputCount > 0 ? 'Sudah Input' : 'Belum Input'
            ];
        }

        $data = [
            'title' => 'Monitoring Input Indikator Mutu',
            'monitoringData' => $monitoringData,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
        ];

        return view('monitoring_input/index', $data);
    }
}
