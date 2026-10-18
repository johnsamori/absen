<?php

namespace App\Models;

use CodeIgniter\Model;

class PresensiModel extends Model
{
    protected $table            = 'presensi';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['pertemuan_id', 'mahasiswa_id', 'waktu_presensi', 'status', 'metode_absen', 'latitude', 'longitude', 'jarak_meter', 'bukti_dokumen', 'catatan_keterangan'];

    public function findByPertemuan(int $pertemuanId): array
    {
        return $this->where('pertemuan_id', $pertemuanId)->findAll();
    }

    public function findByPertemuanMahasiswa(int $pertemuanId, int $mahasiswaId): ?array
    {
        return $this->where('pertemuan_id', $pertemuanId)->where('mahasiswa_id', $mahasiswaId)->first();
    }

    /**
     * Hitung ringkasan kehadiran mahasiswa pada suatu kelas (semua pertemuan).
     */
    public function rekapPerKelas(int $kelasKuliahId, int $mahasiswaId): array
    {
        $rows = $this->builder()
            ->select('presensi.*, pertemuan.pertemuan_ke')
            ->join('pertemuan', 'pertemuan.id = presensi.pertemuan_id')
            ->where('pertemuan.kelas_kuliah_id', $kelasKuliahId)
            ->where('presensi.mahasiswa_id', $mahasiswaId)
            ->get()->getResultArray();

        $total = [
            'Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0,
        ];

        foreach ($rows as $row) {
            $total[$row['status']] = ($total[$row['status']] ?? 0) + 1;
        }

        $jumlah = count($rows);
        $persen = $jumlah > 0 ? round(($total['Hadir'] / $jumlah) * 100, 1) : 0;

        return ['total' => $total, 'jumlah' => $jumlah, 'persen_hadir' => $persen, 'rows' => $rows];
    }

    /**
     * Ringkasan kehadiran seluruh mahasiswa pada satu pertemuan (untuk live counter / rekap).
     */
    public function rekapPertemuan(int $pertemuanId): array
    {
        $rows = $this->builder()
            ->select('presensi.*, mahasiswa.nim, mahasiswa.nama_lengkap')
            ->join('mahasiswa', 'mahasiswa.id = presensi.mahasiswa_id')
            ->where('presensi.pertemuan_id', $pertemuanId)
            ->orderBy('presensi.waktu_presensi', 'ASC')
            ->get()->getResultArray();

        $total = ['Hadir' => 0, 'Izin' => 0, 'Sakit' => 0, 'Alpa' => 0];
        foreach ($rows as $row) {
            $total[$row['status']] = ($total[$row['status']] ?? 0) + 1;
        }

        return ['total' => $total, 'rows' => $rows];
    }
}
