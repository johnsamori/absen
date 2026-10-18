<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['username', 'password_hash', 'email', 'role', 'is_active', 'avatar'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'id'       => 'permit_empty|integer',
        'username' => 'required|min_length[3]|max_length[100]|is_unique[users.username,id,{id}]',
        'email'    => 'permit_empty|valid_email|max_length[150]',
        'role'     => 'required|in_list[admin,dosen,mahasiswa,pimpinan]',
    ];

    public function findByUsername(string $username): ?array
    {
        return $this->where('username', $username)->first();
    }
}
