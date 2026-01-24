<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Profil Indikator Mutu</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        .header h1 {
            font-size: 24px;
            margin: 0;
            padding: 0;
        }
        .group-header {
            margin-top: 25px;
            margin-bottom: 10px;
            border-bottom: 1px solid #333;
        }
        .group-header h2 {
            font-size: 16px;
            font-weight: bold;
            margin: 0;
            padding: 5px 0;
            text-align: left;
        }
        .header h3 {
            font-size: 14px;
            margin: 10px 0 5px;
            padding: 0;
            text-align: left;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .table th, .table td {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
            font-size: 12px;
            line-height: 1.2;
        }
        .table th {
            background-color: #f2f2f2;
            text-align: left;
            width: 30%;
        }
        .table td p, .table td ul, .table td ol {
            margin: 0;
            padding-left: 15px;
        }
        .table td p {
            padding-left: 0;
            margin-bottom: 5px;
        }
        .table td ul, .table td ol {
            margin-bottom: 5px;
        }
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Profil Indikator Mutu</h1>
    </div>
    <?php 
    $current_jenis = null;
    $nomor_urut = 1;
    foreach ($indikator_mutu_list as $index => $indikator_mutu): 
        $jenis_nama = $indikator_mutu['jenis_indikator_nama'] ?? 'Tanpa Jenis';
    ?>
        
        <?php if ($jenis_nama !== $current_jenis): ?>
            <div class="group-header">
                <h2>Jenis Indikator: <?= esc($jenis_nama) ?></h2>
            </div>
            <?php 
            $current_jenis = $jenis_nama; 
            $nomor_urut = 1; // Reset nomor urut
            ?>
        <?php endif; ?>

        <div class="header">
            <h3 style="text-align: left;"><?= $nomor_urut++ ?>. <?= esc($indikator_mutu['judul_indikator']) ?></h3>
        </div>

        <table class="table">
            <tr>
                <th>Judul Indikator</th>
                <td><?= esc($indikator_mutu['judul_indikator']) ?></td>
            </tr>
            <tr>
                <th>Dimensi Mutu</th>
                <td><?= esc($indikator_mutu['dimensi_mutu'] ?? '-') ?></td>
            </tr>
            <tr>
                <th>Tujuan</th>
                <td><?= $indikator_mutu['tujuan'] ?? '-' ?></td>
            </tr>
            <tr>
                <th>Jenis Indikator</th>
                <td>
                    <?= esc($indikator_mutu['jenis_indikator_nama'] ?? '-') ?>
                </td>
            </tr>

            <tr>
                <th>Definisi Operasional</th>
                <td><?= $indikator_mutu['definisi_operasional'] ?></td>
            </tr>
            <tr>
                <th>Numerator</th>
                <td><?= $indikator_mutu['numerator'] ?></td>
            </tr>
            <tr>
                <th>Denumerator</th>
                <td><?= $indikator_mutu['denumerator'] ?></td>
            </tr>
            <tr>
                <th>Target Pencapaian</th>
                <td>
                    <?= esc($indikator_mutu['standar_target_pencapaian'] ?? '') ?> 
                    <?= esc($indikator_mutu['target_pencapaian']) ?> 
                    <?= esc($indikator_mutu['satuan_target_pencapaian'] ?? '%') ?>
                </td>
            </tr>
            <tr>
                <th>Kriteria Inklusi</th>
                <td><?= $indikator_mutu['kriteria_inklusi'] ?></td>
            </tr>
            <tr>
                <th>Kriteria Eksklusi</th>
                <td><?= $indikator_mutu['kriteria_eksklusi'] ?></td>
            </tr>
            <tr>
                <th>Sumber Data</th>
                <td><?= esc($indikator_mutu['sumber_data']) ?></td>
            </tr>
            <tr>
                <th>Frekuensi Pengumpulan Data</th>
                <td><?= esc($indikator_mutu['frekuensi_pengumpulan_data']) ?></td>
            </tr>
            <tr>
                <th>Periode Analisis Data</th>
                <td><?= esc($indikator_mutu['periode_analisis_data']) ?></td>
            </tr>
            <tr>
                <th>Rencana Analisis</th>
                <td><?= $indikator_mutu['rencana_analisis'] ?></td>
            </tr>
            <tr>
                <th>Instrumen Pengambilan Data</th>
                <td><?= esc($indikator_mutu['instrumen_pengambilan_data']) ?></td>
            </tr>
            <tr>
                <th>Area Pengukuran</th>
                <td><?= esc($indikator_mutu['area_pengukuran']) ?></td>
            </tr>
        </table>
        
        <?php if ($index < count($indikator_mutu_list) - 1): ?>
            <div class="page-break"></div>
        <?php endif; ?>

    <?php endforeach; ?>
</body>
</html>
