<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Jenis Indikator
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Daftar Jenis Indikator</h3>
        <div class="card-tools">
            <a href="<?= base_url('jenis-indikator/create') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Jenis Indikator
            </a>
        </div>
    </div>
    <div class="card-body p-0">
        <?php if (session()->has('message')) : ?>
            <div class="alert alert-success m-3">
                <?= session('message') ?>
            </div>
        <?php endif ?>

        <?php if (session()->has('error')) : ?>
            <div class="alert alert-danger m-3">
                <?= session('error') ?>
            </div>
        <?php endif ?>

        <table class="table table-striped">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Jenis Indikator</th>
                    <th>Deskripsi</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jenis_indikator as $index => $item) : ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($item['jenis_indikator']) ?></td>
                        <td><?= esc($item['deskripsi']) ?></td>
                        <td>
                            <a href="<?= base_url('jenis-indikator/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= base_url('jenis-indikator/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>
