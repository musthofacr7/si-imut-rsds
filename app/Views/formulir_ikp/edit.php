<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Edit Laporan IKP<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<style>
    .section-badge { background:#0d6efd;color:#fff;font-weight:700;font-size:13px;border-radius:4px;padding:3px 10px;display:inline-block;margin-bottom:10px; }
    .form-check { margin-bottom:4px; }
    .grading-btn { min-width:90px;font-weight:700;letter-spacing:1px; }
</style>

<div class="mb-3">
    <a href="<?= base_url('formulir-ikp/daftar') ?>" class="btn btn-secondary">
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

<?php
// Helper: set_val — ambil dari old input dulu, fallback ke $ikp
function sv($field, $ikp) {
    $old = old($field);
    return $old !== null ? esc($old) : esc($ikp[$field] ?? '');
}
// Helper: checked radio
function rc($name, $val, $ikp) {
    $old = old($name);
    $cur = $old !== null ? $old : ($ikp[$name] ?? '');
    return $cur === $val ? 'checked' : '';
}
// Helper: checked checkbox (tindakan_oleh is array)
function cc($val, $ikp) {
    $arr = is_array($ikp['tindakan_oleh']) ? $ikp['tindakan_oleh'] : [];
    $old = old('tindakan_oleh');
    if ($old !== null) $arr = (array)$old;
    return in_array($val, $arr) ? 'checked' : '';
}
?>

<form action="<?= base_url('formulir-ikp/update/' . $ikp['id']) ?>" method="post">
<div class="card shadow-sm">
  <div class="card-header bg-white py-3">
    <h6 class="mb-0 fw-semibold"><i class="bi bi-pencil-square text-warning me-2"></i>Edit Laporan Insiden (IKP)</h6>
  </div>
  <div class="card-body">

    <div class="section-badge">I. DATA PASIEN</div>
    <?php $isPjMutu = in_groups('pj-mutu') && !in_groups('administrator'); ?>
    <div class="row g-3 mb-3">
      <div class="<?= $isPjMutu ? 'col-md-6' : 'col-md-3' ?>">
        <label class="form-label fw-semibold">Nama Pasien</label>
        <input type="text" class="form-control" name="nama_pasien" value="<?= sv('nama_pasien',$ikp) ?>" required>
      </div>
      <div class="col-md-3">
        <label class="form-label fw-semibold">No MR</label>
        <input type="text" class="form-control" name="no_mr" value="<?= sv('no_mr',$ikp) ?>">
      </div>
      <?php if ($isPjMutu): ?>
        <input type="hidden" name="id_area_pengukuran" value="<?= !empty($area_pengukuran) ? $area_pengukuran[0]['id'] : ($ikp['id_area_pengukuran'] ?? '') ?>">
      <?php else: ?>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Ruangan Asal *</label>
        <select class="form-select" name="id_area_pengukuran" required>
            <option value="">Pilih Area</option>
            <?php foreach ($area_pengukuran as $area) : ?>
                <option value="<?= $area['id'] ?>" <?= ($ikp['id_area_pengukuran'] ?? '') == $area['id'] ? 'selected' : '' ?>><?= $area['area_pengukuran'] ?></option>
            <?php endforeach; ?>
        </select>
      </div>
      <?php endif; ?>
      <div class="col-md-3">
        <label class="form-label fw-semibold">Detail Ruangan</label>
        <input type="text" class="form-control" name="ruangan" value="<?= sv('ruangan',$ikp) ?>">
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-12">
        <label class="form-label fw-semibold">Umur *</label>
        <div class="row row-cols-2 row-cols-md-4 g-2 ms-1">
          <?php $umurOpts=['0-1_bln'=>'0-1 bulan','>1bln-1thn'=>'> 1 bulan – 1 tahun','>1-5thn'=>'> 1 tahun – 5 tahun','>5-15thn'=>'> 5 tahun – 15 tahun','>15-30thn'=>'> 15 tahun – 30 tahun','>30-65thn'=>'> 30 tahun – 65 tahun','>65thn'=>'> 65 tahun']; ?>
          <?php foreach($umurOpts as $v=>$l): ?>
          <div class="col"><div class="form-check">
            <input class="form-check-input" type="radio" name="umur" value="<?= $v ?>" id="umur_<?= $v ?>" <?= rc('umur',$v,$ikp) ?>>
            <label class="form-check-label" for="umur_<?= $v ?>"><?= $l ?></label>
          </div></div>
          <?php endforeach; ?>
        </div>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-md-4">
        <label class="form-label fw-semibold">Jenis Kelamin *</label>
        <div class="d-flex gap-4 ms-1">
          <div class="form-check"><input class="form-check-input" type="radio" name="jenis_kelamin" value="L" id="jk_l" <?= rc('jenis_kelamin','L',$ikp) ?>><label class="form-check-label" for="jk_l">Laki-laki</label></div>
          <div class="form-check"><input class="form-check-input" type="radio" name="jenis_kelamin" value="P" id="jk_p" <?= rc('jenis_kelamin','P',$ikp) ?>><label class="form-check-label" for="jk_p">Perempuan</label></div>
        </div>
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Tanggal Masuk RS</label>
        <input type="date" class="form-control" name="tgl_masuk" value="<?= sv('tgl_masuk',$ikp) ?>">
      </div>
      <div class="col-md-4">
        <label class="form-label fw-semibold">Jam Masuk</label>
        <input type="time" class="form-control" name="jam_masuk" value="<?= sv('jam_masuk',$ikp) ?>">
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label fw-semibold">Penanggung Biaya Pasien</label>
      <div class="row row-cols-2 row-cols-md-3 g-2 ms-1">
        <?php $biayaOpts=['pribadi'=>'Pribadi','asuransi_swasta'=>'Asuransi Swasta','askes'=>'ASKES Pemerintah','perusahaan'=>'Perusahaan','jamkesmas'=>'JAMKESMAS','jamkesda'=>'JAMKESDA']; ?>
        <?php foreach($biayaOpts as $v=>$l): ?>
        <div class="col"><div class="form-check">
          <input class="form-check-input" type="radio" name="biaya" value="<?= $v ?>" id="biaya_<?= $v ?>" <?= rc('biaya',$v,$ikp) ?>>
          <label class="form-check-label" for="biaya_<?= $v ?>"><?= $l ?></label>
        </div></div>
        <?php endforeach; ?>
      </div>
    </div>

    <hr>
    <div class="section-badge">II. RINCIAN KEJADIAN</div>

    <p class="fw-semibold mb-2">1. Tanggal dan Waktu Insiden</p>
    <div class="row g-3 mb-3 ms-1">
      <div class="col-md-4">
        <label class="form-label">Tanggal</label>
        <input type="date" class="form-control" name="tgl_insiden" value="<?= sv('tgl_insiden',$ikp) ?>" required>
      </div>
      <div class="col-md-3">
        <label class="form-label">Jam</label>
        <input type="time" class="form-control" name="jam_insiden" value="<?= sv('jam_insiden',$ikp) ?>">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">2. Insiden</label>
      <input type="text" class="form-control" name="insiden" value="<?= sv('insiden',$ikp) ?>" required>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">3. Kronologis Insiden</label>
      <textarea class="form-control" name="kronologis" rows="4"><?= sv('kronologis',$ikp) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">4. Jenis Insiden *</label>
      <div class="ms-1">
        <div class="form-check"><input class="form-check-input" type="radio" name="jenis_insiden" value="KNC" id="ji_knc" <?= rc('jenis_insiden','KNC',$ikp) ?>><label class="form-check-label" for="ji_knc">Kejadian Nyaris Cedera / KNC</label></div>
        <div class="form-check"><input class="form-check-input" type="radio" name="jenis_insiden" value="KTC" id="ji_ktc" <?= rc('jenis_insiden','KTC',$ikp) ?>><label class="form-check-label" for="ji_ktc">Kejadian Tidak Cedera / KTC</label></div>
        <div class="form-check"><input class="form-check-input" type="radio" name="jenis_insiden" value="KTD" id="ji_ktd" <?= rc('jenis_insiden','KTD',$ikp) ?>><label class="form-check-label" for="ji_ktd">Kejadian Tidak Diharapkan / KTD</label></div>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">5. Orang Pertama Yang Melaporkan Insiden *</label>
      <div class="ms-1">
        <?php $pelaporOpts=['karyawan'=>'Karyawan : Dokter / Perawat / Petugas lainnya','pasien'=>'Pasien','keluarga'=>'Keluarga / Pendamping pasien','pengunjung'=>'Pengunjung']; ?>
        <?php foreach($pelaporOpts as $v=>$l): ?>
        <div class="form-check"><input class="form-check-input" type="radio" name="pelapor" value="<?= $v ?>" id="pl_<?= $v ?>" <?= rc('pelapor',$v,$ikp) ?>><label class="form-check-label" for="pl_<?= $v ?>"><?= $l ?></label></div>
        <?php endforeach; ?>
        <div class="form-check"><input class="form-check-input" type="radio" name="pelapor" value="lain" id="pl_lain" <?= rc('pelapor','lain',$ikp) ?>><label class="form-check-label" for="pl_lain">Lain-lain</label></div>
        <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_pelapor_lain" name="pelapor_lain" value="<?= sv('pelapor_lain',$ikp) ?>" placeholder="Sebutkan..." <?= ($ikp['pelapor']??'') !== 'lain' ? 'disabled' : '' ?>>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">6. Insiden terjadi pada *</label>
      <div class="ms-1">
        <div class="form-check"><input class="form-check-input" type="radio" name="insiden_pada" value="pasien" id="ip_pasien" <?= rc('insiden_pada','pasien',$ikp) ?>><label class="form-check-label" for="ip_pasien">Pasien</label></div>
        <div class="form-check"><input class="form-check-input" type="radio" name="insiden_pada" value="lain" id="ip_lain" <?= rc('insiden_pada','lain',$ikp) ?>><label class="form-check-label" for="ip_lain">Lain-lain</label></div>
        <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_insiden_pada_lain" name="insiden_pada_lain" value="<?= sv('insiden_pada_lain',$ikp) ?>" placeholder="Sebutkan..." <?= ($ikp['insiden_pada']??'') !== 'lain' ? 'disabled' : '' ?>>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">7. Insiden menyangkut pasien</label>
      <div class="ms-1">
        <?php $pasienOpts=['rawat_inap'=>'Pasien rawat inap','rawat_jalan'=>'Pasien rawat jalan','ugd'=>'Pasien UGD']; ?>
        <?php foreach($pasienOpts as $v=>$l): ?>
        <div class="form-check"><input class="form-check-input" type="radio" name="pasien_jenis" value="<?= $v ?>" id="pj_<?= $v ?>" <?= rc('pasien_jenis',$v,$ikp) ?>><label class="form-check-label" for="pj_<?= $v ?>"><?= $l ?></label></div>
        <?php endforeach; ?>
        <div class="form-check"><input class="form-check-input" type="radio" name="pasien_jenis" value="lain" id="pj_lain" <?= rc('pasien_jenis','lain',$ikp) ?>><label class="form-check-label" for="pj_lain">Lain-lain</label></div>
        <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_pasien_jenis_lain" name="pasien_jenis_lain" value="<?= sv('pasien_jenis_lain',$ikp) ?>" placeholder="Sebutkan..." <?= ($ikp['pasien_jenis']??'') !== 'lain' ? 'disabled' : '' ?>>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">8. Tempat Insiden</label>
      <div class="input-group ms-1">
        <span class="input-group-text">Lokasi kejadian</span>
        <input type="text" class="form-control" name="lokasi" value="<?= sv('lokasi',$ikp) ?>">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">9. Insiden terjadi pada pasien (sesuai kasus penyakit / spesialisasi)</label>
      <div class="row row-cols-1 row-cols-md-2 g-1 ms-1">
        <?php $spOpts=['penyakit_dalam'=>'Penyakit Dalam','anak'=>'Anak','bedah'=>'Bedah','obgyn'=>'Obstetri Gynekologi','tht'=>'THT','mata'=>'Mata','saraf'=>'Saraf','anastesi'=>'Anastesi','kulit'=>'Kulit & Kelamin','jantung'=>'Jantung','paru'=>'Paru','jiwa'=>'Jiwa']; ?>
        <?php foreach($spOpts as $v=>$l): ?>
        <div class="col"><div class="form-check">
          <input class="form-check-input" type="radio" name="spesialisasi" value="<?= $v ?>" id="sp_<?= $v ?>" <?= rc('spesialisasi',$v,$ikp) ?>>
          <label class="form-check-label" for="sp_<?= $v ?>"><?= $l ?></label>
        </div></div>
        <?php endforeach; ?>
        <div class="col-12">
          <div class="form-check"><input class="form-check-input" type="radio" name="spesialisasi" value="lain" id="sp_lain" <?= rc('spesialisasi','lain',$ikp) ?>><label class="form-check-label" for="sp_lain">Lain-lain</label></div>
          <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_spesialisasi_lain" name="spesialisasi_lain" value="<?= sv('spesialisasi_lain',$ikp) ?>" placeholder="Sebutkan..." <?= ($ikp['spesialisasi']??'') !== 'lain' ? 'disabled' : '' ?>>
        </div>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">10. Unit / Departemen terkait yang menyebabkan insiden</label>
      <div class="input-group ms-1">
        <span class="input-group-text">Unit kerja penyebab</span>
        <input type="text" class="form-control" name="unit_penyebab" value="<?= sv('unit_penyebab',$ikp) ?>">
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">11. Akibat Insiden Terhadap Pasien *</label>
      <div class="ms-1">
        <?php $akibatOpts=['kematian'=>'Kematian','cedera_berat'=>'Cedera Berat','cedera_sedang'=>'Cedera Sedang','cedera_ringan'=>'Cedera Ringan','tidak_cedera'=>'Tidak ada cedera']; ?>
        <?php foreach($akibatOpts as $v=>$l): ?>
        <div class="form-check"><input class="form-check-input" type="radio" name="akibat" value="<?= $v ?>" id="ak_<?= $v ?>" <?= rc('akibat',$v,$ikp) ?>><label class="form-check-label" for="ak_<?= $v ?>"><?= $l ?></label></div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">12. Tindakan yang dilakukan segera setelah kejadian, dan hasilnya</label>
      <textarea class="form-control" name="tindakan_segera" rows="3"><?= sv('tindakan_segera',$ikp) ?></textarea>
    </div>

    <div class="mb-3">
      <label class="form-label fw-semibold">13. Tindakan dilakukan oleh *</label>
      <div class="ms-1">
        <div class="row align-items-center mb-2">
          <div class="col-auto"><div class="form-check">
            <input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="tim" id="to_tim" <?= cc('tim',$ikp) ?>>
            <label class="form-check-label" for="to_tim">Tim, terdiri dari :</label>
          </div></div>
          <div class="col"><input type="text" class="form-control form-control-sm bg-light" id="input_tim_terdiri" name="tim_terdiri" value="<?= sv('tim_terdiri',$ikp) ?>" placeholder="Sebutkan..." <?= !in_array('tim', is_array($ikp['tindakan_oleh'])?$ikp['tindakan_oleh']:[]) ? 'disabled' : '' ?>></div>
        </div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="dokter" id="to_dokter" <?= cc('dokter',$ikp) ?>><label class="form-check-label" for="to_dokter">Dokter</label></div>
        <div class="form-check"><input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="perawat" id="to_perawat" <?= cc('perawat',$ikp) ?>><label class="form-check-label" for="to_perawat">Perawat</label></div>
        <div class="row align-items-center mt-1">
          <div class="col-auto"><div class="form-check">
            <input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="petugas_lain" id="to_petugas_lain" <?= cc('petugas_lain',$ikp) ?>>
            <label class="form-check-label" for="to_petugas_lain">Petugas lainnya</label>
          </div></div>
          <div class="col"><input type="text" class="form-control form-control-sm bg-light" id="input_petugas_lainnya" name="petugas_lainnya" value="<?= sv('petugas_lainnya',$ikp) ?>" placeholder="Sebutkan..." <?= !in_array('petugas_lain', is_array($ikp['tindakan_oleh'])?$ikp['tindakan_oleh']:[]) ? 'disabled' : '' ?>></div>
        </div>
      </div>
    </div>

    <div class="mb-4">
      <label class="form-label fw-semibold">14. Apakah kejadian yang sama pernah terjadi di Unit Kerja lain? *</label>
      <div class="d-flex gap-4 ms-1 mb-2">
        <div class="form-check"><input class="form-check-input" type="radio" name="pernah_terjadi" value="ya" id="pt_ya" <?= rc('pernah_terjadi','ya',$ikp) ?>><label class="form-check-label" for="pt_ya">Ya</label></div>
        <div class="form-check"><input class="form-check-input" type="radio" name="pernah_terjadi" value="tidak" id="pt_tidak" <?= rc('pernah_terjadi','tidak',$ikp) ?>><label class="form-check-label" for="pt_tidak">Tidak</label></div>
      </div>
      <textarea class="form-control" name="langkah_pencegahan" rows="3" placeholder="Uraikan langkah pencegahan..."><?= sv('langkah_pencegahan',$ikp) ?></textarea>
    </div>

    <hr>
    <div class="row g-3 mb-4">
      <div class="col-md-6">
        <div class="card border">
          <div class="card-header bg-light fw-bold text-center py-2">Pembuat Laporan</div>
          <div class="card-body">
            <div class="mb-2"><label class="form-label small">Nama</label><input type="text" class="form-control form-control-sm" name="pembuat_laporan" value="<?= sv('pembuat_laporan',$ikp) ?>"></div>
            <div class="mb-2"><label class="form-label small">Paraf</label><input type="text" class="form-control form-control-sm" name="paraf_pembuat" value="<?= sv('paraf_pembuat',$ikp) ?>"></div>
            <div><label class="form-label small">Tanggal Lapor</label><input type="date" class="form-control form-control-sm" name="tgl_lapor" value="<?= sv('tgl_lapor',$ikp) ?>"></div>
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="card border">
          <div class="card-header bg-light fw-bold text-center py-2">Penerima Laporan</div>
          <div class="card-body">
            <div class="mb-2"><label class="form-label small">Nama</label><input type="text" class="form-control form-control-sm" name="penerima_laporan" value="<?= sv('penerima_laporan',$ikp) ?>"></div>
            <div class="mb-2"><label class="form-label small">Paraf</label><input type="text" class="form-control form-control-sm" name="paraf_penerima" value="<?= sv('paraf_penerima',$ikp) ?>"></div>
            <div><label class="form-label small">Tanggal Terima</label><input type="date" class="form-control form-control-sm" name="tgl_terima" value="<?= sv('tgl_terima',$ikp) ?>"></div>
          </div>
        </div>
      </div>
    </div>

    <div class="card border-0 bg-light p-3">
      <p class="fw-bold mb-2">Grading Risiko Kejadian * <small class="text-muted fw-normal">(Diisi oleh atasan pelapor)</small></p>
      <div class="d-flex flex-wrap gap-2">
        <?php foreach(['biru'=>'primary','hijau'=>'success','kuning'=>'warning','merah'=>'danger'] as $g=>$cls): ?>
        <input type="radio" class="btn-check" name="grading" id="g_<?= $g ?>" value="<?= $g ?>" <?= rc('grading',$g,$ikp) ?>>
        <label class="btn btn-outline-<?= $cls ?> grading-btn" for="g_<?= $g ?>"><?= strtoupper($g) ?></label>
        <?php endforeach; ?>
      </div>
    </div>

  </div><!-- card-body -->
  <div class="card-footer text-end">
    <a href="<?= base_url('formulir-ikp/daftar') ?>" class="btn btn-secondary me-2">
      <i class="bi bi-arrow-left"></i> Batal
    </a>
    <button type="submit" class="btn btn-warning">
      <i class="bi bi-save"></i> Update Laporan
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
    initLainInput('ip_lain', 'input_insiden_pada_lain', 'insiden_pada');
    initLainInput('pj_lain', 'input_pasien_jenis_lain', 'pasien_jenis');
    initLainInput('sp_lain', 'input_spesialisasi_lain', 'spesialisasi');
    initCheckboxInput('to_tim', 'input_tim_terdiri');
    initCheckboxInput('to_petugas_lain', 'input_petugas_lainnya');
});
</script>
<?= $this->endSection(); ?>
