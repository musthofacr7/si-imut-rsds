<?php

namespace App\Controllers;

use App\Models\IndikatorMutuModel;
use App\Models\InputIndikatorRsModel;
use App\Models\AreaPengukuranModel;

class RekapAreaPengukuran extends BaseController
{
    protected $indikatorMutuModel;
    protected $inputIndikatorRsModel;
    protected $areaPengukuranModel;

    public function __construct()
    {
        $this->indikatorMutuModel = new IndikatorMutuModel();
        $this->inputIndikatorRsModel = new InputIndikatorRsModel();
        $this->areaPengukuranModel = new AreaPengukuranModel();
    }

    public function index()
    {
        $data['area_pengukuran'] = $this->areaPengukuranModel->getActive();
        $data['available_years'] = $this->inputIndikatorRsModel->getAvailableYears();
        
        // Add current year as default if no data exists
        if (empty($data['available_years'])) {
            $data['available_years'] = [date('Y')];
        }
        
        return view('rekap_area_pengukuran/index', $data);
    }

    public function getData()
    {
        // Get POST parameters
        $areaId = $this->request->getPost('area_id');
        $year = $this->request->getPost('year');

        // Validate required parameters
        if (!$areaId || !$year) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Get Area details
        $area = $this->areaPengukuranModel->find($areaId);
        if (!$area) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Area Pengukuran tidak ditemukan'
            ]);
        }

        // Get indicators mapped to this area
        $settingsModel = new \App\Models\SettingIndikatorMutuModel();
        
        $indicators = $this->indikatorMutuModel
            ->select('indikator_mutu.*, jenis_indikator.jenis_indikator')
            ->join('jenis_indikator', 'jenis_indikator.id = indikator_mutu.jenis_indikator_id')
            ->join('setting_indikator_mutu', 'setting_indikator_mutu.indikator_mutu_id = indikator_mutu.id')
            ->where('setting_indikator_mutu.area_pengukuran_id', $areaId)
            ->findAll();
        
        $resultData = [];
        
        foreach ($indicators as $indikator) {
            // Get stats for this indicator SPECIFIC to this AREA
            $stats = $this->inputIndikatorRsModel->getMonthlyStats($indikator['id'], $areaId, $year, $indikator['satuan_target_pencapaian']);
            
            $row = [
                'id' => $indikator['id'],
                'judul' => $indikator['judul_indikator'],
                'jenis' => $indikator['jenis_indikator'] ?? '-',
                'target' => $indikator['target_pencapaian'],
                'satuan' => $indikator['satuan_target_pencapaian'],
                'standar' => $indikator['standar_target_pencapaian'] ?? '>=',
                'monthly_data' => []
            ];

            foreach ($stats as $stat) {
                $monthKey = $stat['month'];
                $row['monthly_data'][$monthKey] = [
                    'numerator' => $stat['numerator'],
                    'denumerator' => $stat['denumerator'],
                    'achievement' => $stat['achievement']
                ];
            }
            
            $resultData[] = $row;
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $resultData,
            'area_pengukuran' => $area['area_pengukuran'],
            'year' => $year
        ]);
    }
}
