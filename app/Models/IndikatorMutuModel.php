<?php

namespace App\Models;

use CodeIgniter\Model;

class IndikatorMutuModel extends Model
{
    protected $table = 'indikator_mutu';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'judul_indikator',
        'dimensi_mutu',
        'tujuan',
        'jenis_indikator_id',
        'definisi_operasional',
        'numerator',
        'denumerator',
        'target_pencapaian',
        'satuan_target_pencapaian',
        'standar_target_pencapaian',
        'kriteria_inklusi',
        'kriteria_eksklusi',
        'sumber_data',
        'frekuensi_pengumpulan_data',
        'periode_analisis_data',
        'rencana_analisis',
        'instrumen_pengambilan_data',
        'area_pengukuran',
        'status',
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // Validation
    protected $validationRules = [
        'judul_indikator' => 'required|max_length[255]',
        'dimensi_mutu' => 'required|max_length[255]',
        'tujuan' => 'required',
        'jenis_indikator_id' => 'required|is_natural_no_zero',
        'definisi_operasional' => 'required',
        'numerator' => 'required',
        'denumerator' => 'required',
        'target_pencapaian' => 'required|max_length[255]',
        'satuan_target_pencapaian' => 'required|in_list[%,menit]',
        'standar_target_pencapaian' => 'permit_empty|in_list[>,<,>=,<=]',
        'sumber_data' => 'required|max_length[255]',
        'status' => 'permit_empty|in_list[aktif,tidak aktif]',
    ];
    protected $validationMessages = [
        'judul_indikator' => [
            'required' => 'Judul Indikator harus diisi',
        ],
        'jenis_indikator_id' => [
            'required' => 'Jenis Indikator harus dipilih',
            'is_natural_no_zero' => 'Jenis Indikator tidak valid',
        ],
        'status' => [
            'in_list' => 'Status harus aktif atau tidak aktif',
        ],
    ];
    protected $skipValidation = false;

    // Get data with jenis indikator
    public function getWithJenisIndikator($id = null, $jenisIndikatorId = null)
    {
        $builder = $this->select('indikator_mutu.*, jenis_indikator.jenis_indikator')
                        ->join('jenis_indikator', 'jenis_indikator.id = indikator_mutu.jenis_indikator_id');
        
        if ($id !== null) {
            return $builder->where('indikator_mutu.id', $id)->first();
        }

        if ($jenisIndikatorId !== null && $jenisIndikatorId !== '') {
            $builder->where('indikator_mutu.jenis_indikator_id', $jenisIndikatorId);
        }
        
        return $builder->findAll();
    }

    // Check if indicator has input data
    public function hasInputData($id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('input_indikator_rs');
        $count = $builder->where('indikator_mutu_id', $id)->countAllResults();
        
        return $count > 0;
    }
}
