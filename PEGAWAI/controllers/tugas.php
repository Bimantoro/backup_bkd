<?php
class Tugas extends CI_Controller {
	function __construct() {
		parent::__construct();
        $this->view 	= $this->s00_lib_output;
        $this->api 		= $this->load->library('s00_lib_api');
        $this->url 		= $this->load->library('lib_url');
        $this->user 	= $this->load->library('lib_user', $this->session->all_userdata());
        $this->util 	= $this->load->library('lib_util');
        $this->lib_skp 	= $this->load->library('lib_skp'); 
        $this->lib_remun = $this->load->library('lib_remun'); 
        $this->simpeg 	= $this->load->module('remunerasi/simpeg');
        
        $this->session->set_userdata('app', 'remunerasi_app');
        
		if (!$this->user->is_login()) {
			redirect();
		}

		error_reporting(0);
	}

	function pegawai_adl_pejabat($id){
		$ljabatan 	= $this->api->post($this->url->remun . '/jabatan_iku');
		$parameter 	= array('api_kode' => 1121, 'api_subkode' => 3, 'api_search' => array(date('d-m-Y'), $id, 1)); #tgl, kd_pgw, status aktif/tidak aktif
		$data 		= $this->api->post($this->url->simpeg_search, $parameter);
		$jabatan	= (!empty($data)) ? $data[0]['STR_ID'] : '';

		$pejabat = FALSE;
		if ($jabatan === '7654321V'){ //rektor
			$pejabat = TRUE;
		} else {
			if (!empty($jabatan)){
				for($i=0;$i<count($ljabatan);$i++){
					$jab = $ljabatan[$i];
					$pejabat = ($jabatan==$jab['STR_ID']);
					if ($pejabat) { break; }
				}
			}
		}
		
		//echo ($pejabat) ? "Pejabat" : "Bukan Pejabat";
		return $pejabat;
	}
	
	function tambahan_pegawai(){
        $pejabat 			= $this->pegawai_adl_pejabat($this->user->get_id());
		$dosen				= $this->simpeg->data_dosen($this->user->get_id());
		if (!empty($pejabat))
			$kode = 'E';
		else if (!empty($dosen))
			$kode = 'D';
		else $kode = 'P';
		$data['kode']		= $kode;
				
        $this->view->output_display('form_tugas_tambahan', $data);
	}
	
	function tambahan_manajerial(){
		$data['atasan']		= $this->simpeg->atasan($this->user->get_id(), date('d-m-Y'));
		//$data['bawahan'] 	= $this->simpeg->bawahan($this->user->get_id());
		$data['sejawat']	= $this->simpeg->kolega($this->user->get_id());
		//$data['sejawat']	= null;
        $this->view->output_display('nilai_perilaku_manajerial', $data);
	}
	
	function ajax_tugas(){
		if(ISSET($_POST['q'])){
			$q = $_POST['q'];
			$parameter 	= array('search'=> $q);
			$grup 		= $this->api->post($this->url->remun . '/tugas_tambahan', $parameter);
			
			$tmp 		= array();
			foreach($grup as $val){
				$tmp[] = array('id' => $val['ID_TUGAS'], 'name' => $val['NM_TUGAS']);
			}
			echo (!empty($tmp)) ? json_encode($tmp) : 0;
		}
	}
	
	function simpan_tugas(){
		//$this->util->pre($this->api->post($this->url->remun . '/simpan_tugas_tambahan', $_POST));
		$simpan = $this->api->post($this->url->remun . '/simpan_tugas_tambahan', $_POST);
		echo ($simpan) ? notifications('Tugas tambahan telah disimpan', 'success') : notifications('Tugas tambahan telah disimpan', 'errors');		
	}
	
	function penilaian_manajerial(){
		$triwulan			= $this->uri->segment(4);
		$data['triwulan']	= $triwulan;
		$data['periode']	= date('Y');
        $data['pegawai'] 	= $this->simpeg->pegawai($this->user->get_id());
		$data['atasan']		= $this->simpeg->atasan($this->user->get_id(), date('d-m-Y'));
		$data['nilai']		= $this->api->post($this->url->remun . '/nilai_manajerial', array('search'=>'ATASAN', 'penilai'=>$this->user->get_id(), 'pegawai'=>$data['atasan']['DETAIL']['KD_PGW'], 'tahun'=>date('Y'), 'periode'=>$triwulan));
		$data['kuesioner']	= $this->api->post($this->url->remun . '/kuesioner', array('search'=>'ATASAN'));
        $this->view->output_display('form_nilai_manajerial', $data);
	}
	
	function simpan_penilaian_manajerial(){
		//$this->util->pre($this->api->post($this->url->remun . '/simpan_nilai_manajerial', $_POST));
		echo json_encode(array('hasil'=>$this->api->post($this->url->remun . '/simpan_nilai_manajerial', $_POST)));
	}
	
	function update_penilaian_manajerial(){
		//$this->util->pre($this->api->post($this->url->remun . '/update_nilai_manajerial', $_POST));
		echo json_encode(array('hasil'=>$this->api->post($this->url->remun . '/update_nilai_manajerial', $_POST)));
	}
	
	function penilaian_sejawat(){
		$id_kolega			= $this->uri->segment(4);
		$triwulan			= $this->uri->segment(5);
		$data['triwulan']	= $triwulan;
		$data['periode']	= date('Y');
        $data['pegawai'] 	= $this->simpeg->pegawai($this->user->get_id());
		$data['kolega']		= $this->simpeg->pegawai($id_kolega, date('d-m-Y'));
		$data['nilai']		= $this->api->post($this->url->remun . '/nilai_manajerial', array('search'=>'SEJAWAT', 'penilai'=>$this->user->get_id(), 'pegawai'=>$id_kolega, 'tahun'=>date('Y'), 'periode'=>$triwulan));
		$data['kuesioner']	= $this->api->post($this->url->remun . '/kuesioner', array('search'=>'SEJAWAT'));
        $this->view->output_display('form_nilai_manajerial_s', $data);
	}
		
}
?>
