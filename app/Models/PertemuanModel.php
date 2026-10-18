<?php

namespace App\Models;

use CodeIgniter\Model;

class PertemuanModel extends Model
{
    protected $table            = 'pertemuan';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $allowedFields    = ['kelas_kuliah_id', 'pertemuan_ke', 'tanggal', 'jam_buka', 'jam_tutup', 'materi_kuliah', 'qr_secret_salt', 'token_cadangan', 'status'];

    protected $validationRules = [
        'kelas_kuliah_id' => 'required|integer',
        'pertemuan_ke'    => 'required|integer|greater_than[0]',
    ];

    public function findByKelas(int $kelasKuliahId): array
    {
        return $this->where('kelas_kuliah_id', $kelasKuliahId)->orderBy('pertemuan_ke', 'ASC')->findAll();
    }

    /**
     * Generate token dinamis HMAC-SHA256 berdasarkan interval waktu.
     * Payload = hash_hmac('sha256', pertemuan_id . floor(timestamp / 20), qr_secret_salt)
     */
    public function generateToken(array $pertemuan, ?int $timestamp = null): string
    {
        $timestamp ??= time();
        $interval = (int) floor($timestamp / 20);

        return hash_hmac('sha256', $pertemuan['id'] . $interval, $pertemuan['qr_secret_salt']);
    }

    /**
     * Validasi token — menerima token untuk interval saat ini atau interval sebelumnya (toleransi 20 detik).
     */
    public function validasiToken(array $pertemuan, string $token): bool
    {
        if ($pertemuan['qr_secret_salt'] === null || $pertemuan['qr_secret_salt'] === '') {
            return false;
        }

        $now       = time();
        $interval  = (int) floor($now / 20);
        $candidates = [
            $interval,
            $interval - 1,
        ];

        foreach ($candidates as $i) {
            $expected = hash_hmac('sha256', $pertemuan['id'] . $i, $pertemuan['qr_secret_salt']);
            if (hash_equals($expected, $token)) {
                return true;
            }
        }

        return false;
    }
}
