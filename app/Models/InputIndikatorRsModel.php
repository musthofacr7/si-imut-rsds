<?php

namespace App\Models;

use CodeIgniter\Model;

class InputIndikatorRsModel extends Model
{
    protected $table = 'input_indikator_rs';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['tanggal', 'indikator_mutu_id', 'area_pengukuran_id', 'numerator', 'denumerator'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'tanggal' => 'required|valid_date',
        'indikator_mutu_id' => 'required|integer',
        'area_pengukuran_id' => 'required|integer',
        'numerator' => 'permit_empty|numeric',
        'denumerator' => 'permit_empty|numeric',
    ];
    protected $validationMessages = [
        'tanggal' => [
            'required' => 'Tanggal harus diisi',
            'valid_date' => 'Format tanggal tidak valid',
        ],
        'indikator_mutu_id' => [
            'required' => 'Indikator Mutu harus dipilih',
            'integer' => 'Indikator Mutu tidak valid',
        ],
        'area_pengukuran_id' => [
            'required' => 'Area Pengukuran harus dipilih',
            'integer' => 'Area Pengukuran tidak valid',
        ],
        'numerator' => [
            'numeric' => 'Numerator harus berupa angka',
        ],
        'denumerator' => [
            'numeric' => 'Denumerator harus berupa angka',
        ],
    ];
    protected $skipValidation = false;

    // Get data with joins
    public function getWithRelations()
    {
        return $this->select('input_indikator_rs.*, indikator_mutu.judul_indikator, area_pengukuran.area_pengukuran')
            ->join('indikator_mutu', 'indikator_mutu.id = input_indikator_rs.indikator_mutu_id')
            ->join('area_pengukuran', 'area_pengukuran.id = input_indikator_rs.area_pengukuran_id')
            ->findAll();
    }

    // Get chart data for specific indicator, area, and year
    public function getChartData($indikatorId, $areaId, $year)
    {
        $builder = $this->db->table($this->table);
        
        $builder->select("
            DATE_FORMAT(tanggal, '%Y-%m') as month,
            SUM(numerator) as total_numerator,
            SUM(denumerator) as total_denumerator
        ");
        
        $builder->where('indikator_mutu_id', $indikatorId);
        
        // If areaId is provided, filter by specific area
        // If empty, aggregate data from all areas
        if (!empty($areaId)) {
            $builder->where('area_pengukuran_id', $areaId);
        }
        
        $builder->where('YEAR(tanggal)', $year);
        $builder->groupBy('month');
        $builder->orderBy('month', 'ASC');
        
        return $builder->get()->getResultArray();
    }

    // Get available years from input data
    public function getAvailableYears()
    {
        $builder = $this->db->table($this->table);
        
        $builder->select('YEAR(tanggal) as year');
        $builder->distinct();
        $builder->orderBy('year', 'DESC');
        
        $results = $builder->get()->getResultArray();
        
        return array_column($results, 'year');
    }

    // Get monthly detailed statistics (numerator, denumerator, achievement)
    public function getMonthlyStats($indikatorId, $areaId, $year, $satuan = '%')
    {
        $data = $this->getChartData($indikatorId, $areaId, $year);
        
        // Initialize all 12 months with 0
        $months = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthKey = str_pad($i, 2, '0', STR_PAD_LEFT);
            $months[$monthKey] = [
                'month' => $monthKey,
                'numerator' => 0,
                'denumerator' => 0,
                'achievement' => 0
            ];
        }
        
        // Fill in actual data
        foreach ($data as $row) {
            $monthNum = substr($row['month'], 5, 2); // Extract month from YYYY-MM
            
            $months[$monthNum]['numerator'] = (int)$row['total_numerator'];
            $months[$monthNum]['denumerator'] = (int)$row['total_denumerator'];
            
            if ($row['total_denumerator'] > 0) {
                if ($satuan == '%') {
                    $achievement = ($row['total_numerator'] / $row['total_denumerator']) * 100;
                    $months[$monthNum]['achievement'] = round($achievement, 2);
                } else {
                    $achievement = ($row['total_numerator'] / $row['total_denumerator']);
                    $months[$monthNum]['achievement'] = round($achievement, 0);
                }
            }
        }
        
        return array_values($months);
    }

    // Get monthly statistics grouped by area
    public function getAreaMonthlyStats($indikatorId, $year, $areaId = null, $satuan = '%')
    {
        $builder = $this->db->table($this->table);
        
        $builder->select("
            area_pengukuran.id as area_id,
            area_pengukuran.area_pengukuran,
            DATE_FORMAT(input_indikator_rs.tanggal, '%Y-%m') as month,
            SUM(input_indikator_rs.numerator) as total_numerator,
            SUM(input_indikator_rs.denumerator) as total_denumerator
        ");
        
        $builder->join('area_pengukuran', 'area_pengukuran.id = input_indikator_rs.area_pengukuran_id');
        $builder->where('input_indikator_rs.indikator_mutu_id', $indikatorId);
        $builder->where('YEAR(input_indikator_rs.tanggal)', $year);
        
        if (!empty($areaId)) {
            $builder->where('input_indikator_rs.area_pengukuran_id', $areaId);
        }
        
        $builder->groupBy('area_pengukuran.id, month');
        $builder->orderBy('area_pengukuran.area_pengukuran ASC, month ASC');
        
        $results = $builder->get()->getResultArray();
        
        // Process results into a structured array: [AreaName => [Month => Data]]
        $structuredData = [];
        
        foreach ($results as $row) {
            $areaName = $row['area_pengukuran'];
            $monthNum = substr($row['month'], 5, 2);
            
            if (!isset($structuredData[$areaName])) {
                $structuredData[$areaName] = [];
                // Initialize all months
                for ($i = 1; $i <= 12; $i++) {
                    $m = str_pad($i, 2, '0', STR_PAD_LEFT);
                    $structuredData[$areaName][$m] = [
                        'numerator' => 0,
                        'denumerator' => 0,
                        'achievement' => 0
                    ];
                }
            }
            
            $structuredData[$areaName][$monthNum]['numerator'] = (int)$row['total_numerator'];
            $structuredData[$areaName][$monthNum]['denumerator'] = (int)$row['total_denumerator'];
            
            if ($row['total_denumerator'] > 0) {
                if ($satuan == '%') {
                    $achievement = ($row['total_numerator'] / $row['total_denumerator']) * 100;
                    $structuredData[$areaName][$monthNum]['achievement'] = round($achievement, 2);
                } else {
                    $achievement = ($row['total_numerator'] / $row['total_denumerator']);
                    $structuredData[$areaName][$monthNum]['achievement'] = round($achievement, 0);
                }
            }
        }
        
        return $structuredData;
    }

    // Get monthly achievement percentages (kept for backward compatibility)
    public function getMonthlyAchievement($indikatorId, $areaId, $year)
    {
        $stats = $this->getMonthlyStats($indikatorId, $areaId, $year);
        return array_column($stats, 'achievement');
    }
}
