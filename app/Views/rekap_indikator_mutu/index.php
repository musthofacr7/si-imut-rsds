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
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <h5 class="card-title mb-0" id="chartTitle">Grafik Pencapaian Indikator Mutu</h5>
                            <div id="standarCapaianInfo" class="d-none text-end">
                                <span class="text-muted small me-1">Standar Capaian:</span>
                                <span id="standarBadge" class="fw-semibold"></span>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Toolbar Tombol -->
                        <div class="d-flex flex-wrap gap-2 mb-3">
                            <div>
                                <span class="me-1 fw-semibold small text-muted">Tampilan Trend:</span>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" name="trendType" id="trendOff" value="off" checked>
                                    <label class="btn btn-outline-secondary" for="trendOff">
                                        <i class="bi bi-x-circle"></i> Trend Off
                                    </label>
                                    <input type="radio" class="btn-check" name="trendType" id="trend3" value="3">
                                    <label class="btn btn-outline-success" for="trend3">
                                        <i class="bi bi-graph-up-arrow"></i> 3 Bulanan
                                    </label>
                                    <input type="radio" class="btn-check" name="trendType" id="trend6" value="6">
                                    <label class="btn btn-outline-success" for="trend6">
                                        <i class="bi bi-graph-up-arrow"></i> 6 Bulanan
                                    </label>
                                </div>
                            </div>
                            <div>
                                <span class="me-1 fw-semibold small text-muted">Tipe Grafik:</span>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" name="chartType" id="typeBar" value="bar" checked>
                                    <label class="btn btn-outline-primary" for="typeBar">
                                        <i class="bi bi-bar-chart-fill"></i> Bar
                                    </label>
                                    <input type="radio" class="btn-check" name="chartType" id="typeLine" value="line">
                                    <label class="btn btn-outline-primary" for="typeLine">
                                        <i class="bi bi-graph-up"></i> Line
                                    </label>
                                </div>
                            </div>
                        </div>

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
                                        <th rowspan="2" style="min-width: 90px; vertical-align: middle;">Standar</th>
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
                                        <td colspan="15" class="text-center text-muted">Silakan pilih filter dan klik "Tampilkan Grafik" untuk melihat data.</td>
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
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@3.0.1/dist/chartjs-plugin-annotation.min.js"></script>

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

    // Handle Chart Type Change
    $('input[name="chartType"]').change(function() {
        const type = $(this).val();
        if (achievementChart) {
            achievementChart.config.type = type;
            achievementChart.update();
        }
    });

    // Handle Trend Type Change
    $('input[name="trendType"]').change(function() {
        updateTrendAnnotations();
    });
});

function updateTrendAnnotations() {
    if (!achievementChart) return;
    
    const trendType = $('input[name="trendType"]:checked').val();
    let annotations = {};
    
    if (trendType === 'off') {
        achievementChart.options.plugins.annotation.annotations = annotations;
        achievementChart.update();
        return;
    }

    const data = achievementChart.data.datasets[0].data; // Monthly data (index 0-11)

    // Helper: get first non-null value in range [start, end] (inclusive)
    function firstVal(start, end) {
        for (let i = start; i <= end; i++) {
            if (data[i] !== null && data[i] !== undefined) return { idx: i, val: parseFloat(data[i]) };
        }
        return null;
    }
    // Helper: get last non-null value in range [start, end] (inclusive)
    function lastVal(start, end) {
        for (let i = end; i >= start; i--) {
            if (data[i] !== null && data[i] !== undefined) return { idx: i, val: parseFloat(data[i]) };
        }
        return null;
    }

    // Boundary line style (jelas, solid putih dengan garis hitam tipis di belakang)
    function makeBoundary(x, key) {
        // Shadow hitam tipis agar terlihat di background apapun
        annotations[key + '_bg'] = {
            type: 'line',
            xMin: x, xMax: x,
            borderColor: 'rgba(0,0,0,0.4)',
            borderWidth: 3,
            drawTime: 'beforeDatasetsDraw'
        };
        // Garis putus-putus putih di atas
        annotations[key] = {
            type: 'line',
            xMin: x, xMax: x,
            borderColor: 'rgba(255,255,255,0.95)',
            borderWidth: 2,
            borderDash: [6, 4],
            drawTime: 'afterDatasetsDraw'
        };
    }

    // Arrow style: di dalam periode, dari nilai bulan pertama ke bulan terakhir
    function makeArrow(startIdx, endIdx, key) {
        const p1 = firstVal(startIdx, endIdx);
        const p2 = lastVal(startIdx, endIdx);
        if (!p1 || !p2 || p1.idx === p2.idx) return;

        const y1 = p1.val;
        const y2 = p2.val;
        let color;
        if (y2 > y1)       color = 'rgba(25,135,84,0.80)';    // hijau - naik
        else if (y2 < y1)  color = 'rgba(220,53,69,0.80)';    // merah - turun
        else               color = 'rgba(108,117,125,0.65)';  // abu - stabil

        annotations[key] = {
            type: 'line',
            xMin: p1.idx,
            yMin: y1,
            xMax: p2.idx,
            yMax: y2,
            borderColor: color,
            borderWidth: 1.5,
            arrowHeads: {
                end: {
                    display: true,
                    fill: true,
                    length: 5,
                    width: 5
                }
            },
            drawTime: 'afterDatasetsDraw'
        };
    }

    if (trendType === '3') {
        // 4 Periode: Jan-Mar (0-2), Apr-Jun (3-5), Jul-Sep (6-8), Okt-Des (9-11)
        // Batas: antara Mar/Apr (2.5), Jun/Jul (5.5), Sep/Okt (8.5)
        makeBoundary(2.5, 'lineV0');
        makeBoundary(5.5, 'lineV1');
        makeBoundary(8.5, 'lineV2');

        // Panah di dalam tiap periode
        makeArrow(0, 2,  'arrow_p1');
        makeArrow(3, 5,  'arrow_p2');
        makeArrow(6, 8,  'arrow_p3');
        makeArrow(9, 11, 'arrow_p4');

    } else if (trendType === '6') {
        // 2 Periode: Jan-Jun (0-5), Jul-Des (6-11)
        // Batas: antara Jun/Jul (5.5)
        makeBoundary(5.5, 'lineV0');

        // Panah di dalam tiap periode
        makeArrow(0, 5,  'arrow_p1');
        makeArrow(6, 11, 'arrow_p2');
    }

    achievementChart.options.plugins.annotation.annotations = annotations;
    achievementChart.update();
}

function initChart() {
    const ctx = document.getElementById('achievementChart').getContext('2d');
    
    // Register the plugin
    Chart.register(ChartDataLabels);
    if (typeof window['chartjs-plugin-annotation'] !== 'undefined') {
        Chart.register(window['chartjs-plugin-annotation']);
    }

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
                annotation: {
                    annotations: {}
                },
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

                // Update standar capaian di card-header
                const standar = response.standar || '';
                const standarSymbols = { '>': '>', '<': '<', '>=': '≥', '<=': '≤' };
                const targetRaw = response.target_raw || response.target;
                const satuanLabel = response.satuan || '%';
                const sym = standarSymbols[standar] || '';

                const badgeText = sym
                    ? `${sym} ${targetRaw} ${satuanLabel}`
                    : `${targetRaw} ${satuanLabel}`;

                $('#standarBadge').text(badgeText).removeClass().addClass('fw-semibold');
                $('#standarCapaianInfo').removeClass('d-none');

                // Update chart data
                achievementChart.data.labels = response.labels;
                achievementChart.data.datasets[0].data = response.data;
                
                // Create array of target values (same value for all months)
                const targetData = Array(12).fill(response.target);
                achievementChart.data.datasets[1].data = targetData;
                
                achievementChart.data.datasets[1].data = targetData;
                
                // Update Chart Options based on Unit
                const satuan = response.satuan || '%';
                
                // Calculate dynamic Y-axis max based on target and max achievement
                const maxAchievement = Math.max(...response.data.filter(v => v !== null && v !== undefined), 0);
                const targetValue = response.target || 0;
                
                let dynamicMax;
                if (satuan === '%') {
                    // Batas atas berdasarkan range target
                    let minMax;
                    if (targetValue < 2) {
                        minMax = 2; // Standar < 2%, maksimal grafik 2%
                    } else if (targetValue < 5) {
                        minMax = 5; // Standar < 5%, maksimal grafik 5%
                    } else {
                        minMax = targetValue * 1.2; // Standar >= 5%, gunakan 1.2 × target
                    }
                    dynamicMax = Math.max(minMax, maxAchievement * 1.1);
                    dynamicMax = Math.ceil(dynamicMax / (targetValue < 2 ? 0.5 : 5)) * (targetValue < 2 ? 0.5 : 5); // Bulatkan
                } else {
                    // Untuk satuan non-persen
                    dynamicMax = Math.max(targetValue * 1.2, maxAchievement * 1.1);
                    dynamicMax = Math.ceil(dynamicMax);
                }
                
                // Update Y-axis
                achievementChart.options.scales.y.max = dynamicMax;
                achievementChart.options.scales.y.ticks.callback = function(value) {
                    return satuan === '%' ? value + '%' : value;
                };
                achievementChart.options.scales.y.title.text = 'Pencapaian (' + satuan + ')';
                
                // Update Tooltip
                achievementChart.options.plugins.tooltip.callbacks.label = function(context) {
                    let label = 'Pencapaian: ' + context.parsed.y;
                    if (satuan === '%') {
                        label = 'Pencapaian: ' + context.parsed.y.toFixed(2) + '%';
                    } else if (satuan === '‰' || satuan === 'Permil (‰)') {
                        label = 'Pencapaian: ' + context.parsed.y.toFixed(2) + '‰';
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
                        } else if (satuan === '‰' || satuan === 'Permil (‰)') {
                            return value.toFixed(2) + '‰';
                        } else {
                            return value + ' ' + satuan;
                        }
                    }
                    return '';
                };
                
                achievementChart.data.datasets[0].label = 'Pencapaian (' + satuan + ')';

                achievementChart.update();
                
                // Update trend annotations if active
                updateTrendAnnotations();
                
                $('#chartContainer').show();

                // Update Data Table (Detailed Matrix Format)
                let tableBody = '';
                
                const standarSym = response.standar || '';
                const tgtVal = parseFloat(response.target) || 0;

                // Helper: cek apakah capaian memenuhi standar
                function meetStandar(achievement) {
                    const val = parseFloat(achievement);
                    if (isNaN(val)) return null;
                    if (standarSym === '>=') return val >= tgtVal;
                    if (standarSym === '<=') return val <= tgtVal;
                    if (standarSym === '>')  return val >  tgtVal;
                    if (standarSym === '<')  return val <  tgtVal;
                    return null;
                }

                // Iterate through each area
                for (const [areaName, months] of Object.entries(response.area_data)) {
                    const standarSymDisplay = { '>=': '≥', '<=': '≤', '>': '>', '<': '<' }[standarSym] || '';
                    const satuanCompact = satuan === 'Permil (‰)' ? '‰' : satuan;
                    const standarCellText = tgtVal
                        ? `<span class="fw-semibold">${standarSymDisplay}${tgtVal}${satuanCompact}</span>`
                        : `-`;

                    // Row 1: Numerator (with Area Name rowspan=3, Standar rowspan=3)
                    tableBody += `<tr>`;
                    tableBody += `<td rowspan="3" class="fw-bold align-middle bg-light">${areaName}</td>`;
                    tableBody += `<td rowspan="3" class="text-center align-middle">${standarCellText}</td>`;
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
                    const satuanLabel = satuan === '%' ? '%' : `(${satuan})`;
                    tableBody += `<tr>`;
                    tableBody += `<td class="fw-bold text-primary">Capaian ${satuanLabel}</td>`;
                    
                    for (let i = 1; i <= 12; i++) {
                        const monthKey = i.toString().padStart(2, '0');
                        const data = months[monthKey];
                        let badgeClass = 'bg-secondary';
                        let displayValue = data.achievement;
                        
                        if (data.denumerator > 0) {
                            if (satuan === '%') {
                                // Logic for Percentage
                                if (data.achievement >= 80) badgeClass = 'bg-success';
                                else if (data.achievement >= 50) badgeClass = 'bg-warning text-dark';
                                else badgeClass = 'bg-danger';
                                
                                displayValue += '%';
                            } else if (satuan === '‰' || satuan === 'Permil (‰)') {
                                // Logic for Permil - show 2 decimal places
                                badgeClass = 'bg-info text-dark';
                                displayValue = parseFloat(data.achievement).toFixed(2) + '‰';
                            } else {
                                // Logic for Non-Percentage (use neutral or check against target if needed)
                                // For now, just use a neutral color or info
                                badgeClass = 'bg-info text-dark';
                                displayValue += ' ' + satuan;
                            }
                        }
                        
                        tableBody += `<td class="text-center">
                            <span class="badge ${badgeClass}">${displayValue}</span>
                        </td>`;
                    }
                    tableBody += `</tr>`;
                }
                
                if (Object.keys(response.area_data).length === 0) {
                    tableBody = '<tr><td colspan="15" class="text-center">Tidak ada data untuk filter yang dipilih</td></tr>';
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
