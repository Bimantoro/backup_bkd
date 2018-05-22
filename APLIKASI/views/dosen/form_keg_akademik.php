<script type='text/javascript' src="http://akademik.uin-suka.ac.id/asset/js_select2/select2.min.js"></script>
  <link href="http://akademik.uin-suka.ac.id/asset/js2/jquery.autocomplete.css" rel='stylesheet' />
   <link href="http://akademik.uin-suka.ac.id/asset/css_select2/select2.min.css" rel='stylesheet' />
<?php echo $this->s00_lib_output->output_info_dsn();?>
<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/akademik';?>">Kegiatan Akademik</a></li>
</ul>

<?php 
	if(!empty($curr_keg)){
		$kd_ka = $curr_keg[0]['KD_KA'];
		$kd_kat = $curr_keg[0]['KD_KAT'];
		$nm_kat = $curr_keg[0]['NM_KAT'];
		$judul = $curr_keg[0]['JUDUL_KEGIATAN'];
		$mulai = $curr_keg[0]['TGL_MULAI'];
		$selesai = $curr_keg[0]['TGL_SELESAI'];
		$sumber_dana = $curr_keg[0]['SUMBER_DANA'];
		$jumlah_dana = $curr_keg[0]['JUMLAH_DANA'];
		$lokasi = $curr_keg[0]['LOKASI_KEGIATAN'];
		$laman_kegiatan = $curr_keg[0]['LAMAN_KEGIATAN'];
		$act = 'updateAkademik';
		$btn = 'Update';
	}else{
		$kd_ka = '';
		$kd_kat = '';
		$nm_kat = '- Pilih Kategori Kegiatan -';
		$judul = '';
		$mulai = '';
		$selesai = '';
		$sumber_dana = '';
		$jumlah_dana = '';
		$lokasi = '';
		$laman_kegiatan = 'http://';
		$act = 'simpanAkademik';
		$btn = 'Simpan';
	}
?>
<script type="text/javascript">
	$(document).ready(function(){
		 $("#kategori").select2({
                });
		});
</script>
<h2>Form Kegiatan Akademik</h2>
<?php echo form_open('bkd/dosen/bebankerja/'.$act);?>
<input type="hidden" name="kd_ka" value="<?php echo $kd_ka;?>">
<table class="table table-condensed">
<tr><th colspan="4">Data Kegiatan Akademik</th></tr>
	<tr>
		<td width="20%">Kategori</td>
		<td colspan="3">
			<select name="kd_kat" id="kategori" class="input-xxlarge" required>
			<option value="<?php echo $kd_kat;?>"><?php echo $nm_kat;?></option>
			<?php foreach ($kategori as $kat){?>
				<option value="<?php echo $kat->KD_KAT;?>"><?php echo $kat->NM_KAT;?></option>
			<?php } ?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Judul Kegiatan</td>
		<td colspan="3"><textarea name="judul_kegiatan" class="input-xxlarge" id="judul" required><?php echo $judul;?></textarea></td>
	</tr>
	<tr>
		<td>Tanggal Mulai</td>
		<td width="40%"><input type="text" class="datepicker input-medium" name="tgl_mulai" value="<?php echo $mulai;?>" readonly></td>
		<td width="10%">Tanggal Selesai</td>
		<td><input type="text" class="datepicker input-medium" name="tgl_selesai" value="<?php echo $selesai;?>" readonly></td>
	</tr>
	<tr>
		<td>Sumber Dana</td>
		<td colspan="3"><input type="text" name="sumber_dana" class="input-xxlarge" value="<?php echo $sumber_dana;?>"></td>
	</tr>
	<tr>
		<td>Jumlah Dana</td>
		<td colspan="3"><input type="number" name="jumlah_dana" class="input-medium" value="<?php echo $jumlah_dana;?>"></td>
	</tr>
	<tr>
		<td>Lokasi Kegiatan</td>
		<td colspan="3"><input type="text" name="lokasi_kegiatan" class="input-xxlarge" value="<?php echo $lokasi;?>" placeholder=""></td>
	</tr>
	<tr>
		<td>Laman Kegiatan</td>
		<td colspan="3"><input type="url" name="laman_kegiatan" class="input-xxlarge" value="<?php echo $laman_kegiatan;?>" placeholder="Masukkan URL"></td>
	</tr>
	<?php 	
		$cur_data=array();
		if(isset($current_data))$cur_data=array('current_data'=>$current_data);
		$this->load->view('bkd/dosen/bkd_form',$cur_data);
	?>
	<tr>
		<td width="15%">&nbsp;</td>
		<td>
			<input type="submit" name="submit" value="<?php echo $btn;?>" class="btn btn-uin btn-inverse btn-small">
			<input type="reset" name="reset" value="Batal" class="btn btn-uin black btn-small">
		</td>
	</tr>
</table>
<?php echo form_close();?>

<!-- tampilkan beban kerja yang telah diambil -->
<h2>Data Beban Kerja Bidang Hak Kekayaan Intelektual</h2><h3>Tahun Akademik : <?php echo $this->session->userdata('ta');?>, Semester : 
<?php echo $this->session->userdata('smt');?></h3>
<table class="table table-bordered table-hover">
	<tr>
		<th rowspan="2">No</th>
		<th rowspan="2">Jenis Kegiatan</th>
		<th colspan="2">Beban Kerja</th>
		<th rowspan="2">Masa Penugasan</th>
		<th colspan="2">Kinerja</th>
		<th rowspan="2">Rekomendasi</th>
		<th colspan="2" rowspan="2" style="border-right:none">Aksi</th>
	</tr>
	<tr>
		<th>Bukti Penugasan</th>
		<th>SKS</th>
		<th>Bukti Dokumen</th>
		<th>SKS</th>
	</tr>
	<?php 
		if (empty($data_beban)){
			echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>';
		}else{
			$no=0; $sks_beban = 0; $sks_bukti = 0;
			foreach ($data_beban as $bp){ $no++;
				$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
				$sks_bukti = $sks_bukti+$bp->SKS_BKT;
				if($this->uri->segment(6) !== ''){
					if($this->uri->segment(6) == $bp->KD_BK) $bg = '#FFFFDD'; else $bg='';
				}
			?>
			<tr bgcolor="<?php echo $bg;?>">
				<td><?php echo $no;?></td>
				<td><?php echo $bp->JENIS_KEGIATAN;?></td>
				<td><?php echo $bp->BKT_PENUGASAN;?></td>
				<td align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
				<td><?php echo $bp->MASA_PENUGASAN;?></td>
				<td><?php echo $bp->BKT_DOKUMEN;?></td>
				<td align="center"><?php echo (float)$bp->SKS_BKT;?></td>
				<td><?php echo $bp->REKOMENDASI;?></td>
				<td><a href="<?php echo site_url().'bkd/dosen/bebankerja/edit/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" class="btn btn-mini"><i class="icon icon-edit"></i> Edit</a></td>
				<td><a onclick="return hapus('Anda yakin ingin menghapus data ini?')" href="<?php echo site_url().'bkd/dosen/bebankerja/hapus_hybrid/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" class="btn btn-mini">
				<i class="icon icon-trash"></i> Hapus</a></td>
			</tr>
		<?php } ?>
		<tr class="total">
			<td></td>
			<td colspan="2">Jumlah Beban Kerja</td>
			<td align="center"><?php echo $sks_beban?></td>
			<td colspan="2">Jumlah Kinerja</td>
			<td align="center"><?php echo $sks_bukti;?></td>
			<td colspan="4"></td>
		</tr>
	<?php } ?>
</table>
</div>
	
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