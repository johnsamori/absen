<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateAbsensiTables extends Migration
{
    public function up(): void
    {
        // 1. users
        $this->forge->addField([
            'id'            => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'username'      => ['type' => 'VARCHAR', 'constraint' => 100],
            'password_hash' => ['type' => 'VARCHAR', 'constraint' => 255],
            'email'         => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
            'role'          => ['type' => 'ENUM', 'constraint' => ['admin', 'dosen', 'mahasiswa', 'pimpinan'], 'default' => 'mahasiswa'],
            'is_active'     => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 1],
            'avatar'        => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at'    => ['type' => 'DATETIME', 'null' => true],
            'updated_at'    => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('username');
        $this->forge->addKey('role');
        $this->forge->createTable('users', true);

        // 2. program_studi
        $this->forge->addField([
            'id'         => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode_prodi' => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama_prodi' => ['type' => 'VARCHAR', 'constraint' => 150],
            'jenjang'    => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'kaprodi'    => ['type' => 'VARCHAR', 'constraint' => 150, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_prodi');
        $this->forge->createTable('program_studi', true);

        // 3. tahun_akademik
        $this->forge->addField([
            'id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tahun'     => ['type' => 'VARCHAR', 'constraint' => 20],
            'semester'  => ['type' => 'ENUM', 'constraint' => ['Ganjil', 'Genap']],
            'is_active' => ['type' => 'TINYINT', 'constraint' => 1, 'default' => 0],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('tahun_akademik', true);

        // 4. dosen
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nip'            => ['type' => 'VARCHAR', 'constraint' => 30],
            'nidn'           => ['type' => 'VARCHAR', 'constraint' => 30, 'null' => true],
            'nama_lengkap'   => ['type' => 'VARCHAR', 'constraint' => 150],
            'gelar_depan'    => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'gelar_belakang' => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'prodi_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'no_hp'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nip');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('prodi_id', 'program_studi', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('dosen', true);

        // 5. mahasiswa
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id'        => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'nim'            => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama_lengkap'   => ['type' => 'VARCHAR', 'constraint' => 150],
            'prodi_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'angkatan'       => ['type' => 'VARCHAR', 'constraint' => 4, 'null' => true],
            'kelas_paralel'  => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'no_hp'          => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('nim');
        $this->forge->addKey('user_id');
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('prodi_id', 'program_studi', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('mahasiswa', true);

        // 6. mata_kuliah
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode_mk'        => ['type' => 'VARCHAR', 'constraint' => 30],
            'nama_mk'        => ['type' => 'VARCHAR', 'constraint' => 150],
            'sks'            => ['type' => 'INT', 'constraint' => 2, 'default' => 2],
            'prodi_id'       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'semester_paket' => ['type' => 'INT', 'constraint' => 2, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey('kode_mk');
        $this->forge->addForeignKey('prodi_id', 'program_studi', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('mata_kuliah', true);

        // 7. kelas_kuliah
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'tahun_akademik_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'mata_kuliah_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'dosen_id'          => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'nama_kelas'        => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
            'hari'              => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
            'jam_mulai'         => ['type' => 'TIME', 'null' => true],
            'jam_selesai'       => ['type' => 'TIME', 'null' => true],
            'ruangan'           => ['type' => 'VARCHAR', 'constraint' => 50, 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('tahun_akademik_id', 'tahun_akademik', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mata_kuliah_id', 'mata_kuliah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('dosen_id', 'dosen', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('kelas_kuliah', true);

        // 8. peserta_kelas
        $this->forge->addField([
            'id'             => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kelas_kuliah_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'mahasiswa_id'    => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['kelas_kuliah_id', 'mahasiswa_id']);
        $this->forge->addForeignKey('kelas_kuliah_id', 'kelas_kuliah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('peserta_kelas', true);

        // 9. pertemuan
        $this->forge->addField([
            'id'              => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kelas_kuliah_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'pertemuan_ke'    => ['type' => 'INT', 'constraint' => 2],
            'tanggal'         => ['type' => 'DATE', 'null' => true],
            'jam_buka'        => ['type' => 'DATETIME', 'null' => true],
            'jam_tutup'       => ['type' => 'DATETIME', 'null' => true],
            'materi_kuliah'   => ['type' => 'TEXT', 'null' => true],
            'qr_secret_salt'  => ['type' => 'VARCHAR', 'constraint' => 64, 'null' => true],
            'token_cadangan'  => ['type' => 'VARCHAR', 'constraint' => 6, 'null' => true],
            'status'          => ['type' => 'ENUM', 'constraint' => ['open', 'closed'], 'default' => 'open'],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kelas_kuliah_id', 'kelas_kuliah', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('pertemuan', true);

        // 10. presensi
        $this->forge->addField([
            'id'                => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'pertemuan_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'mahasiswa_id'      => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'waktu_presensi'    => ['type' => 'DATETIME', 'null' => true],
            'status'            => ['type' => 'ENUM', 'constraint' => ['Hadir', 'Izin', 'Sakit', 'Alpa'], 'default' => 'Hadir'],
            'metode_absen'      => ['type' => 'ENUM', 'constraint' => ['qr', 'token', 'manual'], 'default' => 'qr'],
            'latitude'          => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'longitude'         => ['type' => 'DECIMAL', 'constraint' => '10,7', 'null' => true],
            'jarak_meter'       => ['type' => 'DECIMAL', 'constraint' => '10,2', 'null' => true],
            'bukti_dokumen'     => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'catatan_keterangan' => ['type' => 'TEXT', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addUniqueKey(['pertemuan_id', 'mahasiswa_id']);
        $this->forge->addForeignKey('pertemuan_id', 'pertemuan', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('mahasiswa_id', 'mahasiswa', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('presensi', true);

        // 11. pengaturan_kampus
        $this->forge->addField([
            'id'                       => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_lokasi'              => ['type' => 'VARCHAR', 'constraint' => 150, 'default' => 'Kampus FISIP UNCEN'],
            'latitude_pusat'           => ['type' => 'DECIMAL', 'constraint' => '10,7'],
            'longitude_pusat'          => ['type' => 'DECIMAL', 'constraint' => '10,7'],
            'radius_toleransi_meter'   => ['type' => 'INT', 'constraint' => 11, 'default' => 150],
            'syarat_kehadiran_persen'  => ['type' => 'INT', 'constraint' => 3, 'default' => 75],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('pengaturan_kampus', true);
    }

    public function down(): void
    {
        $tables = [
            'presensi', 'pertemuan', 'peserta_kelas', 'kelas_kuliah',
            'mata_kuliah', 'mahasiswa', 'dosen', 'tahun_akademik',
            'program_studi', 'users', 'pengaturan_kampus',
        ];

        foreach ($tables as $table) {
            $this->forge->dropTable($table, true);
        }
    }
}
