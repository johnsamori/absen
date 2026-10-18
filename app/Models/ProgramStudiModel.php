<?php

namespace App\Models;

use CodeIgniter\Model;

class ProgramStudiModel extends Model
{
    protected $table            = 'program_studi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kode_prodi', 'nama_prodi', 'jenjang', 'kaprodi'];

    protected $validationRules = [
        'id'         => 'permit_empty|integer',
        'kode_prodi' => 'required|max_length[30]|is_unique[program_studi.kode_prodi,id,{id}]',
        'nama_prodi' => 'required|max_length[150]',
    ];
}
