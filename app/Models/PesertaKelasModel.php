<?php

namespace App\Models;

use CodeIgniter\Model;

class PesertaKelasModel extends Model
{
    protected $table            = 'peserta_kelas';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kelas_kuliah_id', 'mahasiswa_id'];

    /**
     * Mengambil daftar peserta (mahasiswa) suatu kelas.
     */
    public function getPeserta(int $kelasKuliahId): array
    {
        return $this->builder()
            ->select('peserta_kelas.id as peserta_id, mahasiswa.*, program_studi.nama_prodi')
            ->join('mahasiswa', 'mahasiswa.id = peserta_kelas.mahasiswa_id')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left')
            ->where('peserta_kelas.kelas_kuliah_id', $kelasKuliahId)
            ->orderBy('mahasiswa.nim', 'ASC')
            ->get()->getResultArray();
    }

    /**
     * Mengambil daftar kelas yang diikuti seorang mahasiswa.
     */
    public function getKelasMahasiswa(int $mahasiswaId): array
    {
        return $this->builder()
            ->select('kelas_kuliah.*, mata_kuliah.kode_mk, mata_kuliah.nama_mk, mata_kuliah.sks,
                      dosen.nama_lengkap as dosen_nama, tahun_akademik.tahun, tahun_akademik.semester')
            ->join('kelas_kuliah', 'kelas_kuliah.id = peserta_kelas.kelas_kuliah_id')
            ->join('mata_kuliah', 'mata_kuliah.id = kelas_kuliah.mata_kuliah_id', 'left')
            ->join('dosen', 'dosen.id = kelas_kuliah.dosen_id', 'left')
            ->join('tahun_akademik', 'tahun_akademik.id = kelas_kuliah.tahun_akademik_id', 'left')
            ->where('peserta_kelas.mahasiswa_id', $mahasiswaId)
            ->orderBy('kelas_kuliah.id', 'DESC')
            ->get()->getResultArray();
    }
}
