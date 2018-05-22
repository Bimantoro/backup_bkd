<?php 
	foreach ($current_data as $data);
	$aksi = $this->uri->segment(7);
	$_aksi = explode('-',$aksi);
	if($_aksi[0] == 'penugasan')
		$value = $data->BKT_PENUGASAN;
	else $value = $data->BKT_DOKUMEN;
	# cek dosen prof
	if($this->uri->segment(4) == 'profesor'){
		$link_close = base_url().'bkd/dosen/remun/profesor/detail/'.$this->uri->segment(6);
		$action = 'upload_dokumen_prof';
	}else{
		$link_close = base_url().'bkd/dosen/remun/data/'.$kode;
		$action = 'upload_dokumen';
	}
?>
<div id="modal">
	<div class="title-bar">Isi Bukti <?php echo ucwords($_aksi[0]);?> Remun  
		<span class="close"><i class="icon-remove icon-white" onclick="return window.history.back()"></i></span>
	</div>
	<?php echo form_open_multipart('bkd/dosen/remun/'.$action);?>
		<input type="hidden" name="kd_bk" value="<?php echo $this->uri->segment(6);?>">
		<input type="hidden" name="kode" value="<?php echo $kode;?>">
		<input type="hidden" name="aksi" value="<?php echo $this->uri->segment(7);?>">
		<table class="table">
		<tr>
			<th>Bukti <?php echo ucwords($_aksi[0]);?> Remun</th> 
			<td><input type="text" name="bkt_penugasan" class="input-xxlarge" value="<?php echo $value;?>"></td>
		</tr>
		<tr><td></td><td><input type="submit" name="submit" value="Simpan Bukti Penugasan" class="btn btn-small btn-inverse btn-uin"></td></tr>
		</table>
	<?php echo form_close();?>
</div>
<style>
	#modal{
		position:fixed;
		left:30%; top:15%;
		border:solid 5px #444;
		border-radius:10px;
		background-color:#FFF;
		z-index:10;
		box-shadow:0 0 0 1000px #EEE;
	}
	.title-bar{
		padding:6px; font-weight:bold;
		color:#FFF; background-color:#444;
		border-bottom:solid 1px #444;
	}
	form{
		margin:10px;
	}
</style>