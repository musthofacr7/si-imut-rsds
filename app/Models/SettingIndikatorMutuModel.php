<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingIndikatorMutuModel extends Model
{
    protected $table = 'setting_indikator_mutu';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['indikator_mutu_id', 'area_pengukuran_id'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'indikator_mutu_id' => 'required|integer',
        'area_pengukuran_id' => 'required|integer',
    ];
    protected $validationMessages = [
        'indikator_mutu_id' => [
            'required' => 'Indikator Mutu harus dipilih',
            'integer' => 'Indikator Mutu tidak valid',
        ],
        'area_pengukuran_id' => [
            'required' => 'Area Pengukuran harus dipilih',
            'integer' => 'Area Pengukuran tidak valid',
        ],
    ];
    protected $skipValidation = false;

    // Get data with joins
    public function getWithRelations()
    {
        return $this->select('setting_indikator_mutu.*, indikator_mutu.judul_indikator, area_pengukuran.area_pengukuran')
            ->join('indikator_mutu', 'indikator_mutu.id = setting_indikator_mutu.indikator_mutu_id')
            ->join('area_pengukuran', 'area_pengukuran.id = setting_indikator_mutu.area_pengukuran_id')
            ->findAll();
    }
}
