<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *
 * @package		Revitalisasi SIA
 * @subpackage  SKRIPSI
 * @category    Proposal Skripsi
 * @created 	17-04-2013, Fadli Ikhsan Pratama
*/

class Verifikator extends CI_Controller {
	
	function __construct(){
        parent::__construct();
		if(empty($this->session->userdata('id_user'))){ redirect(); }
		$this->load->helper(array('form','url', 'text_helper','date'));
		$this->load->library('bkd_lib_history','' ,'history');
		$this->load->library('bkd_lib_setting','','setting');
		$this->load->model('mdl_bkd');
		$this->load->library(array('Pagination','image_lib'));
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->session->set_userdata('app','app_bkd');
		
		$this->cur_ta = $this->session->userdata('kd_ta');
		$this->cur_smt = $this->session->userdata('kd_smt');
		
    }
	
	function index(){
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		$this->output99->output_display('asesor/v_home', $data);
	}
	
	function cariNIPNamaDosen($q){
		#sia_dosen/data_search, 20000/13
		$api_url = URL_API_SIA.'sia_dosen/data_search';
		$parameter = array('api_kode' => 20000, 'api_subkode' => 14, 'api_search'=> array($q));
		$data['dosen'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		return $data['dosen'];
	}
	
	
	function dosen(){
		// $kd_ta	 	= $this->session->userdata('kd_ta');
		// $kd_smt		= $this->session->userdata('kd_smt');
		$post = $this->input->post('thn');
		if($post == ''){
			$kd_ta = $this->cur_ta;
			$kd_smt = $this->cur_smt;
		}else{
			$kd_ta = $this->input->post('thn');
			$kd_smt = $this->input->post('smt');
		}
		
		$data['ta'] = $kd_ta;
		$data['smt'] = $kd_smt;

		$api_url = URL_API_BKD.'bkd_admin/verifikasi';
		$parameter = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search'=> array($this->session->userdata('id_user'), $kd_ta, $kd_smt));
		$is_asesor = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		if($this->session->userdata('adm') == 'BKDVER'){
			if(isset($_POST['keyword'])){
				$data['dosen'] = $this->cariNIPNamaDosen($_POST['keyword']);
			}else{ $data['dosen'] = '';}
			$data['is_asesor'] = 0;
		}else{
			$data['dosen'] = $is_asesor;
			$data['is_asesor'] = 1;
		}
		
		if(!empty($data['dosen'])){
			foreach ($data['dosen'] as $a){
				$data['nm_dosen']['_'.$a['KD_DOSEN']] = $this->konvertNama($a['KD_DOSEN']);
				$data['ds']['_'.$a['KD_DOSEN']] = $this->history->_status_DS($a['KD_DOSEN'], $kd_ta, $kd_smt);
			}
		}
		#print_r($this->session->all_userdata());
		$this->output99->output_display('asesor/data_dosen', $data);	
	}
	function sertifikasi_dosen(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->session->userdata('kd_ta');
		$smt = $this->session->userdata('kd_smt');
		//$kd_dosen = '197701032005011003';
		$data['nira'] = $this->cek_nira_dosen($kd_dosen);
		if(!empty($data['nira'])){
			$nira = $data['nira'];
			$data_dosen_di_asesori = $this->cek_nira_dosen_yang_diasesori($nira);
			if(!empty($data_dosen_di_asesori)){
				foreach ($data_dosen_di_asesori as $key) {
					$nip_dosen = $key['KD_DOSEN'];
					$data_dosen = $this->cariNIPNamaDosen($nip_dosen);
					foreach ($data_dosen as $dd) {
						$status_dosen = $this->_status_DS($kd_dosen, $ta, $smt);
						$kd_dosen = $dd['KD_DOSEN'];
						$nm_dosen = $dd['NM_DOSEN_F'];

						$api_url 	= URL_API_BKD.'bkd_admin/cek_status_verifikasi_sertifikasi';
						$parameter  = array('api_search' => array($kd_dosen, $ta, $smt));
						$ok = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

						if($ok==0){
							$status_verifikasi = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";//Belum diverifikasi
							/*$status_verifikasi = "Belum Diverifikasi";*/
						}elseif($ok==1){
							$status_verifikasi = "<span class='badge badge-warning'> <i class='icon-white icon-info'></i></span>";//Sudah diverifikasi
							/*$status_verifikasi = "Sudah Diverifikasi tetapi belum lengkap";*/
						}elseif($ok==2){
							$status_verifikasi = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
							/*$status_verifikasi = "Sudah Diverifikasi dan sudah lengkap";*/
						}

						$api_url 	= URL_API_BKD.'bkd_admin/cek_verifikator_dan_tanggal_verifikasi_sertifikasi';
						$parameter  = array('api_search' => array($kd_dosen, $ta, $smt));
						$cek_verifikasi = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
						if(!empty($cek_verifikasi['VERIFIKATOR'])){
							$verifikator = $this->cariNIPNamaDosen($cek_verifikasi['VERIFIKATOR']);
							$verifikator = $verifikator[0]['NM_DOSEN_F'];
							$tgl_verifikasi = $cek_verifikasi['TGL_VERIFIKASI'];
						}else{
							$verifikator = '-';
							$tgl_verifikasi = '-';
						}

						$data['data_dosen'][] = array("KD_DOSEN" => $kd_dosen, "NM_DOSEN"=>$nm_dosen, "STATUS"=>$status_dosen, "STATUS_VERIFIKASI"=>$status_verifikasi, "VERIFIKATOR"=>$verifikator, "TGL_VERIFIKASI"=>$tgl_verifikasi);
					}
					
					//$data['data_dosen'][] = array("KD_DOSEN" => $nip_dosen, "NM_DOSEN"=>$nama_dosen);
				}
			}else{
				//$data['data_dosen'] = array();
			}
			
		}else{
			//bukan asesor bkd
			$data['nira']= '';
		}
		/*echo "<pre>";
		print_r($data['data_dosen']);
		echo "</pre>";
		die();*/
		$this->output99->output_display('asesor/data_sertifikasi_dosen', $data);
	}
	function cek_nira_dosen($kd_dosen){
		$api_url 	= URL_API_BKD.'bkd_admin/cek_nira_dosen';
		$parameter  = array('api_search' => array($kd_dosen));
		$ok = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		if(!empty($ok)){
			$nira = $ok['NIRA'];	
		}else{
			$nira ='';
		}
		
		return $nira;
	}
	function cek_nira_dosen_yang_diasesori($nira){
		$api_url 	= URL_API_BKD.'bkd_admin/cek_nira_dosen_yang_diasesori';
		$parameter  = array('api_search' => array($nira));
		$ok = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		return $ok;
	}
	function remunerasi_dosen(){
		header("Cache-Control: no cache");
		session_cache_limiter("private_no_expire");
		#print_r($this->session->all_userdata());

		$kd_dosen = $this->session->userdata('kd_dosen');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');
		//$kd_dosen = '197701032005011003';

		$api_url 	= 'http://service2.uin-suka.ac.id/servsimpeg/index.php/remunerasi_dosen/agregasi/get_asesor_remunerasi_by_kd_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $kd_ta, $kd_smt, 1));
		$data['asesor'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		if(!empty($data['asesor'])){
			$kd_fak = $data['asesor']['kd_fakultas'];
			$fak = $kd_fak;
			$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($fak));
			$data['prodi']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);
		}else{
			//do nothing
			$data['asesor']=array();
		}
		$this->output99->output_display('asesor/data_remunerasi_dosen', $data);
	}
	function update_status_verifikasi_remun(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$kode_bk = $this->input->post('kd_bk');
		$komentar = $this->input->post('komentar');
		
		$kd_bk = json_decode($kode_bk);
		$comment = json_decode($komentar, true);

		$data_kd_bk = implode(',', $kd_bk);
		
		/*$tgl_ver = date('Y-m-d');*/


		$api_url 	= URL_API_BKD.'bkd_admin/update_status_remun';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($data_kd_bk, $kd_dosen));
		$ok = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		foreach ($comment as $key) {
				$kdbk = $key['kd_bk'];
				$kom = $key['komentar'];
				$api_url 	= URL_API_BKD.'bkd_admin/update_status_remun';
				$parameter  = array('api_kode' => 1000, 'api_subkode' => 2, 'api_search' => array($kdbk, $kom, $kd_dosen));
				$ok2 = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		if($ok=1&&$ok2=1){
			$this->session->set_flashdata('msg', array('success', 'Verifikasi data berhasil disimpan'));
		}else{
			$this->session->set_flashdata('msg', array('danger', 'Verifikasi data gagal disimpan'));
		}
		/*redirect(base_url().'bkd/verifikator/kinerja/remunerasi_dosen/'.$kd_dosen);*/
	}
	function update_status_verifikasi_sertifikasi(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$kode_bk = $this->input->post('kd_bk');
		$komentar = $this->input->post('komentar');
		
		$kd_bk = json_decode($kode_bk);
		$comment = json_decode($komentar, true);

		$data_kd_bk = implode(',', $kd_bk);

		$api_url 	= URL_API_BKD.'bkd_admin/update_status_sertifikasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($data_kd_bk, $kd_dosen));
		$ok = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		foreach ($comment as $key) {
				$kdbk = $key['kd_bk'];
				$kom = $key['komentar'];
				$api_url 	= URL_API_BKD.'bkd_admin/update_status_sertifikasi';
				$parameter  = array('api_kode' => 1000, 'api_subkode' => 2, 'api_search' => array($kdbk, $kom, $kd_dosen));
				$ok2 = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		if($ok=1&&$ok2=1){
			$this->session->set_flashdata('msg', array('success', 'Verifikasi data berhasil disimpan'));
		}else{
			$this->session->set_flashdata('msg', array('danger', 'Verifikasi data gagal disimpan'));
		}
	}

	function daftar($kd){
		$post = $this->input->post('thn');
		if($post == ''){
			$kd_ta = $this->cur_ta;
			$kd_smt = $this->cur_smt;
		}else{
			$kd_ta = $this->input->post('thn');
			$kd_smt = $this->input->post('smt');
		}

		$data['dosen'] = $this->cariNIPNamaDosen($kd);
		$data['nm_dosen'] = $this->konvertNama($kd);
		$data['status'] = $this->history->_status_DS($kd, $kd_ta, $kd_smt);
		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($data['status']);
		
		$data['ta'] = $kd_ta;
		$data['smt'] = $kd_smt;
		
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd));
		$data['noser'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/bebankerja';
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('A',$kd, $kd_ta, $kd_smt));
		$data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('B',$kd, $kd_ta, $kd_smt));
		$data['penelitian'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('C',$kd, $kd_ta, $kd_smt));
		$data['pengabdian'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('D',$kd, $kd_ta, $kd_smt));
		$data['penunjang'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		if($data['status'] == 'PR' || $data['status'] == 'PT'){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/history_data_bebankerja_prof';
			$parameter  = array('api_search' => array($kd, $kd_ta, $kd_smt));
			$data['professor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}

		
		$this->output99->output_display('asesor/daftar_kinerja', $data);
	}
	function daftar_remunerasi($kd){
		$post = $this->input->post('thn');
		if($post == ''){
			$kd_ta = $this->cur_ta;
			$kd_smt = $this->cur_smt;
		}else{
			$kd_ta = $this->input->post('thn');
			$kd_smt = $this->input->post('smt');
		}

		$data['dosen'] = $this->cariNIPNamaDosen($kd);
		$data['nm_dosen'] = $this->konvertNama($kd);
		$data['status'] = $this->history->_status_DS($kd, $kd_ta, $kd_smt);
		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($data['status']);
		
		$data['ta'] = $kd_ta;
		$data['smt'] = $kd_smt;
		
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd));
		$data['noser'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/bebankerja_remunerasi';
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('A',$kd, $kd_ta, $kd_smt));
		$data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('B',$kd, $kd_ta, $kd_smt));
		$data['penelitian'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('C',$kd, $kd_ta, $kd_smt));
		$data['pengabdian'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('D',$kd, $kd_ta, $kd_smt));
		$data['penunjang'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		if($data['status'] == 'PR' || $data['status'] == 'PT'){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/history_data_bebankerja_prof';
			$parameter  = array('api_search' => array($kd, $kd_ta, $kd_smt));
			$data['professor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}

		
		$this->output99->output_display('asesor/daftar_kinerja_remunerasi', $data);
	}
	function daftar_sertifikasi($kd){
		$post = $this->input->post('thn');
		if($post == ''){
			$kd_ta = $this->cur_ta;
			$kd_smt = $this->cur_smt;
		}else{
			$kd_ta = $this->input->post('thn');
			$kd_smt = $this->input->post('smt');
		}

		$data['dosen'] = $this->cariNIPNamaDosen($kd);
		$data['nm_dosen'] = $this->konvertNama($kd);
		$data['status'] = $this->history->_status_DS($kd, $kd_ta, $kd_smt);
		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($data['status']);
		
		$data['ta'] = $kd_ta;
		$data['smt'] = $kd_smt;
		
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd));
		$data['noser'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/bebankerja_sertifikasi';
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('A',$kd, $kd_ta, $kd_smt));
		$data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('B',$kd, $kd_ta, $kd_smt));
		$data['penelitian'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('C',$kd, $kd_ta, $kd_smt));
		$data['pengabdian'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 1, 'api_search' => array('D',$kd, $kd_ta, $kd_smt));
		$data['penunjang'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		if($data['status'] == 'PR' || $data['status'] == 'PT'){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/history_data_bebankerja_prof';
			$parameter  = array('api_search' => array($kd, $kd_ta, $kd_smt));
			$data['professor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		$this->output99->output_display('asesor/daftar_kinerja_sertifikasi', $data);
	}
	function rencana($kd){
		$post = $this->input->post('thn');
		if($post == ''){
			$kd_ta = $this->cur_ta;
			$kd_smt = $this->cur_smt;
		}else{
			$kd_ta = $this->input->post('thn');
			$kd_smt = $this->input->post('smt');
		}

		$data['dosen'] = $this->cariNIPNamaDosen($kd);
		$data['nm_dosen'] = $this->konvertNama($kd);
		$data['status'] = $this->history->_status_DS($kd, $kd_ta, $kd_smt);
		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($data['status']);
		
		$data['ta'] = $kd_ta;
		$data['smt'] = $kd_smt;
		
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd));
		$data['noser'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/bebankerja';
		$parameter  = array('api_kode'=>3000, 'api_subkode' => 1, 'api_search' => array('A',$kd, $kd_ta, $kd_smt));
		$data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>3000, 'api_subkode' => 1, 'api_search' => array('B',$kd, $kd_ta, $kd_smt));
		$data['penelitian'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>3000, 'api_subkode' => 1, 'api_search' => array('C',$kd, $kd_ta, $kd_smt));
		$data['pengabdian'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$parameter  = array('api_kode'=>3000, 'api_subkode' => 1, 'api_search' => array('D',$kd, $kd_ta, $kd_smt));
		$data['penunjang'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		if($data['status'] == 'PR' || $data['status'] == 'PT'){
			$parameter  = array('api_kode'=>3000, 'api_subkode' => 1, 'api_search' => array('E',$kd, $kd_ta, $kd_smt));
			$data['professor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}

		
		$this->output99->output_display('asesor/daftar_rencana', $data);
	}
	
	function konvertNama($kd){
		$api_url 	= URL_API_SIA.'sia_dosen/data_search';
		$parameter  = array('api_kode' => 20000, 'api_subkode' => 14, 'api_search' => array($kd));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($data)){
			return array($data[0]->NM_DOSEN_F, $data[0]->NM_PRODI);
		}
		else{
			return array('Nama belum terdata','Prodi belum disetting');
		}
	}

	
	function update_status(){
		$datapost = $this->input->post('data');
		$pecah = explode('#', $datapost);
	
		$data = array(
			'id' 	=> $pecah[0],
			'field' => $pecah[1],
			'value' => $pecah[2]
		);
		
		$api_url 	= URL_API_BKD.'bkd_admin/verifikasi';
		$parameter  = array('api_kode' => 1001, 'api_subkode' => 2, 'api_search' => array($data['id'], $data['field'], $data['value']));
		$ok = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if($ok){
			$pesan = 'Success';
		}else{
			$pesan = 'Failed';
		}
		echo $pesan;
	}

	function update_status_bk(){
		$datapost = $this->input->post('data');
		$pecah = explode('#', $datapost);
	
		$data = array(
			'id' 	=> $pecah[0],
			'field' => $pecah[1],
			'value' => $pecah[2]
		);
		
		$api_url 	= URL_API_BKD.'bkd_admin/verifikasi';
		$parameter  = array('api_kode' => 1001, 'api_subkode' => 1, 'api_search' => array($data['id'], $data['field'], $data['value']));
		$ok = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if($ok){
			$pesan = 'Success';
		}else{
			$pesan = 'Failed';
		}
		echo $pesan;
	}
	function cek_jabatan_dosen($NIP){
		//$NIP = $this->session->userdata('kd_dosen');
		//$NIP = '198205112006042002';
		//$NIP = '197701032005011003';
		$url = 'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search';

		$cek = $this->s00_lib_api->get_api_json(
			$url,
			'POST',
			array(
				'api_kode' => 1121,
				'api_subkode' => 3,
				'api_search' => array(date('d-m-Y'),$NIP,1)
			)
		);
		$kd_fak='';
		foreach ($cek as $c) {
			if($c['RR_STATUS_N']=='Aktif'){
				if($c['STR_NAMA_S2']=='Wadek Bid. Bidang Akademik' || $c['STR_NAMA_S2']=='Wadek Bid. ADUM, Perencanaan dan Keuangan'){
					$kd_unit = $c['UNIT_ID'];
					$url = 'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search';
					$cek2 = $this->s00_lib_api->get_api_json(
								$url,
								'POST',
								array(
									'api_kode' => 1901,
									'api_subkode' => 4,
									'api_search' => array(date('d/m/Y'),$kd_unit)
								)
							);
					foreach ($cek2 as $c) {
						$kd_fak = $c['SIA_KODE'];
					}
					//echo $kd_fak;
				}
			}

		}
		/*die();*/
		/*echo $kd_fak;*/
		return $kd_fak;
	}
	function get_asal_dosen(){
		$data = array(
			array('KD' =>'2', 'ASAL' => 'Semua'),
			array('KD' =>'0', 'ASAL' => 'Dosen Dalam Program Studi'),
			array('KD' =>'1', 'ASAL' => 'Dosen Luar Program Studi')
		);

		echo json_encode($data);
	}
	function get_jenis_dosen(){
		$data = array(
			array('KD' =>'2', 'JENIS' => 'Semua'),
			array('KD' =>'0', 'JENIS' => 'Dosen Tetap PNS'),
			array('KD' =>'1', 'JENIS' => 'Dosen Luar Biasa')
		);

		echo json_encode($data);
	}
	function get_status_dosen(){
		$data = array(
			array('KD' =>'2', 'STATUS' => 'Semua'),
			array('KD' =>'0', 'STATUS' => 'Aktif Mengajar'),
			array('KD' =>'1', 'STATUS' => 'Tidak Aktif Mengajar')
		);

		echo json_encode($data);
	}
	function get_data_dosen(){
		$kd_prodi 	= $this->input->post('prod');
		$asal 		= $this->input->post('asal');
		$status 	= $this->input->post('status');
		$jenis 		= $this->input->post('jenis');
		$ta = $this->session->userdata('kd_ta');
		$smt = $this->session->userdata('kd_smt');

		$dosen= $this->api->get_api_json(URL_API_SIA.'sia_penawaran/data_search', 'POST', array('api_kode'=>58002, 'api_subkode' => 9, 'api_search' => array($kd_prodi, $asal, $status)));
		$data = array();
		if($dosen){
			foreach ($dosen as $d) {
				$kd_dosen= $d['KD_DOSEN'];
				//$kd_dosen = '197701032005011003';
				//START UJI COBA
				
				$api_url 	= URL_API_BKD.'bkd_admin/cek_status_verifikasi';
				$parameter  = array('api_search' => array($kd_dosen, $ta, $smt));
				$ok = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				if($ok==0){
					$status_verifikasi = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";//Belum diverifikasi
					/*$status_verifikasi = "Belum Diverifikasi";*/
				}elseif($ok==1){
					$status_verifikasi = "<span class='badge badge-warning'> <i class='icon-white icon-info'></i></span>";//Sudah diverifikasi
					/*$status_verifikasi = "Sudah Diverifikasi tetapi belum lengkap";*/
				}elseif($ok==2){
					$status_verifikasi = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
					/*$status_verifikasi = "Sudah Diverifikasi dan sudah lengkap";*/
				}

				$api_url 	= URL_API_BKD.'bkd_admin/cek_verifikator_dan_tanggal_verifikasi';
				$parameter  = array('api_search' => array($kd_dosen, $ta, $smt));
				$cek_verifikasi = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
				if(!empty($cek_verifikasi['VERIFIKATOR'])){
					$verifikator = $this->cariNIPNamaDosen($cek_verifikasi['VERIFIKATOR']);
					$verifikator = $verifikator[0]['NM_DOSEN_F'];
					$tgl_verifikasi = $cek_verifikasi['TGL_VERIFIKASI'];
				}else{
					$verifikator = '-';
					$tgl_verifikasi = '-';
				}
				

				//END UJI COBA
				$cek_nip = substr($kd_dosen, -3, 1);

				if(($jenis == '0' && $cek_nip != '3') || ($jenis == '1' && $cek_nip == '3') || ($jenis == '2')){
					$status_dosen = $this->_status_DS_remun($kd_dosen, $ta, $smt);
					

					$data[] = array(
							'KD_DOSEN'	=> $d['KD_DOSEN'],
							'NAMA' 		=> $d['NM_DOSEN_F'],
							'JENIS'	=> $status_dosen,
							'STATUS' =>$status_verifikasi,
							'VERIFIKATOR' => $verifikator,
							'TGL_VERIFIKASI' => $tgl_verifikasi,
					);
				}
				
			}
		}
		if(!empty($data)){
			//echo json_encode($data);
			echo json_encode($data);
		}
		else{
			echo '0';
		}
	}
	function t3(){
		$kd_dosen = '197102092005011003';
		$ta = $this->session->userdata('kd_ta');
		$smt = $this->session->userdata('kd_smt');
		$status_dosen = $this->_status_DS($kd_dosen, $ta, $smt);
		echo "<pre>";
		print_r($status_dosen);
		echo "<pre>";
	}


	function t4(){
		$api_url = "http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search";
		$parameter  = array(
						'api_kode' 		=> 1121,
						'api_subkode' 	=> 5,
						'api_search' 	=> array('198012232009011007')
					);

		$data = $this->api->get_api_json($api_url, 'POST', $parameter);

		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}


	function _status_DS($kd_dosen, $kd_ta, $kd_smt){
		$struk = $this->_cek_jab_struk($kd_dosen, $kd_ta, $kd_smt);
		$fungs = $this->_cek_jab_fungsional($kd_dosen, $kd_ta, $kd_smt);
		if ($struk == 1){
			if($fungs == 1) $status = 'PT';
			else $status = 'DT';
		}else{
			if($fungs == 1) $status = 'PR';
			else $status = 'DS';
		}
		return $status;
	}

	function _status_DS_remun($kd_dosen, $kd_ta, $kd_smt){
		$struk = $this->_cek_jab_struk($kd_dosen, $kd_ta, $kd_smt);
		$fungs = $this->_cek_jab_fungsional($kd_dosen, $kd_ta, $kd_smt);
		if($struk == 2){
			$status = 'DK';
		}
		else if($struk == 1){
			if($fungs == 1) $status = 'DT';//'PT';
			else $status = 'DT';
		}else{
			if($fungs == 1) $status = 'DS';//'PR';
			else $status = 'DS';
		}
		return $status;
	}

	function _cek_jab_struk($kd_dosen, $kd_ta, $kd_smt){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' =>1121, 'api_subkode' => 5, 'api_search' => array($kd_dosen));
		$data = $this->api->get_api_jsob($api_url,'POST',$parameter);
		#return $data; die();
		$tgl_dok = $this->tanggal_akhir_ta_default($kd_ta, $kd_smt);
		$status = 0;
		foreach ($data as $jab){
			if($jab->RR_STATUS == 1 || $jab->RR_TANGGAL_AKHIR_F == ''){
				if($jab->RR_JENIS2 == 0){
					//INI UNIT BAYANGAN
					$status = 2; //if 2 maka unit bayangan
				}else{
					$status = 1;
				}
				break;
			}
			
			if($jab->RR_TANGGAL_MULAI !== '' && $jab->RR_TANGGAL_MULAI > $tgl_dok){
				$status = 0;
				break;
			}
		}
		return $status;
	}
	function _cek_jab_fungsional($kd_dosen, $kd_ta, $kd_smt){
		$api_url_jabatan 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 1122, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		$data = $this->api->get_api_jsob($api_url_jabatan,'POST',$parameter);
		if(empty($data)){
			$status = 0;
		}else{
			$fung = strtolower($data[0]->FUN_NAMA);
			if($fung == 'guru besar'){
				$status = 1;
			}else{
				$status = 0;
			}
			# sementara di 0 kan. bisa dosen bisa gubes array('status_dosen'=>'DT', 'tanggal'=>'');
			# status gubes = 1, status selain gubes = 0
			# belum ada data => asumsikan dosen 
			# intine (P = 1) dan (D = 0) // oke sip mas
		}
		return $status;
		
	}
	function tanggal_akhir_ta_default($kd_ta, $kd_smt){
		$tahun = $kd_ta + 1;
		if($kd_ta == 1){
			return "31-01-".$tahun;
		}
		else{
			return "31-08-".$tahun;
		}
	}
	function testing_verifikasi(){
		//$kd_dosen = $this->session->userdata('kd_dosen');
		//$kode_dosen = array('198205112006042002');
		$kode_dosen = array('197510242009121002');
		//$kode_dosen = array("197701032005011003");
		$ta = $this->session->userdata('kd_ta');
		$smt = $this->session->userdata('kd_smt');
		foreach ($kode_dosen as $kd) {
			$kd_dosen = $kd;
			$api_url 	= URL_API_BKD.'bkd_admin/cek_status_verifikasi';
			$parameter  = array('api_search' => array($kd_dosen, $ta, $smt));
			$ok = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if($ok==0){
				echo "Belum diverifikasi";
			}elseif($ok==1){
				echo "Sudah diverifikasi tetapi belum lengkap";
			}elseif($ok==2){
				echo "Sudah diverivikasi dan sudah lengkap";
			}
		}
		
	}
	function get_keterangan_syarat(){
		echo "<strong>Keterangan</strong>";
		
			echo "<table style='margin: 5px 0 30px 0;''>";
				echo "<tr>";
					
					echo "<td>";
						echo "<table style='margin: 5px 0 30px 0;''>
								<tbody>";
						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Status Verifikasi :</b></td></tr>";
						
						
						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "
						<tr>
							<td style='padding-left: 20px;'><span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span></td>
							<td> &nbsp; : Belum diverifikasi</b></td>
						</tr>
						";

						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "
						<tr>
							<td style='padding-left: 20px;'><span class='badge badge-warning'> <i class='icon-white icon-info'></i></span></td>
							<td> &nbsp; : Sudah diverifikasi tetapi masih ada yang perlu diperbaiki</b></td>
						</tr>
						";

						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "
						<tr>
							<td style='padding-left: 20px;'><span class='badge badge-success'> <i class='icon-white icon-ok'></i></span></td>
							<td> &nbsp; : Sudah diverifikasi dan sudah selesai</b></td>
						</tr>
						";
						echo "</tbody>
							</table>";
					echo "</td>";
					echo "<td>";
						echo "<table style='margin: 5px 0 30px 0;''>";
						echo	"<tbody>";
						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Status Dosen :</b></td></tr>";
						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "
						<tr>
							<td style='padding-left: 20px;'>DS</td>
							<td> &nbsp; : Dosen Biasa</b></td>
						</tr>
						<tr>
							<td style='padding-left: 20px;'>DT</td>
							<td> &nbsp; : Dosen dengan Tugas Tambahan</b></td>
						</tr>
						<tr>
							<td style='padding-left: 20px;'>DK</td>
							<td> &nbsp; : Dosen dengan Tugas Khusus</b></td>
						</tr>
						";
						echo 	"</tbody>";
						echo "</table>";
					echo "</td>";
				echo "</tr>
			</table>";
	}
	function testing_jabatan_dosen(){
		$NIP = '197701032005011003';
		$url = 'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search';

		$cek = $this->s00_lib_api->get_api_json(
			$url,
			'POST',
			array(
				'api_kode' => 1121,
				'api_subkode' => 3,
				'api_search' => array(date('d-m-Y'),$NIP,1)
			)
		);
		echo "<pre>";
			print_r($cek);
		echo "<pre>";

	}
	function testing_kode_unit(){
		$kd_unit = 'UN02006';
		$url = 'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search';
		$cek2 = $this->s00_lib_api->get_api_json(
					$url,
					'POST',
					array(
						'api_kode' => 1901,
						'api_subkode' => 4,
						'api_search' => array(date('d/m/Y'),$kd_unit)
					)
				);
		echo "<pre>";
			print_r($cek2);
		echo "<pre>";
	}
	function tf(){
		$kd_dosen = '197701032005011003';
		$ta = '2017';
		$smt = '2';
		$api_url 	= URL_API_BKD.'bkd_admin/cek_verifikator_dan_tanggal_verifikasi';
		$parameter  = array('api_search' => array($kd_dosen, $ta, $smt));
		$cek_verifikasi = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		echo "<pre>";
		print_r($cek_verifikasi);
		echo "</pre>";
	}


}
