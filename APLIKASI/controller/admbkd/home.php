<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Home extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}
	
	function index(){

		$kd_smt = $this->session->userdata('kd_smt');
		# set session semester
		if($kd_smt == 2) $this->session->set_userdata('smt','GENAP');
		else $this->session->set_userdata('smt','GANJIL');
		
		# HILANGKAN SESSION YANG NGGAK GUE PENGEN
		$this->session->unset_userdata('adm_univ');
		#$this->session->unset_userdata('adm_fak');
		$this->session->unset_userdata('adm_pro');
		
		#print_r($this->session->all_userdata());
		
		$data['ta'] = $this->session->userdata('ta');
		#$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/home', $data);
		
	
	}
	
	function test(){
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array('api_kode' => 89020, 'api_subkode' => 10, 'api_search' => array('06'));
		$data['dekan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	function cek(){
		$kd_dosen = '197903262006042002';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/publikasi';
		$parameter  = array('api_kode'=>9900, 'api_subkode' => 2, 'api_search' => array($kd_dosen));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
		
	}
	
	function cekPD(){
		$kd_dosen = '198012232009011007';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/pendidikan';
		$parameter  = array('api_kode'=>1000, 'api_subkode' => 1, 'api_search' => array($kd_dosen));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	/* file location : ../application/controller/ADMBKD/home.php
	 * last modified : 13/November/2013
	 * By : Sabbana 
	 */

}