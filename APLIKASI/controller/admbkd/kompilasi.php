<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Kompilasi extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library(array('form_validation','pagination'));
		$this->load->library('bkd_lib_setting','' ,'setting');
		$this->load->library('bkd_lib_history','' ,'history');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}
	
	# pagination configuration API
	function paging_config($link, $total, $hal, $ovr = array()){
		$config = array();
		$config['total_rows'] = $total;
		$config['cur_page'] = (int)preg_replace("/[^0-9]/", "", $hal);
		
		if(!array_key_exists('base_url',$ovr)) { $config['base_url'] = site_url('archive/'.$link.'/'); }
		else { $config['base_url'] = site_url($ovr['base_url'].$link); }
		if(!array_key_exists('per_page',$ovr)) { $config['per_page'] = 10; } else { $config['per_page'] = $ovr['per_page']; }
		if(!array_key_exists('uri_segment',$ovr)) { $config['uri_segment'] = 3; } else { $config['uri_segment'] = $ovr['uri_segment']; }
		if(!array_key_exists('prefix',$ovr)) { $config['prefix'] = 'page'; } else { $config['prefix'] = $ovr['prefix']; }
		if(!array_key_exists('suffix',$ovr)) { $config['suffix'] = '.html'; } else { $config['suffix'] = $ovr['suffix']; }
		if(!array_key_exists('first_url',$ovr)) { $config['first_url'] = 'page0.html'; } else { $config['first_url'] = $ovr['first_url']; }
		if(!array_key_exists('use_page_numbers',$ovr)) { $config['use_page_numbers'] = true; } else { $config['use_page_numbers'] = $ovr['use_page_numbers']; }
		
		return $config;
	}

	function index($hal=1){
		if(isset($_POST['thn'])){
			#$ta = $_POST['thn'];
			$this->session->unset_userdata('ta');
			$this->session->set_userdata('ta', $_POST['thn']);
		}
		
		$ta = $this->session->userdata('ta');

			$data['dosen_tetap'] = $this->dosen_tetap();
			# CONFIGURASI PAGINASI
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
			$parameter  = array('api_kode'=>1000, 'api_subkode'=> 100, 'api_search' => array($ta));
			$tot_page = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			#echo $tot_page;
			
			$config['base_url'] = base_url().'bkd/admbkd/kompilasi/index/';
			$config['total_rows'] = $tot_page;
			$config['per_page'] = 20;		
			$config["uri_segment"] = 5;
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = 'Selanjutnya';
			$config['prev_link'] = 'Sebelumnya';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li><a href="#" style="font-weight:bold;background-color:#f5f5f5">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_links'] = 2;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['first_link'] = 'Pertama';
			$config['last_link'] = 'Terakhir';
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
			$data['links'] = $this->pagination->create_links(); 
			$batas_akhir='';
			if($page > 1){
				$limit = $page + 20;
			}else{
				$limit = 20;
			}		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($ta, $limit, $this->uri->segment(5)));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$data['namaDosen'] = array();
		if(!empty($data['kompilasi'])){
			foreach ($data['kompilasi'] as $a){
				$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			}
		}
		
		# CREATE LABEL HEADING
		$data['ta'] = $ta;
		$data['dept'] = "Universitas Islam Negeri Sunan Kalijaga Yogyakarta";
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/kompilasi_univ',$data);
	}
	
	# KOMPILASI DOSEN 
	# ===============================================================
	function dosen($tingkat = ''){
		$ta = $this->session->userdata('ta');
		$data['ta'] = $ta;
		$keyword = $this->input->post('keyword');
		if(isset($_POST['keyword'])){
			$subkey = $this->input->post('subkey');
			$data['keyword'] = $keyword;
			$data['subkey'] = $subkey;
			
			# CEK ADMIN TINGKAT
			if($tingkat == 'fakultas'){
				$id = $this->session->userdata('adm_fak');
				$subkode = 29;
				$param = array($data['subkey'], $data['keyword'], $ta, $id);
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
				$parameter  = array('api_kode' => 21041991, 'api_subkode' => 1, 'api_search' => array($id));
				$data['devisi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}else if ($tingkat == 'prodi'){
				$id = $this->session->userdata('adm_pro');
				$subkode = 39;
				$param = array($data['subkey'], $data['keyword'], $ta, $id);
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
				$parameter  = array('api_kode' => 21041991, 'api_subkode' => 2, 'api_search' => array($id));
				$data['devisi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}else{
				$subkode = 9;
				$param = array($data['subkey'], $data['keyword'], $ta);
				$data['devisi'] = "Universitas Islam Negeri Sunan Kalijaga";
			}
			
			#print_r($param); echo $subkode

			if($data['subkey'] == 'C.NIP') $ls = 'NIP'; else $ls = 'Nama Dosen';
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
			$parameter  = array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => $param);
			$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if (empty($data['kompilasi'])){
				$data['label'] = "<div class='bs-callout bs-callout-error'>Pencarian <b>".$ls."</b> dengan keyword : <b>".$keyword."</b> tidak ditemukan";			
				#$data['jenis_dosen'] = $this->history->_status_DS($kd_dosen, $kd_ta, $kd_smt);
			}else{
				#$data['jenis_dosen'] = $this->history->_status_DS($kd_dosen, $kd_ta, $kd_smt);
				$data['label'] = '';
			}
		}else{
			$data['label'] = '';
		}
		
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/cari_dosen',$data);
	}
	
	
	
	# ======================================================== KOMPILASI SEMESTER  =====================================================
	function semester($tingkat = ''){
		$ses_ta = $this->session->userdata('ta');
		$ses_smt = $this->session->userdata('smt');
		# set parameter 
		if(isset($_POST['thn'])){			
			$ta = $this->input->post('thn');
			$smt = $this->input->post('smt');
			# create new session ta/smt
			$this->session->set_userdata('ta', $ta);
			$this->session->set_userdata('smt', $smt);
		}else{
			$this->session->unset_userdata('ta');
			$this->session->unset_userdata('smt');
			$this->session->set_userdata('ta', $ses_ta);
			$this->session->set_userdata('smt', $ses_smt);				
		}
			
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
    
        $kd_ta = $this->setting->_generate_kd_ta($data['ta']);
        $kd_smt = $this->setting->_generate_kd_smt($data['smt']);
        #echo $kd_ta.'=='.$kd_smt;
		$data['dosen_tetap'] = $this->dosen_tetap();

		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($data['ta']));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$tot_page = count($data['kompilasi']);
		# paging
			$config['base_url'] = base_url().'bkd/admbkd/kompilasi/semester/';
			$config['total_rows'] = $tot_page;
			$config['per_page'] = 20;		
			$config["uri_segment"] = 5;
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = 'Selanjutnya';
			$config['prev_link'] = 'Sebelumnya';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li><a href="#" style="font-weight:bold;background-color:#f5f5f5">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_links'] = 2;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['first_link'] = 'Pertama';
			$config['last_link'] = 'Terakhir';
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
			$data['links'] = $this->pagination->create_links(); 
			$batas_akhir='';
			if($page > 1){
				$limit = $page + 20;
			}else{
				$limit = 20;
			}		
/* 		if($tingkat == 'fakultas'){
			$id = $this->session->userdata('adm_fak'); $a = 1;
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
			$parameter  = array('api_kode' => 1000, 'api_subkode' => 21, 'api_search' => array($data['ta'], $id));
			$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data['kompilasi'])){
			    foreach ($data['kompilasi'] as $a){
			        $data['ds']['_'.$a->KD_DOSEN] = $this->history->_status_DS($a->KD_DOSEN, $kd_ta, $kd_smt);
					$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			    }
			}
		}else if($tingkat == 'prodi'){
			$id = $this->session->userdata('adm_pro'); $a = 2;
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
			$parameter  = array('api_kode' => 1000, 'api_subkode' => 31, 'api_search' => array($data['ta'], $id));
			$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data['kompilasi'])){
			    foreach ($data['kompilasi'] as $a){
			        $data['ds']['_'.$a->KD_DOSEN] = $this->history->_status_DS($a->KD_DOSEN, $kd_ta, $kd_smt);
					$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			    }
			}
		}else{ */
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
			$parameter  = array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($data['ta'], $limit, $this->uri->segment(5)));
			$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data['kompilasi'])){
			    foreach ($data['kompilasi'] as $a){
			        $data['ds']['_'.$a->KD_DOSEN] = $this->history->_status_DS($a->KD_DOSEN, $kd_ta, $kd_smt);
					$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			    }
			}
		#}
		#print_r($data);
		if($this->session->userdata('adm_univ') == ''){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
			$parameter  = array('api_kode' => 21041991, 'api_subkode' => $a, 'api_search' => array($id));
			$data['dept'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}else{
			$data['dept'] = 'UNIVERSITAS ISLAM NEGERI SUNAN KALIJAGA YOGYAKARTA';
		}


		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/kompilasi_semester',$data);
	}
	
	
	function detail($kd_dosen){
		$kd_dosen = $this->uri->segment(5);
		$thn = $this->session->userdata('ta');
		

		$data['dosen'] = $this->dataDosen($kd_dosen);
		$data['dosenBkd'] = $this->getDataDosenBKD($kd_dosen);
		$data['dosenSia'] = $this->getDataDosenSIA($kd_dosen);
		$data['namaFakultas'] = $this->namaFak($data['dosenBkd'][0]->KD_FAK);
		$data['namaProdi'] = $this->namaProdi($data['dosenBkd'][0]->KD_PRODI);

		# pendidikan 
		$data['kode'] = 'A';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, 'GANJIL'));
		$data['pd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# beban kerja penelitian
		$data['kode'] = 'B';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, 'GANJIL'));
		$data['pl'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# beban kerja pengabdian masyarakat
		$data['kode'] = 'C';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, 'GANJIL'));
		$data['pk'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# beban kerja tambahan
		$data['kode'] = 'D';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, 'GANJIL'));
		$data['pg'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		# pendidikan semester 2
		$data['kode'] = 'A';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, 'GENAP'));
		$data['pd2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# beban kerja penelitian
		$data['kode'] = 'B';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, 'GENAP'));
		$data['pl2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# beban kerja pengabdian masyarakat
		$data['kode'] = 'C';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, 'GENAP'));
		$data['pk2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# beban kerja tambahan
		$data['kode'] = 'D';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, 'GENAP'));
		$data['pg2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/detail_bkd_dosen',$data);
	}

	
	function getDataDosenBKD($kd_dosen){
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd_dosen));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data;
	}
	

	function getDataDosenSIA($kd_dosen){
		$api_url 	= URL_API_SIA.'sia_dosen/data_search';
		$parameter  = array('api_kode'=>20000, 'api_subkode'=>3, 'api_search' => array($kd_dosen));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data;
	}
	
	function dosen_tetap(){
		#sia_dosen/data_view 20000/7
		$api_url 	= URL_API_SIA.'sia_dosen/data_view';
		$parameter  = array(
						'api_kode' 		=> 20000,
						'api_subkode' 	=> 8,
						'api_search' 	=> array()
					);
		$xxx = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$data['kd_dosen_tetap'] = array();
		$no=0; foreach ($xxx as $kd){
			$data['kd_dosen_tetap'][$no] = $kd->KD_DOSEN;
			$no++;
		}
		return $data['kd_dosen_tetap'];
	}
	
	
	
	function fakultas(){
		$ta = $this->session->userdata('ta');		
		$kd_fak = $this->session->userdata('adm_fak');
			$data['ta'] = $ta;
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
			$parameter  = array('api_kode' => 21041991, 'api_subkode' => 1, 'api_search' => array($kd_fak));
			$data['dept'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 21, 'api_search' => array($ta, $kd_fak));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$data['dosen_tetap'] = $this->dosen_tetap();
			

		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/kompilasi_univ',$data);
	}

	function prodi(){
		$ta = $this->session->userdata('ta');		
		$kd_prodi = $this->session->userdata('adm_pro');
		
			$data['ta'] = $ta;
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
			$parameter  = array('api_kode' => 21041991, 'api_subkode' => 2, 'api_search' => array($kd_prodi));
			$data['dept'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 31, 'api_search' => array($ta, $kd_prodi));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$data['dosen_tetap'] = $this->dosen_tetap();

		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/kompilasi_univ',$data);
	}
	
	function publikasi($tingkat = '', $hal = 1){
		$data['title'] = 'Publikasi';
		$ta = $this->input->post('thn');
		$smt = $this->input->post('smt');
		if($ta == ''){
			$data['ta'] = $this->session->userdata('thn');
			$data['smt'] = $this->session->userdata('smt');
		}else{
			$data['ta'] = $ta;
			$data['smt'] = $smt;
		}
		# CREATE PAGING
		$table 		= 'BKD.BKD_DATA_PUBLIKASI';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000000, 'api_subkode' => 1, 'api_search' => array($table));
		$tot_record = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		if($tingkat == 'fakultas'){
			$kd = $this->session->userdata('adm_fak');
			$subkode = 21;
			$param = array($kd);
		}else if ($tingkat == 'prodi'){
			$kd = $this->session->userdata('adm_pro');
			$subkode = 31;
			$param = array($kd);
		}else{
			$subkode = 1;
			$param = array();		
		}
		
			$config['base_url'] = base_url().'bkd/admbkd/kompilasi/publikasi/';
			$config['total_rows'] = $tot_record;
			$config['per_page'] = 3;		
			$config["uri_segment"] = 5;
			$config['full_tag_open'] = '<div class="pagination"><ul>';
			$config['full_tag_close'] = '</ul></div>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = 'Selanjutnya';
			$config['prev_link'] = 'Sebelumnya';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li><a href="#" style="font-weight:bold;background-color:#f5f5f5">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_links'] = 2;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['first_link'] = 'Pertama';
			$config['last_link'] = 'Terakhir';
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
			$data['links'] = $this->pagination->create_links(); 
			$batas_akhir='';
			if($page > 1){
				$limit = $page + 3;
			}else{
				$limit = 3;
			}					
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1001, 'api_subkode' => $subkode, 'api_search' => $param);
		$data['publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/data_publikasi',$data);
	}
	
	function publikasi_dosen(){
		$data['subkey'] = $this->input->post('subkey');
		$data['keyword'] = strtolower($this->input->post('keyword'));
		
		$level = $this->session->userdata('adm_univ');
		if($level == 'BKDADM'){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/publikasi';
			$parameter  = array('api_kode' => 9900, 'api_subkode' =>111, 'api_search' => array($data['subkey'], $data['keyword']));
			$data['publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		
		
		# GET NAMA Dosen
		if(!empty($data['publikasi'])){
			$data['nm_dosen'] = array();
			foreach ($data['publikasi'] as $pub){
				$data['nm_dosen']['_'.$pub->KD_DOSEN] = $this->konvertNama($pub->KD_DOSEN);
			}
		}

		
		#$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/cari_dosen_pub',$data);
	}
	
	function publikasi_semester($tingkat = ''){
		if(isset($_POST['thn'])){
			$this->session->unset_userdata('ta');
			$this->session->set_userdata('ta', $_POST['thn']);
			$this->session->unset_userdata('smt');
			$this->session->set_userdata('smt', $_POST['smt']);
		}
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');

		$data['ta'] = $ta;
		$data['smt'] = $smt;
		$data['title'] = 'Publikasi';
		# CREATE PAGING
		$table 		= 'BKD.BKD_DATA_PUBLIKASI';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000000, 'api_subkode' => 2, 'api_search' => array($table, $ta, $smt));
		$tot_record = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				
			$config['base_url'] = base_url().'bkd/admbkd/kompilasi/publikasi_semester/';
			$config['total_rows'] = $tot_record;
			$config['per_page'] = 2;		
			$config["uri_segment"] = 5;
			$config['full_tag_open'] = '<div class="pagination pull-left"><ul>';
			$config['full_tag_close'] = '</ul></div>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = 'Selanjutnya';
			$config['prev_link'] = 'Sebelumnya';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li><a href="#" style="font-weight:bold;background-color:#f5f5f5">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_links'] = 2;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['first_link'] = 'Pertama';
			$config['last_link'] = 'Terakhir';
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
			$data['links'] = $this->pagination->create_links(); 
			$batas_akhir = '';
			if($page > 1){
				$limit = $page + 2;
			}else{
				$limit = 2;
			}					
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/publikasi';
		$parameter  = array('api_kode' => 8800, 'api_subkode' => 1, 'api_search' => array($ta, $smt, $limit, $this->uri->segment(5)));
		$data['publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($data['publikasi'])){
			$data['nm_dosen'] = array();
			foreach ($data['publikasi'] as $pub){
				$data['nm_dosen']['_'.$pub->KD_DOSEN] = $this->konvertNama($pub->KD_DOSEN);
			}
		}
		#$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/data_publikasi',$data);
	}
	
	function namaFak($kd_fak){
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter 	= array('api_kode' => 101006, 'api_subkode' => 5, 'api_search' => array(date('d/m/Y'), $kd_fak));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data); die();
		if (!empty($data)){
			$nama = $data[0]->FAK_NM_FAK;
			return $nama;
		}else{
			return 'Nama Fakultas belum disetting';
		}
	}
	
	function namaProdi($kd_prodi){
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter 	= array('api_kode' => 101006, 'api_subkode' => 1, 'api_search' => array(date('d/m/Y'), $kd_prodi));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data[0]->PRO_NM_PRODI;
		#print_r($data);
	}
	
	function baru(){
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/baru';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array());
		$a = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if($a) echo "OKE";
		else echo "NGGAK OKE";
	}
	
	
	function konvertNama($kd){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2001, 'api_subkode' => 2, 'api_search' => array($kd));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($data)){
			return $data[0]->NM_PGW_F;
		}
		else{
			return 'Namabelum terdata';
		}
	}
	
	function dataDosen($kd){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2001, 'api_subkode' => 2, 'api_search' => array($kd));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data;
	}
	
	
	
	# KOMPILASI KEGIATAN AKADEMIK & NARASUMBER
	function akademik(){
		$data['title'] = 'Kegiatan Akademik';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 100, 'api_search' => array());
		$tot_page = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		#echo $tot_page;
		$config['base_url'] = base_url().'bkd/admbkd/kompilasi/akademik/';
		$config['total_rows'] = $tot_page;
		$config['per_page'] = 20;
		$config["uri_segment"] = 5;
		$config['full_tag_open'] = '<div class="pagination"><ul>';
		$config['full_tag_close'] = '</ul></div>';
		$config['next_tag_open'] = '<li>';
		$config['next_tag_close'] = '</li>';
		$config['prev_tag_open'] = '<li>';
		$config['prev_tag_close'] = '</li>';
		$config['next_link'] = 'Selanjutnya';
		$config['prev_link'] = 'Sebelumnya';
		$config['num_tag_open'] = '<li>';
		$config['num_tag_close'] = '</li>';
		$config['cur_tag_open'] = '<li><a href="#" style="font-weight:bold;background-color:#f5f5f5">';
		$config['cur_tag_close'] = '</a></li>';
		$config['num_links'] = 2;
		$config['first_tag_open'] = '<li>';
		$config['first_tag_close'] = '</li>';
		$config['last_tag_open'] = '<li>';
		$config['last_tag_close'] = '</li>';
		$config['first_link'] = 'Pertama';
		$config['last_link'] = 'Terakhir';
		
		$this->pagination->initialize($config);
		$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
		$data['links'] = $this->pagination->create_links();
		$batas_akhir='';
		if($page > 1){
			$limit = $page + 20;
		}else{
			$limit = 20;
		}
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($limit, $this->uri->segment(5)));
		$data['kegiatan'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		$data['namaDosen'] = array();
		if(!empty($data['kegiatan'])){
			foreach ($data['kegiatan'] as $a){
				$data['namaDosen']['_'.$a['KD_DOSEN']] = $this->konvertNama($a['KD_DOSEN']);
			}
		}
		$this->output99->output_display('admbkd/data_kegiatan_akademik', $data);
	}
	
	function narasumber(){
		if(isset($_POST['thn'])){
			$this->session->unset_userdata('ta');
			$this->session->set_userdata('ta', $_POST['thn']);
			$this->session->unset_userdata('smt');
			$this->session->set_userdata('smt', $_POST['smt']);
		}
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');

		$data['ta'] = $ta;
		$data['smt'] = $smt;
		$data['title'] = 'Narasumber/Pembicara';
		# CREATE PAGING
		$table 		= 'BKD.BKD_DATA_NARASUMBER';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000000, 'api_subkode' => 2, 'api_search' => array($table, $ta, $smt));
		$tot_record = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				
			$config['base_url'] = base_url().'bkd/admbkd/kompilasi/narasumber/';
			$config['total_rows'] = $tot_record;
			$config['per_page'] = 20;		
			$config["uri_segment"] = 5;
			$config['full_tag_open'] = '<div class="pagination pull-left"><ul>';
			$config['full_tag_close'] = '</ul></div>';
			$config['next_tag_open'] = '<li>';
			$config['next_tag_close'] = '</li>';
			$config['prev_tag_open'] = '<li>';
			$config['prev_tag_close'] = '</li>';
			$config['next_link'] = 'Selanjutnya';
			$config['prev_link'] = 'Sebelumnya';
			$config['num_tag_open'] = '<li>';
			$config['num_tag_close'] = '</li>';
			$config['cur_tag_open'] = '<li><a href="#" style="font-weight:bold;background-color:#f5f5f5">';
			$config['cur_tag_close'] = '</a></li>';
			$config['num_links'] = 2;
			$config['first_tag_open'] = '<li>';
			$config['first_tag_close'] = '</li>';
			$config['last_tag_open'] = '<li>';
			$config['last_tag_close'] = '</li>';
			$config['first_link'] = 'Pertama';
			$config['last_link'] = 'Terakhir';
			
			$this->pagination->initialize($config);
			$page = ($this->uri->segment(5)) ? $this->uri->segment(5) : 0;
			$data['links'] = $this->pagination->create_links(); 
			$batas_akhir = '';
			if($page > 1){ $limit = $page + 20; }else{ $limit = 20;	}					
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode' => 2000, 'api_subkode' =>4, 'api_search' => array($ta, $smt, $limit, $this->uri->segment(5)));
		$data['narasumber'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['narasumber']);
		if(!empty($data['narasumber'])){
			$data['nm_dosen'] = array();
			foreach ($data['narasumber'] as $pub){
				$data['nm_dosen']['_'.$pub->KD_DOSEN] = $this->konvertNama($pub->KD_DOSEN);
			}
		}
		$this->output99->output_display('admbkd/data_narasumber',$data);
	}
	
	
	function cariNIPdosen($q){
		#sia_dosen/data_search, 20000/13
		$api_url = URL_API_SIA.'sia_dosen/data_search';
		$parameter = array('api_kode' => 20000, 'api_subkode' => 14, 'api_search'=> array($q));
		$data['dosen'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		print_r($data['dosen']);
	}
	/* file location : ../application/controller/admbkd/kompilasi.php
	 * last modified : 15/November/2013
	 * By : Sabbana 
	 */

}
