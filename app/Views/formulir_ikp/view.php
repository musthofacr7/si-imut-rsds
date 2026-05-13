<?= $this->extend('layout/main'); ?>
<?= $this->section('title'); ?>Detail Laporan IKP<?= $this->endSection(); ?>
<?= $this->section('content'); ?>

<?php
function chk($f,$v,$d){return($d[$f]??'')===$v?'&#9745;':'&#9744;';}
function chkA($v,$d){$a=is_array($d['tindakan_oleh']??null)?$d['tindakan_oleh']:[];return in_array($v,$a)?'&#9745;':'&#9744;';}
function dl($f,$d){return'<span style="border-bottom:1px dotted #555;display:inline-block;min-width:120px;padding:0 4px">'.esc($d[$f]??'').'</span>';}
$g=['biru'=>'primary','hijau'=>'success','kuning'=>'warning','merah'=>'danger'];
$gb=$g[$ikp['grading']]??'secondary';
$ji=['KNC'=>'warning','KTC'=>'info','KTD'=>'danger'];
$jb=$ji[$ikp['jenis_insiden']]??'secondary';
$tol=is_array($ikp['tindakan_oleh']??null)?$ikp['tindakan_oleh']:[];
?>

<!-- Tombol Aksi -->
<div class="mb-3 d-flex justify-content-between align-items-center">
  <a href="<?=base_url('formulir-ikp/daftar')?>" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Kembali</a>
  <div class="d-flex gap-2">
    <a href="<?=base_url('formulir-ikp/edit/'.$ikp['id'])?>" class="btn btn-warning"><i class="bi bi-pencil"></i> Edit</a>
    <button class="btn btn-primary" onclick="cetakIKP()"><i class="bi bi-printer"></i> Cetak</button>
  </div>
</div>

<!-- Formulir IKP — tampil di layar & bisa dicetak -->
<div class="card shadow-sm">
  <div class="card-body" style="max-width:800px;margin:0 auto">
  <div id="ikp-print-content">
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
      <div style="font-weight:bold;font-size:12pt">Formulir Laporan Insiden ke Tim KP di RS</div>
      <div>Rumah Sakit Umum Dr Soedirman Kebumen</div>
    </div>
  </div>
  <div style="border:1px solid #000;padding:2px 8px;text-align:center;font-weight:bold;font-size:9pt;margin-bottom:6px">RAHASIA, TIDAK BOLEH DIFOTOCOPY, DILAPORKAN MAXIMAL 2 x 24 JAM</div>
  <div style="text-align:center;font-weight:bold;font-size:11pt;margin:6px 0 2px">LAPORAN INSIDEN</div>
  <div style="text-align:center;margin-bottom:8px">(INTERNAL)</div>

  <!-- I. DATA PASIEN -->
  <div style="font-weight:bold;margin:6px 0 4px">I. DATA PASIEN</div>
  <div style="display:flex;align-items:baseline;margin-bottom:3px"><span style="min-width:110px"><b>Nama</b></span><span>: </span><?=dl('nama_pasien',$ikp)?></div>
  <div style="display:flex;align-items:baseline;margin-bottom:3px">
    <span style="min-width:110px"><b>No MR</b></span><span>: </span>
    <span style="border-bottom:1px dotted #555;min-width:100px;padding:0 4px"><?=esc($ikp['no_mr']??'')?></span>
    <span style="margin:0 8px"><b>Ruangan Asal</b> :</span>
    <span style="border-bottom:1px dotted #555;flex:1;padding:0 4px"><?=esc($ikp['area_pengukuran']??'')?><?= !empty($ikp['ruangan']) ? ' - ' . esc($ikp['ruangan']) : '' ?></span>
  </div>
  <div style="display:flex;align-items:flex-start;margin-bottom:2px">
    <span style="min-width:110px">Umur *</span><span>: </span>
    <div style="display:flex;flex-wrap:wrap;flex:1">
      <?php $uOpts=['0-1_bln'=>'0-1 bulan','>1bln-1thn'=>'&gt; 1 bulan – 1 tahun','>1-5thn'=>'&gt; 1 tahun – 5 tahun','>5-15thn'=>'&gt; 5 tahun – 15 tahun','>15-30thn'=>'&gt; 15 tahun – 30 tahun','>30-65thn'=>'&gt; 30 tahun – 65 tahun','>65thn'=>'&gt; 65 tahun'];?>
      <?php foreach($uOpts as $v=>$l):?><div style="width:33.33%;margin-bottom:1px"><?=chk('umur',$v,$ikp)?> <?=$l?></div><?php endforeach;?>
    </div>
  </div>
  <div style="margin-bottom:2px">Jenis kelamin : <?=chk('jenis_kelamin','L',$ikp)?> Laki-laki &nbsp; <?=chk('jenis_kelamin','P',$ikp)?> Perempuan</div>
  <div style="margin-bottom:1px">Penanggung biaya pasien :</div>
  <div style="display:flex;flex-wrap:wrap;margin-left:20px;margin-bottom:2px">
    <?php $bOpts=['pribadi'=>'Pribadi','asuransi_swasta'=>'Asuransi Swasta','askes'=>'ASKES Pemerintah','perusahaan'=>'Perusahaan*','jamkesmas'=>'JAMKESMAS','jamkesda'=>'JAMKESDA'];?>
    <?php foreach($bOpts as $v=>$l):?><div style="width:50%;margin-bottom:1px"><?=chk('biaya',$v,$ikp)?> <?=$l?></div><?php endforeach;?>
  </div>
  <div style="display:flex;align-items:baseline;margin-bottom:4px">
    <span>Tanggal Masuk RS : </span>
    <span style="border-bottom:1px dotted #555;min-width:120px;padding:0 4px"><?=$ikp['tgl_masuk']?date('d/m/Y',strtotime($ikp['tgl_masuk'])):''?></span>
    <span style="margin:0 6px">Jam</span>
    <span style="border-bottom:1px dotted #555;min-width:80px;padding:0 4px"><?=$ikp['jam_masuk']?substr($ikp['jam_masuk'],0,5):''?></span>
  </div>

  <!-- II. RINCIAN KEJADIAN -->
  <div style="font-weight:bold;margin:4px 0 3px">II. RINCIAN KEJADIAN</div>

  <div style="margin-bottom:2px"><b>1. Tanggal dan Waktu Insiden</b></div>
  <div style="display:flex;align-items:baseline;margin-left:20px;margin-bottom:3px">
    Tanggal : <span style="border-bottom:1px dotted #555;min-width:120px;padding:0 4px"><?=$ikp['tgl_insiden']?date('d/m/Y',strtotime($ikp['tgl_insiden'])):''?></span>
    &nbsp;Jam <span style="border-bottom:1px dotted #555;min-width:80px;padding:0 4px"><?=$ikp['jam_insiden']?substr($ikp['jam_insiden'],0,5):''?></span>
  </div>

  <div style="display:flex;align-items:baseline;margin-bottom:3px"><b>2. Insiden : </b><?=dl('insiden',$ikp)?></div>

  <div style="margin-bottom:1px"><b>3. Kronologis Insiden</b></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:2px;margin-left:20px;padding:0 4px"><?=esc($ikp['kronologis']??'')?></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:4px;margin-left:20px"></div>

  <div style="margin-bottom:2px"><b>4. Jenis Insiden* :</b></div>
  <div style="margin-left:20px;margin-bottom:4px">
    <div><?=chk('jenis_insiden','KNC',$ikp)?> Kejadian Nyaris Cedera / KNC <em>(Near miss)</em></div>
    <div><?=chk('jenis_insiden','KTC',$ikp)?> Kejadian Tidak cedera / KTC <em>(No Harm)</em></div>
    <div><?=chk('jenis_insiden','KTD',$ikp)?> Kejadian Tidak diharapkan / KTD <em>(Adverse Event)</em> / Kejadian Sentinel <em>(Sentinel Event)</em></div>
  </div>

  <div style="margin-bottom:2px"><b>5. Orang Pertama Yang Melaporkan Insiden*</b></div>
  <div style="margin-left:20px;margin-bottom:4px">
    <div><?=chk('pelapor','karyawan',$ikp)?> Karyawan : Dokter / Perawat / Petugas lainnya</div>
    <div><?=chk('pelapor','pasien',$ikp)?> Pasien</div>
    <div><?=chk('pelapor','keluarga',$ikp)?> Keluarga / Pendamping pasien</div>
    <div><?=chk('pelapor','pengunjung',$ikp)?> Pengunjung</div>
    <div style="display:flex;align-items:baseline"><?=chk('pelapor','lain',$ikp)?>&nbsp;Lain-lain <span style="border-bottom:1px dotted #555;flex:1;padding:0 4px;margin:0 4px"><?=esc($ikp['pelapor_lain']??'')?></span><b>(sebutkan)</b></div>
  </div>

  <div style="margin-bottom:2px"><b>6. Insiden terjadi pada* :</b></div>
  <div style="margin-left:20px;margin-bottom:4px">
    <div><?=chk('insiden_pada','pasien',$ikp)?> Pasien</div>
    <div style="display:flex;align-items:baseline"><?=chk('insiden_pada','lain',$ikp)?>&nbsp;Lain-lain <span style="border-bottom:1px dotted #555;flex:1;padding:0 4px;margin:0 4px"><?=esc($ikp['insiden_pada_lain']??'')?></span><b>(sebutkan)</b></div>
    <div style="font-size:9pt">Mis : karyawan / Pengunjung / Pendamping / Keluarga pasien, lapor ke K3 RS.</div>
  </div>

  <div style="margin-bottom:2px"><b>7. Insiden menyangkut pasien :</b></div>
  <div style="margin-left:20px;margin-bottom:4px">
    <div><?=chk('pasien_jenis','rawat_inap',$ikp)?> Pasien rawat inap</div>
    <div><?=chk('pasien_jenis','rawat_jalan',$ikp)?> Pasien rawat jalan</div>
    <div><?=chk('pasien_jenis','ugd',$ikp)?> Pasien UGD</div>
    <div style="display:flex;align-items:baseline"><?=chk('pasien_jenis','lain',$ikp)?>&nbsp;Lain-lain <span style="border-bottom:1px dotted #555;flex:1;margin:0 4px"><?=esc($ikp['pasien_jenis_lain']??'')?></span><b>(sebutkan)</b></div>
  </div>

  <div style="margin-bottom:2px"><b>8. Tempat Insiden</b></div>
  <div style="display:flex;align-items:baseline;margin-left:20px;margin-bottom:4px">
    Lokasi kejadian : <span style="border-bottom:1px dotted #555;flex:1;padding:0 4px;margin:0 4px"><?=esc($ikp['lokasi']??'')?></span><b>(sebutkan)</b>
  </div>
  <div style="margin-left:20px;font-size:9pt;margin-bottom:4px">(Tempat pasien berada)</div>

  <div style="margin-bottom:2px"><b>9. Insiden terjadi pada pasien : (sesuai kasus penyakit / spesialisasi)</b></div>
  <div style="margin-left:20px;margin-bottom:4px;display:flex;flex-wrap:wrap">
    <?php $sp=['penyakit_dalam'=>'Penyakit Dalam dan Subspesialisasinya','anak'=>'Anak dan Subspesialisasinya','bedah'=>'Bedah dan Subspesialisasinya','obgyn'=>'Obstetri Gynekologi dan Subspesialisasinya','tht'=>'THT dan Subspesialisasinya','mata'=>'Mata dan Subspesialisasinya','saraf'=>'Saraf dan Subspesialisasinya','anastesi'=>'Anastesi dan Subspesialisasinya','kulit'=>'Kulit & Kelamin dan Subspesialisasinya','jantung'=>'Jantung dan Subspesialisasinya','paru'=>'Paru dan Subspesialisasinya','jiwa'=>'Jiwa dan Subspesialisasinya'];?>
    <?php foreach($sp as $v=>$l):?><div style="width:50%;margin-bottom:1px"><?=chk('spesialisasi',$v,$ikp)?> <?=$l?></div><?php endforeach;?>
    <div style="width:100%;display:flex;align-items:baseline"><?=chk('spesialisasi','lain',$ikp)?>&nbsp;Lain-lain <span style="border-bottom:1px dotted #555;flex:1;margin:0 4px"><?=esc($ikp['spesialisasi_lain']??'')?></span><b>(sebutkan)</b></div>
  </div>

  <div style="margin-bottom:2px"><b>10. Unit / Departemen terkait yang menyebabkan insiden</b></div>
  <div style="display:flex;align-items:baseline;margin-left:20px;margin-bottom:4px">
    Unit kerja penyebab : <span style="border-bottom:1px dotted #555;flex:1;margin:0 4px"><?=esc($ikp['unit_penyebab']??'')?></span><b>(sebutkan)</b>
  </div>

  <div style="margin-bottom:2px"><b>11. Akibat Insiden Terhadap Pasien* :</b></div>
  <div style="margin-left:20px;margin-bottom:4px">
    <?php $ak=['kematian'=>'Kematian','cedera_berat'=>'Cedera Irreversibel / Cedera Berat','cedera_sedang'=>'Cedera Reversibel / Cedera Sedang','cedera_ringan'=>'Cedera Ringan','tidak_cedera'=>'Tidak ada cedera'];?>
    <?php foreach($ak as $v=>$l):?><div><?=chk('akibat',$v,$ikp)?> <?=$l?></div><?php endforeach;?>
  </div>

  <div style="margin-bottom:2px"><b>12. Tindakan yang dilakukan segera setelah kejadian, dan hasilnya :</b></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:3px;margin-left:20px;padding:0 4px"><?=esc($ikp['tindakan_segera']??'')?></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:6px;margin-left:20px"></div>

  <div style="margin-bottom:2px"><b>13. Tindakan dilakukan oleh* :</b></div>
  <div style="margin-left:20px;margin-bottom:4px">
    <div style="display:flex;align-items:baseline"><?=chkA('tim',$ikp)?>&nbsp;Tim : terdiri dari : <span style="border-bottom:1px dotted #555;flex:1;margin:0 4px"><?=esc($ikp['tim_terdiri']??'')?></span></div>
    <div><?=chkA('dokter',$ikp)?> Dokter</div>
    <div><?=chkA('perawat',$ikp)?> Perawat</div>
    <div style="display:flex;align-items:baseline"><?=chkA('petugas_lain',$ikp)?>&nbsp;Petugas lainnya <span style="border-bottom:1px dotted #555;flex:1;margin:0 4px"><?=esc($ikp['petugas_lainnya']??'')?></span></div>
  </div>

  <div style="margin-bottom:2px"><b>14. Apakah kejadian yang sama pernah terjadi di Unit Kerja lain?*</b></div>
  <div style="margin-left:20px;margin-bottom:2px"><?=chk('pernah_terjadi','ya',$ikp)?> Ya &nbsp; <?=chk('pernah_terjadi','tidak',$ikp)?> Tidak</div>
  <div style="margin-left:20px;font-size:9pt">Apabila ya, isi bagian dibawah ini.<br><b>Kapan? dan Langkah/tindakan apa yang telah diambil pada Unit kerja tersebut untuk mencegah terulangnya kejadian yang sama?</b></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:3px;margin-left:20px;padding:0 4px"><?=esc($ikp['langkah_pencegahan']??'')?></div>
  <div style="border-bottom:1px dotted #555;min-height:14px;margin-bottom:6px;margin-left:20px"></div>

  <!-- Tanda Tangan -->
  <table style="width:100%;border-collapse:collapse;margin-top:10px">
    <tr>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt"><b>Pembuat Laporan</b></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt">: <?=esc($ikp['pembuat_laporan']??'')?></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt"><b>Penerima Laporan</b></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt">: <?=esc($ikp['penerima_laporan']??'')?></td>
    </tr>
    <tr>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;height:50px;vertical-align:top">Paraf</td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;height:50px;vertical-align:top">: <?=esc($ikp['paraf_pembuat']??'')?></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;height:50px;vertical-align:top">Paraf</td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt;height:50px;vertical-align:top">: <?=esc($ikp['paraf_penerima']??'')?></td>
    </tr>
    <tr>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt">Tgl Lapor</td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt">: <?=$ikp['tgl_lapor']?date('d/m/Y',strtotime($ikp['tgl_lapor'])):''?></td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt">Tgl terima</td>
      <td style="border:1px solid #000;padding:3px 6px;font-size:9pt">: <?=$ikp['tgl_terima']?date('d/m/Y',strtotime($ikp['tgl_terima'])):''?></td>
    </tr>
  </table>

  <!-- Grading -->
  <?php
    $gradingColors = [
      'biru'   => ['bg'=>'#4a90d9','label'=>'BIRU'],
      'hijau'  => ['bg'=>'#5aab5a','label'=>'HIJAU'],
      'kuning' => ['bg'=>'#f5c518','label'=>'KUNING'],
      'merah'  => ['bg'=>'#d94040','label'=>'MERAH'],
    ];
  ?>
  <div style="margin-top:8px">
    <b>Grading Risiko Kejadian*</b> (Diisi oleh atasan pelapor) :<br>
    <div style="display:flex;gap:8px;margin-top:6px;flex-wrap:wrap">
      <?php foreach($gradingColors as $gr=>$cfg): ?>
        <?php $isSelected = ($ikp['grading']??'') === $gr; ?>
        <div style="
          display:inline-flex;align-items:center;gap:4px;
          padding:5px 14px;
          border:2px solid <?=$cfg['bg']?>;
          border-radius:4px;
          background:<?= $isSelected ? $cfg['bg'] : '#fff' ?>;
          color:#000;
          font-weight:bold;
          font-size:10pt;
          -webkit-print-color-adjust:exact;
          print-color-adjust:exact;
        ">
          <?= $isSelected ? '&#9745;' : '&#9744;' ?> <?=$cfg['label']?>
        </div>
      <?php endforeach; ?>
    </div>
    <div style="font-size:9pt;margin-top:6px">NB. * = pilih satu jawaban</div>
  </div>

</div>
</div><!-- #ikp-print-content -->
  </div><!-- card-body -->
</div><!-- card -->

<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
function cetakIKP() {
    var konten = document.getElementById('ikp-print-content').innerHTML;
    var popup = window.open('', '_blank', 'width=794,height=1123,scrollbars=yes');
    popup.document.write('<!DOCTYPE html><html><head>'
        + '<meta charset="utf-8">'
        + '<title>Laporan IKP - <?= esc($ikp['nama_pasien']) ?></title>'
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
