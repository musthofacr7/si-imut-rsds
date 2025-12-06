<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Detail Indikator Mutu
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-info card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Detail Indikator Mutu</h3>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Judul Indikator</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['judul_indikator']) ?>" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Dimensi Mutu</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['dimensi_mutu'] ?? '') ?>" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Tujuan</label>
                    <div class="col-sm-9">
                        <div class="border p-2 rounded bg-light">
                            <?= $indikator_mutu['tujuan'] ?? '' ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Jenis Indikator</label>
                    <div class="col-sm-9">
                        <select class="form-select" disabled>
                            <option value="">Pilih Jenis Indikator</option>
                            <?php foreach ($jenis_indikator as $jenis) : ?>
                                <option value="<?= $jenis['id'] ?>" <?= $indikator_mutu['jenis_indikator_id'] == $jenis['id'] ? 'selected' : '' ?>>
                                    <?= esc($jenis['jenis_indikator']) ?>
                                </option>
                            <?php endforeach ?>
                        </select>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Status</label>
                    <div class="col-sm-9">
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="aktif" <?= ($indikator_mutu['status'] ?? 'aktif') == 'aktif' ? 'checked' : '' ?> disabled>
                            <label class="form-check-label">Aktif</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" value="tidak aktif" <?= ($indikator_mutu['status'] ?? 'aktif') == 'tidak aktif' ? 'checked' : '' ?> disabled>
                            <label class="form-check-label">Tidak Aktif</label>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Definisi Operasional</label>
                    <div class="col-sm-9">
                        <div class="border p-2 rounded bg-light">
                            <?= $indikator_mutu['definisi_operasional'] ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Numerator</label>
                    <div class="col-sm-9">
                        <div class="border p-2 rounded bg-light">
                            <?= $indikator_mutu['numerator'] ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Denumerator</label>
                    <div class="col-sm-9">
                        <div class="border p-2 rounded bg-light">
                            <?= $indikator_mutu['denumerator'] ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Target Pencapaian</label>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['standar_target_pencapaian'] ?? '') ?>" disabled>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['target_pencapaian']) ?>" disabled>
                    </div>
                    <div class="col-sm-3">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['satuan_target_pencapaian'] ?? '%') ?>" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Kriteria Inklusi</label>
                    <div class="col-sm-9">
                        <div class="border p-2 rounded bg-light">
                            <?= $indikator_mutu['kriteria_inklusi'] ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Kriteria Eksklusi</label>
                    <div class="col-sm-9">
                        <div class="border p-2 rounded bg-light">
                            <?= $indikator_mutu['kriteria_eksklusi'] ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Sumber Data</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['sumber_data']) ?>" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Frekuensi Pengumpulan Data</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['frekuensi_pengumpulan_data']) ?>" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Periode Analisis Data</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['periode_analisis_data']) ?>" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Rencana Analisis</label>
                    <div class="col-sm-9">
                        <div class="border p-2 rounded bg-light">
                            <?= $indikator_mutu['rencana_analisis'] ?>
                        </div>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Instrumen Pengambilan Data</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['instrumen_pengambilan_data']) ?>" disabled>
                    </div>
                </div>

                <div class="row mb-3">
                    <label class="col-sm-3 col-form-label">Area Pengukuran</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" value="<?= esc($indikator_mutu['area_pengukuran']) ?>" disabled>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <a href="<?= base_url('indikator-mutu') ?>" class="btn btn-secondary">Kembali</a>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
