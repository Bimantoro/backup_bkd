<?php
class Iku extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->view = $this->s00_lib_output;
        $this->api = $this->load->library('s00_lib_api');
        $this->url = $this->load->library('lib_url');
        $this->user = $this->load->library('lib_user', $this->session->all_userdata());
        $this->util = $this->load->library('lib_util');
        $this->lib_skp = $this->load->library('lib_skp'); 
        $this->lib_remun = $this->load->library('lib_remun'); 
        $this->simpeg = $this->load->module('remunerasi/simpeg');

        $this->session->set_userdata('app', 'iku_app');

        if (!$this->lib_user->is_login()) {
            redirect();
        }
        /*if ($this->user->get_id() != '199303110000001101') {
            //$this->masalah();
        } else {
            $this->user->set_id('198206012005012017');
        }*/
    }
    
    function form_iku() {
		/*
		$par	= array('kd_eselon is null');
        $data['template'] = $this->api->post($this->url->remunerasi . '/aspek_iku', $par);
        $data['periode'] = date('Y');
        
        $data['pegawai'] = $this->simpeg->pegawai($this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
        $data['penilai'] = $this->simpeg->atasan($this->user->get_id(), date('d-m-Y'));
		$data['penilai']['JABATAN'] = $this->simpeg->jabatan($data['penilai']['DETAIL']['KD_PGW'], date('d-m-Y'));
		

        $this->view->output_display('form_iku_baru', $data);
        */

		$par	= array('id'=>$this->user->get_id(), 'periode'=>date('Y'));
        $sasaran_iku = $this->api->post($this->url->remun . '/sasaran_iku', $par);
        
        if (empty($sasaran_iku)){
			$this->form_iku_baru();
		} else {
			$id= $sasaran_iku[0]['ID'];
			$this->form_iku_edit($id);
		}
    }
    
	function form_iku_baru(){
		$par	= array('kd_eselon is null');
        $data['template'] = $this->api->post($this->url->remun . '/aspek_iku', $par);
        $data['periode'] = date('Y');
        
        $data['pegawai'] = $this->simpeg->pegawai($this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
        $data['penilai'] = $this->simpeg->atasan($this->user->get_id(), date('d-m-Y'));
		$data['penilai']['JABATAN'] = $this->simpeg->jabatan($data['penilai']['DETAIL']['KD_PGW'], date('d-m-Y'));
		

        $this->view->output_display('form_iku_baru', $data);
	}

	function form_iku_edit($id){
        $data['periode'] 	= date('Y');
        
		$kd_pgw				= substr($id, 0, -4);
		$par				= array('id'=>$id);
        $data['kegiatan'] 	= $this->api->post($this->url->remun . '/kegiatan_iku', $par);
        $data['pegawai'] 	= $this->simpeg->pegawai($kd_pgw);
        $data['pegawai']['JABATAN'] = $this->simpeg->jabatan($kd_pgw, date('d-m-Y'));
        $data['penilai'] 	= $this->simpeg->atasan($kd_pgw, date('d-m-Y'));
		$data['penilai']['JABATAN'] = $this->simpeg->jabatan($data['penilai']['DETAIL']['KD_PGW'], date('d-m-Y'));
		

        $this->view->output_display('form_iku_edit', $data);
	}
	
	function simpan_iku(){
		$this->util->pre($this->api->post($this->url->remun . '/simpan_rencana_iku', $_POST));
	}
	
	function form_realisasi(){
		//$triwulan			= $this->uri->segment(4);
		$idiku				= $this->uri->segment(4);
		$triwulan			= $this->uri->segment(5);
        $data['triwulan'] 	= $triwulan;
        $data['periode'] 	= date('Y');
        if ($triwulan){
			$par				= array('id'=>$idiku, 'triwulan'=>$triwulan);
			$data['kegiatan'] 	= $this->api->post($this->url->remun . '/realisasi_iku', $par);
		//} else {
			//$par				= array('id'=>$idiku);
			//$data['kegiatan'] 	= $this->api->post($this->url->remun . '/kegiatan_iku', $par);
		}
        
        $kd_pgw = ($idiku) ? substr($idiku, 0, -4) : $this->user->get_id();
        $data['pegawai'] 	= $this->simpeg->pegawai($kd_pgw);
        //$data['pegawai']['JABATAN'] = $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
        $data['penilai'] 	= $this->simpeg->atasan($kd_pgw, date('d-m-Y'));
		//$data['penilai']['JABATAN'] = $this->simpeg->jabatan($data['penilai']['DETAIL']['KD_PGW'], date('d-m-Y'));
		

        $this->view->output_display('form_realisasi_iku', $data);
	}

	function target_triwulan(){
		//$triwulan			= $this->uri->segment(4);
		$idiku				= $this->uri->segment(4);
		$triwulan			= $this->uri->segment(5);
        $data['triwulan'] 	= $triwulan;
        $data['periode'] 	= date('Y');
        if ($triwulan){
			$par				= array('id'=>$idiku, 'triwulan'=>$triwulan);
			$data['kegiatan'] 	= $this->api->post($this->url->remun . '/realisasi_iku', $par);
		//} else {
			//$par				= array('id'=>$idiku);
			//$data['kegiatan'] 	= $this->api->post($this->url->remun . '/kegiatan_iku', $par);
		}
        
        $kd_pgw = ($idiku) ? substr($idiku, 0, -4) : $this->user->get_id();
        $data['pegawai'] 	= $this->simpeg->pegawai($kd_pgw);
        //$data['pegawai']['JABATAN'] = $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
        $data['penilai'] 	= $this->simpeg->atasan($kd_pgw, date('d-m-Y'));
		//$data['penilai']['JABATAN'] = $this->simpeg->jabatan($data['penilai']['DETAIL']['KD_PGW'], date('d-m-Y'));
		

        $this->view->output_display('form_target_triwulan', $data);
	}

    
	function simpan_target_triwulan(){
		$this->util->pre($this->api->post($this->url->remun . '/simpan_target_triwulan', $_POST));
		//echo json_encode($_POST);
	}
	
	function hapus_kegiatan_iku(){
		$params = $_POST;
		$params['kd_pegawai'] 	= $this->user->get_id();
		$params['periode']		= $periode = date('Y');
		$this->util->pre($this->api->post($this->url->remun . '/hapus_kegiatan_iku', $params));
		//echo json_encode($params);
	}
	
	function simpan_realisasi(){
		$this->util->pre($this->api->post($this->url->remun . '/simpan_realisasi', $_POST));
		//echo json_encode($_POST);
	}
	
	function penilaian(){
		$tanggal = date('d-m-Y');
        $data['pegawai'] 			= $this->simpeg->pegawai($this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
        $data['bawahan'] 			= $this->simpeg->bawahan($this->user->get_id());
		
        $this->view->output_display('riwayat_penilaian_iku', $data);
	}
	
	//admin dan pegawai yang membuat iku
	function cetak(){
        $periode = date('Y');
        $data['pegawai'] 			= $this->api->post($this->url->skp . '/pegawai/' . $this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->api->post($this->url->skp . '/jabatan/' . $this->user->get_id() . '/' . date('d-m-Y'));
        $data['periode']			= $periode;

        $this->view->output_display('cetak_capaian_iku', $data);
	}
	
	//seluruh pegawai
	function cetak_iku(){
        $periode = date('Y');
        $data['periode']= $periode;
		$data['jabatan']= $this->api->post($this->url->remun . '/jabatan_iku');
        $this->view->output_display('cetak_capaian_iku_pegawai', $data);
	}
	
	function riwayat_iku_bawahan($kd_bawahan=''){
		$data['pegawai'] 	= $this->api->post($this->url->skp . '/pegawai/' . $this->user->get_id());
		$data['bawahan'] 	= $this->api->post($this->url->skp . '/pegawai/' . $kd_bawahan);
        $data['kd_pgw'] 	= $kd_bawahan;
		$par				= array('id'=>$kd_bawahan . date('Y'));
        $data['triwulan'] 	= $this->api->post($this->url->remun . '/periode_realisasi', $par);
		$this->view->output_display('riwayat_iku_bawahan', $data);
	}
	
	function form_penilaian_iku($kd_bawahan='', $triwulan=''){
        $data['triwulan'] 	= $triwulan;
        $data['periode'] 	= date('Y');
        
		$data['pegawai'] 	= $this->api->post($this->url->skp . '/pegawai/' . $this->user->get_id());
		$data['bawahan'] 	= $this->api->post($this->url->skp . '/pegawai/' . $kd_bawahan);
		
		$par				= array('id'=>$kd_bawahan . date('Y'), 'triwulan'=>$triwulan);
		$data['iku']	 	= $this->api->post($this->url->remun . '/realisasi_iku', $par);
		$this->view->output_display('form_penilaian_iku', $data);
	}
	
	function detail_triwulan(){
		$kd_pgw				= $this->uri->segment(4);
		$triwulan			= $this->uri->segment(5);
        $data['triwulan'] 	= $triwulan;
        $data['periode'] 	= date('Y');
        $idiku				= $kd_pgw . date('Y');
		$par				= array('id'=>$idiku, 'triwulan'=>$triwulan);
		$data['kegiatan'] 	= $this->api->post($this->url->remun . '/realisasi_iku', $par);
        
        $data['bawahan'] 	= $this->simpeg->pegawai($kd_pgw);
        $data['pegawai'] 	= $this->simpeg->pegawai($this->user->get_id());		

        $this->view->output_display('detail_triwulan_iku', $data);
	}

	function pdf_capaian_iku($id, $periode, $bulan=null){
		$parameters = array('api_kode'		=> 1121,
							'api_subkode' 	=> 9,
							'api_search'	=> array(date('d/m/Y'), $id, 1) //tanggal = 'DD/MM/YYYY'	
							);
							
		$data 		= $this->api->get_api_json($this->url->simpeg_search, 'POST', $parameters);
		// log_message('error', 'pegawai: ' . json_encode($data));
		$pegawai	= (!empty($data)) ? current($data):null;
		$kd_pegawai = (array_key_exists('NIP', $pegawai)) ? $pegawai['NIP'] : null;
		
		// log_message('error', 'pegawai: ' . json_encode($pegawai));
		
        // $id_sasaran 	 	= $id . $periode;
        $id_sasaran 	 	= $kd_pegawai . $periode;
		
		$par				= array('id'=>$id_sasaran);
        $data['kegiatan'] 	= $this->api->post($this->url->remun . '/kegiatan_iku', $par);
        $data['pegawai'] 	= $this->simpeg->pegawai($kd_pegawai);
        $data['penilai'] 	= $this->simpeg->atasan($kd_pegawai, date('d-m-Y'));
		$data['periode']	= $periode;
		$kd_jab				= $data['pegawai']['JABATAN']['STR_ID'];
		log_message('error', 'Kode jab: ' . $kd_jab);
		$parameter			= array('api_kode' => 110012, 'api_subkode' => 2, 'api_search' => array("'$kd_jab'"));
		$data['psd']		= $this->api->postsurat('tnde_master/get_psd', $parameter);
		log_message('error', json_encode($data['psd']));
		//print_r($data);
        $this->printPDF('remunerasi/pdf_capaian_iku', $data, 'kontrak_kerja_' . $id);
        
	}
	
	function printPDF($view, $data, $output, $settings=null){
		$laporan 	= $this->load->module('remunerasi/laporan');
		$html 		= $this->load->view($view, $data, true);
		
		$laporan->pdf->loadHtml($html);
		$laporan->pdf->render();
		$laporan->setSubject(ucwords(str_replace('_', ' ', $str=$output)));
		$laporan->pdf->stream($output,array('Attachment'=>0));
	}

	function ajax_kel_aspek(){
		if(ISSET($_POST['q'])){
			$q = $_POST['q'];
			
			$parameter 	= array('q'=> $q);
			$grup 		= $this->api->post($this->url->remun . '/grup_iku', $parameter);
			
			$tmp 		= array();
			foreach($grup as $val){
				$tmp[] = array('id' => $val['NM_GRUP_IKU'], 'name' => $val['NM_GRUP_IKU']);
			}
			echo (!empty($tmp)) ? json_encode($tmp) : 0;
		}
	}
    
	function ajax_aspek(){
		if(ISSET($_POST['q'])){
			$g = $_POST['g'];
			$q = $_POST['q'];
			
			$parameter 	= array('g'=> $g, 'q'=> $q);
			$grup 		= $this->api->post($this->url->remun . '/aspek_iku', $parameter);
			
			$tmp 		= array();
			foreach($grup as $val){
				$tmp[] = array('id' => $val['ID_GRUP_IKU'], 'name' => $val['NM_ASPEK']);
			}
			echo (!empty($tmp)) ? json_encode($tmp) : 0;
		}
	}
	
	function ajax_uraian(){
		if(ISSET($_POST['q'])){
			$g = $_POST['g'];
			$q = $_POST['q'];
			$j = $_POST['j'];
			
			$parameter 	= ($j) ? array('g'=> $g, 'q'=> $q, 'jabatan'=>$j) : array('g'=> $g, 'q'=> $q);
			//$parameter 	= array('g'=> $g, 'q'=> $q);
			$grup 		= $this->api->post($this->url->remun . '/uraian_iku', $parameter);
			
			$tmp 		= array();
			foreach($grup as $val){
				$tmp[] = array('id' => $val['ID_IKU'], 'name' => $val['URAIAN']);
			}
			echo (!empty($tmp)) ? json_encode($tmp) : 0;
		}
	}
	
	function ajax_realisasi(){
		if(ISSET($_POST['periode'])){
			$g = $_POST['periode'];
			
			$par	= array('id'=>$this->user->get_id() . date('Y'), 'triwulan'=>$g);
			$data 	= $this->api->post($this->url->remun . '/realisasi_iku', $par);
			echo (!empty($data)) ? json_encode($data) : 0;
		}
	}

	function bawah(){
		$pegawai = $this->simpeg->pegawai($this->user->get_id());
		//return $this->simpeg_jabatan_struktural($kd_pegawai);
        print_r(json_encode($pegawai));
        echo '<br>';
        echo $pegawai['JABATAN']['STR_ID'];
        echo '<br>';
		$kd_jabatan_pegawai = $this->simpeg->simpeg_jabatan_struktural($this->user->get_id())[0]['RR_STR_ID'];
		//return $this->simpeg_jabatan_struktural($kd_pegawai);
        print_r($kd_jabatan_pegawai);
        echo '<br>';
		$data = $this->simpeg->simpeg_ranting_pegawai('bawah', $kd_pegawai)[$kd_jabatan_pegawai];
        //$data	= $this->simpeg->bawahan($this->user->get_id());
        print_r($data);
	}
	
	function periode_re(){
		$par	= array('id'=>'197510122000031002' . date('Y'));
        $data 	= $this->api->post($this->url->remun . '/periode_realisasi', $par);
        print_r($data);
	}
	
	function jabt($q){
		$parameter = array('api_kode' => 1101, 'api_subkode' => 2, 'api_search' => array($q, 1));
		$simpeg = $this->api->post($this->url->simpeg_search, $parameter);
		
		print_r($simpeg);
	}
	
	function implode_to_string($data){
		$hasil = '';
		if(!empty($data)){
			foreach($data as $val){
				$tmp[] = "'".$val."'";
			}
			$hasil = implode(",", $tmp);
		}
		return $hasil;
	}
	function psd(){
		$parameter	= array('api_kode' => 110012, 'api_subkode' => 2, 'api_search' => array("'ST000141'"));
		$pa		= $this->api->postsurat('tnde_master/get_psd', $parameter);
		print_r($pa);
	}
}
?>
