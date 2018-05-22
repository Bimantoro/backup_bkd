<?php echo $this->s00_lib_output->output_info_dsn();?>
<?php foreach ($data_asesor as $data);	?>
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_biodata';?>">Identitas</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/asesor/'.$data->NIRA;?>">Data Asesor</a></li>
</ul>
<div id="content">
	<div>
		<h2><?php echo $title;?></h2>
		<table border="0" width="100%" cellspacing="0" cellpadding="5" class="table table-hover table-bordered">
		<tr>
			<th width="200">NIRA</th>
			<td> : <?php echo $data->NIRA;?></td>
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