<?php

namespace App\Models;

use CodeIgniter\Model;

class JenisIndikatorModel extends Model
{
    protected $table = 'jenis_indikator';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = ['jenis_indikator', 'deskripsi'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'jenis_indikator' => 'required|max_length[255]',
        'deskripsi' => 'required|max_length[255]',
    ];
    protected $validationMessages = [
        'jenis_indikator' => [
            'required' => 'Jenis Indikator harus diisi',
            'max_length' => 'Jenis Indikator maksimal 255 karakter',
        ],
        'deskripsi' => [
            'required' => 'Deskripsi harus diisi',
            'max_length' => 'Deskripsi maksimal 255 karakter',
        ],
    ];
    protected $skipValidation = false;
}
