<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit Setting Indikator Mutu
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Edit Setting Indikator Mutu</h3>
            </div>
            <form action="<?= base_url('setting-indikator-mutu/update/' . $setting_indikator['id']) ?>" method="post">
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
                        <label for="indikator_mutu_id" class="col-sm-4 col-form-label">Indikator Mutu</label>
                        <div class="col-sm-8">
                            <select class="form-select select2" id="indikator_mutu_id" name="indikator_mutu_id" required>
                                <option value="">Pilih Indikator Mutu</option>
                                <?php foreach ($indikator_mutu as $indikator) : ?>
                                    <option value="<?= $indikator['id'] ?>" <?= old('indikator_mutu_id', $setting_indikator['indikator_mutu_id']) == $indikator['id'] ? 'selected' : '' ?>><?= esc($indikator['judul_indikator']) ?></option>
                                <?php endforeach ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="area_pengukuran_id" class="col-sm-4 col-form-label">Area Pengukuran</label>
                        <div class="col-sm-8">
                            <?php 
                            $selected_area_ids = array_column($selected_areas, 'area_pengukuran_id');
                            ?>
                            <select class="form-select select2" id="area_pengukuran_id" name="area_pengukuran_id[]" multiple required>
                                <?php foreach ($area_pengukuran as $area) : ?>
                                    <option value="<?= $area['id'] ?>" <?= in_array($area['id'], old('area_pengukuran_id', $selected_area_ids)) ? 'selected' : '' ?>><?= esc($area['area_pengukuran']) ?></option>
                                <?php endforeach ?>
                            </select>
                            <small class="text-muted">Pilih satu atau lebih area pengukuran</small>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('setting-indikator-mutu') ?>" class="btn btn-secondary float-end">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
