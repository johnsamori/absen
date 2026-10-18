<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\PertemuanModel;
use App\Models\TahunAkademikModel;

class DosenDashboardController extends BaseController
{
    public function index()
    {
        $dosenId = (int) session()->get('profile_id');
        $taAktif = model(TahunAkademikModel::class)->getAktif();

        $kelas = model(KelasKuliahModel::class)->getKelasRelasi([
            'kelas_kuliah.dosen_id' => $dosenId,
        ]);

        // Tambahkan jumlah peserta & pertemuan per kelas.
        $db = db_connect();
        $totalPeserta = 0;
        $totalPertemuan = 0;
        $totalSesiBuka = 0;

        foreach ($kelas as &$k) {
            $k['jml_peserta']   = $db->table('peserta_kelas')->where('kelas_kuliah_id', $k['id'])->countAllResults();
            $k['jml_pertemuan'] = $db->table('pertemuan')->where('kelas_kuliah_id', $k['id'])->countAllResults();

            $totalPeserta += (int) $k['jml_peserta'];
            $totalPertemuan += (int) $k['jml_pertemuan'];

            // Pertemuan aktif (status open).
            $k['pertemuan_aktif'] = $db->table('pertemuan')
                ->where('kelas_kuliah_id', $k['id'])
                ->where('status', 'open')
                ->orderBy('pertemuan_ke', 'DESC')
                ->get()->getFirstRow('array');

            if ($k['pertemuan_aktif'] !== null) {
                $totalSesiBuka++;
            }
        }
        unset($k);

        // Profil Dosen
        $dosen = model(\App\Models\DosenModel::class)
            ->select('dosen.*, program_studi.nama_prodi')
            ->join('program_studi', 'program_studi.id = dosen.prodi_id', 'left')
            ->find($dosenId);

        // Jadwal hari ini.
        $hariIni = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'][(int) date('w')];
        $jadwalHariIni = array_values(array_filter($kelas, fn ($k) => $k['hari'] === $hariIni));

        $data = [
            'title'           => 'Dashboard Dosen',
            'dosen'           => $dosen,
            'kelas'           => $kelas,
            'jadwalHariIni'   => $jadwalHariIni,
            'hariIni'         => $hariIni,
            'taAktif'         => $taAktif,
            'totalPeserta'    => $totalPeserta,
            'totalPertemuan'  => $totalPertemuan,
            'totalSesiBuka'   => $totalSesiBuka,
        ];

        return view('dosen/dashboard', $data);
    }
}
