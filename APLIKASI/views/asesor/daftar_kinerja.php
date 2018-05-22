<style>
	td.bukti:hover{
		background-color:#FFFFEE;
		cursor:pointer;
	}
</style>
<div id="content">
<?php $this->load->view('fungsi');?>
<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/verifikator/kinerja';?>">Asesor Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/verifikator/kinerja/dosen';?>">Rencana dan Kinerja</a></li>
		<li><a href="<?php echo base_url().'bkd/verifikator/kinerja/dosen/'.$this->uri->segment(5);?>">Data Dosen</a></li>
	</ul>
	
	<h2>Asesmen Kinerja Dosen</h2>
	
	<?php $this->load->view('asesor/form_semester');?>

	<table border="0" width="80%" class="table">
		<tr>
			<td>NIP / No.Sertifikat</td>
			<?php if(empty($noser[0]['NO_SERTIFIKAT']) || $noser[0]['NO_SERTIFIKAT'] == '') $noser = ''; else $noser = $noser[0]['NO_SERTIFIKAT']; ?>
			<td>: <?php	echo _generate_nip($dosen[0]['KD_DOSEN'])." / ".$noser; ?></td>
		</tr>
		<tr>
			<td>Nama Lengkap</td>
			<td>: <?php echo $nm_dosen[0];?></td>
		</tr>
		<tr>
			<td>Program Studi</td>
			<td>: <?php echo $nm_dosen[1];?></td>
		</tr>
		<tr>
			<td>Status</td>
			<td>: <?php echo "(".$status.") ".$nama_jenis_dosen;?></td>
		</tr>
		<tr>
			<td>Tahun Akademik</td>
			<th align="left">: <?php echo konv_label_ta($ta)." - ".konv_label_smt($smt);?></th>
		</tr>
	</table>
	<br/>

	<h3>Bidang Pendidikan dan Pengajaran</h3>
	<table border="0" cellspacing="0" class="table table-bordered table-hover" width="2000">
		<tr>
			<th rowspan="2">No.</th>
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
			if (empty($pendidikan)){
				echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN...</td></tr>';
			}else{
				$no=0; $sks_beban = 0; $sks_bukti = 0;
				foreach ($pendidikan as $bp){ $no++; 
					$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
					$sks_bukti = $sks_bukti+$bp->SKS_BKT;
					if($bp->STATUS == 1){$class = 'btn-success'; $classb = ''; $icon = 'icon-white'; $iconb = '';}
					else if($bp->STATUS == 0){ $class = ''; $classb = 'btn-danger'; $icon = ''; $iconb = 'icon-white';}
					else {$class = ''; $classb = ''; $icon = ''; $iconb = '';}
					# STATUS PENUGASAN
					if($bp->STATUS_PENUGASAN == 1){$classp = 'btn-success'; $classbp = ''; $iconp = 'icon-white'; $iconbp = '';}
					else if($bp->STATUS_PENUGASAN == 0){ $classp = ''; $classbp = 'btn-danger'; $iconp = ''; $iconbp = 'icon-white';}
					else {$classp = ''; $classbp = ''; $iconp = ''; $iconbp = '';}
					# STATUS CAPAIAN
					if($bp->STATUS_CAPAIAN == 1){$classc = 'btn-success'; $classbc = ''; $iconc = 'icon-white'; $iconbc = '';}
					else if($bp->STATUS_CAPAIAN == 0){ $classc = ''; $classbc = 'btn-danger'; $iconc = ''; $iconbc = 'icon-white';}
					else {$classc = ''; $classbc = ''; $iconc = ''; $iconbc = '';}
				?>
				<tr class ="<?php echo $bp->KD_BK;?>">
					<td align="center"><?php echo $no;?>.</td>
					<td><?php echo $bp->JENIS_KEGIATAN;?></a></td>
					<td class="fp_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_PENUGASAN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
					<td><?php echo $bp->MASA_PENUGASAN;?></td>
					<td class="fc_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_DOKUMEN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_BKT;?></td>
					<td><?php echo $bp->REKOMENDASI;?></td>
					<td align="center">
						<p class="btn-group">
							<button class="btn btn-mini <?php echo $class; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#1">
								<i class="icon-ok <?php echo $icon;?> icon_<?php echo $bp->KD_BK;?>"></i>
							</button>
							<button class="btn btn-mini <?php echo $classb; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#0">
								<i class="icon-remove <?php echo $iconb;?> iconb_<?php echo $bp->KD_BK;?>"></i>
							</button>
						</p>
					</td>
				</tr>
				<tr class="file-penugasan_<?php echo $bp->KD_BK;?>" style="display:none">
					<td></td>
					<td colspan="8">
						<div class="label-data">
							<table class="table table-bordered">
								<tr>
									<th width="175">Bukti Penugasan</td>
									<td><?php echo $bp->BKT_PENUGASAN;?></td>
									<td>
										<p class="btn-group">
											<button class="btn btn-mini <?php echo $classp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#1">
												<i class="icon-ok <?php echo $iconp;?> iconp_<?php echo $bp->KD_BK;?>"></i>
											</button>
											<button class="btn btn-mini <?php echo $classbp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#0">
												<i class="icon-remove <?php echo $iconbp;?> iconbp_<?php echo $bp->KD_BK;?>"></i>
											</button>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div class="doc-data">
						<?php 
							$fp = strlen($bp->FILE_PENUGASAN);
							if($fp > 1){
								# cek apakah file surat apa bukan
								if (substr($bp->FILE_PENUGASAN, 0,5) == 'surat'){
									$split = explode(':', $bp->FILE_PENUGASAN);
									$id_surat = $split[1]; ?>
									
									<?php $kd_sistem = "21a4b81cbb4f"; ?>
									<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
										<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
										<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
										<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
									</form>
									
								<?php 	
								}else{
									if($bp->FILE_PENUGASAN !== ''){
										/*$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_PENUGASAN;*/
										/*echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';*/
										$url_fp = base_url().'bkd/dosen/bebankerja/getFileBlob/BKD_DOC_KINERJA/BLOB_PENUGASAN/'.$bp->KD_BK;
										echo '<iframe src="'.$url_fp.'" width="700" height="500" style="border: none;"></iframe>';
									}else{
										echo "<p align='center'>TIDAK ADA FILE PENUGASAN YANG DIUPLOAD</p>";
									}
								}
							}
						?>
						</div>
					</td>
				</tr>
				
				<!-- FILE CAPAIAN -->
				<tr class="file-capaian_<?php echo $bp->KD_BK;?>" style="display:none">
					<td></td>
					<td colspan="8">
						<div class="label-data">
							<table class="table table-bordered">
								<tr>
									<th width="175">Bukti Capaian</td>
									<td><?php echo $bp->BKT_DOKUMEN;?></td>
									<td>
										<p class="btn-group">
											<button class="btn btn-mini <?php echo $classc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#1">
												<i class="icon-ok <?php echo $iconc;?> iconc_<?php echo $bp->KD_BK;?>"></i>
											</button>
											<button class="btn btn-mini <?php echo $classbc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#0">
												<i class="icon-remove <?php echo $iconbc;?> iconbc_<?php echo $bp->KD_BK;?>"></i>
											</button>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div class="doc-data">
						<?php 
							$fp = strlen($bp->FILE_CAPAIAN);
							if($fp > 1){
								# cek apakah file surat apa bukan
								if (substr($bp->FILE_CAPAIAN, 0,5) == 'surat'){
									$split = explode(':', $bp->FILE_CAPAIAN);
									$id_surat = $split[1]; ?>
									
									<?php $kd_sistem = "21a4b81cbb4f"; ?>
									<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
										<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
										<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
										<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
									</form>
									
								<?php 	
								}else{
									if($bp->FILE_PENUGASAN !== ''){
										$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_CAPAIAN;
										echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';
									}else{
										echo "<p align='center'>TIDAK ADA FILE CAPAIAN YANG DIUPLOAD</p>";
									}
								}
							}
						?>
						</div>
					</td>
				</tr>
				<!-- javascript -->
				<script>
					$(function(){
						$('.fp_<?php echo $bp->KD_BK;?>').click(function(){
							$('.file-penugasan_<?php echo $bp->KD_BK;?>').toggle('fast');
						});
						$('.fc_<?php echo $bp->KD_BK;?>').click(function(){
							$('.file-capaian_<?php echo $bp->KD_BK;?>').toggle('fast');
						});
						$('.aksi_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.icon_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconb_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconb_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.icon_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});
						// penugasan
						$('.aksip_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.iconp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconbp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconbp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});
						// capaian
						$('.aksic_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.iconc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconbc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconbc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});

					});
				</script>
				
				
			<?php } ?>
			<tr class="total">
				<th></th>
				<th colspan="2">Jumlah Beban Kerja</th>
				<th align="center"><?php echo $sks_beban; ?></th>
				<th colspan="2">Jumlah Kinerja</th>
				<th align="center"><?php echo $sks_bukti; ?></th>
				<th colspan="4"></th>
			</tr>
		<?php }  ?>
	</table>	
	
	<h3>Bidang Penelitian</h3>
	<table border="0" cellspacing="0" class="table table-bordered table-hover" width="2000">
		<tr>
			<th rowspan="2">No.</th>
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
			if (empty($penelitian)){
				echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN...</td></tr>';
			}else{
				$no=0; $sks_beban = 0; $sks_bukti = 0;
				foreach ($penelitian as $bp){ $no++; 
					$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
					$sks_bukti = $sks_bukti+$bp->SKS_BKT;
					if($bp->STATUS == 1){$class = 'btn-success'; $classb = ''; $icon = 'icon-white'; $iconb = '';}
					else if($bp->STATUS == 0){ $class = ''; $classb = 'btn-danger'; $icon = ''; $iconb = 'icon-white';}
					else {$class = ''; $classb = ''; $icon = ''; $iconb = '';}
					# STATUS PENUGASAN
					if($bp->STATUS_PENUGASAN == 1){$classp = 'btn-success'; $classbp = ''; $iconp = 'icon-white'; $iconbp = '';}
					else if($bp->STATUS_PENUGASAN == 0){ $classp = ''; $classbp = 'btn-danger'; $iconp = ''; $iconbp = 'icon-white';}
					else {$classp = ''; $classbp = ''; $iconp = ''; $iconbp = '';}
					# STATUS CAPAIAN
					if($bp->STATUS_CAPAIAN == 1){$classc = 'btn-success'; $classbc = ''; $iconc = 'icon-white'; $iconbc = '';}
					else if($bp->STATUS_CAPAIAN == 0){ $classc = ''; $classbc = 'btn-danger'; $iconc = ''; $iconbc = 'icon-white';}
					else {$classc = ''; $classbc = ''; $iconc = ''; $iconbc = '';}
				?>
				<tr class ="<?php echo $bp->KD_BK;?>">
					<td align="center"><?php echo $no;?>.</td>
					<td><?php echo $bp->JENIS_KEGIATAN;?></a></td>
					<td class="fp_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_PENUGASAN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
					<td><?php echo $bp->MASA_PENUGASAN;?></td>
					<td class="fc_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_DOKUMEN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_BKT;?></td>
					<td><?php echo $bp->REKOMENDASI;?></td>
					<td align="center">
						<p class="btn-group">
							<button class="btn btn-mini <?php echo $class; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#1">
								<i class="icon-ok <?php echo $icon;?> icon_<?php echo $bp->KD_BK;?>"></i>
							</button>
							<button class="btn btn-mini <?php echo $classb; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#0">
								<i class="icon-remove <?php echo $iconb;?> iconb_<?php echo $bp->KD_BK;?>"></i>
							</button>
						</p>
					</td>
				</tr>
				<tr class="file-penugasan_<?php echo $bp->KD_BK;?>" style="display:none">
					<td></td>
					<td colspan="8">
						<div class="label-data">
							<table class="table table-bordered">
								<tr>
									<th width="175">Bukti Penugasan</td>
									<td><?php echo $bp->BKT_PENUGASAN;?></td>
									<td>
										<p class="btn-group">
											<button class="btn btn-mini <?php echo $classp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#1">
												<i class="icon-ok <?php echo $iconp;?> iconp_<?php echo $bp->KD_BK;?>"></i>
											</button>
											<button class="btn btn-mini <?php echo $classbp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#0">
												<i class="icon-remove <?php echo $iconbp;?> iconbp_<?php echo $bp->KD_BK;?>"></i>
											</button>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div class="doc-data">
						<?php 
							$fp = strlen($bp->FILE_PENUGASAN);
							if($fp > 1){
								# cek apakah file surat apa bukan
								if (substr($bp->FILE_PENUGASAN, 0,5) == 'surat'){
									$split = explode(':', $bp->FILE_PENUGASAN);
									$id_surat = $split[1]; ?>
									
									<?php $kd_sistem = "21a4b81cbb4f"; ?>
									<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
										<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
										<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
										<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
									</form>
									
								<?php 	
								}else{
									if($bp->FILE_PENUGASAN !== ''){
										/*$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_PENUGASAN;

										echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';*/
										$url_fp = base_url().'bkd/dosen/bebankerja/getFileBlob/BKD_DOC_KINERJA/BLOB_PENUGASAN/'.$bp->KD_BK;
										echo '<iframe src="'.$url_fp.'" width="700" height="500" style="border: none;"></iframe>';
									}else{
										echo "<p align='center'>TIDAK ADA FILE PENUGASAN YANG DIUPLOAD</p>";
									}
								}
							}
						?>
						</div>
					</td>
				</tr>
				
				<!-- FILE CAPAIAN -->
				<tr class="file-capaian_<?php echo $bp->KD_BK;?>" style="display:none">
					<td></td>
					<td colspan="8">
						<div class="label-data">
							<table class="table table-bordered">
								<tr>
									<th width="175">Bukti Capaian</td>
									<td><?php echo $bp->BKT_DOKUMEN;?></td>
									<td>
										<p class="btn-group">
											<button class="btn btn-mini <?php echo $classc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#1">
												<i class="icon-ok <?php echo $iconc;?> iconc_<?php echo $bp->KD_BK;?>"></i>
											</button>
											<button class="btn btn-mini <?php echo $classbc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#0">
												<i class="icon-remove <?php echo $iconbc;?> iconbc_<?php echo $bp->KD_BK;?>"></i>
											</button>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div class="doc-data">
						<?php 
							$fp = strlen($bp->FILE_CAPAIAN);
							if($fp > 1){
								# cek apakah file surat apa bukan
								if (substr($bp->FILE_CAPAIAN, 0,5) == 'surat'){
									$split = explode(':', $bp->FILE_CAPAIAN);
									$id_surat = $split[1]; ?>
									
									<?php $kd_sistem = "21a4b81cbb4f"; ?>
									<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
										<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
										<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
										<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
									</form>
									
								<?php 	
								}else{
									if($bp->FILE_PENUGASAN !== ''){
										/*$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_CAPAIAN;
										echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';*/
										$url_fp = base_url().'bkd/dosen/bebankerja/getFileBlob/BKD_DOC_KINERJA/BLOB_PENUGASAN/'.$bp->KD_BK;
										echo '<iframe src="'.$url_fp.'" width="700" height="500" style="border: none;"></iframe>';
									}else{
										echo "<p align='center'>TIDAK ADA FILE CAPAIAN YANG DIUPLOAD</p>";
									}
								}
							}
						?>
						</div>
					</td>
				</tr>
				<!-- javascript -->
				<script>
					$(function(){
						$('.fp_<?php echo $bp->KD_BK;?>').click(function(){
							$('.file-penugasan_<?php echo $bp->KD_BK;?>').toggle('fast');
						});
						$('.fc_<?php echo $bp->KD_BK;?>').click(function(){
							$('.file-capaian_<?php echo $bp->KD_BK;?>').toggle('fast');
						});
						$('.aksi_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.icon_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconb_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconb_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.icon_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});
						// penugasan
						$('.aksip_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.iconp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconbp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconbp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});
						// capaian
						$('.aksic_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.iconc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconbc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconbc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});

					});
				</script>
				
				
			<?php } ?>
			<tr class="total">
				<th></th>
				<th colspan="2">Jumlah Beban Kerja</th>
				<th align="center"><?php echo $sks_beban; ?></th>
				<th colspan="2">Jumlah Kinerja</th>
				<th align="center"><?php echo $sks_bukti; ?></th>
				<th colspan="4"></th>
			</tr>
		<?php }  ?>
	</table>	

	<h3>Bidang Pengabdian dan Masyarakat</h3>
	<table border="0" cellspacing="0" class="table table-bordered table-hover" width="2000">
		<tr>
			<th rowspan="2">No.</th>
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
			if (empty($pengabdian)){
				echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN...</td></tr>';
			}else{
				$no=0; $sks_beban = 0; $sks_bukti = 0;
				foreach ($pengabdian as $bp){ $no++; 
					$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
					$sks_bukti = $sks_bukti+$bp->SKS_BKT;
					if($bp->STATUS == 1){$class = 'btn-success'; $classb = ''; $icon = 'icon-white'; $iconb = '';}
					else if($bp->STATUS == 0){ $class = ''; $classb = 'btn-danger'; $icon = ''; $iconb = 'icon-white';}
					else {$class = ''; $classb = ''; $icon = ''; $iconb = '';}
					# STATUS PENUGASAN
					if($bp->STATUS_PENUGASAN == 1){$classp = 'btn-success'; $classbp = ''; $iconp = 'icon-white'; $iconbp = '';}
					else if($bp->STATUS_PENUGASAN == 0){ $classp = ''; $classbp = 'btn-danger'; $iconp = ''; $iconbp = 'icon-white';}
					else {$classp = ''; $classbp = ''; $iconp = ''; $iconbp = '';}
					# STATUS CAPAIAN
					if($bp->STATUS_CAPAIAN == 1){$classc = 'btn-success'; $classbc = ''; $iconc = 'icon-white'; $iconbc = '';}
					else if($bp->STATUS_CAPAIAN == 0){ $classc = ''; $classbc = 'btn-danger'; $iconc = ''; $iconbc = 'icon-white';}
					else {$classc = ''; $classbc = ''; $iconc = ''; $iconbc = '';}
				?>
				<tr class ="<?php echo $bp->KD_BK;?>">
					<td align="center"><?php echo $no;?>.</td>
					<td><?php echo $bp->JENIS_KEGIATAN;?></a></td>
					<td class="fp_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_PENUGASAN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
					<td><?php echo $bp->MASA_PENUGASAN;?></td>
					<td class="fc_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_DOKUMEN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_BKT;?></td>
					<td><?php echo $bp->REKOMENDASI;?></td>
					<td align="center">
						<p class="btn-group">
							<button class="btn btn-mini <?php echo $class; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#1">
								<i class="icon-ok <?php echo $icon;?> icon_<?php echo $bp->KD_BK;?>"></i>
							</button>
							<button class="btn btn-mini <?php echo $classb; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#0">
								<i class="icon-remove <?php echo $iconb;?> iconb_<?php echo $bp->KD_BK;?>"></i>
							</button>
						</p>
					</td>
				</tr>
				<tr class="file-penugasan_<?php echo $bp->KD_BK;?>" style="display:none">
					<td></td>
					<td colspan="8">
						<div class="label-data">
							<table class="table table-bordered">
								<tr>
									<th width="175">Bukti Penugasan</td>
									<td><?php echo $bp->BKT_PENUGASAN;?></td>
									<td>
										<p class="btn-group">
											<button class="btn btn-mini <?php echo $classp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#1">
												<i class="icon-ok <?php echo $iconp;?> iconp_<?php echo $bp->KD_BK;?>"></i>
											</button>
											<button class="btn btn-mini <?php echo $classbp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#0">
												<i class="icon-remove <?php echo $iconbp;?> iconbp_<?php echo $bp->KD_BK;?>"></i>
											</button>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div class="doc-data">
						<?php 
							$fp = strlen($bp->FILE_PENUGASAN);
							if($fp > 1){
								# cek apakah file surat apa bukan
								if (substr($bp->FILE_PENUGASAN, 0,5) == 'surat'){
									$split = explode(':', $bp->FILE_PENUGASAN);
									$id_surat = $split[1]; ?>
									
									<?php $kd_sistem = "21a4b81cbb4f"; ?>
									<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
										<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
										<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
										<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
									</form>
									
								<?php 	
								}else{
									if($bp->FILE_PENUGASAN !== ''){
										/*$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_PENUGASAN;
										echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';*/
										$url_fp = base_url().'bkd/dosen/bebankerja/getFileBlob/BKD_DOC_KINERJA/BLOB_PENUGASAN/'.$bp->KD_BK;
										echo '<iframe src="'.$url_fp.'" width="700" height="500" style="border: none;"></iframe>';
									}else{
										echo "<p align='center'>TIDAK ADA FILE PENUGASAN YANG DIUPLOAD</p>";
									}
								}
							}
						?>
						</div>
					</td>
				</tr>
				
				<!-- FILE CAPAIAN -->
				<tr class="file-capaian_<?php echo $bp->KD_BK;?>" style="display:none">
					<td></td>
					<td colspan="8">
						<div class="label-data">
							<table class="table table-bordered">
								<tr>
									<th width="175">Bukti Capaian</td>
									<td><?php echo $bp->BKT_DOKUMEN;?></td>
									<td>
										<p class="btn-group">
											<button class="btn btn-mini <?php echo $classc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#1">
												<i class="icon-ok <?php echo $iconc;?> iconc_<?php echo $bp->KD_BK;?>"></i>
											</button>
											<button class="btn btn-mini <?php echo $classbc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#0">
												<i class="icon-remove <?php echo $iconbc;?> iconbc_<?php echo $bp->KD_BK;?>"></i>
											</button>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div class="doc-data">
						<?php 
							$fp = strlen($bp->FILE_CAPAIAN);
							if($fp > 1){
								# cek apakah file surat apa bukan
								if (substr($bp->FILE_CAPAIAN, 0,5) == 'surat'){
									$split = explode(':', $bp->FILE_CAPAIAN);
									$id_surat = $split[1]; ?>
									
									<?php $kd_sistem = "21a4b81cbb4f"; ?>
									<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
										<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
										<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
										<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
									</form>
									
								<?php 	
								}else{
									if($bp->FILE_PENUGASAN !== ''){
										$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_CAPAIAN;
										echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';
									}else{
										echo "<p align='center'>TIDAK ADA FILE CAPAIAN YANG DIUPLOAD</p>";
									}
								}
							}
						?>
						</div>
					</td>
				</tr>
				<!-- javascript -->
				<script>
					$(function(){
						$('.fp_<?php echo $bp->KD_BK;?>').click(function(){
							$('.file-penugasan_<?php echo $bp->KD_BK;?>').toggle('fast');
						});
						$('.fc_<?php echo $bp->KD_BK;?>').click(function(){
							$('.file-capaian_<?php echo $bp->KD_BK;?>').toggle('fast');
						});
						$('.aksi_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.icon_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconb_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconb_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.icon_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});
						// penugasan
						$('.aksip_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.iconp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconbp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconbp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});
						// capaian
						$('.aksic_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.iconc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconbc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconbc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});

					});
				</script>
				
				
			<?php } ?>
			<tr class="total">
				<th></th>
				<th colspan="2">Jumlah Beban Kerja</th>
				<th align="center"><?php echo $sks_beban; ?></th>
				<th colspan="2">Jumlah Kinerja</th>
				<th align="center"><?php echo $sks_bukti; ?></th>
				<th colspan="4"></th>
			</tr>
		<?php }  ?>
	</table>	
	
	<h3>Bidang Penunjang Lain</h3>
	<table border="0" cellspacing="0" class="table table-bordered table-hover" width="2000">
		<tr>
			<th rowspan="2">No.</th>
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
			if (empty($penunjang)){
				echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN...</td></tr>';
			}else{
				$no=0; $sks_beban = 0; $sks_bukti = 0;
				foreach ($penunjang as $bp){ $no++; 
					$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
					$sks_bukti = $sks_bukti+$bp->SKS_BKT;
					if($bp->STATUS == 1){$class = 'btn-success'; $classb = ''; $icon = 'icon-white'; $iconb = '';}
					else if($bp->STATUS == 0){ $class = ''; $classb = 'btn-danger'; $icon = ''; $iconb = 'icon-white';}
					else {$class = ''; $classb = ''; $icon = ''; $iconb = '';}
					# STATUS PENUGASAN
					if($bp->STATUS_PENUGASAN == 1){$classp = 'btn-success'; $classbp = ''; $iconp = 'icon-white'; $iconbp = '';}
					else if($bp->STATUS_PENUGASAN == 0){ $classp = ''; $classbp = 'btn-danger'; $iconp = ''; $iconbp = 'icon-white';}
					else {$classp = ''; $classbp = ''; $iconp = ''; $iconbp = '';}
					# STATUS CAPAIAN
					if($bp->STATUS_CAPAIAN == 1){$classc = 'btn-success'; $classbc = ''; $iconc = 'icon-white'; $iconbc = '';}
					else if($bp->STATUS_CAPAIAN == 0){ $classc = ''; $classbc = 'btn-danger'; $iconc = ''; $iconbc = 'icon-white';}
					else {$classc = ''; $classbc = ''; $iconc = ''; $iconbc = '';}
				?>
				<tr class ="<?php echo $bp->KD_BK;?>">
					<td align="center"><?php echo $no;?>.</td>
					<td><?php echo $bp->JENIS_KEGIATAN;?></a></td>
					<td class="fp_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_PENUGASAN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
					<td><?php echo $bp->MASA_PENUGASAN;?></td>
					<td class="fc_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_DOKUMEN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_BKT;?></td>
					<td><?php echo $bp->REKOMENDASI;?></td>
					<td align="center">
						<p class="btn-group">
							<button class="btn btn-mini <?php echo $class; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#1">
								<i class="icon-ok <?php echo $icon;?> icon_<?php echo $bp->KD_BK;?>"></i>
							</button>
							<button class="btn btn-mini <?php echo $classb; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#0">
								<i class="icon-remove <?php echo $iconb;?> iconb_<?php echo $bp->KD_BK;?>"></i>
							</button>
						</p>
					</td>
				</tr>
				<tr class="file-penugasan_<?php echo $bp->KD_BK;?>" style="display:none">
					<td></td>
					<td colspan="8">
						<div class="label-data">
							<table class="table table-bordered">
								<tr>
									<th width="175">Bukti Penugasan</td>
									<td><?php echo $bp->BKT_PENUGASAN;?></td>
									<td>
										<p class="btn-group">
											<button class="btn btn-mini <?php echo $classp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#1">
												<i class="icon-ok <?php echo $iconp;?> iconp_<?php echo $bp->KD_BK;?>"></i>
											</button>
											<button class="btn btn-mini <?php echo $classbp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#0">
												<i class="icon-remove <?php echo $iconbp;?> iconbp_<?php echo $bp->KD_BK;?>"></i>
											</button>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div class="doc-data">
						<?php 
							$fp = strlen($bp->FILE_PENUGASAN);
							if($fp > 1){
								# cek apakah file surat apa bukan
								if (substr($bp->FILE_PENUGASAN, 0,5) == 'surat'){
									$split = explode(':', $bp->FILE_PENUGASAN);
									$id_surat = $split[1]; ?>
									
									<?php $kd_sistem = "21a4b81cbb4f"; ?>
									<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
										<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
										<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
										<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
									</form>
									
								<?php 	
								}else{
									if($bp->FILE_PENUGASAN !== ''){
										/*$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_PENUGASAN;
										echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';*/
										$url_fp = base_url().'bkd/dosen/bebankerja/getFileBlob/BKD_DOC_KINERJA/BLOB_PENUGASAN/'.$bp->KD_BK;
										echo '<iframe src="'.$url_fp.'" width="700" height="500" style="border: none;"></iframe>';
									}else{
										echo "<p align='center'>TIDAK ADA FILE PENUGASAN YANG DIUPLOAD</p>";
									}
								}
							}
						?>
						</div>
					</td>
				</tr>
				
				<!-- FILE CAPAIAN -->
				<tr class="file-capaian_<?php echo $bp->KD_BK;?>" style="display:none">
					<td></td>
					<td colspan="8">
						<div class="label-data">
							<table class="table table-bordered">
								<tr>
									<th width="175">Bukti Capaian</td>
									<td><?php echo $bp->BKT_DOKUMEN;?></td>
									<td>
										<p class="btn-group">
											<button class="btn btn-mini <?php echo $classc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#1">
												<i class="icon-ok <?php echo $iconc;?> iconc_<?php echo $bp->KD_BK;?>"></i>
											</button>
											<button class="btn btn-mini <?php echo $classbc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#0">
												<i class="icon-remove <?php echo $iconbc;?> iconbc_<?php echo $bp->KD_BK;?>"></i>
											</button>
										</p>
									</td>
								</tr>
							</table>
						</div>
						<div class="doc-data">
						<?php 
							$fp = strlen($bp->FILE_CAPAIAN);
							if($fp > 1){
								# cek apakah file surat apa bukan
								if (substr($bp->FILE_CAPAIAN, 0,5) == 'surat'){
									$split = explode(':', $bp->FILE_CAPAIAN);
									$id_surat = $split[1]; ?>
									
									<?php $kd_sistem = "21a4b81cbb4f"; ?>
									<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
										<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
										<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
										<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
									</form>
									
								<?php 	
								}else{
									if($bp->FILE_PENUGASAN !== ''){
										$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_CAPAIAN;
										echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';
									}else{
										echo "<p align='center'>TIDAK ADA FILE CAPAIAN YANG DIUPLOAD</p>";
									}
								}
							}
						?>
						</div>
					</td>
				</tr>
				<!-- javascript -->
				<script>
					$(function(){
						$('.fp_<?php echo $bp->KD_BK;?>').click(function(){
							$('.file-penugasan_<?php echo $bp->KD_BK;?>').toggle('fast');
						});
						$('.fc_<?php echo $bp->KD_BK;?>').click(function(){
							$('.file-capaian_<?php echo $bp->KD_BK;?>').toggle('fast');
						});
						$('.aksi_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.icon_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconb_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconb_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.icon_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});
						// penugasan
						$('.aksip_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.iconp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconbp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconbp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});
						// capaian
						$('.aksic_<?php echo $bp->KD_BK;?>').click(function(){
							var isi = this.dataset.isi;
							var self = this;
							// pecah isinya
							var text = isi.split('#');
							if(text[2] == 1){
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-success');
										$('.iconc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconbc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}else{
								$.ajax({
								   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
								   type: "POST",
								   data: {data : isi},
								   dataType : 'html',
								   success: function(result){
										$(self).addClass('btn-danger');
										$('.iconbc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
										$('.iconc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
										$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
										console.log('<?php echo $bp->KD_BK;?>');
								   },
								   error: function(){
									   alert("Something went wrong");
								   }
								});
							}
						});

					});
				</script>
				
				
			<?php } ?>
			<tr class="total">
				<th></th>
				<th colspan="2">Jumlah Beban Kerja</th>
				<th align="center"><?php echo $sks_beban; ?></th>
				<th colspan="2">Jumlah Kinerja</th>
				<th align="center"><?php echo $sks_bukti; ?></th>
				<th colspan="4"></th>
			</tr>
		<?php }  ?>
	</table>	

	
	<?php if($status == 'PT' || $status == 'PR'){?>
		<h3>Kewajiban Khusus Professor</h3>
		<table border="0" cellspacing="0" class="table table-bordered table-hover" width="2000">
			<tr>
				<th rowspan="2">No.</th>
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
				if (empty($professor)){
					echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN...</td></tr>';
				}else{
					$no=0; $sks_beban = 0; $sks_bukti = 0;
					foreach ($professor as $bp){ $no++; 
						$sks_beban = $sks_beban+$bp->SKS_PENUGASAN;
						$sks_bukti = $sks_bukti+$bp->SKS_BKT;
						if($bp->STATUS == 1){$class = 'btn-success'; $classb = ''; $icon = 'icon-white'; $iconb = '';}
						else if($bp->STATUS == 0){ $class = ''; $classb = 'btn-danger'; $icon = ''; $iconb = 'icon-white';}
						else {$class = ''; $classb = ''; $icon = ''; $iconb = '';}
						# STATUS PENUGASAN
						if($bp->STATUS_PENUGASAN == 1){$classp = 'btn-success'; $classbp = ''; $iconp = 'icon-white'; $iconbp = '';}
						else if($bp->STATUS_PENUGASAN == 0){ $classp = ''; $classbp = 'btn-danger'; $iconp = ''; $iconbp = 'icon-white';}
						else {$classp = ''; $classbp = ''; $iconp = ''; $iconbp = '';}
						# STATUS CAPAIAN
						if($bp->STATUS_CAPAIAN == 1){$classc = 'btn-success'; $classbc = ''; $iconc = 'icon-white'; $iconbc = '';}
						else if($bp->STATUS_CAPAIAN == 0){ $classc = ''; $classbc = 'btn-danger'; $iconc = ''; $iconbc = 'icon-white';}
						else {$classc = ''; $classbc = ''; $iconc = ''; $iconbc = '';}
					?>
					<tr class ="<?php echo $bp->KD_BK;?>">
						<td align="center"><?php echo $no;?>.</td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></a></td>
						<td class="fp_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td class="fc_<?php echo $bp->KD_BK;?> bukti"><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo (float)$bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
						<td align="center">
							<p class="btn-group">
								<button class="btn btn-mini <?php echo $class; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#1">
									<i class="icon-ok <?php echo $icon;?> icon_<?php echo $bp->KD_BK;?>"></i>
								</button>
								<button class="btn btn-mini <?php echo $classb; ?> aksi_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS#0">
									<i class="icon-remove <?php echo $iconb;?> iconb_<?php echo $bp->KD_BK;?>"></i>
								</button>
							</p>
						</td>
					</tr>
					<tr class="file-penugasan_<?php echo $bp->KD_BK;?>" style="display:none">
						<td></td>
						<td colspan="8">
							<div class="label-data">
								<table class="table table-bordered">
									<tr>
										<th width="175">Bukti Penugasan</td>
										<td><?php echo $bp->BKT_PENUGASAN;?></td>
										<td>
											<p class="btn-group">
												<button class="btn btn-mini <?php echo $classp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#1">
													<i class="icon-ok <?php echo $iconp;?> iconp_<?php echo $bp->KD_BK;?>"></i>
												</button>
												<button class="btn btn-mini <?php echo $classbp; ?> aksip_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_PENUGASAN#0">
													<i class="icon-remove <?php echo $iconbp;?> iconbp_<?php echo $bp->KD_BK;?>"></i>
												</button>
											</p>
										</td>
									</tr>
								</table>
							</div>
							<div class="doc-data">
							<?php 
								$fp = strlen($bp->FILE_PENUGASAN);
								if($fp > 1){
									# cek apakah file surat apa bukan
									if (substr($bp->FILE_PENUGASAN, 0,5) == 'surat'){
										$split = explode(':', $bp->FILE_PENUGASAN);
										$id_surat = $split[1]; ?>
										
										<?php $kd_sistem = "21a4b81cbb4f"; ?>
										<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
											<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
											<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
											<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
										</form>
										
									<?php 	
									}else{
										if($bp->FILE_PENUGASAN !== ''){
											/*$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_PENUGASAN;
											echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';*/
											$url_fp = base_url().'bkd/dosen/bebankerja/getFileBlob/BKD_DOC_KINERJA/BLOB_PENUGASAN/'.$bp->KD_BK;
										echo '<iframe src="'.$url_fp.'" width="700" height="500" style="border: none;"></iframe>';
										}else{
											echo "<p align='center'>TIDAK ADA FILE PENUGASAN YANG DIUPLOAD</p>";
										}
									}
								}
							?>
							</div>
						</td>
					</tr>
					
					<!-- FILE CAPAIAN -->
					<tr class="file-capaian_<?php echo $bp->KD_BK;?>" style="display:none">
						<td></td>
						<td colspan="8">
							<div class="label-data">
								<table class="table table-bordered">
									<tr>
										<th width="175">Bukti Capaian</td>
										<td><?php echo $bp->BKT_DOKUMEN;?></td>
										<td>
											<p class="btn-group">
												<button class="btn btn-mini <?php echo $classc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#1">
													<i class="icon-ok <?php echo $iconc;?> iconc_<?php echo $bp->KD_BK;?>"></i>
												</button>
												<button class="btn btn-mini <?php echo $classbc; ?> aksic_<?php echo $bp->KD_BK;?>" data-isi="<?php echo $bp->KD_BK;?>#STATUS_CAPAIAN#0">
													<i class="icon-remove <?php echo $iconbc;?> iconbc_<?php echo $bp->KD_BK;?>"></i>
												</button>
											</p>
										</td>
									</tr>
								</table>
							</div>
							<div class="doc-data">
							<?php 
								$fp = strlen($bp->FILE_CAPAIAN);
								if($fp > 1){
									# cek apakah file surat apa bukan
									if (substr($bp->FILE_CAPAIAN, 0,5) == 'surat'){
										$split = explode(':', $bp->FILE_CAPAIAN);
										$id_surat = $split[1]; ?>
										
										<?php $kd_sistem = "21a4b81cbb4f"; ?>
										<form action="http://surat.uin-suka.ac.id/surat/keluar" method="POST" target="_BLANK">
											<input type="hidden" name="id_surat" value="<?php echo $id_surat;?>"/>
											<input type="hidden" name="kd_sistem" value="<?php echo $kd_sistem;?>"/>
											<input class="btn btn-uin btn-inverse" type="submit" name="Lihat" title="Lihat" value="Lihat Dokumen Surat"/>
										</form>
										
									<?php 	
									}else{
										if($bp->FILE_PENUGASAN !== ''){
											$url_fp = 'http://docs.google.com/viewer?url='.$bp->FILE_CAPAIAN;
											echo '<iframe src="'.$url_fp.'&embedded=true" width="700" height="500" style="border: none;"></iframe>';
										}else{
											echo "<p align='center'>TIDAK ADA FILE CAPAIAN YANG DIUPLOAD</p>";
										}
									}
								}
							?>
							</div>
						</td>
					</tr>
					<!-- javascript -->
					<script>
						$(function(){
							$('.fp_<?php echo $bp->KD_BK;?>').click(function(){
								$('.file-penugasan_<?php echo $bp->KD_BK;?>').toggle('fast');
							});
							$('.fc_<?php echo $bp->KD_BK;?>').click(function(){
								$('.file-capaian_<?php echo $bp->KD_BK;?>').toggle('fast');
							});
							$('.aksi_<?php echo $bp->KD_BK;?>').click(function(){
								var isi = this.dataset.isi;
								var self = this;
								// pecah isinya
								var text = isi.split('#');
								if(text[2] == 1){
									$.ajax({
									   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
									   type: "POST",
									   data: {data : isi},
									   dataType : 'html',
									   success: function(result){
											$(self).addClass('btn-success');
											$('.icon_<?php echo $bp->KD_BK;?>').addClass('icon-white');
											$('.iconb_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
											$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
									   },
									   error: function(){
										   alert("Something went wrong");
									   }
									});
								}else{
									$.ajax({
									   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
									   type: "POST",
									   data: {data : isi},
									   dataType : 'html',
									   success: function(result){
											$(self).addClass('btn-danger');
											$('.iconb_<?php echo $bp->KD_BK;?>').addClass('icon-white');
											$('.icon_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
											$('.aksi_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
											console.log('<?php echo $bp->KD_BK;?>');
									   },
									   error: function(){
										   alert("Something went wrong");
									   }
									});
								}
							});
							// penugasan
							$('.aksip_<?php echo $bp->KD_BK;?>').click(function(){
								var isi = this.dataset.isi;
								var self = this;
								// pecah isinya
								var text = isi.split('#');
								if(text[2] == 1){
									$.ajax({
									   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
									   type: "POST",
									   data: {data : isi},
									   dataType : 'html',
									   success: function(result){
											$(self).addClass('btn-success');
											$('.iconp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
											$('.iconbp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
											$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
									   },
									   error: function(){
										   alert("Something went wrong");
									   }
									});
								}else{
									$.ajax({
									   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
									   type: "POST",
									   data: {data : isi},
									   dataType : 'html',
									   success: function(result){
											$(self).addClass('btn-danger');
											$('.iconbp_<?php echo $bp->KD_BK;?>').addClass('icon-white');
											$('.iconp_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
											$('.aksip_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
											console.log('<?php echo $bp->KD_BK;?>');
									   },
									   error: function(){
										   alert("Something went wrong");
									   }
									});
								}
							});
							// capaian
							$('.aksic_<?php echo $bp->KD_BK;?>').click(function(){
								var isi = this.dataset.isi;
								var self = this;
								// pecah isinya
								var text = isi.split('#');
								if(text[2] == 1){
									$.ajax({
									   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
									   type: "POST",
									   data: {data : isi},
									   dataType : 'html',
									   success: function(result){
											$(self).addClass('btn-success');
											$('.iconc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
											$('.iconbc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
											$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-danger');
									   },
									   error: function(){
										   alert("Something went wrong");
									   }
									});
								}else{
									$.ajax({
									   url: "<?php echo base_url().'bkd/verifikator/kinerja/update_status_bk';?>",
									   type: "POST",
									   data: {data : isi},
									   dataType : 'html',
									   success: function(result){
											$(self).addClass('btn-danger');
											$('.iconbc_<?php echo $bp->KD_BK;?>').addClass('icon-white');
											$('.iconc_<?php echo $bp->KD_BK;?>').removeClass('icon-white');
											$('.aksic_<?php echo $bp->KD_BK;?>').removeClass('btn-success');
											console.log('<?php echo $bp->KD_BK;?>');
									   },
									   error: function(){
										   alert("Something went wrong");
									   }
									});
								}
							});

						});
					</script>
					
					
				<?php } ?>
				<tr class="total">
					<th></th>
					<th colspan="2">Jumlah Beban Kerja</th>
					<th align="center"><?php echo $sks_beban; ?></th>
					<th colspan="2">Jumlah Kinerja</th>
					<th align="center"><?php echo $sks_bukti; ?></th>
					<th colspan="4"></th>
				</tr>
			<?php }  ?>
		</table>	

	<?php } ?>
</div>
</div>