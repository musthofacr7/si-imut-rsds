<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Tambah Laporan KPC<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<style>
    .section-badge { background:#0d6efd;color:#fff;font-weight:700;font-size:13px;border-radius:4px;padding:3px 10px;display:inline-block;margin-bottom:10px; }
    .form-check { margin-bottom:4px; }
</style>

<div class="mb-3">
    <a href="<?= base_url('laporan-kpc/daftar') ?>" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
    </a>
</div>

<?php if (session()->getFlashdata('errors')): ?>
<div class="alert alert-danger alert-dismissible fade show">
    <ul class="mb-0">
        <?php foreach (session()->getFlashdata('errors') as $err): ?>
        <li><?= esc($err) ?></li>
        <?php endforeach; ?>
    </ul>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<form action="<?= base_url('laporan-kpc/store') ?>" method="post">
<div class="card shadow-sm">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0 fw-semibold"><i class="bi bi-file-earmark-medical-fill text-primary me-2"></i>Formulir Laporan Kondisi Potensial Cedera (KPC)</h6>
  </div>
  <div class="card-body">

    <?php $isPjMutu = in_groups('pj-mutu') && !in_groups('administrator'); ?>
    <div class="row g-3 mb-3">
        <?php if ($isPjMutu): ?>
            <input type="hidden" name="id_area_pengukuran" value="<?= !empty($area_pengukuran) ? $area_pengukuran[0]['id'] : '' ?>">
        <?php else: ?>
        <div class="col-md-6">
            <label class="form-label fw-semibold">Ruangan Asal *</label>
            <select class="form-select" name="id_area_pengukuran" required>
                <option value="">Pilih Area</option>
                <?php foreach ($area_pengukuran as $area) : ?>
                    <option value="<?= $area['id'] ?>" <?= old('id_area_pengukuran') == $area['id'] ? 'selected' : '' ?>><?= $area['area_pengukuran'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">1. Tanggal dan Waktu ditemukan Kondisi Potensi Cedera (KPC) *</label>
      <div class="row g-3 ms-1">
        <div class="col-md-4">
          <label class="form-label">Tanggal</label>
          <input type="date" class="form-control" name="tgl_kpc" value="<?= old('tgl_kpc') ?>" required>
        </div>
        <div class="col-md-3">
          <label class="form-label">Jam</label>
          <input type="time" class="form-control" name="jam_kpc" value="<?= old('jam_kpc') ?>">
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">2. KPC *</label>
      <textarea class="form-control" name="insiden" rows="3" required placeholder="Jelaskan Kondisi Potensi Cedera..."><?= old('insiden') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">3. Orang Pertama Yang Melaporkan Insiden *</label>
      <div class="ms-1">
        <?php $pelaporOpts=['karyawan'=>'Karyawan : Dokter / Perawat / Petugas lainnya','pasien'=>'Pasien','keluarga'=>'Keluarga / Pendamping pasien','pengunjung'=>'Pengunjung']; ?>
        <?php foreach($pelaporOpts as $v=>$l): ?>
        <div class="form-check"><input class="form-check-input" type="radio" name="pelapor" value="<?= $v ?>" id="pl_<?= $v ?>" <?= old('pelapor')==$v?'checked':'' ?>><label class="form-check-label" for="pl_<?= $v ?>"><?= $l ?></label></div>
        <?php endforeach; ?>
        <div class="form-check"><input class="form-check-input" type="radio" name="pelapor" value="lain" id="pl_lain" <?= old('pelapor')=='lain'?'checked':'' ?>><label class="form-check-label" for="pl_lain">Lain-lain</label></div>
        <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_pelapor_lain" name="pelapor_lain" value="<?= old('pelapor_lain') ?>" placeholder="Sebutkan..." <?= old('pelapor')!='lain'?'disabled':'' ?>>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">4. Lokasi diketahui KPC</label>
      <div class="input-group ms-1">
        <span class="input-group-text">Lokasi</span>
        <input type="text" class="form-control" name="lokasi" value="<?= old('lokasi') ?>" placeholder="(sebutkan)">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">5. Unit / Departemen terkait KPC</label>
      <div class="input-group ms-1">
        <span class="input-group-text">Unit terkait</span>
        <input type="text" class="form-control" name="unit_terkait" value="<?= old('unit_terkait') ?>" placeholder="(sebutkan)">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">6. Tindakan apa yang dilakukan untuk mengatasi kondisi potensi cedera selama ini ?</label>
      <textarea class="form-control ms-1" name="tindakan" rows="3"><?= old('tindakan') ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">7. Tindakan dilakukan oleh *</label>
      <div class="ms-1">
        <div class="row align-items-center mb-2">
          <div class="col-auto"><div class="form-check">
            <input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="tim" id="to_tim" <?= in_array('tim',(array)old('tindakan_oleh',[]))?'checked':'' ?>>
            <label class="form-check-label" for="to_tim">Tim, terdiri dari :</label>
          </div></div>
          <div class="col"><input type="text" class="form-control form-control-sm bg-light" id="input_tim_terdiri" name="tim_terdiri" value="<?= old('tim_terdiri') ?>" placeholder="Sebutkan..." <?= !in_array('tim',(array)old('tindakan_oleh',[]))?'disabled':'' ?>></div>
        </div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="dokter" id="to_dokter" <?= in_array('dokter',(array)old('tindakan_oleh',[]))?'checked':'' ?>><label class="form-check-label" for="to_dokter">Dokter</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="perawat" id="to_perawat" <?= in_array('perawat',(array)old('tindakan_oleh',[]))?'checked':'' ?>><label class="form-check-label" for="to_perawat">Perawat</label></div>
        <div class="row align-items-center mt-1">
          <div class="col-auto"><div class="form-check">
            <input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="petugas_lain" id="to_petugas_lain" <?= in_array('petugas_lain',(array)old('tindakan_oleh',[]))?'checked':'' ?>>
            <label class="form-check-label" for="to_petugas_lain">Petugas lainnya</label>
          </div></div>
          <div class="col"><input type="text" class="form-control form-control-sm bg-light" id="input_petugas_lainnya" name="petugas_lainnya" value="<?= old('petugas_lainnya') ?>" placeholder="Sebutkan..." <?= !in_array('petugas_lain',(array)old('tindakan_oleh',[]))?'disabled':'' ?>></div>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label fw-semibold">8. Apakah kejadian yang sama pernah terjadi di Unit Kerja lain? *</label>
      <div class="d-flex gap-4 ms-1 mb-2">
        <div class="form-check"><input class="form-check-input" type="radio" name="pernah_terjadi" value="ya" id="pt_ya" <?= old('pernah_terjadi')=='ya'?'checked':'' ?>><label class="form-check-label" for="pt_ya">Ya</label></div>
        <div class="form-check"><input class="form-check-input" type="radio" name="pernah_terjadi" value="tidak" id="pt_tidak" <?= old('pernah_terjadi')=='tidak'?'checked':'' ?>><label class="form-check-label" for="pt_tidak">Tidak</label></div>
      </div>
      <textarea class="form-control ms-1" name="langkah_pencegahan" rows="3" placeholder="Apabila ya, Kapan ? dan Langkah / tindakan apa yang telah diambil pada Unit kerja tersebut untuk mencegah terulangnya kondisi yang sama?"><?= old('langkah_pencegahan') ?></textarea>
    </div>

    <hr>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="card border">
          <div class="card-header bg-light fw-bold text-center py-2">Pembuat Laporan</div>
          <div class="card-body">
            <div class="mb-2"><label class="form-label small">Nama</label><input type="text" class="form-control form-control-sm" name="pembuat_laporan" value="<?= old('pembuat_laporan') ?>"></div>
            <div class="mb-2"><label class="form-label small">Paraf</label><input type="text" class="form-control form-control-sm" name="paraf_pembuat" value="<?= old('paraf_pembuat') ?>"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border">
          <div class="card-header bg-light fw-bold text-center py-2">Penerima Laporan</div>
          <div class="card-body">
            <div class="mb-2"><label class="form-label small">Nama</label><input type="text" class="form-control form-control-sm" name="penerima_laporan" value="<?= old('penerima_laporan') ?>"></div>
            <div class="mb-2"><label class="form-label small">Paraf</label><input type="text" class="form-control form-control-sm" name="paraf_penerima" value="<?= old('paraf_penerima') ?>"></div>
          </div>
        </div>
      </div>
    </div>

  </div><!-- card-body -->
  <div class="card-footer text-end">
    <a href="<?= base_url('laporan-kpc/daftar') ?>" class="btn btn-secondary me-2">
      <i class="bi bi-x-circle"></i> Batal
    </a>
    <button type="submit" class="btn btn-primary">
      <i class="bi bi-save"></i> Simpan Laporan
    </button>
  </div>
</div>
</form>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
function initLainInput(radioId, inputId, groupName) {
    const radioLain = document.getElementById(radioId);
    const input = document.getElementById(inputId);
    if (!radioLain || !input) return;
    const allRadios = document.querySelectorAll(`input[type="radio"][name="${groupName}"]`);
    function syncState() {
        input.disabled = !radioLain.checked;
        if (radioLain.checked) { input.classList.remove('bg-light'); input.focus(); }
        else { input.classList.add('bg-light'); }
    }
    allRadios.forEach(r => r.addEventListener('change', syncState));
    syncState();
}
function initCheckboxInput(checkboxId, inputId) {
    const checkbox = document.getElementById(checkboxId);
    const input = document.getElementById(inputId);
    if (!checkbox || !input) return;
    function syncState() {
        input.disabled = !checkbox.checked;
        if (checkbox.checked) { input.classList.remove('bg-light'); }
        else { input.classList.add('bg-light'); }
    }
    checkbox.addEventListener('change', syncState);
    syncState();
}
document.addEventListener('DOMContentLoaded', () => {
    initLainInput('pl_lain', 'input_pelapor_lain', 'pelapor');
    initCheckboxInput('to_tim', 'input_tim_terdiri');
    initCheckboxInput('to_petugas_lain', 'input_petugas_lainnya');
});
</script>
<?= $this->endSection(); ?>
