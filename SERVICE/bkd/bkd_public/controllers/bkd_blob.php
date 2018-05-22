/**/
<?php 

class Bkd_blob extends CI_Controller{
	
	protected $builtInMethods;

	function __construct(){
		parent::__construct();
		$this->load->model('bkd_dosen/mdl_blob');
	}
	
	function dokumen($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);		
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1500: switch($subkode){
				default: 
				case 1: $query = $this->mdl_blob->getBlob($api_search[0], $api_search[1], $api_search[2]);break;
				case 2: $query = $this->mdl_blob->insertBlob($api_search[0], $api_search[1], $api_search[2], $api_search[3]);break;
			} 
			break;				
		}
		$this->sia_api_lib_format->output($query, $format);	
	}
	
	function file($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);		
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1: $query = $this->mdl_blob->getExtensi($api_search[0], $api_search[1], $api_search[2]);break;
			} 
			break;				
		}
		$this->sia_api_lib_format->output($query, $format);	
	}
	
	
	
}