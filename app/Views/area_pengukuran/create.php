<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Tambah Area Pengukuran
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Tambah Area Pengukuran</h3>
            </div>
            <form action="<?= base_url('area-pengukuran/store') ?>" method="post">
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
                        <label for="area_pengukuran" class="col-sm-3 col-form-label">Area Pengukuran</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="area_pengukuran" name="area_pengukuran" value="<?= old('area_pengukuran') ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="status" class="col-sm-3 col-form-label">Status</label>
                        <div class="col-sm-9">
                            <select class="form-select select2" id="status" name="status" required>
                                <option value="aktif" <?= old('status') == 'aktif' ? 'selected' : '' ?>>Aktif</option>
                                <option value="tidak aktif" <?= old('status') == 'tidak aktif' ? 'selected' : '' ?>>Tidak Aktif</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('area-pengukuran') ?>" class="btn btn-secondary float-end">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
