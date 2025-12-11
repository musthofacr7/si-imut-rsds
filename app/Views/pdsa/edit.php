<?= $this->extend('layout/main') ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><?= $title ?></h3>
            </div>
            <form action="<?= base_url('pdsa/update/' . $pdsa['id']) ?>" method="post" enctype="multipart/form-data">
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
                     <?php if (session()->getFlashdata('error')) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= session()->getFlashdata('error') ?>
                        </div>
                    <?php endif; ?>

                    <!-- Common View for File Info -->
                    <div class="mb-3">
                        <label class="form-label">File Saat Ini</label>
                        <div>
                            <a href="<?= base_url('uploads/pdsa/' . $pdsa['file_pdsa']) ?>" target="_blank" class="btn btn-info btn-sm">
                                <i class="bi bi-download"></i> Download File
                            </a>
                        </div>
                    </div>

                    <!-- Administrator Validation Form -->
                    <?php if (in_groups('administrator')) : ?>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi (User)</label>
                            <div class="form-control bg-light"><?= esc($pdsa['deskripsi']) ?></div>
                        </div>

                        <?php if (!empty($pdsa['keterangan'])) : ?>
                        <div class="mb-3">
                            <label class="form-label">Keterangan Revisi</label>
                            <div class="form-control bg-light"><?= esc($pdsa['keterangan']) ?></div>
                        </div>
                        <?php endif; ?>

                        <hr>
                        <h4>Validasi</h4>
                        
                        <div class="mb-3">
                            <label for="validasi" class="form-label">Status Validasi</label>
                            <select class="form-select" id="validasi" name="validasi">
                                <option value="0" <?= $pdsa['validasi'] == 0 ? 'selected' : '' ?>>Tolak / Pending</option>
                                <option value="1" <?= $pdsa['validasi'] == 1 ? 'selected' : '' ?>>Terima</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="komentar_admin" class="form-label">Komentar Admin <span class="text-danger">* Wajib jika ditolak</span></label>
                            <textarea class="form-control" id="komentar_admin" name="komentar_admin" rows="3"><?= esc($pdsa['komentar_admin']) ?></textarea>
                        </div>
                    <?php endif; ?>

                    <!-- PJ Mutu Edit Form -->
                    <?php if (in_groups('pj-mutu')) : ?>
                        <!-- If Admin commented, show it -->
                        <?php if (!empty($pdsa['komentar_admin'])) : ?>
                            <div class="alert alert-warning">
                                <strong>Komentar Admin:</strong> <?= esc($pdsa['komentar_admin']) ?>
                            </div>
                        <?php endif; ?>

                        <div class="mb-3">
                            <label for="file_pdsa" class="form-label">Upload File Baru (Opsional)</label>
                            <input type="file" class="form-control" id="file_pdsa" name="file_pdsa">
                            <small class="text-muted">Biarkan kosong jika tidak ingin mengubah file.</small>
                        </div>

                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" required><?= old('deskripsi', $pdsa['deskripsi']) ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan Revisi (Jika ada)</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="2" placeholder="Tambahkan catatan tentang revisi ini..."><?= old('keterangan', $pdsa['keterangan'] ?? '') ?></textarea>
                        </div>
                        
                        <input type="hidden" name="role_action" value="pj-mutu">
                    <?php endif; ?>

                </div>
                <div class="card-footer text-end">
                    <a href="<?= base_url('pdsa') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
