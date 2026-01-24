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
        .header h2 {
            margin: 0;
            padding: 0;
        }
        .header h3 {
            margin: 5px 0 0;
            padding: 0;
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
    </style>
</head>
<body>
    <div class="header">
        <h2>Profil Indikator Mutu</h2>
        <h3><?= esc($indikator_mutu['judul_indikator']) ?></h3>
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
                <?php
                $jenisNama = '-';
                foreach ($jenis_indikator as $jenis) {
                    if ($indikator_mutu['jenis_indikator_id'] == $jenis['id']) {
                        $jenisNama = $jenis['jenis_indikator'];
                        break;
                    }
                }
                echo esc($jenisNama);
                ?>
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
</body>
</html>
