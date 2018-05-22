<?php
$buka = '';
if ($this->session->userdata('app') == 'app_bkd') { $buka = 'buka'; } 
else { $buka = ''; }
		
if ($this->session->userdata('app') == 'ver_bkd') { $bukaver = 'buka'; } 
else { $bukaver = ''; }			
#BKDADM#BKD1222206#BKD1006#BEA0201#BEA0103 

#print_r($this->session->all_userdata());

if ($this->session->userdata('status') == 'staff'){
	$ses_jab = $this->session->userdata('jabatan');
	#$ses = $this->session->userdata('id_user');
	$status_adm = explode("#", $ses_jab);
	$is_adm = 0;
	$is_adm_fakultas = 0;
	$is_adm_prodi = 0;
	$is_asesor = 0;
	
	for($a = 0; $a<count($status_adm); $a++){
		if($status_adm[$a] == 'BKDADM'){
			$is_adm = 1;
			$this->session->set_userdata('adm_univ', 'BKDADM');			
		}
		# cek status admin fakultas
		$cek_kode[$a] = substr($status_adm[$a],0,5);
		if($cek_kode[$a] == 'BKD10'){
			#$is_adm_fakultas = 1;
			$pnjChar = strlen($status_adm[$a])-5;
			$getFak = substr($status_adm[$a],5,$pnjChar);
			#$this->session->set_userdata('adm_fak', $getFak);
		}
		if($cek_kode[$a] == 'BKD12'){
			#$is_adm_prodi = 1;
			$pnjChar = strlen($status_adm[$a])-5;
			$getPro = substr($status_adm[$a],5,$pnjChar);
			#$this->session->set_userdata('adm_pro', $getPro);
		}
	}

	# cek status user
	if ($is_adm == 1){ ?>
		<?php #print_r ($this->session->all_userdata());?>
		<!-- content menu admin -->
		<li id="li-bkd-admin" class="item">
			<a href="<?php echo base_url().'bkd/admbkd/home';?>" class="item"><span>ADMIN Kinerja Dosen</span></a>
				<div class="underline-menu"></div>
				<ol id="ol-bkd" class="<?php echo $buka;?>">
					<li class="submenu"><span><b>Master</b></span></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/asesor/data';?>"><span>Daftar Asesor</span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/asesor/prodi';?>"><span>Asesor Prodi</span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/asesor/import';?>"><span>Import Asesor</span></a></li>
					<li class="submenu"><span><b>Kompilasi</b><span></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/dosen';?>"><span>Cari Kompilasi<span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/semester';?>"><span>Kompilasi Kinerja Semester<span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi';?>"><span>Kompilasi Kinerja Tahunan<span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/akademik';?>"><span>Kompilasi Kegiatan Akademik<span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/narasumber';?>"><span>Kompilasi Narasumber/Pembicara<span></a></li>
					<li class="submenu"><span><b>Data Publikasi</b><span></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/publikasi_dosen';?>"><span>Cari Publikasi<span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/publikasi_semester';?>"><span>Kompilasi Publikasi<span></a></li>
					<li class="submenu"><span><b>Pengaturan</b></span></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/setting/pengaturan_sks';?>"><span>Pengaturan Nilai SKS</span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/setting/pengaturan_syarat';?>"><span>Pengaturan Syarat Beban Kerja</span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/setting/pengaturan_batas';?>"><span>Pengaturan Batas Beban Kerja</span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/setting/pengaturan_konversi';?>"><span>Pengaturan Konversi Kategori</span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/setting/pengaturan_jalur';?>"><span>Pengaturan Jalur Data</span></a></li>

					<?php if(strtoupper($this->session->userdata('id_user')) == 'PKSI100'){ ?>
					<li class="submenu"><span><b>Pengaturan Poin Remun</b></span></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/setremun/poin';?>"><span>Pengaturan Poin Remun</span></a></li>
					<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/setremun/pengaturan_remun_mengajar';?>"><span>Pengaturan Batas Mengajar</span></a></li>
					<?php } ?>

				</ol>	
		</li>
	<?php }
	else{
			# BKD ADMIN FAKULTAS MENU
			if($is_adm_fakultas == 1){ ?>
			
				<li id="li-bkd-admin" class="item">			
					<a href="<?php echo base_url().'bkd/admbkd/home';?>" class="item"><span>Admin Kinerja Dosen Fakultas</span></a>
						<div class="underline-menu"></div>
						<ol id="ol-bkd" class="<?php echo $buka;?>">
							<li class="submenu"><span><b>Kompilasi</b><span></li>
							<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/dosen/fakultas';?>"><span>Kompilasi Dosen<span></a></li>
							<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/semester/fakultas';?>"><span>Kompilasi Semester<span></a></li>
							<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/fakultas';?>"><span>Kompilasi Tahunan<span></a></li>
							<li class="submenu"><span><b>Data Publikasi</b><span></li>
							<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/publikasi/fakultas';?>"><span>Publikasi Dosen<span></a></li>
						</ol>	
				</li>
			<?php }else{
				# ADMIN BKD PRODI MENU
					if($is_adm_prodi == 1){ ?>
					
						<li id="li-bkd-admin" class="item">			
							<a href="<?php echo base_url().'bkd/admbkd/home';?>" class="item"><span>Admin Kinerja Dosen Prodi</span></a>
								<div class="underline-menu"></div>
								<ol id="ol-bkd" class="<?php echo $buka;?>">
									<li class="submenu"><span><b>Kompilasi</b><span></li>
									<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/dosen/prodi';?>"><span>Kompilasi Dosen<span></a></li>
									<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/semester/prodi';?>"><span>Kompilasi Semester<span></a></li>
									<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/prodi';?>"><span>Kompilasi Tahunan<span></a></li>
									<li class="submenu"><span><b>Data Publikasi</b><span></li>
									<li class="submenu"><a href="<?php echo site_url().'bkd/admbkd/kompilasi/publikasi/prodi';?>"><span>Publikasi Dosen<span></a></li>
								</ol>	
						</li>
	<?php			}
				}
	}
	
	$sess = $this->session->userdata('jabatan');
	$status = explode("#", $sess);
	$is_dosen = 0;
	for ($a=0; $a<count($status); $a++){
		if ($status[$a] == 'DSN'){
			$is_dosen = 1; # dosen
		}
	}
	if ($is_dosen == 1){ ?>
	<!-- menu dosen -->
	<?php if(!isset($status_err_bkd)){?>
	<li id="li-bkd" class="item">			
		<a href="<?php echo base_url().'bkd/auth#bkd';?>" class="item"><span>Kinerja Dosen</span></a>
			<div class="underline-menu"></div>
			<ol id="ol-bkd" class="<?php echo $buka;?>">
				<li class="submenu"><b>Identitas</b></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/biodata/edit_biodata';?>"> Profil</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/biodata/edit_kepegawaian';?>"> Kepegawaian</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/biodata/edit_akademik';?>"> Akademik</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/biodata/edit_riwayat_pendidikan';?>"> Pendidikan</a></li>
				<li class="submenu"><b>RBKD Sertifikasi</b></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/rbkd_api/A';?>"> Pendidikan</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/rbkd_api/B';?>"> Penelitian</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/rbkd_api/C';?>"> Pengabdian Masyarakat</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/rbkd_api/D';?>"> Penunjang Lain</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/rbkd_api/E';?>"> Kewajiban Khusus Profesor</a></li>
				<li class="submenu"><b>BKD Sertifikasi</b></li>
				<!--<li class="submenu"><a href="<?php //echo site_url().'bkd/dosen/biodata/edit_biodata';?>"> Identitas</a></li>-->
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/data_penawaran_kelas';?>"> Pendidikan</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/data/B';?>"> Penelitian</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/cek_dpl';?>"> Pengabdian Masyarakat</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/cek_dpa_sia';?>"> Penunjang Lain</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/profesor';?>">Kewajiban Khusus Profesor</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/akademik';?>"> Kegiatan Akademik</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/publikasi';?>"> Publikasi</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/data/F';?>"> Narasumber/Pembicara</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/data/H';?>"> HAKI</a></li>				
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/kesimpulan';?>">Kesimpulan</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/bebankerja/cetak';?>">Cetak Laporan</a></li>
				<li class="submenu"><b>BKD Remun</b></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/remun/data/A';?>"> Pendidikan</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/remun/data/B';?>"> Penelitian</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/remun/data/D';?>"> Penunjang</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/remun/kesimpulan';?>"> Kesimpulan</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/remun/cetak';?>">Cetak Laporan</a></li>

				<li class="submenu"><b>Verifikator Kinerja Dosen</b></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/verifikator/sertifikasi_dosen';?>"> Verifikator Sertifikasi Dosen</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/dosen/verifikator/remunerasi_dosen';?>"> Verifikator Remunerasi Dosen</a></li>
				
				<!-- <li class="submenu"><b>Asesor Kinerja Dosen</b></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/verifikator/kinerja/sertifikasi_dosen';?>"> Asesor BKD Sertifikasi Dosen</a></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/verifikator/kinerja/remunerasi_dosen';?>"> Asesor BKD Remunerasi Dosen</a></li> -->

			</ol>	
	</li>
	<?php }else{ ?>
	<li id="li-bkd" class="item">			
		<a href="<?php echo base_url().'bkd/auth#bkd';?>" class="item"><span>Kinerja Dosen</span></a>
		<div class="underline-menu"></div>
	</li>
	<?php } 
	}
	
	# VERIFIKATOR
	$sess = $this->session->userdata('jabatan');
	$status = explode("#", $sess);
	$is_dosen = 0;
	for ($a=0; $a<count($status); $a++){
		if ($status[$a] == 'BKDVER'){
			$is_asesor = 1; # dosen
			$this->session->set_userdata('adm', $status[$a]);
		}
	}
	if ($is_asesor == 1){ ?>
	<!-- menu dosen -->
	<?php if(!isset($status_err_bkd)){?>
	<li id="li-bkd" class="item">			
		<a href="<?php echo base_url().'bkd/verifikator/kinerja';?>" class="item"><span>Asesor Kinerja Dosen</span></a>
			<div class="underline-menu"></div>

			<ol id="ol-bkd" class="<?php echo $bukaver;?>">
				<li class="submenu"><b>Rencana dan Kinerja</b></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/verifikator/kinerja/dosen';?>"> Data Dosen</a></li>
			</ol>
			<!-- <ol id="ol-bkd" class="<?php echo $bukaver;?>">
				<li class="submenu"><b>Asesor BKD Dosen</b></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/verifikator/kinerja/sertifikasi_dosen';?>"> Data Sertifikasi Dosen</a></li>
			</ol>
			<ol id="ol-bkd" class="<?php echo $bukaver;?>">
				<li class="submenu"><b>Asesor Renumerasi Dosen</b></li>
				<li class="submenu"><a href="<?php echo site_url().'bkd/verifikator/kinerja/remunerasi_dosen';?>"> Data Renumerasi Dosen</a></li>
			</ol> -->	
	</li>
	<?php }else{ ?>
	<li id="li-bkd" class="item">			
		<a href="<?php echo base_url().'bkd/auth#bkd';?>" class="item"><span>Kinerja Dosen</span></a>
		<div class="underline-menu"></div>
	</li>
	<?php } 
	}
} ?>


