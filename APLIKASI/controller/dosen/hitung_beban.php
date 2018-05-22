<?php if (!defined('BASEPATH')) exit ('No direct access script allowed.');

class Hitung_beban extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
	}
	
	
	function index(){
		echo "Proses Perhitungan Beban Kerja Dosen";
	}
	
	
	function data_bimbingan(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('smt');
		$ta = $this->session->userdata('ta');
		$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
		$parameter  = array(
						'api_kode' 		=> 28000,
						'api_subkode' 	=> 2,
						'api_search'	=> array($kd_dosen)
					);
		$data['bimbingan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$data['jml_mhs'] = 0;
		$data['mahasiswa'] = array();
		$no=0;
		foreach ($data['bimbingan'] as $bimbingan){
			$no++;
			$data['jml_mhs'] = $data['jml_mhs']+1;
			$data['mahasiswa'][$no] = $bimbingan->NAMA;
		}
		
		print_r($data['mahasiswa']);
		
	}
	
	function data_penawaran_kelas(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('kd_smt');
		$ta = $this->session->userdata('kd_ta');
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array(
						'api_kode' 		=> 58000,
						'api_subkode' 	=> 32,
						'api_search' 	=> array($ta, $smt , $kd_dosen)
					);
		$data['penawaran'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$a = 0;
		foreach ($data['penawaran'] as $x){
			# sia_kurikulum/data_search, 40000/19, api_search = array($prodi, $kd_kur, $kd_mk)
			$api_url 	= URL_API_SIA.'sia_kurikulum/data_search';
			$parameter  = array(
							'api_kode' 		=> 40000,
							'api_subkode' 	=> 19,
							'api_search' 	=> array($x->KD_PRODI, $x->KD_KUR , $x->KD_MK)
						);
			$data['jenjang'][$a] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			$a++;
		}
		print_r($data);
	}
	
	function data_kaprodi(){
		$kd_prodi 	= $this->session->userdata('kd_prodi'); 
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array(
						'api_kode' 		=> 89020,
						'api_subkode' 	=> 9,
						'api_search' 	=> array($kd_prodi)
					);
		$data['kaprodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data['kaprodi']);
	
	}
	
	function data_dpl(){
		$data= $this->mdl_bkd->get_api_kkn(
				'kkn_admin/ambil_dpl_kkn',
				'json',
				'POST',
				array(  'api_kode' => 1000,
						'api_subkode' => 1,
						'api_search' => array())
		);
		print_r($data);
	}
	
	function cek_dosen_dpl(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$data= $this->mdl_bkd->get_api_kkn(
				'kkn_admin/cek_dpl_kkn',
				'json',
				'POST',
				array(  'api_kode' => 1000,
						'api_subkode' => 1,
						'api_search' => array($kd_dosen))
		);
		print_r($data);
	}
	
	function cek_dosen_dpa(){
		#'sia_mahasiswa/data_search', 28000/3, api_search = array(kd_dosen)
		$kd_dosen = $this->session->userdata('kd_dosen');
		$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
		$parameter  = array(
						'api_kode' 		=> 28000,
						'api_subkode' 	=> 3,
						'api_search' 	=> array($kd_dosen)
					);
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	
	}
	
	function cek_mhs_bimbingan(){
		#'sia_mahasiswa/­data_search', 28000/2, api_search = array(kd_dosen)
		$kd_dosen = $this->session->userdata('kd_dosen');
		$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
		$parameter  = array(
						'api_kode' 		=> 28000,
						'api_subkode' 	=> 2,
						'api_search' 	=> array($kd_dosen)
					);
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	
}