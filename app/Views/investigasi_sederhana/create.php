<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Formulir Investigasi Sederhana<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<!-- ── Action Bar ── -->
<div class="d-flex justify-content-end gap-2 mb-3">
    <a href="<?= base_url('formulir-ikp/daftar') ?>" class="btn btn-outline-secondary btn-sm">
        <i class="bi bi-arrow-left"></i> Kembali
    </a>
</div>

<form action="<?= base_url('investigasi-sederhana/store') ?>" method="POST">
<?= csrf_field() ?>
<?php if (!empty($ikp_id)): ?>
    <input type="hidden" name="ikp_id" value="<?= esc($ikp_id) ?>">
<?php endif; ?>

<div class="card border-0 shadow-sm">

    <!-- ════════════════════════════════════════
         HEADER
    ════════════════════════════════════════ -->
    <div class="card-header bg-light text-dark border-bottom py-3">
        <div class="row align-items-center g-2">
            <div class="col-auto">
                <img src="<?= base_url('assets/img/logo-rsud.jpg') ?>"
                     alt="Logo RSUD" width="52" height="52"
                     class="rounded-circle border border-2 border-secondary bg-white p-1">
            </div>
            <div class="col text-center">
                <h6 class="fw-bold mb-0 text-uppercase text-dark">Lembar Kerja Investigasi Sederhana</h6>
                <p class="mb-0 small text-secondary">RSUD Dr. Soedirman Kebumen</p>
                <p class="mb-0 fst-italic text-muted" style="font-size:.75rem;">
                    untuk <em>Bands</em> Risiko &nbsp;
                    <span class="badge bg-primary bg-opacity-75">BIRU</span>
                    &nbsp;/&nbsp;
                    <span class="badge bg-success bg-opacity-75">HIJAU</span>
                </p>
            </div>
        </div>
    </div>

    <div class="card-body p-3 p-md-4">

        <!-- ════════════════════════════════════════
             BAGIAN 1 – PENYEBAB LANGSUNG
        ════════════════════════════════════════ -->
        <h6 class="d-flex align-items-center gap-2 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
            <i class="bi bi-1-circle-fill"></i> Penyebab Langsung Insiden
        </h6>

        <?php
        $langsung = [
            'alat'         => ['label' => 'Alat',         'ph' => 'Uraikan penyebab dari alat...'],
            'tempat_kerja' => ['label' => 'Tempat Kerja',  'ph' => 'Uraikan kondisi tempat kerja...'],
            'prosedur'     => ['label' => 'Prosedur',      'ph' => 'Uraikan faktor prosedur...'],
        ];
        ?>
        <div class="mb-4">
            <?php foreach ($langsung as $name => $item): ?>
            <div class="mb-3">
                <label class="form-label fw-semibold small mb-1"><?= $item['label'] ?></label>
                <textarea class="form-control form-control-sm"
                    name="<?= $name ?>" rows="3"
                    placeholder="<?= $item['ph'] ?>"></textarea>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ════════════════════════════════════════
             BAGIAN 2 – PENYEBAB MELATARBELAKANGI
        ════════════════════════════════════════ -->
        <h6 class="d-flex align-items-center gap-2 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
            <i class="bi bi-2-circle-fill"></i> Penyebab yang Melatarbelakangi / Akar Masalah Insiden
        </h6>

        <?php
        $akar = [
            'individu'          => ['label' => 'Individu',         'ph' => 'Uraikan faktor individu / SDM...'],
            'tempat_kerja_akar' => ['label' => 'Tempat Kerja',      'ph' => 'Uraikan faktor lingkungan / tempat kerja...'],
            'organisasi'        => ['label' => 'Organisasi / Tim',  'ph' => 'Uraikan faktor organisasi / tim...'],
        ];
        ?>
        <div class="mb-4">
            <?php foreach ($akar as $name => $item): ?>
            <div class="mb-3">
                <label class="form-label fw-semibold small mb-1"><?= $item['label'] ?></label>
                <textarea class="form-control form-control-sm"
                    name="<?= $name ?>" rows="3"
                    placeholder="<?= $item['ph'] ?>"></textarea>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- ════════════════════════════════════════
             BAGIAN 3 – REKOMENDASI
        ════════════════════════════════════════ -->
        <h6 class="d-flex align-items-center gap-2 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
            <i class="bi bi-3-circle-fill"></i> Rekomendasi
        </h6>

        <!-- Desktop: tabel | Mobile: kartu -->
        <div class="d-none d-md-block mb-2">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" id="tbl-rekomendasi-desk">
                    <thead class="table-light">
                        <tr>
                            <th style="min-width:240px;">Rekomendasi</th>
                            <th style="min-width:160px;">Penanggung Jawab</th>
                            <th style="min-width:140px;">Tanggal</th>
                            <th style="width:50px;" class="text-center d-print-none">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-rekomendasi">
                        <tr>
                            <td><textarea class="form-control form-control-sm border-0 shadow-none" name="rekomendasi[]" rows="2" placeholder="1. Rekomendasi..."></textarea></td>
                            <td><input type="text" class="form-control form-control-sm border-0 shadow-none" name="pj_rekomendasi[]" placeholder="Nama penanggung jawab"></td>
                            <td><input type="date" class="form-control form-control-sm border-0 shadow-none" name="tgl_rekomendasi[]"></td>
                            <td class="text-center d-print-none">
                                <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-row" title="Hapus baris" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile kartu rekomendasi -->
        <div class="d-md-none mb-2" id="mobile-rekomendasi">
            <div class="card border mb-2 mobile-rekomen-card">
                <div class="card-body p-2">
                    <label class="form-label small fw-semibold">Rekomendasi</label>
                    <textarea class="form-control form-control-sm mb-2" name="rekomendasi[]" rows="2" placeholder="1. Rekomendasi..."></textarea>
                    <label class="form-label small fw-semibold">Penanggung Jawab</label>
                    <input type="text" class="form-control form-control-sm mb-2" name="pj_rekomendasi[]" placeholder="Nama penanggung jawab">
                    <label class="form-label small fw-semibold">Tanggal</label>
                    <input type="date" class="form-control form-control-sm">
                </div>
            </div>
        </div>

        <div class="mb-4 d-print-none d-flex gap-2">
            <button type="button" class="btn btn-outline-success btn-sm" id="btn-tambah-rekomendasi">
                <i class="bi bi-plus-circle"></i> Tambah Baris
            </button>
        </div>

        <!-- Tindakan yang Akan Dilakukan -->
        <div class="d-none d-md-block mb-2">
            <div class="table-responsive">
                <table class="table table-bordered align-middle mb-0" id="tbl-tindakan-desk">
                    <thead class="table-secondary">
                        <tr>
                            <th style="min-width:240px;">Tindakan yang Akan Dilakukan</th>
                            <th style="min-width:160px;">Penanggung Jawab</th>
                            <th style="min-width:140px;">Tanggal</th>
                            <th style="width:50px;" class="text-center d-print-none">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody-tindakan">
                        <tr>
                            <td><textarea class="form-control form-control-sm border-0 shadow-none" name="tindakan[]" rows="2" placeholder="1. Tindakan..."></textarea></td>
                            <td><input type="text" class="form-control form-control-sm border-0 shadow-none" name="pj_tindakan[]" placeholder="Nama penanggung jawab"></td>
                            <td><input type="date" class="form-control form-control-sm border-0 shadow-none" name="tgl_tindakan[]"></td>
                            <td class="text-center d-print-none">
                                <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-row" title="Hapus baris" disabled>
                                    <i class="bi bi-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Mobile kartu tindakan -->
        <div class="d-md-none mb-2" id="mobile-tindakan">
            <div class="card border mb-2 mobile-tindakan-card">
                <div class="card-body p-2">
                    <label class="form-label small fw-semibold">Tindakan yang Akan Dilakukan</label>
                    <textarea class="form-control form-control-sm mb-2" name="tindakan[]" rows="2" placeholder="1. Tindakan..."></textarea>
                    <label class="form-label small fw-semibold">Penanggung Jawab</label>
                    <input type="text" class="form-control form-control-sm mb-2" name="pj_tindakan[]" placeholder="Nama penanggung jawab">
                    <label class="form-label small fw-semibold">Tanggal</label>
                    <input type="date" class="form-control form-control-sm" name="tgl_tindakan[]">
                </div>
            </div>
        </div>

        <div class="mb-4 d-print-none">
            <button type="button" class="btn btn-outline-success btn-sm" id="btn-tambah-tindakan">
                <i class="bi bi-plus-circle"></i> Tambah Baris
            </button>
        </div>

        <!-- ════════════════════════════════════════
             BAGIAN 4 – MANAGER / KEPALA BAGIAN
        ════════════════════════════════════════ -->
        <h6 class="d-flex align-items-center gap-2 fw-bold text-primary border-start border-4 border-primary ps-2 mb-3">
            <i class="bi bi-person-badge-fill"></i> Manager / Kepala Bagian / Kepala Unit
        </h6>

        <div class="row g-3 mb-4">
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small mb-1">Nama</label>
                <input type="text" class="form-control form-control-sm"
                    name="manajer_nama" placeholder="Nama manajer / kepala bagian">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small mb-1">Tanda Tangan</label>
                <input type="text" class="form-control form-control-sm"
                    name="ttd_manajer" placeholder="(tanda tangan)">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small mb-1">Tanggal mulai Investigasi</label>
                <input type="date" class="form-control form-control-sm"
                    name="tgl_mulai_investigasi">
            </div>
            <div class="col-12 col-md-6">
                <label class="form-label fw-semibold small mb-1">Tanggal selesai Investigasi</label>
                <input type="date" class="form-control form-control-sm"
                    name="tgl_selesai_investigasi">
            </div>
        </div>

        <!-- ════════════════════════════════════════
             BAGIAN 5 – MANAJEMEN RISIKO
        ════════════════════════════════════════ -->
        <div class="card border border-dark mb-0">
            <div class="card-header bg-dark text-white py-2">
                <h6 class="mb-0 fw-bold">
                    <i class="bi bi-shield-exclamation me-1"></i> Manajemen Risiko
                </h6>
            </div>
            <div class="card-body p-3">

                <!-- Row 1 – Investigasi Lengkap -->
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center flex-wrap gap-2 py-2 border-bottom">
                    <span class="fw-semibold text-nowrap" style="min-width:170px;">Investigasi Lengkap :</span>
                    <div class="d-flex gap-3">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="investigasi_lengkap" value="ya" id="il_ya">
                            <label class="form-check-label fw-semibold" for="il_ya">YA</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="investigasi_lengkap" value="tidak" id="il_tidak">
                            <label class="form-check-label fw-semibold" for="il_tidak">TIDAK</label>
                        </div>
                    </div>
                    <span class="fw-semibold">Tanggal :</span>
                    <input type="date" class="form-control form-control-sm" style="max-width:180px;" name="tgl_investigasi_lengkap">
                </div>

                <!-- Row 2 – Investigasi Lebih Lanjut -->
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center flex-wrap gap-2 py-2 border-bottom">
                    <span class="fw-semibold" style="min-width:170px;">Diperlukan Investigasi lebih lanjut :</span>
                    <div class="d-flex gap-3">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="investigasi_lanjut" value="ya" id="inv_lanjut_ya">
                            <label class="form-check-label fw-semibold" for="inv_lanjut_ya">YA</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="investigasi_lanjut" value="tidak" id="inv_lanjut_tidak">
                            <label class="form-check-label fw-semibold" for="inv_lanjut_tidak">TIDAK</label>
                        </div>
                    </div>
                </div>

                <!-- Row 3 – Grading Ulang -->
                <div class="d-flex flex-column flex-sm-row align-items-start align-items-sm-center flex-wrap gap-2 pt-2">
                    <span class="fw-semibold" style="min-width:170px;">Investigasi setelah <em>Grading</em> ulang :</span>
                    <div class="d-flex gap-3 flex-wrap">
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="grading_ulang" value="hijau" id="gu_hijau">
                            <label class="form-check-label fw-bold text-success" for="gu_hijau">Hijau</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="grading_ulang" value="kuning" id="gu_kuning">
                            <label class="form-check-label fw-bold text-warning" for="gu_kuning">Kuning</label>
                        </div>
                        <div class="form-check mb-0">
                            <input class="form-check-input" type="radio" name="grading_ulang" value="merah" id="gu_merah">
                            <label class="form-check-label fw-bold text-danger" for="gu_merah">Merah</label>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div><!-- /card-body -->

    <!-- Footer Buttons -->
    <div class="card-footer text-end">
        <a href="<?= base_url('formulir-ikp/daftar') ?>" class="btn btn-secondary btn-sm me-2">
            <i class="bi bi-arrow-left"></i> Batal
        </a>
        <button type="submit" class="btn btn-primary btn-sm">
            <i class="bi bi-save"></i> Simpan
        </button>
    </div>

</div><!-- /card -->
</form>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
/* ────────────────────────────────────────────────
   Desktop table – add / delete row
   ──────────────────────────────────────────────── */
function makeDesktopRow(placeholder, textareaName, pjName, tglName) {
    const tr = document.createElement('tr');
    tr.innerHTML = `
        <td><textarea class="form-control form-control-sm border-0 shadow-none" name="${textareaName}" rows="2" placeholder="${placeholder}"></textarea></td>
        <td><input type="text" class="form-control form-control-sm border-0 shadow-none" name="${pjName}" placeholder="Nama penanggung jawab"></td>
        <td><input type="date" class="form-control form-control-sm border-0 shadow-none" name="${tglName}"></td>
        <td class="text-center d-print-none">
            <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-row" title="Hapus baris">
                <i class="bi bi-trash"></i>
            </button>
        </td>
    `;
    return tr;
}

function updateDeleteButtons(tbody) {
    const rows = tbody.querySelectorAll('tr');
    rows.forEach(row => {
        const btn = row.querySelector('.btn-hapus-row');
        if (btn) btn.disabled = (rows.length <= 1);
    });
}

function initDesktopTable(tbodyId, addBtnId, textareaName, pjName, tglName, placeholderBase) {
    const tbody  = document.getElementById(tbodyId);
    const addBtn = document.getElementById(addBtnId);
    if (!tbody || !addBtn) return;

    updateDeleteButtons(tbody);

    addBtn.addEventListener('click', () => {
        const rowCount = tbody.querySelectorAll('tr').length + 1;
        const tr = makeDesktopRow(`${rowCount}. ${placeholderBase}`, textareaName, pjName, tglName);
        tbody.appendChild(tr);
        updateDeleteButtons(tbody);
    });

    tbody.addEventListener('click', e => {
        const btn = e.target.closest('.btn-hapus-row');
        if (!btn) return;
        btn.closest('tr').remove();
        updateDeleteButtons(tbody);
    });
}

/* ────────────────────────────────────────────────
   Mobile cards – add card
   ──────────────────────────────────────────────── */
function makeMobileCard(index, textareaName, pjName, tglName, placeholderBase, titleLabel) {
    const div = document.createElement('div');
    div.className = 'card border mb-2';
    div.innerHTML = `
        <div class="card-body p-2">
            <div class="d-flex justify-content-between align-items-center mb-1">
                <span class="fw-semibold small">${titleLabel} ke-${index}</span>
                <button type="button" class="btn btn-outline-danger btn-sm btn-hapus-card" title="Hapus">
                    <i class="bi bi-trash"></i>
                </button>
            </div>
            <label class="form-label small fw-semibold mb-1">${titleLabel}</label>
            <textarea class="form-control form-control-sm mb-2" name="${textareaName}" rows="2" placeholder="${index}. ${placeholderBase}"></textarea>
            <label class="form-label small fw-semibold mb-1">Penanggung Jawab</label>
            <input type="text" class="form-control form-control-sm mb-2" name="${pjName}" placeholder="Nama penanggung jawab">
            <label class="form-label small fw-semibold mb-1">Tanggal</label>
            <input type="date" class="form-control form-control-sm" name="${tglName}">
        </div>
    `;
    return div;
}

function updateMobileDeleteButtons(container) {
    const cards = container.querySelectorAll('.card');
    cards.forEach(card => {
        const btn = card.querySelector('.btn-hapus-card');
        if (btn) btn.disabled = (cards.length <= 1);
    });
}

function initMobileCards(containerId, addBtnId, textareaName, pjName, tglName, placeholderBase, titleLabel) {
    const container = document.getElementById(containerId);
    const addBtn    = document.getElementById(addBtnId);
    if (!container || !addBtn) return;

    updateMobileDeleteButtons(container);

    addBtn.addEventListener('click', () => {
        const index = container.querySelectorAll('.card').length + 1;
        const card  = makeMobileCard(index, textareaName, pjName, tglName, placeholderBase, titleLabel);
        container.appendChild(card);
        updateMobileDeleteButtons(container);
    });

    container.addEventListener('click', e => {
        const btn = e.target.closest('.btn-hapus-card');
        if (!btn) return;
        btn.closest('.card').remove();
        updateMobileDeleteButtons(container);
    });
}

document.addEventListener('DOMContentLoaded', () => {
    /* Desktop */
    initDesktopTable('tbody-rekomendasi', 'btn-tambah-rekomendasi',
        'rekomendasi[]', 'pj_rekomendasi[]', 'tgl_rekomendasi[]', 'Rekomendasi...');

    initDesktopTable('tbody-tindakan', 'btn-tambah-tindakan',
        'tindakan[]', 'pj_tindakan[]', 'tgl_tindakan[]', 'Tindakan...');

    /* Mobile */
    initMobileCards('mobile-rekomendasi', 'btn-tambah-rekomendasi',
        'rekomendasi[]', 'pj_rekomendasi[]', 'tgl_rekomendasi[]', 'Rekomendasi...', 'Rekomendasi');

    initMobileCards('mobile-tindakan', 'btn-tambah-tindakan',
        'tindakan[]', 'pj_tindakan[]', 'tgl_tindakan[]', 'Tindakan...', 'Tindakan');

    // Prevent duplicate submission of array fields
    const form = document.querySelector('form');
    if (form) {
        form.addEventListener('submit', function() {
            if (window.innerWidth >= 768) {
                // Desktop active, disable mobile inputs
                document.querySelectorAll('#mobile-rekomendasi input, #mobile-rekomendasi textarea').forEach(el => el.disabled = true);
                document.querySelectorAll('#mobile-tindakan input, #mobile-tindakan textarea').forEach(el => el.disabled = true);
            } else {
                // Mobile active, disable desktop inputs
                document.querySelectorAll('#tbl-rekomendasi-desk input, #tbl-rekomendasi-desk textarea').forEach(el => el.disabled = true);
                document.querySelectorAll('#tbl-tindakan-desk input, #tbl-tindakan-desk textarea').forEach(el => el.disabled = true);
            }
        });
    }
});
</script>
<?= $this->endSection(); ?>
