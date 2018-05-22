<?php
$filename = str_replace(' ','_','Kompilasi_Perguruan_Tinggi.xls');
header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename = $filename");
header("Pragma: no-cache");
header("Expires: 0");
?>
<style>
.table th{ font-weight: bold; border-bottom:solid 2px #333; border-top:solid 1px #333; text-align:center;}
.table td{ border-bottom:solid 1px #333; }
</style>

	<table border="1" cellpadding="2"><tr><td colspan="15" align="center"><?php echo $judul;?></td></tr></table><br/>
	<?php if ($this->session->userdata('adm_univ') == ''){ ?>
	<div>Nama Perguruan Tinggi : <?php echo $pt;?></div>
	<div>Nama <?php echo $label;?> : <?php echo ucwords(strtolower($label_value)); ?></div>
	<br/>
	<?php }else{ ?>
	<div>Nama Perguruan Tinggi : <?php echo $pt;?></div>
	<div>Alamat Perguruan Tinggi</td><td>: <?php echo $alamat;?></div>
	<br/>
	<?php } ?>
	<table class="table" border="0" cellspacing="0" cellpadding="2">
		<tr>
			<th rowspan="2" width="12">No</th>
			<th rowspan="2" width="50">No Sertifkat</th>
			<th rowspan="2" width="40">Nama Dosen</th>
			<th rowspan="2" width="60">Fakultas</th>
			<th colspan="4" width="60">Semester Ganjil</th>
			<th colspan="4" width="60">Semester Genap</th>
			<th rowspan="2">Kewajiban<br/>Khusus<br/>Profesor</th>
			<th rowspan="2">Status</th>
			<th rowspan="2" width="30">Kesimpulan</th>
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
		<tr><td colspan="14">Beban Kerja masih kosong pada tahun ajaran <?php echo $ta;?></td></tr>
		<?php } else { 
			$no=0; foreach ($kompilasi as $data) { $no++;
				$tot_ganjil = $data->PD1 + $data->PL1 + $data->PG1 + $data->PK1;
				$tot_genap = $data->PD2 + $data->PL2 + $data->PG2 + $data->PK2;
				$tot_tahun = $tot_ganjil + $tot_genap;
				if($tot_tahun >= 24 && $tot_tahun <= 32){ $kesimpulan = 'M'; } else {$kesimpulan = 'T'; }
		?>
		<tr>
			<td><?php echo $no;?></td>
			<td>'<?php if ($data->NO_SERTIFIKAT == "") echo '-'; else echo $data->NO_SERTIFIKAT;?></td>
			<td><?php echo $data->NM_DOSEN_F;?></td>
			<td><?php echo str_replace('Fakultas','',ucwords(strtolower($data->NM_FAK)));?></td>
			<td align="center"><?php echo (float)$data->PD1;?></td>
			<td align="center"><?php echo (float)$data->PL1;?></td>
			<td align="center"><?php echo (float)$data->PG1;?></td>
			<td align="center"><?php echo (float)$data->PK1;?></td>
			<td align="center" bgcolor="#F7F7F7"><?php echo (float)$data->PD2;?></td>
			<td align="center" bgcolor="#F7F7F7"><?php echo (float)$data->PL2;?></td>
			<td align="center" bgcolor="#F7F7F7"><?php echo (float)$data->PG2;?></td>
			<td align="center" bgcolor="#F7F7F7"><?php echo (float)$data->PK2;?></td>
			<td align="center"><?php echo $data->PR1+$data->PR2;?></td>
			<td align="center"><?php echo $data->KD_JENIS_DOSEN;?></td>
			<td align="center"><?php echo $kesimpulan;?></td>
		</tr>
		<?php } 
		} ?>
	</table>

