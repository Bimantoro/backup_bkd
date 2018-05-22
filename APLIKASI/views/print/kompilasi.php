<style>
.table th{ font-weight: bold; border-bottom:solid 2px #333; border-top:solid 2px #333; text-align:center;}
.table td{ border-bottom:solid 1px #333; padding:7px; }
#footer_pdf{ border: solid 1px #aaa; padding:2px; position:fixed;} 
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
	<table class="table" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<th rowspan="2" width="12">No</th>
			<th rowspan="2" width="40">No Sertifkat</th>
			<th rowspan="2" width="60">Nama Dosen</th>
			<th rowspan="2" width="60">Fakultas</th>
			<th colspan="4" width="60">Semester Ganjil</th>
			<th colspan="4" width="60">Semester Genap</th>
			<th rowspan="2">Kewajiban<br/>Khusus<br/>Profesor</th>
			<th rowspan="2">Status</th>
			<th rowspan="2" width="25">Kesimpulan</th>
		</tr>
		<tr>
			<th width="15">Pd</th>
			<th width="15">Pl</th>
			<th width="15">Pg</th>
			<th width="15">Pk</th>
			<th width="15">Pd</th>
			<th width="15">Pl</th>
			<th width="15">Pg</th>
			<th width="15">Pk</th>
		</tr>
		<?php if (empty($kompilasi)){ ?>
		<tr><td colspan="15" align="center">BEBAN KERJA MASIH KOSONG PADA TAHUN AJARAN <?php echo $ta;?></td></tr>
		<?php } else { 
			$no=0; foreach ($kompilasi as $data) { 
			
			if(in_array($data->KD_DOSEN, $dosen_tetap)){
			
				$no++; 
				$tot_ganjil = $data->PD1 + $data->PL1 + $data->PG1 + $data->PK1;
				$tot_genap = $data->PD2 + $data->PL2 + $data->PG2 + $data->PK2;
				$tot_tahun = $tot_ganjil + $tot_genap;
				if($tot_tahun >= 24 && $tot_tahun <= 32){ $kesimpulan = 'M'; } else {$kesimpulan = 'T'; }
			?>
		<tr>
			<td><?php echo $no;?></td>
			<td><?php echo $data->NO_SERTIFIKAT;?></td>
			<td><?php echo $namaDosen['_'.$data->KD_DOSEN];?></td>
			<td><?php echo str_replace('Fakultas','',ucwords(strtolower($namaFakultas['_'.$data->KD_DOSEN])));?></td>
			<td align="center"><?php echo (float)$data->PD1;?></td>
			<td align="center"><?php echo (float)$data->PL1;?></td>
			<td align="center"><?php echo (float)$data->PG1;?></td>
			<td align="center"><?php echo (float)$data->PK1;?></td>
			<td align="center" bgcolor="#F7F7F7"><?php echo (float)$data->PD2;?></td>
			<td align="center" bgcolor="#F7F7F7"><?php echo (float)$data->PL2;?></td>
			<td align="center" bgcolor="#F7F7F7"><?php echo (float)$data->PG2;?></td>
			<td align="center" bgcolor="#F7F7F7"><?php echo (float)$data->PK2;?></td>
			<td align="center"><?php echo (float)$data->PR1+$data->PR2;?></td>
			<td align="center"><?php echo $data->KD_JENIS_DOSEN;?></td>
			<td align="center"><?php echo $kesimpulan;?></td>
		</tr>
		<?php }
		} } ?>
	</table>