<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Rbkd_lib_setting {

# dispensasi pengisian Rencana Beban Kerja Dosen
/* --------------------------------------------------------
1. Dispensasi pengisian RBKD semester lalu adalah tanggal akhir semester lalu + 90 hari (default dari akademik)
2. 	API ambil tanggal akhir semester lalu, 
	dengan parameter (kd_dosen, kd_ta, kd_smt);
*/
	function _generate_smt_lalu(){
		$CI =& get_instance();
		$r_smt 	= $CI->session->userdata('r_smt');
		$r_ta 	= $CI->session->userdata('r_ta');
		$r_tahun = substr($r_ta,0,4);
		
		if($r_smt == 'GANJIL'){
			$kd_smt = 2;
			$kd_ta  = $r_tahun-1;
		}
		else{
			$kd_smt = 1;
			$kd_ta  = $r_tahun;
		}
		return array('kd_r_ta'=>$kd_ta, 'kd_r_smt' => $kd_smt);

	}

	function _tangal_akhir_semester(){
		$CI			=& get_instance();
		$kd 		= $this->_generate_smt_lalu();
		$kd_ta 		= $kd['kd_r_ta'];
		$kd_smt 	= $kd['kd_r_smt'];
		$kd_prodi 	= $CI->session->userdata('kd_prodi');
		
		if($kd_prodi == null){
			return null;
		}else{
			if($kd_smt == 1){
				$api_url 	= URL_API_SIA.'sia_master/data_search';
				$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
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

	private function _dispen_hari(){
		$CI =& get_instance();
		$api_url 	= URL_API_SIA.'sia_sistem/data_view';
		$parameter  = array('api_kode' =>200002, 'api_subkode' => 1, 'api_search' => array());
		$data 		= $CI->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data[0]->KADALUWARSA_HARI1;
	}


	function _is_enabled(){
		$CI	=& get_instance();
		
		$kd_ta = $CI->sesion>userdata('kd_ta');
		$kd_smt = $CI->sesion>userdata('kd_smt');
		
		
		
		
		
	}

}