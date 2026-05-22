<?php

namespace App\Models;

use CodeIgniter\Model;

class LaporanKPCModel extends Model
{
    protected $table            = 'laporan_kpc';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'id_area_pengukuran',
        'tgl_kpc',
        'jam_kpc',
        'insiden',
        'pelapor',
        'pelapor_lain',
        'lokasi',
        'unit_terkait',
        'tindakan',
        'tindakan_oleh',
        'tim_terdiri',
        'petugas_lainnya',
        'pernah_terjadi',
        'langkah_pencegahan',
        'pembuat_laporan',
        'paraf_pembuat',
        'penerima_laporan',
        'paraf_penerima',
        'user_id'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'id_area_pengukuran' => 'required',
        'tgl_kpc'            => 'required',
        'insiden'            => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
