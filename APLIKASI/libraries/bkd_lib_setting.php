<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Bkd_lib_setting {

	# CEK AKHIR SEMESTER PRODI BERSANGKUTAN
	# ================== 15/07/2014 ====================================
	
	/* 
		JIKA KD_SMT = 1, MAKA YANG DIAMBIL TANGGAL SMT 2 TAHUN SEBEUMNYA
		JIKA KD_SMT = 2, MAKA YANG DIAMBIL TANGGAL SMT 1 TAHUN SEKARANG
	*/
	
	function _cek_akhir_smt($kd_ta, $kd_smt){
		$CI =& get_instance();
		$kd_prodi 	= $CI->session->userdata('kd_prodi');
		
		if($kd_prodi == null){
			return null;
		}else{
			if($kd_smt == 1){
				$kd_ta_lalu = $kd_ta - 1;
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data)) return $data[0]->T_SMT_AKT_2_F; else return 0;
			}
			else{
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data)) return $data[0]->T_SMT_AKT_2_F; else return 0;
			}
		}
	}

	# BATAS PENGISIAN BKD SEMESTER SEBELUMNYA (DISPENSASI PENGISIAN DEFAULT 90 HARI DARI SIA)
	function _batas_isi_bkd($kd_ta, $kd_smt){
		$akhr_smt = $this->_cek_akhir_smt($kd_ta, $kd_smt);
		$disp = $this->_dispen_hari();
		$skrg = $akhr_smt;
		$result = date('d-m-Y H:i:s', strtotime($skrg. "+ ".$disp." days"));
		return $result;
	}
	
	function _cek_status_akhir_smt($kd_ta, $kd_smt){
		$date = $this->_cek_akhir_smt($kd_ta, $kd_smt);
		if($date == 0 || $date == '01-01-1900 00:00:00') return 0;
		else return 1;
	}
	
	
	function _is_crud_bkd_lalu($kd_ta, $kd_smt){
		$CI =& get_instance();

		$now = date('d-m-Y H:i:s');
		$berakhir = $this->_batas_isi_bkd($kd_ta, $kd_smt);
		$batas  = date('d-m-Y H:i:s', strtotime($berakhir));
		
		$ta_berjalan = $CI->session->userdata('ta_berjalan');
		$smt_berjalan = $CI->session->userdata('smt_berjalan');

		if($CI->session->userdata('ta') == $ta_berjalan && $CI->session->userdata('smt') == $smt_berjalan){
			return 1;
		}else{
			if(strtotime($now) > strtotime($batas)){
				return false;
			}
			else{
				return true; #$now.' > '.$batas;
			}
		}
	}
	
	# DISPENSASI PENGISIAN DATA BKD
	# =================================================================
	
	function _dispen_hari(){
		$CI =& get_instance();
		$api_url 	= URL_API_SIA.'sia_sistem/data_view';
		$parameter  = array('api_kode' =>200002, 'api_subkode' => 1, 'api_search' => array());
		$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data[0]->KADALUWARSA_HARI1;
	}
	

	# GENERATE FUNGSI NAMA JENIS DOSEN / STATUS TUGAS
	function _jenisDosen($kode){
		if($kode == 'DS') $name = 'DOSEN BIASA';
		else if($kode == 'DT') $name = 'DOSEN DENGAN TUGAS TAMBAHAN';
		else if($kode == 'PR') $name = 'PROFESOR';
		else if($kode == 'PT') $name = 'PROFESOR DENGAN TUGAS TAMBAHAN';
		else $name = '';
		return $name;
	}
	
	
	
	# SETTING DISPENSISI PENGISIAN RBKD
	# =============================================================={{[]}}==============================================================			!impotent
	function _is_crud_rbkd_lalu(){
		$CI =& get_instance();

		$now = date('d-m-Y H:i:s');
		$berakhir = $this->_batas_isi_bkd();
		$ta_berjalan = $CI->session->userdata('ta_berjalan');
		$smt_berjalan = $CI->session->userdata('smt_berjalan');

		if($CI->session->userdata('ta') == $ta_berjalan && $CI->session->userdata('smt') == $smt_berjalan){
			return 1;
		}else{
			if($now > $berakhir){
				return 0;
			}
			else{
				return 1;
			}
		}
	}
	
	
	function _tanggal_akhir_laporan($kd_ta, $kd_smt, $jenis){
		$CI =& get_instance();
		$kd_prodi 	= $CI->session->userdata('kd_prodi');
		
		if($jenis == 'lbkd'){
			if($kd_smt == 1){
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data)){
					$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
				}else{
					$tanggal = date('d/m/Y');
				}
			}else{ 
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta+1, $kd_smt-1, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data)){
					$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
				}else{
					$tanggal = date('d/m/Y');
				}
			}
			#print_r($data); die();
			# CEK TANGGAL SEKARANG/CETAK
			if(date('d/m/Y') <= $tanggal){
				$hasil = date('d/m/Y');
			}else{
				$hasil = $tanggal;
			}
			
		}else{
			$api_url 	= URL_API_SIA.'sia_master/data_search';
			$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
			$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if($kd_smt == 1){ $tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F)); }else{ $tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_2_F));}		
			# CEK TANGGAL SEKARANG/CETAK
			if(date('d/m/Y') <= $tanggal){
				$hasil = date('d/m/Y');
			}else{
				$hasil = $tanggal;
			}
		}
		return $hasil;
	}
	
	function _tanggal_akhir_semester($kd_ta, $kd_smt, $jenis){
		$CI =& get_instance();
		$kd_prodi 	= $CI->session->userdata('kd_prodi');
		
		if($jenis == 'lbkd'){
			if($kd_smt == 1){
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data)){
					$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
				}else{
					$tanggal = date('d/m/Y');
				}
			}else{ 
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta+1, $kd_smt-1, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data)){
					$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
				}else{
					$tanggal = date('d/m/Y');
				}
			}
			#print_r($data); die();
			# CEK TANGGAL SEKARANG/CETAK
			if(date('d/m/Y') <= $tanggal){
				$hasil = $this->tanggal_indo(date('d/m/Y'));
			}else{
				$hasil = $this->tanggal_indo($tanggal);
			}
			
		}else{
			// $api_url 	= URL_API_SIA.'sia_master/data_search';
			// $parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
			// $data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			// if($kd_smt == 1){ 
				// $tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F)); 
			// }else{ 
				// $tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
			// }		
			# CEK TANGGAL SEKARANG/CETAK
			# LAPORAN RBKD PENANGGALAN SAMA DENGAN BKD, KARENA PROSESNYA PELAPORANYA BERSAMAAN
			// if(date('d/m/Y') <= $tanggal){
				// $hasil = $this->tanggal_indo(date('d/m/Y'));
			// }else{
				// $hasil = $this->tanggal_indo($tanggal);
			// }
			if($kd_smt == 1){
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data)){
					$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
				}else{
					$tanggal = date('d/m/Y');
				}
			}else{ 
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta+1, $kd_smt-1, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data)){
					$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
				}else{
					$tanggal = date('d/m/Y');
				}
			}
			#print_r($data); die();
			# CEK TANGGAL SEKARANG/CETAK
			if(date('d/m/Y') <= $tanggal){
				$hasil = $this->tanggal_indo(date('d/m/Y'));
			}else{
				$hasil = $this->tanggal_indo($tanggal);
			}
		}
		return $hasil;
	}
	
	function _tanggal_akhir_semester_cek($kd_ta, $kd_smt, $jenis){
	$CI =& get_instance();
	$kd_prodi 	= $CI->session->userdata('kd_prodi');
	
	if($jenis == 'lbkd'){
		if($kd_smt == 1){
			$api_url 	= URL_API_SIA.'sia_master/data_search';
			$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
			$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data)){
				$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
			}else{
				$tanggal = date('d/m/Y');
			}
		}else{ 
			$api_url 	= URL_API_SIA.'sia_master/data_search';
			$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta+1, $kd_smt-1, $kd_prodi));
			$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data)){
				$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
			}else{
				$tanggal = date('d/m/Y');
			}
		}
		
		if(date('d/m/Y') <= $tanggal){
			$hasil = $this->$tanggal;
		}else{			
			$hasil = $this->date('d/m/Y');
		}
		
	}else{

		if($kd_smt == 1){
			$api_url 	= URL_API_SIA.'sia_master/data_search';
			$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
			$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data)){
				$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
			}else{
				$tanggal = date('d/m/Y');
			}
		}else{ 
			$api_url 	= URL_API_SIA.'sia_master/data_search';
			$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta+1, $kd_smt-1, $kd_prodi));
			$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data)){
				$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
			}else{
				$tanggal = date('d/m/Y');
			}
		}
		
		if(date('d/m/Y') <= $tanggal){
			$hasil = $this->tanggal_indo(date('d/m/Y'));
		}else{
			$hasil = $this->tanggal_indo($tanggal);
		}
	}
	return $hasil;
	}
	
	function _batas_isi_rbkd(){
		$akhr_smt = $this->_cek_akhir_smt_depan();
		$disp = $this->_dispen_hari();
		$skrg = $akhr_smt;
		$result = date('d-m-Y H:i:s', strtotime($skrg. "+ ".$disp." days"));
		return $result;
	}
	
	function _cek_akhir_smt_depan(){
		$CI =& get_instance();
		$r = $this->generate_tahun_r();
		
		$kd_ta 		= $r['kd_r_ta']; #$CI->session->userdata('kd_ta');
		$kd_smt 	= $r['kd_r_smt']; #$CI->session->userdata('kd_smt');
		$kd_prodi 	= $CI->session->userdata('kd_prodi');

		if($kd_prodi == null){
			return null;
		}else{
			if($kd_smt == 1){
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta-1, $kd_smt, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				return $data[0]->T_SMT_AKT_2_F;
			}
			else{
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
				$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				return $data[0]->T_SMT_AKT_1_F;		
			}
		}
	}

	function generate_tahun_r(){
		$CI 	=& get_instance();
		$r_smt 	= $CI->session->userdata('r_smt');
		$r_ta 	= substr($CI->session->userdata('r_ta'),0,4);
		if($r_smt == 'GANJIL'){
			$kd_r_smt 	= 1;
			$kd_r_ta 	= $r_ta;
		}
		else{
			$kd_r_smt	= 2;
			$kd_r_ta	= $r_ta;		
		}
		return array('kd_r_ta' => $kd_r_ta, 'kd_r_smt'=>$kd_r_smt);
	}
	
	
	# GENERATE KODE TA DAN SMT
	function _generate_kd_ta($ta){
		return substr($ta, 0, 4);
	}
	
	function _generate_kd_smt($smt){
		if($smt == 'GENAP') return 2;
		else return 1;
	}
	
	function sekarang(){
		$nama_bulan = array(1=> 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
		return date('d').' '.$nama_bulan[(int)date('m')]. ' '.date('Y');
	}
	
	
	function tanggal_indo($date){
		$nama_bulan = array(1=> 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember');
		$tgl = explode('/', $date);
		return $tgl[0].' '.$nama_bulan[(int)$tgl[1]].' '.$tgl[2];
	}
	
	function _generate_tangal_cetak($ta, $smt){
		$CI =& get_instance();
		$ta_berjalan = $CI->session->userdata('ta_berjalan');
		$smt_berjalan = $CI->session->userdata('smt_berjalan');
		
		$kd_ta = $this->_generate_kd_ta($ta);
		$kd_smt = $this->_generate_kd_smt($smt);
		$kd_prodi = $CI->session->userdata('kd_prodi');
		
		
		# JIKA LAPORAN BEBAN KINERJA DOSEN MAKA TANGGALYANG TERCETAK ADALAH TANGGAL SKRG JIKA BELUM MELEBIHI TANGGAL BERAKHIRNYA SMT BERSANGKUTAN
		# JIKA MELEBIHI, MAKA YANG DICETAK ADALAH TANGGAL TERAKHIR SEMSTER BERSANGKUTAN.
		
		# MISAL TANGGAL SAAT INI > SEMESTER N-1, MAKA YANG TERCETAK ADALAH TANGGAL AKHIR SEMESTER N-1 (Akademik).
		# MISAL TANGGAL SAAT INI < SEMESTER N-1, MAKA YANG TERCETAK ADALAH TANGGAL SAAT INI.
		
		if($ta == $ta_berjalan && $smt == $smt_berjalan){
			$date = $this->sekarang();
		}
		else{
			$tahun = $kd_ta+1;
			if($kd_smt == 2){
				$date = '31 Agustus '.$tahun;
			}
			else{
				$date = '31 Januari '.$tahun;
			}
		}
		return $date;
	}
	
	function _cek_tgl($ta, $smt){
		$CI 		=& get_instance();
		$kd_ta = $this->_generate_kd_ta($ta);
		$kd_smt = $this->_generate_kd_smt($smt);
		$kd_prodi = $CI->session->userdata('kd_prodi');

		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>16001, 'api_subkode' => 3, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
		$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	function _get_history_pendidikan($kd_dosen, $kd_ta, $kd_smt){
		$CI =& get_instance();
		# API HISTORY PENDIDIKAN DOSEN
		
	}
	
	# FUNGSI GENERATE NIP DOSEN/PEGAWAI
	function _generate_nip($nip){
		$pnjNip = strlen($nip);
		if($pnjNip == 18){
			$tgl_lahir = substr($nip, 0,8);
			$bln_masuk = substr($nip, 8,6);
			$jk = substr($nip, 14,1);
			$no = substr($nip, 15,3);
			return $tgl_lahir.' '.$bln_masuk.' '.$jk.' '.$no;
		}else{
			return $nip;
		}
	}
	
	
	function _cek_jab_struk(){
		$CI =& get_instance();
		$kd = $CI->session->userdata('kd_dosen');
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' =>1121, 'api_subkode' => 5, 'api_search' => array($kd));
		$data = $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data;
	}
	

	# FUNGSI UNTUK NGECEK DAN MENGAMBIL JABATAN STRUKTURAL PEGAWAI
	# YANG DIGUNAKAN UNTUK DIMASUKKAN PADA BIDANG PENUNJANG DOSEN
	# ==========================================================================
	
	function _is_pejabat_penunjang_nt(){
		$CI 		=& get_instance();
		$kd 		= $CI->session->userdata('kd_dosen');
		$tgl 		= date('d-m-Y');
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' =>1121, 'api_subkode' => 3, 'api_search' => array($tgl, $kd, 1));
		$data = $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//print_r ($data);
		$value = array();
		//echo $data[count($data)-1]->STR_NAMA;
		if(!empty($data)){
			//$kd_jab 	= $data[count($data)-1]->STR_ID;
			//ini harusnya yang aktif, bukan  yang index 0 ; 
			foreach ($data as $d) {
				$kd_jab 	= $d->STR_ID;
				$api_url 	= URL_API_BKD.'bkd_dosen/jabatan';
				$parameter  = array('api_kode' =>20000, 'api_subkode' => 1, 'api_search' => array($kd_jab));
				$data2 = $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(!empty($data2)){
					//$value['nama_jabatan'] = $data[count($data)-1]->STR_NAMA;
					$value['nama_jabatan'] = $d->STR_NAMA;
					$value['sks_maks'] = $this->_get_sks_max($d->STR_ID);//$data2[0]->SKS_MAX;
					$value['bukti_penugasan'] = 'Surat Keputusan';
					$value['masa_penugasan'] = '1 Tahun';
					$value['tgl_mulai'] = $d->RR_TANGGAL_MULAI_F;
					$value['tgl_selesai'] = $d->RR_TANGGAL_AKHIR_F;
				}
			}
			
		}
		return $value;
		#return $data;
		
	}

	function _get_sks_max($kd_str){
		$CI =& get_instance();
		$CI->load->library('bkd_lib_sks_rule', '', 'sksrule');
		$sksmax = 0;
		
		$map = $CI->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_map_dosen_dt',
						'POST',
						array(
							'api_search' => array($kd_str)
						)
					); 

		if($map){
			$group = $map['MAP'];
			switch ($group) {
				case 'A':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,1); //REKTOR
					break;

				case 'B':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,2); //WAKIL REKTOR
					break;

				case 'C':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,2); //DEKAN
					break;

				case 'D':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,2); //DIREKTUR PASCASARJANA
					break;

				case 'E':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,3); //WAKIL DEKAN
					break;

				case 'F':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,4); //KETUA LEMBAGA
					break;

				case 'G':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,4); //KETUA UPT
					break;

				case 'H':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,4); //KETUA SPI
					break;

				case 'I':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,5); //KETUA PROGRAM STUDI
					break;

				case 'J':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,6); //SEKRETARIS PROGRAM STUDI
					break;

				case 'K':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,6); //SEKRETARIS LEMBAGA
					break;

				case 'L':
					$sksmax = $CI->sksrule->_nilai_sks(1,1011,7); //KEPALA PUSAT PADA LEMBAGA
					break;
				
				default:
					$sksmax = 0;
					break;
			}
		}


		return $sksmax;
	}
	
	
	
	
	
	
	
}










	
