<style>
a.link{ color:#222; text-decoration:underline;}
.title-bar{ font-weight:bold; color:#555;}
.subkey{
	background-color:#EEE;
	color:#999;
	font-weight:normal;
	font-style:italic;
	padding:5px;
}
select option{ padding:8px; }
.search td{ border-bottom:solid 1px #DDD; }
</style>
<div id="content">
<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Beban Kerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/dosen/'.$this->uri->segment(5);?>">Kompilasi Dosen</a></li>
	</ul>
	<br/>
	<?php echo form_open();?>
	<table class="table table-hover">
		<tr><td colspan="3" class="title-bar"><i class="icon icon-search"></i> Masukkan Keyword</td></tr>
		<tr class="search">
			<td>
				<select name="subkey" class="subkey">
					<option value="C.NM_DOSEN_F">Nama Dosen</option>
					<option value="C.NIP">NIP Dosen</option>
				</select>
			</td>
			<td><input type="text" name="keyword" class="input-xlarge" placeholder="Masukkan keyword" required></td>
			<td><input type="submit" class="btn btn-mini btn-inverse btn-uin" value="Cari"></td>
		</tr>
	</table>
	<?php echo form_close();?>
	<?php if(!empty($kompilasi)){ 
		#echo $subkey.':'.$keyword;
	?>
	<table class="table table-hover">
	<tr>
		<th rowspan="2">No</th>
		<th rowspan="2">No Sertifikat</th>
		<th rowspan="2">Nama Dosen</th>
		<th colspan="4">Semester Ganjil</th>
		<th colspan="4">Semester Genap</th>
		<th rowspan="2">Kewajiban<br/>Khusus<br/>Profesor</th>
		<th rowspan="2">Kesimpulan</th>
	</tr>
	<tr>
		<th>Pd</th>
		<th>Pl</th>
		<th>Pg</th>
		<th>Pk</th>
		<th>Pd</th>
		<th>Pl</th>
		<th>Pg</th>
		<th>Pk</th>
	</tr>
	<?php $no=0; foreach($kompilasi as $data){ $no++;
			if($data->NO_SERTIFIKAT == '') $noser = '-'; else $noser = $data->NO_SERTIFIKAT;
				$tot_ganjil = $data->PD1 + $data->PL1 + $data->PG1 + $data->PK1;
				$tot_genap = $data->PD2 + $data->PL2 + $data->PG2 + $data->PK2;
				$tot_tahun = $tot_ganjil + $tot_genap;
				if($tot_tahun >= 24 && $tot_tahun <= 32){ $kesimpulan = '<span class="badge badge-success">M</span>'; } else {$kesimpulan = '<span class="badge badge-important">T</span>'; }
	?>
		<tr>
			<td><?php echo $no;?></td>
			<td><?php echo $noser;?></td>
			<td><a class="link" href="<?php echo base_url().'bkd/admbkd/kompilasi/detail/'.$data->KD_DOSEN;?>"><?php echo $data->NM_DOSEN_F;?></a></td>
			<td align="center"><?php echo $data->PD1;?></td>
			<td align="center"><?php echo $data->PL1;?></td>
			<td align="center"><?php echo $data->PG1;?></td>
			<td align="center"><?php echo $data->PK1;?></td>
			<td align="center"><?php echo $data->PD2;?></td>
			<td align="center"><?php echo $data->PL2;?></td>
			<td align="center"><?php echo $data->PG2;?></td>
			<td align="center"><?php echo $data->PK2;?></td>
			<td align="center"><?php echo $data->PR1+$data->PR2;?></td>
			<td align="center"><?php echo $kesimpulan;?></td>
		</tr>
	<?php } ?>
	</table>
	<?php } ?>
	<?php echo $label;?>
</div>
</div>
