<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title"><?= $title ?></h3>
                <?php if (in_groups('pj-mutu')) : ?>
                <a href="<?= base_url('risk-register/create') ?>" class="btn btn-primary btn-sm ml-auto">
                    <i class="bi bi-plus-lg"></i> Tambah Risk Register
                </a>
                <?php endif; ?>
            </div>
            <div class="card-body">
                <?php if (session()->getFlashdata('message')) : ?>
                    <div class="alert alert-success" role="alert">
                        <?= session()->getFlashdata('message') ?>
                    </div>
                <?php endif; ?>
                <?php if (session()->getFlashdata('error')) : ?>
                    <div class="alert alert-danger" role="alert">
                        <?= session()->getFlashdata('error') ?>
                    </div>
                <?php endif; ?>

                <div class="table-responsive">
                    <table id="riskRegisterTable" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>User</th>
                                <th>File Risk Register</th>
                                <th>Deskripsi</th>
                                <th>Keterangan Revisi</th>
                                <th>Status Validasi</th>
                                <th>Tanggal Validasi</th>
                                <th>Komentar Admin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($risk_register as $key => $item) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= esc($item['username']) ?></td>
                                    <td>
                                        <a href="<?= base_url('uploads/risk_register/' . $item['file_risk_register']) ?>" target="_blank" class="btn btn-sm btn-info">
                                            <i class="bi bi-download"></i> Download
                                        </a>
                                    </td>
                                    <td><?= esc($item['deskripsi']) ?></td>
                                    <td><?= !empty($item['keterangan']) ? esc($item['keterangan']) : '-' ?></td>
                                    <td>
                                        <?php if ($item['validasi'] == 1) : ?>
                                            <span class="badge bg-success">Diterima</span>
                                        <?php elseif ($item['validasi'] == 0 && !empty($item['komentar_admin'])) : ?>
                                            <span class="badge bg-danger">Ditolak</span>
                                        <?php else : ?>
                                            <span class="badge bg-warning">Menunggu Validasi</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $item['tgl_validasi'] ? esc($item['tgl_validasi']) : '-' ?></td>
                                    <td><?= esc($item['komentar_admin']) ?></td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="<?= base_url('risk-register/view/' . $item['id']) ?>" class="btn btn-info btn-sm" title="Lihat Detail">
                                                <i class="bi bi-eye"></i>
                                            </a>
                                            <?php if (in_groups('administrator')) : ?>
                                                <a href="<?= base_url('risk-register/edit/' . $item['id']) ?>" class="btn btn-warning btn-sm" title="Validasi">
                                                    <i class="bi bi-check-circle"></i> Validasi
                                                </a>
                                                <a href="<?= base_url('risk-register/delete/' . $item['id']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus data ini?')" title="Hapus">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if (in_groups('pj-mutu') && user_id() == $item['user_id'] && $item['validasi'] != 1) : ?>
                                                <a href="<?= base_url('risk-register/edit/' . $item['id']) ?>" class="btn btn-primary btn-sm" title="Edit / Re-upload">
                                                    <i class="bi bi-pencil"></i> Edit
                                                </a>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    $('#riskRegisterTable').DataTable({
        responsive: true,
        language: {
            search: 'Cari:',
            lengthMenu: 'Tampilkan _MENU_ data',
            info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
            paginate: {
                first: '«',
                last: '»',
                next: '›',
                previous: '‹',
            },
            emptyTable: 'Belum ada data Risk Register',
            zeroRecords: 'Data tidak ditemukan',
        },
        pageLength: 10,
        order: [[0, 'asc']],
    });
});
</script>
<?= $this->endSection() ?>
