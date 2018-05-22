<script type="text/javascript">
	function select_tahun(){
		var jenis = document.getElementById("jenis").value;
		if (jenis == "DS" || jenis == "DT" || jenis == ""){
			document.getElementById("tahun_gubes").value = "";
			document.getElementById("tahun_gubes").disabled = true;
		}else{
			document.getElementById("tahun_gubes").disabled = false;
		}
	}
	$(function() {
		$( ".datepicker" ).datepicker({
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
</script>
<style>
.btn.black{ color:#222;}
#extra{ display:block; font-size:0.9em; padding:2px; background:linear-gradient(#fff,#f7f7f7); color:#777; text-shadow:1px 1px #fff; border:solid 1px #ddd;}
#tahun_gubes{ width:100px;}
</style>

<?php echo $this->s00_lib_output->output_info_dsn();?>

<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Beban Kerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_biodata';?>">Identitas</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_kepegawaian';?>">Kepegawaian</a></li>
	<li class="current"><a href="<?php echo base_url().'bkd/dosen/biodata/edit_akademik';?>">Akademik</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata/edit_riwayat_pendidikan';?>">Riwayat Pendidikan</a></li>
</ul>
<h2>Data Akademik</h2>
<div id="content">
	<div>
		<?php foreach ($identity as $data);?>
		<?php foreach ($uni as $univ);?>
		<?php echo form_open('bkd/dosen/biodata/update_akademik');?>
		<table class="table table-hover">
		<tr>
			<td>Kode Perguruan Tinggi</td>
			<td colspan="3"><input type="text" name="kd_univ" value="<?php echo $univ->KD_PT;?>" readonly></td>
		</tr>
		<tr>
			<td>Nama Perguruan Tinggi</td>
			<td colspan="3"><input type="text" name="nm_univ" value="<?php echo $univ->NM_PT;?>" class="input-xxlarge" readonly></td>
		</tr>
		<tr>
			<td>Alamat</td>
			<td colspan="3"><input type="text" name="alamat_pt" value="<?php echo $univ->ALAMAT;?>" class="input-xxlarge" readonly></td>
		</tr>
		<tr>
			<td>Jenis Pendidikan Tinggi</td>
			<td colspan="3"><input type="text" class="input-xxlarge" value="Universitas" readonly></td>
		</tr>
		<tr>
			<td>Fakultas / Departemen</td>
			<td colspan="3">
				<select name="kd_fak" class="input-xxlarge" disabled>
					<option value="<?php echo $this->session->userdata('kd_fak');?>"><?php echo $namaFak;?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Program Studi</td>
			<td colspan="3">
				<select name="kd_prodi" class="input-xxlarge" disabled>
					<option value="<?php echo $data->KD_PRODI;?>"><?php echo ucwords(strtolower($data->NM_PRODI));?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Pendidikan S1</td>
			<td colspan="3"><input type="text" name="s1" class="input-xxlarge" value="<?php echo $ps1;?>" disabled></td>
		</tr>
		<tr>
			<td>Pendidikan S2</td>
			<td colspan="3"><input type="text" name="s2" class="input-xxlarge" value="<?php echo $ps2;?>" disabled></td>
		</tr>
		<tr>
			<td>Pendidikan S3</td>
			<td colspan="3"><input type="text" name="s3"class="input-xxlarge"  value="<?php echo $ps3;?>" disabled></td>

		</tr>		
		<tr>
			<th>Status Tugas</th>
			<td><input type="hidden" name="kd_jenis_dosen" value="<?php echo $this->session->userdata('jenis_dosen');?>">
			<input type="text" name="nm_jenis_dosen" value="<?php echo $nama_jenis_dosen;?>" class="input-xlarge" disabled></td>
			<td colspan="2">Periode Lap.<br/>Gubes Mulai Tahun &nbsp;
				<select name="tahun_gubes" id="tahun_gubes" disabled>
					<option value="<?php echo $this->session->userdata('thn_prof');?>"><?php echo $this->session->userdata('thn_prof');?></option>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3"><input type="submit" name="submit" value="Simpan dan Lanjut" class="btn btn-inverse btn-uin btn-small"></td>
		</tr>
		</table>
		<?php echo form_close();?>
	</div>
</div>
<style>.note{font-style:italic; color:#550000; line-height:10px; font-size:0.9em;}</style>