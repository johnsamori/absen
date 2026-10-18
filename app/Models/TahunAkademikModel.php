<?php

namespace App\Models;

use CodeIgniter\Model;

class TahunAkademikModel extends Model
{
    protected $table            = 'tahun_akademik';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['tahun', 'semester', 'is_active'];

    public function getAktif(): ?array
    {
        return $this->where('is_active', 1)->first();
    }

    /**
     * Menonaktifkan semua tahun akademik lalu mengaktifkan satu yang dipilih.
     */
    public function setAktif(int $id): void
    {
        $this->db->table($this->table)->update(['is_active' => 0]);
        $this->db->table($this->table)->update(['is_active' => 1], ['id' => $id]);
    }
}
