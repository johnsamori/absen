<?php

namespace App\Models;

use CodeIgniter\Model;

class MataKuliahModel extends Model
{
    protected $table            = 'mata_kuliah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kode_mk', 'nama_mk', 'sks', 'prodi_id', 'semester_paket'];

    protected $validationRules = [
        'id'      => 'permit_empty|integer',
        'kode_mk' => 'required|max_length[30]|is_unique[mata_kuliah.kode_mk,id,{id}]',
        'nama_mk' => 'required|max_length[150]',
        'sks'     => 'required|integer|greater_than[0]|less_than_equal_to[12]',
    ];
}
