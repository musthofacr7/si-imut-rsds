<?php

namespace App\Models;

use CodeIgniter\Model;

class AreaPengukuranModel extends Model
{
    protected $table = 'area_pengukuran';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['area_pengukuran', 'status'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'area_pengukuran' => 'required|max_length[255]',
        'status' => 'required|in_list[aktif,tidak aktif]',
    ];
    protected $validationMessages = [
        'area_pengukuran' => [
            'required' => 'Area Pengukuran harus diisi',
            'max_length' => 'Area Pengukuran maksimal 255 karakter',
        ],
        'status' => [
            'required' => 'Status harus dipilih',
            'in_list' => 'Status harus aktif atau tidak aktif',
        ],
    ];
    protected $skipValidation = false;

    // Get only active areas
    public function getActive()
    {
        return $this->where('status', 'aktif')->findAll();
    }
}
