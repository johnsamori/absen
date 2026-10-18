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

        $data = [
            'title'    => 'Peserta Kelas',
            'kelas'    => $kelas[0],
            'peserta'  => $pesertaModel->getPeserta($kelasKuliahId),
            'mhsList'  => $this->kandidatMahasiswa($kelasKuliahId),
        ];

        return view('admin/peserta/index', $data);
    }

    public function tambah(int $kelasKuliahId)
    {
        $mhsId = (int) $this->request->getPost('mahasiswa_id');
        if ($mhsId === 0) {
            return redirect()->back()->with('error', 'Pilih mahasiswa terlebih dahulu.');
        }

        $pesertaModel = model(PesertaKelasModel::class);

        $ada = $pesertaModel->where('kelas_kuliah_id', $kelasKuliahId)->where('mahasiswa_id', $mhsId)->first();
        if ($ada !== null) {
            return redirect()->back()->with('error', 'Mahasiswa sudah terdaftar di kelas ini.');
        }

        $pesertaModel->insert([
            'kelas_kuliah_id' => $kelasKuliahId,
            'mahasiswa_id'    => $mhsId,
        ]);

        return redirect()->back()->with('success', 'Mahasiswa berhasil ditambahkan ke kelas.');
    }

    public function hapus(int $pesertaId)
    {
        model(PesertaKelasModel::class)->delete($pesertaId);

        return redirect()->back()->with('success', 'Peserta berhasil dihapus dari kelas.');
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
