<script type="text/javascript">
	$(document).ready(function(){
		$('#ganjil').click(function(){
			$('.detail-ganjil').slideToggle('normal');
		});
		$('#genap').click(function(){
			$('.detail-genap').slideToggle('normal');
		});

		$('.print').click(function(){
			$('#bg').fadeIn('fast');
			$('#loading').fadeIn('fast');
		});
	});
</script>
<style>
	#bg{ 
		background-color:#555; 
		opacity:0.5;
		position:fixed;
		width:100%; height:100%;
		top:0; left:0;
		z-index:5;
		display:none;
	}
	#loading{
		position:fixed;
		width:20%;
		left:40%;
		top:20%;
		font-weight:bold;
		text-align:center;
		display:none;
		background-color:#FFF;
		border:solid 1px #AAA;
		padding:30px;
		z-index:10;
	}
</style>
<?php $this->load->view('fungsi');?>
<div id="bg"></div>
<div id="loading">Harap Menunggu...</div>
<div id="content">
<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Kompilasi</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi';?>">Kompilasi Tahunan</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/detail/'.$this->uri->segment(5);?>">Detail Beban Kerja Dosen</a></li>
	</ul>

	<h2>Detail Beban Kerja Dosen</h2>
	<h3>Tahun Akademik <?php echo $this->session->userdata("ta");?></h3>
	<br/>
	<div id="biodata">
		<h3>I. Identitas Dosen</h3>
		<table class="table table-condensed table-hover">
			<tr>
				<td width="200">Nomor Sertifikat</td>
				<td>: <?php echo $dosenBkd[0]->NO_SERTIFIKAT;?></td>
			</tr>
			<tr>
				<td>NIP</td>
				<td>: <?php echo _generate_nip($dosen[0]->KD_PGW);?></td>
			</tr>
			<tr>
				<td>NIDN</td>
				<td>: <?php echo $dosenSia[0]->NIDN;?></td>
			</tr>
			<tr>
				<td>Nama Lengkap</td>
				<td>: <?php echo $dosen[0]->NM_PGW_F;?></td>
			</tr>

			<tr>
				<td>Fakultas/Departemen</td>
				<td>: <?php echo $namaFakultas;?></td>
			</tr>
			<tr>
				<td>Program Studi</td>
				<td>: <?php echo $namaProdi;?></td>
			</tr>

		</table>
	</div>
	<br/>
	<style>
		.total td{font-weight:bold; background:#FFFFDD;}
		#ganjil, #genap, #cetak{
			color:#555;
			padding:10px;
			background:linear-gradient(#FFF,#F7F7F7);
			font-weight:bold;
			border:solid 1px #DDD;
			cursor:pointer;
			border-top:solid 1px #FFF;
		}
		#ganjil:hover, #genap:hover{ background:#F7F7F7;}
		#ganjil{ border-top:solid 1px #DDD; }
		.detail-ganjil, .detail-genap{
			padding:10px;
			border:solid 1px #DDD;
		}
	</style>
	
	<div id="ganjil">Semester Ganjil <span class="close"><i class="icon icon-chevron-down"></i></span></div>
	<div id="cetak">
		<?php echo form_open('bkd/dosen/cetak');?>
			<input type="hidden" name="kd_dosen" value="<?php echo $dosenBkd[0]->KD_DOSEN;?>">
			<input type="hidden" name="jenis" value="lbkd">
			<input type="hidden" name="kd_prodi" value="<?php echo $dosenBkd[0]->KD_PRODI;?>">
			<input type="hidden" name="kd_fak" value="<?php echo $dosenBkd[0]->KD_FAK;?>">
			<input type="hidden" name="ta" value="<?php echo $this->session->userdata('ta');?>">
			<input type="hidden" name="smt" value="GANJIL">
			<button class="btn print"><i class="icon-print"></i></button>
		<?php echo form_close();?>
	</div>
	<!-- tampilkan beban kerja pendidikan yang telah diambil -->
	<div class="detail-ganjil">
		<?php 
			if (empty($pd)){
				echo "";
			}else{ ?>
		<h3>II. Bidang Pendidikan dan Pengajaran</h3>
		<table class = "table table-condensed table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 	$no=0; $jb = 0; $jk = 0;
					foreach ($pd as $bp){ $no++; 
						$jb = $jb+$bp->SKS_PENUGASAN;
						$jk = $jk+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
					</tr>
				<?php } ?>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $jb; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $jk;?></td>
					<td></td>
				</tr>
			<?php } ?>
		</table>	
		
		<!-- tampilkan beban kerja penelitian yang telah diambil -->
			<?php 
				if (empty($pl)){
					echo "";
				}else{ ?>
		<h3>III. Bidang Penelitian</h3>
		<table class = "table table-condensed table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 	$no=0; $jb = 0; $jk = 0;
					foreach ($pl as $bp){ $no++; 
						$jb = $jb+$bp->SKS_PENUGASAN;
						$jk = $jk+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
					</tr>
				<?php } ?>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $jb; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $jk;?></td>
					<td></td>
				</tr>
			<?php } ?>
		</table>	

		<!-- tampilkan beban kerja pengabdian masyarakat yang telah diambil -->
			<?php 
				if (empty($pk)){
					echo "";
				}else{ ?>
		<h3>IV. Bidang Pengabdian Kepada Masyarakat</h3>
		<table class = "table table-condensed table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 	$no=0; $jb = 0; $jk = 0;
					foreach ($pk as $bp){ $no++; 
						$jb = $jb+$bp->SKS_PENUGASAN;
						$jk = $jk+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
					</tr>
				<?php } ?>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $jb; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $jk;?></td>
					<td></td>
				</tr>
			<?php } ?>
		</table>	

		<!-- tampilkan beban kerja penunjang yang telah diambil -->
			<?php 
				if (empty($pg)){
					echo "";
				}else{ ?>
		<h3>V. Bidang Penunjang Tridharma Perguruan Tinggi</h3>
		<table class = "table table-condensed table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 	$no=0; $jb = 0; $jk = 0;
					foreach ($pg as $bp){ $no++;
						$jb = $jb+$bp->SKS_PENUGASAN;
						$jk = $jk+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
					</tr>
				<?php } ?>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $jb; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $jk;?></td>
					<td></td>
				</tr>
		</table>
		<?php } ?>
	</div>

	
	<div id="genap">Semester Genap<span class="close"><i class="icon icon-chevron-down"></i></span></div>
	<div id="cetak">
		<?php echo form_open('bkd/dosen/cetak');?>
			<input type="hidden" name="kd_dosen" value="<?php echo $dosenBkd[0]->KD_DOSEN;?>">
			<input type="hidden" name="jenis" value="lbkd">
			<input type="hidden" name="kd_prodi" value="<?php echo $dosenBkd[0]->KD_PRODI;?>">
			<input type="hidden" name="kd_fak" value="<?php echo $dosenBkd[0]->KD_FAK;?>">
			<input type="hidden" name="ta" value="<?php echo $this->session->userdata('ta');?>">
			<input type="hidden" name="smt" value="GENAP">
			<button class="btn print"><i class="icon-print"></i></button>
		<?php echo form_close();?>
	</div>
	<div class="detail-genap">
		<!-- tampilkan beban kerja pendidikan yang telah diambil -->
			<?php 
				if (empty($pd2)){
					echo "";
				}else{ ?>
		<h3>II. Bidang Pendidikan dan Pengajaran</h3>
		<table class = "table table-condensed table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 	$no=0; $jb = 0; $jk = 0;
					foreach ($pd2 as $bp){ $no++; 
						$jb = $jb+$bp->SKS_PENUGASAN;
						$jk = $jk+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
					</tr>
				<?php } ?>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $jb; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $jk;?></td>
					<td></td>
				</tr>
			<?php } ?>
		</table>	
		
		<!-- tampilkan beban kerja penelitian yang telah diambil -->
			<?php 
				if (empty($pl2)){
					echo "";
				}else{ ?>
		<h3>III. Bidang Penelitian</h3>
		<table class = "table table-condensed table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 	$no=0; $jb = 0; $jk = 0;
					foreach ($pl2 as $bp){ $no++; 
						$jb = $jb+$bp->SKS_PENUGASAN;
						$jk = $jk+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
					</tr>
				<?php } ?>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $jb; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $jk;?></td>
					<td></td>
				</tr>
			<?php } ?>
		</table>	

		<!-- tampilkan beban kerja pengabdian masyarakat yang telah diambil -->
			<?php 
				if (empty($pk2)){
					echo "";
				}else{ ?>
		<h3>IV. Bidang Pengabdian Kepada Masyarakat</h3>
		<table class = "table table-condensed table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 	$no=0; $jb = 0; $jk = 0;
					foreach ($pk2 as $bp){ $no++; 
						$jb = $jb+$bp->SKS_PENUGASAN;
						$jk = $jk+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
					</tr>
				<?php } ?>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $jb; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $jk;?></td>
					<td></td>
				</tr>
			<?php } ?>
		</table>	

		<!-- tampilkan beban kerja penunjang yang telah diambil -->
			<?php 
				if (empty($pg2)){
					echo "";
				}else{ ?>
		<h3>V. Bidang Penunjang Tridharma Perguruan Tinggi</h3>
		<table class = "table table-condensed table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
			<?php 	$no=0; $jb = 0; $jk = 0;
					foreach ($pg2	 as $bp){ $no++;
						$jb = $jb+$bp->SKS_PENUGASAN;
						$jk = $jk+$bp->SKS_BKT;
					?>
					<tr>
						<td><?php echo $no;?></td>
						<td><?php echo $bp->JENIS_KEGIATAN;?></td>
						<td><?php echo $bp->BKT_PENUGASAN;?></td>
						<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
						<td><?php echo $bp->MASA_PENUGASAN;?></td>
						<td><?php echo $bp->BKT_DOKUMEN;?></td>
						<td align="center"><?php echo $bp->SKS_BKT;?></td>
						<td><?php echo $bp->REKOMENDASI;?></td>
					</tr>
				<?php } ?>
				<tr class="total">
					<td></td>
					<td colspan="2">Jumlah Beban Kerja</td>
					<td align="center"><?php echo $jb; ?></td>
					<td colspan="2">Jumlah Kinerja</td>
					<td align="center"><?php echo $jk;?></td>
					<td></td>
				</tr>
		</table>
		<?php } ?>
	</div>

	
	
	<!-- tampilkan beban kerja khusus profesor yang telah diambil -->
	<?php
		if ($this->session->userdata("jenis_dosen") == "PR" || $this->session->userdata("jenis_dosen") == "PT"){
			if (empty($dbp)){
				echo "";
			}else{ ?>
	<h3>VI. Kewajiban Khusus Profesor</h3>
	<table class = "table table-condensed table-hover">
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">Jenis Kegiatan</th>
			<th colspan="2">Beban Kerja</th>
			<th rowspan="2">Masa Penugasan</th>
			<th colspan="2">Kinerja</th>
			<th rowspan="2">Rekomendasi</th>
		</tr>
		<tr>
			<th>Bukti Penugasan</th>
			<th>SKS</th>
			<th>Bukti Dokumen</th>
			<th>SKS</th>
		</tr>
		<?php 	$no=0; $jb = 0; $jk = 0;
				foreach ($dbp as $bp){ $no++;
					$jb = $jb+$bp->SKS_PENUGASAN;
					$jk = $jk+$bp->SKS_BKT;
				?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $bp->JENIS_KEGIATAN;?></td>
					<td><?php echo $bp->BKT_PENUGASAN;?></td>
					<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
					<td><?php echo $bp->MASA_PENUGASAN;?></td>
					<td><?php echo $bp->BKT_DOKUMEN;?></td>
					<td align="center"><?php echo $bp->SKS_BKT;?></td>
					<td><?php echo $bp->REKOMENDASI;?></td>
				</tr>
			<?php } ?>
			<tr class="total">
				<td></td>
				<td colspan="2">Jumlah Beban Kerja</td>
				<td align="center"><?php echo $jb; ?></td>
				<td colspan="2">Jumlah Kinerja</td>
				<td align="center"><?php echo $jk;?></td>
				<td></td>
			</tr>
	</table>
	<?php } 
	} ?>
</div>
</div>