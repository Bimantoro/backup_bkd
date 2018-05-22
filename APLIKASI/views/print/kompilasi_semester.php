<style>
.table th{ font-size:7pt; font-weight:bold; border-bottom:solid 2px #333; border-top:solid 2px #333; text-align:center;}
.table td{ font-size:7pt; border-bottom:solid 1px #333; padding:7px; }
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

	<table class="table" border="0" cellpadding="2">
		<tr>
			<th rowspan="2" width="12">No</th>
			<th rowspan="2" width="40">No Sertifikat</th>
			<th rowspan="2" width="65">Nama Dosen</th>
			<th colspan = "4" width="50">Semester <?php echo ucwords(strtolower($smt));?></th>
			<th rowspan="2">Kewajiban<br/>Khusus<br/>Profesor</th>
			<th rowspan="2">Status</th>
			<th rowspan="2" width="30">Kesimpulan</th>
		</tr>
		<tr>
			<th width="14">Pd</th>
			<th width="12">Pl</th>
			<th width="12">Pg</th>
			<th width="12">Pk</th>
		</tr>
		<?php if (empty($kompilasi)){ ?>
		<tr><td colspan="10" align="center">TIDAK ADA DATA BEBAN KERJA YANG DAPAT DITAMPILKAN PADA TAHUN AKADEMIK <?php echo $ta;?></td></tr>
		<?php } else { $no=0; foreach ($kompilasi as $data) { 
		
		if(in_array($data->KD_DOSEN, $dosen_tetap)){
		
			$no++; 
			if($data->NO_SERTIFIKAT == '') $noser = '-'; else $noser = $data->NO_SERTIFIKAT;
		?>
		<tr>
			<td><?php echo $no;?></td>
			<td><?php echo $noser;?></td>
			<td><?php echo $namaDosen['_'.$data->KD_DOSEN];?></td>
			<?php if($smt == 'GANJIL'){ 
				$tot_ganjil = $data->PD1 + $data->PL1 + $data->PG1 + $data->PK1;
				if($tot_ganjil >= 12 && $tot_ganjil <= 16){ $kesimpulan = 'M'; } else {$kesimpulan = 'T'; } ?>
				<td align="center"><?php echo (float)$data->PD1;?></td>
				<td align="center"><?php echo (float)$data->PL1;?></td>
				<td align="center"><?php echo (float)$data->PG1;?></td>
				<td align="center"><?php echo (float)$data->PK1;?></td>
				<td align="center"><?php echo (float)$data->PR1;?></td>
				<td align="center"><?php echo $ds['_'.$data->KD_DOSEN];?></td>
				<td align="center"><?php echo $kesimpulan;?></td>
			<?php }else{ 
				$tot_genap = $data->PD2 + $data->PL2 + $data->PG2 + $data->PK2;
				if($tot_genap >= 12 && $tot_genap <= 16){ $kesimpulan = '<span class="badge badge-success">M</span>'; }
				else {$kesimpulan = '<span class="badge badge-important">T</span>'; } ?>
				<td align="center"><?php echo (float)$data->PD2;?></td>
				<td align="center"><?php echo (float)$data->PL2;?></td>
				<td align="center"><?php echo (float)$data->PG2;?></td>
				<td align="center"><?php echo (float)$data->PK2;?></td>
				<td align="center"><?php echo (float)$data->PR2;?></td>
				<td align="center"><?php echo $ds['_'.$data->KD_DOSEN];?></td>
				<td align="center"><?php echo $kesimpulan;?></td>
			<?php } ?>
		</tr>
		<?php } 
		} } ?>	
	</table>

