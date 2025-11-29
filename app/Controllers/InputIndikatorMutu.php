<?php

namespace App\Controllers;

use App\Models\InputIndikatorRsModel;
use App\Models\IndikatorMutuModel;
use App\Models\AreaPengukuranModel;
use App\Models\UserAreaPengukuranModel;
use App\Models\SettingIndikatorMutuModel;

class InputIndikatorMutu extends BaseController
{
    protected $inputIndikatorRsModel;
    protected $indikatorMutuModel;
    protected $areaPengukuranModel;
    protected $userAreaPengukuranModel;
    protected $settingIndikatorMutuModel;

    public function __construct()
    {
        $this->inputIndikatorRsModel = new InputIndikatorRsModel();
        $this->indikatorMutuModel = new IndikatorMutuModel();
        $this->areaPengukuranModel = new AreaPengukuranModel();
        $this->userAreaPengukuranModel = new UserAreaPengukuranModel();
        $this->settingIndikatorMutuModel = new SettingIndikatorMutuModel();
    }

    public function index()
    {
        // Get selected month and year from query params, default to current month
        $selectedMonth = $this->request->getGet('month') ?? date('m');
        $selectedYear = $this->request->getGet('year') ?? date('Y');
        
        // Get current user's area pengukuran
        $userId = user_id();
        $userAreas = $this->userAreaPengukuranModel->where('user_id', $userId)->findAll();
        
        // If user has no assigned areas, show all (for administrator)
        if (empty($userAreas)) {
            // Check if user is administrator
            if (in_groups('administrator')) {
                // Administrator can see all areas
                $areaIds = null; // Will not filter by area
            } else {
                // Non-admin user with no assigned areas
                return view('input_indikator_mutu/index', [
                    'indikators' => [],
                    'selectedMonth' => $selectedMonth,
                    'selectedYear' => $selectedYear,
                    'daysInMonth' => 0,
                    'existingData' => [],
                    'message' => 'Anda belum memiliki area pengukuran yang ditugaskan.'
                ]);
            }
        } else {
            // User has assigned areas
            $areaIds = array_column($userAreas, 'area_pengukuran_id');
        }
        
        // Get indikator mutu from setting_indikator_mutu
        $query = $this->settingIndikatorMutuModel
            ->select('setting_indikator_mutu.indikator_mutu_id, setting_indikator_mutu.area_pengukuran_id, 
                      indikator_mutu.judul_indikator, indikator_mutu.numerator, indikator_mutu.denumerator,
                      indikator_mutu.satuan_target_pencapaian,
                      area_pengukuran.area_pengukuran')
            ->join('indikator_mutu', 'indikator_mutu.id = setting_indikator_mutu.indikator_mutu_id')
            ->join('area_pengukuran', 'area_pengukuran.id = setting_indikator_mutu.area_pengukuran_id');
        
        // Filter by user's areas if not administrator or if user has assigned areas
        if ($areaIds !== null) {
            $query->whereIn('setting_indikator_mutu.area_pengukuran_id', $areaIds);
        }
        
        $settings = $query
            ->orderBy('area_pengukuran.area_pengukuran', 'ASC')
            ->orderBy('indikator_mutu.judul_indikator', 'ASC')
            ->findAll();
        
        if (empty($settings)) {
            return view('input_indikator_mutu/index', [
                'indikators' => [],
                'selectedMonth' => $selectedMonth,
                'selectedYear' => $selectedYear,
                'daysInMonth' => 0,
                'existingData' => [],
                'message' => 'Belum ada indikator mutu yang disetting untuk area Anda.'
            ]);
        }
        
        // Get all area IDs from settings for data retrieval
        $settingAreaIds = array_unique(array_column($settings, 'area_pengukuran_id'));
        
        // Get existing data for the selected month
        $startDate = "$selectedYear-$selectedMonth-01";
        $endDate = date('Y-m-t', strtotime($startDate));
        
        $existingData = $this->inputIndikatorRsModel
            ->whereIn('area_pengukuran_id', $settingAreaIds)
            ->where('tanggal >=', $startDate)
            ->where('tanggal <=', $endDate)
            ->findAll();
        
        // Organize existing data by indikator_id, area_id, and date for easy lookup
        $dataByKey = [];
        foreach ($existingData as $data) {
            $date = date('j', strtotime($data['tanggal'])); // Day of month without leading zeros
            $key = $data['indikator_mutu_id'] . '_' . $data['area_pengukuran_id'] . '_' . $date;
            $dataByKey[$key] = $data;
        }
        
        $daysInMonth = date('t', strtotime($startDate));
        
        return view('input_indikator_mutu/index', [
            'indikators' => $settings,
            'selectedMonth' => $selectedMonth,
            'selectedYear' => $selectedYear,
            'daysInMonth' => $daysInMonth,
            'existingData' => $dataByKey
        ]);
    }

    public function store()
    {
        // Handle AJAX request for save
        if ($this->request->isAJAX()) {
            $inputData = $this->request->getJSON(true);
            
            $tanggal = $inputData['tanggal'] ?? null;
            $indikatorId = $inputData['indikator_id'] ?? null;
            $areaId = $inputData['area_id'] ?? null;
            $numerator = $inputData['numerator'] ?? null;
            $denumerator = $inputData['denumerator'] ?? null;
            
            if (!$tanggal || !$indikatorId || !$areaId) {
                return $this->response->setJSON([
                    'success' => false,
                    'message' => 'Data tidak lengkap'
                ]);
            }
            
            // Check if record exists
            $existing = $this->inputIndikatorRsModel
                ->where('tanggal', $tanggal)
                ->where('indikator_mutu_id', $indikatorId)
                ->where('area_pengukuran_id', $areaId)
                ->first();
            
            $data = [
                'tanggal' => $tanggal,
                'indikator_mutu_id' => $indikatorId,
                'area_pengukuran_id' => $areaId,
                'numerator' => $numerator,
                'denumerator' => $denumerator,
            ];
            
            if ($existing) {
                // Update existing record
                $success = $this->inputIndikatorRsModel->update($existing['id'], $data);
            } else {
                // Insert new record
                $success = $this->inputIndikatorRsModel->save($data);
            }
            
            return $this->response->setJSON([
                'success' => $success,
                'message' => $success ? 'Data berhasil disimpan' : 'Gagal menyimpan data'
            ]);
        }
    }

    public function getDetail($id)
    {
        $data = $this->indikatorMutuModel->getWithJenisIndikator($id);

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
