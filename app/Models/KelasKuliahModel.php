<?php

namespace App\Models;

use CodeIgniter\Model;

class KelasKuliahModel extends Model
{
    protected $table            = 'kelas_kuliah';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['tahun_akademik_id', 'mata_kuliah_id', 'dosen_id', 'nama_kelas', 'hari', 'jam_mulai', 'jam_selesai', 'ruangan'];

    /**
     * Mengambil kelas beserta relasi mata kuliah, dosen, tahun akademik.
     */
    public function getKelasRelasi(array $where = [], ?int $limit = null, int $offset = 0, string $q = ''): array
    {
        $builder = $this->builder()
            ->select('kelas_kuliah.*, mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks,
                      dosen.nama_lengkap as dosen_nama, dosen.gelar_depan, dosen.gelar_belakang,
                      tahun_akademik.tahun, tahun_akademik.semester,
                      program_studi.kode_prodi, program_studi.nama_prodi, program_studi.kaprodi')
            ->join('mata_kuliah', 'mata_kuliah.id = kelas_kuliah.mata_kuliah_id', 'left')
            ->join('dosen', 'dosen.id = kelas_kuliah.dosen_id', 'left')
            ->join('tahun_akademik', 'tahun_akademik.id = kelas_kuliah.tahun_akademik_id', 'left')
            ->join('program_studi', 'program_studi.id = mata_kuliah.prodi_id', 'left');

        if ($where !== []) {
            $builder->where($where);
        }

        if ($q !== '') {
            $builder->groupStart()
                ->like('mata_kuliah.kode_mk', $q)
                ->orLike('mata_kuliah.nama_mk', $q)
                ->orLike('kelas_kuliah.nama_kelas', $q)
                ->orLike('dosen.nama_lengkap', $q)
                ->orLike('kelas_kuliah.hari', $q)
                ->orLike('kelas_kuliah.ruangan', $q)
                ->orLike('program_studi.nama_prodi', $q)
                ->groupEnd();
        }

        $builder->orderBy('kelas_kuliah.id', 'DESC');

        if ($limit !== null) {
            $builder->limit($limit, $offset);
        }

        return $builder->get()->getResultArray();
    }
}
