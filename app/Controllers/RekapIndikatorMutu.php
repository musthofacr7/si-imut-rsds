<?php

namespace App\Controllers;

use App\Models\IndikatorMutuModel;
use App\Models\InputIndikatorRsModel;
use App\Models\AreaPengukuranModel;

class RekapIndikatorMutu extends BaseController
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
        $data['indikator_mutu'] = $this->indikatorMutuModel->getWithJenisIndikator();
        $data['area_pengukuran'] = $this->areaPengukuranModel->getActive();
        $data['available_years'] = $this->inputIndikatorRsModel->getAvailableYears();
        
        // Add current year as default if no data exists
        if (empty($data['available_years'])) {
            $data['available_years'] = [date('Y')];
        }
        
        return view('rekap_indikator_mutu/index', $data);
    }

    public function getChartData()
    {
        // Get POST parameters
        $indikatorId = $this->request->getPost('indikator_id');
        $areaId = $this->request->getPost('area_id'); // Can be empty for combined data
        $year = $this->request->getPost('year');

        // Validate required parameters (area is optional)
        if (!$indikatorId || !$year) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Get monthly achievement data (for chart - aggregated)
        $stats = $this->inputIndikatorRsModel->getMonthlyStats($indikatorId, $areaId, $year);
        
        // Get area-wise monthly stats (for table - matrix)
        $areaStats = $this->inputIndikatorRsModel->getAreaMonthlyStats($indikatorId, $year, $areaId);
        
        // Extract achievements for chart
        $achievements = array_column($stats, 'achievement');

        // Get indicator name for chart title
        $indikator = $this->indikatorMutuModel->find($indikatorId);
        
        // Get area name if specified, otherwise use "Semua Area"
        $areaName = 'Semua Area';
        if (!empty($areaId)) {
            $area = $this->areaPengukuranModel->find($areaId);
            $areaName = $area['area_pengukuran'] ?? 'Area';
        }

        return $this->response->setJSON([
            'success' => true,
            'data' => $achievements,
            'monthly_data' => $stats, // Kept for reference if needed
            'area_data' => $areaStats, // New matrix data
            'labels' => ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'],
            'title' => $indikator['judul_indikator'] ?? 'Indikator',
            'area' => $areaName,
            'year' => $year
        ]);
    }
}
