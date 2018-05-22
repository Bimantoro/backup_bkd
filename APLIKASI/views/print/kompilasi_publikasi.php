<style>
.table th{ font-weight: bold; border-bottom:solid 2px #333; border-top:solid 2px #333; text-align:center;}
.table td{ border-bottom:solid 1px #333; padding:7px; }
#footer_pdf{ border: solid 1px #aaa; padding:2px; position:fixed;} 
.tgl{ color:#555; font-style:italic;}
h2{ border:solid 1px #555; text-align:center; line-height:1px;}
</style>

	<table border="0.01" cellpadding="2"><tr><td align="center"><?php echo $judul;?></td></tr></table><br/><br/>
	<?php if ($this->session->userdata('adm_univ') == ''){ ?>
	<table border="0">
		<tr><td width="50">Nama Perguruan Tinggi</td><td>: <?php echo $pt;?></td></tr>
		<tr><td width="50">Nama <?php echo $label;?></td><td>: <?php echo ucwords(strtolower($label_value)); ?></td></tr>
	</table><br/><br/>
	<?php }else{ ?>
	<table border="0">
		<tr><td width="50">Nama Perguruan Tinggi</td><td>: <?php echo $pt;?></td></tr>
		<tr><td width="50">Alamat Perguruan Tinggi</td><td>: <?php echo $alamat;?></td></tr>
	</table><br/><br/>
	<?php } ?>
	
	<table class="table" cellpadding="1">
	<tr>
		<th width="20">No</th>
		<th width="70">NIP</th>
		<th width="270">Uraian Publikasi</th>
	</tr>
	<?php if(!empty($publikasi)){ 
		$no=0; foreach ($publikasi as $data){ $no++; 
		?>
	<tr>
		<td><?php echo $no;?></td>
		<td><?php echo $data->KD_DOSEN;?></td>
		<td>
			<div>
			Dosen : <?php echo $nm_dosen['_'.$data->KD_DOSEN].'<br/>Penerbit : '.$data->PENERBIT;?></div>
			<b>Judul :</b> <?php echo $data->JUDUL_PUBLIKASI;?>
			<div class="tgl">
				<?php echo date('d/m/Y', strtotime($data->TANGGAL_PUB)).' Tingkat '.$data->TINGKAT; ?>
			</div>
		</td>
	</tr>
	<?php }
	}else{	?>
	<tr><td colspan="3">Tidak ada data publikasi yang dapat ditampilkan</td></tr>
	<?php } ?>
	</table>
