<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Tambah Laporan IKP<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<style>
    .ikp-header-box {
        background: #f8f9fa;
        border: 2px solid #dee2e6;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .section-badge {
        background: #0d6efd;
        color: #fff;
        font-weight: 700;
        font-size: 13px;
        border-radius: 4px;
        padding: 3px 10px;
        display: inline-block;
        margin-bottom: 10px;
    }
    .form-check { margin-bottom: 4px; }
    .grading-btn {
        min-width: 90px;
        font-weight: 700;
        letter-spacing: 1px;
    }
    @media print {
        .no-print { display: none !important; }
        .card { box-shadow: none !important; border: 1px solid #ccc !important; }
    }
</style>

<div class="mb-3 d-flex justify-content-between align-items-center">
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

<form action="<?= base_url('formulir-ikp/store') ?>" method="post">

<div class="card shadow-sm">
    <div class="card-body">

        <?php /* ===== HEADER ===== */ ?>
        <div class="ikp-header-box text-center">
            <h5 class="fw-bold mb-1">Formulir Laporan Insiden ke Tim KP di RS</h5>
            <p class="mb-1 text-muted small">Rumah Sakit Umum Dr Soedirman Kebumen</p>
            <div class="alert alert-warning py-1 px-2 mb-0 small fw-bold">
                RAHASIA, TIDAK BOLEH DIFOTOCOPY, DILAPORKAN MAXIMAL 2 x 24 JAM
            </div>
        </div>

        <h5 class="text-center fw-bold mb-0">LAPORAN INSIDEN</h5>
        <p class="text-center text-muted mb-4">(INTERNAL)</p>

        <?php /* ===== I. DATA PASIEN ===== */ ?>
        <div class="section-badge">I. DATA PASIEN</div>

        <div class="row g-3 mb-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Nama Pasien</label>
                <input type="text" class="form-control" name="nama_pasien" placeholder="Nama lengkap pasien">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">No MR</label>
                <input type="text" class="form-control" name="no_mr" placeholder="No. Rekam Medis">
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Ruangan Asal *</label>
                <select class="form-select" name="id_area_pengukuran" required>
                    <option value="">Pilih Area</option>
                    <?php foreach ($area_pengukuran as $area) : ?>
                        <option value="<?= $area['id'] ?>"><?= $area['area_pengukuran'] ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Detail Ruangan</label>
                <input type="text" class="form-control" name="ruangan" placeholder="Nama ruangan">
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-12">
                <label class="form-label fw-semibold">Umur *</label>
                <div class="row row-cols-2 row-cols-md-4 g-2 ms-1">
                    <?php $umurOptions = ['0-1_bln'=>'0-1 bulan','>1bln-1thn'=>'> 1 bulan – 1 tahun','>1-5thn'=>'> 1 tahun – 5 tahun','>5-15thn'=>'> 5 tahun – 15 tahun','>15-30thn'=>'> 15 tahun – 30 tahun','>30-65thn'=>'> 30 tahun – 65 tahun','>65thn'=>'> 65 tahun']; ?>
                    <?php foreach($umurOptions as $val => $label): ?>
                    <div class="col">
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="umur" value="<?= $val ?>" id="umur_<?= $val ?>">
                            <label class="form-check-label" for="umur_<?= $val ?>"><?= $label ?></label>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jenis Kelamin *</label>
                <div class="d-flex gap-4 ms-1">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="L" id="jk_l">
                        <label class="form-check-label" for="jk_l">Laki-laki</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="jenis_kelamin" value="P" id="jk_p">
                        <label class="form-check-label" for="jk_p">Perempuan</label>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tanggal Masuk RS</label>
                <input type="date" class="form-control" name="tgl_masuk">
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Jam Masuk</label>
                <input type="time" class="form-control" name="jam_masuk">
            </div>
        </div>

        <div class="mb-4">
            <label class="form-label fw-semibold">Penanggung Biaya Pasien</label>
            <div class="row row-cols-2 row-cols-md-3 g-2 ms-1">
                <?php $biayaOpts = ['pribadi'=>'Pribadi','asuransi_swasta'=>'Asuransi Swasta','askes'=>'ASKES Pemerintah','perusahaan'=>'Perusahaan*','jamkesmas'=>'JAMKESMAS','jamkesda'=>'JAMKESDA']; ?>
                <?php foreach($biayaOpts as $val => $label): ?>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="biaya" value="<?= $val ?>" id="biaya_<?= $val ?>">
                        <label class="form-check-label" for="biaya_<?= $val ?>"><?= $label ?></label>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <hr>

        <?php /* ===== II. RINCIAN KEJADIAN ===== */ ?>
        <div class="section-badge">II. RINCIAN KEJADIAN</div>

        <?php /* 1. Tanggal dan Waktu Insiden */ ?>
        <p class="fw-semibold mb-2">1. Tanggal dan Waktu Insiden</p>
        <div class="row g-3 mb-3 ms-1">
            <div class="col-md-4">
                <label class="form-label">Tanggal</label>
                <input type="date" class="form-control" name="tgl_insiden">
            </div>
            <div class="col-md-3">
                <label class="form-label">Jam</label>
                <input type="time" class="form-control" name="jam_insiden">
            </div>
        </div>

        <?php /* 2. Insiden */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">2. Insiden</label>
            <input type="text" class="form-control" name="insiden" placeholder="Deskripsi singkat insiden">
        </div>

        <?php /* 3. Kronologis */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">3. Kronologis Insiden</label>
            <textarea class="form-control" name="kronologis" rows="4" placeholder="Uraikan kronologis kejadian..."></textarea>
        </div>

        <?php /* 4. Jenis Insiden */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">4. Jenis Insiden *</label>
            <div class="ms-1">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis_insiden" value="KNC" id="ji_knc">
                    <label class="form-check-label" for="ji_knc">Kejadian Nyaris Cedera / KNC <em>(Near miss)</em></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis_insiden" value="KTC" id="ji_ktc">
                    <label class="form-check-label" for="ji_ktc">Kejadian Tidak cedera / KTC <em>(No Harm)</em></label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="jenis_insiden" value="KTD" id="ji_ktd">
                    <label class="form-check-label" for="ji_ktd">Kejadian Tidak diharapkan / KTD <em>(Adverse Event)</em> / Kejadian Sentinel <em>(Sentinel Event)</em></label>
                </div>
            </div>
        </div>

        <?php /* 5. Orang Pertama Melapor */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">5. Orang Pertama Yang Melaporkan Insiden *</label>
            <div class="ms-1">
                <?php $pelaporOpts = ['karyawan'=>'Karyawan : Dokter / Perawat / Petugas lainnya','pasien'=>'Pasien','keluarga'=>'Keluarga / Pendamping pasien','pengunjung'=>'Pengunjung']; ?>
                <?php foreach($pelaporOpts as $val => $label): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pelapor" value="<?= $val ?>" id="pl_<?= $val ?>">
                    <label class="form-check-label" for="pl_<?= $val ?>"><?= $label ?></label>
                </div>
                <?php endforeach; ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pelapor" value="lain" id="pl_lain">
                    <label class="form-check-label" for="pl_lain">Lain-lain</label>
                </div>
                <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_pelapor_lain" name="pelapor_lain" placeholder="Sebutkan..." disabled>
            </div>
        </div>

        <?php /* 6. Insiden Terjadi Pada */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">6. Insiden terjadi pada *</label>
            <div class="ms-1">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="insiden_pada" value="pasien" id="ip_pasien">
                    <label class="form-check-label" for="ip_pasien">Pasien</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="insiden_pada" value="lain" id="ip_lain">
                    <label class="form-check-label" for="ip_lain">Lain-lain</label>
                </div>
                <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_insiden_pada_lain" name="insiden_pada_lain" placeholder="Sebutkan..." disabled>
                <small class="text-muted ms-1">Mis : karyawan / Pengunjung / Pendamping / Keluarga pasien, lapor ke K3 RS.</small>
            </div>
        </div>

        <?php /* 7. Insiden Menyangkut Pasien */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">7. Insiden menyangkut pasien</label>
            <div class="ms-1">
                <?php $pasienOpts = ['rawat_inap'=>'Pasien rawat inap','rawat_jalan'=>'Pasien rawat jalan','ugd'=>'Pasien UGD']; ?>
                <?php foreach($pasienOpts as $val => $label): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pasien_jenis" value="<?= $val ?>" id="pj_<?= $val ?>">
                    <label class="form-check-label" for="pj_<?= $val ?>"><?= $label ?></label>
                </div>
                <?php endforeach; ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pasien_jenis" value="lain" id="pj_lain">
                    <label class="form-check-label" for="pj_lain">Lain-lain</label>
                </div>
                <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_pasien_jenis_lain" name="pasien_jenis_lain" placeholder="Sebutkan..." disabled>
            </div>
        </div>

        <?php /* 8. Tempat Insiden */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">8. Tempat Insiden</label>
            <div class="input-group ms-1">
                <span class="input-group-text">Lokasi kejadian</span>
                <input type="text" class="form-control" name="lokasi" placeholder="Sebutkan lokasi (tempat pasien berada)">
            </div>
        </div>

        <?php /* 9. Spesialisasi Pasien */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">9. Insiden terjadi pada pasien (sesuai kasus penyakit / spesialisasi)</label>
            <div class="row row-cols-1 row-cols-md-2 g-1 ms-1">
                <?php $spesialisasiOpts = [
                    'penyakit_dalam' => 'Penyakit Dalam dan Subspesialisasinya',
                    'anak'           => 'Anak dan Subspesialisasinya',
                    'bedah'          => 'Bedah dan Subspesialisasinya',
                    'obgyn'          => 'Obstetri Gynekologi dan Subspesialisasinya',
                    'tht'            => 'THT dan Subspesialisasinya',
                    'mata'           => 'Mata dan Subspesialisasinya',
                    'saraf'          => 'Saraf dan Subspesialisasinya',
                    'anastesi'       => 'Anastesi dan Subspesialisasinya',
                    'kulit'          => 'Kulit & Kelamin dan Subspesialisasinya',
                    'jantung'        => 'Jantung dan Subspesialisasinya',
                    'paru'           => 'Paru dan Subspesialisasinya',
                    'jiwa'           => 'Jiwa dan Subspesialisasinya',
                ]; ?>
                <?php foreach($spesialisasiOpts as $val => $label): ?>
                <div class="col">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="spesialisasi" value="<?= $val ?>" id="sp_<?= $val ?>">
                        <label class="form-check-label" for="sp_<?= $val ?>"><?= $label ?></label>
                    </div>
                </div>
                <?php endforeach; ?>
                <div class="col-12">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="spesialisasi" value="lain" id="sp_lain">
                        <label class="form-check-label" for="sp_lain">Lain-lain</label>
                    </div>
                    <input type="text" class="form-control form-control-sm mt-1 ms-4 bg-light" id="input_spesialisasi_lain" name="spesialisasi_lain" placeholder="Sebutkan..." disabled>
                </div>
            </div>
        </div>


        <?php /* 10. Unit/Departemen */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">10. Unit / Departemen terkait yang menyebabkan insiden</label>
            <div class="input-group ms-1">
                <span class="input-group-text">Unit kerja penyebab</span>
                <input type="text" class="form-control" name="unit_penyebab" placeholder="Sebutkan...">
            </div>
        </div>

        <?php /* 11. Akibat Insiden */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">11. Akibat Insiden Terhadap Pasien *</label>
            <div class="ms-1">
                <?php $akibatOpts = ['kematian'=>'Kematian','cedera_berat'=>'Cedera Irreversibel / Cedera Berat','cedera_sedang'=>'Cedera Reversibel / Cedera Sedang','cedera_ringan'=>'Cedera Ringan','tidak_cedera'=>'Tidak ada cedera']; ?>
                <?php foreach($akibatOpts as $val => $label): ?>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="akibat" value="<?= $val ?>" id="ak_<?= $val ?>">
                    <label class="form-check-label" for="ak_<?= $val ?>"><?= $label ?></label>
                </div>
                <?php endforeach; ?>
            </div>
        </div>

        <?php /* 12. Tindakan Segera */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">12. Tindakan yang dilakukan segera setelah kejadian, dan hasilnya</label>
            <textarea class="form-control" name="tindakan_segera" rows="3" placeholder="Uraikan tindakan dan hasilnya..."></textarea>
        </div>

        <?php /* 13. Tindakan Dilakukan Oleh */ ?>
        <div class="mb-3">
            <label class="form-label fw-semibold">13. Tindakan dilakukan oleh *</label>
            <small class="text-muted d-block mb-2 ms-1"><i class="bi bi-info-circle"></i> Dapat memilih lebih dari satu</small>
            <div class="ms-1">
                <div class="row align-items-center mb-2">
                    <div class="col-auto">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="tim" id="to_tim">
                            <label class="form-check-label" for="to_tim">Tim, terdiri dari :</label>
                        </div>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control form-control-sm bg-light" id="input_tim_terdiri" name="tim_terdiri" placeholder="Sebutkan..." disabled>
                    </div>
                </div>
                <?php $olehOpts = ['dokter' => 'Dokter', 'perawat' => 'Perawat']; ?>
                <?php foreach($olehOpts as $val => $label): ?>
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="<?= $val ?>" id="to_<?= $val ?>">
                    <label class="form-check-label" for="to_<?= $val ?>"><?= $label ?></label>
                </div>
                <?php endforeach; ?>
                <div class="row align-items-center mt-1">
                    <div class="col-auto">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="tindakan_oleh[]" value="petugas_lain" id="to_petugas_lain">
                            <label class="form-check-label" for="to_petugas_lain">Petugas lainnya</label>
                        </div>
                    </div>
                    <div class="col">
                        <input type="text" class="form-control form-control-sm bg-light" id="input_petugas_lainnya" name="petugas_lainnya" placeholder="Sebutkan..." disabled>
                    </div>
                </div>
            </div>
        </div>


        <?php /* 14. Pernah Terjadi Sebelumnya */ ?>
        <div class="mb-4">
            <label class="form-label fw-semibold">14. Apakah kejadian yang sama pernah terjadi di Unit Kerja lain? *</label>
            <div class="d-flex gap-4 ms-1 mb-2">
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pernah_terjadi" value="ya" id="pt_ya">
                    <label class="form-check-label" for="pt_ya">Ya</label>
                </div>
                <div class="form-check">
                    <input class="form-check-input" type="radio" name="pernah_terjadi" value="tidak" id="pt_tidak">
                    <label class="form-check-label" for="pt_tidak">Tidak</label>
                </div>
            </div>
            <label class="form-label small">Apabila ya – <strong>Kapan? dan Langkah/tindakan apa yang telah diambil untuk mencegah terulangnya kejadian yang sama?</strong></label>
            <textarea class="form-control" name="langkah_pencegahan" rows="3" placeholder="Uraikan langkah pencegahan..."></textarea>
        </div>

        <hr>

        <?php /* ===== TANDA TANGAN ===== */ ?>
        <div class="row g-3 mb-4">
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header bg-light fw-bold text-center py-2">Pembuat Laporan</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small">Nama</label>
                            <input type="text" class="form-control form-control-sm" name="pembuat_laporan">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Paraf</label>
                            <input type="text" class="form-control form-control-sm" name="paraf_pembuat">
                        </div>
                        <div>
                            <label class="form-label small">Tanggal Lapor</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_lapor">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card border">
                    <div class="card-header bg-light fw-bold text-center py-2">Penerima Laporan</div>
                    <div class="card-body">
                        <div class="mb-2">
                            <label class="form-label small">Nama</label>
                            <input type="text" class="form-control form-control-sm" name="penerima_laporan">
                        </div>
                        <div class="mb-2">
                            <label class="form-label small">Paraf</label>
                            <input type="text" class="form-control form-control-sm" name="paraf_penerima">
                        </div>
                        <div>
                            <label class="form-label small">Tanggal Terima</label>
                            <input type="date" class="form-control form-control-sm" name="tgl_terima">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php /* ===== GRADING RISIKO ===== */ ?>
        <div class="card border-0 bg-light p-3">
            <p class="fw-bold mb-2">Grading Risiko Kejadian * <small class="text-muted fw-normal">(Diisi oleh atasan pelapor)</small></p>
            <div class="d-flex flex-wrap gap-2">
                <input type="radio" class="btn-check" name="grading" id="g_biru" value="biru">
                <label class="btn btn-outline-primary grading-btn" for="g_biru">BIRU</label>

                <input type="radio" class="btn-check" name="grading" id="g_hijau" value="hijau">
                <label class="btn btn-outline-success grading-btn" for="g_hijau">HIJAU</label>

                <input type="radio" class="btn-check" name="grading" id="g_kuning" value="kuning">
                <label class="btn btn-outline-warning grading-btn" for="g_kuning">KUNING</label>

                <input type="radio" class="btn-check" name="grading" id="g_merah" value="merah">
                <label class="btn btn-outline-danger grading-btn" for="g_merah">MERAH</label>
            </div>
            <small class="text-muted mt-2">NB. * = pilih satu jawaban</small>
        </div>

    </div><!-- card-body -->
    <div class="card-footer text-end">
        <a href="<?= base_url('formulir-ikp/daftar') ?>" class="btn btn-secondary me-2">
            <i class="bi bi-arrow-left"></i> Batal
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
/**
 * Hubungkan radio "Lain-lain" dengan input teks pasangannya.
 * @param {string} radioId   - id radio button "Lain-lain"
 * @param {string} inputId   - id text input yang dikontrol
 * @param {string} groupName - name atribut dari kelompok radio
 */
function initLainInput(radioId, inputId, groupName) {
    const radioLain = document.getElementById(radioId);
    const input     = document.getElementById(inputId);
    if (!radioLain || !input) return;

    // Semua radio dalam group yang sama
    const allRadios = document.querySelectorAll(`input[type="radio"][name="${groupName}"]`);

    function syncState() {
        const isLainSelected = radioLain.checked;
        input.disabled = !isLainSelected;
        if (isLainSelected) {
            input.classList.remove('bg-light');
            input.focus();
        } else {
            input.classList.add('bg-light');
            input.value = '';   // bersihkan nilai saat non-aktif
        }
    }

    allRadios.forEach(radio => radio.addEventListener('change', syncState));

    // Jalankan saat halaman dimuat (jaga state jika browser auto-fill)
    syncState();
}

/**
 * Hubungkan checkbox dengan input teks pasangannya.
 * @param {string} checkboxId - id elemen checkbox
 * @param {string} inputId    - id text input yang dikontrol
 */
function initCheckboxInput(checkboxId, inputId) {
    const checkbox = document.getElementById(checkboxId);
    const input    = document.getElementById(inputId);
    if (!checkbox || !input) return;

    function syncState() {
        input.disabled = !checkbox.checked;
        if (checkbox.checked) {
            input.classList.remove('bg-light');
            input.focus();
        } else {
            input.classList.add('bg-light');
            input.value = '';
        }
    }

    checkbox.addEventListener('change', syncState);
    syncState();
}

document.addEventListener('DOMContentLoaded', () => {
    initLainInput('pl_lain', 'input_pelapor_lain',       'pelapor');
    initLainInput('ip_lain', 'input_insiden_pada_lain',  'insiden_pada');
    initLainInput('pj_lain', 'input_pasien_jenis_lain',  'pasien_jenis');
    initLainInput('sp_lain', 'input_spesialisasi_lain',  'spesialisasi');

    initCheckboxInput('to_tim',          'input_tim_terdiri');
    initCheckboxInput('to_petugas_lain', 'input_petugas_lainnya');
});
</script>
<?= $this->endSection(); ?>
