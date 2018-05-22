<div id="content">
<div>
	<?php 
		// cek kode
		switch($kode){
			case "A" : $title = 'pendidikan'; break;
			case "B" : $title = 'penelitian'; break;
			case "C" : $title = 'pengabdian masyarakat'; break;
			case "D" : $title = 'penunjang lain'; break;
			case "F" : $title = 'narasumber'; break;
		}
		
	?>
	
	<?php echo $this->s00_lib_output->output_info_dsn();?>
    <!-- menu -->
    <div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>" class="current">Kinerja Dosen</a>
			<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode;?>" class="current"><?php echo ucwords($title);?></a>
		</ul><div style="clear:both"></div>
	</div>

	<?php $this->load->view('dosen/form_history');?>
    
	<?php if($kode == 'F'){?>
		<h2>Data <?php echo ucwords($title);?>/Pembicara</h2>
	<?php }else{?>
		<h2>Beban Kerja Bidang <?php echo ucwords($title);?></h2>
	<?php } ?>
	<h3>Tahun Akadamik : <?php echo $thn;?>, Semester : <?php echo $smt;?></h3>
	<!-- tampilkan beban kerja yang telah diambil -->
		<table border="0" cellspacing="0" class="table table-bordered table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2" width="180">Jenis Kegiatan</th>
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
					?>
					<tr class ="<?php echo $bp->KD_BK;?>">
						<th><?php echo $no;?></th>
						<td class="JENIS_KEGIATAN"><a class="detail" title="Lihat detail beban kerja" href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK;?>"><?php echo $bp->JENIS_KEGIATAN;?></a></td>
						<th class="BKT_PENUGASAN">
						<?php if($is_crud == 1){?>
							<p class="btn-group">
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-isi';?>" class="btn btn-mini" title="Isi bukti penugasan">
								<i class="icon-pencil"></i></a>
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-cari';?>" class="btn btn-mini" title="Cari dokumen penugasan">
								<i class="icon-search"></i></a>
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/penugasan-upload';?>" class="btn btn-mini" title="Upload dokumen penugasan">
								<i class="icon-upload"></i></a>
							</p>
						<?php } else { echo $bp->BKT_PENUGASAN;}?>
						</th>
						<td class="SKS_PENUGASAN" align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
						<td class="MASA_PENUGASAN"><?php echo $bp->MASA_PENUGASAN;?></td>
						<th class="BKT_DOKUMEN">
						<?php if($is_crud == 1){?>
							<p class="btn-group">
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-isi';?>" class="btn btn-mini" title="Isi bukti kinerja">
								<i class="icon-pencil"></i></a>
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-cari';?>" class="btn btn-mini" title="Cari dokumen kinerja">
								<i class="icon-search"></i></a>
								<a href="<?php echo base_url().'bkd/dosen/bebankerja/data/'.$kode.'/'.$bp->KD_BK.'/kinerja-upload';?>" class="btn btn-mini" title="Upload dokumen kinerja">
								<i class="icon-upload"></i></a>
							</p>
						<?php } else { echo $bp->BKT_DOKUMEN;}?>
						</th>
						<td class="SKS_BKT" align="center"><?php echo (float)$bp->SKS_BKT;?></td>
						<td class="REKOMENDASI"><?php echo $bp->REKOMENDASI;?></td>
						<th><a href="<?php echo site_url().'bkd/dosen/bebankerja/edit/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" class="btn btn-mini" <?php echo $tombol;?>>
						<i class="icon icon-edit"></i> Edit</a></th>
						<th><a class="btn btn-mini" onclick="return hapus('Anda yakin ingin menghapus data ini?')" href="<?php echo site_url().'bkd/dosen/bebankerja/hapus_hybrid/'.$bp->KD_JBK.'/'.$bp->KD_BK;?>" <?php echo $tombol;?>><i class="icon icon-trash"></i> Hapus</a></th>
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
		<!-- <div class="bs-callout bs-callout-info"><b>Edit Cepat : </b>Klik ganda pada kolom yang ingin Anda ubah, kemudian tekan Enter untuk menyimpan</div>-->
	</div>
	
	
	<?php if($is_crud == 1){ ?>
		<p><a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/bebankerja/tambah/'.$kode;?>">Tambah</a></p>
	<?php } ?>
	
	
	
	<br/>
	<!-- detail data beban kerja -->
	<?php 
		if(!empty($current_data)){ 
			foreach ($current_data as $data);
		?>
		<style>.total{ font-weight:bold; }</style>
		<h2>Detail Data Beban Kerja</h2>
		<table class="table table-condensed table-hover">
			<?php if($kode == 'A' || $kode == 'B' || $kode == 'C'){ ?>
			<tr><th width="200">Kategori</th><td>:</td><td><?php echo $data->NM_KAT;?></td></tr>
			<?php } ?>
			<tr><th>Jenis Kegiatan</th><td>:</td><td><?php echo $data->JENIS_KEGIATAN;?></td></tr>
			<tr><th>Bukti Penugasan</th><td>:</td><td><?php echo $data->BKT_PENUGASAN;?></td></tr>
			<tr><th>Bukti Kinerja</th><td>:</td><td><?php echo $data->BKT_DOKUMEN;?></td></tr>
			<!-- data pendidikan -->
			<?php if($kode == 'A'){ ?>
				<tr><th>Jenjang</th><td>:</td><td><?php echo $data->JENJANG;?></td></tr>
				<tr><th>Jumlah Mhs</th><td>:</td><td><?php echo $data->JML_MHS;?></td></tr>
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

			<!-- data pengabdian -->
			<?php if($kode == 'C'){ ?>
				<tr><th>Tanggal Mulai</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->BT_MULAI));?></td></tr>
				<tr><th>Tanggal Selesai</th><td>:</td><td><?php echo date('d/m/Y', strtotime($data->BT_SELESAI));?></td></tr>
				<tr><th>Sumber dana</th><td>:</td><td><?php echo $data->SUMBER_DANA;?></td></tr>
				<tr><th>Jumlah dana</th><td>:</td><td><?php echo $data->JUMLAH_DANA;?></td></tr>
			<?php } ?>
			
			<!-- data penunjang -->
			<?php if($kode == 'D'){ ?>
				<tr><th>Outcome</th><td>:</td><td><?php echo $data->OUTCOME;?></td></tr>
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
	.total td{font-weight:bold;}
	textarea, .table.table-bordered select{ position:relative; width:95%; height:99%; border:none; font-family:segoe, opensans, sans-serif; font-size:13px;}
	textarea:focus{box-shadow:none; margin:0; padding:0;}
	</style>

</div>	
</div>