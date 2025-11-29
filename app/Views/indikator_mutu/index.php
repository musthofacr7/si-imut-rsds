<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Indikator Mutu
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Daftar Indikator Mutu</h3>
        <div class="card-tools">
            <a href="<?= base_url('indikator-mutu/create') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Indikator Mutu
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

        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th style="width: 10px">#</th>
                        <th>Judul Indikator</th>
                        <th>Jenis Indikator</th>
                        <th>Target Pencapaian</th>
                        <th style="width: 100px">Status</th>
                        <th style="width: 150px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($indikator_mutu as $index => $item) : ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($item['judul_indikator']) ?></td>
                            <td><?= esc($item['jenis_indikator']) ?></td>
                            <td><?= esc($item['target_pencapaian']) ?> <?= esc($item['satuan_target_pencapaian'] ?? '%') ?></td>
                            <td>
                                <?php if ($item['status'] == 'aktif') : ?>
                                    <span class="badge bg-success">Aktif</span>
                                <?php else : ?>
                                    <span class="badge bg-danger">Tidak Aktif</span>
                                <?php endif ?>
                            </td>
                            <td>
                                <a href="<?= base_url('indikator-mutu/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="<?= base_url('indikator-mutu/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
                                    <i class="bi bi-trash"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
