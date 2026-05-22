<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
            </div>
            <form action="<?= base_url('risk-register/store') ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>
                <div class="card-body">
                    <?php if (session()->getFlashdata('errors')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <ul>
                                <?php foreach (session()->getFlashdata('errors') as $error) : ?>
                                    <li><?= esc($error) ?></li>
                                <?php endforeach ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="file_risk_register" class="form-label">File Risk Register (PDF, DOC, DOCX, XLS, XLSX)</label>
                        <input type="file" class="form-control" id="file_risk_register" name="file_risk_register" accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= old('deskripsi') ?></textarea>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="<?= base_url('risk-register') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
