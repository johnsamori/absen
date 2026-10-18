<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MainSeeder extends Seeder
{
    public function run(): void
    {
        $db = $this->db;

        // ------------------------------------------------------------------
        // 1. Program Studi FISIP UNCEN
        // ------------------------------------------------------------------
        $prodiRows = [
            ['kode_prodi' => 'S1-ADM', 'nama_prodi' => 'S1 Ilmu Administrasi Publik', 'jenjang' => 'S1', 'kaprodi' => 'Dr. Yohanis Ronsumbre, M.Si'],
            ['kode_prodi' => 'S1-IP',  'nama_prodi' => 'S1 Ilmu Pemerintahan',       'jenjang' => 'S1', 'kaprodi' => 'Dr. Alimin Alwi, M.Si'],
            ['kode_prodi' => 'S1-HI',  'nama_prodi' => 'S1 Hubungan Internasional',  'jenjang' => 'S1', 'kaprodi' => 'Dr. Marthinus Sesa, M.Si'],
            ['kode_prodi' => 'S1-IK',  'nama_prodi' => 'S1 Ilmu Komunikasi',         'jenjang' => 'S1', 'kaprodi' => 'Dr. Jacobus Kastanya, M.Si'],
            ['kode_prodi' => 'S1-SOS', 'nama_prodi' => 'S1 Sosiologi',               'jenjang' => 'S1', 'kaprodi' => 'Dr. Erfiningsih, M.Si'],
            ['kode_prodi' => 'S1-ANT', 'nama_prodi' => 'S1 Antropologi Sosial',      'jenjang' => 'S1', 'kaprodi' => 'Dr. Wilhelmus Renyaan, M.Si'],
            ['kode_prodi' => 'S2-ADM', 'nama_prodi' => 'Magister Ilmu Administrasi', 'jenjang' => 'S2', 'kaprodi' => 'Prof. Dr. Yacob Pattiasina, M.Si'],
        ];

        $prodiIds = [];
        foreach ($prodiRows as $row) {
            $db->table('program_studi')->insert($row);
            $prodiIds[$row['kode_prodi']] = $db->insertID();
        }

        // ------------------------------------------------------------------
        // 2. Tahun Akademik
        // ------------------------------------------------------------------
        $db->table('tahun_akademik')->insertBatch([
            ['tahun' => '2025/2026', 'semester' => 'Genap',  'is_active' => 0],
            ['tahun' => '2026/2027', 'semester' => 'Ganjil', 'is_active' => 1],
        ]);

        // ------------------------------------------------------------------
        // 3. Users + Dosen
        // ------------------------------------------------------------------
        $dosenData = [
            ['username' => 'dosen1', 'nama' => 'Dr. Yakobus Murib, S.Sos., M.Si', 'nip' => '196801011995031002', 'nidn' => '0001016803', 'prodi' => 'S1-ADM'],
            ['username' => 'dosen2', 'nama' => 'Dr. Alimin Alwi, M.Si',           'nip' => '197002121998021001', 'nidn' => '0012027001', 'prodi' => 'S1-IP'],
            ['username' => 'dosen3', 'nama' => 'Marthinus Sesa, S.IP., M.Si',     'nip' => '198003152006041003', 'nidn' => '0015038002', 'prodi' => 'S1-HI'],
            ['username' => 'dosen4', 'nama' => 'Jacobus Kastanya, S.Sos., M.Si',  'nip' => '197505202002121004', 'nidn' => '0020057502', 'prodi' => 'S1-IK'],
        ];

        $dosenIds = [];
        foreach ($dosenData as $i => $d) {
            $userId = $this->insertUser($db, $d['username'], 'dosen123', 'dosen', $d['username'] . '@fisip.uncen.ac.id');
            $db->table('dosen')->insert([
                'user_id'        => $userId,
                'nip'            => $d['nip'],
                'nidn'           => $d['nidn'],
                'nama_lengkap'   => $d['nama'],
                'prodi_id'       => $prodiIds[$d['prodi']],
                'no_hp'          => '0812345678' . $i,
            ]);
            $dosenIds[$d['username']] = $db->insertID();
        }

        // ------------------------------------------------------------------
        // 4. Users + Mahasiswa
        // ------------------------------------------------------------------
        $mhsData = [
            ['username' => 'mhs1', 'nama' => 'Andi Saputra',      'nim' => '202501001', 'prodi' => 'S1-ADM', 'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs2', 'nama' => 'Bunga Lestari',     'nim' => '202501002', 'prodi' => 'S1-ADM', 'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs3', 'nama' => 'Citra Dewi',        'nim' => '202501003', 'prodi' => 'S1-IP',  'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs4', 'nama' => 'Dedi Kurniawan',    'nim' => '202501004', 'prodi' => 'S1-IP',  'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs5', 'nama' => 'Eka Wulandari',     'nim' => '202501005', 'prodi' => 'S1-HI',  'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs6', 'nama' => 'Fajar Nugraha',     'nim' => '202501006', 'prodi' => 'S1-HI',  'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs7', 'nama' => 'Gita Maharani',     'nim' => '202501007', 'prodi' => 'S1-IK',  'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs8', 'nama' => 'Hendra Gunawan',    'nim' => '202501008', 'prodi' => 'S1-IK',  'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs9', 'nama' => 'Intan Permata',     'nim' => '202501009', 'prodi' => 'S1-SOS', 'angkatan' => '2025', 'kelas' => 'A'],
            ['username' => 'mhs10', 'nama' => 'Joko Susilo',      'nim' => '202501010', 'prodi' => 'S1-SOS', 'angkatan' => '2025', 'kelas' => 'A'],
        ];

        $mhsIds = [];
        foreach ($mhsData as $m) {
            $userId = $this->insertUser($db, $m['username'], 'mhs123', 'mahasiswa', $m['username'] . '@student.uncen.ac.id');
            $db->table('mahasiswa')->insert([
                'user_id'       => $userId,
                'nim'           => $m['nim'],
                'nama_lengkap'  => $m['nama'],
                'prodi_id'      => $prodiIds[$m['prodi']],
                'angkatan'      => $m['angkatan'],
                'kelas_paralel' => $m['kelas'],
                'no_hp'         => '0812345678' . substr($m['nim'], -2),
            ]);
            $mhsIds[$m['username']] = $db->insertID();
        }

        // ------------------------------------------------------------------
        // 5. Users: Admin & Pimpinan
        // ------------------------------------------------------------------
        $this->insertUser($db, 'admin', 'admin123', 'admin', 'admin@fisip.uncen.ac.id');
        $this->insertUser($db, 'pimpinan', 'pimpinan123', 'pimpinan', 'dekan@fisip.uncen.ac.id');

        // ------------------------------------------------------------------
        // 6. Mata Kuliah
        // ------------------------------------------------------------------
        $mkRows = [
            ['kode_mk' => 'ADM101', 'nama_mk' => 'Pengantar Ilmu Administrasi',  'sks' => 3, 'prodi' => 'S1-ADM', 'semester_paket' => 1],
            ['kode_mk' => 'ADM201', 'nama_mk' => 'Manajemen Publik',             'sks' => 3, 'prodi' => 'S1-ADM', 'semester_paket' => 3],
            ['kode_mk' => 'IP101',  'nama_mk' => 'Pengantar Ilmu Politik',       'sks' => 3, 'prodi' => 'S1-IP',  'semester_paket' => 1],
            ['kode_mk' => 'IP201',  'nama_mk' => 'Pemerintahan Daerah',          'sks' => 3, 'prodi' => 'S1-IP',  'semester_paket' => 3],
            ['kode_mk' => 'HI101',  'nama_mk' => 'Pengantar Hubungan Internasional', 'sks' => 3, 'prodi' => 'S1-HI', 'semester_paket' => 1],
            ['kode_mk' => 'IK101',  'nama_mk' => 'Pengantar Ilmu Komunikasi',    'sks' => 3, 'prodi' => 'S1-IK',  'semester_paket' => 1],
            ['kode_mk' => 'SOS101', 'nama_mk' => 'Pengantar Sosiologi',          'sks' => 3, 'prodi' => 'S1-SOS', 'semester_paket' => 1],
        ];

        $mkIds = [];
        foreach ($mkRows as $row) {
            $row['prodi_id'] = $prodiIds[$row['prodi']];
            unset($row['prodi']);
            $db->table('mata_kuliah')->insert($row);
            $mkIds[$row['kode_mk']] = $db->insertID();
        }

        // ------------------------------------------------------------------
        // 7. Kelas Kuliah
        // ------------------------------------------------------------------
        $kelasRows = [
            ['mk' => 'ADM101', 'dosen' => 'dosen1', 'nama_kelas' => 'A', 'hari' => 'Senin',   'jam_mulai' => '08:00:00', 'jam_selesai' => '09:40:00', 'ruangan' => 'R.201'],
            ['mk' => 'IP101',  'dosen' => 'dosen2', 'nama_kelas' => 'A', 'hari' => 'Selasa',  'jam_mulai' => '10:00:00', 'jam_selesai' => '11:40:00', 'ruangan' => 'R.202'],
            ['mk' => 'HI101',  'dosen' => 'dosen3', 'nama_kelas' => 'A', 'hari' => 'Rabu',    'jam_mulai' => '13:00:00', 'jam_selesai' => '14:40:00', 'ruangan' => 'R.203'],
            ['mk' => 'IK101',  'dosen' => 'dosen4', 'nama_kelas' => 'A', 'hari' => 'Kamis',   'jam_mulai' => '08:00:00', 'jam_selesai' => '09:40:00', 'ruangan' => 'R.204'],
            ['mk' => 'SOS101', 'dosen' => 'dosen2', 'nama_kelas' => 'A', 'hari' => 'Jumat',   'jam_mulai' => '09:00:00', 'jam_selesai' => '10:40:00', 'ruangan' => 'R.205'],
        ];

        $kelasIds = [];
        foreach ($kelasRows as $row) {
            $db->table('kelas_kuliah')->insert([
                'tahun_akademik_id' => 2, // 2026/2027 Ganjil aktif
                'mata_kuliah_id'    => $mkIds[$row['mk']],
                'dosen_id'          => $dosenIds[$row['dosen']],
                'nama_kelas'        => $row['nama_kelas'],
                'hari'              => $row['hari'],
                'jam_mulai'         => $row['jam_mulai'],
                'jam_selesai'       => $row['jam_selesai'],
                'ruangan'           => $row['ruangan'],
            ]);
            $kelasIds[$row['mk']] = $db->insertID();
        }

        // ------------------------------------------------------------------
        // 8. Peserta Kelas (Plotting KRS)
        // ------------------------------------------------------------------
        $pesertaMap = [
            'ADM101' => ['mhs1', 'mhs2'],
            'IP101'  => ['mhs3', 'mhs4'],
            'HI101'  => ['mhs5', 'mhs6'],
            'IK101'  => ['mhs7', 'mhs8'],
            'SOS101' => ['mhs9', 'mhs10'],
        ];

        $pesertaBatch = [];
        foreach ($pesertaMap as $mk => $mhsList) {
            foreach ($mhsList as $mhsUser) {
                $pesertaBatch[] = [
                    'kelas_kuliah_id' => $kelasIds[$mk],
                    'mahasiswa_id'    => $mhsIds[$mhsUser],
                ];
            }
        }
        $db->table('peserta_kelas')->insertBatch($pesertaBatch);

        // ------------------------------------------------------------------
        // 9. Pengaturan Kampus (Koordinat pusat FISIP UNCEN)
        // ------------------------------------------------------------------
        $db->table('pengaturan_kampus')->insert([
            'nama_lokasi'             => 'Kampus FISIP Universitas Cenderawasih',
            'latitude_pusat'          => -2.596944,
            'longitude_pusat'         => 140.668556,
            'radius_toleransi_meter'  => 150,
            'syarat_kehadiran_persen' => 75,
        ]);
    }

    private function insertUser($db, string $username, string $password, string $role, string $email): int
    {
        $db->table('users')->insert([
            'username'      => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'email'         => $email,
            'role'          => $role,
            'is_active'     => 1,
            'created_at'    => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
        ]);

        return $db->insertID();
    }
}
