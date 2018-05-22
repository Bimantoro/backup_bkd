<?php 	


// function data_penawaran_kelas(){
// 		$kd_dosen = $this->session->userdata('kd_dosen');
// 		$smt = $this->session->userdata('kd_smt');
// 		$ta = $this->session->userdata('kd_ta');
// 		$thn = $this->session->userdata('ta');
// 		if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';

// 		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
// 		$parameter  = array(
// 						'api_kode' 		=> 58000,
// 						'api_subkode' 	=> 32,
// 						'api_search' 	=> array($ta, $smt , $kd_dosen)
// 					);
// 		$data['penawaran'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

// 		if(!empty($data['penawaran'])){
// 				foreach ($data['penawaran'] as $data){

// 					//kondisi kroschek mulai dari sini
// 					$jn_mk = $this->_cek_jenis_mk($data->KD_KELAS);
					
// 					//start : kondisi kroschek nilai mata kuliah sudah di upload atau belum oleh dosen
// 					$status_verifikasi = $this->cek_verifikasi_input_nilai($data->KD_KELAS);
// 					//status_verifikasi == 1 berarti nilai mata kuliah sudah di upload oleh dosen dan sudah diverifikasi
// 					if($status_verifikasi == 1){
// 						//finish : kondisi kroschek nilai mata kuliah sudah di upload atau belum oleh dosen
// 						if($jn_mk == 'UMUM'){

// 							$tim = $data->TIM_AJAR;
// 							$jml_tim = explode('#', $tim); $jml_dosen = count($jml_tim);
// 							$jenis_kegiatan = "Mengajar Matakuliah ".$data->NM_MK." Program Studi ".$data->NM_PRODI.", Kelas ".$data->KELAS_PARAREL.", ".$data->SKS." sks, ".$data->TERISI." Mahasiswa, ".$jml_dosen." Dosen";
// 							$nm_keg = $jenis_kegiatan; //"Mengajar Matakuliah ".$data->NM_MK;
// 							$jenjang = $this->jenjang($data->KD_PRODI);
// 							$sks = $data->SKS;
// 							$jml_mhs = $data->TERISI;

// 							// --- modified by DNG A BMTR --- //
							
// 							$apikode = 1000;
// 							if(strtoupper($jenjang) == 'S2'){
// 								$subkode = 2;
// 							}else if(strtoupper($jenjang) == 'S3'){
// 								$subkode = 3;
// 							}else{
// 								$subkode = 1;
// 							}

// 							//$kd_mk = $data->KD_KELAS;

// 							$sks_rule = $this->sksrule->_nilai_sks($jml_mhs, $apikode, $subkode, $sks);

// 							$status_pindah = $this->get_status_pindah(1);

// 							// --- modified by DNG A BMTR --- //


// 							$tatapmuka = $data->TATAP;
// 							$pertemuan_pm = count(explode('#', $data->JADWAL1));
// 							$kelas = $data->KD_JENIS_KELAS;

// 							if($kelas == null || $kelas = ''){
// 								$kelas = 'A';
// 							}

// 							$nm_prodi = $data->NM_PRODI;
// 							$masa_penugasan = "1 Semester";
// 							$bukti_penugasan = '-'; $bkt_dokumen = '-';

// 							$string_kd_kelas = $data->KD_KELAS.'#'.$data->KELAS_PARAREL; // ini solusi ketika data dipisah

// 							//$cek_input_nilai_matkul = $this->cek_input_nilai_matkul($data->KD_KELAS);
// 							$cek_data_pengajaran = $this->cek_api_data_pendidikan($kd_dosen, $ta, $smt, '1', $string_kd_kelas);

// 							if(!$cek_data_pengajaran){
// 								//cek seperti bimbingan TA
// 								$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
// 								$parameter  = array('api_search' => array($kd_dosen, 'A', $jenis_kegiatan, $bukti_penugasan, $sks_rule, $masa_penugasan, $sks_rule, $bkt_dokumen, $thn, $semester, 'LANJUTKAN','100', $ta, $smt));
// 								$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
// 								if ($simpan){
// 									# get last id beban kerja 
// 									$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
// 									$parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
// 									$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
// 									$kd_kelas =  $string_kd_kelas;

// 									#simpan data pendidikan
// 									//catatan: $parameter ke dua adalah kd_kat

// 									$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan';
// 									$parameter	= array('api_search' => array($getid, '1', $nm_keg, $jenjang, '-', $jml_mhs, $sks, $jml_dosen, $tatapmuka, $kelas, $pertemuan_pm, $nm_prodi,$kd_kelas,$status_pindah));
// 									$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
// 								}
// 							}
// 						}
// 					}else{
// 						//do nothing
// 					}
					
// 				}
// 			//}
// 		}else{

// 		}
// 		$this->auto_membimbing_ta();
// 		$this->auto_menguji_ta();
// 		$this->auto_bimbingan_dosen();

// 		redirect('bkd/dosen/bebankerja/data/A');
// 	}

		function auto_membimbing_ta(){
			$ta = $this->session->userdata('kd_ta');
			$smt = $this->session->userdata('kd_smt');

			$data = $this->get_current_bimbingan_ta(2);

			foreach ($data as $ta) {
				$cek_nilai_mhs = $this->get_status_ta($ta['NIM']);
				if($cek_nilai_mhs){
					$nim = $ta['NIM'];
					$judul_ta = $ta['JUDUL'];
					$kd_kur_mhs = $this->kurikulum_mahasiswa($nim);
					$temp = $this->get_data_mhs($nim);
					$jenjang = $temp[0]['NM_JENJANG'];
					$prodi = $temp[0]['NM_PRODI'];

					$list_mk = $this->api->get_api_json(
						URL_API_SIA.'sia_kurikulum/data_search',
						'POST',
						array(
							'api_kode'=>40000,
							'api_subkode' => 15,
							'api_search' => array($kd_kur_mhs)
						)
					);

					foreach ($list_mk as $mk) {
						$get_makul = strtoupper($mk['NM_MK']);
						$get_jenjang = strtoupper($mk['NM_PEND']);
						if($get_jenjang == "S0"){
							if($get_makul == "TUGAS AKHIR" || $get_makul == "SKRIPSI" || $get_makul == "TUGAS AKHIR/SKRIPSI" || $get_makul == "SKRIPSI/TUGAS AKHIR"){
								$jml_sks = $mk['SKS_MK'];
							}
						}else if($get_jenjang == "S1"){
							if($get_makul == "TUGAS AKHIR" || $get_makul == "SKRIPSI" || $get_makul == "TUGAS AKHIR/SKRIPSI" || $get_makul == "SKRIPSI/TUGAS AKHIR"){
								$jml_sks = $mk['SKS_MK'];
							}
						}else if($get_jenjang=="S2"){
							if($get_makul == "TESIS" || $get_makul == "TESIS/TUGAS AKHIR" || $get_makul == "TUGAS AKHIR/TESIS"){
								$jml_sks = $mk['SKS_MK'];
							}
						}else if($get_jenjang=="S3"){
							if($get_makul == "DISERTASI" || $get_makul == "DISERTASI/TUGAS AKHIR" || $get_makul == "TUGAS AKHIR/DISERTASI"){
								$jml_sks = $mk['SKS_MK'];
							}
						}
					}

					//BUAT NARASI + INPUT DATA KE DB + ADA CEK DUPLUKASI DATA
					$narasi = "Membimbing Tugas Akhir ".$jenjang.", Program Studi ".$prodi.", Judul ".$judul_ta.", ".$jml_sks." SKS";
					$narasi = str_replace("'", "", $narasi);
					$narasi = strip_tags($narasi);

					//NARASI UNTUK OTOMARIS MENJADI KETUA SIDANG :

					$narasi2 = "Ketua Sidang Tugas Akhir ".$jenjang.", Program Studi ".$prodi.", Judul ".$judul_ta.", ".$jml_sks." SKS";
					$narasi2 = str_replace("'", "", $narasi2);
					$narasi2 = strip_tags($narasi2);

					//$narasi = str_replace(",", "", $narasi);
					//$sks_rule = round($this->aturan_beban_sks2(strtoupper($jenjang), $jml_sks, 1, 'TUGAS_AKHIR'),2);
					
					$jml_mhs = 1;
					//ATURAN SKS_RULE UNTUK MEMBIMBING TA
					$kd_kat = '74'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					if($jenjang == 'S0'){
						// $sks_rule = round(($sks_rule*1), 2);
						// $sks_rule2 = round(($sks_rule*0.5), 2);
						$sks_rule 	= $this->sksrule->_nilai_sks($jml_mhs, 1001, 2);
						$sks_rule2 	= $this->sksrule->_nilai_sks($jml_mhs, 1003, 2);
						$kd_kat = '73'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					}elseif($jenjang == 'S1'){
						// $sks_rule = round(($sks_rule*1), 2);
						// $sks_rule2 = round(($sks_rule*0.5), 2);
						$sks_rule 	= $this->sksrule->_nilai_sks($jml_mhs, 1001, 2);
						$sks_rule2 	= $this->sksrule->_nilai_sks($jml_mhs, 1003, 2);
						$kd_kat = '73'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					}elseif($jenjang == 'S2'){
						// $sks_rule = round(($sks_rule*1), 2);
						// $sks_rule2 = round(($sks_rule*0.5), 2);
						$sks_rule 	= $this->sksrule->_nilai_sks($jml_mhs, 1001, 3);
						$sks_rule2 	= $this->sksrule->_nilai_sks($jml_mhs, 1003, 3);
						$kd_kat = '73'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					}elseif($jenjang == 'S3'){
						// $sks_rule = round(($sks_rule*1), 2);
						// $sks_rule2 = round(($sks_rule*0.5), 2);
						$sks_rule 	= $this->sksrule->_nilai_sks($jml_mhs, 1001, 4);
						$sks_rule2 	= $this->sksrule->_nilai_sks($jml_mhs, 1003, 4);
						$kd_kat = '74'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					}

					$smt = $this->session->userdata('kd_smt');
					$ta = $this->session->userdata('kd_ta');

					// $kd_jbk = 'A';
					$kd_dosen = $this->session->userdata('kd_dosen');
					$jenis_kegiatan = $narasi;
					/*$jenis_kegiatan ='test';*/
					$bukti_penugasan = '-';
					// $sks_penugasan = $sks_rule;
					$masa_penugasan = '1 Semester';
					$bkt_dokumen = '-';
					$kd_jbk 		= 'A';
					$bkt_penugasan 	= '-';
					$bkt_dokumen 	= '-';
					$rekomendasi 	= 'LANJUTKAN';
					$capaian 		= 100;
					$jml_jam 		= 1;
					$outcome 		= '-';
					$file_penugasan = '-';
					$file_capaian 	= '-';
					// $sks_bkt = $sks_rule;
					$thn = $this->session->userdata('ta');
					if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';
					// $rekomendasi = 'LANJUTKAN';
					// $jumlah_jam = null;
					// $capaian = '100';
					// $outcome = null;
					// $file_penugasan = null;
					// $file_capaian = null;

					//insert into BKD_BEBAN_KERJA && BKD_REMUN_KINERJA

					//cek apakah sudah ada data atau belum ?
					//$cek_nilai_mhs = $this->get_status_ta($nim);
					$cek_mhs_bimbingan = $this->cek_api_mhs_bimbingan($nim);

					$cek_mhs_bimbingan_ta = $this->cek_api_data_pendidikan($kd_dosen, $ta, $smt, '3', $nim);
					$cek_mhs_sidang_ta = $this->cek_api_data_pendidikan($kd_dosen, $ta, $smt, $kd_kat, $nim);

					if(!$cek_mhs_bimbingan_ta){

						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
						$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule, $masa_penugasan, $bkt_dokumen, $sks_rule, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						if($simpan){
							// echo 'simpan ke db bkd_beban_kerja dan BKD_REMUN_KINERJA sukses'; echo '<br>';
							// $api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
							// $parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
							// $getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

							$getid = $simpan;
							$nm_keg = $jenis_kegiatan;

							$status_pindah = $this->get_status_pindah(3);
							#simpan data pendidikan
							//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
							//akan berubah menjadi kd_kat untuk membimbing
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
							$parameter	= array('api_search' => array($getid, '3', $nm_keg, $jenjang, '-', '1', $jml_sks, '1', '1', 'A', '1', $prodi, $nim, $status_pindah));
							$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
							if($simpan){
								//menimpan otomatis menjadi ketua sidang disini
								$jenis_kegiatan = $narasi2;
								$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
								$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule2, $masa_penugasan, $bkt_dokumen, $sks_rule2, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
								$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

								if($simpan && !$cek_mhs_sidang_ta){
									$getid = $simpan;
									$nm_keg = $jenis_kegiatan;

									$status_pindah = $this->get_status_pindah($kd_kat);
									#simpan data pendidikan
									//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
									//akan berubah menjadi kd_kat untuk membimbing
									$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
									$parameter	= array('api_search' => array($getid, $kd_kat, $nm_keg, $jenjang, '-', '1', $jml_sks, '1', '1', 'A', '1', $prodi, $nim, $status_pindah));
									$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
								}

							}
						}
					}
				}				
			}
		}

		function get_jml_mhs_bimbingan_semprop(){
			$jml = $this->get_current_bimbingan_semprop();
			$jml = count($jml);
			if($jml > 0){ return $jml; }else{ return false; }
		}

		function auto_membimbing_semprop(){

			$semprop = $this->get_jml_mhs_bimbingan_semprop();
			if($semprop){
				$kd_kat 	= 8; //ini untuk membimbing seminar proposal
		 		$kd_jbk 	= 'A';
		 		$kd_dosen 	= $this->session->userdata('kd_dosen');
		 		$kd_ta 		= $this->session->userdata('kd_ta');
		 		$kd_smt 	= $this->session->userdata('kd_smt');
		 		$status_pindah = $this->get_status_pindah(8);


		 		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_bimbingan_mhs_sp'; // dengan status pindah
		 		$parameter	= array('api_search' => array($kd_kat, $kd_jbk, $kd_dosen, $kd_ta, $kd_smt, $status_pindah)); //statusp pindah dimasukkan untuk crosscex ketika data tidak memiliki relasi dengan remunasi / sebaliknya

		 		$kd_bk 		= $this->api->get_api_json($api_url, 'POST', $parameter);

		 		if($kd_bk){
		 			$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_jml_mhs_bimbingan';
					$parameter	= array('api_search' => array($kd_bk, $kkn));
					$update_bk	= $this->api->get_api_json($api_url, 'POST', $parameter);
		 		}else{

		 			$jml_sks = 0;
		 			$narasi = "Membimbing seminar proposal";

		 			$jml_mhs = $semprop;
		 			$sks_rule = $this->sksrule->_nilai_sks($jml_mhs, 1001, 1);

		 			$smt = $this->session->userdata('kd_smt');
					$ta = $this->session->userdata('kd_ta');

					// $kd_jbk = 'A';
					$kd_dosen = $this->session->userdata('kd_dosen');
					$jenis_kegiatan = $narasi;

					$bukti_penugasan = '-';
					// $sks_penugasan = $sks_rule;
					$masa_penugasan = '1 Semester';
					$bkt_dokumen = '-';
					$kd_jbk 		= 'A';
					$bkt_penugasan 	= '-';
					$bkt_dokumen 	= '-';
					$rekomendasi 	= 'LANJUTKAN';
					$capaian 		= 100;
					$jml_jam 		= 1;
					$outcome 		= '-';
					$file_penugasan = '-';
					$file_capaian 	= '-';
					$thn = $this->session->userdata('ta');
					if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';

					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
					$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule, $masa_penugasan, $bkt_dokumen, $sks_rule, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

					if($simpan){
						$getid = $simpan;
						$nm_keg = $jenis_kegiatan;
						#simpan data pendidikan
						//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
						//akan berubah menjadi kd_kat untuk membimbing

						

						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
						$parameter	= array('api_search' => array($getid, '9', $nm_keg, 'S1', '-', $jml_mhs, $jml_sks, '1', '1', 'A', '1', '-', '-', $status_pindah));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					}

		 		}
			}
		}

		function auto_membimbing_semprop(){
			$ta = $this->session->userdata('kd_ta');
			$smt = $this->session->userdata('kd_smt');

			$data = $this->get_current_bimbingan_semprop(1);

			foreach ($data as $ta) {
				$cek_nilai_mhs = $this->get_status_semprop($ta['NIM']);
				if($cek_nilai_mhs){
					$nim = $ta['NIM'];
					$judul_ta = $ta['JUDUL'];
					$kd_kur_mhs = $this->kurikulum_mahasiswa($nim);
					$temp = $this->get_data_mhs($nim);
					$jenjang = $temp[0]['NM_JENJANG'];
					$prodi = $temp[0]['NM_PRODI'];

					$list_mk = $this->api->get_api_json(
						URL_API_SIA.'sia_kurikulum/data_search',
						'POST',
						array(
							'api_kode'=>40000,
							'api_subkode' => 15,
							'api_search' => array($kd_kur_mhs)
						)
					);


					$jml_sks = 0;

					foreach ($list_mk as $mk) {
						$get_makul = strtoupper($mk['NM_MK']);
						$get_jenjang = strtoupper($mk['NM_PEND']);
						if($get_jenjang == "S0"){
							if($get_makul == "TUGAS AKHIR 1" || $get_makul == "TUGAS AKHIR I" || $get_makul == "SKRIPSI I" || $get_makul == "SKRIPSI 1" || $get_makul == "SEMINAR PROPOSAL" || $get_makul == "SEMINAR PROPOSAL SKRIPSI" || $get_makul == "SEMINAR PROPOSAL TUGAS AKHIR I" || $get_makul == "SEMINAR PROPOSAL TUGAS AKHIR 1" || $get_makul == "TUGAS AKHIR 1/SEMINAR PROPOSAL" || $get_makul == "SEMINAR PROPOSAL/TUGAS AKHIR 1" || $get_makul == "TUGAS AKHIR I/SEMINAR PROPOSAL" || $get_makul == "SEMINAR PROPOSAL/TUGAS AKHIR I" || $get_makul == "SEMINAR AKUNTANSI SYARIAH" || $get_makul == "SEMINAR BKI" || $get_makul == "SEMINAR KOMUNIKASI"){
								$jml_sks = $mk['SKS_MK'];
							}
						}else if($get_jenjang == "S1"){
							if($get_makul == "TUGAS AKHIR 1" || $get_makul == "TUGAS AKHIR I" || $get_makul == "SKRIPSI I" || $get_makul == "SKRIPSI 1" || $get_makul == "SEMINAR PROPOSAL" || $get_makul == "SEMINAR PROPOSAL SKRIPSI" || $get_makul == "SEMINAR PROPOSAL TUGAS AKHIR I" || $get_makul == "SEMINAR PROPOSAL TUGAS AKHIR 1" || $get_makul == "TUGAS AKHIR 1/SEMINAR PROPOSAL" || $get_makul == "SEMINAR PROPOSAL/TUGAS AKHIR 1" || $get_makul == "TUGAS AKHIR I/SEMINAR PROPOSAL" || $get_makul == "SEMINAR PROPOSAL/TUGAS AKHIR I" || $get_makul == "SEMINAR AKUNTANSI SYARIAH" || $get_makul == "SEMINAR BKI" || $get_makul == "SEMINAR KOMUNIKASI"){
								$jml_sks = $mk['SKS_MK'];
							}
						}else if($get_jenjang=="S2"){
							if($get_makul == "SEMINAR PROPOSAL TESIS" || $get_makul == "PROPOSAL TESIS" || $get_makul == "SEMINAR PROPOSAL THESIS" || $get_makul == "PROPOSAL THESIS" || $get_makul == "SEMINAR EKONOMI SYARIAH" || $get_makul == "SEMINAR PROPOSAL" || $get_makul == "THESIS 1" || $get_makul == "THESIS I" || $get_makul == "TESIS 1" || $get_makul == "THESIS I" || $get_makul == "IAT13SEMINAR PROPOSAL TESIS" || $get_makul == "SEMINAR EKONOMI SYARIAH" || $get_makul == "SEMINAR PERBANKAN SYARIAH" || $get_makul == "THESIS SEMINAR PROPOSAL"){
								$jml_sks = $mk['SKS_MK'];
							}
						}else if($get_jenjang=="S3"){
							if($get_makul == "DISERTASI 1" || $get_makul == "DISERTASI I" || $get_makul == "DISSERTATION" || $get_makul == "SEMINAR PROPOSAL" || $get_makul == "SEMINAR PROPOSAL DISERTASI" || $get_makul == "DISSERTATION PROPOSAL SEMINAR"){
								$jml_sks = $mk['SKS_MK'];
							}
						}
					}



					//BUAT NARASI + INPUT DATA KE DB + ADA CEK DUPLUKASI DATA
					$narasi = "Membimbing Seminar Proposal ".$jenjang.", Program Studi ".$prodi.", Judul ".$judul_ta.", ".$jml_sks." SKS";
					$narasi = str_replace("'", "", $narasi);
					$narasi = strip_tags($narasi);

					//NARASI UNTUK OTOMARIS MENJADI KETUA SIDANG :

					// $narasi2 = "Ketua Sidang Tugas Akhir ".$jenjang.", Program Studi ".$prodi.", Judul ".$judul_ta.", ".$jml_sks." SKS";
					// $narasi2 = str_replace("'", "", $narasi2);
					// $narasi2 = strip_tags($narasi2);

					//$narasi = str_replace(",", "", $narasi);
					//$sks_rule = round($this->aturan_beban_sks2(strtoupper($jenjang), $jml_sks, 1, 'TUGAS_AKHIR'),2);
					
					$jml_mhs = 1;
					//ATURAN SKS_RULE UNTUK MEMBIMBING TA
					$kd_kat = '74'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					if($jenjang == 'S0'){
						// $sks_rule = round(($sks_rule*1), 2);
						// $sks_rule2 = round(($sks_rule*0.5), 2);
						$sks_rule 	= $this->sksrule->_nilai_sks($jml_mhs, 1001, 2);
						$sks_rule2 	= $this->sksrule->_nilai_sks($jml_mhs, 1003, 2);
						$kd_kat = '73'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					}elseif($jenjang == 'S1'){
						// $sks_rule = round(($sks_rule*1), 2);
						// $sks_rule2 = round(($sks_rule*0.5), 2);
						$sks_rule 	= $this->sksrule->_nilai_sks($jml_mhs, 1001, 2);
						$sks_rule2 	= $this->sksrule->_nilai_sks($jml_mhs, 1003, 2);
						$kd_kat = '73'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					}elseif($jenjang == 'S2'){
						// $sks_rule = round(($sks_rule*1), 2);
						// $sks_rule2 = round(($sks_rule*0.5), 2);
						$sks_rule 	= $this->sksrule->_nilai_sks($jml_mhs, 1001, 3);
						$sks_rule2 	= $this->sksrule->_nilai_sks($jml_mhs, 1003, 3);
						$kd_kat = '73'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					}elseif($jenjang == 'S3'){
						// $sks_rule = round(($sks_rule*1), 2);
						// $sks_rule2 = round(($sks_rule*0.5), 2);
						$sks_rule 	= $this->sksrule->_nilai_sks($jml_mhs, 1001, 4);
						$sks_rule2 	= $this->sksrule->_nilai_sks($jml_mhs, 1003, 4);
						$kd_kat = '74'; //ini untuk otomatis pembimbing menjadi ketua sidang (tim penguji)
					}

					$smt = $this->session->userdata('kd_smt');
					$ta = $this->session->userdata('kd_ta');

					// $kd_jbk = 'A';
					$kd_dosen = $this->session->userdata('kd_dosen');
					$jenis_kegiatan = $narasi;
					/*$jenis_kegiatan ='test';*/
					$bukti_penugasan = '-';
					// $sks_penugasan = $sks_rule;
					$masa_penugasan = '1 Semester';
					$bkt_dokumen = '-';
					$kd_jbk 		= 'A';
					$bkt_penugasan 	= '-';
					$bkt_dokumen 	= '-';
					$rekomendasi 	= 'LANJUTKAN';
					$capaian 		= 100;
					$jml_jam 		= 1;
					$outcome 		= '-';
					$file_penugasan = '-';
					$file_capaian 	= '-';
					// $sks_bkt = $sks_rule;
					$thn = $this->session->userdata('ta');
					if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';
					// $rekomendasi = 'LANJUTKAN';
					// $jumlah_jam = null;
					// $capaian = '100';
					// $outcome = null;
					// $file_penugasan = null;
					// $file_capaian = null;

					//insert into BKD_BEBAN_KERJA && BKD_REMUN_KINERJA

					//cek apakah sudah ada data atau belum ?
					//$cek_nilai_mhs = $this->get_status_ta($nim);
					$cek_mhs_bimbingan = $this->cek_api_mhs_bimbingan($nim);

					$cek_mhs_bimbingan_ta = $this->cek_api_data_pendidikan($kd_dosen, $ta, $smt, '3', $nim);
					$cek_mhs_sidang_ta = $this->cek_api_data_pendidikan($kd_dosen, $ta, $smt, $kd_kat, $nim);

					if(!$cek_mhs_bimbingan_ta){

						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
						$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule, $masa_penugasan, $bkt_dokumen, $sks_rule, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						if($simpan){
							// echo 'simpan ke db bkd_beban_kerja dan BKD_REMUN_KINERJA sukses'; echo '<br>';
							// $api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
							// $parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
							// $getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

							$getid = $simpan;
							$nm_keg = $jenis_kegiatan;

							$status_pindah = $this->get_status_pindah(3);
							#simpan data pendidikan
							//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
							//akan berubah menjadi kd_kat untuk membimbing
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
							$parameter	= array('api_search' => array($getid, '3', $nm_keg, $jenjang, '-', '1', $jml_sks, '1', '1', 'A', '1', $prodi, $nim, $status_pindah));
							$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
							if($simpan){
								//menimpan otomatis menjadi ketua sidang disini
								$jenis_kegiatan = $narasi2;
								$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
								$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule2, $masa_penugasan, $bkt_dokumen, $sks_rule2, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
								$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

								if($simpan && !$cek_mhs_sidang_ta){
									$getid = $simpan;
									$nm_keg = $jenis_kegiatan;

									$status_pindah = $this->get_status_pindah($kd_kat);
									#simpan data pendidikan
									//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
									//akan berubah menjadi kd_kat untuk membimbing
									$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
									$parameter	= array('api_search' => array($getid, $kd_kat, $nm_keg, $jenjang, '-', '1', $jml_sks, '1', '1', 'A', '1', $prodi, $nim, $status_pindah));
									$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
								}

							}
						}
					}
				}				
			}
		}


		switch ($) {
			case 'value':
				# code...
				break;
			
			default:
				# code...
				break;
		}

 ?>