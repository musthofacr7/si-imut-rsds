<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Rekap Indikator Mutu RS
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Rekap Indikator Mutu Rumah Sakit</h3>
        <div class="card-tools">
            <button class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel"></i> Export Excel
            </button>
            <button class="btn btn-danger btn-sm">
                <i class="bi bi-file-earmark-pdf"></i> Export PDF
            </button>
        </div>
    </div>
    <div class="card-body">
        <!-- Filter Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <h5 class="mb-3">Filter Grafik</h5>
                <form id="filterForm" class="row g-3">
                    <div class="col-md-3">
                        <label for="area_filter" class="form-label">Area Pengukuran</label>
                        <select class="form-select select2" id="area_filter" name="area_id">
                            <option value="">Semua Area (Gabungan)</option>
                            <?php foreach ($area_pengukuran as $area) : ?>
                                <option value="<?= $area['id'] ?>"><?= esc($area['area_pengukuran']) ?></option>
                            <?php endforeach ?>
                        </select>
                        <small class="text-muted">Kosongkan untuk melihat gabungan semua area</small>
                    </div>
                    <div class="col-md-4">
                        <label for="indikator_filter" class="form-label">Nama Indikator</label>
                        <select class="form-select select2" id="indikator_filter" name="indikator_id" required>
                            <option value="">Pilih Indikator</option>
                            <?php foreach ($indikator_mutu as $indikator) : ?>
                                <option value="<?= $indikator['id'] ?>"><?= esc($indikator['judul_indikator']) ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-md-2">
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
                            <i class="bi bi-bar-chart-fill"></i> Tampilkan Grafik
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Chart Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0" id="chartTitle">Grafik Pencapaian Indikator Mutu</h5>
                    </div>
                    <div class="card-body">
                        <div id="chartLoading" class="text-center py-5" style="display: none;">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat data grafik...</p>
                        </div>
                        <div id="chartContainer">
                            <canvas id="achievementChart" height="80"></canvas>
                        </div>
                        <div id="noDataMessage" class="alert alert-info text-center" style="display: none;">
                            <i class="bi bi-info-circle"></i> Silakan pilih filter dan klik "Tampilkan Grafik" untuk melihat data.
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Data Table Section -->
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">Detail Data Bulanan</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm" id="monthlyDataTable">
                                <thead class="table-light text-center align-middle">
                                    <tr>
                                        <th rowspan="2" style="min-width: 150px; vertical-align: middle;">Area Pengukuran</th>
                                        <th rowspan="2" style="min-width: 100px; vertical-align: middle;">Data</th>
                                        <th colspan="12">Bulan</th>
                                    </tr>
                                    <tr>
                                        <?php 
                                        $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
                                        foreach ($months as $m) : ?>
                                            <th style="min-width: 80px;"><?= $m ?></th>
                                        <?php endforeach ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td colspan="14" class="text-center text-muted">Silakan pilih filter dan klik "Tampilkan Grafik" untuk melihat data.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</div>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>

<script>
let achievementChart = null;

$(document).ready(function() {
    // Initialize chart with empty data
    initChart();
    
    // Show initial message
    $('#chartContainer').hide();
    $('#noDataMessage').show();

    // Handle form submission
    $('#filterForm').on('submit', function(e) {
        e.preventDefault();
        
        const areaId = $('#area_filter').val(); // Can be empty for combined data
        const indikatorId = $('#indikator_filter').val();
        const year = $('#year_filter').val();

        // Only indikator and year are required, area is optional
        if (!indikatorId || !year) {
            alert('Silakan pilih Indikator dan Tahun');
            return;
        }

        loadChartData(indikatorId, areaId, year);
    });
});

function initChart() {
    const ctx = document.getElementById('achievementChart').getContext('2d');
    
    // Register the plugin
    Chart.register(ChartDataLabels);

    achievementChart = new Chart(ctx, {
        type: 'bar',
        data: {
            labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Oct', 'Nov', 'Des'],
            datasets: [{
                label: 'Pencapaian (%)',
                data: [],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                order: 2
            }, {
                label: 'Target',
                data: [],
                type: 'line',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 2,
                borderDash: [5, 5],
                pointRadius: 0,
                fill: false,
                order: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 100,
                    ticks: {
                        callback: function(value) {
                            return value + '%';
                        }
                    },
                    title: {
                        display: true,
                        text: 'Persentase Pencapaian'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Bulan'
                    }
                }
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top'
                },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = 'Pencapaian: ' + context.parsed.y;
                                // We need to access the unit from somewhere. 
                                // Since initChart is called once, we might need to store the unit globally or update options dynamically.
                                // For now, let's use a placeholder or check if we can access the chart options.
                                // Better approach: update options in loadChartData.
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value, context) {
                            if (context.datasetIndex === 0) {
                                return value;
                            }
                            return ''; 
                        },
                        font: {
                            weight: 'bold'
                        },
                        color: '#333'
                    }
                }
            }
    });
}

function loadChartData(indikatorId, areaId, year) {
    // Show loading
    $('#chartLoading').show();
    $('#chartContainer').hide();
    $('#noDataMessage').hide();

    $.ajax({
        url: '<?= base_url('rekap-indikator-mutu/get-chart-data') ?>',
        type: 'POST',
        data: {
            indikator_id: indikatorId,
            area_id: areaId,
            year: year,
            <?= csrf_token() ?>: '<?= csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            $('#chartLoading').hide();
            
            if (response.success) {
                // Update chart title
                $('#chartTitle').text(`Grafik Pencapaian: ${response.title} - ${response.area} (${response.year})`);
                
                // Update chart data
                achievementChart.data.labels = response.labels;
                achievementChart.data.datasets[0].data = response.data;
                
                // Create array of target values (same value for all months)
                const targetData = Array(12).fill(response.target);
                achievementChart.data.datasets[1].data = targetData;
                
                achievementChart.data.datasets[1].data = targetData;
                
                // Update Chart Options based on Unit
                const satuan = response.satuan || '%';
                
                // Update Y-axis
                achievementChart.options.scales.y.max = satuan === '%' ? 100 : undefined;
                achievementChart.options.scales.y.ticks.callback = function(value) {
                    return satuan === '%' ? value + '%' : value;
                };
                achievementChart.options.scales.y.title.text = 'Pencapaian (' + satuan + ')';
                
                // Update Tooltip
                achievementChart.options.plugins.tooltip.callbacks.label = function(context) {
                    let label = 'Pencapaian: ' + context.parsed.y;
                    if (satuan === '%') {
                        label = 'Pencapaian: ' + context.parsed.y.toFixed(2) + '%';
                    } else {
                        label = 'Pencapaian: ' + context.parsed.y + ' ' + satuan;
                    }
                    return label;
                };
                
                // Update Data Labels
                achievementChart.options.plugins.datalabels.formatter = function(value, context) {
                    if (context.datasetIndex === 0) {
                        if (satuan === '%') {
                            return value.toFixed(2) + '%';
                        } else {
                            return value + ' ' + satuan;
                        }
                    }
                    return '';
                };
                
                achievementChart.data.datasets[0].label = 'Pencapaian (' + satuan + ')';

                achievementChart.update();
                
                $('#chartContainer').show();

                // Update Data Table (Detailed Matrix Format)
                let tableBody = '';
                
                // Iterate through each area
                for (const [areaName, months] of Object.entries(response.area_data)) {
                    // Row 1: Numerator (with Area Name rowspan)
                    tableBody += `<tr>`;
                    tableBody += `<td rowspan="3" class="fw-bold align-middle bg-light">${areaName}</td>`;
                    tableBody += `<td class="fw-bold">Numerator</td>`;
                    
                    for (let i = 1; i <= 12; i++) {
                        const monthKey = i.toString().padStart(2, '0');
                        tableBody += `<td class="text-center">${months[monthKey].numerator}</td>`;
                    }
                    tableBody += `</tr>`;
                    
                    // Row 2: Denumerator
                    tableBody += `<tr>`;
                    tableBody += `<td class="fw-bold">Denumerator</td>`;
                    
                    for (let i = 1; i <= 12; i++) {
                        const monthKey = i.toString().padStart(2, '0');
                        tableBody += `<td class="text-center">${months[monthKey].denumerator}</td>`;
                    }
                    tableBody += `</tr>`;
                    
                    // Row 3: Achievement
                    tableBody += `<tr>`;
                    tableBody += `<td class="fw-bold text-primary">Capaian (%)</td>`;
                    
                    for (let i = 1; i <= 12; i++) {
                        const monthKey = i.toString().padStart(2, '0');
                        const data = months[monthKey];
                        let badgeClass = 'bg-secondary';
                        
                        // Simple color coding
                        if (data.denumerator > 0) {
                            if (data.achievement >= 80) badgeClass = 'bg-success';
                            else if (data.achievement >= 50) badgeClass = 'bg-warning text-dark';
                            else badgeClass = 'bg-danger';
                        }
                        
                        tableBody += `<td class="text-center">
                            <span class="badge ${badgeClass}">${data.achievement}%</span>
                        </td>`;
                    }
                    tableBody += `</tr>`;
                }
                
                if (Object.keys(response.area_data).length === 0) {
                    tableBody = '<tr><td colspan="14" class="text-center">Tidak ada data untuk filter yang dipilih</td></tr>';
                }
                
                $('#monthlyDataTable tbody').html(tableBody);
            } else {
                alert(response.message || 'Gagal memuat data grafik');
                $('#noDataMessage').show();
            }
        },
        error: function(xhr, status, error) {
            $('#chartLoading').hide();
            $('#noDataMessage').show();
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memuat data grafik');
        }
    });
}
</script>
<?= $this->endSection() ?>
