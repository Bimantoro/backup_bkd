<script type="text/javascript" src="http://surat.uin-suka.ac.id/asset/js/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="http://surat.uin-suka.ac.id/asset/css/token-input.css" type="text/css" />
<script type="text/javascript">
 	$(document).ready(function() {
		$("#asesor").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/biodata/get_asesor",{
			tokenLimit:'2',
			prePopulate:[
				<?php if($asdos[0]->NIRA1 != ''){?>
					{id:'<?php echo $asdos[0]->NIRA1;?>', name:"<?php echo $namaAsesor1.' ('.$asdos[0]->NIRA1.')';?>"},
				<?php } if($asdos[0]->NIRA2 != ''){?>
					{id:'<?php echo $asdos[0]->NIRA2;?>', name:'<?php echo $namaAsesor2.' ('.$asdos[0]->NIRA2.')';?>'},
				<?php } ?>
			],
		});
	});
</script>
<script type="text/javascript" src="http://akademik.uin-suka.ac.id/asset/js/jquery.autocomplete.min.js"></script>
<script type="text/javascript">
$(function(){
	var kecamatan = <?php echo $kec;?>;
	$('#kecamatan').autocomplete({
		lookup: kecamatan,
		onSelect: function (suggestion) {
			var thehtml = suggestion.data;
			var data = thehtml.split('#');
			$('#kabupaten').val(data[2]);
			$('#propinsi').val(data[4]);
			$('#kd_kab').val(data[1]);
			$('#kd_prop').val(data[3]);
			$('#kd_kec').val(data[0]);
		}
	});
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

.autocomplete-suggestions { border: 1px solid #999; background: #fff; cursor: default; overflow: auto; }
.autocomplete-suggestion { padding: 5px; font-size: 1em; white-space: nowrap; overflow: hidden; }
.autocomplete-selected { background: #f0f0f0; }
.autocomplete-suggestions strong { font-weight: bold; color: #222; }
</style>
<?php echo $this->s00_lib_output->output_info_dsn();?>
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_biodata';?>">Identitas</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_kepegawaian';?>">Kepegawaian</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_akademik';?>">Akademik</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_riwayat_pendidikan';?>">Riwayat Pendidikan</a></li>
</ul>
<h2>Identitas</h2>
<div id="content">
	<div>
		<?php 
		if(!empty($identity)){
			foreach ($identity as $data);
				if($data->J_KELAMIN !== 'P'){
					$check_l = 'checked'; 
					$check_p = ''; 
				}else{
					$check_p = 'checked'; 
					$check_l = ''; 
				}
		?>

		<?php echo form_open_multipart('bkd/dosen/biodata/update_biodata');?>
		<?php echo validation_errors(); ?>
        
		<table border="0" cellspacing="0" cellpadding="2" class="table table-hover">
		<tr>
			<td>NIDN</td>
			<td colspan="3"><input type="text" name="nidn" size="20" value="<?php echo $data->NIDN;?>" readonly></td>
		</tr>
		<tr>
			<td>Nama</td>
			<td colspan="3"><input type="text" name="nama" class="input-xxlarge" value="<?php echo ucwords(strtolower($data->NM_DOSEN));?>" readonly></td>
		</tr>
		<tr>
			<td>Gelar Depan<br/>Akademik</td>
			<td><input type="text" name="gelar_depan" size="40" value="<?php echo $gda;?>" class="input-small" readonly></td>
			<td>Gelar Depan<br/>Non-Akademik</td>
			<td><input type="text" name="gelar_belakang" size="40" value="<?php echo $gdna;?>" class="input-small" readonly></td>
		</tr>
		<tr>
			<td>Gelar Belakang<br/>Non-Akademik</td>
			<td><input type="text" name="gelar_depan" size="40" value="<?php echo $gbna;?>" class="input-small" readonly></td>
			<td>Gelar Belakang<br/>Akademik</td>
			<td><input type="text" name="gelar_belakang" size="40" value="<?php echo $gba;?>" class="input-small" readonly></td>
		</tr>
		<tr>
			<td>Jenis Kelamin</td>
			<td><input type="radio" name="jk" size="40" value="P" <?php echo $check_p;?> disabled> Perempuan</td>
			<td colspan="2"><input type="radio" name="jk" size="40" value="L" <?php echo $check_l;?> disabled> Laki-laki</td>
		</tr>
		<tr>
			<td>Tanggal Lahir</td>
			<td><input type="text" name="tgl_lahir" class="datepicker input-medium" value="<?php echo date('d/m/Y', strtotime($pegawai[0]->TGL_LAHIR));?>" readonly></td>
			<td>Tempat lahir</td>
			<td><input type="text" name="tempat_lahir" value="<?php echo ucwords(strtolower($pegawai[0]->TMP_LAHIR));?>" readonly></td>
		</tr>
		<tr>
			<td>No. KTP</td>
			<td colspan="3"><input type="text" name="no_ktp" size="40" value="<?php echo $pegawai[0]->NO_KTP;?>" readonly></td>
		</tr>
		<tr>
			<td>NPWP</td>
			<td colspan="3"><input type="text" name="npwp" size="40" value="<?php #echo $data->NPWP;?>" readonly></td>
		</tr>
		<tr>
			<td>Alamat Rumah</td>
			<td colspan="3"><input type="text" name="alamat" id="alamat" class="input-xxlarge" value="<?php echo $pegawai[0]->ALMT_RUMAH;?>">
			<!-- table detail alamat -->
				<div id="detail-alamat" width="80%">
					<table class="table">
					<tr>
						<td>RT/RW</td><td><input type="text" name="rt" id="rt" class="input-small" value="<?php echo $pegawai[0]->RT_RUMAH;?>">
						<input type="text" name="rw" id="rw" class="input-small" value="<?php echo $pegawai[0]->RW_RUMAH;?>"></td>
					</tr>
					<tr>
						<td>Kelurahan/Desa</td>
						<td colspan="3"><input type="text" name="desa" id="desa" class="input-xlarge" value="<?php echo $pegawai[0]->NM_KEL_RUMAH;?>"></td>
					</tr>
					<tr>
						<td>Kecamatan</td>
						<td colspan="3">
							<input type="hidden" name="kd_kec" id="kd_kec" value="<?php echo $pegawai[0]->KD_KEC_RUMAH;?>">
							<input type="text" name="nm_kec" id="kecamatan" class="input-xlarge"  value="<?php echo $nm_kec;?>" onblur="autokab()">
						</td>
					</tr>
					<tr>
						<td>Kabupaten</td>
						<td colspan="3">
							<input type="hidden" name="kd_kab" id="kd_kab" value="<?php echo $pegawai[0]->KD_KAB_RUMAH;?>">
							<input type="text" name="nm_kab" id="kabupaten" class="input-xlarge" value="<?php echo $nm_kab;?>">
						</td>
					</tr>
					<tr>
						<td>Propinsi</td>
						<td colspan="3">
							<input type="hidden" name="kd_prop" id="kd_prop" value="<?php echo $pegawai[0]->KD_PROP_RUMAH;?>">
							<input type="text" name="nm_prop" id="propinsi" class="input-xlarge"  value="<?php echo $nm_prop;?>" readonly>
						</td>
					</tr>
					<tr>
						<td>Negara</td>
						<td><select name="id_negara" id="negara" class="input-medium">
							<?php foreach ($negara as $ngr){
								if ($ngr['KD_NEGARA'] == 99) $slc = 'selected'; else $slc = ''; ?>
								<option value="<?php echo $ngr['KD_NEGARA'];?>" <?php echo $slc;?>><?php echo $ngr['NM_NEGARA'];?></option>
							<?php }?>
						</select>
						<input type="text" name="pos" id="kd_pos" class="input-small" placeholder="Kode Pos" value="<?php echo $pegawai[0]->KDPOS_RUMAH;?>"></td>
					</tr>
					</table>
				</div>
			</td>
		</tr>
		<tr>
			<td>Telepon Rumah</td>
			<td colspan="3"><input type="text" name="telp_rumah" size="30" value="<?php echo $pegawai[0]->TELEPON_RUMAH1;?>"></td>
		</tr>
		<tr>
			<td>Handphone</td>
			<td colspan="3"><input type="text" name="mobile" size="30" value="<?php echo $pegawai[0]->TELEPON_HP1;?>"></td>
		</tr>
		<tr>
			<td>Email</td>
			<td colspan="3"><input type="email" name="email" size="40" value="<?php echo $pegawai[0]->EMAIL;?>"></td>
		</tr>
		<tr>
			<td>Penerima Tunjangan<br/> Profesi Sejak</td>
			<td colspan="3"><input type="text" name="thn_tunj_profesi" class="datepicker input-medium" value="<?php #echo $profesi;?>"></td>
		</tr>
		<tr>
			<td>Penerima Tunjangan Kehormatan Sejak</td>
			<td colspan="3"><input type="text" name="thn_tunj_kehormatan" class="datepicker input-medium" value="<?php #echo $kehormatan;?>"></td>
		</tr>
		<tr><th colspan="4" class="batas">Asesor</td></tr>
		<input type="hidden" name="kd_ad" value="<?php echo $kd_ad;?>">
		<!-- asesor -->
		<tr>
			<td>Pilih 2 Asesor</td>
			
			<td colspan="3">
				<div class="tujuan-surat">
					<div class="auto-surat grup">
						<div class="label-input">Asesor</div>
						<input type="text" name="asesor" id="asesor"/>
					</div>
				</div>
			</td>
		</tr>
		<!-- end auto complete -->
		<tr>
			<td>Deskripsi Diri</td>
			<td colspan="3">
			<textarea name="deskripsi" style="width:98%"><?php if(isset($bio->DESKRIPSI))echo $bio->DESKRIPSI ?></textarea>
			</td>
		</tr>				
		<tr>
			<td>Upload file CV</td>
			<td colspan="3" class="reg-input"><input type="file" name="file_cv"/>
			<div style="font-size: 11px;line-height: 15px;margin-bottom: 10px;color: #777;">File Curriculum Vitae (CV) yang diizinkan berjenis pdf atau docx dengan ukuran maksimal 1 MB.</div>
			</td>
		</tr>				

		<tr>
			<td></td>
			<td colspan="3"><input type="submit" name="submit" value="Simpan dan Lanjut" class="btn btn-uin btn-inverse btn-small">
		</tr>
		</table>
		<?php echo form_close();?>

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
	<?php }else{ echo "Sepertinya service sedang bermasalah...";} ?>
	</div>
</div>
