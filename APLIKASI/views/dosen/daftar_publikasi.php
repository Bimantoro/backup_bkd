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
					alert('update BKD_BEBAN_KERJA set '+nama_col+' = '+newContent+' where KD_BK = '+id); 
					/*$.post({
						url : <?php echo base_url().'bkd/dosen/bebankerja/editcepat';?>,
						type : 'POST',
						data : {
							id : $(this).parent("tr").attr('class'),
							field : $(this).attr('class'),
							value : newContent
						},
						success : function(){
							alert('Wokeh...!!!');
						}
					}) */
					return false;
				}
			});
			  
			$(this).children().first().blur(function(){
				$(this).parent().text(OriginalContent);
				$(this).parent().removeClass("cellEditing");
			});
			
		});
	});

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
a.detail { color:#000; text-decoration:underline;}
</style>
<?php echo $this->s00_lib_output->output_info_dsn();?>
<div id="content">
<div>
	<div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/publikasi';?>" class="current">Publikasi</a></li>
		</ul>
	</div>
	
		<?php $this->load->view('dosen/form_history_pub');?>
		
		<h2>Data Bidang Publikasi</h2>
		<h3>Tahun Akademik : <?php echo $ta;?>, Semester <?php echo $smt;?></h3>
		<!-- tampilkan beban kerja yang telah diambil -->
		<table class="table table-bordered table-hover">
			<tr>
				<th>No</th>
				<th>Judul Publikasi</th>
				<th>Penerbit</th>
				<th>Akreditasi Terbitan</th>
				<th colspan="2">Aksi</th>
			</tr>
			<?php if (!empty($data_publikasi)){
						$no=0; foreach ($data_publikasi as $dp){ $no++; ?>
				<tr class="<?php echo $dp->KD_DP;?>">
					<th><?php echo $no;?></th>
					<td class="<?php echo $dp->JUDUL_PUBLIKASI;?>"><a class="detail" href="<?php echo base_url().'bkd/dosen/bebankerja/publikasi/detail/'.$dp->KD_DP;?>"><?php echo $dp->JUDUL_PUBLIKASI;?></a></td>
					<td class="<?php echo $dp->PENERBIT;?>"><?php echo $dp->PENERBIT;?></td>
					<td class="<?php echo $dp->AKREDITASI;?>" align="center"><?php echo $dp->AKREDITASI;?></td>
					<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/publikasi/tambah/'.$dp->KD_DP;?>" class="btn btn-mini"><i class="icon icon-edit"></i> Edit</a></td>
					<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/hapus_publikasi/'.$dp->KD_DP;?>" onclick="return hapus('Anda yakin ingin menghapus data publikasi ini?')" class="btn btn-mini"><i class="icon icon-trash"></i> Hapus</a></td>
				</tr>
			<?php } 
			}else{ ?>
				<tr><td colspan="11" align="center">TIDAK ADA DATA YANG DAPAT DITAMPILKAN</td></tr>
			<?php } ?>
		</table>
		<p><a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/bebankerja/publikasi/tambah';?>">Tambah</a></p>
		
		<!-- detail data publikasi -->
		<?php if(!empty($curr_publikasi)){ 
			foreach ($curr_publikasi as $data); ?>
		
		<h2>Detail Data Publikasi</h2>
		<table class="table table-condensed">
		<tr>
			<th>Judul</th>
			<td>:</td>
			<td><?php echo $data->JUDUL_PUBLIKASI;?></td>
		</tr>
		<tr>
			<th>Penerbit</th>
			<td>:</td>
			<td><?php echo $data->PENERBIT;?></td>
		</tr>
		<tr>
			<th>Di Publikasikan pada</th>
			<td>:</td>
			<td><?php echo $data->PADA;?></td>
		</tr>
		<tr>
			<th>Tanggal</th>
			<td>:</td>
			<td><?php echo date('d/m/Y', strtotime($data->TANGGAL_PUB));?></td>
		</tr>
		<tr>
			<th>Tingkat</th>
			<td>:</td>
			<td><?php echo $data->TINGKAT;?></td>
		</tr>
		<tr>
			<th>Akreditasi</th>
			<td>:</td>
			<td><?php echo $data->AKREDITASI;?></td>
		</tr>
		<tr>
			<th>Partner Publikasi</th>
			<td>:</td>
			<td>
			
				<!-- partner publikasi -->
				<?php if(!empty($partner)){ ?>
				<table class="table table-condensed">
					<tr>
						<th>NIP/NIM</th>
						<th>Nama</th>
						<th>Aksi</th>
					</tr>
					<?php foreach ($partner as $p){ ?>
								<tr>
									<td><?php echo $p->PARTNER;?></td>
									<td><?php echo $nama_partner[$p->PARTNER];?></td>
									<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/hapus_partner/'.$dp->KD_DP.'/'.'PUBLIKASI/'.$p->PARTNER;?>"  class="btn btn-mini"><i class="icon-trash hapus-partner"></i></a></td>
								</tr>
					<?php }
						}else{
							echo '-';
						}
					?>
				</table>
			
			</td>
		</tr>
		</table>
		
		<?php } ?>
		
</div>	
</div>