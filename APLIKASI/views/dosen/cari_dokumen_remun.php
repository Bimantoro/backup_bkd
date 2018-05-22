<script type="text/javascript">
 	$(document).ready(function(){
		$("#surat").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/remun/get_surat");
	}); 
</script>
<style>
.grup{
	background-color: #EEEEEE;
}
.auto-surat{
	float: left;
	margin: 0px 0px 10px 0px;
	border: 1px solid #CCCCCC;
	border-radius: 4px;
	padding: 1px;
	width: 566px;
}
.label-input {
	width: 64px;
	float: left;
	padding:8px;
}
.tujuan-surat input:focus{
	box-shadow:none;
}
</style>
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
	<div class="title-bar">Cari Bukti <?php echo ucwords($_aksi[0]);?> Remun  
		<span class="close"><a href="<?php echo $link_close;?>"><i class="icon-remove icon-white"></i></a></span>
	</div>
	<?php echo form_open_multipart('bkd/dosen/remun/'.$action);?>
		<input type="hidden" name="kd_bk" value="<?php echo $this->uri->segment(6);?>">
		<input type="hidden" name="kode" value="<?php echo $kode;?>">
		<input type="hidden" name="aksi" value="<?php echo $this->uri->segment(7);?>">
		<table class="table table-condensed">
		<tr>
			<th>Bukti Remun <?php echo ucwords($_aksi[0]);?></th>
			<td>
				<div class="tujuan-surat">
					<div class="auto-surat grup">
						<div class="label-input"><i class="icon icon-search"></i> </div>
						<input type="text" name="surat" id="surat"/>
					</div>
				</div>
			</td>
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
		z-index:1;
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
	.close{ color:#FFF;}
</style>