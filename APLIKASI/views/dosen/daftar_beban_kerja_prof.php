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
					$.post("<?php echo base_url().'bkd/dosen/bebankerja/editcepatprof';?>",
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
			  
			$(this).children().first().blur(function(){
				$(this).parent().text(OriginalContent);
				$(this).parent().removeClass("cellEditing");
			});
			
		});
	});
	
	function autoHeight(o) {
		o.style.height = "1px";
		o.style.height = (10+o.scrollHeight)+"px";
	}

</script>
<script type="text/javascript">
	function hapus(msg){
		var conf = confirm(msg);
		if (conf == true) return true;
		else return false;
	}
</script>
<style>
.total td{font-weight:bold;}
textarea, .table.table-bordered select{ position:relative; width:95%; height:99%; border:none; font-family:segoe, opensans, sans-serif; font-size:13px;}
textarea:focus{box-shadow:none; margin:0; padding:0;}
</style>
<?php echo $this->s00_lib_output->output_info_dsn();?>

<div id="content">
<div>
	<div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>" class="current">Beban Kerja Dosen</a>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor';?>" class="current">Kewajiban Profesor</a>
		</ul><div style="clear:both"></div>
	</div>

	<?php $this->load->view('dosen/form_history_prof');?>
	
	<h2>Data Beban Kerja Profesor</h2>
	
	<!-- tampilkan beban kerja yang telah diambil -->
	<style>
	a.detail { color:#000; text-decoration:underline;}
	.scrollbar{
		background: none repeat scroll 0 0 #FCFCFC;
		border: none; 
		border-radius: 5px;
		box-shadow: 0 1px 0 #FFFFFF inset;
		width: 99%;
		padding: 0px;
		overflow-x: scroll;
		margin-bottom:10px;
	}
	</style>
	<div class="scrollbar">
		<table border="0" cellspacing="0" class="table table-bordered table-hover" width="100%">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
				<th rowspan="2">Tahun</th>
				<th colspan="2" rowspan="2" style="border-right:none">Aksi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 
				if (empty($data_beban_prof)){
					echo '<tr><td colspan="10" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>';
				}else{
					$no=0; $sks_beban = 0; $sks_bukti = 0;
					foreach ($data_beban_prof as $bp){ $no++; 
						$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
						$sks_bukti = $sks_bukti+$bp->SKS_BKT;
					?>
					<tr class="<?php echo $bp->KD_BKP;?>">
						<th><?php echo $no;?></th>
						<td class="JENIS_KEGIATAN"><a class="detail" href="<?php echo base_url().'bkd/dosen/bebankerja/profesor/detail/'.$bp->KD_BKP;?>"><?php echo $bp->JENIS_KEGIATAN;?></a></td>
						<th>
							<p class="btn-group">
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor/detail/'.$bp->KD_BKP.'/penugasan-isi';?>" class="btn btn-mini" title="Isi bukti penugasan">
								<i class="icon-pencil"></i></a>
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor/detail/'.$bp->KD_BKP.'/penugasan-cari';?>" class="btn btn-mini" title="Cari dokumen penugasan">
								<i class="icon-search"></i></a>
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor/detail/'.$bp->KD_BKP.'/penugasan-upload';?>" class="btn btn-mini" title="Upload dokumen penugasan">
								<i class="icon-upload"></i></a>
							</p>
						</th>
						<td class="SKS_PENUGASAN" align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td class="MASA_PENUGASAN"><?php echo $bp->MASA_PENUGASAN;?></td>
						<th>
							<p class="btn-group">
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor/detail/'.$bp->KD_BKP.'/kinerja-isi';?>" class="btn btn-mini" title="Isi bukti penugasan">
								<i class="icon-pencil"></i></a>
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor/detail/'.$bp->KD_BKP.'/kinerja-cari';?>" class="btn btn-mini" title="Cari dokumen penugasan">
								<i class="icon-search"></i></a>
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor/detail/'.$bp->KD_BKP.'/kinerja-upload';?>" class="btn btn-mini" title="Upload dokumen penugasan">
								<i class="icon-upload"></i></a>
							</p>
						</th>
						<td class="SKS_BKT" align="center"><?php echo $bp->SKS_BKT;?></td>
						<td class="REKOMENDASI"><?php echo $bp->REKOMENDASI;?></td>
						<td class="TAHUN"><?php echo $bp->TAHUN;?></td>
						<th><a class="btn btn-small" href="<?php echo site_url().'bkd/dosen/bebankerja/edit_bkp/'.$bp->KD_BKP;?>"><i class="icon icon-edit"></i> Edit</a></th>
						<th><a class="btn btn-small" onclick="return hapus('Hapus?')" href="<?php echo site_url().'bkd/dosen/bebankerja/hapus_bkp/'.$bp->KD_BKP;?>"><i class="icon icon-trash"></i> Hapus</a></th>
					</tr>
				<?php } ?>
				<style>.total{font-weight:bold;}</style>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $sks_beban; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $sks_bukti; ?></td>
					<td colspan="5"></td>
				</tr>
			<?php } ?>
		</table>
		<!-- <div class="bs-callout bs-callout-info"><b>Edit Cepat : </b>Double Click kolom yang ingin Anda ubah isinya, kemudian tekan Enter untuk menyimpan</div> -->
	</div>
	<p><a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/bebankerja/input_prof';?>">Tambah</a></p>
	<br/>
	<?php if(!empty($current_data)){ foreach($current_data as $data); ?>
	<h2>Detail Beban Khusus Profesor</h2>
	<table class="table table-condensed">
		<tr><th>Jenis Kegiatan</th><td>:</td><td><?php echo $data->JENIS_KEGIATAN;?></td></tr>
		<tr><th>Kategori</th><td>:</td><td><?php echo $data->NM_KAT.' ( '.$data->NM_KEGIATAN.' )';?></td></tr>
		<tr><th>Tahun Pelaksanaan</th><td>:</td><td><?php echo $data->TAHUN;?></td></tr>
		<tr><th>Periode Laporan</th><td>:</td><td><?php echo $data->PERIODE_LAPORAN;?></td></tr>
		<tr><th>Sumber Dana</th><td>:</td><td><?php echo $data->SUMBER_DANA;?></td></tr>
		<tr><th>Jumlah Dana</th><td>:</td><td><?php echo $data->JUMLAH_DANA;?></td></tr>
		<tr><th>Bukti Penugasan</th><td>:</td><td><?php echo $data->BKT_PENUGASAN;?></td></tr>
		<tr><th>Bukti Kinerja</th><td>:</td><td><?php echo $data->BKT_DOKUMEN;?></td></tr>
			<tr><th>File Penugasan</th><td>:</td>
				<td class="fp" style="cursor:pointer">
					<?php 
						$fp = strlen($data->FILE_PENUGASAN);
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
						$fp = strlen($data->FILE_PENUGASAN);
						if($fp > 1){
							# cek apakah file surat apa bukan
							if (substr($data->FILE_CAPAIAN, 0,5) == 'surat'){
								$split = explode(':', $data->FILE_PENUGASAN);
								$id_surat = $split[1]; ?>
								
								<?php $kd_sistem = "21a4b81cbb4f"; ?>
								<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
									<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
									<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
									<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
								</form>
								
							<?php 	
							}else{
								$url_fp = 'http://docs.google.com/viewer?url='.urlencode($data->FILE_PENUGASAN);
								echo '<iframe src="'.$url_fp.'&embedded=true" width="600" height="780" style="border: none;"></iframe>';
							}
						}
					?>
				</td>
			</tr>
			<tr><th>File Capaian</th><td>:</td>
				<td class="fc" style="cursor:pointer">
					<?php 
						$fp = strlen($data->FILE_CAPAIAN);
						if($fp > 1){
							$file = '<i class = "icon-eye-open"></i> Lihat file capaian';
						}else { $file = '<i class="icon-remove"></i> Tidak ada file capaian'; }
						echo $file;
					?>
				</td>
			</tr>
			
			<tr class="file-capaian" style="display:none">
				<td></td>
				<td colspan="2">
					<?php 
						$fp = strlen($data->FILE_CAPAIAN);
						if($fp > 1){
						
							# cek apakah file surat apa bukan
							if (substr($data->FILE_CAPAIAN, 0,5) == 'surat'){
								$split = explode(':', $data->FILE_CAPAIAN);
								$id_surat = $split[1]; ?>
								
								<?php $kd_sistem = "21a4b81cbb4f"; ?>
								<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
									<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
									<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
									<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
								</form>
								
							<?php 	
							}else{
								$url_fp = 'http://docs.google.com/viewer?url='.urlencode($data->FILE_CAPAIAN);
								echo '<iframe src="'.$url_fp.'&embedded=true" width="600" height="780" style="border: none;"></iframe>';
							}
							
						}
					?>
				</td>
			</tr>
	</table>
	<?php } ?>

	<!-- form upload file penugasan dan -->
	<?php if($this->uri->segment(7) != ''){ ?>
		<?php $this->load->view($view);?>
	<?php } ?>
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
	
    $("[rel=tooltip]").tooltip({ placement: 'bottom'});
	
});
</script>

</div>
</div>