<?= $this->extend('layout/main'); ?>

<?= $this->section('content'); ?>
<!-- <div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Monitoring Input Indikator Mutu</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-end">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Monitoring Input</li>
            </ol>
        </div>
    </div>
</div> -->

<div class="content">
    <div class="container-fluid">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Filter Data</h3>
            </div>
            <div class="card-body">
                <form action="" method="get" class="row g-3">
                    <div class="col-md-4">
                        <label for="month" class="form-label">Bulan</label>
                        <select name="month" id="month" class="form-select">
                            <?php for ($i = 1; $i <= 12; $i++) : ?>
                                <option value="<?= $i ?>" <?= $selectedMonth == $i ? 'selected' : '' ?>>
                                    <?= date('F', mktime(0, 0, 0, $i, 10)) ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="year" class="form-label">Tahun</label>
                        <select name="year" id="year" class="form-select">
                            <?php for ($i = date('Y'); $i >= date('Y') - 5; $i--) : ?>
                                <option value="<?= $i ?>" <?= $selectedYear == $i ? 'selected' : '' ?>>
                                    <?= $i ?>
                                </option>
                            <?php endfor; ?>
                        </select>
                    </div>
                    <div class="col-md-4 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-filter"></i> Tampilkan
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <div class="card card-outline card-success mt-3">
            <div class="card-header">
                <h3 class="card-title">Data Monitoring Input (<?= date('F', mktime(0, 0, 0, $selectedMonth, 10)) ?> <?= $selectedYear ?>)</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="monitoringTable">
                        <thead>
                            <tr>
                                <th class="text-center" width="5%">No</th>
                                <th class="text-center">Area Pengukuran</th>
                                <th class="text-center">PJ Mutu</th>
                                <th class="text-center">Indikator Mutu</th>
                                <th class="text-center" width="10%">Jml Input</th>
                                <th class="text-center" width="15%">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($monitoringData)) : ?>
                                <tr>
                                    <td colspan="6" class="text-center">Data tidak ditemukan / Belum ada setting indikator.</td>
                                </tr>
                            <?php else : ?>
                                <?php $i = 1; foreach ($monitoringData as $row) : ?>
                                    <tr>
                                        <td class="text-center"><?= $i++ ?></td>
                                        <td><?= esc($row['area']) ?></td>
                                        <td><?= esc($row['pj_mutu']) ?></td>
                                        <td><?= esc($row['indikator']) ?></td>
                                        <td class="text-center"><?= $row['input_count'] ?></td>
                                        <td class="text-center">
                                            <?php if ($row['input_count'] > 0) : ?>
                                                <span class="badge bg-success">Sudah Input</span>
                                            <?php else : ?>
                                                <span class="badge bg-danger">Belum Input</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        $('#monitoringTable').DataTable({
            "responsive": true, 
            "lengthChange": false, 
            "autoWidth": false,
            "buttons": ["copy", "csv", "excel", "pdf", "print", "colvis"]
        }).buttons().container().appendTo('#monitoringTable_wrapper .col-md-6:eq(0)');
    });
</script>
<?= $this->endSection(); ?>
<?= $this->endSection(); ?>
