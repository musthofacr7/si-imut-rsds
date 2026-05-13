<?php

namespace App\Models;

use CodeIgniter\Model;

class FormulirIKPModel extends Model
{
    protected $table            = 'formulir_ikp';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = true;
    protected $protectFields    = true;
    protected $allowedFields    = [
        // Data Pasien
        'nama_pasien',
        'no_mr',
        'id_area_pengukuran',
        'ruangan',
        'umur',
        'jenis_kelamin',
        'tgl_masuk',
        'jam_masuk',
        'biaya',
        // Rincian Kejadian
        'tgl_insiden',
        'jam_insiden',
        'insiden',
        'kronologis',
        'jenis_insiden',
        'pelapor',
        'pelapor_lain',
        'insiden_pada',
        'insiden_pada_lain',
        'pasien_jenis',
        'pasien_jenis_lain',
        'lokasi',
        'spesialisasi',
        'spesialisasi_lain',
        'unit_penyebab',
        'akibat',
        'tindakan_segera',
        'tindakan_oleh',       // JSON
        'tim_terdiri',
        'petugas_lainnya',
        'pernah_terjadi',
        'langkah_pencegahan',
        // Tanda Tangan
        'pembuat_laporan',
        'paraf_pembuat',
        'tgl_lapor',
        'penerima_laporan',
        'paraf_penerima',
        'tgl_terima',
        // Grading
        'grading',
        // Meta
        'user_id',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'nama_pasien' => 'required',
        'tgl_insiden' => 'required',
        'insiden'     => 'required',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;
}
