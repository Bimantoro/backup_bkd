<script>
/*$(function() {
    $( ".datepicker" ).datepicker({
		dateFormat : 'dd/mm/yy',
		buttonImage: 'http://akademik.uin-suka.ac.id/asset/img/calendar.jpg',
		showOn: "button",
		buttonImageOnly: true
	});
 });
 
 function hapus(msg){
	var conf = confirm(msg);
	if(conf == true) return true;
	else return false;
 }*/
</script>
<?php echo $this->s00_lib_output->output_info_dsn();?>
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_biodata';?>">Identitas</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_kepegawaian';?>">Kepegawaian</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_akademik';?>">Akademik</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_riwayat_pendidikan';?>">Riwayat Pendidikan</a></li>
</ul>
<div id="content">
	<div>
	<h2>Riwayat Pendidikan Dosen</h2>
	<table class="table table-bordered">
	<tr>
		<th>No</th>
		<th>Universitas/Prodi</th>
		<th>Bidang Ilmu</th>
		<th>Jenjang</th>
		<th>Tanggal Masuk</th>
		<th>Tanggal Lulus</th>
		<th>&nbsp;</th>
	</tr>
	
	<?php if(!empty($pendidikan)){ $no=0; foreach($pendidikan as $data){ $no++;?>
	<tr>
		<td><?php echo $no;?></td>
		<td><?php echo $data->NM_PT.' - '.$data->NM_PRODI;?></td>
		<td><?php echo $data->BIDANG_ILMU;?></td>
		<td><?php echo $data->JENJANG;?></td>
		<td><?php echo date('d/m/Y', strtotime($data->TGL_MULAI));?></td>
		<td><?php echo date('d/m/Y', strtotime($data->TGL_SELESAI));?></td>
		<td><a href="<?php echo site_url().'bkd/dosen/biodata/delete_riwayat_pendidikan/'.$data->KD_RIWAYAT;?>" class="btn btn-small btn-inverse btn-uin">Hapus</a></td>
	</tr>
	<?php }} else { echo "<tr><td colspan='6' align='center'>BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>"; }?>
	</table>
	<tr>
	<?php echo form_open_multipart('bkd/dosen/biodata/simpan_riwayat_pendidikan');?>
	<?php echo validation_errors(); ?>
	</br>
        <h2>Tambah Riwayat Pendidikan Dosen</h2>
		<table border="0" cellspacing="0" cellpadding="2" class="table table-hover">
		<td>Nama Perguruan Tinggi</td>
			<td colspan="3"><input type="text" name="nm_pt" size="30" value="" required></td>
		</tr>
		<tr>
			<td>Jurusan/prodi</td>
			<td colspan="3"><input type="text" name="nm_prodi" size="30" value="" required></td>
		</tr>
		<tr>
			<td>Bidang Ilmu</td>
			<td colspan="3"><input type="text" name="bidang_ilmu" size="40" value="" required></td>
		</tr>
		<tr>
			<td>Jenjang Pendidikan</td>
			<td colspan="3">
				<select name="jenjang" required>
					<option value="">-Pilih-</option>
					<option value="S1">Strata 1 (S1)</option>
					<option value="S2">Strata 2 (S2)</option>
					<option value="S3">Strata 3 (S3)</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Tanggal Masuk</td>
			<td colspan="3"><input type="text" name="tanggal_masuk" class="datepicker input-medium" value=""></td>
		</tr>
		<tr>
			<td>Tanggal Lulus</td>
			<td colspan="3"><input type="text" name="tanggal_lulus" class="datepicker input-medium" value=""></td>
		</tr>
		<tr>
			<td>Gelar</td>
			<td colspan="3"><input type="text" name="gelar" size="40" value="" required></td>
		</tr>
			<input type="hidden" name="sumber_dana" size="40" value="">
		<tr>
			<td></td>
			<td colspan="3"><input type="submit" name="submit" value="Tambahkan" class="btn btn-uin btn-inverse btn-small">
		</tr>
		</table>
		<?php echo form_close();?>
	</div>
</div>
<!-- script -->
<script type="text/javascript">
	$(function() {
		$( ".datepicker" ).datepicker({
			dateFormat : 'dd/mm/yy',
			buttonImage: 'http://akademik.uin-suka.ac.id/asset/img/calendar.jpg',
			showOn: "button",
			buttonImageOnly: true
		});
	 });
</script>