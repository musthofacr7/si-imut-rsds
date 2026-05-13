<?php

namespace App\Models;

use CodeIgniter\Model;

class InvestigasiSederhanaModel extends Model
{
    protected $table            = 'investigasi_sederhana';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;

    protected $allowedFields    = [
        'ikp_id',
        'alat',
        'tempat_kerja',
        'prosedur',
        'individu',
        'tempat_kerja_akar',
        'organisasi',
        'rekomendasi',
        'tindakan',
        'manajer_nama',
        'ttd_manajer',
        'tgl_mulai_investigasi',
        'tgl_selesai_investigasi',
        'investigasi_lengkap',
        'tgl_investigasi_lengkap',
        'investigasi_lanjut',
        'grading_ulang',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';
}
