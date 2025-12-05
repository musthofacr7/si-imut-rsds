<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Setting Indikator Mutu
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Setting Indikator Mutu</h3>
        <div class="card-tools">
            <a href="<?= base_url('setting-indikator-mutu/create') ?>" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Tambah Setting
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

        <table class="table table-striped" id="table-setting">
            <thead>
                <tr>
                    <th style="width: 10px">#</th>
                    <th>Indikator Mutu</th>
                    <th>Area Pengukuran</th>
                    <th style="width: 150px">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Group by indikator_mutu_id
                $grouped = [];
                foreach ($setting_indikator as $item) {
                    $grouped[$item['indikator_mutu_id']]['judul_indikator'] = $item['judul_indikator'];
                    $grouped[$item['indikator_mutu_id']]['areas'][] = $item['area_pengukuran'];
                    $grouped[$item['indikator_mutu_id']]['id'] = $item['id']; // Use first ID for edit
                }
                
                $index = 1;
                foreach ($grouped as $indikator_id => $data) : 
                ?>
                    <tr>
                        <td><?= $index++ ?></td>
                        <td><?= esc($data['judul_indikator']) ?></td>
                        <td>
                            <?php foreach ($data['areas'] as $area) : ?>
                                <span class="badge bg-primary me-1"><?= esc($area) ?></span>
                            <?php endforeach ?>
                        </td>
                        <td>
                            <a href="<?= base_url('setting-indikator-mutu/edit/' . $data['id']) ?>" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="<?= base_url('setting-indikator-mutu/delete/' . $data['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus semua area untuk indikator ini?')">
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

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        $('#table-setting').DataTable({
            "pageLength": 10,
            "lengthMenu": [
                [10, 20, 25, 50, -1],
                [10, 20, 25, 50, "All"]
            ],
            "responsive": true,
            "autoWidth": false,
            "language": {
                "url": "<?= base_url('assets/plugins/datatables/i18n/id.json') ?>"
            }
        });
    });
</script>
<?= $this->endSection() ?>
