	<br/><br/><br/><?php $nama_bulan = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'); ?>
	<?php if(strlen($this->uri->segment(4)) > 15) $span = 10; else $span = 15;?> 
	
	<table cellpadding="3">
	<tr><td colspan="<?php echo $span;?>" align="center">
		<h3 align="center">PERNYATAAN <?php echo strtoupper($jabatan);?></h3>
		Saya sudah memeriksa dan bisa menyetujui laporan evaluasi ini<br/><br/>
		Yogyakarta, <?php echo date('d').' '.$nama_bulan[(int)date('m')].' '.date('Y');?><br/>
		Mengesahkan <?php echo $jabatan;?>,<br/><br/><br/><br/><br/>
		<?php echo $penandatangan;?><br/><?php echo $nip_penandatangan;?>
	</td></tr>
	</table>
