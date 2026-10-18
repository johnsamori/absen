<?php

namespace App\Controllers;

use App\Models\KelasKuliahModel;
use App\Models\MahasiswaModel;
use App\Models\PesertaKelasModel;

class PesertaController extends BaseController
{
    public function index(int $kelasKuliahId)
    {
        $kelas = model(KelasKuliahModel::class)->getKelasRelasi(['kelas_kuliah.id' => $kelasKuliahId]);

        if ($kelas === []) {
            return redirect()->to('/admin/kelas-kuliah')->with('error', 'Kelas tidak ditemukan.');
        }

        $pesertaModel = model(PesertaKelasModel::class);
        $kandidat     = $this->kandidatMahasiswa($kelasKuliahId);

        // Kumpulkan kelas paralel unik beserta jumlah mahasiswanya
        $kelasList = [];
        foreach ($kandidat as $m) {
            $kp = trim($m['kelas_paralel'] ?? '');
            if ($kp !== '') {
                $kelasList[$kp] = ($kelasList[$kp] ?? 0) + 1;
            }
        }
        ksort($kelasList);

        $data = [
            'title'     => 'Peserta Kelas',
            'kelas'     => $kelas[0],
            'peserta'   => $pesertaModel->getPeserta($kelasKuliahId),
            'mhsList'   => $kandidat,
            'kelasList' => $kelasList,
        ];

        return view('admin/peserta/index', $data);
    }

    public function tambah(int $kelasKuliahId)
    {
        // Mendukung array mahasiswa_ids[] atau single mahasiswa_id
        $mhsIds = $this->request->getPost('mahasiswa_ids');
        if (!is_array($mhsIds)) {
            $singleId = (int) $this->request->getPost('mahasiswa_id');
            $mhsIds   = $singleId > 0 ? [$singleId] : [];
        }

        $mhsIds = array_values(array_unique(array_filter(array_map('intval', $mhsIds), fn($id) => $id > 0)));

        if ($mhsIds === []) {
            return redirect()->back()->with('error', 'Pilih minimal satu mahasiswa terlebih dahulu.');
        }

        $pesertaModel = model(PesertaKelasModel::class);
        $db = db_connect();

        // Cari yang sudah terdaftar untuk menghindari duplikasi
        $sudah = $db->table('peserta_kelas')
            ->select('mahasiswa_id')
            ->where('kelas_kuliah_id', $kelasKuliahId)
            ->whereIn('mahasiswa_id', $mhsIds)
            ->get()->getResultArray();
        $sudahIds = array_column($sudah, 'mahasiswa_id');

        $toInsert = [];
        foreach ($mhsIds as $id) {
            if (!in_array($id, $sudahIds, true)) {
                $toInsert[] = [
                    'kelas_kuliah_id' => $kelasKuliahId,
                    'mahasiswa_id'    => $id,
                ];
            }
        }

        if ($toInsert === []) {
            return redirect()->back()->with('error', 'Semua mahasiswa yang dipilih sudah terdaftar di kelas ini.');
        }

        $pesertaModel->insertBatch($toInsert);
        $count = count($toInsert);

        $msg = $count === 1
            ? '1 mahasiswa berhasil ditambahkan ke kelas.'
            : "{$count} mahasiswa berhasil ditambahkan ke kelas.";

        if (count($sudahIds) > 0) {
            $msg .= ' (' . count($sudahIds) . ' mahasiswa sudah terdaftar sebelumnya)';
        }

        return redirect()->back()->with('success', $msg);
    }

    public function hapus(int $pesertaId)
    {
        model(PesertaKelasModel::class)->delete($pesertaId);

        return redirect()->back()->with('success', 'Peserta berhasil dihapus dari kelas.');
    }

    public function hapusBanyak(int $kelasKuliahId)
    {
        $pesertaIds = $this->request->getPost('peserta_ids');
        if (!is_array($pesertaIds) || $pesertaIds === []) {
            return redirect()->back()->with('error', 'Pilih minimal satu peserta untuk dikeluarkan.');
        }

        $pesertaIds = array_values(array_unique(array_filter(array_map('intval', $pesertaIds), fn($id) => $id > 0)));

        if ($pesertaIds === []) {
            return redirect()->back()->with('error', 'Pilih minimal satu peserta yang valid.');
        }

        $pesertaModel = model(PesertaKelasModel::class);
        $pesertaModel->where('kelas_kuliah_id', $kelasKuliahId)->whereIn('id', $pesertaIds)->delete();

        return redirect()->back()->with('success', count($pesertaIds) . ' peserta berhasil dikeluarkan dari kelas.');
    }

    /**
     * Daftar mahasiswa yang BELUM menjadi peserta kelas ini.
     */
    private function kandidatMahasiswa(int $kelasKuliahId): array
    {
        $db = db_connect();

        $sudah = $db->table('peserta_kelas')->select('mahasiswa_id')->where('kelas_kuliah_id', $kelasKuliahId)->get()->getResultArray();
        $ids   = array_column($sudah, 'mahasiswa_id');

        $builder = model(MahasiswaModel::class)
            ->select('mahasiswa.*, program_studi.nama_prodi')
            ->join('program_studi', 'program_studi.id = mahasiswa.prodi_id', 'left');

        if ($ids !== []) {
            $builder->whereNotIn('mahasiswa.id', $ids);
        }

        return $builder->orderBy('mahasiswa.nim', 'ASC')->findAll();
    }
}
