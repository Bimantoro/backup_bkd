<?php

class Adminiku extends CI_Controller {

    function __construct() {
        parent::__construct();
		//require_once 'includes/PHPExcel/PHPExcel.php';
		//require_once 'includes/excel_reader/excel_reader.php';
        $this->view = $this->s00_lib_output;
        $this->api = $this->load->library('s00_lib_api');
        $this->url = $this->load->library('lib_url');
        $this->user = $this->load->library('lib_user', $this->session->all_userdata());
        $this->util = $this->load->library('lib_util');
        $this->lib_skp = $this->load->library('lib_skp');
        $this->lib_remun = $this->load->library('lib_remun');
        $this->simpeg = $this->load->module('remunerasi/simpeg');
        $this->penilaian = $this->load->module('remunerasi/penilaian');

        $this->session->set_userdata('app', 'iku_admin_app');
		

        if (!$this->user->is_login() || !$this->user->is_skp_admin())
            redirect();
    }

    function index() { 
        redirect();
    }

	function uraian(){
		$kd_eselon		= $this->uri->segment(4);
		$parameter 		= array('f'=> $kd_eselon);
		$data['kode']	= $kd_eselon;
		$data['uraian']	= $this->api->post($this->url->remun . '/uraian_iku', $parameter);
		$data['jabatan']= $this->api->post($this->url->remun . '/jabatan_iku');
        $this->view->output_display('admin/daftar_iku', $data);
	}
	
	function tambah_uraian(){
        $this->view->output_display('admin/form_tambah_iku', $data);
	}
	
	function simpan_uraian(){
		$kd_jabatan = $this->input->post('kd_jabatan');
		$grup 		= $this->input->post('grup');
		$aspek 		= $this->input->post('aspek');
		$uraian 	= $this->input->post('uraian');
		$data		= array(
						'kd_jabatan'=> $kd_jabatan,
						'grup'		=> $grup,
						'aspek'		=> $aspek,
						'uraian'	=> $uraian
						);
		$this->util->pre($this->api->post($this->url->remun . '/simpan_iku_eselon', $data));
	}
	
	function hapus_iku(){
		$this->util->pre($this->api->post($this->url->remun . '/hapus_uraian_iku', $_POST));
	}
	
	function jabatan(){
		$data['jabatan']= $this->api->post($this->url->remun . '/jabatan_iku');
		print_r($data);
	}
	
	function daftar_jabatan(){
		$jabt =  array ('654321BI', 'ST000329');
		$parameter 	= array('api_kode' => 1101, 'api_subkode' => 1, 'api_search' => array('654321BI', 'ST000329'));
		$jabatan 	= $this->api->post($this->url->simpeg_search, $parameter);
		print_r($jabatan);
	}
	
	function uraian_ik(){
		$parameter 		= array('f'=> '654321BI');
		$data['uraian']	= $this->api->post($this->url->remun . '/uraian_iku', $parameter);
		print_r($data);
	}
}
?>
