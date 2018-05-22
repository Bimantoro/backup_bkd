<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Bkd_lib_history {
	
	# CEK JABATAN STRUKTURAL DOSEN 
	# =================================================================
	
	function _cek_jab_struk($kd_dosen, $kd_ta, $kd_smt){
		$CI 		=& get_instance();
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' =>1121, 'api_subkode' => 5, 'api_search' => array($kd_dosen));
		$data = $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#return $data; die();
		$tgl_dok = $this->tanggal_akhir_ta_default($kd_ta, $kd_smt);
		$status = 0;
		foreach ($data as $jab){
			if($jab->RR_STATUS == 1 || $jab->RR_TANGGAL_AKHIR_F == ''){
				$status = 1;
				break;
			}
			
			if($jab->RR_TANGGAL_MULAI !== '' && $jab->RR_TANGGAL_MULAI > $tgl_dok){
				$status = 0;
				break;
			}
		}
		return $status;
	}
	
	
	# CEK JABATAN FUNGSIONAL DOSEN
	# ============================================================================================================
	function _cek_jab_fungsional($kd_dosen, $kd_ta, $kd_smt){
		$CI =& get_instance();
		$api_url_jabatan 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 1122, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		$data = $CI->s00_lib_api->get_api_jsob($api_url_jabatan,'POST',$parameter);
		if(empty($data)){
			$status = 0;
		}else{
			$fung = strtolower($data[0]->FUN_NAMA);
			if($fung == 'guru besar'){
				$status = 1;
			}else{
				$status = 0;
			}
			# sementara di 0 kan. bisa dosen bisa gubes array('status_dosen'=>'DT', 'tanggal'=>'');
			# status gubes = 1, status selain gubes = 0
			# belum ada data => asumsikan dosen 
			# intine (P = 1) dan (D = 0)
		}
		return $status;
		
	}
	
	# SESSION STATUS DOSEN
	# ============================================================================================================
	function _status_DS($kd_dosen, $kd_ta, $kd_smt){
		$struk = $this->_cek_jab_struk($kd_dosen, $kd_ta, $kd_smt);
		$fungs = $this->_cek_jab_fungsional($kd_dosen, $kd_ta, $kd_smt);
		if ($struk == 1){
			if($fungs == 1) $status = 'PT';
			else $status = 'DT';
		}else{
			if($fungs == 1) $status = 'PR';
			else $status = 'DS';
		}
		return $status;
	}
	
	
	# TANGGAL AKHIR TA DEFAULT (SEBAGAIMANA UMUMNYA)
	# ===========================================================
	function tanggal_akhir_ta_default($kd_ta, $kd_smt){
		$tahun = $kd_ta + 1;
		if($kd_ta == 1){
			return "31-01-".$tahun;
		}
		else{
			return "31-08-".$tahun;
		}
	}
	
	/* ----------------##########----------------- */
	# SEKENARIO MASALAH HISTORY JABATAN DAN PENDIDIKAN
	# JABATAN :
		# AMBIL SEMUA JABATAN YANG PERNAH ADA PADA PEGAWAI/DOSEN.
		# JIKA TANGGAL AWAL JABATAN KOSONG
		
		
	# SET SESSION JENIS DOSEN PADA SEMESTER BERSANGKUTAN
	# ===========================================================
	/*
		1. Ambil tanggal mulai dan tanggal selesai semester ini
		2. Ambil jabatan dosen (status dan akhir tgl jabatan terakhir) 
			a. Jika 
		3. 
	*/
	
	function tanggal_semester_aktif(){
		$CI		=& get_instance();
		$kd_ta 	= $CI->session->userdata('kd_ta');
		$kd_smt = $CI->session->userdata('kd_smt');
		$kd_prodi = $CI->session->userdata('kd_prodi');
		
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
		$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$tgl_smt = array(
			'mulai'=> $data[0]->T_SMT_AKT_1_F,
			'selesai'=> $data[0]->T_SMT_AKT_2_F
		);
		return $tgl_smt;
	}

	function session_status_jabatan(){
		return $this->tanggal_semester_aktif();
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
}


