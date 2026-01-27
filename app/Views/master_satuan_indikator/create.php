<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
<?= $title ?>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
            </div>
            <form action="<?= base_url('master-satuan-indikator/store') ?>" method="post">
                <div class="card-body">
                    <?= csrf_field() ?>
                    
                    <div class="form-group mb-3">
                        <label for="nama_satuan">Nama Satuan <span class="text-danger">*</span></label>
                        <input type="text" class="form-control <?= ($validation->hasError('nama_satuan')) ? 'is-invalid' : '' ?>" id="nama_satuan" name="nama_satuan" value="<?= old('nama_satuan') ?>" placeholder="Contoh: %, Menit, ‰">
                        <div class="invalid-feedback">
                            <?= $validation->getError('nama_satuan') ?>
                        </div>
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan</button>
                    <a href="<?= base_url('master-satuan-indikator') ?>" class="btn btn-secondary float-end">Batal</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
