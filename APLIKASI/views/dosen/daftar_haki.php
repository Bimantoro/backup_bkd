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
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/haki';?>" class="current">HAKI</a></li>
		</ul>
	</div>
	
		<h2>Data Hak Kekayaan Intelektual</h2>
		<!-- tampilkan beban kerja yang telah diambil -->
		<table class="table table-bordered table-hover">
			<tr>
				<th>No</th>
				<th>Judul haki</th>
				<th>Penerbit</th>
				<th>Akreditasi Terbitan</th>
				<th colspan="2">Aksi</th>
			</tr>
			<?php if (!empty($haki)){
						$no=0; foreach ($haki as $dp){ $no++; ?>
				<tr class="<?php echo $dp->KD_HAKI;?>">
					<th><?php echo $no;?></th>
					<td class="<?php echo $dp->JUDUL_HAKI;?>"><a class="detail" href="<?php echo base_url().'bkd/dosen/bebankerja/haki/detail/'.$dp->KD_HAKI;?>"><?php echo $dp->JUDUL_HAKI;?></a></td>
					<td class="<?php echo $dp->PENERBIT_SK;?>"><?php echo $dp->PENERBIT_SK;?></td>
					<td class="<?php echo $dp->TINGKAT;?>" align="center"><?php echo $dp->TINGKAT;?></td>
					<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/haki/tambah/'.$dp->KD_HAKI;?>" class="btn btn-mini"><i class="icon icon-edit"></i> Edit</a></td>
					<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/hapus_haki/'.$dp->KD_HAKI;?>" onclick="return hapus('Anda yakin ingin menghapus data ini?')" class="btn btn-mini"><i class="icon icon-trash"></i> Hapus</a></td>
				</tr>
			<?php } 
			}else{ ?>
				<tr><td colspan="11" align="center">TIDAK ADA DATA YANG DAPAT DITAMPILKAN</td></tr>
			<?php } ?>
		</table>
		<p><a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/bebankerja/haki/tambah';?>">Tambah</a></p>
		
		<!-- detail data haki -->
		<?php if(!empty($curr_haki)){ 
			$data=$curr_haki  ?>
		
		<h2>Detail Data haki</h2>
		<table class="table table-condensed">
		<tr>
			<th>Judul</th>
			<td>:</td>
			<td><?php echo $data->JUDUL_HAKI;?></td>
		</tr>
		<tr>
			<th>Tingkat</th>
			<td>:</td>
			<td><?php echo $data->TINGKAT;?></td>
		</tr>
		<tr>
			<th>Nomor SK</th>
			<td>:</td>
			<td><?php echo $data->NOMOR_SK;?></td>
		</tr>
		<tr>
			<th>Tanggal</th>
			<td>:</td>
			<td><?php echo date('d/m/Y', strtotime($data->TANGGAL_SK));?></td>
		</tr>
		<tr>
			<th>Penerbit SK</th>
			<td>:</td>
			<td><?php echo $data->PENERBIT_SK;?></td>
		</tr>
		<tr>
			<th>Pemilik Hak</th>
			<td>:</td>
			<td><?php echo $data->PEMILIK_HAK;?></td>
		</tr>
		<tr>
			<th>Partner</th>
			<td>:</td>
			<td>
			
				<!-- partner haki -->
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
									<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/hapus_partner/'.$data->KD_HAKI.'/HAKI/'.$p->PARTNER;?>"  class="btn btn-mini"><i class="icon-trash hapus-partner"></i></a></td>
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