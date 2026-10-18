<?php

namespace App\Models;

use CodeIgniter\Model;

class DosenModel extends Model
{
    protected $table            = 'dosen';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'nip', 'nidn', 'nama_lengkap', 'gelar_depan', 'gelar_belakang', 'prodi_id', 'no_hp'];

    protected $validationRules = [
        'id'           => 'permit_empty|integer',
        'nip'          => 'required|max_length[30]|is_unique[dosen.nip,id,{id}]',
        'nama_lengkap' => 'required|max_length[150]',
    ];

    public function findByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }

    public function namaGelar(array $dosen): string
    {
        $nama = trim(($dosen['gelar_depan'] ?? '') . ' ' . ($dosen['nama_lengkap'] ?? '') . ' ' . ($dosen['gelar_belakang'] ?? ''));
        return trim(preg_replace('/\s+/', ' ', $nama));
    }
}
