<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi SIA
 * @subpackage  IKD Admin
 * @category    ikd
 * @recoded 	10 Juni 2013, Rischan Mafrur
*/
 
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
//require APPPATH.'/libraries/REST_Controller.php';
 
class Ikd_admin extends CI_Controller
{
	protected $builtInMethods;
 
	public function __construct() {
		parent::__construct();
		$this->load->model('ikd_admin/mdl_ikd_admin','mdl_1000');
	}
	
	function index() { echo 'ikd_admin'; }	
	
	function capcus($format = 'json'){
		/* $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);		
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: 
						$query = $this->mdl_1000->mdl_skr_data_mahasiswa_aktif($api_search[0]); 
				break; 
			} 
		break;		
		} */
		#$query = $this->mdl_1000->woiiii('197903312005011004'); 
		$query = $this->mdl_1000->capcus();
		#$query 	= $this->mdl_1000->gunaguna(); 
		$this->sia_api_lib_format->output($query, $format);		
	}
		
	function cek_v_jadwal($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					#case 1: $query = $this->mdl_1000->ambil_soal(); , KD_RUANG
					case 1: $query = $this->db->query("SELECT KD_KELAS, KD_MK, NM_MK, TGL, KD_TA, KD_SMT FROM SIA.V_UJIAN WHERE KD_KELAS = '".$api_search[0]."' AND UPPER(NM_J_UJIAN) = 'UAS' ")->result_array(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function ambil_soal($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				#case 1: $query = $this->mdl_1000->ambil_soal(); break;
				case 2: $query = $this->mdl_1000->ambil_soal_baru(); break;
				case 3: $query = $this->mdl_1000->ambil_soal_baru_det($api_search[0]); break;
				case 4: $query = $this->mdl_1000->ambil_idmax_soal_baru(); break;
				case 551: $query = $this->mdl_1000->ubah_soal_baru($api_search[0],$api_search[1],$api_search[2]); break;
				case 552: $query = $this->mdl_1000->ubah_point_soal_baru($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
				case 111: $query = $this->mdl_1000->insert_soal_baru($api_search[0],$api_search[1]); break;
				case 112: $query = $this->mdl_1000->insert_point_soal_baru($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
				case 991: $query = $this->mdl_1000->hapus_soal_baru($api_search[0]); break;
				default: $query = array(); break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function cek_prodi($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cek_prodi($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function data_dosen($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->data_dosen($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function cekkuesionermhs($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cekkuesionermhs($api_search[0],$api_search[1],$api_search[2],$api_search[3]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function insertkuesionerman($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->insertkuesionerman($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function ambil_detail($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->ambil_detail($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function ambil_detail_tanggal($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->ambil_detail_tanggal($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Update_Soal($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Update_Soal($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Update_Tanggal_Libur($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Update_Tanggal_Libur($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Cek_Hari_Libur($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Cek_Hari_Libur($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Insert_Soal($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Insert_Soal($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Insert_Jabatan($format = 'json') {
    	$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Insert_Jabatan($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function hapusjabatan($format = 'json') {
    	$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->hapusjabatan($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function hapustanggallibur($format = 'json') {
    	$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->hapustanggallibur($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Insert_Tanggal_Libur($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Insert_Tanggal_Libur($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Tampil_Hari_Libur($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Tampil_Hari_Libur(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Jumlah_Hari_Libur($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Jumlah_Hari_Libur(); 
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

    function soal_kuesioner($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->soal_kuesioner(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function jum_soal($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->jum_soal(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function cek_hasil_pencarian($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cek_hasil_pencarian($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function cek_data_jabatan($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cek_data_jabatan($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Hasil_pencarian($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Hasil_pencarian($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function OutProdiDosen($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->OutProdiDosen($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function cek_dosen_lengkap($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cek_dosen_lengkap($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function cek_jabatan_dosen($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cek_jabatan_dosen($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

   

    function hasil_rekap($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->hasil_rekap($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Tampil_Tahun($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Tampil_Tahun($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    function tahun($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->tahun(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    } 
	
	function detTa($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->detTa($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function detSmt($format = 'json') {
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->detSmt($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	
     
	function detailprodi($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->detailprodi($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function detailprodik($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->detailprodik(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function detailfakultas($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->detailfakultas($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function detailfakultask($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->detailfakultask(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Tampil_ByKatergoriBaru($format = 'json') {
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

    function printberkasdosen($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->printberkasdosen($api_search[0],$api_search[1],$api_search[2]);
					case 2: $query = $this->mdl_1000->printberkasdosen2($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function outdataprodi($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->outdataprodi($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
    function printberkasprodi($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->printberkasprodi($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function cekrekapprodi($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cekrekapprodi($api_search[0],$api_search[1],$api_search[2],$api_search[3]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function cekrekapfak($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cekrekapfak($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function insertrekapprodi($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->insertrekapprodi($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11]); break; 
					case 2: $query = $this->mdl_1000->updaterekapprodi($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11]); break; 
			} 
		break;		
		}
		#print_r($query);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function insertrekapfak($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->insertrekapfak($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11]); break; 
					case 2: $query = $this->mdl_1000->updaterekapfak($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10]); break; 
			} 
		break;		
		}
		#print_r($query);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function printberkasfakultas($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->printberkasfakultas($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
    function DataBerkasDosen($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->DataBerkasDosen($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Lihat_Nilai($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Lihat_Nilai($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
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
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Pengumpulan_Berkas($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Pengumpulan_Berkas($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Banyak_Kuliah($format = 'json') {
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

    function Judul_MK($format = 'json') {
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

    function Update_Content($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Update_Content($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Dekan($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Dekan($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
    function tampilta($format = 'json'){
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->tampilta($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
    function tampilsmt($format = 'json'){
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->tampilsmt($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
    function tampilprodi($format = 'json'){
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->tampilprodi($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
	function tampilfakultas($format = 'json'){
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->tampilfakultas($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
    function Kaprodi($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Kaprodi($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Data_jabatan($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Data_jabatan($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
    
    function Data_jabatan_struktural_dosen($format = 'json'){
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Data_jabatan_struktural_dosen(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Data_jabatan_struktural($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Data_jabatan_struktural($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Cek_jabatan_D($format = 'json') {
       $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Cek_jabatan_D($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Data_fakultas($format = 'json') {
        $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Data_fakultas(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Cek_waktu_kuesioner($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Cek_waktu_kuesioner(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Insert_waktu($format = 'json') {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Insert_waktu($api_search[0],$api_search[1]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Update_waktu($id, $tgl_awal, $tgl_akhir) {
      $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->Update_waktu($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }

    function Tampilkan_Soal($format = 'json') {
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
	
	function GET_NIP($format = 'json') {
     $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				case 1: $query = $this->mdl_1000->GET_NIP(); break;
				case 2: $query = $this->mdl_1000->CHANGE_NIP($api_search[0],$api_search[1]); break;
				default: $query = array(); break;
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function kepuasan($format = 'json') {
     $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				case 1: $query = $this->mdl_1000->prosentase_kepuasan($api_search[0],$api_search[1]); break;
				case 2: $query = $this->mdl_1000->prosentase_kepuasan_arr($api_search[0],$api_search[1]); break;
				case 3: $query = $this->mdl_1000->prosentase_kepuasan_harap($api_search[0],$api_search[1],$api_search[2]); break;
				case 4: $query = $this->mdl_1000->prosentase_kepuasan_nyata($api_search[0],$api_search[1],$api_search[2]); break;
				case 5: $query = $this->mdl_1000->prosentase_dum_nim($api_search[0],$api_search[1]); break;
				case 6: $query = $this->mdl_1000->get_arr_kat(); break;
				case 7: $query = $this->mdl_1000->cek_id_tany_on_p($api_search[0],$api_search[1],$api_search[2]); break;
				case 8: $query = $this->mdl_1000->get_kat_soal_id($api_search[0],$api_search[1],$api_search[2]); break;
				case 9: $query = $this->mdl_1000->get_tany_soal_id($api_search[0],$api_search[1],$api_search[2]); break;
				case 10: $query = $this->mdl_1000->cek_id_kat_on_p($api_search[0],$api_search[1]); break;
				case 11: $query = $this->mdl_1000->get_max_id_kat(); break;
				case 12: $query = $this->mdl_1000->get_arr_kat2($api_search[0],$api_search[1]); break;
				case 13: $query = $this->mdl_1000->prosentase_kepuasan2($api_search[0],$api_search[1]); break;
				case 111: $query = $this->mdl_1000->in_kat_per_x($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
				case 112: $query = $this->mdl_1000->in_soal_per_x($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); break;
				case 551: $query = $this->mdl_1000->e_kat_soal($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); break;
				case 552: $query = $this->mdl_1000->e_tany_soal($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); break;
				case 553: $query = $this->mdl_1000->e_point_puas($api_search[0]); break;
				case 991: $query = $this->mdl_1000->del_kat_soal($api_search[0],$api_search[1],$api_search[2]); break;
				case 992: $query = $this->mdl_1000->del_tany_soal($api_search[0],$api_search[1],$api_search[2]); break;
				default: $query = array(); break;
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
    }
}
?>