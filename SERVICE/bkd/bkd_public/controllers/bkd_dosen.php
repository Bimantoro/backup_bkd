<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi SIA
 * @subpackage  BKD
 * @category    bkd
 * @recoded 	10 Juni 2013, 
*/
 
 
class Bkd_dosen extends CI_Controller
{
	protected $builtInMethods;
 
	public function __construct() {
		parent::__construct();
		$this->load->model('bkd_dosen/mdl_bkd_dosen');
	}
	
	function index() { echo 'BKD Dosen'; }	
		
		
	function create_session($format = 'json') {
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->create_session($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
		
    }
	
	# cek dosen bkd
	function cek_dosen_bkd($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->cek_dosen_bkd($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	# simpan dosen ke bkd dosen
	function simpan_data_dosen($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query 		= $this->mdl_bkd_dosen->simpan_bkd_dosen($api_search[0],$api_search[1],$api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function get_data_dosen($format = 'json') {
		$parameter 	= $this->input->post('data_search');		
		$query = $this->mdl_bkd_dosen->get_data_dosen($parameter[0]);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function cek_asdos_semester($format = 'json') {
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->cek_asdos_semester($parameter[0],$parameter[1],$parameter[2]);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	
	function get_email_dosen($format = 'json') {
		$parameter 	= $this->input->post('data_search');		
		$query = $this->mdl_bkd_dosen->get_email_dosen($parameter[0]);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	
	function get_dekan_fakultas($format = 'json') {
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->get_dekan_fakultas($parameter[0]);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function generate_nama($format = 'json') {
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->generate_nama($parameter[0]);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	# data semua pt 
	function data_pt($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->data_pt();
		$this->sia_api_lib_format->output($query, $format);
	}
		
	function data_fakultas($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->data_fakultas();
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function data_prodi($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->data_prodi();
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function get_nama_prodi($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->get_nama_prodi($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function data_jabatan($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->data_jabatan();
		$this->sia_api_lib_format->output($query, $format);
	}

	function data_golongan($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->data_golongan();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_bkd_dosen($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->get_bkd_dosen($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	
	# asesor
	function get_nama_asesor($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->get_nama_asesor($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function simpan_asdos($format = 'json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_dosen->simpan_asesor_dosen($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]);
		$this->sia_api_lib_format->output($query, $format);
	}

	
	
	# update biodata dosen
	# update data bkd dosen
	# update data md_dosen
	# update bkd_asesor_dosen
	#======================================================================
	function update_identitas_dosen($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->update_identitas_dosen($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11]);
		$this->sia_api_lib_format->output($query, $format);		
	}
	function update_biografi_dosen($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->update_biografi_dosen($api_search);
		$this->sia_api_lib_format->output($api_search, $format);		
	}
	
	function update_identitas_bkd($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->update_identitas_bkd($api_search[0],$api_search[1],$api_search[2]);
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function update_kepegawaian_dosen($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->update_kepegawaian_dosen($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6]);
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function update_akademik_dosen($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->update_akademik_dosen($api_search[0],$api_search[1],$api_search[2]);
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	
	function update_md_dosen($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->update_md_dosen($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9]);
		$this->sia_api_lib_format->output($query, $format);		
	}

	function update_asesor_dosen($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->update_asesor_dosen($api_search[0],$api_search[1],$api_search[2]);
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function get_all_dosen($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->get_all_dosen();
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	
	# asesor
	# register 
	# insert 
	# update
	# delete
	
	function data_asesor($format = 'json'){
		$param		= $this->input->post('data_search');
		$query		= $this->mdl_bkd_dosen->data_asesor();
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function data_asesor_prodi($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->data_asesor_prodi($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function cari_data_asesor_prodi($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->cari_data_asesor_prodi($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function insert_asesor($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->simpan_asesor($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6]);
		$this->sia_api_lib_format->output($query, $format);			
	}

	function update_asesor($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->update_data_asesor($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6]);
		$this->sia_api_lib_format->output($query, $format);			
	}
	
	function asesor_dosen($format = 'json') {
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->asesor_dosen($parameter[0],$parameter[1],$parameter[2]);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	function biodata_asesor($format = 'json') {
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->biodata_asesor($parameter[0]);
		$this->sia_api_lib_format->output($query, $format);		
    }

	function current_asesor($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->biodata_asesor($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);			
	}
	function hapus_asesor($format = 'json'){
		$api_search	= $this->input->post('api_search');
		$query		= $this->mdl_bkd_dosen->delete_asesor($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);			
	}
	
	#searching
	function cari_dosen($format = 'json') {
		$api_search	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->cari_dosen($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);		
    }
	
	#limit query
	function dosen_limit($format = 'json') {
		$api_search	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->get_dosen_limit($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);		
    }

	function asesor_limit($format = 'json') {
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->get_data_asesor_limit($parameter[0],$parameter[1]);
		$this->sia_api_lib_format->output($query, $format);		
    }

	#count data dosen
	function count_dosen($format = 'json') {
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->tot_record('BKD.BKD_DOSEN');
		$this->sia_api_lib_format->output($query, $format);
    }
	#count data asesor
	function count_asesor($format = 'json') {
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->tot_record('BKD.BKD_ASESOR');
		$this->sia_api_lib_format->output($query, $format);
    }
	

	
	# JENJANG PRNDIDIKAN DOSEN 
	function simpan_riwayat_pendidikan($format = 'json') {
		$api_search	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->simpan_riwayat_pendidikan($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4],$api_search[5], $api_search[6], $api_search[7], $api_search[8],$api_search[9]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function update_riwayat_pendidikan($format = 'json'){
		$api_search 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->update_riwayat_pendidikan($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4],$api_search[5], $api_search[6], $api_search[7], $api_search[8],$api_search[9]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function delete_riwayat_pendidikan($format = 'json'){
		$api_search 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->delete_riwayat_pendidikan($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	/* function riwayat_pendidikan_dosen($format = 'json'){
		$api_search 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->riwayat_pendidikan_dosen($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    } */
	
	function riwayat_pendidikan_dosen($format = 'json'){
		$parameter 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->riwayat_pendidikan_dosen($parameter[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function current_riwayat_pendidikan($format = 'json'){
		$api_search 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->current_riwayat_pendidikan($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	# MERGE APLKASI DENGAN SIPKD (SISTEM INFORMASI PENGEMBANGAN KARIR DOSEN) KAYA DIKTIS
	# TAMBAHAN, DATA KEPEGAWAIAN
	# DATA RIWAYAT PENDIDIKAN
	
	function update_fak_dosen($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->update_fak_dosen($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function cek_kompilasi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query = $this->mdl_bkd_dosen->cek_kompilasi($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function pendidikan_dosen($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_dosen->get_pendidikan($api_search[0], $api_search[1]); break; 
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function partner($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 11000: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_dosen->get_partner($api_search[0], $api_search[1]); break; 
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function dosen($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 9999: switch($subkode){
				default: 
				case 9: $query = $this->mdl_bkd_dosen->get_data_dosen($api_search[0]); break; 
				case 100: $query = $this->mdl_bkd_dosen->create_session($api_search[0]); break; 
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function asesor($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 7777: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_dosen->data_asesor_limit($api_search[0], $api_search[1]); break; 
				case 100: $query = $this->mdl_bkd_dosen->total_asesor_pt(); break; 
			}break;
			case 8888: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_dosen->data_asesor_prodi_limit($api_search[0], $api_search[1], $api_search[2]); break; 
				case 100: $query = $this->mdl_bkd_dosen->total_asesor_prodi($api_search[0]); break; 
			}break;
			case 9999: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_dosen->cari_nama_asesor($api_search[0]); break; 
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	# API JABATAN 
	# ====================================================================================
	
	function jabatan($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 20000: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_dosen->get_sks_maks_jabatan($api_search[0]); break; 
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}

	function ttt($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_dosen->rr(); break; 
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}

	function cv_dosen($kd_dosen=""){
		$d = $this->mdl_bkd_dosen->get_cv_dosen($kd_dosen);
		$data['NAMA_FILE_CV']=$d['NAMA_FILE_CV'];
		$data['FILE_CV']=base64_encode($d['FILE_CV']->load());
		$this->sia_api_lib_format->output($data, 'json');		
	}	
}
?>