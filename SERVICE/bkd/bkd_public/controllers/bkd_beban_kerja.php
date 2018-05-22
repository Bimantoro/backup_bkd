<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi SIA
 * @subpackage  BKD
 * @category    bkd
 * @recoded 	10 Juni 2013, 
*/
 
 
class Bkd_beban_kerja extends CI_Controller
{
	protected $builtInMethods;
 
	public function __construct() {
		parent::__construct();
		$this->load->model('bkd_dosen/mdl_bkd_beban_kerja');
	}
	
	function index(){ echo 'BKD Beban Kerja'; }	
		
	
	function get_all_dosen($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->get_all_dosen();
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function data_bebankerja($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->data_bebankerja($api_search[0],$api_search[1],$api_search[2],$api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function jml_beban($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->jml_beban($api_search[0],$api_search[1],$api_search[2],$api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function jml_kinerja($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->jml_kinerja($api_search[0],$api_search[1],$api_search[2],$api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function jml_beban_lebih($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->jml_beban_lebih($api_search[0],$api_search[1],$api_search[2],$api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function data_bebankerja_tahunan($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->data_bebankerja_tahunan($api_search[0],$api_search[1],$api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	function current_data_bkd($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->get_data($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	// Modul untuk beban kerja dosen
	//=======================================================================	
	//insert beban kerja
	function simpan_beban_kerja($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->simpan_beban_kerja($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12],$api_search[13],$api_search[14],$api_search[15],$api_search[16],$api_search[17]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	// hapus beban kerja
	function hapus_beban_kerja($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->hapus_beban_kerja($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	// update beban kerja
	function update_beban_kerja($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->update_beban_kerja($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16]);
		$this->sia_api_lib_format->output($query, $format); 
	} 
	function update_bkd($format='json'){ 
		$api_search = $this->input->post('api_search');	
		$query 		= $this->mdl_bkd_beban_kerja->update_beban_kerja($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16]);
		$this->sia_api_lib_format->output($api_search, $format); 
	} 

	function history_beban_kerja($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->history_bk($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	
	// Modul untuk beban kerja dosen
	//=======================================================================	
	
	// data beban kerja professor
	function data_bebankerja_prof($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->data_bebankerja_prof($api_search[0],$api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	// jumlh beban professor
	function jml_beban_prof($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->jml_beban_prof($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	// jumlh kinerja professor
	function jml_kinerja_prof($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->jml_kinerja_prof($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	
	//insert beban kerja
	function simpan_beban_kerja_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->simpan_bebankerja_prof($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],
					$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12],$api_search[13],$api_search[14],$api_search[15],$api_search[16],$api_search[17], $api_search[18], $api_search[19]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	//update beban kerja
	function update_beban_kerja_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->update_bebankerja_prof($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12],$api_search[13],$api_search[14],$api_search[15],$api_search[16],$api_search[17],$api_search[18], $api_search[19],$api_search[20]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	//history data beban kerja profesor
	function history_data_bebankerja_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->history_data_bebankerja_prof($api_search[0],$api_search[1],$api_search[2]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	//history jumlah beban kerja profesor 
	function history_jml_beban_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->history_jml_beban_prof($api_search[0],$api_search[1]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	//history jumlah beban kerja profesor 
	function history_jml_kinerja_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->history_jml_kinerja_prof($api_search[0],$api_search[1]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	//current beban kerja profesor 
	function current_bebankerja_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->get_beban_prof($api_search[0]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	//current beban kerja profesor 
	function hapus_beban_kerja_prof($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->hapus_beban_prof($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	// kinerja profesor tahunan
	function kinerja_prof_tahunan($format='json'){ 
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->kinerja_prof_tahunan($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		$this->sia_api_lib_format->output($query, $format); 
	} 

	# DATA PENEITIAN DAN PENGABDIAN MASYARAKAT DETAIL
	# ===================================================================================================================
	function simpan_data_penelitian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_penelitian($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16], $api_search[17], $api_search[18], $api_search[19]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function simpan_data_pengabdian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_pengabdian($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7], $api_search[8],$api_search[9],$api_search[10],$api_search[11],$api_search[12],$api_search[13],$api_search[14],$api_search[15], $api_search[16], $api_search[17]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function get_current_data_tersimpan($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->get_current_data_tersimpan($api_search[0],$api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
    }

	function update_data_penelitian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->update_data_penelitian($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function update_data_pengabdian($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->update_data_pengabdian($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	# DATA PUBLIKASI DOSEN
	# =====================================================================================================================
	function simpan_data_publikasi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_publikasi($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6], $api_search[7],$api_search[8],$api_search[9],$api_search[10]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function update_data_publikasi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->update_data_publikasi($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6]);
		$this->sia_api_lib_format->output($query, $format);
    }

	function hapus_data_publikasi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->hapus_data_publikasi($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function get_data_publikasi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->get_data_publikasi($api_search[0],$api_search[1],$api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function current_data_publikasi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->current_data_publikasi($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	# HAKI
	# ===========================================================================
	function get_jenis_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->get_jenis_haki();
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function get_data_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->get_data_haki($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function current_data_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->current_data_haki($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function simpan_data_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_haki($api_search);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function update_data_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->update_data_haki($api_search);
		$this->sia_api_lib_format->output($query, $format);
    }
	function hapus_data_haki($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->hapus_data_haki($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	# SIPKD TAMBAHAN
	# ===========================================================================
	function get_kategori($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->get_kategori($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function simpan_data_pendidikan($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_pendidikan($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }

    function simpan_data_pendidikan_ta($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_pendidikan_ta($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function simpan_data_pengabdian_dosen($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_pengabdian_dosen($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function simpan_data_penelitian_dosen($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_penelitian_dosen($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function simpan_data_penelitian_asesor($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_penelitian_asesor($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function simpan_data_penunjang_ta($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_penunjang_ta($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }

    function simpan_data_penunjang_ta_maps($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_penunjang_ta($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13], $api_search[14]);
		$this->sia_api_lib_format->output($query, $format);
    }
     function simpan_data_penunjang($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_data_penunjang($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }

    function get_satuan_data_remun($format = 'json') {
    	$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->get_satuan_data_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }

	function update_data_pendidikan($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->update_data_pendidikan($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	# SUTO SAVE BEBAN KERJA FROM SIA
	function simpan_beban_kerja_sia($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_beban_kerja_sia($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13]);
		$this->sia_api_lib_format->output($query, $format);
    }


	function simpan_beban_kerja_sia_maps($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_beban_kerja_sia_maps($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13], $api_search[14]);
		$this->sia_api_lib_format->output($query, $format);
    }


    function simpan_beban_kerja_sia_ta($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_beban_kerja_sia_ta($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16], $api_search[17]);
		$this->sia_api_lib_format->output($query, $format);
    }
    function simpan_beban_kerja_sia_ta_maps($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_beban_kerja_sia_ta_maps($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16], $api_search[17], $api_search[18]);
		$this->sia_api_lib_format->output($query, $format);
    }
	function simpan_beban_kerja_sia_asesor($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_beban_kerja_sia_asesor($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16], $api_search[17]);
		$this->sia_api_lib_format->output($query, $format);
    }
	function simpan_beban_kerja_sia_asesor_maps($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_beban_kerja_sia_asesor_maps($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10], $api_search[11], $api_search[12], $api_search[13], $api_search[14], $api_search[15], $api_search[16], $api_search[17], $api_search[18]);
		$this->sia_api_lib_format->output($query, $format);
    }
	function cek_data_bkd($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->cek_data_bkd($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function clear_data_bkd($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->clear_data_bkd($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	# cek jenjang prodi
	function cek_jenjang_prodi($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->cek_jenjang_prodi($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	# RENCANA BEBAN KERJA DOSEN
	function simpan_rbkd($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->simpan_rbkd($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9], $api_search[10]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function update_rbkd($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->update_rbkd($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function data_rbkd($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->data_rbkd($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function current_rbkd($format = 'json') {
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->current_rbkd($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function hapus_rbkd($format = 'json'){
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->hapus_rbkd($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function bebankerja($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_bkd_beban_kerja->get_file_bebankerja($api_search[0]);break;
			}break;
			
			case 1001: switch($subkode){
				default: 
					case 1: $query = $this->mdl_bkd_beban_kerja->SemuaDataBebanKerjaDosen($api_search[0], $api_search[1]);break;
			}break;
			
			case 2000: switch($subkode){
				default:
					# APLIKASI IKD 
					case 1: $query = $this->mdl_bkd_beban_kerja->detail_beban_kerja($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
					case 2: $query = $this->mdl_bkd_beban_kerja->get_data_publikasi($api_search[0],$api_search[1],$api_search[2]); break;
					case 3: $query = $this->mdl_bkd_beban_kerja->detail_beban_kerja_fakultas($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
					case 4: $query = $this->mdl_bkd_beban_kerja->get_data_publikasi_fakultas($api_search[0],$api_search[1],$api_search[2]); break;
					case 5: $query = $this->mdl_bkd_beban_kerja->detail_beban_kerja_prodi($api_search[0],$api_search[1],$api_search[2], $api_search[3]); break;
					case 6: $query = $this->mdl_bkd_beban_kerja->get_data_publikasi_prodi($api_search[0],$api_search[1],$api_search[2]); break;
			}break;
			# rencana beban kerja 
			case 3000: switch($subkode){
				default:
					# APLIKASI IKD 
					case 1: $query = $this->mdl_bkd_beban_kerja->data_rbkd_dosen($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);
    }
    function bebankerja_remunerasi($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_bkd_beban_kerja->get_file_bebankerja($api_search[0]);break;
			}break;
			
			case 1001: switch($subkode){
				default: 
					case 1: $query = $this->mdl_bkd_beban_kerja->SemuaDataBebanKerjaDosen($api_search[0], $api_search[1]);break;
			}break;
			
			case 2000: switch($subkode){
				default:
					# APLIKASI IKD 
					case 1: $query = $this->mdl_bkd_beban_kerja->detail_beban_kerja_remunerasi($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
					case 2: $query = $this->mdl_bkd_beban_kerja->get_data_publikasi($api_search[0],$api_search[1],$api_search[2]); break;
					case 3: $query = $this->mdl_bkd_beban_kerja->detail_beban_kerja_fakultas($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
					case 4: $query = $this->mdl_bkd_beban_kerja->get_data_publikasi_fakultas($api_search[0],$api_search[1],$api_search[2]); break;
					case 5: $query = $this->mdl_bkd_beban_kerja->detail_beban_kerja_prodi($api_search[0],$api_search[1],$api_search[2], $api_search[3]); break;
					case 6: $query = $this->mdl_bkd_beban_kerja->get_data_publikasi_prodi($api_search[0],$api_search[1],$api_search[2]); break;
			}break;
			# rencana beban kerja 
			case 3000: switch($subkode){
				default:
					# APLIKASI IKD 
					case 1: $query = $this->mdl_bkd_beban_kerja->data_rbkd_dosen($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);
    }
	
	
	function update_dokumen($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_beban_kerja->update_bkt_001($api_search[0], $api_search[1]); break; 
				case 2: $query = $this->mdl_bkd_beban_kerja->update_kinerja_001($api_search[0], $api_search[1]); break; 
				case 3: $query = $this->mdl_bkd_beban_kerja->update_bkt_002($api_search[0], $api_search[1], $api_search[2]); break; 
				case 4: $query = $this->mdl_bkd_beban_kerja->update_kinerja_002($api_search[0], $api_search[1], $api_search[2]); break; 
			}break;
			case 1001 : switch($subkode){ # profesor
				default: 
				case 1: $query = $this->mdl_bkd_beban_kerja->update_bkt_003($api_search[0], $api_search[1]); break; # penugasan
				case 2: $query = $this->mdl_bkd_beban_kerja->update_kinerja_003($api_search[0], $api_search[1]); break; # kinerja
				case 3: $query = $this->mdl_bkd_beban_kerja->update_bkt_004($api_search[0], $api_search[1], $api_search[2]); break; # upload penugasan 
				case 4: $query = $this->mdl_bkd_beban_kerja->update_kinerja_004($api_search[0], $api_search[1], $api_search[2]); break; # upload kinerja			
			}break;
			case 100000: switch($subkode){
				default:
				case 1: $query = $this->mdl_bkd_beban_kerja->update_per_cell_bkd($api_search[0], $api_search[1], $api_search[2]); break;
				case 2: $query = $this->mdl_bkd_beban_kerja->update_per_cell_bkd_prof($api_search[0], $api_search[1], $api_search[2]); break;
				case 3: $query = $this->mdl_bkd_beban_kerja->update_per_cell_publikasi($api_search[0], $api_search[1], $api_search[2]); break;
				case 4: $query = $this->mdl_bkd_beban_kerja->update_per_cell_rbkd($api_search[0], $api_search[1], $api_search[2]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		

	}
	

	
	
	# administrator 
	# ==============================================================
	
	function kompilasi($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_beban_kerja->kompilasi_dosen($api_search[0]); break; # kompilasi seluruh dosen
				case 3: $query = $this->mdl_bkd_beban_kerja->kompilasi_dosen_limit($api_search[0], $api_search[1], $api_search[2]); break; # kompilasi limit
				case 9: $query = $this->mdl_bkd_beban_kerja->cari_kompilasi_dosen($api_search[0], $api_search[1], $api_search[2]); break; # cari kompilasi dosen
				case 100: $query = $this->mdl_bkd_beban_kerja->jml_kompilasi_dosen($api_search[0]); break; # cari kompilasi dosen
				
				# ADMIN FAKULTAS 
				case 21: $query = $this->mdl_bkd_beban_kerja->kompilasi_dosen_fakultas($api_search[0], $api_search[1]); break;
				case 29: $query = $this->mdl_bkd_beban_kerja->cari_kompilasi_dosen_fakultas($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
				# ADMIN PRODI
				case 31: $query = $this->mdl_bkd_beban_kerja->kompilasi_dosen_prodi($api_search[0], $api_search[1]); break;
				case 39: $query = $this->mdl_bkd_beban_kerja->cari_kompilasi_dosen_prodi($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
			}break;
			case 1001 : switch($subkode){
				default:
				# ADMIN UNIVERSITAS
				case 1 : $query = $this->mdl_bkd_beban_kerja->data_pubikasi_limit(); break;
				# ADMIN FAKULTAS 
				case 21 : $query = $this->mdl_bkd_beban_kerja->data_pubikasi_limit_fakultas($api_search[0]); break;
				# ADMIN PRODI
				case 31 : $query = $this->mdl_bkd_beban_kerja->data_pubikasi_limit_prodi($api_search[0]); break;
			}break;
			case 1000000: switch($subkode){
				default:
				case 1 : $query = $this->mdl_bkd_beban_kerja->tot_record($api_search[0]); break;
				case 2 : $query = $this->mdl_bkd_beban_kerja->tot_record_semester($api_search[0],$api_search[1],$api_search[2]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}

	# DATA ATRIBUT
	function atribut($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		$this->load->model('bkd_dosen/mdl_bkd_dosen');
		switch($kode){
			case 21041991: switch($subkode){
				default: 
				case 1: $query = $this->mdl_bkd_dosen->get_nm_fakultas($api_search[0]); break; # kompilasi seluruh dosen
				case 2: $query = $this->mdl_bkd_dosen->get_nm_prodi($api_search[0]); break; # kompilasi seluruh dosen
			}break;
		}
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
				case 1: $query = $this->mdl_bkd_beban_kerja->simpan_partner($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
				case 99: $query = $this->mdl_bkd_beban_kerja->hapus_partner($api_search[0], $api_search[1], $api_search[2]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	
	
	# PUBLIKASI DOSEN 
	function publikasi($format =  'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 9900: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->semua_publikasi(); break;
				case 2	: $query = $this->mdl_bkd_beban_kerja->semua_publikasi_dosen($api_search[0]); break;
				case 99	: $query = $this->mdl_bkd_beban_kerja->publikasi_tahunan($api_search[0]); break;
				case 999: $query = $this->mdl_bkd_beban_kerja->publikasi_semester($api_search[0], $api_search[1]); break;
				case 111: $query = $this->mdl_bkd_beban_kerja->cari_publikasi($api_search[0], $api_search[1]); break;
			}break;
			case 8800: switch($subkode){
				case 1 : $query = $this->mdl_bkd_beban_kerja->publikasi_semester_limit($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	
	function pendidikan($format =  'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->historiPendidikanDosen($api_search[0]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	
	function tingkat($format =  'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->tingkatKegiatan(); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	
	function kegiatan_akademik($format =  'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->data_kegiatan_akademik_dosen($api_search[0]); break; #BUAT MAS DARU
				case 2	: $query = $this->mdl_bkd_beban_kerja->current_kegiatan_akademik($api_search[0]); break;
				case 3	: $query = $this->mdl_bkd_beban_kerja->current_kegiatan_akademik_semua_dosen_limit($api_search[0], $api_search[1]); break;
				
				case 100 : $query = $this->mdl_bkd_beban_kerja->count_kegiatan_akademik(); break;
			}break;
			case 1001: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->simpan_kegiatan_akademik($api_search); break;
				case 2	: $query = $this->mdl_bkd_beban_kerja->update_kegiatan_akademik($api_search[0],$api_search[1]); break;
				case 3	: $query = $this->mdl_bkd_beban_kerja->delete_kegiatan_akademik($api_search[0]); break;
			}break;
			case 2000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->data_narasumber_dosen($api_search[0]); break; #BUAT MAS HAFID
				case 2	: $query = $this->mdl_bkd_beban_kerja->data_narasumber_dosen_semester($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
				case 3	: $query = $this->mdl_bkd_beban_kerja->current_narasumber($api_search[0]); break;
				case 4	: $query = $this->mdl_bkd_beban_kerja->data_narasumber_semester_limit($api_search[0], $api_search[1], $api_search[2], $api_search[3]); break;
			}break;
			case 2001: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->simpan_narasumber($api_search); break;
				case 2	: $query = $this->mdl_bkd_beban_kerja->update_data_narasumber($api_search); break;
				case 3	: $query = $this->mdl_bkd_beban_kerja->delete_narasumber($api_search[0]); break;
			}break;
			
			case 10000: switch($subkode){
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->simpan_narasumber($api_search); break;
				case 2	: $query = $this->mdl_bkd_beban_kerja->update_data_narasumber($api_search); break;
				case 3	: $query = $this->mdl_bkd_beban_kerja->delete_narasumber($api_search[0]); break;
			}break;
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	
	///KONFIGURASI JADWAL PENGISIAN
	function get_jadwal($kd_dosen=""){
		$query = $this->db->query("SELECT * FROM JADWAL_PENGISIAN WHERE SYSDATE BETWEEN TGL_MULAI AND TGL_SELESAI")->result_array();
		$this->sia_api_lib_format->output($query, 'json');		
	}	
	function update_status_pakai($format='json'){
		$api_search = $this->input->post('api_search');		
		$query 		= $this->mdl_bkd_beban_kerja->update_status_pakai($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_last_id_mhs_bimbingan($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_last_id_mhs_bimbingan($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function cek_data_pendidikan($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->cek_data_pendidikan($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_last_id_input_nilai_matkul($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_last_id_input_nilai_matkul($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_last_id_dosen_pengabdian($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_last_id_dosen_pengabdian($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_last_id_dosen_penelitian($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_last_id_dosen_penelitian($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_current_bimbingan_mhs($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_current_bimbingan_mhs($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_current_bimbingan_mhs_sp($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_current_bimbingan_mhs_sp($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_current_anggota_penelitian($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_current_anggota_penelitian($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_current_asesor_dosen($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_current_asesor_dosen($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function update_jml_mhs_bimbingan($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->update_jml_mhs_bimbingan($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function update_jml_dosen_asesor($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->update_jml_dosen_asesor($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_current_bimbingan_pa($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_current_bimbingan_pa($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_current_jab_struktural($format='json'){
		$api_search = $this->input->post('api_search');
		$query 		= $this->mdl_bkd_beban_kerja->get_current_jab_struktural($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function kategori_bkd_bebankerja($format = 'json'){
		$api_search 	= $this->input->post('api_search');		
		$query 			= $this->mdl_bkd_beban_kerja->kategori_bkd_bebankerja($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
    }
	
	function get_rule_sks($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');

		switch ($kode) {
			case 1000: switch($subkode){ // MENGAJAR
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('57'); break; //untuk S1
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('58'); break; //untuk S2
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('59'); break; //untuk S3
			}break;

			case 1001: switch($subkode){ // MEMBIMBING
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('63'); break; //untuk seminar proposal
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('64'); break; //untuk skripsi
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('65'); break; //untuk tesis
				case 4	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('66'); break; //untuk disertasi
				case 5	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('60'); break; //untuk praktek lapangan
				case 6	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('61'); break; //untuk praktek klinik
				case 7	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('62'); break; //untuk DPL
				case 8	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('67'); break; //untuk dosen yang lebih rendah pangkatnya
				case 9	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('343'); break; //untuk asistensi tugas / praktikum
			}break;

			case 1002: switch($subkode){ // BIDANG PENDIDIKAN
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('68'); break; //Mengembangkan Program Perkuliahan / Pengajaran
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('69'); break; //melaksanakan Kegiatan Detasering dan Pencangkokan dosen
			}break;

			case 1003: switch($subkode){ // MENGUJI
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('70'); break; //untuk seminar proposal
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('71'); break; //untuk skripsi
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('72'); break; //untuk tesis
				case 4	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('73'); break; //untuk disertasi
			}break;

			case 1004: switch($subkode){ // PENELITIAN
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('74'); break; // Ketua Penelitian Kelompok
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('164'); break; // Anggota Penelitian Kelompok
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('75'); break; // Ketua Penelitian Mandiri
				case 4	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('76'); break; //Menulis Naskah Buku ber ISBN
				case 5	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('77'); break; //Editor Buku
				case 6	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('78'); break; //Kontributor Buku
				case 7	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('79'); break; //Menulis Modul/Diktat/Bahan Ajar
				case 8	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('80'); break; //Menulis Naskah Buku Internasiona
				case 9	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('81'); break; //Penerjemahkan Buku (Mandiri / tanpa anggota)
				case 10	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('82'); break; //Ketua /Editor Penerjemahkan Buku
				case 11	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('83'); break; //Anggota Penerjemah Buku
				case 12	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('84'); break; //Penyunting Naskah Buku (Mandiri / tanpa anggota)
				case 13	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('85'); break; //Ketua /Editor Penyunting Naskah Buku 
				case 14	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('86'); break; //Anggota Penyunting Naskah Buku
				case 15	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('87'); break; //Ijin Belajar
				case 16	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('88'); break; //Asesor Beban Kerja Dosen Dan Evaluasi Pelaksanaan Tridharma Perguruan Tinggi
				case 17	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('89'); break; //Menulis di Media Massa
			}break;

			case 1005: switch($subkode){ // MENULIS JURNAL ILMIAH
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('90'); break; //Diterbitkan oleh Jurnal ilmiah/majalah ilmiah ber- ISSN tidak terakreditasi
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('91'); break; //Diterbitkan oleh Jurnal terakreditasi
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('92'); break; //Diterbitkan oleh Jurnal terakreditasi internasional (dalam bahasa intenasional)
			}break;

			case 1006: switch($subkode){ // MEMPEROLEH HAK PATEN
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('93'); break; //Proses pengurusan paten sederhana
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('94'); break; //Proses pengurusan paten Biasa
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('95'); break; //Proses pengurusan paten Internasional (minimal 3 negara)
			}break;

			case 1007: switch($subkode){ // MENYAMPAIKAN ORASI ILMIAH, PEMBICARA SEMINAR, NARASUMBER
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('96'); break; //Tingkat regional daerah, institusional (minimum fakultas)
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('97'); break; //Tingkat Tingkat nasional
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('98'); break; //Tingkat internasional (dengan bahasa internasional)
			}break;

			case 1008: switch($subkode){ // PENGABDIAN MASYARAKAT
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('99'); break; //Satu kegiatan yang setara dengan 50 jam kerja per semester
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('100'); break; //Menulis karya pengabdian yang dipakai sebagai Modul/Bahan Ajar oleh seorang Dosen
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('101'); break; //Penyuluhan kepada masyarakat
				case 4	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('102'); break; //Memberi kursus/menatar pada masyarakat
			}break;

			case 1009: switch($subkode){ // MEMBUAT/MENULIS KARYA PENGABDIAN KEPADA MASYARAKAT
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('103'); break; //Menulis 1 judul, direncanakan terbit ber ISBN, ada kontrak penerbitan dan atau sudah diterbitkan dan ber – ISBN ( Mandiri )
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('104'); break; //Editor 1 Judul
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('105'); break; //Kontributor tiap Chapter dalam 1 Judul
			}break;

			case 1010: switch($subkode){ // PEMBINAAN SIVITAS AKADEMIKA
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('106'); break; //Bimbingan Akademik (perwalian/penasehat akademik)
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('107'); break; //Bimbingan dan Konseling
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('108'); break; //Pimpinan Pembinaan Unit kegiatan mahasiswa
				case 4	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('109'); break; //Pimpinan organisasi sosial intern sebagai Ketua/Wakil Ketua
			}break;

			case 1011: switch($subkode){ // ADMINISTRASI DAN MANAJEMEN
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('110'); break; //Rektor
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('111'); break; //Wakil Rektor, Dekan, Direktur Pascasarjana
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('112'); break; //Wakil Dekan
				case 4	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('113'); break; //Ketua Lembaga/UPT/SPI
				case 5	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('114'); break; //Ketua Program Studi
				case 6	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('115'); break; //Sekretaris Program Studi / Sekretaris Lembaga
				case 7	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('116'); break; //Kepala Pusat pada Lembaga
				case 8	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('117'); break; //Ketua/Sekretaris Senat Universitas/Fakultas
				case 9	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('118'); break; //Anggota Senat Universitas/Fakultas
				case 10	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('119'); break; //Ketua Redaksi Jurnal ber-ISSN
				case 11	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('261'); break; //Anggota Redaksi Jurnal ber-ISSN
				case 12	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('262'); break; //Ketua Panitia Ad Hoc
				case 13	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('263'); break; //Anggota Panitia Ad Hoc
			}break;

			case 1012: switch($subkode){ // KETUA PANITIA TETAP
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('120'); break; //Tingkat Universitas
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('121'); break; //Tingkat Fakultas
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('122'); break; //Tingkat Prodi
			}break;

			case 1013: switch($subkode){ // ANGGOTA PANITIA TETAP
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('123'); break; //Tingkat Universitas
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('124'); break; //Tingkat Fakultas
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('125'); break; //Tingkat Prodi
			}break;

			case 1014: switch($subkode){ // PELAKSANAAN TUGAS PENUNJANG PESERTA
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_rule_sks('126'); break; //seminar/workshop/kursus berdasar penugasan pimpinan  
			}break;

			//INI MASIH BANYAK TERUSANNYA 👍 
		}

		$this->sia_api_lib_format->output($query, $format);		
	}

	function get_kd_kat_remun($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_kd_kat_remun($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_jenis_nilai_kat_bebankerja($format='json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_jenis_nilai_kat_bebankerja($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_group_nilai_sks($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_group_nilai_sks();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_subgroup_nilai_sks($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_subgroup_nilai_sks($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_nilai_rule_sks($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_nilai_rule_sks($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_mode_pengaturan_sks($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_mode_pengaturan_sks();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_operator_pengaturan_sks($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_operator_pengaturan_sks();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_current_rule_sks($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_current_rule_sks($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_kd_kat($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_kd_kat($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function cek_kd_konversi($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->cek_kd_konversi($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function tambah_pengaturan_sks($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->tambah_pengaturan_sks($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function update_pengaturan_sks($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->update_pengaturan_sks($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_map_dosen_dt($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_map_dosen_dt($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function daftar_kategori_bebankerja($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->daftar_kategori_bebankerja($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_spesifik_kategori($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_spesifik_kategori($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_data_asesor_dosen_by_nip($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_data_asesor_dosen_by_nip($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function cek_nira_asesor_dosen_uin($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->cek_nira_asesor_dosen_uin($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function cek_data_asesor($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->cek_data_asesor($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format); 
	}
	function getblob_bkd_bebankerja($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->getblob_bkd_bebankerja($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function getextensi_bkd_bebankerja($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->getextensi_bkd_bebankerja($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_syarat_total($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_syarat_total($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_syarat_kesimpulan($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');

		switch ($kode) {
			case 1000: switch($subkode){ // MENGAJAR
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_syarat_kesimpulan($api_search[0], 'A'); break; //untuk PENDIDIKAN
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_syarat_kesimpulan($api_search[0], 'B'); break; //untuk PENELITIAN
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_syarat_kesimpulan($api_search[0], 'C'); break; //untuk PENGABDIAN
				case 4	: $query = $this->mdl_bkd_beban_kerja->get_syarat_kesimpulan($api_search[0], 'D'); break; //untuk TAMBAHAN
			}break; 
		}

		$this->sia_api_lib_format->output($query, $format);		
	}

	function get_syarat_kesimpulan_kat($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');

		switch ($kode) {
			case 1000: switch($subkode){ // MENGAJAR
				default: 
				case 1	: $query = $this->mdl_bkd_beban_kerja->get_syarat_kesimpulan_kat($api_search[0], 'A', $api_search[1]); break; //untuk PENDIDIKAN
				case 2	: $query = $this->mdl_bkd_beban_kerja->get_syarat_kesimpulan_kat($api_search[0], 'B', $api_search[1]); break; //untuk PENELITIAN
				case 3	: $query = $this->mdl_bkd_beban_kerja->get_syarat_kesimpulan_kat($api_search[0], 'C', $api_search[1]); break; //untuk PENGABDIAN
				case 4	: $query = $this->mdl_bkd_beban_kerja->get_syarat_kesimpulan_kat($api_search[0], 'D', $api_search[1]); break; //untuk TAMBAHAN
			}break; 
		}

		$this->sia_api_lib_format->output($query, $format);		
	}

	function get_all_syarat_kesimpulan($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_all_syarat_kesimpulan($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_all_syarat_total($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_all_syarat_total();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_group_jenis_dosen($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_group_jenis_dosen();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_group_bidang($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->get_group_bidang();
		$this->sia_api_lib_format->output($query, $format);
	}

	function tambah_pengaturan_syarat($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->tambah_pengaturan_syarat($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function update_pengaturan_syarat($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->update_pengaturan_syarat($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function edit_pengaturan_syarat($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->edit_pengaturan_syarat($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function tambah_pengaturan_batas($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->tambah_pengaturan_batas($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function update_pengaturan_batas($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->update_pengaturan_batas($api_search[0], $api_search[1], $api_search[2]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function edit_pengaturan_batas($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query = $this->mdl_bkd_beban_kerja->edit_pengaturan_batas($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}
	function get_kd_kat_remun2($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->get_kd_kat_remun2($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_kat_serdos_available($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->get_kat_serdos_available();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_kat_remun_available($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->get_kat_remun_available();
		$this->sia_api_lib_format->output($query, $format);
	}

	function get_all_konversi($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->get_all_konversi();
		$this->sia_api_lib_format->output($query, $format);
	}

	function delete_konversi_kategori($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->delete_konversi_kategori($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function tambah_konversi_kategori($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->tambah_konversi_kategori($api_search[0], $api_search[1]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function pindah_jalur_kat($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->pindah_jalur_kat($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function cek_kewajiban_serdos($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->cek_kewajiban_serdos($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}

	function cek_jalur_data($format = 'json'){
		$api_search = $this->input->post('api_search');
		$query      = $this->mdl_bkd_beban_kerja->cek_jalur_data($api_search[0]);
		$this->sia_api_lib_format->output($query, $format);
	}








	/*
		Last modified	: 23/02/2014
		Author			: Padang Rumput
	*/
	
	
}
?>