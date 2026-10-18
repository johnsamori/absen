<?php

namespace App\Controllers;

use App\Models\DosenModel;
use App\Models\MahasiswaModel;
use App\Models\MataKuliahModel;
use App\Models\ProgramStudiModel;
use App\Models\UserModel;

/**
 * Impor data master (mahasiswa, dosen, mata kuliah) dari file CSV.
 */
class ImportController extends BaseController
{
    /**
     * Konfigurasi jenis impor yang didukung.
     */
    private function konfigurasi(string $jenis): ?array
    {
        $map = [
            'mahasiswa' => [
                'label'      => 'Mahasiswa',
                'entity'     => 'mahasiswa',
                'deskripsi'  => 'Impor data mahasiswa dari file CSV (encoding UTF-8, pemisah koma atau titik koma).',
                'password'   => 'mhs123',
                'kolom'      => [
                    'nim'            => ['wajib', 'Nomor Induk Mahasiswa (unik)'],
                    'nama_lengkap'   => ['wajib', 'Nama lengkap mahasiswa'],
                    'kode_prodi'     => ['opsional', 'Kode program studi, mis. S1-ADM (atau nama prodi)'],
                    'angkatan'       => ['opsional', 'Tahun angkatan, mis. 2025'],
                    'kelas_paralel'  => ['opsional', 'Kelas paralel, mis. A'],
                    'no_hp'          => ['opsional', 'Nomor HP'],
                ],
            ],
            'dosen' => [
                'label'      => 'Dosen',
                'entity'     => 'dosen',
                'deskripsi'  => 'Impor data dosen dari file CSV (encoding UTF-8, pemisah koma atau titik koma).',
                'password'   => 'dosen123',
                'kolom'      => [
                    'nip'             => ['wajib', 'Nomor Induk Pegawai (unik)'],
                    'nama_lengkap'    => ['wajib', 'Nama lengkap dosen'],
                    'nidn'            => ['opsional', 'Nomor Induk Dosen Nasional'],
                    'gelar_depan'     => ['opsional', 'Gelar depan, mis. Dr.'],
                    'gelar_belakang'  => ['opsional', 'Gelar belakang, mis. M.Si'],
                    'kode_prodi'      => ['opsional', 'Kode program studi, mis. S1-ADM (atau nama prodi)'],
                    'no_hp'           => ['opsional', 'Nomor HP'],
                ],
            ],
            'mata-kuliah' => [
                'label'      => 'Mata Kuliah',
                'entity'     => 'mata-kuliah',
                'deskripsi'  => 'Impor data mata kuliah dari file CSV (encoding UTF-8, pemisah koma atau titik koma).',
                'password'   => null,
                'kolom'      => [
                    'kode_mk'        => ['wajib', 'Kode mata kuliah (unik)'],
                    'nama_mk'        => ['wajib', 'Nama mata kuliah'],
                    'sks'            => ['wajib', 'Jumlah SKS (1-12)'],
                    'kode_prodi'     => ['opsional', 'Kode program studi, mis. S1-ADM (atau nama prodi)'],
                    'semester_paket' => ['opsional', 'Semester paket (1-8)'],
                ],
            ],
        ];

        return $map[$jenis] ?? null;
    }

    public function form(string $jenis)
    {
        $cfg = $this->konfigurasi($jenis);
        if ($cfg === null) {
            return redirect()->to('/admin')->with('error', 'Jenis impor tidak valid.');
        }

        return view('admin/import/form', [
            'title'  => 'Impor Data ' . $cfg['label'],
            'jenis'  => $jenis,
            'cfg'    => $cfg,
            'result' => null,
        ]);
    }

    public function proses(string $jenis)
    {
        $cfg = $this->konfigurasi($jenis);
        if ($cfg === null) {
            return redirect()->to('/admin')->with('error', 'Jenis impor tidak valid.');
        }

        $file = $this->request->getFile('file_csv');
        if ($file === null || ! $file->isValid()) {
            return redirect()->back()->withInput()->with('error', 'Pilih file CSV terlebih dahulu. ' . ($file?->getErrorString() ?? ''));
        }

        $ext = strtolower($file->getClientExtension());
        if (! in_array($ext, ['csv', 'txt'], true)) {
            return redirect()->back()->withInput()->with('error', 'File harus berformat CSV.');
        }

        $path = $file->getTempName();
        $content = file_get_contents($path);
        if ($content === false || trim($content) === '') {
            return redirect()->back()->withInput()->with('error', 'File CSV kosong atau tidak terbaca.');
        }

        $rows = $this->parseCsv($content);
        if ($rows === []) {
            return redirect()->back()->withInput()->with('error', 'Tidak ada baris data di dalam file CSV.');
        }

        $result = match ($jenis) {
            'mahasiswa'   => $this->importMahasiswa($rows),
            'dosen'       => $this->importDosen($rows),
            'mata-kuliah' => $this->importMataKuliah($rows),
            default       => ['sukses' => 0, 'dilewati' => 0, 'errors' => [], 'skips' => []],
        };

        return view('admin/import/form', [
            'title'  => 'Impor Data ' . $cfg['label'],
            'jenis'  => $jenis,
            'cfg'    => $cfg,
            'result' => $result,
        ]);
    }

    public function template(string $jenis)
    {
        $cfg = $this->konfigurasi($jenis);
        if ($cfg === null) {
            return redirect()->to('/admin')->with('error', 'Jenis impor tidak valid.');
        }

        $headers = array_keys($cfg['kolom']);
        $content = implode(',', $headers) . "\n";

        // Baris contoh.
        $contoh = [
            'mahasiswa'   => ['202501011', 'Nama Mahasiswa', 'S1-ADM', '2025', 'A', '081234567890'],
            'dosen'       => ['196801011995031002', 'Nama Dosen', '0001016803', 'Dr.', 'M.Si', 'S1-ADM', '081234567890'],
            'mata-kuliah' => ['ADM101', 'Nama Mata Kuliah', '3', 'S1-ADM', '1'],
        ];
        $content .= implode(',', $contoh[$jenis] ?? []) . "\n";

        return $this->response
            ->setHeader('Content-Type', 'text/csv; charset=utf-8')
            ->setHeader('Content-Disposition', 'attachment; filename="template_' . $jenis . '.csv"')
            ->setBody("\xEF\xBB\xBF" . $content);
    }

    // ---------------------------------------------------------------------
    // Parsing CSV
    // ---------------------------------------------------------------------

    /**
     * Parse CSV menjadi array assoc (header => nilai per baris).
     * Mendeteksi delimiter koma atau titik koma secara otomatis.
     */
    private function parseCsv(string $content): array
    {
        $content = preg_replace('/^\xEF\xBB\xBF/', '', $content); // buang BOM
        $lines   = preg_split('/\r\n|\r|\n/', $content);
        $lines   = array_values(array_filter($lines, static fn ($l) => trim($l) !== ''));

        if ($lines === []) {
            return [];
        }

        $delimiter = str_contains($lines[0], ';') ? ';' : ',';

        $header = str_getcsv(array_shift($lines), $delimiter);
        $header = array_map(static fn ($h) => strtolower(trim($h)), $header);

        $rows = [];
        foreach ($lines as $line) {
            $vals = str_getcsv($line, $delimiter);
            if (count($vals) !== count($header)) {
                // Pad/marge agar panjang konsisten.
                $vals = array_slice(array_pad($vals, count($header), ''), 0, count($header));
            }
            $row = [];
            foreach ($header as $i => $key) {
                $row[$key] = trim($vals[$i] ?? '');
            }
            $rows[] = $row;
        }

        return $rows;
    }

    /**
     * Validasi header: pastikan semua kolom wajib ada.
     */
    private function validasiHeader(array $cfg, array $header): ?string
    {
        $wajib = [];
        foreach ($cfg['kolom'] as $k => [$ket, $desc]) {
            if ($ket === 'wajib') {
                $wajib[] = $k;
            }
        }

        $missing = array_diff($wajib, $header);
        if ($missing !== []) {
            return 'Kolom wajib tidak ditemukan di header CSV: ' . implode(', ', $missing)
                . '. Gunakan template yang disediakan.';
        }

        return null;
    }

    private function mapProdi(): array
    {
        $byKode = [];
        $byNama = [];
        foreach (model(ProgramStudiModel::class)->findAll() as $p) {
            $byKode[strtoupper(trim($p['kode_prodi']))] = (int) $p['id'];
            $byNama[strtolower(trim($p['nama_prodi']))] = (int) $p['id'];
        }

        return [$byKode, $byNama];
    }

    /**
     * Resolve prodi dari kode/nama. Return: int id, null (kosong), false (tidak ditemukan).
     */
    private function resolveProdi(string $val, array $byKode, array $byNama)
    {
        $val = trim($val);
        if ($val === '') {
            return null;
        }
        if (isset($byKode[strtoupper($val)])) {
            return $byKode[strtoupper($val)];
        }
        if (isset($byNama[strtolower($val)])) {
            return $byNama[strtolower($val)];
        }

        return false;
    }

    // ---------------------------------------------------------------------
    // Import per entitas
    // ---------------------------------------------------------------------

    private function importMahasiswa(array $rows): array
    {
        $cfg = $this->konfigurasi('mahasiswa');
        $header = array_keys($rows[0]);
        if (($err = $this->validasiHeader($cfg, $header)) !== null) {
            return ['sukses' => 0, 'dilewati' => 0, 'errors' => [$err], 'skips' => []];
        }

        $mhsModel  = model(MahasiswaModel::class);
        $userModel = model(UserModel::class);
        [$byKode, $byNama] = $this->mapProdi();

        $sukses = 0;
        $dilewati = 0;
        $errors = [];
        $skips = [];

        foreach ($rows as $i => $r) {
            $no = $i + 2; // baris 1 = header
            $nim = $r['nim'] ?? '';
            $nama = $r['nama_lengkap'] ?? '';

            if ($nim === '' || $nama === '') {
                $errors[] = "Baris {$no}: NIM dan nama wajib diisi.";
                continue;
            }

            if ($mhsModel->where('nim', $nim)->first() !== null) {
                $dilewati++;
                $skips[] = "Baris {$no}: NIM {$nim} sudah ada (dilewati).";
                continue;
            }

            $prodiId = $this->resolveProdi($r['kode_prodi'] ?? '', $byKode, $byNama);
            if ($prodiId === false) {
                $errors[] = "Baris {$no}: Program studi '{$r['kode_prodi']}' tidak ditemukan.";
                continue;
            }

            $username = $nim;
            if ($userModel->where('username', $username)->first() !== null) {
                $username = $nim . '_' . random_int(100, 999);
            }

            $db = db_connect();
            $db->transBegin();

            $userId = $userModel->insert([
                'username'      => $username,
                'password_hash' => password_hash('mhs123', PASSWORD_DEFAULT),
                'email'         => $username . '@student.uncen.ac.id',
                'role'          => 'mahasiswa',
                'is_active'     => 1,
            ]);

            $mhsModel->insert([
                'user_id'       => $userId,
                'nim'           => $nim,
                'nama_lengkap'  => $nama,
                'prodi_id'      => $prodiId,
                'angkatan'      => $r['angkatan'] ?? null,
                'kelas_paralel' => $r['kelas_paralel'] ?? null,
                'no_hp'         => $r['no_hp'] ?? null,
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                $errors[] = "Baris {$no}: Gagal menyimpan mahasiswa {$nim}.";
                continue;
            }
            $db->transCommit();
            $sukses++;
        }

        return ['sukses' => $sukses, 'dilewati' => $dilewati, 'errors' => $errors, 'skips' => $skips];
    }

    private function importDosen(array $rows): array
    {
        $cfg = $this->konfigurasi('dosen');
        $header = array_keys($rows[0]);
        if (($err = $this->validasiHeader($cfg, $header)) !== null) {
            return ['sukses' => 0, 'dilewati' => 0, 'errors' => [$err], 'skips' => []];
        }

        $dosenModel = model(DosenModel::class);
        $userModel  = model(UserModel::class);
        [$byKode, $byNama] = $this->mapProdi();

        $sukses = 0;
        $dilewati = 0;
        $errors = [];
        $skips = [];

        foreach ($rows as $i => $r) {
            $no = $i + 2;
            $nip = $r['nip'] ?? '';
            $nama = $r['nama_lengkap'] ?? '';

            if ($nip === '' || $nama === '') {
                $errors[] = "Baris {$no}: NIP dan nama wajib diisi.";
                continue;
            }

            if ($dosenModel->where('nip', $nip)->first() !== null) {
                $dilewati++;
                $skips[] = "Baris {$no}: NIP {$nip} sudah ada (dilewati).";
                continue;
            }

            $prodiId = $this->resolveProdi($r['kode_prodi'] ?? '', $byKode, $byNama);
            if ($prodiId === false) {
                $errors[] = "Baris {$no}: Program studi '{$r['kode_prodi']}' tidak ditemukan.";
                continue;
            }

            $username = $nip;
            if ($userModel->where('username', $username)->first() !== null) {
                $username = $nip . '_' . random_int(100, 999);
            }

            $db = db_connect();
            $db->transBegin();

            $userId = $userModel->insert([
                'username'      => $username,
                'password_hash' => password_hash('dosen123', PASSWORD_DEFAULT),
                'email'         => $username . '@fisip.uncen.ac.id',
                'role'          => 'dosen',
                'is_active'     => 1,
            ]);

            $dosenModel->insert([
                'user_id'        => $userId,
                'nip'            => $nip,
                'nidn'           => $r['nidn'] ?? null,
                'nama_lengkap'   => $nama,
                'gelar_depan'    => $r['gelar_depan'] ?? null,
                'gelar_belakang' => $r['gelar_belakang'] ?? null,
                'prodi_id'       => $prodiId,
                'no_hp'          => $r['no_hp'] ?? null,
            ]);

            if ($db->transStatus() === false) {
                $db->transRollback();
                $errors[] = "Baris {$no}: Gagal menyimpan dosen {$nip}.";
                continue;
            }
            $db->transCommit();
            $sukses++;
        }

        return ['sukses' => $sukses, 'dilewati' => $dilewati, 'errors' => $errors, 'skips' => $skips];
    }

    private function importMataKuliah(array $rows): array
    {
        $cfg = $this->konfigurasi('mata-kuliah');
        $header = array_keys($rows[0]);
        if (($err = $this->validasiHeader($cfg, $header)) !== null) {
            return ['sukses' => 0, 'dilewati' => 0, 'errors' => [$err], 'skips' => []];
        }

        $mkModel = model(MataKuliahModel::class);
        [$byKode, $byNama] = $this->mapProdi();

        $sukses = 0;
        $dilewati = 0;
        $errors = [];
        $skips = [];

        foreach ($rows as $i => $r) {
            $no = $i + 2;
            $kode = $r['kode_mk'] ?? '';
            $nama = $r['nama_mk'] ?? '';
            $sks  = (int) ($r['sks'] ?? 0);

            if ($kode === '' || $nama === '') {
                $errors[] = "Baris {$no}: Kode MK dan nama wajib diisi.";
                continue;
            }
            if ($sks < 1 || $sks > 12) {
                $errors[] = "Baris {$no}: SKS harus antara 1-12.";
                continue;
            }

            if ($mkModel->where('kode_mk', $kode)->first() !== null) {
                $dilewati++;
                $skips[] = "Baris {$no}: Kode MK {$kode} sudah ada (dilewati).";
                continue;
            }

            $prodiId = $this->resolveProdi($r['kode_prodi'] ?? '', $byKode, $byNama);
            if ($prodiId === false) {
                $errors[] = "Baris {$no}: Program studi '{$r['kode_prodi']}' tidak ditemukan.";
                continue;
            }

            $mkModel->insert([
                'kode_mk'        => $kode,
                'nama_mk'        => $nama,
                'sks'            => $sks,
                'prodi_id'       => $prodiId,
                'semester_paket' => $r['semester_paket'] !== '' ? (int) $r['semester_paket'] : null,
            ]);

            $sukses++;
        }

        return ['sukses' => $sukses, 'dilewati' => $dilewati, 'errors' => $errors, 'skips' => $skips];
    }
}
