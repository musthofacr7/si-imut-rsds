<?php

namespace App\Models;

use CodeIgniter\Model;

class MasterSatuanIndikatorModel extends Model
{
    protected $table            = 'master_satuan_indikator';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['nama_satuan'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'nama_satuan' => 'required|max_length[50]|is_unique[master_satuan_indikator.nama_satuan,id,{id}]',
    ];
    protected $validationMessages   = [
        'nama_satuan' => [
            'required' => 'Nama Satuan harus diisi.',
            'max_length' => 'Nama Satuan maksimal 50 karakter.',
            'is_unique' => 'Nama Satuan sudah ada.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
