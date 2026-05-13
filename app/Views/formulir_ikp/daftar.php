<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Daftar IKP<?= $this->endSection(); ?>

<?= $this->section('content'); ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <a href="<?= base_url('formulir-ikp/create') ?>" class="btn btn-primary">
        <i class="bi bi-plus-circle"></i> Tambah Laporan IKP
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
            <i class="bi bi-clipboard-pulse text-primary fs-5"></i>
            <h6 class="mb-0 fw-semibold">Daftar Laporan Insiden (IKP)</h6>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table id="ikpTable" class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th class="px-3" style="width:50px">No</th>
                        <th>Nama Pasien</th>
                        <th>No MR</th>
                        <th>Ruangan Asal</th>
                        <th>Tgl Insiden</th>
                        <th>Insiden</th>
                        <th>Jenis Insiden</th>
                        <th>Grading</th>
                        <th>Status Investigasi</th>
                        <th class="text-center" style="min-width:180px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($ikpList as $ikp): ?>
                    <tr>
                        <td class="px-3"><?= $no++ ?></td>
                        <td>
                            <div class="fw-semibold"><?= esc($ikp['nama_pasien']) ?></div>
                        </td>
                        <td><?= esc($ikp['no_mr'] ?? '-') ?></td>
                        <td>
                            <div class="fw-semibold"><?= esc($ikp['area_pengukuran'] ?? '-') ?></div>
                            <?php if (!empty($ikp['ruangan'])): ?>
                                <small class="text-muted"><?= esc($ikp['ruangan']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($ikp['tgl_insiden']): ?>
                                <?= date('d/m/Y', strtotime($ikp['tgl_insiden'])) ?>
                                <?php if ($ikp['jam_insiden']): ?>
                                    <br><small class="text-muted"><?= substr($ikp['jam_insiden'], 0, 5) ?></small>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td><?= esc($ikp['insiden'] ?? '-') ?></td>
                        <td>
                            <?php
                            $jiMap = ['KNC' => 'warning', 'KTC' => 'info', 'KTD' => 'danger'];
                            $jiBadge = $jiMap[$ikp['jenis_insiden']] ?? 'secondary';
                            ?>
                            <?php if ($ikp['jenis_insiden']): ?>
                                <span class="badge bg-<?= $jiBadge ?>"><?= esc($ikp['jenis_insiden']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php
                            $gradingMap = [
                                'biru'   => 'primary',
                                'hijau'  => 'success',
                                'kuning' => 'warning',
                                'merah'  => 'danger',
                            ];
                            $gradingBadge = $gradingMap[$ikp['grading']] ?? 'secondary';
                            ?>
                            <?php if ($ikp['grading']): ?>
                                <span class="badge bg-<?= $gradingBadge ?> text-uppercase"><?= esc($ikp['grading']) ?></span>
                            <?php else: ?>
                                <span class="text-muted">-</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($ikp['inv_id']): ?>
                                <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai</span>
                            <?php else: ?>
                                <span class="badge bg-secondary">Belum</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
                                <?php if ($ikp['inv_id']): ?>
                                    <a href="<?= base_url('investigasi-sederhana/view/' . $ikp['inv_id']) ?>"
                                       class="btn btn-sm btn-outline-success" title="Lihat Investigasi Sederhana">
                                        <i class="bi bi-search"></i> Lihat Inv.
                                    </a>
                                <?php elseif (in_array(strtolower($ikp['grading'] ?? ''), ['hijau', 'biru'])): ?>
                                    <a href="<?= base_url('investigasi-sederhana/create/' . $ikp['id']) ?>"
                                       class="btn btn-sm btn-outline-primary" title="Buat Investigasi Sederhana">
                                        <i class="bi bi-search"></i> Buat Inv.
                                    </a>
                                <?php endif; ?>
                                <a href="<?= base_url('formulir-ikp/view/' . $ikp['id']) ?>"
                                   class="btn btn-sm btn-outline-info" title="Lihat Detail">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                <a href="<?= base_url('formulir-ikp/edit/' . $ikp['id']) ?>"
                                   class="btn btn-sm btn-outline-warning" title="Edit">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <button type="button"
                                        class="btn btn-sm btn-outline-danger btn-hapus"
                                        data-id="<?= $ikp['id'] ?>"
                                        data-nama="<?= esc($ikp['nama_pasien']) ?>"
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
                <p class="mb-0">Apakah Anda yakin ingin menghapus laporan IKP atas nama <strong id="nama-hapus"></strong>? <br><small class="text-muted">Tindakan ini juga akan menghapus Investigasi Sederhana terkait jika sudah dibuat.</small></p>
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
    $('#ikpTable').DataTable({
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
            emptyTable: 'Belum ada data laporan IKP',
            zeroRecords: 'Data tidak ditemukan',
        },
        pageLength: 10,
        order: [[0, 'asc']],
        columnDefs: [
            { orderable: false, targets: [8] }
        ],
    });

    // Modal Hapus
    const modalHapus = new bootstrap.Modal(document.getElementById('modalHapus'));
    document.querySelectorAll('.btn-hapus').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const id   = this.dataset.id;
            const nama = this.dataset.nama;
            document.getElementById('nama-hapus').textContent = nama;
            document.getElementById('link-hapus').href = '<?= base_url('formulir-ikp/delete/') ?>' + id;
            modalHapus.show();
        });
    });
});
</script>
<?= $this->endSection(); ?>
