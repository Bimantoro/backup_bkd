<?php echo $this->s00_lib_output->output_info_dsn();?>
<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/akademik';?>">Kegiatan Akademik</a></li>
</ul>
<pre><?php #print_r($this->session->all_userdata());?></pre>
<?php 
	if(!empty($curr_keg)){
		$kd_np = $curr_keg[0]['KD_NP'];
		$kd_tingkat = $curr_keg[0]['KD_TINGKAT'];
		$nm_tingkat = $curr_keg[0]['NM_TINGKAT'];
		$judul = $curr_keg[0]['JUDUL_ACARA'];
		$mulai = $curr_keg[0]['TGL_MULAI'];
		$selesai = $curr_keg[0]['TGL_SELESAI'];
		$lokasi = $curr_keg[0]['LOKASI_ACARA'];
		$laman_kegiatan = $curr_keg[0]['LAMAN_PUBLIKASI'];
		$act = 'updateNarasumber';
		$btn = 'Update';
	}else{
		$kd_np = '';
		$kd_tingkat = '';
		$nm_tingkat = '- Pilih Tingkat Acara - ';
		$judul = '';
		$mulai = '';
		$selesai = '';
		$lokasi = '';
		$laman_kegiatan = 'http://';
		$act = 'simpanNarasumber';
		$btn = 'Simpan';
	}
?>
<h2>Form Narasumber/Pembicara</h2>
<?php echo form_open('bkd/dosen/bebankerja/'.$act);?>
<input type="hidden" name="kd_np" value="<?php echo $kd_np;?>">
<table class="table table-condensed">
	<tr>
		<td width="20%">Tingkat</td>
		<td colspan="3">
			<select name="kd_tingkat" class="input-xxlarge" required>
			<option value="<?php echo $kd_tingkat;?>"><?php echo $nm_tingkat;?></option>
			<?php foreach ($kategori as $kat){?>
				<option value="<?php echo $kat['KD_TINGKAT'];?>"><?php echo $kat['NM_TINGKAT'];?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Judul Acara</td>
		<td colspan="3"><textarea name="judul_acara" class="input-xxlarge" id="judul" required><?php echo $judul;?></textarea></td>
	</tr>
	<tr>
		<td>Semester</td>
		<td width="40%"><input type="text" name="smt" class="input-medium" value="<?php echo $this->session->userdata('smt');?>" readonly></td>
		<td width="10%">Tahun Akademik</td>
		<td><input type="text" name="ta" class="input-medium" value="<?php echo $this->session->userdata('ta');?>" readonly></td>
	</tr>
	<tr>
		<td>Tanggal Mulai</td>
		<td width="40%"><input type="text" class="datepicker input-medium" name="tgl_mulai" value="<?php echo $mulai;?>" readonly></td>
		<td width="10%">Tanggal Selesai</td>
		<td><input type="text" class="datepicker input-medium" name="tgl_selesai" value="<?php echo $selesai;?>" readonly></td>
	</tr>
	<tr>
		<td>Lokasi Acara</td>
		<td colspan="3"><input type="text" name="lokasi_acara" class="input-xxlarge" value="<?php echo $lokasi;?>" placeholder=""></td>
	</tr>
	<tr>
		<td>Laman Publikasi</td>
		<td colspan="3"><input type="url" name="laman_publikasi" class="input-xxlarge" value="<?php echo $laman_kegiatan;?>" placeholder="Masukkan URL"></td>
	</tr>
	<tr>
		<td width="15%">&nbsp;</td>
		<td>
			<input type="submit" name="submit" value="<?php echo $btn;?>" class="btn btn-uin btn-inverse btn-small">
			<input type="reset" name="reset" value="Batal" class="btn btn-uin black btn-small">
		</td>
	</tr>
</table>
<?php echo form_close();?>
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