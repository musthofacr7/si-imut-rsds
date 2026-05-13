<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Cetak Investigasi Sederhana<?= $this->endSection(); ?>

<?= $this->section('content'); ?>
<!-- Tombol Aksi -->
<div class="mb-3 d-flex justify-content-between align-items-center">
  <a href="<?=base_url('formulir-ikp/daftar')?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  <div class="d-flex gap-2">
    <a href="<?=base_url('investigasi-sederhana/edit/'.$inv['id'])?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    <form action="<?= base_url('investigasi-sederhana/delete/' . $inv['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus investigasi ini?');">
      <?= csrf_field() ?>
      <button type="submit" class="btn btn-danger"><i class="bi bi-trash"></i> Hapus</button>
    </form>
    <button class="btn btn-primary" onclick="cetakINV()"><i class="bi bi-printer"></i> Cetak</button>
  </div>
</div>

<div class="card shadow-sm">
  <div class="card-body" style="max-width:800px;margin:0 auto">
    <div id="inv-print-content">
      <div style="font-family:'Times New Roman',serif;font-size:10pt;color:#000;line-height:1.4;padding:0">

        <!-- HEADER -->
        <div style="display:flex;align-items:center;margin-bottom:10px;gap:10px">
          <!-- Logo di kiri -->
          <div style="flex-shrink:0">
            <img src="<?= base_url('assets/img/logo-rsud.jpg') ?>" alt="Logo RSUD Soedirman"
                 style="width:65px;height:65px;object-fit:contain;mix-blend-mode:multiply">
          </div>
          <!-- Judul di tengah -->
          <div style="flex:1;text-align:center">
            <div style="font-weight:bold;font-size:11pt">LEMBAR KERJA INVESTIGASI SEDERHANA</div>
            <div style="font-weight:bold;font-size:11pt">RSUD Dr. Soedirman kebumen</div>
            <div style="font-size:10pt">untuk <i>Bands</i> Risiko BIRU / HIJAU</div>
          </div>
          <!-- Spasi agar tengah pas -->
          <div style="width:65px"></div>
        </div>

        <!-- Penyebab Langsung -->
        <table style="width:100%;border-collapse:collapse;margin-bottom:6px">
          <tr>
            <td style="border:1px solid #000;padding:4px 6px;vertical-align:top;height:120px">
              <u><b>Penyebab langsung insiden :</b></u><br><br>
              <div style="display:flex;margin-bottom:8px">
                <div style="width:100px">Alat :</div>
                <div style="flex:1;white-space:pre-wrap"><?=esc($inv['alat']??'')?></div>
              </div>
              <div style="display:flex;margin-bottom:8px">
                <div style="width:100px">Tempat Kerja :</div>
                <div style="flex:1;white-space:pre-wrap"><?=esc($inv['tempat_kerja']??'')?></div>
              </div>
              <div style="display:flex;margin-bottom:8px">
                <div style="width:100px">Prosedur :</div>
                <div style="flex:1;white-space:pre-wrap"><?=esc($inv['prosedur']??'')?></div>
              </div>
            </td>
          </tr>
        </table>

        <!-- Akar Masalah -->
        <table style="width:100%;border-collapse:collapse;margin-bottom:6px">
          <tr>
            <td style="border:1px solid #000;padding:4px 6px;vertical-align:top;height:120px">
              <u><b>Penyebab yang melatarbelakangi / akar masalah insiden :</b></u><br><br>
              <div style="display:flex;margin-bottom:8px">
                <div style="width:100px">Individu :</div>
                <div style="flex:1;white-space:pre-wrap"><?=esc($inv['individu']??'')?></div>
              </div>
              <div style="display:flex;margin-bottom:8px">
                <div style="width:100px">Tempat Kerja :</div>
                <div style="flex:1;white-space:pre-wrap"><?=esc($inv['tempat_kerja_akar']??'')?></div>
              </div>
              <div style="display:flex;margin-bottom:8px">
                <div style="width:100px">Organisasi :</div>
                <div style="flex:1;white-space:pre-wrap"><?=esc($inv['organisasi']??'')?></div>
              </div>
            </td>
          </tr>
        </table>

        <!-- Rekomendasi -->
        <table style="width:100%;border-collapse:collapse;margin-bottom:6px">
          <tr>
            <td style="border:1px solid #000;padding:4px 6px;width:60%"><u><b>Rekomendasi :</b></u></td>
            <td style="border:1px solid #000;padding:4px 6px;width:25%">Penanggung jawab</td>
            <td style="border:1px solid #000;padding:4px 6px;width:15%">Tanggal :</td>
          </tr>
          <?php if(!empty($inv['rekomendasi'])): ?>
            <?php foreach($inv['rekomendasi'] as $rek): ?>
            <tr>
              <td style="border:1px solid #000;padding:4px 6px;vertical-align:top">- <?=esc($rek['rekomendasi']??'')?></td>
              <td style="border:1px solid #000;padding:4px 6px;vertical-align:top"><?=esc($rek['pj_rekomendasi']??'')?></td>
              <td style="border:1px solid #000;padding:4px 6px;vertical-align:top"><?=$rek['tgl_rekomendasi']?date('d/m/Y',strtotime($rek['tgl_rekomendasi'])):''?></td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td style="border:1px solid #000;padding:4px 6px;height:40px">- </td>
              <td style="border:1px solid #000;padding:4px 6px"></td>
              <td style="border:1px solid #000;padding:4px 6px"></td>
            </tr>
          <?php endif; ?>
        </table>

        <!-- Tindakan -->
        <table style="width:100%;border-collapse:collapse;margin-bottom:6px">
          <tr>
            <td style="border:1px solid #000;padding:4px 6px;width:60%">Tindakan yang akan dilakukan :</td>
            <td style="border:1px solid #000;padding:4px 6px;width:25%">Penanggung jawab :</td>
            <td style="border:1px solid #000;padding:4px 6px;width:15%">Tanggal :</td>
          </tr>
          <?php if(!empty($inv['tindakan'])): ?>
            <?php foreach($inv['tindakan'] as $tind): ?>
            <tr>
              <td style="border:1px solid #000;padding:4px 6px;vertical-align:top">- <?=esc($tind['tindakan']??'')?></td>
              <td style="border:1px solid #000;padding:4px 6px;vertical-align:top"><?=esc($tind['pj_tindakan']??'')?></td>
              <td style="border:1px solid #000;padding:4px 6px;vertical-align:top"><?=$tind['tgl_tindakan']?date('d/m/Y',strtotime($tind['tgl_tindakan'])):''?></td>
            </tr>
            <?php endforeach; ?>
          <?php else: ?>
            <tr>
              <td style="border:1px solid #000;padding:4px 6px;height:40px">- </td>
              <td style="border:1px solid #000;padding:4px 6px"></td>
              <td style="border:1px solid #000;padding:4px 6px"></td>
            </tr>
          <?php endif; ?>
        </table>

        <!-- Manager -->
        <table style="width:100%;border-collapse:collapse;margin-bottom:6px">
          <tr>
            <td colspan="2" style="border:1px solid #000;padding:4px 6px;background:#ddd;font-weight:bold">Manager / Kepala Bagian / Kepala Unit</td>
          </tr>
          <tr>
            <td style="border:1px solid #000;padding:6px;width:50%;vertical-align:top">
              <div style="margin-bottom:8px">Nama &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span style="border-bottom:1px solid #000;display:inline-block;width:200px"><?=esc($inv['manajer_nama']??'')?></span></div>
              <div>Tanda tangan &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span style="border-bottom:1px solid #000;display:inline-block;width:200px;height:30px"><?=esc($inv['ttd_manajer']??'')?></span></div>
            </td>
            <td style="border:1px solid #000;padding:6px;width:50%;vertical-align:top">
              <div style="margin-bottom:8px">Tanggal mulai Investigasi &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: <span style="border-bottom:1px solid #000;display:inline-block;width:150px"><?=$inv['tgl_mulai_investigasi']?date('d/m/Y',strtotime($inv['tgl_mulai_investigasi'])):''?></span></div>
              <div>Tanggal selesai Investigasi &nbsp;&nbsp;&nbsp;: <span style="border-bottom:1px solid #000;display:inline-block;width:150px"><?=$inv['tgl_selesai_investigasi']?date('d/m/Y',strtotime($inv['tgl_selesai_investigasi'])):''?></span></div>
            </td>
          </tr>
        </table>

        <!-- Manajemen Risiko -->
        <table style="width:100%;border-collapse:collapse;margin-bottom:6px">
          <tr>
            <td style="border:1px solid #000;padding:4px 6px;width:20%;font-weight:bold;vertical-align:top" rowspan="2">Manajemen<br>Risiko :</td>
            <td style="border:1px solid #000;padding:4px 6px">
              Investigasi Lengkap : 
              <span style="text-decoration: <?= ($inv['investigasi_lengkap']??'')=='ya' ? 'underline' : 'none' ?>">YA</span>/<!--
              --><span style="text-decoration: <?= ($inv['investigasi_lengkap']??'')=='tidak' ? 'underline' : 'none' ?>">TIDAK</span>
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Tanggal : <span style="border-bottom:1px solid #000;display:inline-block;width:150px"><?=$inv['tgl_investigasi_lengkap']?date('d/m/Y',strtotime($inv['tgl_investigasi_lengkap'])):''?></span>
            </td>
          </tr>
          <tr>
            <td style="border:1px solid #000;padding:4px 6px">
              Diperlukan Investigasi lebih lanjut : 
              <span style="text-decoration: <?= ($inv['investigasi_lanjut']??'')=='ya' ? 'underline' : 'none' ?>">YA</span>/<!--
              --><span style="text-decoration: <?= ($inv['investigasi_lanjut']??'')=='tidak' ? 'underline' : 'none' ?>">TIDAK</span><br>
              Investigasi setelah <i>Grading</i> ulang : 
              <span style="text-decoration: <?= ($inv['grading_ulang']??'')=='hijau' ? 'underline' : 'none' ?>">Hijau</span>/<!--
              --><span style="text-decoration: <?= ($inv['grading_ulang']??'')=='kuning' ? 'underline' : 'none' ?>">Kuning</span>/<!--
              --><span style="text-decoration: <?= ($inv['grading_ulang']??'')=='merah' ? 'underline' : 'none' ?>">Merah</span>
            </td>
          </tr>
        </table>

      </div>
    </div>
  </div>
</div>

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
function cetakINV() {
    var konten = document.getElementById('inv-print-content').innerHTML;
    var popup = window.open('', '_blank', 'width=794,height=1123,scrollbars=yes');
    popup.document.write('<!DOCTYPE html><html><head>'
        + '<meta charset="utf-8">'
        + '<title>Lembar Kerja Investigasi Sederhana</title>'
        + '<style>'
        + 'body{font-family:"Times New Roman",serif;font-size:10pt;color:#000;margin:0;padding:14mm;}'
        + '@page{size:A4 portrait;margin:12mm 14mm;}'
        + '@media print{body{padding:0;}}'
        + '</style>'
        + '</head><body>'
        + konten
        + '</body></html>');
    popup.document.close();
    popup.focus();
    setTimeout(function(){ popup.print(); }, 500);
}
</script>
<?= $this->endSection(); ?>
