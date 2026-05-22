<?php

namespace App\Models;

use CodeIgniter\Model;

class RiskRegisterModel extends Model
{
    protected $table            = 'risk_register';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'file_risk_register',
        'deskripsi',
        'keterangan', // New field
        'validasi',
        'komentar_admin',
        'tgl_validasi',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'user_id'   => 'required|integer',
        'file_risk_register' => 'required',
        'deskripsi' => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
