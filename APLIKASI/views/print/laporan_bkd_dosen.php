<!DOCTYPE html>
<html>
<head>
	<title>Laporan Kinerja Dosen</title>
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
    table { page-break-inside:auto }
    tr    { page-break-inside:avoid; page-break-after:auto }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
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
		<div class="centered">LAPORAN KINERJA DOSEN</div>
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
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<br>
	<!-- tampilkan beban kerja pendidikan yang telah diambil -->
		<?php 
			if (empty($pd)){
				echo "";
			}else{ ?>
				<H3>II. BIDANG PENDIDIKAN DAN PENGAJARAN</H3>
				<table border="0" cellspacing="0" class="list" cellpadding="1.5" width="100%">
					<tr>
						<th rowspan="3" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">No.</th>
						<th rowspan="3" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jenis Kegiatan</th>
						<th colspan="2" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Beban Kerja</th>
						<th rowspan="3" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Masa Pelaksanaan Tugas</th>
						<th colspan="3" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Kinerja</th>
						<th rowspan="3" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Penilaian/ Rekomendasi Asesor</th>
					</tr>
					<tr>
						<th rowspan="2" width="20%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Penugasan</th>
						<th rowspan="2" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
						<th rowspan="2" width="15%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Kinerja</th>
						<th colspan="2" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Capaian</th>
					</tr>
					<tr>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">%</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
					</tr>
					<?php 	$no=0; $sks_beban = 0; $sks_bukti = 0;
							foreach ($pd as $bp){ $no++; 
								/*$sks_beban = $sks_beban + $bp->SKS_PENUGASAN;
								$sks_bukti = $sks_bukti + $bp->SKS_BKT;*/
								$a = (float) str_replace(",", ".", $bp->SKS_PENUGASAN);
								$b =  (float) str_replace(",", ".", $bp->SKS_BKT);
								$sks_beban = $sks_beban+$a;
								$sks_bukti = $sks_bukti+$b;
								if($bp->SKS_PENUGASAN == 0) $capaian = 0; else $capaian = ($bp->SKS_BKT/$bp->SKS_PENUGASAN)*100;
							?>
							<tr>
								<td style="border: 0.25px solid black;" align="center"><?php echo $no;?>.</td>
								<td style="border: 0.25px solid black;"><?php echo $bp->JENIS_KEGIATAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_PENUGASAN;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_PENUGASAN);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->MASA_PENUGASAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_DOKUMEN;?></td>
								<td style="border: 0.25px solid black;" align="center"><?php echo $capaian;?></td>
								<td align="center" style="border: 0.25px solid black; "><?php echo (float) str_replace(",", ".", $bp->SKS_BKT);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->REKOMENDASI;?></td>
							</tr>
						<?php } ?>
						<tr class="total">
							
							<td colspan="3" align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Beban Kerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_beban;?></td>
							<td align="center" colspan="3" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Kinerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_bukti;?></td>
							<td style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"></td>
						</tr>
					</table>	
		<?php } ?>
	
	<!-- tampilkan beban kerja penelitian yang telah diambil --><br/>
		<?php 
			if (empty($pl)){
				echo "";
			}else{ ?>
				<H3>III. BIDANG PENELITIAN</H3>
				<table border="0" cellspacing="0" class="list" cellpadding="2" width="100%">
					<tr>
						<th rowspan="3" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">No.</th>
						<th rowspan="3" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jenis Kegiatan</th>
						<th colspan="2" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Beban Kerja</th>
						<th rowspan="3" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Masa Pelaksanaan Tugas</th>
						<th colspan="3" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Kinerja</th>
						<th rowspan="3" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Penilaian/ Rekomendasi Asesor</th>
					</tr>
					<tr>
						<th rowspan="2" width="20%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Penugasan</th>
						<th rowspan="2" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
						<th rowspan="2" width="15%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Kinerja</th>
						<th colspan="2" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Capaian</th>
					</tr>
					<tr>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">%</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
					</tr>
					<?php 	$no=0; $sks_beban = 0; $sks_bukti = 0;
							foreach ($pl as $bp){ $no++; 
								/*$sks_beban = $sks_beban + $bp->SKS_PENUGASAN;
								$sks_bukti = $sks_bukti + $bp->SKS_BKT;*/
								$a = (float) str_replace(",", ".", $bp->SKS_PENUGASAN);
								$b =  (float) str_replace(",", ".", $bp->SKS_BKT);
								$sks_beban = $sks_beban+$a;
								$sks_bukti = $sks_bukti+$b;
								if($bp->SKS_PENUGASAN == 0) $capaian = 0; else $capaian = ($bp->SKS_BKT/$bp->SKS_PENUGASAN)*100;
							?>
							<tr>
								<td style="border: 0.25px solid black;" align="center"><?php echo $no;?>.</td>
								<td style="border: 0.25px solid black;"><?php echo $bp->JENIS_KEGIATAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_PENUGASAN;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_PENUGASAN);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->MASA_PENUGASAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_DOKUMEN;?></td>
								<td style="border: 0.25px solid black;" align="center"><?php echo $capaian;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_BKT);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->REKOMENDASI;?></td>
							</tr>
						<?php } ?>
						<tr class="total">
							<td colspan="3" align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Beban Kerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_beban;?></td>
							<td align="center" colspan="3" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Kinerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_bukti;?></td>
							<td style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"></td>
						</tr>
				</table>	
		<?php } ?>

	<!-- tampilkan beban kerja pengabdian masyarakat yang telah diambil --><br/>
		<?php 
			if (empty($pk)){
				echo "";
			}else{ ?>
				<H3>IV. BIDANG PENGABDIAN KEPADA MASYARAKAT</H3>
				<table border="0" cellspacing="0" class="list" cellpadding="2" width="100%">
					<tr>
						<th rowspan="3" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">No.</th>
						<th rowspan="3" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jenis Kegiatan</th>
						<th colspan="2" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Beban Kerja</th>
						<th rowspan="3" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Masa Pelaksanaan Tugas</th>
						<th colspan="3" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Kinerja</th>
						<th rowspan="3" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Penilaian/ Rekomendasi Asesor</th>
					</tr>
					<tr>
						<th rowspan="2" width="20%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Penugasan</th>
						<th rowspan="2" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
						<th rowspan="2" width="15%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Kinerja</th>
						<th colspan="2" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Capaian</th>
					</tr>
					<tr>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">%</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
					</tr>
					<?php 	$no=0; $sks_beban = 0; $sks_bukti = 0;
							foreach ($pk as $bp){ $no++; 
								/*$sks_beban = $sks_beban + $bp->SKS_PENUGASAN;
								$sks_bukti = $sks_bukti + $bp->SKS_BKT;*/
								$a = (float) str_replace(",", ".", $bp->SKS_PENUGASAN);
								$b =  (float) str_replace(",", ".", $bp->SKS_BKT);
								$sks_beban = $sks_beban+$a;
								$sks_bukti = $sks_bukti+$b;
								if($bp->SKS_PENUGASAN == 0) $capaian = 0; else $capaian = ($bp->SKS_BKT/$bp->SKS_PENUGASAN)*100;
							?>
							<tr>
								<td style="border: 0.25px solid black;" align="center"><?php echo $no;?>.</td>
								<td style="border: 0.25px solid black;"><?php echo $bp->JENIS_KEGIATAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_PENUGASAN;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_PENUGASAN);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->MASA_PENUGASAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_DOKUMEN;?></td>
								<td style="border: 0.25px solid black;" align="center"><?php echo $capaian;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_BKT);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->REKOMENDASI;?></td>
							</tr>
						<?php } ?>
						<tr class="total">
							<td colspan="3" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Beban Kerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_beban;?></td>
							<td align="center" colspan="3" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Kinerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_bukti;?></td>
							<td style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"></td>
						</tr>
				</table>	
		<?php } ?>

	<!-- tampilkan beban kerja penunjang yang telah diambil --><br/>
		<?php 
			if (empty($pg)){
				echo "";
			}else{ ?>
				<H3>V. BIDANG PENUNJANG TRIDHARMA PERGURUAN TINGGI</H3>
				<table border="0" cellspacing="0" class="list" cellpadding="2" width="100%">
					<tr>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" rowspan="3" width="5%">No.</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" rowspan="3" width="25%">Jenis Kegiatan</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" colspan="2" width="25%">Beban Kerja</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" rowspan="3" width="10%">Masa Pelaksanaan Tugas</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" colspan="3" width="25%">Kinerja</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;" rowspan="3" width="10%">Penilaian/ Rekomendasi Asesor</th>
					</tr>
					<tr>
						<th rowspan="2" width="20%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Penugasan</th>
						<th rowspan="2" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
						<th rowspan="2" width="15%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Kinerja</th>
						<th colspan="2" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Capaian</th>
					</tr>
					<tr>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">%</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
					</tr>
					<?php 	$no=0; $sks_beban = 0; $sks_bukti = 0;
							foreach ($pg as $bp){ $no++; 
								/*$sks_beban = $sks_beban + $bp->SKS_PENUGASAN;
								$sks_bukti = $sks_bukti + $bp->SKS_BKT;*/
								$a = (float) str_replace(",", ".", $bp->SKS_PENUGASAN);
								$b =  (float) str_replace(",", ".", $bp->SKS_BKT);
								$sks_beban = $sks_beban+$a;
								$sks_bukti = $sks_bukti+$b;
								if($bp->SKS_PENUGASAN == 0) $capaian = 0; else $capaian = ($bp->SKS_BKT/$bp->SKS_PENUGASAN)*100;
							?>
							<tr>
								<td style="border: 0.25px solid black;" align="center"><?php echo $no;?>.</td>
								<td style="border: 0.25px solid black;"><?php echo $bp->JENIS_KEGIATAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_PENUGASAN;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_PENUGASAN);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->MASA_PENUGASAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_DOKUMEN;?></td>
								<td style="border: 0.25px solid black;" align="center"><?php echo $capaian;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_BKT);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->REKOMENDASI;?></td>
							</tr>
						<?php } ?>
						<tr class="total">
							<td colspan="3" align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Beban Kerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_beban;?></td>
							<td align="center" colspan="3" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Kinerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_bukti;?></td>
							<td style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"></td>
						</tr>
				</table>
	<?php } ?>
	
	<!-- tampilkan beban kerja khusus profesor yang telah diambil -->
	<?php
		if ($jenis_dosen == "PR" || $jenis_dosen == "PT"){
			if (empty($dbp)){
				echo "";
			}else{ ?>
				<H3>VI. KEWAJIBAN KHUSUS PROFESOR</H3>
				<table border="0" cellspacing="0" class="list" cellpadding="2" width="100%">
					<tr>
						<th rowspan="3" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">No.</th>
						<th rowspan="3" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jenis Kegiatan</th>
						<th colspan="2" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Beban Kerja</th>
						<th rowspan="3" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Masa Pelaksanaan Tugas</th>
						<th colspan="3" width="25%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Kinerja</th>
						<th rowspan="3" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Penilaian/ Rekomendasi Asesor</th>
					</tr>
					<tr>
						<th rowspan="2" width="20%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Penugasan</th>
						<th rowspan="2" width="5%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
						<th rowspan="2" width="15%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Bukti Kinerja</th>
						<th colspan="2" width="10%" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Capaian</th>
					</tr>
					<tr>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">%</th>
						<th style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">SKS</th>
					</tr>
					<?php 	$no=0; $sks_bp = 0; $sks_kp = 0;
							foreach ($dbp as $bp){ $no++; 
								/*$sks_bp = $sks_bp + $bp->SKS_PENUGASAN;
								$sks_kp = $sks_kp + $bp->SKS_BKT;*/
								$a = (float) str_replace(",", ".", $bp->SKS_PENUGASAN);
								$b =  (float) str_replace(",", ".", $bp->SKS_BKT);
								$sks_beban = $sks_beban+$a;
								$sks_bukti = $sks_bukti+$b;
								if($bp->SKS_PENUGASAN == 0) $capaian = 0; else $capaian = ($bp->SKS_BKT/$bp->SKS_PENUGASAN)*100;
							?>
							<tr>
								<td style="border: 0.25px solid black;" align="center"><?php echo $no;?>.</td>
								<td style="border: 0.25px solid black;"><?php echo $bp->JENIS_KEGIATAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_PENUGASAN;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_PENUGASAN);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->MASA_PENUGASAN;?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->BKT_DOKUMEN;?></td>
								<td style="border: 0.25px solid black;" align="center"><?php echo $capaian;?></td>
								<td align="center" style="border: 0.25px solid black;"><?php echo (float) str_replace(",", ".", $bp->SKS_BKT);?></td>
								<td style="border: 0.25px solid black;"><?php echo $bp->REKOMENDASI;?></td>
							</tr>
						<?php } ?>
						<tr class="total">
							<td colspan="3" align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Beban Kerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_bp;?></td>
							<td align="center" colspan="3" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;">Jumlah Kinerja</td>
							<td align="center" style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"><?php echo $sks_kp;?></td>
							<td style="border: 0.25px solid black; font-weight: bold; background-color: #d6d6d6;"></td>
						</tr>
				</table>
	<?php } 
	}
}	?>
</body>
</html>
