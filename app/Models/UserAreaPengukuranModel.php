<?php

namespace App\Models;

use CodeIgniter\Model;

class UserAreaPengukuranModel extends Model
{
    protected $table = 'user_area_pengukuran';
    protected $primaryKey = 'id';
    protected $allowedFields = ['user_id', 'area_pengukuran_id'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    /**
     * Get user's area pengukuran with area details
     */
    public function getUserAreas($userId)
    {
        return $this->select('user_area_pengukuran.*, area_pengukuran.area_pengukuran')
            ->join('area_pengukuran', 'area_pengukuran.id = user_area_pengukuran.area_pengukuran_id')
            ->where('user_area_pengukuran.user_id', $userId)
            ->findAll();
    }
}
