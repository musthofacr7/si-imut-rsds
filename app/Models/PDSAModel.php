<?php

namespace App\Models;

use CodeIgniter\Model;

class PDSAModel extends Model
{
    protected $table            = 'pdsa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'user_id',
        'file_pdsa',
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
        'file_pdsa' => 'required',
        'deskripsi' => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
