<script>
$(function() {
    $( ".datepicker" ).datepicker({
		dateFormat : 'dd/mm/yy',
		buttonImage: 'http://akademik.uin-suka.ac.id/asset/img/calendar.jpg',
		showOn: "button",
		buttonImageOnly: true
	});
 });
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
<?php #print_r($identity);?>
<h2>Data Kepegawaian</h2>
<div id="content">
	<?php //print_r($data)?>
	<div>
		<?php foreach ($identity as $data);?>
		<?php 
			if($data->TMT_JABATAN == '') $t_jabatan = ''; else $t_jabatan = date('d/m/Y', strtotime($data->TMT_JABATAN)); 
			if($data->TMT_GOLONGAN == '') $t_golongan = ''; else $t_golongan = date('d/m/Y', strtotime($data->TMT_GOLONGAN)); ?>
		<?php echo form_open('bkd/dosen/biodata/update_kepegawaian');?>
		<table class="table table-hover">
		<tr>
			<td>Kementerian Induk</td>
			<td colspan="3"><input type="text" name="kementerian_induk" value="Kementerian Agama" disabled></td>
		</tr>
		<tr>
			<td>Status</td>
			<td colspan="3">
				<select name="status_peg" disabled>
					<!-- <option value="<?php echo $data->STATUS_PEG;?>"><?php echo $data->STATUS_PEG;?></option> -->
					<option value="PNS" <?php if($data->STATUS_PEG == 'PNS') echo "selected=selected";?>>PNS</option>
					<option value="CPNS" <?php if($data->STATUS_PEG == 'CPNS') echo "selected=selected";?>>CPNS</option>
					<option value="NON-PNS" <?php if($data->STATUS_PEG == 'NON-PNS') echo "selected=selected";?>>NON-PNS</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Nomor Kartu Pegawai</td>
			<td colspan="3"><input type="text" name="no_kartu" value="<?php echo $data->NO_KARTU;?>" disabled></td>
		</tr>
		<tr>
			<td>Pangkat/Golongan</td>
			<td><input type="text" name="kd_golongan" value="<?php echo $golongan_dosen.'/'.$ruang_dosen;?>" disabled></td>
			<td>Tmt Pangkat</td>
			<td colspan="3"><input type="text" name="tmt_golongan" value="<?php echo $t_golongan;?>" disabled></td>
		</tr>
		<tr>
			<td>Jab. Akademik</td>
			<td><input type="text" name="kd_jabatan" value="<?php echo $fungsional_dosen;?>" disabled></td>
			<td>Tmt Jabatan</td>
			<td colspan="3"><input type="text" name="tmt_jabatan" value="<?php echo $t_jabatan;?>" disabled></td>
		</tr>
		<tr>
			<td>NIP</td>
			<td colspan="3"><input type="text" name="nip" size="60" value="<?php echo $data->KD_DOSEN;?>" disabled></td>
		</tr>
		<tr>
			<td>No Sertifikat (Serdos)</td>
			<td colspan="3"><input type="text" name="no_sertifikat" size="60" value="<?php echo $data->NO_SERTIFIKAT;?>" disabled></td>
		</tr>
		<tr>
			<td>Tanggal Mulai<br/>Menjadi Dosen (CPNS)</td>
			<td colspan="3"><input title="Anda tidak dapat mengubah tanggal CPNS" type="text" name="tgl_sk_pns" size="60" value="<?php echo date('d/m/Y', strtotime($pegawai[0]->TGL_SK_PNS));?>" disabled></td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3"><input type="submit" name="submit" value="Simpan dan Lanjut" class="btn btn-inverse btn-uin btn-small"></td>
		</tr>
		</table>
		<?php echo form_close();?>
	</div>
</div>