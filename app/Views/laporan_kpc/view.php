<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Detail Laporan KPC<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<?php
function chk($f,$v,$d){return($d[$f]??'')===$v?'&#9745;':'&#9744;';}
function chkA($v,$d){$a=is_array($d['tindakan_oleh']??null)?$d['tindakan_oleh']:[];return in_array($v,$a)?'&#9745;':'&#9744;';}
function dl($f,$d){return'<span style="border-bottom:1px dotted #555;display:inline-block;min-width:120px;padding:0 4px">'.esc($d[$f]??'').'</span>';}
?>

<!-- Tombol Aksi -->
<div class="mb-3 d-flex justify-content-between align-items-center">
  <a href="<?=base_url('laporan-kpc/daftar')?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  <div class="d-flex gap-2">
    <a href="<?=base_url('laporan-kpc/edit/'.$kpc['id'])?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    <button class="btn btn-primary" onclick="cetakKPC()"><i class="bi bi-printer"></i> Cetak</button>
  </div>
</div>

<!-- Formulir KPC — tampil di layar & bisa dicetak -->
<div class="card shadow-sm">
  <div class="card-body" style="max-width:800px;margin:0 auto">
  <div id="kpc-print-content">
<div style="font-family:'Times New Roman',serif;font-size:10pt;color:#000;line-height:1.6;padding:0">

  <!-- HEADER -->
  <div style="display:flex;align-items:center;margin-bottom:6px;gap:10px">
    <!-- Logo di kiri -->
    <div style="flex-shrink:0">
      <img src="<?= base_url('assets/img/logo-rsud.jpg') ?>" alt="Logo RSUD Soedirman"
           style="width:52px;height:52px;object-fit:contain;mix-blend-mode:multiply">
    </div>
    <!-- Judul di tengah/kanan -->
    <div style="flex:1;text-align:center">
      <div style="font-weight:bold;font-size:12pt">Formulir Laporan KPC ke Tim KP di RS</div>
      <div>Rumah Sakit Umum Dr Soedirman Kebumen</div>
    </div>
  </div>
  <div style="border:1px solid #000;padding:2px 8px;text-align:center;font-weight:bold;font-size:9pt;margin-bottom:6px">RAHASIA, TIDAK BOLEH DIFOTOCOPY, DILAPORKAN MAXIMAL 2 x 24 JAM</div>
  
  <div style="text-align:center;font-weight:bold;font-size:11pt;margin:12px 0 2px">LAPORAN Kondisi Potensial Cedera (KPC)</div>
  <div style="text-align:center;margin-bottom:12px">(INTERNAL)</div>

  <!-- Detail KPC -->
  <div style="display:flex;align-items:baseline;margin-bottom:4px">
    <span style="min-width:20px">1.</span>
    <span>Tanggal dan Waktu ditemukan Kondisi Potensi Cedera (KPC)</span>
  </div>
  <div style="display:flex;align-items:baseline;margin-left:20px;margin-bottom:8px">
    <span>Tanggal : </span>
    <span style="border-bottom:1px dotted #555;min-width:120px;padding:0 4px;margin:0 4px"><?=$kpc['tgl_kpc']?date('d/m/Y',strtotime($kpc['tgl_kpc'])):''?></span>
    <span style="margin:0 6px">Jam</span>
    <span style="border-bottom:1px dotted #555;min-width:80px;padding:0 4px;margin:0 4px"><?=$kpc['jam_kpc']?substr($kpc['jam_kpc'],0,5):''?></span>
  </div>

  <div style="display:flex;align-items:baseline;margin-bottom:8px">
    <span style="min-width:20px">2.</span>
    <span style="white-space:nowrap">KPC : </span>
    <div style="border-bottom:1px dotted #555;flex:1;min-height:14px;padding:0 4px;margin-left:4px"><?=esc($kpc['insiden']??'')?></div>
  </div>

  <div style="margin-bottom:2px">
    <span style="min-width:20px;display:inline-block">3.</span>
    <b>Orang Pertama Yang Melaporkan Insiden*</b>
  </div>
  <div style="margin-left:20px;margin-bottom:8px">
    <div><?=chk('pelapor','karyawan',$kpc)?> Karyawan : Dokter / Perawat / Petugas lainnya</div>
    <div><?=chk('pelapor','pasien',$kpc)?> Pasien</div>
    <div><?=chk('pelapor','keluarga',$kpc)?> Keluarga / Pendamping pasien</div>
    <div><?=chk('pelapor','pengunjung',$kpc)?> Pengunjung</div>
    <div style="display:flex;align-items:baseline"><?=chk('pelapor','lain',$kpc)?>&nbsp;Lain-lain <span style="border-bottom:1px dotted #555;flex:1;padding:0 4px;margin:0 4px"><?=esc($kpc['pelapor_lain']??'')?></span><b>(sebutkan)</b></div>
  </div>

  <div style="margin-bottom:2px">
    <span style="min-width:20px;display:inline-block">4.</span>
    <b>Lokasi diketahui KPC</b>
  </div>
  <div style="display:flex;align-items:baseline;margin-left:20px;margin-bottom:8px">
    <span style="border-bottom:1px dotted #555;flex:1;padding:0 4px;margin:0 4px"><?=esc($kpc['lokasi']??'')?></span><b>(sebutkan)</b>
  </div>
  
  <div style="margin-bottom:2px">
    <span style="min-width:20px;display:inline-block">5.</span>
    <b>Unit / Departemen terkait KPC</b>
  </div>
  <div style="display:flex;align-items:baseline;margin-left:20px;margin-bottom:8px">
    <span style="border-bottom:1px dotted #555;flex:1;padding:0 4px;margin:0 4px"><?=esc($kpc['unit_terkait']??'')?></span><b>(sebutkan)</b>
  </div>

  <div style="margin-bottom:2px">
    <span style="min-width:20px;display:inline-block">6.</span>
    <b>Tindakan apa yang dilakukan untuk mengatasi kondisi potensi cedera selama ini ?</b>
  </div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:3px;margin-left:20px;padding:0 4px"><?=esc($kpc['tindakan']??'')?></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:8px;margin-left:20px"></div>

  <div style="margin-bottom:2px">
    <span style="min-width:20px;display:inline-block">7.</span>
    <b>Tindakan dilakukan oleh* :</b>
  </div>
  <div style="margin-left:20px;margin-bottom:8px">
    <div style="display:flex;align-items:baseline"><?=chkA('tim',$kpc)?>&nbsp;Tim : terdiri dari : <span style="border-bottom:1px dotted #555;flex:1;margin:0 4px"><?=esc($kpc['tim_terdiri']??'')?></span></div>
    <div><?=chkA('dokter',$kpc)?> Dokter</div>
    <div><?=chkA('perawat',$kpc)?> Perawat</div>
    <div style="display:flex;align-items:baseline"><?=chkA('petugas_lain',$kpc)?>&nbsp;Petugas lainnya <span style="border-bottom:1px dotted #555;flex:1;margin:0 4px"><?=esc($kpc['petugas_lainnya']??'')?></span></div>
  </div>

  <div style="margin-bottom:2px">
    <span style="min-width:20px;display:inline-block">8.</span>
    <b>Apakah kejadian yang sama pernah terjadi di Unit Kerja lain?*</b>
  </div>
  <div style="margin-left:20px;margin-bottom:2px"><?=chk('pernah_terjadi','ya',$kpc)?> Ya &nbsp; <?=chk('pernah_terjadi','tidak',$kpc)?> Tidak</div>
  <div style="margin-left:20px;font-size:9pt">Apabila ya, isi bagian dibawah ini.<br><b>Kapan? dan Langkah/tindakan apa yang telah diambil pada Unit kerja tersebut untuk mencegah terulangnya kondisi yang sama?</b></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:3px;margin-left:20px;padding:0 4px"><?=esc($kpc['langkah_pencegahan']??'')?></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:12px;margin-left:20px"></div>

  <!-- Tanda Tangan -->
  <table style="width:100%;border-collapse:collapse;margin-top:10px">
    <tr>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;width:120px"><b>Pembuat Laporan</b></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt">: <?=esc($kpc['pembuat_laporan']??'')?></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;width:120px"><b>Penerima Laporan</b></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt">: <?=esc($kpc['penerima_laporan']??'')?></td>
    </tr>
    <tr>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;height:50px;vertical-align:top">Paraf</td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;height:50px;vertical-align:top">: <?=esc($kpc['paraf_pembuat']??'')?></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;height:50px;vertical-align:top">Paraf</td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;height:50px;vertical-align:top">: <?=esc($kpc['paraf_penerima']??'')?></td>
    </tr>
  </table>

</div>
</div><!-- #kpc-print-content -->
  </div><!-- card-body -->
</div><!-- card -->

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
function cetakKPC() {
    var konten = document.getElementById('kpc-print-content').innerHTML;
    var popup = window.open('', '_blank', 'width=794,height=1123,scrollbars=yes');
    popup.document.write('<!DOCTYPE html><html><head>'
        + '<meta charset="utf-8">'
        + '<title>Laporan KPC - <?= esc($kpc['insiden']) ?></title>'
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
