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
                        <th>Nama Pasien & No MR</th>
                        <th>Ruangan Asal</th>
                        <th>Tgl Insiden</th>
                        <th>Insiden</th>
                        <th>Jenis Insiden</th>
                        <th>Grading</th>
                        <th>Status Investigasi</th>
                        <th>Status Lapor</th>
                        <th class="text-center" style="min-width:180px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1; foreach ($ikpList as $ikp): ?>
                    <tr>
                        <td class="px-3"><?= $no++ ?></td>
                        <td>
                            <div class="fw-semibold"><?= esc($ikp['nama_pasien']) ?></div>
                            <small class="text-muted"><i class="bi bi-person-badge"></i> <?= esc($ikp['no_mr'] ?? '-') ?></small>
                        </td>
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
                            $gradingAwalBadge = $gradingMap[strtolower($ikp['grading'] ?? '')] ?? 'secondary';
                            $gradingAkhirBadge = $gradingMap[strtolower($ikp['grading_akhir'] ?? '')] ?? 'secondary';
                            ?>
                            <div class="d-flex flex-column gap-1">
                                <div>
                                    <small class="text-muted d-block" style="font-size:0.7rem">Awal</small>
                                    <?php if ($ikp['grading']): ?>
                                        <span class="badge bg-<?= $gradingAwalBadge ?> text-uppercase"><?= esc($ikp['grading']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                <?php if ($ikp['inv_id']): ?>
                                <div>
                                    <small class="text-muted d-block" style="font-size:0.7rem">Akhir</small>
                                    <?php if (!empty($ikp['grading_akhir'])): ?>
                                        <span class="badge bg-<?= $gradingAkhirBadge ?> text-uppercase"><?= esc($ikp['grading_akhir']) ?></span>
                                    <?php else: ?>
                                        <span class="text-muted">-</span>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <?php if ($ikp['inv_id']): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Selesai</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Belum</span>
                                <?php endif; ?>
                                
                                <?php if ($ikp['inv_id']): ?>
                                    <a href="<?= base_url('investigasi-sederhana/view/' . $ikp['inv_id']) ?>"
                                       class="btn btn-sm btn-outline-success mt-1" style="font-size: 0.7rem;" title="Lihat Investigasi Sederhana">
                                        <i class="bi bi-search"></i> Lihat Inv.
                                    </a>
                                <?php elseif (in_array(strtolower($ikp['grading'] ?? ''), ['hijau', 'biru'])): ?>
                                    <a href="<?= base_url('investigasi-sederhana/create/' . $ikp['id']) ?>"
                                       class="btn btn-sm btn-outline-primary mt-1" style="font-size: 0.7rem;" title="Buat Investigasi Sederhana">
                                        <i class="bi bi-search"></i> Buat Inv.
                                    </a>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td>
                            <div class="d-flex flex-column gap-1 align-items-center">
                                <?php if ($ikp['is_dilaporkan'] ?? 0): ?>
                                    <span class="badge bg-success"><i class="bi bi-check-circle"></i> Sudah</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark"><i class="bi bi-clock"></i> Belum</span>
                                <?php endif; ?>
                                
                                <?php if (in_groups('administrator')): ?>
                                    <?php if (empty($ikp['is_dilaporkan'])): ?>
                                        <button type="button" class="btn btn-sm btn-outline-success btn-tandai mt-1" style="font-size: 0.7rem;" data-id="<?= $ikp['id'] ?>" data-nama="<?= esc($ikp['nama_pasien']) ?>" title="Tandai Sudah Dilaporkan">
                                            <i class="bi bi-check2-all"></i> Lapor
                                        </button>
                                    <?php else: ?>
                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-batal-tandai mt-1" style="font-size: 0.7rem;" data-id="<?= $ikp['id'] ?>" data-nama="<?= esc($ikp['nama_pasien']) ?>" title="Batal Tandai Dilaporkan">
                                            <i class="bi bi-x-circle"></i> Batal
                                        </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <div class="d-flex gap-1 justify-content-center">
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

<?php /* ===== Modal Konfirmasi Tandai ===== */ ?>
<div class="modal fade" id="modalTandai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-success">
                    <i class="bi bi-check-circle-fill me-2"></i>Konfirmasi Lapor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin menandai laporan IKP atas nama <strong id="nama-tandai"></strong> sudah dilaporkan?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Batal
                </button>
                <a href="#" id="link-tandai" class="btn btn-success">
                    <i class="bi bi-check"></i> Ya, Tandai
                </a>
            </div>
        </div>
    </div>
</div>

<?php /* ===== Modal Konfirmasi Batal Tandai ===== */ ?>
<div class="modal fade" id="modalBatalTandai" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0 pb-0">
                <h5 class="modal-title text-secondary">
                    <i class="bi bi-arrow-counterclockwise me-2"></i>Konfirmasi Batal Lapor
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Apakah Anda yakin ingin <strong>membatalkan</strong> status lapor untuk IKP atas nama <strong id="nama-batal-tandai"></strong>?</p>
            </div>
            <div class="modal-footer border-0 pt-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle"></i> Tutup
                </button>
                <a href="#" id="link-batal-tandai" class="btn btn-danger">
                    <i class="bi bi-check"></i> Ya, Batalkan
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
            { orderable: false, targets: [-1] }
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

    // Modal Tandai
    const modalTandaiElement = document.getElementById('modalTandai');
    if (modalTandaiElement) {
        const modalTandai = new bootstrap.Modal(modalTandaiElement);
        document.querySelectorAll('.btn-tandai').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id   = this.dataset.id;
                const nama = this.dataset.nama;
                document.getElementById('nama-tandai').textContent = nama;
                document.getElementById('link-tandai').href = '<?= base_url('formulir-ikp/mark-reported/') ?>' + id;
                modalTandai.show();
            });
        });
    }

    // Modal Batal Tandai
    const modalBatalTandaiElement = document.getElementById('modalBatalTandai');
    if (modalBatalTandaiElement) {
        const modalBatalTandai = new bootstrap.Modal(modalBatalTandaiElement);
        document.querySelectorAll('.btn-batal-tandai').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const id   = this.dataset.id;
                const nama = this.dataset.nama;
                document.getElementById('nama-batal-tandai').textContent = nama;
                document.getElementById('link-batal-tandai').href = '<?= base_url('formulir-ikp/unmark-reported/') ?>' + id;
                modalBatalTandai.show();
            });
        });
    }
});
</script>
<?= $this->endSection(); ?>
