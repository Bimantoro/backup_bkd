<script type="text/javascript" src="http://surat.uin-suka.ac.id/asset/js/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="http://surat.uin-suka.ac.id/asset/css/token-input.css" type="text/css" />
<script type="text/javascript" src="<?php echo base_url('/asset/js_select2/select2.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/asset/css_select2/select2.min.css'); ?>">
<script type="text/javascript">
	$(function(){ 
		$('.datepicker').datepicker({
			dateFormat : 'dd/mm/yy',
			buttonImage: 'http://akademik.uin-suka.ac.id/asset/img/calendar.jpg',
			showOn: "button",
			buttonImageOnly: true
		});
	});

	function hapus(msg){
		var conf = confirm(msg);
		if (conf == true) return true;
		else return false;
	}
	
	function autosum(){
		var sks1 = parseFloat(document.getElementById('sks1').value);
		var sks2 = parseFloat(document.getElementById('sks2').value);
		var capaian = (sks2/sks1)*100;
		document.getElementById('capaian').value = Math.round(capaian);
	}
	// FUNGSI AUTO
	function auto_fill(){
		var judul = $("#judul").val();
		var pre = $("#jenis_kategori option:selected").text();
		var kegiatan = pre+' : '+judul;
		$("#kegiatan").val(kegiatan);
	}



</script>

<script>
	$(document).ready(function() {
				$("#jenis_kategori").select2({
				});
			});
</script>

<div id="content">
<div>
	<?php 
		// set value 
		if (empty($current_data)){
			$kd_bkp = '';
			$jenis_keg = '';
			$kd_jenis_keg = '';
			$nm_jenis_keg = '- Pilih Jenis Kegiatan -';
			$bkt_penugasan = '';
			$sks_penugasan = 0;
			$ms_penugasan = ''; $lama = ''; $masa = '';
			$bkt_dokumen = '';
			$sks_bkt = 0;
			$recomendasi = '';
			$tahun = '-- Tahun --';
			$caption = '-- Rekomendasi --';
			$capaian = '';
			$file_penugasan = '';
			$file_capaian = '';
			$kd_kat = '';
			$nm_kat = '- Pilih Kategori -';
			$sumber_dana = '';
			$jumlah_dana = 0;
			$periode_laporan = ''; $pl_awal = $this->session->userdata('thn_prof'); $pl_akhir = $pl_awal;
			$laman_publikasi ='';
			$action = 'simpan_bkp';
			$btn = 'Simpan';
			$class="current";
		}else{
			foreach ($current_data as $value);
			$kd_bkp = $value->KD_BKP;
			$jenis_keg = $value->JENIS_KEGIATAN;
			$kd_jenis_keg = $value->KD_JENIS_KEG;
			$nm_jenis_keg = $value->NM_KEGIATAN;
			$bkt_penugasan = $value->BKT_PENUGASAN;
			$sks_penugasan = $value->SKS_PENUGASAN;
			$ms_penugasan = $value->MASA_PENUGASAN; $a = explode(' ',$ms_penugasan); $lama = $a[0]; $masa = $a[1];
			$bkt_dokumen = $value->BKT_DOKUMEN;
			$sks_bkt = $value->SKS_BKT;
			$recomendasi = $value->REKOMENDASI;
			$tahun = $value->TAHUN;
			$caption = $recomendasi;
			$capaian = $value->CAPAIAN;
			$file_penugasan = $value->FILE_PENUGASAN;
			$file_capaian = $value->FILE_CAPAIAN;
			$kd_kat = $value->KD_KAT;
			$nm_kat = $value->NM_KAT;
			$sumber_dana = $value->SUMBER_DANA;
			$jumlah_dana = $value->JUMLAH_DANA;
			$periode_laporan = $value->PERIODE_LAPORAN; 
			$laman_publikasi = $value->LAMAN_PUBLIKASI;
			$pl_awal = substr($periode_laporan,0,4); $pl_akhir = substr($periode_laporan,5,4);
			$action = 'update_bkp';
			$btn = 'Update';
			$class="";
		}
		$title = 'Beban Kerja Khusus Profesor';
	?>
	<?php echo $this->s00_lib_output->output_info_dsn();?>

	<div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Beban Kerja Dosen</a>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor';?>">Kewajiban Profesor</a>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor/input';?>" class="<?php echo $class;?>">Tambah Data</a>
		</ul><div style="clear:both"></div>
	</div>	

	<div id="modal">
		<!-- form tambah data -->
		<h2><?php echo $title;?></h2>
		<?php echo form_open('bkd/dosen/bebankerja/'.$action); ?>
		<?php echo validation_errors();?>
		<input type="hidden" name="kd_dosen" value="<?php echo $this->session->userdata("kd_dosen");?>">
		<input type="hidden" name="kd_bkp" value="<?php echo $kd_bkp;?>">
			<table class="table table-condensed">
				<tr><th colspan="4">Data Beban Khusus Profesor</th></tr>
				<tr>
					<td width="20%">Kategori Publikasi</td>
					<td colspan="3">
						<select name="kd_kat" class="input-xxlarge" id="jenis_kategori" required>
						<option value="<?php echo $kd_kat;?>"><?php echo $nm_kat;?></option>
						<?php foreach ($kategori as $kat){?>
							<option value="<?php echo $kat->KD_KAT;?>"><?php echo $kat->NM_KAT;?></option>
						<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Judul</td>
					<td colspan="3"><textarea name="jenis_kegiatan" id="judul" class="input-xxlarge" onblur="return auto_fill()" required><?php echo $jenis_keg;?></textarea></td>
				</tr>
				<tr>
					<td>Jenis</td>
					<td>
						<select name="kd_jenis">
							<option value="<?php echo $kd_jenis_keg;?>"><?php echo $nm_jenis_keg;?></option>
							<option value="A">MENULIS BUKU</option>
							<option value="B">KARYA ILMIAH</option>
							<option value="C">MENYEBARLUASKAN GAGASAN</option>
						</select>
					</td>
					<td>Tahun</td>
					<td>
						<select name="tahun" required>
							<option value="<?php echo $tahun;?>"><?php echo $tahun;?></option>
							<?php $now = $this->session->userdata('thn_prof'); if($now == 0) $start = date('Y'); else $start = $now;
								for ($a=$start+4; $a>= $start; $a--){
							?>
							<option value="<?php echo $a;?>"><?php echo $a;?></option>
							<?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Periode Laporan</td>
					<td width="40%">
						<select name="pl_awal" required>
							<option value="<?php echo $pl_awal;?>"><?php echo $pl_awal;?></option>
							<?php $now = $this->session->userdata('thn_prof'); if($now == 0) $start = date('Y'); else $start = $now;
								for ($a=$start+4; $a>= $start; $a--){
							?>
							<option value="<?php echo $a;?>"><?php echo $a;?></option>
							<?php } ?>
						</select>
					</td>
					<td width="15%">s/d</td>
					<td>
						<select name="pl_akhir" required>
							<option value="<?php echo $pl_akhir;?>"><?php echo $pl_akhir;?></option>
							<?php $now = $this->session->userdata('thn_prof'); if($now == 0) $start = date('Y'); else $start = $now;
								for ($a=$start+4; $a>= $start; $a--){
							?>
							<option value="<?php echo $a;?>"><?php echo $a;?></option>
							<?php } ?>
						</select>					
					</td>
				</tr>
				<tr>
					<td>Sumber Dana</td>
					<td colspan="3"><input type="text" name="sumber_dana" class="input-xxlarge" value="<?php echo $sumber_dana;?>"></td>
				</tr>
				<tr>
					<td>Jumlah Dana</td>
					<td colspan="3"><input type="number" name="jumlah_dana" value="<?php echo $jumlah_dana;?>"></td>
				</tr>
				<tr>
					<td>Laman Publikasi</td>
					<td colspan="3"><input type="url" name="laman_publikasi" class="input-xxlarge" value="<?php echo $laman_publikasi;?>" placeholder="Masukkan url: http://www."></td>
				</tr>
				<tr><th colspan="4">Data Beban Kerja</th></tr>
				<tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea name="jenis_kegiatan" class="input-xxlarge" id="kegiatan" required><?php echo $jenis_keg;?></textarea></td>
				</tr>
				<tr>
					<td>Semester</td>
					<td><select name="smt">
						<?php $smt = $this->session->userdata('smt'); if($smt == 'GENAP'){?>
						<option value="GENAP">GENAP</option>
						<option value="GANJIL">GANJIL</option>
						<?php }else{ ?>
						<option value="GANJIL">GANJIL</option>
						<option value="GENAP">GENAP</option>
						<?php } ?>
					</select></td>
					<td>Tahun <br/>Akademik</td>
					<td><select name="ta">
						<option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option>
						<?php $now = date('Y')-1; for ($a=$now-1; $a>=$now-5; $a--){ $b=$a+1; ?><option value="<?php echo $a.'/'.$b;?>"><?php echo $a.'/'.$b;?></option><?php } ?>
					</select></td>
				</tr>
				<tr>
					<td>Masa Penugasan</td>
					<td><input type="number" min="0" name="lama" id="lama" class="input-small" value="<?php echo $lama;?>" required></td>
					<td colspan="2">
						<select name="masa" id="masa" required>
							<option value="<?php echo $masa;?>"><?php echo $masa;?></option>
							<option value="Tahun">Tahun</option>
							<option value="Semester">Semester</option>
							<option value="Bulan">Bulan</option>
							<option value="Hari">Hari</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
					<td>
						<input type="hidden" name="bukti_penugasan" id="bukti">
						<p class="btn-group">
							<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label"><?php echo $bkt_penugasan;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td>SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_penugasan;?>" onBlur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Kinerja</b><br/>Bukti Dokumen</td>
					<td>
						<input type="hidden" name="bukti_dokumen" id="bukti2">
						<p class="btn-group">
							<span class="btn btn-isi2" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari2" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td>SKS : <input type="number" name="sks2" id="sks2" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_bkt;?>" onBlur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td>Capaian</td>
					<td><input type="text" id="capaian" name="capaian" class="input-small" value="<?php echo $capaian;?>" readonly> %</td>
					<td>Rekomendasi</td>
					<td>
						<select name="rekomendasi">
							<option value="<?php echo $recomendasi;?>"><?php echo $caption;?></option>
							<option value="SELESAI">A. SELESAI</option>
							<option value="LANJUTKAN">B. LANJUTKAN</option>
							<option value="BEBEN LEBIH">C. BEBAN LEBIH</option>
							<option value="LAINNYA">D. LAINNYA</option>
						</select>
					</td>
				</tr>
			</table>
		<!-- submit button -->
		<style>.btn.black{ color:#000;} select{ padding:5px; border-bottom:solid 1px #ddd;}</style>
		<table class="table">
		<tr>
			<td width="15%">&nbsp;</td>
			<td>
				<input type="submit" name="submit" value="<?php echo $btn;?>" class="btn btn-uin btn-inverse btn-small">
				<input type="reset" name="reset" value="Batal" class="btn btn-uin black btn-small">
			</td>
		</tr>
		</table>
		<?php echo form_close(); ?>
		
		<h2>Data <?php echo $title;?></h2>
		<!-- tampilkan beban kerja yang telah diambil -->
		<table class="table table-bordered table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomen<br/>dasi</th>
				<th colspan="2" rowspan="2">Aksi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 
				if (empty($data_beban_prof)){
					echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>';
				}else{
					$no=0; $sks_beban = 0; $sks_bukti = 0;
					foreach ($data_beban_prof as $bp){ $no++; 
						$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
						$sks_bukti = $sks_bukti+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
						<td><a class="btn btn-small" href="<?php echo site_url().'bkd/dosen/bebankerja/edit_bkp/'.$bp->KD_BKP;?>"><i class="icon icon-edit"></i> Edit</a></td>
						<td><a class="btn btn-small" onclick="return hapus('Hapus?')" href="<?php echo site_url().'bkd/dosen/bebankerja/hapus_bkp/'.$bp->KD_BKP;?>"><i class="icon icon-edit"></i> Hapus</a></td>
					</tr>
				<?php } ?>
				<tr>
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $sks_beban; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $sks_bukti;?></td>
					<td colspan="4"></td>
				</tr>
			<?php } ?>
		</table>
	</div>
		
	<!-- form upload file penugasan dan -->
	<?php $this->load->view('dosen/isi_dokumen_prof');?>
	<?php $this->load->view('dosen/cari_dokumen_rbkd');?>
	<!-- end -->

</div>
</div>
