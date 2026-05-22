<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h3 class="card-title"><?= $title ?></h3>
                <a href="<?= base_url('risk-register') ?>" class="btn btn-secondary btn-sm">Kembali</a>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <table class="table table-borderless">
                            <tr>
                                <th style="width: 150px;">Diupload Oleh</th>
                                <td>: <?= esc($risk_register['username']) ?></td>
                            </tr>
                            <tr>
                                <th>Tanggal Upload</th>
                                <td>: <?= $risk_register['created_at'] ?></td>
                            </tr>
                            <tr>
                                <th>Deskripsi</th>
                                <td>: <?= esc($risk_register['deskripsi']) ?></td>
                            </tr>
                            <?php if (!empty($risk_register['keterangan'])) : ?>
                            <tr>
                                <th>Keterangan Revisi</th>
                                <td>: <?= esc($risk_register['keterangan']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>Status</th>
                                <td>: 
                                    <?php if ($risk_register['validasi'] == 1) : ?>
                                        <span class="badge bg-success">Diterima</span>
                                    <?php elseif ($risk_register['validasi'] == 0 && !empty($risk_register['komentar_admin'])) : ?>
                                        <span class="badge bg-danger">Ditolak</span>
                                    <?php else : ?>
                                        <span class="badge bg-warning">Menunggu Validasi</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php if (!empty($risk_register['komentar_admin'])) : ?>
                            <tr>
                                <th>Komentar Admin</th>
                                <td>: <?= esc($risk_register['komentar_admin']) ?></td>
                            </tr>
                            <?php endif; ?>
                            <tr>
                                <th>File</th>
                                <td>: 
                                    <a href="<?= base_url('uploads/risk_register/' . $risk_register['file_risk_register']) ?>" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="bi bi-download"></i> Download / Buka Tab Baru
                                    </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-8">
                        <div class="card">
                            <div class="card-header">
                                Preview File
                            </div>
                            <div class="card-body p-0 text-center bg-light" style="min-height: 500px;">
                                <?php
                                $ext = strtolower(pathinfo($risk_register['file_risk_register'], PATHINFO_EXTENSION));
                                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                                    echo '<img src="' . base_url('uploads/risk_register/' . $risk_register['file_risk_register']) . '" class="img-fluid" style="max-height: 800px;">';
                                } elseif ($ext == 'pdf') {
                                    echo '<iframe src="' . base_url('uploads/risk_register/' . $risk_register['file_risk_register']) . '" width="100%" height="800px"></iframe>';
                                } else {
                                    echo '<div class="p-5 text-muted">Preview tidak tersedia untuk format file ini. Silakan download untuk melihat.</div>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
