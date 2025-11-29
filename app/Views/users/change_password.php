<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Ubah Password
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-6">
        <div class="card card-primary card-outline mb-4">
            <div class="card-header">
                <h3 class="card-title">Ubah Password</h3>
            </div>
            <form action="<?= base_url('profile/change-password') ?>" method="post">
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

                    <?php if (session()->has('message')) : ?>
                        <div class="alert alert-success">
                            <?= session('message') ?>
                        </div>
                    <?php endif ?>

                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password Baru</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>

                    <div class="mb-3">
                        <label for="pass_confirm" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" class="form-control" id="pass_confirm" name="pass_confirm" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Simpan Password</button>
                    <a href="<?= base_url() ?>" class="btn btn-secondary float-end">Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
