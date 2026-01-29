<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Rekap by Area Pengukuran
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Rekap Indikator Mutu by Area Pengukuran</h3>
        <div class="card-tools">
            <button class="btn btn-success btn-sm" onclick="exportExcel()">
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
                        <label for="area_filter" class="form-label">Area Pengukuran</label>
                        <select class="form-select select2" id="area_filter" name="area_id" required>
                            <option value="">Pilih Area Pengukuran</option>
                            <?php foreach ($area_pengukuran as $area) : ?>
                                <option value="<?= $area['id'] ?>"><?= esc($area['area_pengukuran']) ?></option>
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
                                <th rowspan="2" style="min-width: 150px;">Jenis</th>
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
$(document).ready(function() {
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        
        const areaId = $('#area_filter').val();
        const year = $('#year_filter').val();

        if (!areaId || !year) {
            alert('Silakan pilih Area dan Tahun');
            return;
        }

        loadData(areaId, year);
    });
});

function loadData(areaId, year) {
    $('#loading').show();
    $('#tableContainer').hide();
    $('#noDataMessage').hide();

    $.ajax({
        url: '<?= base_url('rekap-area-pengukuran/get-data') ?>',
        type: 'POST',
        data: {
            area_id: areaId,
            year: year,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            $('#loading').hide();
            
            if (response.success) {
                $('#tableTitle').text(`Rekap Area Pengukuran: ${response.area_pengukuran} Tahun ${response.year}`);
                
                let tbody = '';
                
                if (response.data.length === 0) {
                    tbody = '<tr><td colspan="15" class="text-center">Tidak ada data indikator</td></tr>';
                } else {
                    response.data.forEach(function(row) {
                        const months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                        
                        // Row 1: Numerator
                        tbody += '<tr>';
                        tbody += `<td rowspan="3" class="align-middle bg-white">${row.judul}</td>`;
                        tbody += `<td rowspan="3" class="align-middle bg-white"><small>${row.jenis}</small></td>`;
                        
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
    // Simple logic for badge color based on target
    target = parseFloat(target.toString().replace(',', '.').replace('%', ''));
    achievement = parseFloat(achievement);

    let isPassing = false;
    
    if (standar === '>=') isPassing = achievement >= target;
    else if (standar === '>') isPassing = achievement > target;
    else if (standar === '<=') isPassing = achievement <= target;
    else if (standar === '<') isPassing = achievement < target;
    else isPassing = achievement >= target; // Default

    return isPassing ? 'bg-success' : 'bg-danger';
}

function exportExcel() {
    alert('Fitur Export Excel akan segera hadir');
}
</script>
<?= $this->endSection() ?>
