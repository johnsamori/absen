<?php

namespace App\Models;

use CodeIgniter\Model;

class PengaturanKampusModel extends Model
{
    protected $table            = 'pengaturan_kampus';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['nama_lokasi', 'latitude_pusat', 'longitude_pusat', 'radius_toleransi_meter', 'syarat_kehadiran_persen'];

    public function getPengaturan(): ?array
    {
        return $this->first();
    }
}
