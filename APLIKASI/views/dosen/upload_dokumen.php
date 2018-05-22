<?php 
	foreach ($current_data as $data);
	$aksi = $this->uri->segment(7);
	$_aksi = explode('-',$aksi);
	if($_aksi[0] == 'penugasan')
		$value = $data->BKT_PENUGASAN;
	else $value = $data->BKT_DOKUMEN;
	
	# cek dosen prof
	if($this->uri->segment(4) == 'profesor'){
		$link_close = base_url().'bkd/dosen/bebankerja/profesor/detail/'.$this->uri->segment(6);
		$action = 'upload_dokumen_prof';
	}else{
		$link_close = base_url().'bkd/dosen/bebankerja/data/'.$kode;
		$action = 'upload_dokumen';
	}
?>
<div id="modal">
	<div class="title-bar">Upload File <?php echo ucwords($_aksi[0]);?> 
		<span class="close"><a href="<?php echo $link_close;?>"><i class="icon-remove icon-white"></i></a></span>
	</div>
	<?php echo form_open_multipart('bkd/dosen/bebankerja/'.$action);?>
	
		<?php 
			$err = $this->uri->segment(8);
			if($err != ''){
				$err = str_replace('-',',',$err);
				$err = str_replace('%20',' ',$err);
				echo "<div class='alert alert-error'>".$err."</div>";
			}
		?>
	
		<input type="hidden" name="kd_bk" value="<?php echo $this->uri->segment(6);?>">
		<input type="hidden" name="kode" value="<?php echo $kode;?>">
		<input type="hidden" name="aksi" value="<?php echo $this->uri->segment(7);?>">
		<table class="table">
		<tr>
			<th>Bukti <?php echo ucwords($_aksi[0]);?></th>
			<td><input type="text" name="bukti" class="input-xxlarge" value="<?php echo $value;?>"></td>
		</tr>
		<tr>
			<th>File <?php echo ucwords($_aksi[0]);?></th>
			<td><input type="file" name="file_upload" class="btn btn-small"><br/>
				<div><div class="bs-callout-info" style="margin-top:5px; font-style:italic; border-left:solid 5px skyblue; padding:4px">Type file yang diperbolehkan adalah <b>JPG</b> atau <b>PDF</b></div></div>
			</td>
		</tr>
		<tr><td></td><td><input type="submit" name="submit" value="Upload File Penugasan" class="btn btn-small btn-inverse btn-uin"></td></tr>
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