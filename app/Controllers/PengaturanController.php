<?php

namespace App\Controllers;

use App\Models\PengaturanKampusModel;

class PengaturanController extends BaseController
{
    public function index()
    {
        $row = model(PengaturanKampusModel::class)->getPengaturan();

        return view('admin/pengaturan/index', [
            'title' => 'Konfigurasi Kampus',
            'row'   => $row,
        ]);
    }

    public function update()
    {
        $model = model(PengaturanKampusModel::class);
        $row   = $model->getPengaturan();

        $data = $this->request->getPost([
            'nama_lokasi', 'latitude_pusat', 'longitude_pusat', 'radius_toleransi_meter', 'syarat_kehadiran_persen',
        ]);

        if ($row === null) {
            $model->insert($data);
        } else {
            $model->update((int) $row['id'], $data);
        }

        return redirect()->to('/admin/pengaturan')->with('success', 'Konfigurasi kampus berhasil disimpan.');
    }
}
