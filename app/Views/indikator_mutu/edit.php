<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit Indikator Mutu
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Edit Indikator Mutu</h3>
            </div>
            <form action="<?= base_url('indikator-mutu/update/' . $indikator_mutu['id']) ?>" method="post">
                <div class="card-body">
                    <?php if (session()->has('errors')) : ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                            <?php foreach (session('errors') as $error) : ?>
                                <li><?= $error ?></li>
                            <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif ?>

                    <?= csrf_field() ?>

                    <div class="row mb-3">
                        <label for="judul_indikator" class="col-sm-3 col-form-label">Judul Indikator <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="judul_indikator" name="judul_indikator" value="<?= old('judul_indikator', $indikator_mutu['judul_indikator']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="dimensi_mutu" class="col-sm-3 col-form-label">Dimensi Mutu <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="dimensi_mutu" name="dimensi_mutu" value="<?= old('dimensi_mutu', $indikator_mutu['dimensi_mutu'] ?? '') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="tujuan" class="col-sm-3 col-form-label">Tujuan <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="tujuan" name="tujuan" rows="3" required><?= old('tujuan', $indikator_mutu['tujuan'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="jenis_indikator_id" class="col-sm-3 col-form-label">Jenis Indikator <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <select class="form-select select2" id="jenis_indikator_id" name="jenis_indikator_id" required>
                                <option value="">Pilih Jenis Indikator</option>
                                <?php foreach ($jenis_indikator as $jenis) : ?>
                                    <option value="<?= $jenis['id'] ?>" <?= old('jenis_indikator_id', $indikator_mutu['jenis_indikator_id']) == $jenis['id'] ? 'selected' : '' ?>>
                                        <?= esc($jenis['jenis_indikator']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label class="col-sm-3 col-form-label">Status <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_aktif" value="aktif" <?= old('status', $indikator_mutu['status'] ?? 'aktif') == 'aktif' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_aktif">
                                    Aktif
                                </label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="status" id="status_tidak_aktif" value="tidak aktif" <?= old('status', $indikator_mutu['status'] ?? 'aktif') == 'tidak aktif' ? 'checked' : '' ?>>
                                <label class="form-check-label" for="status_tidak_aktif">
                                    Tidak Aktif
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="definisi_operasional" class="col-sm-3 col-form-label">Definisi Operasional <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="definisi_operasional" name="definisi_operasional" rows="3" required><?= old('definisi_operasional', $indikator_mutu['definisi_operasional']) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="numerator" class="col-sm-3 col-form-label">Numerator <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="numerator" name="numerator" rows="2" required><?= old('numerator', $indikator_mutu['numerator']) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="denumerator" class="col-sm-3 col-form-label">Denumerator <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="denumerator" name="denumerator" rows="2" required><?= old('denumerator', $indikator_mutu['denumerator']) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="target_pencapaian" class="col-sm-3 col-form-label">Target Pencapaian <span class="text-danger">*</span></label>
                        <div class="col-sm-3">
                            <select class="form-select" id="standar_target_pencapaian" name="standar_target_pencapaian">
                                <option value="">Pilih Standar</option>
                                <option value=">" <?= old('standar_target_pencapaian', $indikator_mutu['standar_target_pencapaian'] ?? '') == '>' ? 'selected' : '' ?>>></option>
                                <option value="<" <?= old('standar_target_pencapaian', $indikator_mutu['standar_target_pencapaian'] ?? '') == '<' ? 'selected' : '' ?>><</option>
                                <option value=">=" <?= old('standar_target_pencapaian', $indikator_mutu['standar_target_pencapaian'] ?? '') == '>=' ? 'selected' : '' ?>>>=</option>
                                <option value="<=" <?= old('standar_target_pencapaian', $indikator_mutu['standar_target_pencapaian'] ?? '') == '<=' ? 'selected' : '' ?>><=</option>
                            </select>
                        </div>
                        <div class="col-sm-3">
                            <input type="text" class="form-control" id="target_pencapaian" name="target_pencapaian" value="<?= old('target_pencapaian', $indikator_mutu['target_pencapaian']) ?>" required>
                        </div>
                        <div class="col-sm-3">
                            <select class="form-select" id="satuan_target_pencapaian" name="satuan_target_pencapaian" required>
                                <option value="">Pilih Satuan</option>
                                <?php foreach ($satuan as $s) : ?>
                                    <option value="<?= esc($s['nama_satuan']) ?>" <?= old('satuan_target_pencapaian', $indikator_mutu['satuan_target_pencapaian'] ?? '') == $s['nama_satuan'] ? 'selected' : '' ?>>
                                        <?= esc($s['nama_satuan']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="kriteria_inklusi" class="col-sm-3 col-form-label">Kriteria Inklusi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="kriteria_inklusi" name="kriteria_inklusi" rows="2"><?= old('kriteria_inklusi', $indikator_mutu['kriteria_inklusi']) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="kriteria_eksklusi" class="col-sm-3 col-form-label">Kriteria Eksklusi</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="kriteria_eksklusi" name="kriteria_eksklusi" rows="2"><?= old('kriteria_eksklusi', $indikator_mutu['kriteria_eksklusi']) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="sumber_data" class="col-sm-3 col-form-label">Sumber Data <span class="text-danger">*</span></label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="sumber_data" name="sumber_data" value="<?= old('sumber_data', $indikator_mutu['sumber_data']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="frekuensi_pengumpulan_data" class="col-sm-3 col-form-label">Frekuensi Pengumpulan Data</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="frekuensi_pengumpulan_data" name="frekuensi_pengumpulan_data" value="<?= old('frekuensi_pengumpulan_data', $indikator_mutu['frekuensi_pengumpulan_data']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="periode_analisis_data" class="col-sm-3 col-form-label">Periode Analisis Data</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="periode_analisis_data" name="periode_analisis_data" value="<?= old('periode_analisis_data', $indikator_mutu['periode_analisis_data']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="rencana_analisis" class="col-sm-3 col-form-label">Rencana Analisis</label>
                        <div class="col-sm-9">
                            <textarea class="form-control" id="rencana_analisis" name="rencana_analisis" rows="3"><?= old('rencana_analisis', $indikator_mutu['rencana_analisis']) ?></textarea>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="instrumen_pengambilan_data" class="col-sm-3 col-form-label">Instrumen Pengambilan Data</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="instrumen_pengambilan_data" name="instrumen_pengambilan_data" value="<?= old('instrumen_pengambilan_data', $indikator_mutu['instrumen_pengambilan_data']) ?>">
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="area_pengukuran" class="col-sm-3 col-form-label">Area Pengukuran</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="area_pengukuran" name="area_pengukuran" value="<?= old('area_pengukuran', $indikator_mutu['area_pengukuran']) ?>">
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('indikator-mutu') ?>" class="btn btn-secondary float-end">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Wait for all scripts to load
        setTimeout(function() {
            // Check if Summernote is available
            if (typeof $.fn.summernote === 'undefined') {
                console.error('Summernote is not loaded!');
                return;
            }
            
            // Destroy existing instances if any
            $('.summernote-editor').summernote('destroy');
            
            // Initialize Summernote on textarea fields
            $('#definisi_operasional').summernote({
                height: 150,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });

            $('#tujuan').summernote({
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
            
            $('#numerator').summernote({
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
            
            $('#denumerator').summernote({
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
            
            $('#kriteria_inklusi').summernote({
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
            
            $('#kriteria_eksklusi').summernote({
                height: 120,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
            
            $('#rencana_analisis').summernote({
                height: 150,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link']],
                    ['view', ['fullscreen', 'codeview']]
                ]
            });
        }, 100); // Small delay to ensure all scripts are loaded
    });
</script>
<?= $this->endSection() ?>
