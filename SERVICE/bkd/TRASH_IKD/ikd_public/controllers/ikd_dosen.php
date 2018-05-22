<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi SIA
 * @subpackage  SIA Staff
 * @category    Master data (1)
 * @creator     Rischan Mafrur
 * @created     5-Juni-2013
*/
 
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
//require APPPATH.'/libraries/REST_Controller.php';
 
class Ikd_dosen extends CI_Controller
{
	protected $builtInMethods;
 
	public function __construct() {
		parent::__construct();
		$this->load->model('Ikd_dosen/mdl_ikd_dosen','mdl_1000');
	}
	
	function index() { echo 'Ikd_dosen'; }	
	
	function Coba_Ikd($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Coba_Ikd(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		

	}

	function Tampil_Data($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Tampil_Data($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		

	}
	function Tampil_Soal($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Tampil_Soal($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		

	}
	
	function Tampil_ByKatergori($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Tampil_ByKatergori($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Tampil_ByKatergoriBaru($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Tampil_ByKatergoriBaru($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Total_Soal($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Total_Soal($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	function Lihat_Soal_Quesioner($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_Soal_Quesioner($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	function Total_Lihat_Soal($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Total_Lihat_Soal($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	function Tampilkan_Soal($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Tampilkan_Soal(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Tampil_Tahun($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Tampil_Tahun($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Judul_MK($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Judul_MK($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Judul_MK_Soal($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Judul_MK_Soal($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Hitung_Hasil($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Hitung_Hasil($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	function Simpan_Hasil($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Simpan_Hasil($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
		
	}
	function Validasi_Kuesioner($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Validasi_Kuesioner($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	function Lihat_Nilai($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_Nilai($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function outValK3($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->outValK3($api_search[0],$api_search[1],$api_search[2],$api_search[3]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Lihat_Nilai_K3($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_Nilai_K3($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Isi_Kuesioner($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Isi_Kuesioner($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function outSumIn($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->outSumIn($api_search[0],$api_search[1],$api_search[2],$api_search[3]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Banyak_Kuliah($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Banyak_Kuliah($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Detail_Nilai($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Detail_Nilai($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Lihat_Nilai_Bykatergori($format = 'json')
	{
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_Nilai_Bykatergori($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}

	function Pengumpulan_Berkas($format = 'json'){	
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Pengumpulan_Berkas($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
					//case 1: $query = $this->mdl_1000->Pengumpulan_Berkas(); 
					//case 2: $query = $this->mdl_1000->Pengumpulan_Berkas_coba(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function Lihat_komen_mhs($format = 'json'){	
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_komen_mhs($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function outKomen($format = 'json'){	
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->outKomen($api_search[0],$api_search[1],$api_search[2],$api_search[3]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
            
    function outDetailJawaban($format = 'json'){	
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				case 1: $query = $this->mdl_1000->outDetailJawaban($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
				case 2: $query = $this->mdl_1000->outDetailJawaban_pivot($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
				default: $query = array(); break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
            
    function Lihat_Tanggal_Ujian($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_Tanggal_Ujian($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    function Lihat_Tanggal_Pengumpulan($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_Tanggal_Pengumpulan($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); 
					//case 1: $query = $this->mdl_1000->Lihat_Tanggal_Pengumpulan_coba(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
    function Lihat_hari_lbur($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_hari_lbur(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function cek_pengumpulan_berkas($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cek_pengumpulan_berkas($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
    function Update_Tgl_Pengumpulan($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Update_Tgl_Pengumpulan($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
    function Insert_Tgl_Pengumpulan($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Insert_Tgl_Pengumpulan($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function delete_Tgl_Pengumpulan_Coba($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->delete_Tgl_Pengumpulan_Coba(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
}
?>