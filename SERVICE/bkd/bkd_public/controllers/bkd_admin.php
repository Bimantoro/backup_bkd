<?php if (!defined('BASEPATH')) exit ('Uukkh... Kamu kok nakal banget sich...');
 
class Bkd_admin extends CI_Controller
{
	protected $builtInMethods;
 
	public function __construct() {
		parent::__construct();
		$this->load->model('bkd_dosen/mdl_kompilasi');
	}
	
	function index(){
		echo "BKD ADMIN";
	}	
	
	function verifikasi($format =  'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_kompilasi->mdl_dosen_diasesori($api_search[0], $api_search[1], $api_search[2]); break;
			}break;
			case 1001: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_kompilasi->mdl_update_status_kinerja($api_search[0], $api_search[1], $api_search[2]); break;
				case 2	: $query = $this->mdl_kompilasi->mdl_update_status_rencana($api_search[0], $api_search[1], $api_search[2]); break;
			}break; 
		}
		$this->sia_api_lib_format->output($query, $format);		
	}


}
?>