<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi SIA
 * @subpackage  BKD
 * @category    bkd
 * @recoded 	10 Juni 2013, 
*/
 
 
class Bkd_remun extends CI_Controller
{
	protected $builtInMethods;
 
	public function __construct() {
		parent::__construct();
		$this->load->model('bkd_dosen/mdl_bkd_remun');
	}
	
	function index(){ echo 'BKD Remun'; }	
		
	
	function data_remun($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->data_remun($api_search[0],$api_search[1],$api_search[2],$api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function get_jadwal($kd_dosen=""){
		$query = $this->db->query("SELECT * FROM JADWAL_PENGISIAN WHERE SYSDATE BETWEEN TGL_MULAI AND TGL_SELESAI")->result_array();
		$this->sia_api_lib_format->output($query, 'json');		
	}
	
	function current_data_bkd($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->get_data($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function tingkat($format =  'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_remun->tingkatKegiatan(); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}

	function get_kategori($format = 'json') {
		$api_search= $this->input->post('api_search');		
		$query= $this->mdl_bkd_remun->get_kategori($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function get_kategori_remun($format = 'json') {
		$api_search= $this->input->post('api_search');		
		$query= $this->mdl_bkd_remun->get_kategori_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function get_jenis_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->get_jenis_haki();
		$this->sia_api_lib_format->output($query, $format);
    }
    //insert beban kerja
	function simpan_remun($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->simpan_remun($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12],$api_search[13],$api_search[14],$api_search[15],$api_search[16],$api_search[17]);
		$this->sia_api_lib_format->output($query, $format); 
	}
	function get_current_data_tersimpan($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->get_current_data_tersimpan($api_search[0],$api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
    }
    
    function simpan_data_pendidikan($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->simpan_data_pendidikan($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function simpan_data_penelitian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->simpan_data_penelitian($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function simpan_data_penunjang($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->simpan_data_penunjang($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    # PARTNER 
	function partner_kinerja($format =  'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1800: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_remun->simpan_partner($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
				case 99: $query = $this->mdl_bkd_remun->hapus_partner($api_search[0], $api_search[1], $api_search[2]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	function simpan_data_pengabdian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->simpan_data_pengabdian($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function current_data_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->current_data_haki($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	function get_all_dosen($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->get_all_dosen();
		$this->sia_api_lib_format->output($query, $format);
	}
	function kegiatan_akademik($format =  'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_remun->data_kegiatan_akademik_dosen($api_search[0]); break; #BUAT MAS DARU
				case 2	: $query = $this->mdl_bkd_remun->current_kegiatan_akademik($api_search[0]); break;
				case 3	: $query = $this->mdl_bkd_remun->current_kegiatan_akademik_semua_dosen_limit($api_search[0], $api_search[1]); break;
				
				case 100 : $query = $this->mdl_bkd_remun->count_kegiatan_akademik(); break;
			}break;
			case 1001: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_remun->simpan_kegiatan_akademik($api_search); break;
				case 2	: $query = $this->mdl_bkd_remun->update_kegiatan_akademik($api_search[0],$api_search[1]); break;
				case 3	: $query = $this->mdl_bkd_remun->delete_kegiatan_akademik($api_search[0]); break;
			}break;
			case 2000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_remun->data_narasumber_dosen($api_search[0]); break; #BUAT MAS HAFID
				case 2	: $query = $this->mdl_bkd_remun->data_narasumber_dosen_semester($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
				case 3	: $query = $this->mdl_bkd_remun->current_narasumber($api_search[0]); break;
				case 4	: $query = $this->mdl_bkd_remun->data_narasumber_semester_limit($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
			}break;
			case 2001: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_remun->simpan_narasumber($api_search); break;
				case 2	: $query = $this->mdl_bkd_remun->update_data_narasumber($api_search); break;
				case 3	: $query = $this->mdl_bkd_remun->delete_narasumber($api_search[0]); break;
			}break;
			
			case 10000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_remun->simpan_narasumber($api_search); break;
				case 2	: $query = $this->mdl_bkd_remun->update_data_narasumber($api_search); break;
				case 3	: $query = $this->mdl_bkd_remun->delete_narasumber($api_search[0]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	# DATA PUBLIKASI DOSEN
	# =====================================================================================================================
	function simpan_data_publikasi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->simpan_data_publikasi($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6], $api_search[7],$api_search[8],$api_search[9],$api_search[10]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function simpan_data_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->simpan_data_haki($api_search);
		$this->sia_api_lib_format->output($query, $format);
    }
    function update_remun($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->update_remun($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16]);
		$this->sia_api_lib_format->output($query, $format); 
	}
	function update_data_pendidikan($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->update_data_pendidikan($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function update_data_penunjang($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->update_data_penunjang($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function update_data_penilai_penelitian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->update_data_penilai_penelitian($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function update_data_penelitian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->update_data_penelitian($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function update_data_pengabdian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->update_data_pengabdian($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function update_data_publikasi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->update_data_publikasi($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function update_bkd($format='json'){ 
		$api_search = $this->input->post('api_search');	
		$query 		= $this->mdl_bkd_remun->update_remun($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16]);
		$this->sia_api_lib_format->output($api_search, $format); 
	}
	function update_data_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->update_data_haki($api_search);
		$this->sia_api_lib_format->output($query, $format);
    }

    function hapus_remun($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->hapus_remun($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]);
		$this->sia_api_lib_format->output($query, $format); 
	}
	function update_dokumen($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_remun->update_bkt_001($api_search[0], $api_search[1]); break; 
				case 2: $query = $this->mdl_bkd_remun->update_kinerja_001($api_search[0], $api_search[1]); break; 
				case 3: $query = $this->mdl_bkd_remun->update_bkt_002($api_search[0], $api_search[1], $api_search[2]); break; 
				case 4: $query = $this->mdl_bkd_remun->update_kinerja_002($api_search[0], $api_search[1], $api_search[2]); break; 
			}break;
			case 1001 : switch($subkode){ # profesor
				default: 
				case 1: $query = $this->mdl_bkd_remun->update_bkt_003($api_search[0], $api_search[1]); break; # penugasan
				case 2: $query = $this->mdl_bkd_remun->update_kinerja_003($api_search[0], $api_search[1]); break; # kinerja
				case 3: $query = $this->mdl_bkd_remun->update_bkt_004($api_search[0], $api_search[1], $api_search[2]); break; # upload penugasan 
				case 4: $query = $this->mdl_bkd_remun->update_kinerja_004($api_search[0], $api_search[1], $api_search[2]); break; # upload kinerja			
			}break;
			case 100000: switch($subkode){
				default:
				case 1: $query = $this->mdl_bkd_remun->update_per_cell_bkd($api_search[0], $api_search[1], $api_search[2]); break;
				case 2: $query = $this->mdl_bkd_remun->update_per_cell_bkd_prof($api_search[0], $api_search[1], $api_search[2]); break;
				case 3: $query = $this->mdl_bkd_remun->update_per_cell_publikasi($api_search[0], $api_search[1], $api_search[2]); break;
				case 4: $query = $this->mdl_bkd_remun->update_per_cell_rbkd($api_search[0], $api_search[1], $api_search[2]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		

	}
	function history_data_bebankerja_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->history_data_bebankerja_prof($api_search[0],$api_search[1],$api_search[2]);
		$this->sia_api_lib_format->output($query, $format); 
	}
	//current beban kerja profesor 
	function current_bebankerja_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->get_beban_prof($api_search[0]);
		$this->sia_api_lib_format->output($query, $format); 
	}

	function kategori_bkd_remun2($format = 'json'){
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_remun->kategori_bkd_remun2($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function update_status_pakai($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_remun->update_status_pakai($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_poin_remun($format='json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_remun->get_poin_remun($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_kd_kat_remun($format='json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_remun->get_kd_kat_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_kd_kat_serdos($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_remun->get_kd_kat_serdos($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_kd_kat_remun2($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_remun->get_kd_kat_remun2($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_kd_kat_remun3($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_remun->get_kd_kat_remun3($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_poin_skr($format='json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_poin_skr($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_max_poin_skr($format='json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_max_poin_skr();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_jenis_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_jenis_remun();
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_jenis_nilai_kat_remun($format='json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_jenis_nilai_kat_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function ambil_kd_kat_remun($format='json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->ambil_kd_kat_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	} 

	function lihat_kd_konversi($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->lihat_kd_konversi($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_semua_poin_pendidikan($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_semua_poin_pendidikan();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_jbr($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_jbr();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_kat_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_kat_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}


	function get_poin_kat_pendidikan($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_poin_kat_pendidikan($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function tambah_poin_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->tambah_poin_remun($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function update_poin_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->update_poin_remun($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function hapus_poin_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->hapus_poin_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_detail_poin_pendidikan($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_detail_poin_pendidikan($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function daftar_kategori_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->daftar_kategori_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_spesifik_kategori($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_spesifik_kategori($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_data_asesor_dosen($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_data_asesor_dosen($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_data_asesor_dosen_by_nip($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_data_asesor_dosen_by_nip($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function cek_nira_asesor_dosen_uin($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->cek_nira_asesor_dosen_uin($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function cek_data_asesor($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->cek_data_asesor($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format); 
	}
	function getblob_bkd_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->getblob_bkd_remun($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function getextensi_bkd_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->getextensi_bkd_remun($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function cek_summary_remun_dosen($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->cek_summary_remun_dosen($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function insert_summary_remun_dosen($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->insert_summary_remun_dosen($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function update_summary_remun_dosen($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->update_summary_remun_dosen($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_prosentase_skr_dosen($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_prosentase_skr_dosen($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}



	function get_all_syarat_mengajar($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_remun->get_all_syarat_mengajar();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_syarat_mengajar($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_remun->get_syarat_mengajar($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}


	/*function get_kd_kat_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->get_kd_kat_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function cek_kd_konversi_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_remun->cek_kd_konversi_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}*/
	/*
		Last modified	: 14/12/2017
		Author			: Mata Air Biru
	*/
	
	
}
?>