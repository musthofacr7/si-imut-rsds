<?php

namespace App\Controllers;

use App\Models\UserAreaPengukuranModel;
use App\Models\SettingIndikatorMutuModel;
use App\Models\InputIndikatorRsModel;

class Dashboard extends BaseController
{
    protected $userAreaPengukuranModel;
    protected $settingIndikatorMutuModel;
    protected $inputIndikatorRsModel;

    public function __construct()
    {
        $this->userAreaPengukuranModel = new UserAreaPengukuranModel();
        $this->settingIndikatorMutuModel = new SettingIndikatorMutuModel();
        $this->inputIndikatorRsModel = new InputIndikatorRsModel();
    }

    public function index()
    {
        $userId = user_id();
        $chartData = [];
        
        // Get selected year from query parameter, default to current year
        $selectedYear = $this->request->getGet('year') ?? date('Y');

        // Get user's assigned areas
        $userAreas = $this->userAreaPengukuranModel->where('user_id', $userId)->findAll();
        
        if (!empty($userAreas)) {
            $areaIds = array_column($userAreas, 'area_pengukuran_id');
            
            // Get indicators for user's areas
            $indicators = $this->settingIndikatorMutuModel
                ->select('setting_indikator_mutu.indikator_mutu_id, setting_indikator_mutu.area_pengukuran_id, 
                          indikator_mutu.judul_indikator, area_pengukuran.area_pengukuran')
                ->join('indikator_mutu', 'indikator_mutu.id = setting_indikator_mutu.indikator_mutu_id')
                ->join('area_pengukuran', 'area_pengukuran.id = setting_indikator_mutu.area_pengukuran_id')
                ->whereIn('setting_indikator_mutu.area_pengukuran_id', $areaIds)
                ->findAll();

            // Generate months for selected year (Jan-Dec)
            $months = [];
            $monthLabels = [];
            $indoMonths = [
                1 => 'Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 
                'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'
            ];
            
            for ($m = 1; $m <= 12; $m++) {
                $monthStr = str_pad($m, 2, '0', STR_PAD_LEFT);
                $months[] = "$selectedYear-$monthStr";
                $monthLabels[] = $indoMonths[$m];
            }

            // Prepare chart data for each indicator
            foreach ($indicators as $indicator) {
                $achievementData = [];
                
                foreach ($months as $month) {
                    list($year, $monthNum) = explode('-', $month);
                    $startDate = "$year-$monthNum-01";
                    $endDate = date('Y-m-t', strtotime($startDate));
                    
                    // Get data for this month
                    $data = $this->inputIndikatorRsModel
                        ->select('SUM(numerator) as total_num, SUM(denumerator) as total_den')
                        ->where('indikator_mutu_id', $indicator['indikator_mutu_id'])
                        ->where('area_pengukuran_id', $indicator['area_pengukuran_id'])
                        ->where('tanggal >=', $startDate)
                        ->where('tanggal <=', $endDate)
                        ->first();
                    
                    $totalNum = $data['total_num'] ?? 0;
                    $totalDen = $data['total_den'] ?? 0;
                    $achievement = ($totalDen > 0) ? ($totalNum / $totalDen * 100) : 0;
                    
                    $achievementData[] = round($achievement, 2);
                }
                
                $chartData[] = [
                    'label' => $indicator['judul_indikator'] . ' - ' . $indicator['area_pengukuran'],
                    'data' => $achievementData
                ];
            }
        }

        return view('dashboard/index', [
            'chartData' => $chartData,
            'monthLabels' => $monthLabels ?? [],
            'selectedYear' => $selectedYear
        ]);
    }
}
