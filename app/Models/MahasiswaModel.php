<?php

namespace App\Models;

use CodeIgniter\Model;

class MahasiswaModel extends Model
{
    protected $table            = 'mahasiswa';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['user_id', 'nim', 'nama_lengkap', 'prodi_id', 'angkatan', 'kelas_paralel', 'no_hp'];

    protected $validationRules = [
        'id'           => 'permit_empty|integer',
        'nim'          => 'required|max_length[30]|is_unique[mahasiswa.nim,id,{id}]',
        'nama_lengkap' => 'required|max_length[150]',
    ];

    public function findByUserId(int $userId): ?array
    {
        return $this->where('user_id', $userId)->first();
    }
}
