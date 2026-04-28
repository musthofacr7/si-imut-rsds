<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Rekap by Jenis Indikator
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Rekap Indikator Mutu by Jenis</h3>
        <div class="card-tools">
            <button class="btn btn-danger btn-sm" onclick="exportPDF()" id="btnExportPDF">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </button>
            <button class="btn btn-success btn-sm" onclick="exportExcel()" id="btnExportExcel">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <form id="filterForm" class="row g-3">
                    <div class="col-md-6">
                        <label for="jenis_indikator_filter" class="form-label">Jenis Indikator</label>
                        <select class="form-select select2" id="jenis_indikator_filter" name="jenis_indikator_id" required>
                            <option value="">Pilih Jenis Indikator</option>
                            <?php foreach ($jenis_indikator as $jenis) : ?>
                                <option value="<?= $jenis['id'] ?>"><?= esc($jenis['jenis_indikator']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label for="year_filter" class="form-label">Periode Tahun</label>
                        <select class="form-select select2" id="year_filter" name="year" required>
                            <?php foreach ($available_years as $year) : ?>
                                <option value="<?= $year ?>" <?= $year == date('Y') ? 'selected' : '' ?>><?= $year ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label d-block">&nbsp;</label>
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search"></i> Tampilkan Data
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div id="loading" class="text-center py-5" style="display: none;">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat data...</p>
                </div>
                
                <div class="table-responsive" id="tableContainer" style="display: none;">
                    <h5 class="text-center mb-3 fw-bold" id="tableTitle"></h5>
                    <table class="table table-bordered table-striped table-hover table-sm" id="rekapTable">
                        <thead class="table-light text-center align-middle">
                            <tr>
                                <th rowspan="2" style="min-width: 250px;">Nama Indikator</th>
                                <th rowspan="2" style="min-width: 80px;">Target</th>
                                <th rowspan="2" style="min-width: 80px;">Data</th>
                                <th colspan="12">Capaian Bulan</th>
                            </tr>
                            <tr>
                                <?php 
                                $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                foreach ($months as $m) : ?>
                                    <th style="min-width: 60px;"><?= $m ?></th>
                                <?php endforeach ?>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data will be populated here -->
                        </tbody>
                    </table>
                </div>
                
                <div id="noDataMessage" class="alert alert-info text-center">
                    <i class="bi bi-info-circle"></i> Silakan pilih filter dan klik "Tampilkan Data".
                </div>
            </div>
        </div>
    </div>
</div>

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
<!-- SheetJS for Excel export -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<!-- jsPDF for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- jsPDF AutoTable for table PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.8.2/jspdf.plugin.autotable.min.js"></script>
<script>
// Store the last loaded response data globally for export
let lastResponseData = null;

$(document).ready(function() {
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        
        const jenisId = $('#jenis_indikator_filter').val();
        const year = $('#year_filter').val();

        if (!jenisId || !year) {
            alert('Silakan pilih Jenis Indikator dan Tahun');
            return;
        }

        loadData(jenisId, year);
    });

    $('body').on('click', '.view-detail', function(e) {
        e.preventDefault();
        const id = $(this).data('id');
        
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
                    
                    $('#detail_definisi_operasional').html(data.definisi_operasional);
                    $('#detail_numerator').html(data.numerator);
                    $('#detail_denumerator').html(data.denumerator);
                    $('#detail_kriteria_inklusi').html(data.kriteria_inklusi || '-');
                    $('#detail_kriteria_eksklusi').html(data.kriteria_eksklusi || '-');
                    $('#detail_rencana_analisis').html(data.rencana_analisis || '-');
                    
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

function loadData(jenisId, year) {
    $('#loading').show();
    $('#tableContainer').hide();
    $('#noDataMessage').hide();

    $.ajax({
        url: '<?= base_url('rekap-jenis-indikator/get-data') ?>',
        type: 'POST',
        data: {
            jenis_indikator_id: jenisId,
            year: year,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            $('#loading').hide();
            
            if (response.success) {
                // Store response data for export
                lastResponseData = response;

                $('#tableTitle').text(`Rekap ${response.jenis_indikator} Tahun ${response.year}`);
                
                let tbody = '';
                
                if (response.data.length === 0) {
                    tbody = '<tr><td colspan="15" class="text-center">Tidak ada data indikator untuk jenis ini</td></tr>';
                } else {
                    response.data.forEach(function(row) {
                        const months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                        
                        // Row 1: Numerator
                        tbody += '<tr>';
                        tbody += `<td rowspan="3" class="align-middle bg-white" style="position: sticky; left: 0; z-index: 5;">
                                    <strong>
                                        <a href="#" class="text-decoration-none text-dark view-detail" data-id="${row.id}">
                                            ${row.judul}
                                        </a>
                                    </strong>
                                    <br><small class="text-muted">${lastResponseData.jenis_indikator}</small>
                                  </td>`;
                        
                        let targetDisplay = row.standar + ' ' + row.target;
                        if (row.satuan === '%') targetDisplay += '%';
                        else targetDisplay += ' ' + row.satuan;
                        
                        tbody += `<td rowspan="3" class="text-center fw-bold align-middle bg-white">${targetDisplay}</td>`;
                        
                        tbody += '<td class="fw-bold">Numerator</td>';
                        
                        months.forEach(function(m) {
                             if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                                 tbody += `<td class="text-center">${row.monthly_data[m].numerator}</td>`;
                             } else {
                                 tbody += `<td class="text-center text-muted">-</td>`;
                             }
                        });
                        tbody += '</tr>';

                        // Row 2: Denumerator
                        tbody += '<tr>';
                        tbody += '<td class="fw-bold">Denumerator</td>';
                        months.forEach(function(m) {
                             if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                                 tbody += `<td class="text-center">${row.monthly_data[m].denumerator}</td>`;
                             } else {
                                 tbody += `<td class="text-center text-muted">-</td>`;
                             }
                        });
                        tbody += '</tr>';

                        // Row 3: Capaian
                        tbody += '<tr>';
                        tbody += '<td class="fw-bold text-primary">Capaian</td>';
                        months.forEach(function(m) {
                            if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                                let achievement = row.monthly_data[m].achievement;
                                let badgeClass = getBadgeClass(achievement, row.target, row.standar);
                                let displayVal = achievement;
                                if (row.satuan === '%') displayVal += '%';
                                
                                tbody += `<td class="text-center"><span class="badge ${badgeClass}">${displayVal}</span></td>`;
                            } else {
                                tbody += `<td class="text-center text-muted">-</td>`;
                            }
                        });
                        tbody += '</tr>';
                    });
                }
                
                $('#rekapTable tbody').html(tbody);
                $('#tableContainer').show();
                
            } else {
                alert(response.message || 'Gagal memuat data');
                $('#noDataMessage').show();
            }
        },
        error: function(xhr, status, error) {
            $('#loading').hide();
            $('#noDataMessage').show();
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data');
        }
    });
}

function getBadgeClass(achievement, target, standar) {
    target = parseFloat(target.toString().replace(',', '.').replace('%', ''));
    achievement = parseFloat(achievement);

    let isPassing = false;
    
    if (standar === '>=') isPassing = achievement >= target;
    else if (standar === '>') isPassing = achievement > target;
    else if (standar === '<=') isPassing = achievement <= target;
    else if (standar === '<') isPassing = achievement < target;
    else isPassing = achievement >= target;

    return isPassing ? 'bg-success' : 'bg-danger';
}

/**
 * Build export data rows from the stored response data
 */
function buildExportRows() {
    if (!lastResponseData || !lastResponseData.data || lastResponseData.data.length === 0) {
        return [];
    }

    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
    const rows = [];

    lastResponseData.data.forEach(function(row) {
        let targetDisplay = row.standar + ' ' + row.target;
        if (row.satuan === '%') targetDisplay += '%';
        else targetDisplay += ' ' + row.satuan;

        // Numerator row
        let numRow = [row.judul, targetDisplay, 'Numerator'];
        months.forEach(function(m) {
            if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                numRow.push(row.monthly_data[m].numerator);
            } else {
                numRow.push('-');
            }
        });
        rows.push(numRow);

        // Denumerator row
        let denRow = ['', '', 'Denumerator'];
        months.forEach(function(m) {
            if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                denRow.push(row.monthly_data[m].denumerator);
            } else {
                denRow.push('-');
            }
        });
        rows.push(denRow);

        // Capaian row
        let capRow = ['', '', 'Capaian'];
        months.forEach(function(m) {
            if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                let displayVal = row.monthly_data[m].achievement;
                if (row.satuan === '%') displayVal += '%';
                capRow.push(displayVal);
            } else {
                capRow.push('-');
            }
        });
        rows.push(capRow);
    });

    return rows;
}

/**
 * Export to Excel using SheetJS
 */
function exportExcel() {
    if (!lastResponseData || !lastResponseData.data || lastResponseData.data.length === 0) {
        alert('Silakan tampilkan data terlebih dahulu sebelum export');
        return;
    }

    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const title = `Rekap ${lastResponseData.jenis_indikator} Tahun ${lastResponseData.year}`;

    // Build worksheet data
    const wsData = [];

    // Title row
    wsData.push([title]);
    wsData.push([]); // Empty row

    // Header row
    wsData.push(['Nama Indikator', 'Target', 'Data', ...monthNames]);

    // Data rows
    const dataRows = buildExportRows();
    dataRows.forEach(function(row) {
        wsData.push(row);
    });

    // Create workbook and worksheet
    const wb = XLSX.utils.book_new();
    const ws = XLSX.utils.aoa_to_sheet(wsData);

    // Set column widths
    ws['!cols'] = [
        { wch: 40 }, // Nama Indikator
        { wch: 15 }, // Target
        { wch: 14 }, // Data
        { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 },
        { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 },
        { wch: 10 }, { wch: 10 }, { wch: 10 }, { wch: 10 }
    ];

    // Merge title cell across all columns
    ws['!merges'] = [
        { s: { r: 0, c: 0 }, e: { r: 0, c: 14 } } // Title merge
    ];

    // Add merge cells for Nama Indikator and Target (each indicator spans 3 rows)
    const startDataRow = 3; // 0-indexed, after title, empty row, header
    for (let i = 0; i < lastResponseData.data.length; i++) {
        const rowIdx = startDataRow + (i * 3);
        // Merge Nama Indikator (column 0) across 3 rows
        ws['!merges'].push({ s: { r: rowIdx, c: 0 }, e: { r: rowIdx + 2, c: 0 } });
        // Merge Target (column 1) across 3 rows
        ws['!merges'].push({ s: { r: rowIdx, c: 1 }, e: { r: rowIdx + 2, c: 1 } });
    }

    const sheetName = lastResponseData.jenis_indikator.substring(0, 31);
    XLSX.utils.book_append_sheet(wb, ws, sheetName);

    // Generate filename
    const filename = `Rekap_${lastResponseData.jenis_indikator.replace(/[^a-zA-Z0-9]/g, '_')}_${lastResponseData.year}.xlsx`;
    XLSX.writeFile(wb, filename);
}

/**
 * Export to PDF using jsPDF + AutoTable — Premium Design
 */
function exportPDF() {
    if (!lastResponseData || !lastResponseData.data || lastResponseData.data.length === 0) {
        alert('Silakan tampilkan data terlebih dahulu sebelum export');
        return;
    }

    const { jsPDF } = window.jspdf;
    const doc = new jsPDF('l', 'mm', 'a4'); // Landscape for wide table

    const pageWidth = doc.internal.pageSize.getWidth();
    const pageHeight = doc.internal.pageSize.getHeight();
    const margin = 14;

    // ============ COLOR PALETTE ============
    const colors = {
        primary:      [26, 54, 93],      // Deep navy blue
        primaryLight: [41, 82, 138],      // Medium navy
        accent:       [52, 152, 219],     // Bright sky blue
        accentLight:  [174, 214, 241],    // Light sky blue
        headerBg:     [26, 54, 93],       // Deep navy for header
        headerText:   [255, 255, 255],    // White
        numBg:        [235, 245, 255],    // Very light blue for numerator rows
        denBg:        [245, 248, 255],    // Almost white blue for denumerator rows
        capBg:        [255, 251, 235],    // Warm cream for capaian rows
        successBg:    [212, 239, 223],    // Light green bg
        successText:  [30, 132, 73],      // Dark green text
        dangerBg:     [250, 219, 216],    // Light red bg
        dangerText:   [192, 57, 43],      // Dark red text
        darkText:     [44, 62, 80],       // Dark text
        mutedText:    [160, 170, 180],    // Muted/grey
        borderColor:  [189, 195, 199],    // Soft grey border
        goldAccent:   [243, 156, 18],     // Gold for decorative elements
        whiteText:    [255, 255, 255]
    };

    // ============ HEADER SECTION ============
    // Top decorative bar (gradient-like navy strip)
    doc.setFillColor(...colors.primary);
    doc.rect(0, 0, pageWidth, 8, 'F');

    // Accent gold line under navy bar
    doc.setFillColor(...colors.goldAccent);
    doc.rect(0, 8, pageWidth, 1.5, 'F');

    // Institution name
    doc.setFontSize(10);
    doc.setTextColor(...colors.mutedText);
    doc.text('RSUD dr. Soedirman Kebumen', pageWidth / 2, 17, { align: 'center' });

    // Title
    const title = `Rekap ${lastResponseData.jenis_indikator}`;
    doc.setFontSize(16);
    doc.setFont(undefined, 'bold');
    doc.setTextColor(...colors.primary);
    doc.text(title, pageWidth / 2, 24, { align: 'center' });

    // Subtitle (year)
    doc.setFontSize(11);
    doc.setFont(undefined, 'normal');
    doc.setTextColor(...colors.accent);
    doc.text(`Periode Tahun ${lastResponseData.year}`, pageWidth / 2, 30, { align: 'center' });

    // Decorative line under subtitle
    const lineY = 33;
    const lineLen = 60;
    doc.setDrawColor(...colors.accentLight);
    doc.setLineWidth(0.5);
    doc.line(pageWidth / 2 - lineLen / 2, lineY, pageWidth / 2 + lineLen / 2, lineY);
    // Small diamond in center of line
    const cx = pageWidth / 2;
    doc.setFillColor(...colors.accent);
    doc.setDrawColor(...colors.accent);
    const dSize = 1.5;
    doc.lines(
        [[dSize, dSize], [dSize, -dSize], [-dSize, -dSize], [-dSize, dSize]],
        cx - dSize, lineY, [1, 1], 'F'
    );

    const monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
    const months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];

    // ============ BUILD TABLE DATA ============
    const body = [];
    lastResponseData.data.forEach(function(row) {
        let targetDisplay = row.standar + ' ' + row.target;
        if (row.satuan === '%') targetDisplay += '%';
        else targetDisplay += ' ' + row.satuan;

        // Numerator row
        let numRow = [row.judul, targetDisplay, 'Numerator'];
        months.forEach(function(m) {
            if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                numRow.push(String(row.monthly_data[m].numerator));
            } else {
                numRow.push('-');
            }
        });
        body.push(numRow);

        // Denumerator row
        let denRow = ['', '', 'Denumerator'];
        months.forEach(function(m) {
            if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                denRow.push(String(row.monthly_data[m].denumerator));
            } else {
                denRow.push('-');
            }
        });
        body.push(denRow);

        // Capaian row
        let capRow = ['', '', 'Capaian'];
        months.forEach(function(m) {
            if (row.monthly_data[m] && row.monthly_data[m].denumerator > 0) {
                let displayVal = String(row.monthly_data[m].achievement);
                if (row.satuan === '%') displayVal += '%';
                capRow.push(displayVal);
            } else {
                capRow.push('-');
            }
        });
        body.push(capRow);
    });

    // ============ GENERATE TABLE ============
    doc.autoTable({
        startY: 38,
        head: [['Nama Indikator', 'Target', 'Data', ...monthNames]],
        body: body,
        theme: 'grid',
        styles: {
            fontSize: 7,
            cellPadding: { top: 2.5, right: 2, bottom: 2.5, left: 2 },
            lineColor: colors.borderColor,
            lineWidth: 0.2,
            halign: 'center',
            valign: 'middle',
            textColor: colors.darkText,
            font: 'helvetica'
        },
        headStyles: {
            fillColor: colors.headerBg,
            textColor: colors.headerText,
            fontStyle: 'bold',
            halign: 'center',
            fontSize: 7.5,
            cellPadding: { top: 3.5, right: 2, bottom: 3.5, left: 2 }
        },
        columnStyles: {
            0: { halign: 'left', cellWidth: 55 },  // Nama Indikator
            1: { halign: 'center', cellWidth: 20 }, // Target
            2: { halign: 'center', cellWidth: 20, fontStyle: 'bold' }  // Data type
        },
        didParseCell: function(data) {
            if (data.section !== 'body') return;
            const rowIdx = data.row.index;
            const rowType = rowIdx % 3; // 0=Numerator, 1=Denumerator, 2=Capaian

            // ---- Row background colors ----
            if (rowType === 0) {
                // Numerator rows: soft blue
                data.cell.styles.fillColor = colors.numBg;
            } else if (rowType === 1) {
                // Denumerator rows: lighter blue
                data.cell.styles.fillColor = colors.denBg;
            } else {
                // Capaian rows: warm cream
                data.cell.styles.fillColor = colors.capBg;
            }

            // ---- Nama Indikator column styling ----
            if (data.column.index === 0 && data.cell.raw !== '') {
                data.cell.styles.textColor = colors.primary;
                data.cell.styles.fontStyle = 'bold';
                data.cell.styles.fontSize = 7;
            }

            // ---- Target column styling ----
            if (data.column.index === 1 && data.cell.raw !== '') {
                data.cell.styles.textColor = colors.primaryLight;
                data.cell.styles.fontStyle = 'bold';
            }

            // ---- Data type label styling ----
            if (data.column.index === 2) {
                if (data.cell.raw === 'Capaian') {
                    data.cell.styles.textColor = colors.goldAccent;
                    data.cell.styles.fontStyle = 'bold';
                    data.cell.styles.fontSize = 7.5;
                } else if (data.cell.raw === 'Numerator') {
                    data.cell.styles.textColor = colors.accent;
                    data.cell.styles.fontStyle = 'bold';
                } else if (data.cell.raw === 'Denumerator') {
                    data.cell.styles.textColor = colors.primaryLight;
                    data.cell.styles.fontStyle = 'bold';
                }
            }

            // ---- Capaian value cells with colored backgrounds ----
            if (data.column.index >= 3 && rowType === 2) {
                if (data.cell.raw !== '-') {
                    const indicatorIdx = Math.floor(rowIdx / 3);
                    if (indicatorIdx < lastResponseData.data.length) {
                        const indicator = lastResponseData.data[indicatorIdx];
                        const achievementVal = parseFloat(data.cell.raw);
                        const targetVal = parseFloat(indicator.target.toString().replace(',', '.').replace('%', ''));
                        const standar = indicator.standar;

                        let isPassing = false;
                        if (standar === '>=') isPassing = achievementVal >= targetVal;
                        else if (standar === '>') isPassing = achievementVal > targetVal;
                        else if (standar === '<=') isPassing = achievementVal <= targetVal;
                        else if (standar === '<') isPassing = achievementVal < targetVal;
                        else isPassing = achievementVal >= targetVal;

                        if (isPassing) {
                            data.cell.styles.fillColor = colors.successBg;
                            data.cell.styles.textColor = colors.successText;
                            data.cell.styles.fontStyle = 'bold';
                        } else {
                            data.cell.styles.fillColor = colors.dangerBg;
                            data.cell.styles.textColor = colors.dangerText;
                            data.cell.styles.fontStyle = 'bold';
                        }
                    }
                } else {
                    // Dash cells on capaian row
                    data.cell.styles.textColor = colors.mutedText;
                }
            }

            // ---- Muted dash values on non-capaian rows ----
            if (data.column.index >= 3 && rowType !== 2 && data.cell.raw === '-') {
                data.cell.styles.textColor = colors.mutedText;
            }
        },
        // ============ PAGE HEADER & FOOTER ============
        didDrawPage: function(data) {
            // Top bar on every page
            doc.setFillColor(...colors.primary);
            doc.rect(0, 0, pageWidth, 8, 'F');
            doc.setFillColor(...colors.goldAccent);
            doc.rect(0, 8, pageWidth, 1.5, 'F');

            // Only draw full header on first page
            if (data.pageNumber === 1) {
                // Header already drawn above
            } else {
                // Compact header for subsequent pages
                doc.setFontSize(10);
                doc.setFont(undefined, 'bold');
                doc.setTextColor(...colors.primary);
                doc.text(title, pageWidth / 2, 15, { align: 'center' });
                doc.setFontSize(8);
                doc.setFont(undefined, 'normal');
                doc.setTextColor(...colors.mutedText);
                doc.text(`Periode Tahun ${lastResponseData.year}`, pageWidth / 2, 19, { align: 'center' });
            }

            // Bottom decorative line
            doc.setDrawColor(...colors.accentLight);
            doc.setLineWidth(0.5);
            doc.line(margin, pageHeight - 14, pageWidth - margin, pageHeight - 14);

            // Footer text
            doc.setFontSize(7);
            doc.setFont(undefined, 'italic');
            doc.setTextColor(...colors.mutedText);
            const now = new Date();
            const dateStr = now.toLocaleDateString('id-ID', { day: '2-digit', month: 'long', year: 'numeric' });
            const timeStr = now.toLocaleTimeString('id-ID', { hour: '2-digit', minute: '2-digit' });
            doc.text(`Dicetak pada: ${dateStr}, ${timeStr} WIB`, margin, pageHeight - 9);
            doc.text('IMUT RSDS — Sistem Indikator Mutu', pageWidth / 2, pageHeight - 9, { align: 'center' });
            doc.text(`Halaman ${data.pageNumber}`, pageWidth - margin, pageHeight - 9, { align: 'right' });
        },
        margin: { top: 38, bottom: 20, left: margin, right: margin },
        tableWidth: 'auto'
    });

    // ============ LEGEND ============
    const finalY = doc.lastAutoTable.finalY || 38;
    if (finalY + 20 < pageHeight - 25) {
        const legendY = finalY + 8;
        doc.setFontSize(7.5);
        doc.setFont(undefined, 'bold');
        doc.setTextColor(...colors.darkText);
        doc.text('Keterangan:', margin, legendY);

        // Green legend
        doc.setFillColor(...colors.successBg);
        doc.roundedRect(margin, legendY + 2, 8, 4, 1, 1, 'F');
        doc.setFontSize(7);
        doc.setFont(undefined, 'normal');
        doc.setTextColor(...colors.successText);
        doc.text('Memenuhi Target', margin + 10, legendY + 5);

        // Red legend
        doc.setFillColor(...colors.dangerBg);
        doc.roundedRect(margin + 50, legendY + 2, 8, 4, 1, 1, 'F');
        doc.setTextColor(...colors.dangerText);
        doc.text('Tidak Memenuhi Target', margin + 60, legendY + 5);
    }

    // Generate filename
    const filename = `Rekap_${lastResponseData.jenis_indikator.replace(/[^a-zA-Z0-9]/g, '_')}_${lastResponseData.year}.pdf`;
    doc.save(filename);
}
</script>
<?= $this->endSection() ?>
