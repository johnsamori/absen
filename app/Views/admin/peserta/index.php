<?php
/**
 * @var array<string, mixed> $kelas
 * @var list<array<string, mixed>> $peserta
 * @var list<array<string, mixed>> $mhsList
 * @var array<string, int> $kelasList
 */
?>
<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h4 class="mb-0">Peserta Kelas</h4>
        <small class="text-muted">
            <span class="fw-semibold text-dark"><?= esc($kelas['kode_mk']) ?></span> — <?= esc($kelas['nama_mk']) ?>
            (Kelas <?= esc($kelas['nama_kelas'] ?? '-') ?> · <?= esc($kelas['dosen_nama'] ?? 'Belum ada dosen') ?>)
        </small>
    </div>
    <a href="<?= site_url('admin/kelas-kuliah') ?>" class="btn btn-outline-secondary">
        <i class="fa-solid fa-arrow-left me-1"></i>Kembali ke Kelas
    </a>
</div>

<div class="row g-4">
    <!-- Kolom Kiri: Daftar Peserta Terdaftar -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-users me-1 text-primary"></i>Daftar Peserta
                    </h6>
                    <small class="text-muted"><span id="pesertaCountLabel"><?= count($peserta) ?></span> mahasiswa terdaftar</small>
                </div>
                <?php if ($peserta !== []): ?>
                    <button type="button" class="btn btn-sm btn-outline-danger" id="btnHapusTerpilih" style="display:none;" onclick="submitHapusBanyak()">
                        <i class="fa-solid fa-trash me-1"></i>Keluarkan (<span id="hapusCount">0</span>)
                    </button>
                <?php endif; ?>
            </div>

            <?php if ($peserta !== []): ?>
                <div class="px-3 pt-3">
                    <div class="input-group input-group-sm">
                        <span class="input-group-text bg-light"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                        <input type="text" id="searchPeserta" class="form-control" placeholder="Cari nama, NIM, atau kelas...">
                    </div>
                </div>
            <?php endif; ?>

            <div class="card-body p-0">
                <form id="formHapusBanyak" method="post" action="<?= site_url('admin/kelas-kuliah/' . $kelas['id'] . '/peserta/hapus-banyak') ?>">
                    <?= csrf_field() ?>
                    <div class="table-responsive" style="max-height: 520px; overflow-y: auto;">
                        <table class="table table-hover align-middle mb-0" id="tablePeserta">
                            <thead class="table-light sticky-top" style="z-index: 2;">
                                <tr>
                                    <?php if ($peserta !== []): ?>
                                        <th width="38" class="text-center">
                                            <input type="checkbox" id="checkMasterPeserta" class="form-check-input" title="Pilih Semua">
                                        </th>
                                    <?php endif; ?>
                                    <th>Mahasiswa</th>
                                    <th>Kelas</th>
                                    <th class="text-end" width="60">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($peserta === []): ?>
                                    <tr id="emptyPesertaRow">
                                        <td colspan="4" class="text-center text-muted py-5">
                                            <i class="fa-solid fa-user-slash fs-2 d-block mb-2 text-secondary opacity-50"></i>
                                            Belum ada peserta di kelas ini.<br>
                                            <small>Gunakan form di sebelah kanan untuk menambahkan peserta.</small>
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($peserta as $p): ?>
                                        <?php 
                                            $kpPeserta = trim($p['kelas_paralel'] ?? '');
                                            $searchPeserta = strtolower($p['nim'] . ' ' . $p['nama_lengkap'] . ' ' . $kpPeserta . ' ' . ($p['nama_prodi'] ?? ''));
                                        ?>
                                        <tr class="peserta-row" data-search="<?= esc($searchPeserta) ?>">
                                            <td class="text-center" onclick="event.stopPropagation();">
                                                <input type="checkbox" name="peserta_ids[]" value="<?= $p['peserta_id'] ?>" class="form-check-input peserta-check">
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= esc($p['nama_lengkap']) ?></div>
                                                <small class="text-muted"><?= esc($p['nim']) ?> · <?= esc($p['nama_prodi'] ?? '-') ?></small>
                                            </td>
                                            <td>
                                                <?php if ($kpPeserta !== ''): ?>
                                                    <span class="badge bg-secondary-subtle text-secondary border"><?= esc($kpPeserta) ?></span>
                                                <?php else: ?>
                                                    <span class="text-muted">-</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="text-end" onclick="event.stopPropagation();">
                                                <form method="post" action="<?= site_url('admin/peserta/' . $p['peserta_id'] . '/hapus') ?>"
                                                      onsubmit="return confirm('Keluarkan <?= esc($p['nama_lengkap'], 'js') ?> dari kelas?')">
                                                    <?= csrf_field() ?>
                                                    <button class="btn btn-sm btn-outline-danger" title="Keluarkan dari kelas">
                                                        <i class="fa-solid fa-user-minus"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Kolom Kanan: Tambah Peserta (Seleksi Semua / Per Kelas) -->
    <div class="col-lg-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white d-flex justify-content-between align-items-center py-3">
                <div>
                    <h6 class="mb-0 fw-bold text-dark">
                        <i class="fa-solid fa-user-plus me-1 text-primary"></i>Tambah Peserta
                    </h6>
                    <small class="text-muted">Pilih mahasiswa yang akan dimasukkan ke kelas</small>
                </div>
                <span class="badge bg-primary-subtle text-primary border px-2 py-1">
                    <?= count($mhsList) ?> Tersedia
                </span>
            </div>

            <div class="card-body">
                <?php if ($mhsList === []): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="fa-solid fa-circle-check text-success fs-1 d-block mb-3"></i>
                        <h6 class="fw-bold text-dark">Semua Mahasiswa Sudah Terdaftar</h6>
                        <p class="small mb-0">Tidak ada mahasiswa yang tersedia untuk ditambahkan ke kelas ini.</p>
                    </div>
                <?php else: ?>
                    <form method="post" action="<?= site_url('admin/kelas-kuliah/' . $kelas['id'] . '/peserta/tambah') ?>" id="formTambahPeserta">
                        <?= csrf_field() ?>

                        <!-- Pilihan Kelas & Filter -->
                        <div class="mb-3">
                            <label class="form-label fw-semibold small text-muted text-uppercase mb-1">
                                <i class="fa-solid fa-users-rectangle me-1 text-primary"></i>Pilih Mahasiswa Per Kelas
                            </label>
                            <select id="filterKelas" class="form-select form-select-md">
                                <option value="ALL">Semua Kelas (<?= count($mhsList) ?> Mahasiswa)</option>
                                <?php foreach ($kelasList as $kp => $jml): ?>
                                    <option value="<?= esc($kp) ?>">Kelas <?= esc($kp) ?> (<?= $jml ?> Mahasiswa)</option>
                                <?php endforeach; ?>
                                <?php 
                                    $tanpaKelasCount = count(array_filter($mhsList, fn($m) => empty(trim($m['kelas_paralel'] ?? ''))));
                                    if ($tanpaKelasCount > 0): 
                                ?>
                                    <option value="__NONE__">Tanpa Kelas (<?= $tanpaKelasCount ?> Mahasiswa)</option>
                                <?php endif; ?>
                            </select>
                        </div>

                        <!-- Tombol Aksi Cepat Seleksi -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <button type="button" class="btn btn-sm btn-outline-primary" id="btnSelectFiltered" title="Pilih semua mahasiswa yang tampil pada filter saat ini">
                                <i class="fa-solid fa-check-double me-1"></i><span id="btnSelectFilteredLabel">Pilih Semua di Kelas Ini</span>
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-success" id="btnSelectAll" title="Pilih seluruh mahasiswa kandidat dari semua kelas">
                                <i class="fa-solid fa-list-check me-1"></i>Pilih Semua Mahasiswa
                            </button>
                            <button type="button" class="btn btn-sm btn-outline-secondary" id="btnDeselectAll" title="Batal pilih semua mahasiswa">
                                <i class="fa-solid fa-xmark me-1"></i>Batal Pilih
                            </button>
                        </div>

                        <!-- Pencarian Cepat Nama / NIM -->
                        <div class="mb-3">
                            <div class="input-group input-group-sm">
                                <span class="input-group-text bg-white"><i class="fa-solid fa-magnifying-glass text-muted"></i></span>
                                <input type="text" id="searchKandidat" class="form-control" placeholder="Cari nama atau NIM mahasiswa...">
                                <button type="button" class="btn btn-outline-secondary" id="btnClearSearch" style="display:none;">
                                    <i class="fa-solid fa-xmark"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Status Jumlah & Master Checkbox -->
                        <div class="d-flex justify-content-between align-items-center mb-2 px-1">
                            <small class="text-muted">
                                Menampilkan: <strong id="kandidatVisibleCount"><?= count($mhsList) ?></strong> mahasiswa
                            </small>
                            <span class="badge bg-primary px-2 py-1 fs-7" id="badgeSelectedCount">
                                <span id="kandidatSelectedCount">0</span> Mahasiswa Dipilih
                            </span>
                        </div>

                        <!-- Tabel Daftar Kandidat Mahasiswa (Scrollable) -->
                        <div class="table-responsive border rounded mb-3" style="max-height: 330px; overflow-y: auto;">
                            <table class="table table-hover align-middle mb-0" id="tableKandidat">
                                <thead class="table-light sticky-top" style="z-index: 2;">
                                    <tr>
                                        <th width="38" class="text-center">
                                            <input type="checkbox" id="checkMasterKandidat" class="form-check-input" title="Pilih Semua yang Ditampilkan">
                                        </th>
                                        <th>Mahasiswa</th>
                                        <th class="text-end" width="110">Kelas</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($mhsList as $m): ?>
                                        <?php 
                                            $kp = trim($m['kelas_paralel'] ?? '');
                                            $searchStr = strtolower($m['nim'] . ' ' . $m['nama_lengkap'] . ' ' . $kp . ' ' . ($m['nama_prodi'] ?? ''));
                                        ?>
                                        <tr class="kandidat-row" data-kelas="<?= esc($kp) ?>" data-search="<?= esc($searchStr) ?>" style="cursor: pointer;">
                                            <td class="text-center" onclick="event.stopPropagation();">
                                                <input type="checkbox" name="mahasiswa_ids[]" value="<?= $m['id'] ?>" class="form-check-input kandidat-cb" data-kelas="<?= esc($kp) ?>">
                                            </td>
                                            <td>
                                                <div class="fw-semibold text-dark"><?= esc($m['nama_lengkap']) ?></div>
                                                <small class="text-muted"><?= esc($m['nim']) ?> · <?= esc($m['nama_prodi'] ?? '-') ?></small>
                                            </td>
                                            <td class="text-end">
                                                <?php if ($kp !== ''): ?>
                                                    <span class="badge bg-secondary-subtle text-secondary border"><?= esc($kp) ?></span>
                                                <?php else: ?>
                                                    <span class="badge bg-light text-muted border">-</span>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                    <tr id="noKandidatMatch" style="display: none;">
                                        <td colspan="3" class="text-center text-muted py-4">
                                            <i class="fa-solid fa-filter-circle-xmark fs-3 d-block mb-2 text-secondary opacity-50"></i>
                                            Tidak ada mahasiswa yang sesuai dengan filter.
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Tombol Submit Tambah -->
                        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold" id="btnSubmitTambah" disabled>
                            <i class="fa-solid fa-user-plus me-1"></i>Tambahkan (<span id="submitCountLabel">0</span>) Mahasiswa Terpilih
                        </button>
                    </form>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ==========================================
    // 1. Logika Tambah Peserta (Kandidat)
    // ==========================================
    const filterKelas           = document.getElementById('filterKelas');
    const searchKandidat        = document.getElementById('searchKandidat');
    const btnClearSearch        = document.getElementById('btnClearSearch');
    const checkMasterKandidat   = document.getElementById('checkMasterKandidat');
    const btnSelectFiltered     = document.getElementById('btnSelectFiltered');
    const btnSelectFilteredLabel= document.getElementById('btnSelectFilteredLabel');
    const btnSelectAll          = document.getElementById('btnSelectAll');
    const btnDeselectAll        = document.getElementById('btnDeselectAll');
    const kandidatRows          = Array.from(document.querySelectorAll('.kandidat-row'));
    const kandidatCbs           = Array.from(document.querySelectorAll('.kandidat-cb'));
    const kandidatVisibleCount  = document.getElementById('kandidatVisibleCount');
    const kandidatSelectedCount = document.getElementById('kandidatSelectedCount');
    const submitCountLabel      = document.getElementById('submitCountLabel');
    const btnSubmitTambah       = document.getElementById('btnSubmitTambah');
    const noKandidatMatch       = document.getElementById('noKandidatMatch');

    function filterKandidatRows() {
        if (!filterKelas) return;

        const selectedKelas = filterKelas.value;
        const query = (searchKandidat ? searchKandidat.value : '').toLowerCase().trim();

        if (btnClearSearch) {
            btnClearSearch.style.display = query !== '' ? 'block' : 'none';
        }

        // Perbarui label tombol "Pilih Semua di Kelas Ini"
        if (btnSelectFilteredLabel) {
            if (selectedKelas === 'ALL') {
                btnSelectFilteredLabel.textContent = 'Pilih Semua yang Ditampilkan';
            } else if (selectedKelas === '__NONE__') {
                btnSelectFilteredLabel.textContent = 'Pilih Semua Tanpa Kelas';
            } else {
                btnSelectFilteredLabel.textContent = 'Pilih Semua Kelas ' + selectedKelas;
            }
        }

        let visibleCount = 0;
        kandidatRows.forEach(row => {
            const rowKelas = row.getAttribute('data-kelas') || '';
            const rowSearch = row.getAttribute('data-search') || '';

            let matchKelas = false;
            if (selectedKelas === 'ALL') {
                matchKelas = true;
            } else if (selectedKelas === '__NONE__') {
                matchKelas = (rowKelas === '');
            } else {
                matchKelas = (rowKelas === selectedKelas);
            }

            const matchSearch = query === '' || rowSearch.includes(query);

            if (matchKelas && matchSearch) {
                row.style.display = '';
                visibleCount++;
            } else {
                row.style.display = 'none';
            }
        });

        if (kandidatVisibleCount) {
            kandidatVisibleCount.textContent = visibleCount;
        }

        if (noKandidatMatch) {
            noKandidatMatch.style.display = (visibleCount === 0 && kandidatRows.length > 0) ? '' : 'none';
        }

        updateKandidatSelectionUI();
    }

    function updateKandidatSelectionUI() {
        const checkedList = kandidatCbs.filter(cb => cb.checked);
        const count = checkedList.length;

        if (kandidatSelectedCount) kandidatSelectedCount.textContent = count;
        if (submitCountLabel) submitCountLabel.textContent = count;
        if (btnSubmitTambah) btnSubmitTambah.disabled = (count === 0);

        // Highlight baris yang dicentang
        kandidatRows.forEach(row => {
            const cb = row.querySelector('.kandidat-cb');
            if (cb && cb.checked) {
                row.classList.add('table-primary');
            } else {
                row.classList.remove('table-primary');
            }
        });

        // Sinkronisasi status master checkbox kandidat terhadap baris yang sedang tampil
        if (checkMasterKandidat) {
            const visibleCbs = kandidatRows
                .filter(r => r.style.display !== 'none')
                .map(r => r.querySelector('.kandidat-cb'))
                .filter(Boolean);

            if (visibleCbs.length > 0 && visibleCbs.every(cb => cb.checked)) {
                checkMasterKandidat.checked = true;
                checkMasterKandidat.indeterminate = false;
            } else if (visibleCbs.some(cb => cb.checked)) {
                checkMasterKandidat.checked = false;
                checkMasterKandidat.indeterminate = true;
            } else {
                checkMasterKandidat.checked = false;
                checkMasterKandidat.indeterminate = false;
            }
        }
    }

    if (filterKelas) {
        filterKelas.addEventListener('change', filterKandidatRows);
    }

    if (searchKandidat) {
        searchKandidat.addEventListener('input', filterKandidatRows);
    }

    if (btnClearSearch) {
        btnClearSearch.addEventListener('click', function () {
            searchKandidat.value = '';
            filterKandidatRows();
            searchKandidat.focus();
        });
    }

    // Klik pada baris kandidat untuk toggle checkbox
    kandidatRows.forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.tagName.toLowerCase() === 'input' || e.target.closest('a')) {
                return;
            }
            const cb = this.querySelector('.kandidat-cb');
            if (cb) {
                cb.checked = !cb.checked;
                updateKandidatSelectionUI();
            }
        });
    });

    // Event listener langsung pada checkbox kandidat
    kandidatCbs.forEach(cb => {
        cb.addEventListener('change', updateKandidatSelectionUI);
    });

    // Master checkbox kandidat (hanya memengaruhi baris yang sedang tampil)
    if (checkMasterKandidat) {
        checkMasterKandidat.addEventListener('change', function () {
            const isChecked = this.checked;
            kandidatRows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.kandidat-cb');
                    if (cb) cb.checked = isChecked;
                }
            });
            updateKandidatSelectionUI();
        });
    }

    // Tombol: Pilih Semua di Kelas Ini / yang Tampil
    if (btnSelectFiltered) {
        btnSelectFiltered.addEventListener('click', function () {
            kandidatRows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.kandidat-cb');
                    if (cb) cb.checked = true;
                }
            });
            updateKandidatSelectionUI();
        });
    }

    // Tombol: Pilih Seluruh Mahasiswa Kandidat (Semua Kelas)
    if (btnSelectAll) {
        btnSelectAll.addEventListener('click', function () {
            if (filterKelas && filterKelas.value !== 'ALL') {
                filterKelas.value = 'ALL';
                if (searchKandidat) searchKandidat.value = '';
                filterKandidatRows();
            }
            kandidatCbs.forEach(cb => cb.checked = true);
            updateKandidatSelectionUI();
        });
    }

    // Tombol: Batal Pilih
    if (btnDeselectAll) {
        btnDeselectAll.addEventListener('click', function () {
            kandidatCbs.forEach(cb => cb.checked = false);
            updateKandidatSelectionUI();
        });
    }

    // Inisialisasi tampilan awal kandidat
    filterKandidatRows();

    // ==========================================
    // 2. Logika Daftar Peserta Terdaftar (Kiri)
    // ==========================================
    const searchPeserta      = document.getElementById('searchPeserta');
    const pesertaRows        = Array.from(document.querySelectorAll('.peserta-row'));
    const checkMasterPeserta = document.getElementById('checkMasterPeserta');
    const pesertaCbs         = Array.from(document.querySelectorAll('.peserta-check'));
    const btnHapusTerpilih   = document.getElementById('btnHapusTerpilih');
    const hapusCount         = document.getElementById('hapusCount');

    function filterPesertaRows() {
        if (!searchPeserta) return;
        const q = searchPeserta.value.toLowerCase().trim();
        pesertaRows.forEach(row => {
            const rowSearch = row.getAttribute('data-search') || '';
            row.style.display = (q === '' || rowSearch.includes(q)) ? '' : 'none';
        });
    }

    function updatePesertaSelectionUI() {
        const checkedList = pesertaCbs.filter(cb => cb.checked);
        const count = checkedList.length;

        if (hapusCount) hapusCount.textContent = count;
        if (btnHapusTerpilih) {
            btnHapusTerpilih.style.display = count > 0 ? 'inline-block' : 'none';
        }

        pesertaRows.forEach(row => {
            const cb = row.querySelector('.peserta-check');
            if (cb && cb.checked) {
                row.classList.add('table-danger');
            } else {
                row.classList.remove('table-danger');
            }
        });

        if (checkMasterPeserta) {
            const visibleCbs = pesertaRows
                .filter(r => r.style.display !== 'none')
                .map(r => r.querySelector('.peserta-check'))
                .filter(Boolean);

            if (visibleCbs.length > 0 && visibleCbs.every(cb => cb.checked)) {
                checkMasterPeserta.checked = true;
                checkMasterPeserta.indeterminate = false;
            } else if (visibleCbs.some(cb => cb.checked)) {
                checkMasterPeserta.checked = false;
                checkMasterPeserta.indeterminate = true;
            } else {
                checkMasterPeserta.checked = false;
                checkMasterPeserta.indeterminate = false;
            }
        }
    }

    if (searchPeserta) {
        searchPeserta.addEventListener('input', filterPesertaRows);
    }

    if (checkMasterPeserta) {
        checkMasterPeserta.addEventListener('change', function () {
            const isChecked = this.checked;
            pesertaRows.forEach(row => {
                if (row.style.display !== 'none') {
                    const cb = row.querySelector('.peserta-check');
                    if (cb) cb.checked = isChecked;
                }
            });
            updatePesertaSelectionUI();
        });
    }

    pesertaCbs.forEach(cb => {
        cb.addEventListener('change', updatePesertaSelectionUI);
    });

    pesertaRows.forEach(row => {
        row.addEventListener('click', function (e) {
            if (e.target.tagName.toLowerCase() === 'input' || e.target.closest('button') || e.target.closest('form')) {
                return;
            }
            const cb = this.querySelector('.peserta-check');
            if (cb) {
                cb.checked = !cb.checked;
                updatePesertaSelectionUI();
            }
        });
    });

    window.submitHapusBanyak = function () {
        const checkedList = pesertaCbs.filter(cb => cb.checked);
        if (checkedList.length === 0) return;
        if (confirm('Keluarkan ' + checkedList.length + ' mahasiswa terpilih dari kelas ini?')) {
            document.getElementById('formHapusBanyak').submit();
        }
    };
});
</script>
<?= $this->endSection() ?>
