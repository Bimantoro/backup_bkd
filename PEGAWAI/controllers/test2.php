<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Test2 extends CI_Controller {

	public $data 	= 	array();
	
	public function __construct() {
		
		parent::__construct();
		#$this->load->helper('auth');
		#is_logged_in();
		$this->load->helper(array('form','url', 'text_helper','date','smiley'));
		$this->load->library(array('table','Pagination','image_lib'));
		$this->load->library('breadcrumb');
		$this->load->library('session');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->session->set_userdata('app','skripsimhs');
		// if($this->session->userdata('id_user') == '') redirect(base_url());
		
		$this->load->library('webserv');
		$this->load->library('pagination');
		$this->load->helper('ckeditor');
        $this->url = $this->load->library('lib_url');
        $this->user = $this->load->library('lib_user', $this->session->all_userdata());
	}
	
	function index2()
	{
		echo"sada";
		//$data= $this->webserv->remun_dosen('agregasi');
		//print_r($data)
		
	}
		function index($page=0)
	{
		if(!$page):
		$offset = 0;
		else:
		$offset = $page;
		endif;
		$per_page=10;
		$cfg=$this->webserv->pegawai('agenda/total_row');
		$config['base_url'] = site_url('blog/agenda/index/');
		$config['total_rows'] = $cfg->TOTAL;
		$config['per_page'] = $per_page;
		$config['uri_segment'] = 4;
		$this->pagination->initialize($config);
		$limit=$offset+$per_page;
		$data['offset']=$offset;
		$data['agenda'] = $this->webserv->pegawai('agenda/agenda_list',array('LIMIT'=>$limit,'OFFSET'=>$offset));
		$this->output99->output_display('blog/agenda/agenda_view', $data);
	}
	
}
