<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class WidenKelasParalel extends Migration
{
    public function up(): void
    {
        $this->forge->modifyColumn('mahasiswa', [
            'kelas_paralel' => ['type' => 'VARCHAR', 'constraint' => 20, 'null' => true],
        ]);
    }

    public function down(): void
    {
        $this->forge->modifyColumn('mahasiswa', [
            'kelas_paralel' => ['type' => 'VARCHAR', 'constraint' => 10, 'null' => true],
        ]);
    }
}
