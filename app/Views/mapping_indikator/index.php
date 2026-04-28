<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Mapping Indikator Per Area Pengukuran
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Mapping Indikator Per Area Pengukuran</h3>
            </div>
            <div class="card-body">
                <!-- Filter Section -->
                <form method="get" action="<?= base_url('mapping-indikator') ?>" class="mb-4">
                    <div class="row align-items-end">
                        <div class="col-md-4">
                            <label for="area_id" class="form-label">Filter Area Pengukuran</label>
                            <select class="form-select select2" id="area_id" name="area_id" onchange="this.form.submit()">
                                <option value="">Semua Area</option>
                                <?php foreach ($area_pengukuran as $area) : ?>
                                    <option value="<?= $area['id'] ?>" <?= $selected_area == $area['id'] ? 'selected' : '' ?>>
                                        <?= esc($area['area_pengukuran']) ?>
                                    </option>
                                <?php endforeach ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <?php if ($selected_area) : ?>
                                <a href="<?= base_url('mapping-indikator') ?>" class="btn btn-secondary">Reset Filter</a>
                            <?php endif ?>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-bordered table-striped table-hover" id="table-mapping">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%">No</th>
                                <th style="width: 30%">Area Pengukuran</th>
                                <th style="width: 65%">Judul Indikator</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($mapping_data)) : ?>
                                <tr>
                                    <td colspan="3" class="text-center">Tidak ada data ditemukan</td>
                                </tr>
                            <?php else : ?>
                                <?php foreach ($mapping_data as $index => $item) : ?>
                                    <tr>
                                        <td class="text-center"><?= $index + 1 ?></td>
                                        <td><?= esc($item['area_pengukuran']) ?></td>
                                        <td>
                                            <a href="#" class="text-decoration-none view-detail" data-id="<?= $item['indikator_mutu_id'] ?>">
                                                <?= esc($item['judul_indikator']) ?>
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            <?php endif ?>
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
$(document).ready(function() {
    $('#table-mapping').DataTable({
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

    $('.view-detail').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        // Show loading state or clear previous data
        $('#modalDetail').modal('show');
        
        $.ajax({
            url: '<?= base_url('mapping-indikator/get-detail') ?>/' + id,
            type: 'GET',
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    const data = response.data;
                    
                    $('#detail_judul_indikator').text(data.judul_indikator);
                    $('#detail_jenis_indikator').text(data.jenis_indikator);
                    $('#detail_status').html(data.status == 'aktif' ? '<span class="badge bg-success">Aktif</span>' : '<span class="badge bg-danger">Tidak Aktif</span>');
                    
                    // HTML content
                    $('#detail_definisi_operasional').html(data.definisi_operasional);
                    $('#detail_numerator').html(data.numerator);
                    $('#detail_denumerator').html(data.denumerator);
                    $('#detail_kriteria_inklusi').html(data.kriteria_inklusi || '-');
                    $('#detail_kriteria_eksklusi').html(data.kriteria_eksklusi || '-');
                    $('#detail_rencana_analisis').html(data.rencana_analisis || '-');
                    
                    // Text content
                    $('#detail_target_pencapaian').text((data.standar_target_pencapaian ? data.standar_target_pencapaian + ' ' : '') + data.target_pencapaian + ' ' + (data.satuan_target_pencapaian || '%'));
                    $('#detail_sumber_data').text(data.sumber_data);
                    $('#detail_frekuensi_pengumpulan_data').text(data.frekuensi_pengumpulan_data || '-');
                    $('#detail_periode_analisis_data').text(data.periode_analisis_data || '-');
                    $('#detail_instrumen_pengambilan_data').text(data.instrumen_pengambilan_data || '-');
                    $('#detail_area_pengukuran').text(data.area_pengukuran || '-');
                } else {
                    alert('Gagal memuat data');
                }
            },
            error: function() {
                alert('Terjadi kesalahan koneksi');
            }
        });
    });
});
</script>
<?= $this->endSection() ?>
