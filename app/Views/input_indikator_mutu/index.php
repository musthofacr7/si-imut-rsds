<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Input Indikator Mutu RS
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-12">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <h3 class="card-title mb-0">Input Indikator Mutu RS</h3>
                    </div>
                    <div class="col-md-6 text-center">
                        <!-- Month/Year Selector -->
                        <form method="GET" action="<?= base_url('input-indikator-mutu') ?>" class="form-inline d-inline">
                            <div class="input-group input-group-sm d-inline-flex" style="width: auto;">
                                <select name="month" class="form-select form-select-sm select2">
                                    <?php 
                                    $indoMonths = [
                                        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                                        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
                                    ];
                                    for ($m = 1; $m <= 12; $m++): 
                                    ?>
                                        <option value="<?= str_pad($m, 2, '0', STR_PAD_LEFT) ?>" <?= $selectedMonth == str_pad($m, 2, '0', STR_PAD_LEFT) ? 'selected' : '' ?>>
                                            <?= $indoMonths[$m] ?>
                                        </option>
                                    <?php endfor; ?>
                                </select>
                                <select name="year" class="form-select form-select-sm select2">
                                    <?php for ($y = date('Y') - 2; $y <= date('Y') + 1; $y++): ?>
                                        <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="bi bi-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="col-md-3 text-end">
                        <!-- Export Excel Button -->
                        <button type="button" class="btn btn-success btn-sm" id="btnExportExcel">
                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <?php if (isset($message)): ?>
                    <div class="alert alert-info m-3">
                        <?= $message ?>
                    </div>
                <?php else: ?>
                    <div class="table-responsive" style="max-height: 600px; overflow-y: auto;">
                        <table class="table table-bordered table-sm table-hover mb-0" id="inputTable">
                            <thead class="table-light" style="position: sticky; top: 0; z-index: 10;">
                                <tr>
                                    <th rowspan="2" style="min-width: 250px; position: sticky; left: 0; background: #f8f9fa; z-index: 11;">Indikator Mutu</th>
                                    <th rowspan="2" class="text-center" style="min-width: 80px; position: sticky; left: 250px; background: #f8f9fa; z-index: 11;">N/D</th>
                                    <?php for ($day = 1; $day <= $daysInMonth; $day++): ?>
                                        <?php
                                        $date = "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                                        $dayOfWeek = date('N', strtotime($date));
                                        $isWeekend = $dayOfWeek >= 6;
                                        $isToday = $date == date('Y-m-d');
                                        ?>
                                        <th class="text-center <?= $isWeekend ? 'table-secondary' : '' ?> <?= $isToday ? 'table-primary text-white' : '' ?>" style="min-width: 50px;">
                                            <?= $day ?>
                                        </th>
                                    <?php endfor; ?>
                                    <th class="text-center bg-light" style="min-width: 60px; position: sticky; right: 0; z-index: 5;">Total</th>
                                    <th class="text-center bg-light" style="min-width: 80px; position: sticky; right: -80px; z-index: 5;">Capaian</th>
                                </tr>
                                <tr>
                                    <!-- Empty row for rowspan alignment -->
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($indikators as $indikator): ?>
                                    <!-- Numerator Row -->
                                    <tr data-indikator-id="<?= $indikator['indikator_mutu_id'] ?>" data-area-id="<?= $indikator['area_pengukuran_id'] ?>" data-type="numerator">
                                        <td rowspan="2" style="position: sticky; left: 0; background: white; z-index: 5;">
                                            <strong>
                                                <a href="#" class="text-decoration-none text-dark view-detail" data-id="<?= $indikator['indikator_mutu_id'] ?>">
                                                    <?= esc($indikator['judul_indikator']) ?>
                                                </a>
                                            </strong>
                                            <br><small class="text-muted"><?= esc($indikator['area_pengukuran']) ?></small>
                                        </td>
                                        <td style="position: sticky; left: 250px; background: white; z-index: 5;" class="p-1 align-middle">
                                            <span class="fw-bold">Numerator</span>
                                        </td>
                                        <?php 
                                        $totalNumerator = 0;
                                        for ($day = 1; $day <= $daysInMonth; $day++): 
                                            $date = "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                                            $key = $indikator['indikator_mutu_id'] . '_' . $indikator['area_pengukuran_id'] . '_' . $day;
                                            $value = $existingData[$key]['numerator'] ?? '';
                                            $numericValue = is_numeric($value) ? (float)$value : 0;
                                            $totalNumerator += $numericValue;
                                            $dayOfWeek = date('N', strtotime($date));
                                            $isWeekend = $dayOfWeek >= 6;
                                        ?>
                                            <td class="<?= $isWeekend ? 'table-secondary' : '' ?> p-0 text-center clickable-cell"
                                                data-indikator-id="<?= $indikator['indikator_mutu_id'] ?>"
                                                data-area-id="<?= $indikator['area_pengukuran_id'] ?>"
                                                data-indikator-name="<?= esc($indikator['judul_indikator']) ?>"
                                                data-date="<?= $date ?>"
                                                data-type="numerator"
                                                data-current-value="<?= $value ?>">
                                                <div class="cell-content d-flex justify-content-center align-items-center">
                                                    <?= $value !== '' ? $value : '' ?>
                                                </div>
                                            </td>
                                        <?php endfor; ?>
                                        <td class="p-0 text-center align-middle fw-bold bg-light total-cell-numerator" 
                                            data-indikator-id="<?= $indikator['indikator_mutu_id'] ?>" 
                                            data-area-id="<?= $indikator['area_pengukuran_id'] ?>"
                                            style="position: sticky; right: 0; z-index: 5;">
                                            <?= $totalNumerator ?>
                                        </td>
                                        <?php
                                        // Calculate initial totals for denumerator to calculate capaian
                                        $tempTotalDenumerator = 0;
                                        for ($day = 1; $day <= $daysInMonth; $day++):
                                            $key = $indikator['indikator_mutu_id'] . '_' . $indikator['area_pengukuran_id'] . '_' . $day;
                                            $val = $existingData[$key]['denumerator'] ?? 0;
                                            $tempTotalDenumerator += is_numeric($val) ? (float)$val : 0;
                                        endfor;
                                        
                                        $capaian = ($tempTotalDenumerator > 0) ? ($totalNumerator / $tempTotalDenumerator * 100) : 0;
                                        ?>
                                        <td rowspan="2" class="text-center fw-bold bg-light align-middle capaian-cell"
                                            data-indikator-id="<?= $indikator['indikator_mutu_id'] ?>" 
                                            data-area-id="<?= $indikator['area_pengukuran_id'] ?>"
                                            style="position: sticky; right: -80px; z-index: 5;">
                                            <?= number_format($capaian, 2) ?>%
                                        </td>
                                    </tr>
                                    <!-- Denumerator Row -->
                                    <tr data-indikator-id="<?= $indikator['indikator_mutu_id'] ?>" data-area-id="<?= $indikator['area_pengukuran_id'] ?>" data-type="denumerator">
                                        <td style="position: sticky; left: 250px; background: white; z-index: 5;" class="p-1 align-middle">
                                            <span class="fw-bold">Denumerator</span>
                                        </td>
                                        <?php 
                                        $totalDenumerator = 0;
                                        for ($day = 1; $day <= $daysInMonth; $day++): 
                                            $date = "$selectedYear-$selectedMonth-" . str_pad($day, 2, '0', STR_PAD_LEFT);
                                            $key = $indikator['indikator_mutu_id'] . '_' . $indikator['area_pengukuran_id'] . '_' . $day;
                                            $value = $existingData[$key]['denumerator'] ?? '';
                                            $numericValue = is_numeric($value) ? (float)$value : 0;
                                            $totalDenumerator += $numericValue;
                                            $dayOfWeek = date('N', strtotime($date));
                                            $isWeekend = $dayOfWeek >= 6;
                                        ?>
                                            <td class="<?= $isWeekend ? 'table-secondary' : '' ?> p-0 text-center clickable-cell"
                                                data-indikator-id="<?= $indikator['indikator_mutu_id'] ?>"
                                                data-area-id="<?= $indikator['area_pengukuran_id'] ?>"
                                                data-indikator-name="<?= esc($indikator['judul_indikator']) ?>"
                                                data-date="<?= $date ?>"
                                                data-type="denumerator"
                                                data-current-value="<?= $value ?>">
                                                <div class="cell-content d-flex justify-content-center align-items-center">
                                                    <?= $value !== '' ? $value : '' ?>
                                                </div>
                                            </td>
                                        <?php endfor; ?>
                                        <td class="p-0 text-center align-middle fw-bold bg-light total-cell-denumerator" 
                                            data-indikator-id="<?= $indikator['indikator_mutu_id'] ?>" 
                                            data-area-id="<?= $indikator['area_pengukuran_id'] ?>"
                                            style="position: sticky; right: 0; z-index: 5;">
                                            <?= $totalDenumerator ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
#inputTable {
    font-size: 0.8rem;
}
.clickable-cell {
    cursor: pointer;
    height: 30px;
    vertical-align: middle;
}
.clickable-cell:hover {
    background-color: #e2e6ea;
}
.cell-content {
    width: 100%;
    height: 100%;
    min-height: 30px;
}
.input-btn {
    opacity: 0.5;
}
.clickable-cell:hover .input-btn {
    opacity: 1;
}
</style>

<!-- Modal Detail Indikator -->
<div class="modal fade" id="modalDetail" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Indikator Mutu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Judul Indikator</label>
                    <div class="col-sm-8">
                        <p id="detail_judul_indikator" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Jenis Indikator</label>
                    <div class="col-sm-8">
                        <p id="detail_jenis_indikator" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Status</label>
                    <div class="col-sm-8">
                        <p id="detail_status" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Definisi Operasional</label>
                    <div class="col-sm-8">
                        <div id="detail_definisi_operasional" class="p-2 bg-light rounded border"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Numerator</label>
                    <div class="col-sm-8">
                        <div id="detail_numerator" class="p-2 bg-light rounded border"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Denumerator</label>
                    <div class="col-sm-8">
                        <div id="detail_denumerator" class="p-2 bg-light rounded border"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Target Pencapaian</label>
                    <div class="col-sm-8">
                        <p id="detail_target_pencapaian" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Kriteria Inklusi</label>
                    <div class="col-sm-8">
                        <div id="detail_kriteria_inklusi" class="p-2 bg-light rounded border"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Kriteria Eksklusi</label>
                    <div class="col-sm-8">
                        <div id="detail_kriteria_eksklusi" class="p-2 bg-light rounded border"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Sumber Data</label>
                    <div class="col-sm-8">
                        <p id="detail_sumber_data" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Frekuensi Pengumpulan Data</label>
                    <div class="col-sm-8">
                        <p id="detail_frekuensi_pengumpulan_data" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Periode Analisis Data</label>
                    <div class="col-sm-8">
                        <p id="detail_periode_analisis_data" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Rencana Analisis</label>
                    <div class="col-sm-8">
                        <div id="detail_rencana_analisis" class="p-2 bg-light rounded border"></div>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Instrumen Pengambilan Data</label>
                    <div class="col-sm-8">
                        <p id="detail_instrumen_pengambilan_data" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
                <div class="row mb-3">
                    <label class="col-sm-4 col-form-label fw-bold">Area Pengukuran (Default)</label>
                    <div class="col-sm-8">
                        <p id="detail_area_pengukuran" class="form-control-plaintext border-bottom"></p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- SheetJS library for Excel export -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Export to Excel functionality
    $('#btnExportExcel').on('click', function() {
        const table = document.getElementById('inputTable');
        if (!table) {
            Swal.fire('Error', 'Tabel tidak ditemukan', 'error');
            return;
        }

        // Create workbook
        const wb = XLSX.utils.book_new();
        
        // Convert table to worksheet
        const ws = XLSX.utils.table_to_sheet(table);
        
        // Add worksheet to workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Input Indikator Mutu');
        
        // Generate filename with current month and year
        const month = '<?= $selectedMonth ?>';
        const year = '<?= $selectedYear ?>';
        const monthName = '<?= $indoMonths[(int)$selectedMonth] ?>';
        const filename = `Input_Indikator_Mutu_${monthName}_${year}.xlsx`;
        
        // Save file
        XLSX.writeFile(wb, filename);
        
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: 'Data berhasil diexport ke Excel',
            timer: 1500,
            showConfirmButton: false
        });
    });

    $('.view-detail').on('click', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
        // Show loading state or clear previous data
        $('#modalDetail').modal('show');
        
        $.ajax({
            url: '<?= base_url('input-indikator-mutu/get-detail') ?>/' + id,
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
                    $('#detail_target_pencapaian').text(data.target_pencapaian + ' ' + (data.satuan_target_pencapaian || '%'));
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

    // Store descriptions for use in modal
    const indikatorDescriptions = {};
    <?php foreach ($indikators as $indikator): ?>
        indikatorDescriptions[<?= $indikator['indikator_mutu_id'] ?>] = {
            numerator: <?= json_encode($indikator['numerator']) ?>,
            denumerator: <?= json_encode($indikator['denumerator']) ?>
        };
    <?php endforeach; ?>

    $('.clickable-cell').on('click', function() {
        const cell = $(this);
        const indikatorName = cell.data('indikator-name');
        const date = cell.data('date');
        const type = cell.data('type'); // clicked type
        const indikatorId = cell.data('indikator-id');
        const areaId = cell.data('area-id');
        
        // Find both cells to get current values
        const numCell = $(`.clickable-cell[data-indikator-id="${indikatorId}"][data-area-id="${areaId}"][data-date="${date}"][data-type="numerator"]`);
        const denCell = $(`.clickable-cell[data-indikator-id="${indikatorId}"][data-area-id="${areaId}"][data-date="${date}"][data-type="denumerator"]`);
        
        const currentNum = numCell.data('current-value');
        const currentDen = denCell.data('current-value');

        // Get descriptions
        const descriptions = indikatorDescriptions[indikatorId] || { numerator: '', denumerator: '' };
        
        Swal.fire({
            title: 'Input Data Indikator',
            width: '800px',
            html: `
                <div class="text-start" style="font-size: 0.85rem;">
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label fw-bold">Indikator</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" value="${indikatorName}" readonly disabled>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label class="col-sm-3 col-form-label fw-bold">Tanggal</label>
                        <div class="col-sm-9">
                            <input type="text" class="form-control form-control-sm" value="${date}" readonly disabled>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="mb-9 row">
                                <label for="swal-input-num" class="col-sm-9 col-form-label fw-bold">Numerator</label>
                                <div class="col-sm-9 alert alert-light border p-2 mb-2 small" style="max-height: 100px; overflow-y: auto;">
                                       ${descriptions.numerator}
                                </div>
                                <div class="col-sm-3">
                                    <input type="number" id="swal-input-num" class="form-control form-control-sm" value="${currentNum !== undefined ? currentNum : ''}" min="0">
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="mb-9 row">
                                <label for="swal-input-den" class="col-sm-9 col-form-label fw-bold">Denumerator</label>
                                <div class="col-sm-9 alert alert-light border p-2 mb-2 small" style="max-height: 100px; overflow-y: auto;">
                                       ${descriptions.denumerator}
                                </div>
                                <div class="col-sm-3">
                                    <input type="number" id="swal-input-den" class="form-control form-control-sm" value="${currentDen !== undefined ? currentDen : ''}" min="0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            cancelButtonText: 'Batal',
            focusConfirm: false,
            preConfirm: () => {
                return {
                    numerator: document.getElementById('swal-input-num').value,
                    denumerator: document.getElementById('swal-input-den').value
                }
            }
        }).then((result) => {
            if (result.isConfirmed) {
                const numerator = result.value.numerator;
                const denumerator = result.value.denumerator;
                
                $.ajax({
                    url: '<?= base_url('input-indikator-mutu/store') ?>',
                    method: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        indikator_id: indikatorId,
                        area_id: areaId,
                        tanggal: date,
                        numerator: numerator,
                        denumerator: denumerator
                    }),
                    success: function(response) {
                        if (response.success) {
                            // Update Numerator Cell
                            numCell.data('current-value', numerator);
                            numCell.find('.cell-content').text(numerator !== '' ? numerator : '0');
                            
                            // Update Denumerator Cell
                            denCell.data('current-value', denumerator);
                            denCell.find('.cell-content').text(denumerator !== '' ? denumerator : '0');
                            
                            // Recalculate Totals
                            updateRowTotal(indikatorId, areaId, 'numerator');
                            updateRowTotal(indikatorId, areaId, 'denumerator');
                            
                            // Recalculate Capaian
                            updateCapaian(indikatorId, areaId);
                            
                            Swal.fire({
                                icon: 'success',
                                title: 'Tersimpan',
                                timer: 1000,
                                showConfirmButton: false
                            });
                        } else {
                            Swal.fire('Error', response.message, 'error');
                        }
                    },
                    error: function() {
                        Swal.fire('Error', 'Gagal menyimpan data', 'error');
                    }
                });
            }
        });
    });
    
    function updateRowTotal(indikatorId, areaId, type) {
        let total = 0;
        $(`.clickable-cell[data-indikator-id="${indikatorId}"][data-area-id="${areaId}"][data-type="${type}"]`).each(function() {
            const val = $(this).data('current-value');
            if (val !== undefined && val !== '' && !isNaN(val)) {
                total += parseFloat(val);
            }
        });
        
        $(`.total-cell-${type}[data-indikator-id="${indikatorId}"][data-area-id="${areaId}"]`).text(total);
    }

    function updateCapaian(indikatorId, areaId) {
        const totalNumText = $(`.total-cell-numerator[data-indikator-id="${indikatorId}"][data-area-id="${areaId}"]`).text();
        const totalDenText = $(`.total-cell-denumerator[data-indikator-id="${indikatorId}"][data-area-id="${areaId}"]`).text();
        
        const totalNum = parseFloat(totalNumText) || 0;
        const totalDen = parseFloat(totalDenText) || 0;
        
        let capaian = 0;
        if (totalDen > 0) {
            capaian = (totalNum / totalDen) * 100;
        }
        
        $(`.capaian-cell[data-indikator-id="${indikatorId}"][data-area-id="${areaId}"]`).text(capaian.toFixed(2) + '%');
    }
});
</script>
<?= $this->endSection() ?>
