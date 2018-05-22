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
					$.post("<?php echo base_url().'bkd/dosen/bebankerja/editcepat';?>",
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
					$.post("<?php echo base_url().'bkd/dosen/bebankerja/editcepat';?>",
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
<div id="content">
<div>

	<?php 
		// cek kode
		switch($kode){
			case "A" : $title = 'pendidikan'; break;
			case "B" : $title = 'penelitian'; break;
			case "C" : $title = 'pengabdian masyarakat'; break;
			case "D" : $title = 'penunjang lain'; break;
			case "F" : $title = 'Narasumber'; break;
			case "G" : $title = 'Publikasi'; break;
			case "H" : $title = 'HAKI'; break;
		}
		
	?>
	<?php echo $this->s00_lib_output->output_info_dsn();?>
	
	<div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode;?>" class="current"><?php echo ucwords($title);?></a>
		</ul><div style="clear:both"></div>
	</div>
	
	<?php $this->load->view('dosen/form_history');?>
	
	<?php if($kode == 'F'){?>
		<h2>Data <?php echo ucwords($title);?>/Pembicara</h2>
	<?php }else{?>
		<h2>Beban Kerja Bidang <?php echo ucwords($title);?></h2>
	<?php } ?>
	<h3>Tahun Akademik : <?php echo $this->session->userdata('ta');?>, Semester : <?php echo $this->session->userdata('smt');?></h3>
	<?php
	    $a = $this->session->flashdata('msg');
	    if($a!=null){?>
	        <div class="alert alert-<?php echo $a[0]?> alert-msg">
	            <?php echo $a[1]?>
	        </div>
	    <?php }
	    $b = $this->session->flashdata('msg_update');
	    	if($b!=null){?>
	    		<div class="alert alert-<?php echo $b[0]?> alert-msg">
		            <?php echo $b[1]?>
		        </div>
	    	<?php }
	     $c = $this->session->flashdata('msg_move');
	    	if($c!=null){?>
	    		<div class="alert alert-<?php echo $c[0]?> alert-msg">
		            <?php echo $c[1]?>
		        </div>
	    	<?php }
	?>
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
	.noborder tr, .noborder td{
		border: none;
		padding-left: 20px;
	}

	.foo {
	  float: left;
	  width: 10px;
	  height: 10px;
	  margin: 5px;
	  border: 1px solid rgba(0, 0, 0, .2);
	  border-radius: 2px;
	}

	.magenta {
	  background: #b31010;
	}
	</style>
	
	<?php 
		$statusVer = array(''=>'<i class="icon-question-circle" title="Belum diverifikasi oleh Asesor"></i>', 
							0 => '<i class="icon-remove-circle" title="tidak disetujui oleh Asesor"></i>', 
							1 => '<i class="icon-ok-circle"  title="disetujui oleh Asesor"></i>')
	?>
	<?php
		/*echo "<pre>";
		print_r($data_beban);
		echo "<pre>";*/
	?>
	<div class="scrollbar">
		<?php if($kode == 'a'){?>
			
			<table class="table table-bordered table-hover">
			<tr>
				<th>No.</th>
				<th>Judul Acara</th>
				<th>Tgl. Mulai</th>
				<th>Tgl. Selesai</th>
				<th colspan="3">Aksi</th>
			</tr>
			<?php if(!empty($data_beban)){ $no=0; foreach ($data_beban as $data){ $no++; ?>
			<tr>
				<td align="center"><?php echo $no;?>.</td>
				<td><?php echo $data->JUDUL_ACARA;?></td>
				<td align="center"><?php echo $data->BT_MULAI;?></td>
				<td align="center"><?php echo $data->BT_SELESAI;?></td>
				<th align="center"><a href="<?php echo base_url().'bkd/dosen/bebankerja/narasumber/edit/'.$data['KD_NP'];?>" class="btn btn-small"><i class="icon icon-edit"></i> Edit</a></th>
				<th align="center"><a onclick="return hapus('Apakah Anda ingin menghapus data ini?')" href="<?php echo base_url().'bkd/dosen/bebankerja/narasumber/delete/'.$data['KD_NP'];?>" class="btn btn-small"><i class="icon icon-trash"></i> Hapus</a></th>
			</tr>
			<?php }}else{?>
			<tr><td colspan="6" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>
			<?php } ?>
			</table>

		<?php }else{ ?>
			<?php
				if($kode == 'B'){
					if($nira['NIRA1']=='' AND $nira['NIRA2']==''){?>
						<div class='bs-callout bs-callout-on-progress'>Anda belum mengisi daftar asesor dosen, untuk dapat melihat BKD Sertifikasi bidang penelitian silahkan isi data asesor terlebih dahulu dengan klik tombol berikut <a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/biodata/edit_biodata/'?>">Isi Data Asesor</a></div>
					<?php }else{?>
						<table border="0" cellspacing="0" class="table table-bordered table-hover">
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2" width="180">Jenis Kegiatan</th>
					<th colspan="2">Beban Kerja</th>
					<th rowspan="2">Masa Penugasan</th>
					<th colspan="2">Kinerja</th>
					<th rowspan="2">Rekomendasi</th>
					<th rowspan="2">Status</th>
					<th rowspan="2" style="border-right:none">Aksi</th>
				</tr>
				<tr>
					<th>Bukti Penugasan</th>
					<th>SKS</th>
					<th>Bukti Dokumen</th>
					<th>SKS</th>
				</tr>
				<?php 
					if (empty($data_beban)){
						echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN...</td></tr>';
					}else{
						$no=0; $sks_beban = 0; $sks_bukti = 0; $jml_unused=0;
						foreach ($data_beban as $bp){ $no++;
							$a = (float) str_replace(",", ".", $bp->SKS_PENUGASAN);
							$b =  (float) str_replace(",", ".", $bp->SKS_BKT);
							$sks_beban = $sks_beban+$a;
							$sks_bukti = $sks_bukti+$b;
							
							if($bp->STATUS==1){ $status="Diterima"; } 
							else if($bp->STATUS==0) { $status="Ditolak"."<br>".$bp->KOMENTAR; } 
							else { $status="Unverified"; }

							$cls_min_mhs = "";
							$cls_min_mhs_th = "";
							if(isset($bp->KETERANGAN)){
								if($bp->STS_JML_MHS == 0){
									$cls_min_mhs = "error";
									$cls_min_mhs_th = "style='background-color: #f1c6c6;'";
									$jml_unused++;
								}
							}
							if($bp->STATUS==0){
								$cls_min_mhs = "error";
								$cls_min_mhs_th = "style='background-color: #f1c6c6;'";
								$jml_unused++;
							}
						?>

						<tr class ="<?php echo $bp->KD_BK;?> <?php echo $cls_min_mhs?>" >
							<th <?php echo $cls_min_mhs_th?>><?php echo $no;?></th>
							<td class="JENIS_KEGIATAN"><a class="detail" title="Lihat detail beban kerja" href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK;?>"><?php echo $bp->JENIS_KEGIATAN;?></a> <?php //echo $statusVer[$bp->STATUS];?></td>
							<th class="BKT_PENUGASAN" <?php echo $cls_min_mhs_th; ?>>
							<?php if($is_crud == 1){?>
								<p class="btn-group">
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-isi';?>" class="btn btn-mini" title="Isi bukti penugasan">
									<i class="icon-pencil"></i></a>
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-cari';?>" class="btn btn-mini" title="Cari dokumen penugasan">
									<i class="icon-search"></i></a>
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-upload';?>" class="btn btn-mini" title="Upload dokumen penugasan">
									<i class="icon-upload"></i></a>
								</p>
							<?php }else{ echo $bp->BKT_PENUGASAN; }?>
							</th>

							<td class="SKS_PENUGASAN" align="center"><?php echo (float) str_replace(",", ".", $bp->SKS_PENUGASAN);?></td>
							<td class="MASA_PENUGASAN"><?php echo $bp->MASA_PENUGASAN;?></td>
							<th class="BKT_DOKUMEN" <?php echo $cls_min_mhs_th; ?>>
							<?php if($is_crud == 1){?>
								<p class="btn-group">
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-isi';?>" class="btn btn-mini" title="Isi bukti kinerja">
									<i class="icon-pencil"></i></a>
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-cari';?>" class="btn btn-mini" title="Cari dokumen kinerja">
									<i class="icon-search"></i></a>
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-upload';?>" class="btn btn-mini" title="Upload dokumen kinerja">
									<i class="icon-upload"></i></a>
								</p>
							<?php }else{ echo $bp->BKT_DOKUMEN; }?>
							</th>
							<td class="SKS_BKT" align="center"><?php echo (float) str_replace(",", ".", $bp->SKS_BKT);?></td>
							<td class="REKOMENDASI"><?php echo $bp->REKOMENDASI;?></td>
							<td class="STATUS"><?php echo $status;?></td>
							<th align="center"><a <?php if($bp->SYARAT_PINDAH == 1){?> href="<?php echo site_url().'bkd/dosen/bebankerja/move/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" <?php }else{?> href="#" <?php }?> <?php
							if($bp->SYARAT_PINDAH == 1){?> class="btn btn-success" style="width: 40px; text-align: center;" <?php }else{?> class="btn btn-danger" style="width: 35px; text-align: center; pointer-events:none; cursor:default;"<?php }?> title="bisa di pindah ke BKD Sertifikasi">
								<?php if($bp->SYARAT_PINDAH == 1/*!empty($konversi[$bp->KD_KAT])*/){echo "<i class='icon-white icon-arrow-right'></i><p style='color:white; text-align:center; font-weight:normal; font-size:12px;'>Pindah</p>";}else{echo "<i class='icon-white icon-remove'></i><p style='color:white; text-align:center; font-weight:normal; font-size:12px;'>Tetap</p>";}?></a></th>
						</tr>
					<?php } ?>
					<tr class="total">
						<td></td>
						<td colspan="2">Jumlah Beban Kerja</td>
						<td align="center"><?php echo $sks_beban; ?></td>
						<td colspan="2">Jumlah Kinerja</td>
						<td align="center"><?php echo $sks_bukti; ?></td>
						<td colspan="4"></td>
					</tr>
				<?php }  ?>
			</table>
					<?php }
				}else{?>
					<table border="0" cellspacing="0" class="table table-bordered table-hover">
				<tr>
					<th rowspan="2">No</th>
					<th rowspan="2" width="180">Jenis Kegiatan</th>
					<th colspan="2">Beban Kerja</th>
					<th rowspan="2">Masa Penugasan</th>
					<th colspan="2">Kinerja</th>
					<th rowspan="2">Rekomendasi</th>
					<th rowspan="2">Status</th>
					<th rowspan="2" style="border-right:none">Aksi</th>
				</tr>
				<tr>
					<th>Bukti Penugasan</th>
					<th>SKS</th>
					<th>Bukti Dokumen</th>
					<th>SKS</th>
				</tr>
				<style type="text/css">
					.btn-mini{
						background-: green;
					}
				</style>
				<?php 
					if (empty($data_beban)){
						echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN...</td></tr>';
					}else{
						$no=0; $sks_beban = 0; $sks_bukti = 0; $sks_asli=0; $jml_unused=0;
						foreach ($data_beban as $bp){ $no++;
							$a = (float) str_replace(",", ".", $bp->SKS_PENUGASAN);
							$b =  (float) str_replace(",", ".", $bp->SKS_BKT);

							$c = (float) str_replace(",", ".", $bp->JML_SKS);
							$sks_asli = $sks_asli+$c;

							$sks_beban = $sks_beban+$a;
							$sks_bukti = $sks_bukti+$b;
							
							if($bp->STATUS==1){ $status="Diterima"; } 
							else if($bp->STATUS==0) { $status="Ditolak"."<br>".$bp->KOMENTAR; } 
							else { $status="Unverified"; }

							$cls_min_mhs = "";
							$cls_min_mhs_th = "";
							if(isset($bp->KETERANGAN)){
								if($bp->STS_JML_MHS == 0){
									$cls_min_mhs = "error";
									$cls_min_mhs_th = "style='background-color: #f1c6c6;'";
									$jml_unused++;
								}
							}
							if($bp->STATUS==0){
								$cls_min_mhs = "error";
								$cls_min_mhs_th = "style='background-color: #f1c6c6;'";
								$jml_unused++;
							}
						?>

						<tr class ="<?php echo $bp->KD_BK;?> <?php echo $cls_min_mhs?>">
							<th <?php echo $cls_min_mhs_th?>><?php echo $no;?></th>
							<td class="JENIS_KEGIATAN"><a class="detail" title="Lihat detail beban kerja" href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK;?>"><?php echo $bp->JENIS_KEGIATAN;?></a> <?php //echo $statusVer[$bp->STATUS];?></td>
							<th class="BKT_PENUGASAN" <?php echo $cls_min_mhs_th; ?>>
							<?php if($is_crud == 1){?>
								<p class="btn-group">
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-isi';?>" class="btn btn-mini" title="Isi bukti penugasan">
									<i class="icon-pencil"></i></a>
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-cari';?>" class="btn btn-mini" title="Cari dokumen penugasan">
									<i class="icon-search"></i></a>
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-upload';?>" class="btn btn-mini" title="Upload dokumen penugasan">
									<i class="icon-upload"></i></a>
								</p>
							<?php }else{ echo $bp->BKT_PENUGASAN; }?>
							</th>

							<td class="SKS_PENUGASAN" align="center"><?php echo (float) str_replace(",", ".", $bp->JML_SKS);?></td>
							<td class="MASA_PENUGASAN"><?php echo $bp->MASA_PENUGASAN;?></td>
							<th class="BKT_DOKUMEN" <?php echo $cls_min_mhs_th; ?>>
							<?php if($is_crud == 1){?>
								<p class="btn-group">
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-isi';?>" class="btn btn-mini" title="Isi bukti kinerja">
									<i class="icon-pencil"></i></a>
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-cari';?>" class="btn btn-mini" title="Cari dokumen kinerja">
									<i class="icon-search"></i></a>
									<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-upload';?>" class="btn btn-mini" title="Upload dokumen kinerja">
									<i class="icon-upload"></i></a>
								</p>
							<?php }else{ echo $bp->BKT_DOKUMEN; }?>
							</th>
							<td class="SKS_BKT" align="center"><?php echo (float) str_replace(",", ".", $bp->SKS_BKT);?></td>
							<td class="REKOMENDASI"><?php echo $bp->REKOMENDASI;?></td>
							<td class="STATUS"><?php echo $status;?></td>
							<!-- <th><a href="<?php echo site_url().'bkd/dosen/bebankerja/edit/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" class="btn btn-mini" <?php echo $tombol;?>><i class="icon icon-edit"></i> Edit</a></th>
							<th><a class="btn btn-mini" onclick="return hapus('Anda yakin ingin menghapus data ini?')" href="<?php echo site_url().'bkd/dosen/bebankerja/hapus_hybrid/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" <?php echo $tombol;?>><i class="icon icon-trash"></i> Hapus</a></th> -->
							<th align="center" <?php echo $cls_min_mhs_th; ?>><a <?php if($bp->SYARAT_PINDAH == 1){?> href="<?php echo site_url().'bkd/dosen/bebankerja/move/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" <?php }else{?> href="#" <?php }?> <?php
							if($bp->SYARAT_PINDAH == 1){?> class="btn btn-success" style="width: 40px; text-align: center;" <?php }else{?> class="btn btn-danger" style="width: 35px; text-align: center; pointer-events:none; cursor:default;"<?php }?> title="bisa di pindah ke BKD Sertifikasi">
								<?php if($bp->SYARAT_PINDAH == 1/*!empty($konversi[$bp->KD_KAT])*/){echo "<i class='icon-white icon-arrow-right'></i><p style='color:white; text-align:center; font-weight:normal; font-size:12px;'>Pindah</p>";}else{echo "<i class='icon-white icon-remove'></i><p style='color:white; text-align:center; font-weight:normal; font-size:12px;'>Tetap</p>";}?></a></th>
						</tr>
					<?php } ?>
					<tr class="total">
						<td></td>
						<td colspan="2">Jumlah Beban Kerja</td>
						<td align="center"><?php echo $sks_asli; ?></td>
						<td colspan="2">Jumlah Kinerja</td>
						<td align="center"><?php echo $sks_bukti; ?></td>
						<td colspan="4"></td>
					</tr>
				<?php }  ?>
			</table>

				<?php }
			?>
			<!-- <div class='bs-callout bs-callout-on-progress'>Coba</div> -->
			
		<?php } ?>
	</div>
	<?php
		if($kode=='B'){

			if($nira['NIRA1']=='' AND $nira['NIRA2']==''){?>

			<?php }else{?>
				<?php if($is_crud == 1){?>
					<p><a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/bebankerja/tambah/'.$kode;?>">Tambah</a></p>
				<?php } ?>
			<?php }
		}else{?>
			<?php if($is_crud == 1){?>
				<p><a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/bebankerja/tambah/'.$kode;?>">Tambah</a></p>
			<?php } ?>
		<?php }
	?>
	<?php
		if(isset($jml_unused)){?>
				<?php if($jml_unused > 0){ ?>
				<div>
					<table class="table noborder">
						<tbody><tr>
							<td colspan="2"><b>Keterangan :</b></td>
						</tr>
						<tr>
							<td style="width: 10px;"><div class="foo magenta"></div></td>
							<td style="padding-left: 0px;">: Data tidak memenuhi kriteria</td>
						</tr>		
					</tbody></table>
				</div>
				<?php } ?>
		<?php }
	?>
	
	<br/>
	<!-- detail data beban kerja -->
	<?php 
	
		if(!empty($current_data)){ 
			/*echo "<pre>";
	print_r($current_data); 
	echo "<pre>";*/
			foreach ($current_data as $data);
		?>
		<style>.total{ font-weight:bold; }</style>
		<h2>Detail Data Beban Kerja</h2>
		<table class="table table-condensed table-hover">
			<?php if($kode == 'A' || $kode == 'B' || $kode == 'C' || $kode == 'D' ||$kode == 'H' || $kode == 'F' ){ 
				if($kode == 'F' or $kode == 'H'){?>
					<tr><th width="200">Tingkat</th><td>:</td><td><?php echo $data->NM_TINGKAT;?></td></tr>
				<?php }else{ ?>
					<tr><th width="200">Kategori</th><td>:</td><td><?php echo $data->NM_KAT;?></td></tr>
				<?php } ?>
			<?php } ?>
			<!-- data narasumber -->
			<?php if($kode == 'F'){ ?>
				<tr><th>Judul Acara</th><td>:</td><td><?php echo $data->JUDUL_ACARA;?></td></tr>
				<tr><th>Tanggal Mulai</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->BT_MULAI));?></td></tr>
				<tr><th>Tanggal Selesai</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->BT_SELESAI));?></td></tr>
				<tr><th>Lokasi Acara</th><td>:</td><td><?php echo $data->LOKASI_ACARA;?></td></tr>
				<tr><th>Laman Publikasi</th><td>:</td><td><a target="_blank" class="link" href="<?php echo $data->LAMAN_PUBLIKASI;?>"><?php echo $data->LAMAN_PUBLIKASI;?></a></td></tr>
			<?php } ?>

			<tr><th>Jenis Kegiatan</th><td>:</td><td><?php echo $data->JENIS_KEGIATAN;?></td></tr>
			<tr><th>Bukti Penugasan</th><td>:</td><td><?php echo $data->BKT_PENUGASAN;?></td></tr>
			<tr><th>Bukti Kinerja</th><td>:</td><td><?php echo $data->BKT_DOKUMEN;?></td></tr>
			<!-- data pendidikan -->
			<?php if($kode == 'A'){ ?>
				<?php
					if($data->NILAI_KAT == 'JUMLAH'){
						$jml_sks = (float) str_replace(',', '.', $data->SKS_BKT);
						$satuan = $data->SATUAN;
					}else if($data->NILAI_KAT =='SKS'){
						$jml_sks = $data->JML_SKS;
						$satuan = "MHS";
					}
				?>
				<tr><th>Jenjang</th><td>:</td><td><?php echo $data->JENJANG;?></td></tr>
				<tr><th>Jumlah SKS</th><td>:</td><td><?php echo $jml_sks;?></td></tr>
				<tr><th>Jumlah <?php echo $satuan;?></th><td>:</td><td><?php echo $data->JML_MHS;?></td></tr>
				<tr><th>Tempat Pelaksanaan</th><td>:</td><td><?php echo $data->TEMPAT;?></td></tr>
			<?php } ?>
			<!-- data penelitian -->
			<?php if($kode == 'B'){ ?>
				<tr><th>Judul Penelitian</th><td>:</td><td><?php echo $data->JUDUL_PENELITIAN;?></td></tr>
				<tr><th>Tanggal Mulai</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->BT_MULAI));?></td></tr>
				<tr><th>Tanggal Selesai</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->BT_SELESAI));?></td></tr>
				<tr><th>Sumber dana</th><td>:</td><td><?php echo $data->SUMBER_DANA;?></td></tr>
				<tr><th>Jumlah dana</th><td>:</td><td><?php echo $data->JUMLAH_DANA;?></td></tr>
				<tr><th>Laman Publikasi</th><td>:</td><td><a target="_blank" class="link" href="<?php echo $data->LAMAN_PUBLIKASI;?>"><?php echo $data->LAMAN_PUBLIKASI;?></a></td></tr>
			<?php } ?>
			<!-- data Publikasi -->
			<?php if($kode == 'G'){ ?>
				<tr><th>Judul Publikasi</th><td>:</td><td><?php echo $data->JUDUL_PUBLIKASI;?></td></tr>
				<tr><th>Tanggal SK</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->TANGGAL_SK));?></td></tr>
				<tr><th>Penerbit</th><td>:</td><td><?php echo $data->PADA;?></td></tr>
				<tr><th>Tingkat</th><td>:</td><td><?php echo $data->TINGKAT;?></td></tr
			<?php } ?>
			<!-- data HAKI -->			
			<?php if($kode == 'H'){ ?>
				<tr><th>Judul HAKI</th><td>:</td><td><?php echo $data->JUDUL_HAKI;?></td></tr>
				<tr><th>Nomor SK</th><td>:</td><td><?php echo $data->NOMOR_SK;?></td></tr>
				<tr><th>Tanggal SK</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->TANGGAL_SK));?></td></tr>
				<tr><th>Penerbit SK</th><td>:</td><td><?php echo $data->PENERBIT_SK;?></td></tr>
				<tr><th>Pemilik Hak</th><td>:</td><td><?php echo $data->PEMILIK_HAK;?></td></tr>
				<tr><th>Jenis HAKI</th><td>:</td><td><?php echo $data->JENIS_HAKI;?></td></tr>
			<?php } ?>

			<!-- data pengabdian -->
			<?php if($kode == 'C'){ ?>
				<tr><th>Tanggal Mulai</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->BT_MULAI));?></td></tr>
				<tr><th>Tanggal Selesai</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->BT_SELESAI));?></td></tr>
				<tr><th>Sumber dana</th><td>:</td><td><?php echo $data->SUMBER_DANA;?></td></tr>
				<tr><th>Jumlah dana</th><td>:</td><td><?php echo $data->JUMLAH_DANA;?></td></tr>
			<?php } ?>
			
			<!-- data penunjang -->
			<?php if($kode == 'D'){ ?>
				<?php $jml_sks = (float) str_replace(',', '.', $data->SKS_BKT)?>
				<tr><th>Jumlah SKS</th><td>:</td><td><?php echo $jml_sks;?></td></tr>
				<tr><th>Jumlah MHS</th><td>:</td><td><?php echo $data->JML_MHS;?></td></tr>
			<?php } ?>

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
							if (substr($data->FILE_PENUGASAN, 0,5) == 'surat'){
								$split = explode(':', $data->FILE_PENUGASAN);
								$id_surat = $split[1]; ?>
								
								<?php $kd_sistem = "21a4b81cbb4f"; ?>
								<form action="http://surat.uin-suka.ac.id/internal/surat_keluar/detail/<?php echo $id_surat;?>" method="POST" target="_BLANK">
									<!-- input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
									<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/ -->
									<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
								</form>
								
							<?php 	
							}else{
								$url_fp = base_url().'bkd/dosen/bebankerja/getFileBlob/BKD_DOC_KINERJA/BLOB_PENUGASAN/'.$data->KD_BK;
								echo '<iframe src="'.$url_fp.'" width="600" height="500" style="border: none;"></iframe>';
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
								<form action="http://surat.uin-suka.ac.id/internal/surat_keluar/detail/<?php echo $id_surat;?>" method="POST" target="_BLANK">
									<!-- input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
									<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/ -->
									<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
								</form>
								
							<?php 	
							}else{
								$url_fc = base_url().'bkd/dosen/bebankerja/getFileBlob/BKD_DOC_KINERJA/BLOB_CAPAIAN/'.$data->KD_BK;
								echo '<iframe src="'.$url_fc.'" width="600" height="500" style="border: none;"></iframe>';
							}
							
						}
					?>
				</td>
			</tr>
			<?php if($kode == 'B' || $kode == 'C'){ ?>
			<tr>
				<th>Partner</th><td>:</td>
				<td>
				<!-- partner -->
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
						<td><a href="<?php echo base_url().'bkd/dosen/bebankerja/hapus_partner/'.$bp->KD_BK.'/PENELITIAN/'.$p->PARTNER;?>" class="btn btn-mini"><i class="icon-trash hapus-partner"></i></a></td>
					</tr>
					<?php } ?>
				</table>
				<?php }else { echo '-';}	?>			
				</td>
			</tr>
			<?php } ?>
		</table>
	<?php }	?>

		
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
	
});
</script>
<script type="text/javascript" charset="utf-8">
      $(function(){
        setTimeout('closing_msg()', 5000)
      })

      function closing_msg(){
        $(".alert-msg").slideUp();
      }
    </script>
</div>	
</div>