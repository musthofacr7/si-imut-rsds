<?= $this->extend('layout/main') ?>

<?= $this->section('title') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<div class="row">
    <div class="col-md-12">
        <div class="card mb-4">
            <div class="card-header">
                <h3 class="card-title">Selamat Datang</h3>
            </div>
            <div class="card-body">
                <p class="lead">Anda login sebagai <strong><?= user()->username ?></strong> (<?= user()->email ?>)</p>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($chartData)): ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="row align-items-center">
                    <div class="col-md-5">
                        <h3 class="card-title mb-0">Capaian Indikator Mutu Tahun <?= $selectedYear ?></h3>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="btn-group btn-group-sm" role="group">
                            <button type="button" class="btn btn-outline-danger" id="btnExportPDF" title="Export PDF">
                                <i class="bi bi-file-pdf"></i> PDF
                            </button>
                            <button type="button" class="btn btn-outline-primary" id="btnExportPNG" title="Export PNG">
                                <i class="bi bi-image"></i> PNG
                            </button>
                            <button type="button" class="btn btn-outline-success" id="btnExportExcel" title="Export Excel">
                                <i class="bi bi-file-excel"></i> Excel
                            </button>
                        </div>
                    </div>
                    <div class="col-md-3 text-end">
                        <form method="GET" action="<?= base_url('dashboard') ?>" class="d-inline">
                            <div class="input-group input-group-sm d-inline-flex" style="min-width: 100;">
                                <select name="year" class="form-select form-select-sm select2" onchange="this.form.submit()">
                                    <?php for ($y = date('Y'); $y >= date('Y') - 5; $y--): ?>
                                        <option value="<?= $y ?>" <?= $selectedYear == $y ? 'selected' : '' ?>><?= $y ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <?php foreach ($chartData as $index => $chart): ?>
                <div class="mb-4">
                    <h5><?= esc($chart['label']) ?></h5>
                    <div style="height: 250px; position: relative;">
                        <canvas id="chart-<?= $index ?>"></canvas>
                    </div>
                </div>
                <?php if ($index < count($chartData) - 1): ?>
                <hr>
                <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php else: ?>
<div class="row">
    <div class="col-md-12">
        <div class="alert alert-info">
            <i class="bi bi-info-circle"></i> Belum ada data indikator mutu untuk ditampilkan.
        </div>
    </div>
</div>
<?php endif; ?>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<!-- Chart.js -->
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<!-- jsPDF for PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<!-- html2canvas for PNG export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<!-- SheetJS for Excel export -->
<script src="https://cdn.sheetjs.com/xlsx-0.20.1/package/dist/xlsx.full.min.js"></script>
<script>
<?php if (!empty($chartData)): ?>
const monthLabels = <?= json_encode($monthLabels) ?>;
const chartData = <?= json_encode($chartData) ?>;
const selectedYear = '<?= $selectedYear ?>';
const chartInstances = [];

// Color palette for charts
const colors = [
    'rgba(54, 162, 235, 0.8)',
    'rgba(255, 99, 132, 0.8)',
    'rgba(75, 192, 192, 0.8)',
    'rgba(255, 206, 86, 0.8)',
    'rgba(153, 102, 255, 0.8)',
    'rgba(255, 159, 64, 0.8)'
];


// Export to PNG
document.getElementById('btnExportPNG')?.addEventListener('click', function() {
    let chartIndex = 0;
    
    const exportNextChart = () => {
        if (chartIndex >= chartInstances.length) {
            return;
        }
        
        const chart = chartInstances[chartIndex];
        const canvas = chart.canvas;
        const chartContainer = canvas.closest('.mb-4');
        const titleElement = chartContainer.querySelector('h5');
        const indicatorTitle = titleElement ? titleElement.textContent : chartData[chartIndex].label;
        
        html2canvas(chartContainer, {
            scale: 2,
            backgroundColor: '#ffffff'
        }).then(canvasImg => {
            const link = document.createElement('a');
            // Sanitize filename
            const sanitizedTitle = indicatorTitle.replace(/[^a-z0-9]/gi, '_').substring(0, 50);
            link.download = `${sanitizedTitle}_${selectedYear}.png`;
            link.href = canvasImg.toDataURL();
            link.click();
            
            chartIndex++;
            // Small delay between downloads
            setTimeout(exportNextChart, 300);
        });
    };
    
    exportNextChart();
});


// Export to PDF
document.getElementById('btnExportPDF')?.addEventListener('click', function() {
    const { jsPDF } = window.jspdf;
    const pdf = new jsPDF('p', 'mm', 'a4');
    const pageWidth = pdf.internal.pageSize.getWidth();
    const pageHeight = pdf.internal.pageSize.getHeight();
    let yPosition = 20;
    
    // Add main title
    pdf.setFontSize(16);
    pdf.text(`Capaian Indikator Mutu Tahun ${selectedYear}`, pageWidth / 2, yPosition, { align: 'center' });
    yPosition += 15;
    
    let chartIndex = 0;
    const addChartToPDF = () => {
        if (chartIndex >= chartInstances.length) {
            pdf.save(`Capaian_Indikator_Mutu_${selectedYear}.pdf`);
            return;
        }
        
        const chart = chartInstances[chartIndex];
        const canvas = chart.canvas;
        const chartContainer = canvas.closest('.mb-4');
        const titleElement = chartContainer.querySelector('h5');
        const indicatorTitle = titleElement ? titleElement.textContent : chartData[chartIndex].label;
        
        // Capture the entire chart container (including title)
        html2canvas(chartContainer, { 
            scale: 2,
            backgroundColor: '#ffffff'
        }).then(canvasImg => {
            const imgData = canvasImg.toDataURL('image/png');
            const imgWidth = pageWidth - 20;
            const imgHeight = (canvasImg.height * imgWidth) / canvasImg.width;
            
            // Check if we need a new page
            if (yPosition + imgHeight > pageHeight - 20) {
                pdf.addPage();
                yPosition = 20;
            }
            
            pdf.addImage(imgData, 'PNG', 10, yPosition, imgWidth, imgHeight);
            yPosition += imgHeight + 10;
            chartIndex++;
            addChartToPDF();
        });
    };
    
    addChartToPDF();
});

// Export to Excel
document.getElementById('btnExportExcel')?.addEventListener('click', async function() {
    const wb = XLSX.utils.book_new();
    
    // Process each chart
    for (let index = 0; index < chartData.length; index++) {
        const chart = chartData[index];
        const chartInstance = chartInstances[index];
        
        // Create worksheet data
        const wsData = [
            ['Bulan', 'Capaian (%)'],
            ...monthLabels.map((month, i) => [month, chart.data[i]])
        ];
        
        const ws = XLSX.utils.aoa_to_sheet(wsData);
        
        // Set column widths
        ws['!cols'] = [
            { wch: 15 },  // Bulan column
            { wch: 15 }   // Capaian column
        ];
        
        // Get chart image
        const canvas = chartInstance.canvas;
        const chartContainer = canvas.closest('.mb-4');
        
        try {
            // Capture chart as image
            const canvasImg = await html2canvas(chartContainer, {
                scale: 2,
                backgroundColor: '#ffffff'
            });
            
            const imgData = canvasImg.toDataURL('image/png');
            
            // Add image to worksheet (starting at row 15 to leave space for data)
            // Note: SheetJS doesn't natively support images in free version
            // So we'll add a note about the chart
            XLSX.utils.sheet_add_aoa(ws, [
                [''],
                ['Grafik tersedia dalam file terpisah atau gunakan export PDF/PNG']
            ], { origin: 'A15' });
            
        } catch (error) {
            console.error('Error capturing chart:', error);
        }
        
        const sheetName = chart.label.substring(0, 31); // Excel sheet name max 31 chars
        XLSX.utils.book_append_sheet(wb, ws, sheetName);
    }
    
    XLSX.writeFile(wb, `Capaian_Indikator_Mutu_${selectedYear}.xlsx`);
});


chartData.forEach((data, index) => {
    const ctx = document.getElementById('chart-' + index);
    if (ctx) {
        // Register the plugin
        Chart.register(ChartDataLabels);

        // Create array of target values (same value for all months)
        const targetData = Array(12).fill(data.target);

        const chartInstance = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: monthLabels,
                datasets: [{
                    label: 'Capaian (' + data.satuan + ')',
                    data: data.data,
                    backgroundColor: colors[index % colors.length],
                    borderColor: colors[index % colors.length].replace('0.8', '1'),
                    borderWidth: 1,
                    order: 2
                }, {
                    label: 'Target',
                    data: targetData,
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
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        max: data.satuan === '%' ? 100 : undefined,
                        ticks: {
                            callback: function(value) {
                                if (data.satuan === '%') {
                                    return value + '%';
                                }
                                return value;
                            }
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                let label = 'Capaian: ' + context.parsed.y;
                                if (data.satuan === '%') {
                                    label += '%';
                                } else {
                                    label += ' ' + data.satuan;
                                }
                                return label;
                            }
                        }
                    },
                    datalabels: {
                        anchor: 'end',
                        align: 'top',
                        formatter: function(value, context) {
                            // Only show label for the first dataset (bar chart)
                            if (context.datasetIndex === 0) {
                                if (data.satuan === '%') {
                                    return value.toFixed(2) + '%';
                                } else {
                                    return value + ' ' + data.satuan;
                                }
                            }
                            return ''; // Hide for target line
                        },
                        font: {
                            weight: 'bold',
                            size: 10
                        },
                        color: '#333'
                    }
                }
            }
        });
        chartInstances.push(chartInstance);
    }
});
<?php endif; ?>
</script>
<?= $this->endSection() ?>
