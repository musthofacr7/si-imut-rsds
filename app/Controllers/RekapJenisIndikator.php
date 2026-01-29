<?php

namespace App\Controllers;

use App\Models\IndikatorMutuModel;
use App\Models\InputIndikatorRsModel;
use App\Models\JenisIndikatorModel;
use App\Models\AreaPengukuranModel;

class RekapJenisIndikator extends BaseController
{
    protected $indikatorMutuModel;
    protected $inputIndikatorRsModel;
    protected $jenisIndikatorModel;
    protected $areaPengukuranModel;

    public function __construct()
    {
        $this->indikatorMutuModel = new IndikatorMutuModel();
        $this->inputIndikatorRsModel = new InputIndikatorRsModel();
        $this->jenisIndikatorModel = new JenisIndikatorModel();
        $this->areaPengukuranModel = new AreaPengukuranModel();
    }

    public function index()
    {
        $data['jenis_indikator'] = $this->jenisIndikatorModel->findAll();
        $data['available_years'] = $this->inputIndikatorRsModel->getAvailableYears();
        
        // Add current year as default if no data exists
        if (empty($data['available_years'])) {
            $data['available_years'] = [date('Y')];
        }
        
        return view('rekap_jenis_indikator/index', $data);
    }

    public function getData()
    {
        // Get POST parameters
        $jenisId = $this->request->getPost('jenis_indikator_id');
        $year = $this->request->getPost('year');

        // Validate required parameters
        if (!$jenisId || !$year) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Parameter tidak lengkap'
            ]);
        }

        // Get Jenis Indikator details
        $jenis = $this->jenisIndikatorModel->find($jenisId);
        if (!$jenis) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Jenis Indikator tidak ditemukan'
            ]);
        }

        // Get all indicators for this type
        $indicators = $this->indikatorMutuModel->where('jenis_indikator_id', $jenisId)->findAll();
        
        $resultData = [];
        
        foreach ($indicators as $indikator) {
            // Get combined stats for this indicator (across all areas)
            $stats = $this->inputIndikatorRsModel->getMonthlyStats($indikator['id'], null, $year, $indikator['satuan_target_pencapaian']);
            
            $row = [
                'id' => $indikator['id'],
                'judul' => $indikator['judul_indikator'],
                'target' => $indikator['target_pencapaian'],
                'satuan' => $indikator['satuan_target_pencapaian'],
                'standar' => $indikator['standar_target_pencapaian'] ?? '>=', // Default to >= if null
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
            'jenis_indikator' => $jenis['jenis_indikator'],
            'year' => $year
        ]);
    }
}
