<script type="text/javascript" src="http://surat.uin-suka.ac.id/asset/js/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="http://surat.uin-suka.ac.id/asset/css/token-input.css" type="text/css" />
<script type="text/javascript">
	$(function () {
		$(".table-bordered td").dblclick(function () {
			var OriginalContent = $(this).text();
			var id = $(this).parent("tr").attr('class');
			var nama_col = $(this).attr('class');
			  
			if(nama_col == 'REKOMENDASI'){
				var x = $(this).text();
				if(x == 'LANJUTKAN'){
					$(this).html("<select name='rekomendasi'><option value='"+ x +"'>" +OriginalContent + "</option><option value='SELESAI'>SELESAI</option><option value='BEBAN LEBIH'>BEBAN LEBIH</option><option value='LAINNYA'>LAINNYA</option></select>");
				}else if(x == 'SELESAI'){
					$(this).html("<select name='rekomendasi'><option value='"+ x +"'>" +OriginalContent + "</option><option value='LANJUTKAN'>LANJUTKAN</option><option value='BEBAN LEBIH'>BEBAN LEBIH</option><option value='LAINNYA'>LAINNYA</option></select>");				
				}else if(x == 'BEBAN LEBIH'){				
					$(this).html("<select name='rekomendasi'><option value='"+ x +"'>" +OriginalContent + "</option><option value='SELESAI'>SELESAI</option><option value='LANJUTKAN'>LANJUTKAN</option><option value='LAINNYA'>LAINNYA</option></select>");
				}else{
					$(this).html("<select name='rekomendasi'><option value='"+ x +"'>" +OriginalContent + "</option><option value='SELESAI'>SELESAI</option><option value='BEBAN LEBIH'>BEBAN LEBIH</option><option value='LANJUTKAN'>LANJUTKAN</option></select>");
				}
			}else{
				$(this).html("<textarea onkeyup='autoHeight(this)' style='overflow:hidden'>" + OriginalContent + "</textarea>");
			}
			$(this).children().first().focus();
	  
			$(this).children().first().keypress(function (e) {
				if (e.which == 13) {
					var newContent = $(this).val();
					$(this).parent().text(newContent);
						//alert('update BKD_BEBAN_KERJA set '+nama_col+' = '+newContent+' where KD_BK = '+id); 
					$.post("<?php echo base_url().'bkd/dosen/bebankerja/editcepat_rbkd';?>",
					{
					  id: id,
					  field: nama_col,
					  value: newContent 
					},
					function(data,status){
					  //alert("Data: " + data + "\nStatus: " + status);
					});
					return false;
				}
			});
			  
			$(this).children().first().blur(function () {
					var newContent = $(this).val();
					$(this).parent().text(newContent);
						//alert('update BKD_BEBAN_KERJA set '+nama_col+' = '+newContent+' where KD_BK = '+id); 
					$.post("<?php echo base_url().'bkd/dosen/bebankerja/editcepat_rbkd';?>",
					{
					  id: id,
					  field: nama_col,
					  value: newContent 
					},
					function(data,status){
					  //alert("Data: " + data + "\nStatus: " + status);
					});
					return false;
			});
			  
			$(this).children().first().blur(function(){
				$(this).parent().text(OriginalContent);
				$(this).parent().removeClass("cellEditing");
			});
			
		});
	});
	
	function autoHeight(o){
		o.style.height = "1px";
		o.style.height = (20+o.scrollHeight)+"px";
	}

	function hapus(msg){
		var conf = confirm(msg);
		if (conf == true) return true;
		else return false;
	}
</script>
<style>
	a.detail { color:#000; text-decoration:underline;}
	.total td{font-weight:bold;}
	textarea, .table.table-bordered select{ position:relative; width:95%; height:99%; border:none; font-family:segoe, opensans, sans-serif; font-size:13px;}
	textarea:focus{box-shadow:none; margin:0; padding:0;}
</style>
<div id="content">
<div>

	<?php 
		# cek kode
		switch($kode){
			case "A" : $title = 'pendidikan'; break;
			case "B" : $title = 'penelitian'; break;
			case "C" : $title = 'pengabdian masyarakat'; break;
			case "D" : $title = 'penunjang lain'; break;
			case "E" : $title = 'Kewajiban Khusus Profesor'; break;
		}
		
	?>
	<?php echo $this->s00_lib_output->output_info_dsn();?>
	
	<div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">RBKD</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/rbkd/'.$kode;?>" class="current"><?php echo ucwords($title);?></a>
		</ul><div style="clear:both"></div>
	</div>
	
	<?php $this->load->view('dosen/form_history_rbkd');?>
	
	<?php if($mode == 1){ ?>
	<h2>Form Rencana Beban Kerja Bidang <?php echo ucwords($title);?></h2>
	<?php echo validation_errors();?>
	<table class="table table-condensed table-hover">
		<?php echo form_open('bkd/dosen/bebankerja/simpan_rbkd');?>
		<input type="hidden" name="kd_jbk" value="<?php echo $kode;?>">
		<tr>
			<td>Jenis/Nama Kegiatan</td>
			<td colspan="3"><textarea name="jenis_kegiatan" class="input-xxlarge" style="border:solid 1px #DDD; padding:10px" required></textarea></td>
		</tr>
		<tr>
			<td>Semester</td>
			<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('r_smt');?>"><?php echo $this->session->userdata('r_smt');?></option></select></td>
			<td>Tahun <br/>Akademik</td>
			<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('r_ta');?>"><?php echo $this->session->userdata('r_ta');?></option></select></td>
		</tr>
		<tr>
			<td>Bukti Penugasan</td>
			<td>
				<div>
					<input type="hidden" name="bukti_penugasan" id="bukti">
					<p class="btn-group">
						<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
						<span class="btn btn-cari" title="Cari dokumen penugasan pada arsip dokumen"><i class="icon-search"></i></span>
						<span class="btn btn-upload-disabled" title="Upload bukti dokumen untuk laporan Beban Kerja" disabled><i class="icon-upload"></i></span>
					</p>
				</div>
				<div id="bukti-label" style="border-top:solid 1px #DDD; margin:-3px; margin-top:5px; padding:4px"></div>
			</td>
			<td>SKS</td>
			<td><input type="number" name="sks" class="input-small"></td>
		</tr>
		<tr>
			<td>Masa Penugasan</td>
			<td><input type="number" min="0" name="lama" id="lama" class="input-small" required></td>
			<td colspan="2">
				<select name="masa" id="masa" required>
					<option value="Tahun">Tahun</option>
					<option value="Semester">Semester</option>
					<option value="Bulan">Bulan</option>
					<option value="Hari">Hari</option>
				</select>
			</td>
		</tr>
		<tr>
			<td></td>
			<td colspan="3"><input type="submit" name="submit" value="Simpan" class="btn btn-uin btn-inverse btn-small"></td>
		</tr>
		<?php echo form_close();?>		
		</table>
		
		<?php }else if($mode == 2){
			foreach($current_rbkd as $data);
			$kd_rbkd = $data->KD_RBK;
			$kode = $data->KD_JBK;
			$jenis_kegiatan = $data->JENIS_KEGIATAN;
			$bukti_penugasan = $data->BKT_PENUGASAN;
			$file = $data->FILE_PENUGASAN;
			$sks = $data->SKS_PENUGASAN;
			$masa_penugasan = $data->MASA_PENUGASAN; $a = explode(' ',$masa_penugasan); $lama = $a[0]; $masa = $a[1];
		?>
		<h2>Form Rencana Beban Kerja Bidang <?php echo ucwords($title);?></h2>
		<form action = "<?php echo base_url().'bkd/dosen/bebankerja/update_rbkd';?>" name="f_update_rbkd" method="post">
		<input type="hidden" name="kd_rbkd" value="<?php echo $kd_rbkd;?>">
		<input type="hidden" name="kd_jbk" value="<?php echo $kode;?>">
		<table class="table table-condensed">
		<tr>
			<td>Jenis/ Nama Kegiatan</td>
			<td colspan="3"><textarea name="jenis_kegiatan" class="input-xxlarge" style="border:solid 1px #DDD; padding:10px" required><?php echo $jenis_kegiatan;?></textarea></td>
		</tr>
		<tr>
			<td>Semester</td>
			<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('r_smt');?>"><?php echo $this->session->userdata('r_smt');?></option></select></td>
			<td>Tahun <br/>Akademik</td>
			<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('r_ta');?>"><?php echo $this->session->userdata('r_ta');?></option></select></td>
		</tr>
		<tr>
			<td>Bukti Penugasan</td>
			<td>
				<div>
					<input type="hidden" name="bukti_penugasan" id="bukti">
					<p class="btn-group">
						<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
						<span class="btn btn-cari" title="Cari dokumen penugasan pada arsip dokumen"><i class="icon-search"></i></span>
						<span class="btn btn-upload-disabled" title="Upload bukti dokumen untuk laporan Beban Kerja" disabled><i class="icon-upload"></i></span>
					</p>
				</div>
				<div id="bukti-label" style="border-top:solid 1px #DDD; margin:-3px; margin-top:5px; padding:4px"></div>
			</td>
			<td>SKS</td>
			<td><input type="number" name="sks" class="input-small" value="<?php echo $sks;?>"></td>
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
			<td></td>
			<td colspan="3"><input type="submit" name="submit2" value="Update" class="btn btn-uin btn-inverse btn-small"></td>
		</tr>
	<?php } ?>
	</table>
	</form>		
	
	<!-- data RBKD sebelumnya -->
	<h2>Rencana Beban Kerja Bidang <?php echo ucwords($title);?></h2>
	<h3>Tahun Akademik : <?php echo $ta;?>, Semester : <?php echo $smt;?></h3>
	<?php echo validation_errors();?>
	<table class="table table-bordered table-hover">
	<tr>
		<th rowspan="2">No</th>
		<th rowspan="2" width="180">Jenis Kegiatan</th>
		<th colspan="2">Beban Kerja</th>
		<th rowspan="2" width="20">Masa Penugasan</th>
		<th colspan="2" rowspan="2">Aksi</th>
	</tr>
	<tr>
		<th>Bukti Penugasan</th>
		<th>SKS</th>
	</tr>
	<?php 
		if (empty($data_beban)){
			echo '<tr><td colspan="7" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>';
		}else{
			$no=0; $sks_beban = 0;
			foreach ($data_beban as $bp){ $no++; 
				$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
				if($this->uri->segment(7) !== ''){
					if($this->uri->segment(7) == $bp->KD_RBK) $bg="#FFFFDD"; else $bg="";
				}else{
					$bg = '';
				}
		?>
		<tr class ="<?php echo $bp->KD_RBK;?>" bgcolor="<?php echo $bg;?>">
			<th><?php echo $no;?></th>
			<td class="JENIS_KEGIATAN"><a class="detail" href="<?php echo base_url().'bkd/dosen/bebankerja/rbkd/'.$bp->KD_JBK.'/detail/'.$bp->KD_RBK;?>"><?php echo $bp->JENIS_KEGIATAN;?></a></td>
			<th class="BKT_PENUGASAN">
					<p class="btn-group">
						<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
						<span class="btn btn-cari" title="Cari dokumen penugasan pada arsip dokumen"><i class="icon-search"></i></span>
						<span class="btn btn-upload" title="Upload bukti dokumen untuk laporan Beban Kerja"><i class="icon-upload"></i></span>
					</p>
			</th>
			<td class="SKS_PENUGASAN" align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
			<td class="MASA_PENUGASAN"><?php echo $bp->MASA_PENUGASAN;?></td>
			
			<?php #if($is_crud == 1){?>
				<th><a href="<?php echo site_url().'bkd/dosen/bebankerja/rbkd/'.$bp->KD_JBK.'/edit/'.$bp->KD_RBK;?>" class="btn btn-mini"><i class="icon icon-edit"></i> Edit</a></th>
				<th><a class="btn btn-mini" onclick="return hapus('Anda yakin ingin menghapus data ini?')" href="<?php echo site_url().'bkd/dosen/bebankerja/hapus_rbkd/'.$bp->KD_JBK.'/'.$bp->KD_RBK;?>"><i class="icon icon-trash"></i> Hapus</a></th>
			<?php #} ?>
		</tr>
		<?php } ?>
		<tr class="total">
			<td></td>
			<td colspan="2">Jumlah Beban Kerja</td>
			<td align="center"><?php echo $sks_beban; ?></td>
			<td colspan="4"></td>
		</tr>
		<?php }?>
	</table>
	
	<?php if($is_crud == 1){?>
		<a href="<?php echo base_url().'bkd/dosen/bebankerja/rbkd/'.$kode.'/tambah';?>" class="btn btn-small btn-inverse btn-uin">Tambah</a>
	<?php } ?>
	
	
	<!-- detail RBKD -->
	<?php if($mode == 3){ 
		if(!empty($current_rbkd)){
			foreach ($current_rbkd as $cr);
	?>
	<h2>Detail Rencana Beban Kerja Dosen</h2>
	<table class="table table-condensed">
		<tr>
			<th width="200">Jenis Kegiatan</th><td>:</td>
			<td><?php echo $cr->JENIS_KEGIATAN;?></td>
		</tr>
		<tr>
			<th>Masa Penugasan</th><td>:</td>
			<td><?php echo $cr->MASA_PENUGASAN;?></td>
		</tr>
		<tr>
			<th>Bukti Penugasan</th><td>:</td>
			<td><?php echo $cr->BKT_PENUGASAN;?></td>
		</tr>
			<tr><th>File Penugasan</th><td>:</td>
				<td class="fp" style="cursor:pointer">
					<?php 
						$fp = strlen($cr->FILE_PENUGASAN);
						if($fp > 1){
							$file = '<i class = "icon-eye-open"></i> Lihat file penugasan';
						}else { $file = '<i class="icon-remove"></i> Tidak ada file penugasan'; }
						echo $file;
					?>
				</td>
			</tr>
			<!-- frame google docs -->
			<tr class="file-penugasan" style="display:none">
				<td></td>
				<td colspan="2">
					<?php 
						$fp = strlen($cr->FILE_PENUGASAN);
						if($fp > 1){
							# cek apakah file surat apa bukan
							if (substr($cr->FILE_PENUGASAN, 0,5) == 'surat'){
								$split = explode(':', $cr->FILE_PENUGASAN);
								$id_surat = $split[1]; ?>
								
								<?php $kd_sistem = "21a4b81cbb4f"; ?>
								<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
									<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
									<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
									<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
								</form>
								
							<?php 	
							}else{
								$url_fp = 'http://docs.google.com/viewer?url='.urlencode($cr->FILE_PENUGASAN);
								echo '<iframe src="'.$url_fp.'&embedded=true" width="600" height="780" style="border: none;"></iframe>';
							}
						}
					?>
				</td>
			</tr>
	</table>
	
	<?php } } ?>
	
	<?php #echo $this->session->userdata('r_ta').'::::::'.$this->session->userdata('r_smt');?>

	<!-- form upload file penugasan dan -->
	<?php $this->load->view('dosen/isi_dokumen_rbkd');?>
	<?php $this->load->view('dosen/cari_dokumen_rbkd');?>
	<?php $this->load->view('dosen/upload_dokumen_rbkd');?>
	<!-- end -->
<script type="text/javascript">
$(document).ready(function(){
	// show hide file dokumen
	$(".fp").click(function(){
		$('.file-penugasan').slideToggle('fast');
	});
	$(".fc").click(function(){
		$('.file-capaian').slideToggle('fast');
	});
	
});
</script>

</div>
</div>
