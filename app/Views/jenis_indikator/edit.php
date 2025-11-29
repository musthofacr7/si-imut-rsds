<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Edit Jenis Indikator
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Edit Jenis Indikator</h3>
            </div>
            <form action="<?= base_url('jenis-indikator/update/' . $jenis_indikator['id']) ?>" method="post">
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
                        <label for="jenis_indikator" class="col-sm-3 col-form-label">Jenis Indikator</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="jenis_indikator" name="jenis_indikator" value="<?= old('jenis_indikator', $jenis_indikator['jenis_indikator']) ?>" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <label for="deskripsi" class="col-sm-3 col-form-label">Deskripsi</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control" id="deskripsi" name="deskripsi" value="<?= old('deskripsi', $jenis_indikator['deskripsi']) ?>" required>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <a href="<?= base_url('jenis-indikator') ?>" class="btn btn-secondary float-end">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
