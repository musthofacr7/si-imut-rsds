<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Area Pengukuran
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Daftar Area Pengukuran</h3>
        <div class="card-tools">
            <a href="<?= base_url('area-pengukuran/create') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Area Pengukuran
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
                    <th>Area Pengukuran</th>
                    <th>Status</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($area_pengukuran as $index => $item) : ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($item['area_pengukuran']) ?></td>
                        <td>
                            <?php if ($item['status'] == 'aktif') : ?>
                                <span class="badge text-bg-success">Aktif</span>
                            <?php else : ?>
                                <span class="badge text-bg-secondary">Tidak Aktif</span>
                            <?php endif ?>
                        </td>
                        <td>
                            <a href="<?= base_url('area-pengukuran/edit/' . $item['id']) ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= base_url('area-pengukuran/delete/' . $item['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus?')">
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
