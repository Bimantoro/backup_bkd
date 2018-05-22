<!DOCTYPE html>
<html>
<head>
	<title>Laporan Remunerasi Dosen</title>
	<style>
		.centered{ text-align:center; line-height:0.40px; font-size:9px; font-weight:bold;}
		body, #cover table{
			font-size:6px;
		}
		.list, table{font-size:5px;}
		.list th{ border-bottom: solid 1px #555; border-top: solid 1px #555; font-weight:bold; text-align:center;}
		.list td{ border-bottom: solid 1px #555;}
		.total{ font-weight:bold;}
	</style>
</head>
<body>
<style type="text/css">
    table { page-break-inside:auto; }
    tr    { page-break-inside:avoid; page-break-after:auto; }
    th { display:table-header-group; }
    td { display:table-footer-group; }
    p {text-align: justify;}
    span {text-align: justify;}
</style>
<?php $this->load->view('fungsi');?>
<?php if(!empty($dosen)){
		foreach ($dosen as $data);?>
		<?php foreach ($universitas as $pt);?>
	
<div id="print-area">
	<div id="cover">
		<div class="centered"><img src="<?php echo base_url().'asset/img/logo-ptain.jpg';?>" width="60"></div>
		<div class="centered">LAPORAN REMUNERASI DOSEN</div>
		<br/><br/><br/>
		<!-- BIODATA COVER -->
		<table border="0">
			<tr>
				<td width="20%"></td>
				<td width="80%">
					<table border="0" width="100%" cellpadding="2">
					<tr><td width="80">NAMA</td><td>: <?php echo strtoupper($data->NM_PGW_F);?></td></tr>
					<tr><td>NOMOR SERTIFIKAT</td><td>: <?php echo $dosenBkd[0]->NO_SERTIFIKAT;?></td></tr>
					<tr><td>FAKULTAS</td><td>: <?php echo strtoupper($namaFakultas);?></td></tr>
					<tr><td>PERGURUAN TINGGI</td><td>: <?php echo strtoupper($pt->PTN_NM_PT_J);?></td></tr>
					<tr><td>SEMESTER</td><td>: <?php echo $r_smt.' - '.$r_ta;?></td></tr>
					</table>
				</td>
			</tr>
		</table>
		<br/><br/><br/>
		<div class="centered">KEMENTERIAN AGAMA</div>
		<div class="centered">REPUBLIK INDONESIA</div>
	</div>
	<br/><br/><br/>
	<!-- SECTION --> 
	<div id="biodata">
		<h3>I. IDENTITAS DOSEN</h3>
		<table border="0" width="80%">
			<tr>
				<td width="100">Nomor Sertifikat</td>
				<td>: <?php echo $dosenBkd[0]->NO_SERTIFIKAT;?></td></tr>
			<tr>
				<td>NIP</td>
				<td>: <?php echo _generate_nip($dosenBkd[0]->KD_DOSEN);?></td>
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
				<td>Perguruan Tinggi</td>
				<td>: <?php echo ucwords(strtolower($pt->PTN_NM_PT));?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td>: <?php echo "(".$jenis_dosen.") ".ucwords(strtolower($nama_jenis_dosen));?></td>
			</tr>
			<tr>
				<td>Alamat Perguruan Tinggi</td>
				<td>: <?php echo ucwords(strtolower($pt->PTN_ALAMAT));?></td>
			</tr>
			<tr>
				<td>Fakultas</td>
				<td>: <?php echo ucwords(strtolower($namaFakultas));?></td>
			</tr>
			<tr>
				<td>Jurusan/Departemen</td>
				<td>: <?php echo ucwords(strtolower($namaProdi));?></td>
			</tr>
			<tr>
				<td>Program Studi</td>
				<td>: <?php echo ucwords(strtolower($namaProdi));?></td>
			</tr>
			<tr>
				<td>Jabatan Fungsional/Gol</td>
				<td>: <?php echo $fungsional_dosen." - ".$golongan_dosen.'/'.$ruang_dosen;?></td>
			</tr>
			<tr>
				<td>Tempat dan Tanggal Lahir</td>
				<td>: <?php echo ucwords(strtolower($dosen[0]->TMP_LAHIR)).", ".date('d/m/Y', strtotime($dosen[0]->TGL_LAHIR));?></td>
			</tr>
			<tr>
				<td>S1</td>
				<td>: <?php echo $ps1;?></td>
			</tr>
			<tr>
				<td>S2</td>
				<td>: <?php echo $ps2;?></td>
			</tr>
			<tr>
				<td>S3</td>
				<td>: <?php echo $ps3;?></td>
			</tr>
			<tr>
				<td>Ilmu yang ditekuni</td>
				<td>: <?php echo ucwords(strtolower($dosenBkd[0]->BIDANG_ILMU));?></td>
			</tr>
			<tr>
				<td>No HP</td>
				<td>: <?php echo ucwords(strtolower($dosen[0]->TELEPON_HP1));?></td>
			</tr>
			<tr>
				<td>Email</td>
				<td>: <?php echo strtolower($dosen[0]->EMAIL);?></td>
			</tr>
		</table>
	</div>
	<!-- tampilkan beban kerja pendidikan yang telah diambil -->
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<br>
		<?php 
			if (empty($pd)){
				echo "";
			}else{ ?>
			
					<?php
		//cek ada data terlebih dahulu, ada atau tidak
			if(!empty($summary)){?>
				<!-- jika ada data -->
				<h3>II. REKAPITULASI REMUN DOSEN</h3>
				<table border="0" cellspacing="0" class="list tabel-pendidikan" cellpadding="1.5" width="100%" style="border-collapse: collapse;">
			<tr>
				<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" width="3%">No.</th>
				<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" width="10%">Jenis Remun</th>
				<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" width="24%">Kategori</th>
				<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" width="40%">Jenis Kegiatan</th>
				<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" width="5%">Nilai</th>
				<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" width="11%">Satuan</th>
				<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" width="7%">Poin SKR</th>
				<!-- <th style="border: 0.25px solid black; font-weight: bold;" width="10%">Jumlah Poin</th> -->
			</tr>


			<!-- DNG A BMTR -->

			<?php $nomor = 1; ?>
			 <?php foreach ($summary as $s): ?>
			 	<?php $jenis = 0; ?>
			 	<?php 
			 		$total = 0;
			 		foreach ($s as $t) {
			 			$total += count($t['JENIS_KEGIATAN']);
			 		}
			 	 ?>
			 	<?php foreach ($s as $a): ?>
			 		<?php $keg = 0; ?>
			 		<?php $total_data = count($a['JENIS_KEGIATAN']); ?>
			 		<?php if($jenis == 0){ $jenis++; ?>
			 		<tr>
			 			<td rowspan="<?php echo $total; ?>" style="border: 0.25px solid black; text-align: center;"><?php echo $nomor; $nomor++; ?>.</td>
			 			<td rowspan="<?php echo $total; ?>" style="border: 0.25px solid black;"><?php echo ucwords(strtolower($a['GROUP'])); ?></td>
			 			<td rowspan="<?php echo $total_data; ?>" style="border: 0.25px solid black;"><?php echo $a['JUDUL']; ?></td>
			 			<?php for ($i=0; $i < 1; $i++) { ?>
			 				<td style="border: 0.25px solid black;"><?php echo $a['JENIS_KEGIATAN'][$i]['JUDUL_KEGIATAN']; ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo $a['JENIS_KEGIATAN'][$i]['NILAI']; ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo ($a['JENIS_KEGIATAN'][$i]['SATUAN']=='SKS')?'SKS':ucwords(strtolower($a['JENIS_KEGIATAN'][$i]['SATUAN'])); ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo $a['JENIS_KEGIATAN'][$i]['POIN']; ?></td>
			 			<?php } ?>
			 			<?php $keg++; ?>			 			
			 		</tr>
			 		<?php }

			 		if($keg == 0){ ?>
			 		<tr>
			 			<td rowspan="<?php echo $total_data; ?>" style="border: 0.25px solid black;"><?php echo $a['JUDUL']; ?></td>
			 			<?php for ($i=0; $i < 1; $i++) { ?>
			 				<td style="border: 0.25px solid black;"><?php echo $a['JENIS_KEGIATAN'][$i]['JUDUL_KEGIATAN']; ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo $a['JENIS_KEGIATAN'][$i]['NILAI']; ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo ($a['JENIS_KEGIATAN'][$i]['SATUAN']=='SKS')?'SKS':ucwords(strtolower($a['JENIS_KEGIATAN'][$i]['SATUAN'])); ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo $a['JENIS_KEGIATAN'][$i]['POIN']; ?></td>
			 			<?php } ?>
			 			<?php $keg++; ?>			 			
			 		</tr>
			 		<?php } ?>

			 		<?php if($jenis != 0 && $keg != 0){ ?>
			 		
			 			<?php for ($i=1; $i < count($a['JENIS_KEGIATAN']); $i++) { ?>
			 			<tr>
			 				<td style="border: 0.25px solid black;"><?php echo $a['JENIS_KEGIATAN'][$i]['JUDUL_KEGIATAN']; ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo $a['JENIS_KEGIATAN'][$i]['NILAI']; ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo ($a['JENIS_KEGIATAN'][$i]['SATUAN']=='SKS')?'SKS':ucwords(strtolower($a['JENIS_KEGIATAN'][$i]['SATUAN'])); ?></td>
				 			<td style="border: 0.25px solid black; text-align: center;"><?php echo $a['JENIS_KEGIATAN'][$i]['POIN']; ?></td>
				 		</tr>
			 			<?php } ?>
			 			<?php $keg++; ?>			 			
			 		
			 		<?php } ?>
			 	<?php endforeach ?>
			 <?php endforeach ?>

			<!-- DNG A BMTR -->

			 <tr style="border: 0.25px solid black; background-color: #d6d6d6;">
				<td align="center" colspan="6" style="border: 0.25px solid black; font-weight: bold;">Jumlah Kinerja Selama Periode Penilaian</td>
				<td align="center" style="border: 0.25px solid black;"><?php echo $kesimpulan['JML_POIN']?></td>
			</tr>
			<tr style="border: 0.25px solid black; background-color: #d6d6d6;">
				<td align="center" colspan="6" style="border: 0.25px solid black; font-weight: bold;">Rata-rata Kinerja Per Bulan</td>
				<td align="center" style="border: 0.25px solid black;"><?php echo number_format($kesimpulan['RATA_POIN'],2);?></td>
			</tr>
			<tr style="border: 0.25px solid black; background-color: #d6d6d6;">
				<td align="center" colspan="6" style="border: 0.25px solid black; font-weight: bold;">Nilai Kinerja Akademik (Komponen SKR)</td>
				<td align="center" style="border: 0.25px solid black;"><?php echo $kesimpulan['NILAI_KINERJA']."%";?></td>
			</tr>
		</table>
			<?php }else{?>
				<!-- jika tidak ada data -->
				<?php echo "<h4>Belum ada data yang dapat ditampilkan!</h4>";?>
			<?php }
		?>	
		<?php } ?>
	
	<!-- tampilkan beban kerja penelitian yang telah diambil --><br/>
		

	<!-- tampilkan beban kerja pengabdian masyarakat yang telah diambil --><br/>
		

	<!-- tampilkan beban kerja penunjang yang telah diambil --><br/>
	
	
	<!-- tampilkan beban kerja khusus profesor yang telah diambil -->
	
	
<?php }	?>
</body>
</html>
