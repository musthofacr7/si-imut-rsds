<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Rekap by Jenis Indikator
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Rekap Indikator Mutu by Jenis</h3>
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
        
        const jenisId = $('#jenis_indikator_filter').val();
        const year = $('#year_filter').val();

        if (!jenisId || !year) {
            alert('Silakan pilih Jenis Indikator dan Tahun');
            return;
        }

        loadData(jenisId, year);
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
                $('#tableTitle').text(`Rekap ${response.jenis_indikator} Tahun ${response.year}`);
                
                let tbody = '';
                
                if (response.data.length === 0) {
                    tbody = '<tr><td colspan="14" class="text-center">Tidak ada data indikator untuk jenis ini</td></tr>';
                } else {
                    response.data.forEach(function(row) {
                        tbody += '<tr>';
                        // Indicator Name and Target
                        tbody += `<td>${row.judul}</td>`;
                        
                        let targetDisplay = row.standar + ' ' + row.target;
                        if (row.satuan === '%') targetDisplay += '%';
                        else targetDisplay += ' ' + row.satuan;
                        
                        tbody += `<td class="text-center fw-bold">${targetDisplay}</td>`;
                        
                        // Monthly Data
                        const months = ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12'];
                        
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
    // Clean target value (remove % and commas)
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
    // Basic Excel Export using table2excel or similar library could be added here
    // For now just alert
    alert('Fitur Export Excel akan segera hadir');
}
</script>
<?= $this->endSection() ?>
