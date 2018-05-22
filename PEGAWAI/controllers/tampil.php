<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tampil extends CI_Controller
{
	public function __construct()
	{
		parent::__construct();
		$this->simpeg = $this->load->library('libsimpeg');
	}
 
	function pegawai($nip){
		//echo 'hai';
		$data = $this->simpeg->kolega($nip);
		print_r($data);
	}

 
}