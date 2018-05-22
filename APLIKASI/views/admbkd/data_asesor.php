<div id="content">
	<!-- menu atas -->
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Master</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/asesor/data';?>">Daftar Asesor</a></li>
	</ul>
	<div>
		<h2><?php echo $title;?></h2>
		<?php foreach ($data_asesor as $data);	?>
		<table class="table table-bordered table-hover">
		<tr>
			<th width="150">NIRA</th>
			<td> : <?php echo $data->NIRA;?></td>
		</tr>
		<tr>
			<th>KD Dosen/NIP</th>
			<td> : <?php echo $data->KD_DOSEN;?></td>
		</tr>
		<tr>
			<th>Nama Asesor</th>
			<td colspan="3"> : <?php echo ucwords(strtolower($data->NM_ASESOR));?></td>
		</tr>
		<tr>
			<th>Phone</th>
			<td colspan="3"> : <?php echo $data->TELP;?></td>
		</tr>
		<tr>
			<th>Perguruan Tinggi</th>
			<td colspan="3"> : <?php echo $data->NM_PT;?></td>
		</tr>
		<tr>
			<th>Rumpun</th>
			<td colspan="3"> : <?php echo $data->RUMPUN;?></td>
		</tr>
		</tr>
		<tr>
			<th>Bidang Ilmu</th>
			<td colspan="3"> : <?php echo $data->BIDANG_ILMU;?></td>
		</tr>
		</tr>
		</table>
	</div>
</div>