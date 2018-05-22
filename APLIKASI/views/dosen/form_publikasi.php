<script type="text/javascript" src="http://surat.uin-suka.ac.id/asset/js/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="http://surat.uin-suka.ac.id/asset/css/token-input.css" type="text/css" />
<script type="text/javascript">
 	$(document).ready(function() {
		$("#dosen").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_dosen");
		$("#mahasiswa").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_mahasiswa");
	}); 
</script>
<script type="text/javascript">
	var x = 1;
	$(function(){
		$("#addRow").click(function(){
			row = '<tr>'+
			'<td><input type="text" name="lain['+x+']" class="input-grup" /></td>'+
			'<td><a class="btn btn-small btn-danger btn-act remove-btn" style="margin:-11px 0 0 5px" title="Hapus"><i class="icon-remove icon-white"></i></a></td>'+
			'</tr>';
			$(row).insertBefore("#last");
			x++;
		});
	});
	$(".remove-btn").live('click', function(){
		$(this).parent().parent().remove();
	});
	
	/*
	$(function(){
		$("#addRowts").click(function(){
			row = '<tr>'+
			'<td><input type="text" name="ts_lainnya['+j+']" class="input-grup" style="width:317px"/></td>'+
			'<td>&nbsp;<input type="checkbox" name="lap_ts_lainnya['+j+']" /> sebagai laporan</td>'+
			'<td><a class="btn btn-small btn-danger btn-act remove-btnTs" style="margin:-11px 0 0 5px" title="Hapus"><i class="icon-remove icon-white"></i></a></td>'+
			'</tr>';
			$(row).insertBefore("#lastTs");
			j++;
		});
	});
	$(".remove-btnTs").live('click', function(){
		$(this).parent().parent().remove();
	});
	*/
</script>
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
	
</script>
<script>
$(document).ready(function(){
	$('#partner-penelitian-btn').click(function(){
		$('#partner-penelitian').fadeToggle();
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
select option:first-child{
  color:#222;
  background-color:#FFFFDD;
  border-bottom:solid 1px #aaa;
}
a.detail { color:#000; text-decoration:underline;}
</style>

<?php echo $this->s00_lib_output->output_info_dsn();?>
<div id="content">
<div>
	<div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/publikasi';?>" class="current">Publikasi</a>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/publikasi/tambah';?>">Tambah Data</a>
		</ul><div style="clear:both"></div>
	</div>
	
		<!-- edit values -->
		<?php 
		if (!empty($curr_publikasi)){
			foreach ($curr_publikasi as $val);
			$kd_dp = $val->KD_DP;
			$judul = $val->JUDUL_PUBLIKASI;
			$pada = $val->PADA;
			$tingkat = $val->TINGKAT; $tlabel = $tingkat;
			$tanggal_pub = date('d/m/Y', strtotime($val->TANGGAL_PUB));
			$penerbit = $val->PENERBIT;
			$akreditasi = $val->AKREDITASI; $takre = $akreditasi;
			$label = 'Update';
			$action = 'update_data_publikasi';
		}else{
			$kd_dp = '';
			$judul = '';
			$pada = '';
			$tingkat = ''; $tlabel = '- Pilih tingkat -';
			$tanggal_pub = '';
			$penerbit = '';
			$akreditasi = ''; $takre = '- Pilih akreditasi -';
			$label = 'Simpan';
			$action = 'simpan_data_publikasi';
		}
		?>
		
		<!-- show form publikasi -->
		<h2>Form Data Publikasi</h2>
		<?php echo form_open('bkd/dosen/bebankerja/'.$action);?>
			<?php echo validation_errors(); ?>
			<input type="hidden" name="kd_dp" value="<?php echo $kd_dp;?>">
			<table class="table table-condensd">
			<tr><th colspan="4">Data Publikasi</th></tr>
				<tr>
					<td>Judul</td>
					<td colspan="3"><textarea name="judul" class="input-xxlarge" required><?php echo $judul;?></textarea>
					<hr><div id="partner-penelitian-btn" class="link" style="cursor:pointer"><i class="icon-user"></i> Partner Publikasi</div>
					</td>
				</tr>
				<tr id="partner-penelitian" style="display:none">
					<td>Partner Publikasi</td>
					<td colspan="3">
						<div class="tujuan-surat">
							<div class="auto-surat grup">
								<div class="label-input">Dosen/Staff</div>
								<!-- cek nilai / edit position -->
								<input type="text" name="dosen" id="dosen"/>
							</div>
							<div class="auto-surat grup">
								<div class="label-input">Mahasiswa</div>
								<input type="text" name="mahasiswa" id="mahasiswa" />
							</div>
							<div class="auto-surat grup">
								<div class="label-input">Lainnya</div>
								<table>
									<tr>
										<td><input type="text" name="lain[]" class="input-grup" /></td>
										<td></td>
									</tr>
									<tr id="last"></tr>
									<tr><td colspan="2"><a class="btn btn-small" id="addRow" style="margin:0 0 5px 0">Tambah</a></td></tr>
								</table>
							</div>
						</div>
					</td>
				</tr>
				<!-- end auto complete -->				
				<tr>
					<td>Dipublikasikan pada</td>
					<td colspan="3"><textarea name="pada" class="input-xxlarge"><?php echo $pada;?></textarea></td>
				</tr>
				<tr>
					<td>Tingkat</td>
					<td><select name="tingkat">
							<option value="<?php echo $tingkat;?>"><?php echo $tlabel;?></option>
							<option value="LOKAL">LOKAL</option>
							<option value="NASIONAL">NASIONAL</option>
							<option value="INTERNASIONAL">INTERNASIONAL</option>
						</select>
					</td>
					<td>Tanggal Penyajian</td>
					<td><input type="text" name="tanggal_pub" class="datepicker input-medium" value="<?php echo $tanggal_pub;?>"></td>
				</tr>
				<tr>
					<td>Penerbit</td>
					<td colspan="3"><input type="text" name="penerbit" class="input-xxlarge" value="<?php echo $penerbit;?>"></td>
				</tr>
				<tr>
					<td>Akreditasi</td>
					<td colspan="3"><select name="akreditasi">
							<option value="<?php echo $akreditasi;?>"><?php echo $takre;?></option>
							<option value="A">A</option>
							<option value="B">B</option>
							<option value="C">C</option>
							<option value="LAIN">LAINNYA</option>
						</select>
					</td>
				</tr>
				<?php 	
					$cur_data=array();
					if(isset($current_data))$cur_data=array('current_data'=>$current_data);
					$this->load->view('bkd/dosen/bkd_form',$cur_data);
				?>
				<tr>
					<td></td>
					<td><input type="submit" name="submit" value="<?php echo $label;?>" class="btn btn-uin btn-inverse btn-small">
						<input type="reset" name="reset" value="Batal" class="btn btn-uin btn-small black"></td>
				</tr>
			
			</table>
		<?php echo form_close();?>
	
	
		<h2>Data Bidang Publikasi</h2><h3>Tahun Akademik : <?php echo $this->session->userdata('ta');?></h3>
		<!-- tampilkan beban kerja yang telah diambil -->
		<table class="table table-bordered table-hover">
			<tr>
				<th>No</th>
				<th>Judul Publikasi</th>
				<th>Dipublikasikan pada</th>
				<th>Tingkat</th>
				<th>Tanggal Penyajian/Publikasi</th>
				<th>Penerbit</th>
				<th>Akreditasi Terbitan</th>
				<th colspan="2">Aksi</th>
			</tr>
			<?php if (!empty($data_publikasi)){
						$no=0; foreach ($data_publikasi as $dp){ $no++; 
						if($this->uri->segment(6) == $dp->KD_DP) $bg = '#FFFFDD'; else $bg = '';
						?>
				<tr bgcolor="<?php echo $bg;?>">
					<td><?php echo $no;?></td>
					<td><?php echo $dp->JUDUL_PUBLIKASI;?></td>
					<td><?php echo $dp->PADA;?></td>
					<td><?php echo $dp->TINGKAT;?></td>
					<td><?php echo date('d/m/Y', strtotime($dp->TANGGAL_PUB));?></td>
					<td><?php echo $dp->PENERBIT;?></td>
					<td><?php echo $dp->AKREDITASI;?></td>
					<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/publikasi/tambah/'.$dp->KD_DP;?>" class="btn btn-mini"><i class="icon icon-edit"></i> Edit</a></td>
					<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/hapus_publikasi/'.$dp->KD_DP;?>" onclick="return hapus('Anda yakin ingin menghapus data publikasi ini?')" class="btn btn-mini"><i class="icon icon-trash"></i> Hapus</a></td>
				</tr>
			<?php } 
			}else{ ?>
				<tr><td colspan="11" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>
			<?php } ?>
		</table>
	</div>
</div>	
</div>