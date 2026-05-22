<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Daftar Laporan KPC<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?= base_url('laporan-kpc/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Laporan KPC
    </a>
</div>

<?php if (session()->getFlashdata('message')): ?>
<div class="alert alert-success alert-dismissible fade show" role="alert">
    <i class="bi bi-check-circle me-1"></i> <?= session()->getFlashdata('message') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<?php if (session()->getFlashdata('error')): ?>
<div class="alert alert-danger alert-dismissible fade show" role="alert">
    <i class="bi bi-exclamation-circle me-1"></i> <?= session()->getFlashdata('error') ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
</div>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-header bg-white py-3">
        <div class="d-flex align-items-center gap-2">
            <i class="bi bi-file-earmark-medical-fill text-primary fs-5"></i>
            <h6 class="mb-0 fw-semibold">Daftar Laporan KPC</h6>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="kpcTable" class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="px-3" style="width:50px">No</th>
                        <th>Ruangan Asal</th>
                        <th>Tgl KPC</th>
                        <th>Insiden (KPC)</th>
                        <th>Pelapor</th>
                        <th>Lokasi</th>
                        <th class="text-center" style="min-width:180px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($kpcList as $kpc): ?>
                    <tr>
                        <td class="px-3"><?= $no++ ?></td>
                        <td>
                            <div class="fw-semibold"><?= esc($kpc['area_pengukuran'] ?? '-') ?></div>
                        </td>
                        <td>
                            <?php if ($kpc['tgl_kpc']): ?>
                                <?= date('d/m/Y', strtotime($kpc['tgl_kpc'])) ?>
                                <?php if ($kpc['jam_kpc']): ?>
                                    <br><small class="text-muted"><?= substr($kpc['jam_kpc'], 0, 5) ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($kpc['insiden'] ?? '-') ?></td>
                        <td>
                            <?php 
                            if ($kpc['pelapor'] == 'karyawan') echo 'Karyawan';
                            elseif ($kpc['pelapor'] == 'pasien') echo 'Pasien';
                            elseif ($kpc['pelapor'] == 'keluarga') echo 'Keluarga';
                            elseif ($kpc['pelapor'] == 'pengunjung') echo 'Pengunjung';
                            elseif ($kpc['pelapor'] == 'lain') echo 'Lainnya: ' . esc($kpc['pelapor_lain']);
                            else echo '-';
                            ?>
                        </td>
                        <td><?= esc($kpc['lokasi'] ?? '-') ?></td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <a href="<?= base_url('laporan-kpc/view/' . $kpc['id']) ?>"
                                   class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="<?= base_url('laporan-kpc/edit/' . $kpc['id']) ?>"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-hapus"
                                        data-id="<?= $kpc['id'] ?>"
                                        title="Hapus">
                                    <i class="bi bi-trash"></i> Hapus
                                </button>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php /* ===== Modal Konfirmasi Hapus ===== */ ?>
<div class="modal fade" id="modalHapus" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-danger">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Hapus
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menghapus laporan KPC ini?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Batal
                </button>
                <a href="#" id="link-hapus" class="btn btn-danger">
                    <i class="bi bi-trash"></i> Ya, Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // DataTables
    $('#kpcTable').DataTable({
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
            emptyTable: 'Belum ada data laporan KPC',
            zeroRecords: 'Data tidak ditemukan',
        },
        pageLength: 10,
        order: [[0, 'asc']],
    });

    // Modal Hapus
    const modalHapus = new bootstrap.Modal(document.getElementById('modalHapus'));
    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            document.getElementById('link-hapus').href = '<?= base_url('laporan-kpc/delete/') ?>' + id;
            modalHapus.show();
        });
    });
});
</script>
<?= $this->endSection(); ?>
