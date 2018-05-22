<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Asesor extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}
	
	function index(){
		#$kode = $this->security->xss_clean($this->uri->segment(4));
		# load paging
		$this->load->library('pagination');
		$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
		$parameter  = array('api_kode'=>7777, 'api_subkode'=> 100, 'api_search' => array());
		$tot_page = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); #echo $tot_page;

		$config['base_url'] = base_url().'bkd/admbkd/asesor';
		$config['total_rows'] = $tot_page;
		$config['per_page'] = 20;		
		
		$this->pagination->initialize($config);
		$page = $this->uri->segment(5);
		if(!$page) $offset = 1;
		else $offset = $page+1;
		
		$data['links'] = $this->pagination->create_links();
		
		$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
		$parameter  = array('api_kode'=>7777, 'api_subkode'=> 1, 'api_search' => array($page, $config['per_page']));
		$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/daftar_asesor',$data);
	
	}
	
	function data($page = null){
		$this->load->library('pagination');
		$keyword = $this->input->post('keyword');
		if($keyword == ''){
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode'=>7777, 'api_subkode'=> 100, 'api_search' => array());
			$tot_page = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$config['base_url'] = base_url().'bkd/admbkd/asesor/data/';
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
			$batas_akhir = '';
			if($page>1){ $limit=$page+20; }else{ $limit=20;}
					
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode'=>7777, 'api_subkode'=> 1, 'api_search' => array($limit, $this->uri->segment(5)));
			$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$data['links'] = $this->pagination->create_links();
		}else{
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode'=>9999, 'api_subkode'=> 1, 'api_search' => array($keyword));
			$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$data['links'] = '';
		
		}
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/daftar_asesor',$data);
	
	}
	
	function register($id = ''){
		$id = $this->uri->segment(5);
		if ($id !== null){
			$api_url 	= URL_API_BKD.'bkd_dosen/current_asesor';
			$parameter  = array('api_search' => array($id));
			$data['current_asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}	
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/form_asesor',$data);
	
	}
	
	
	function simpan_asesor(){
		$this->form_validation->set_rules('nira','NIRA ASESOR','trim|required|xss_clean');
		$this->form_validation->set_rules('nm_asesor','NAMA ASESOR','trim|required|xss_clean');
		#$this->form_validation->set_rules('nm_pt','NAMA PERGURUAN TINGGI','trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			$this->register();
		}
		else{
			$a = $this->input->post('nira');
			$b = $this->input->post('nm_asesor');
			$c = $this->input->post('nm_pt');
			$d = $this->input->post('rumpun');
			$e = $this->input->post('bidang_ilmu');
			$f = $this->input->post('telp');
			$api_url 	= URL_API_BKD.'bkd_dosen/insert_asesor';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			redirect('bkd/admbkd/asesor/data');
		}
	}
	
	function v_name($string){
		$result = str_replace("'","''", trim($string));
		return $result;
	}
	
	function update_asesor(){
		$this->form_validation->set_rules('nm_asesor','NAMA ASESOR','trim|required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			$this->register();
		}
		else{
			$g = $this->input->post('kd_dosen');
			$f = $this->input->post('nira');
			$a = $this->v_name($this->input->post('nm_asesor'));
			$b = $this->input->post('nm_pt');
			$c = $this->input->post('rumpun');
			$d = $this->input->post('bidang_ilmu');
			$e = $this->input->post('telp');
			$api_url 	= URL_API_BKD.'bkd_dosen/update_asesor';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f, $g));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			redirect('bkd/admbkd/asesor/data');
		}
	
	}
	
	function delete_asesor($nira){
		$nira = $this->uri->segment(5);
		$api_url 	= URL_API_BKD.'bkd_dosen/hapus_asesor';
		$parameter  = array('api_search' => array($nira));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		redirect('bkd/admbkd/asesor/data');
	}

	# read data from excel.
	# ===============================================================================================
	function import_xls($filename = ''){
		require_once('includes/xls_report/PHPExcel.php');
		$xls_file = 'uploads/DataBkd/DocUpload/'.$filename;

		$objReader = new PHPExcel_Reader_Excel5();
		$objReader->setReadDataOnly(true);
		$objPHPExcel = $objReader->load($xls_file); #return $filename;
		$sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,false);
		$totalrow = count($sheetData); #return $sheetData;
		
		$kd_prodi = $sheetData[0][1];
		# get active record
		$start_record = 9;
		$start_col = 2;
		while ($start_record < $totalrow){
			$a = $this->security->xss_clean($sheetData[$start_record][$start_col]); $start_col++; #nira
			$b = str_replace("'","\'",$sheetData[$start_record][$start_col]); $start_col++; #nm_asesor
			$c = $this->security->xss_clean($sheetData[$start_record][$start_col]); $start_col++; #nm_pt
			$d = $this->security->xss_clean($sheetData[$start_record][$start_col]); $start_col++; 
			$e = $this->security->xss_clean($sheetData[$start_record][$start_col]); $start_col++;
			$f = $this->security->xss_clean($sheetData[$start_record][$start_col]); $start_col++;
			# simpan ke database 
			$api_url 	= URL_API_BKD.'bkd_dosen/insert_asesor';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f, $kd_prodi));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			$start_record++;
			$start_col = 2;
		}
		$api_url 	= URL_API_BKD.'bkd_dosen/get_nama_prodi';
		$parameter  = array('api_search' => array($kd_prodi));
		$nm = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$data['status'] = '<div class="alert alert-success"><i class="icon icon-ok-sign"></i> Import Data Asesor untuk program studi '.$nm.' selesai...
							<br/>Lihat Daftar Asesor Prodi'.$nm.' <a href="'.base_url().'bkd/admbkd/asesor/prodi/'.$kd_prodi.'">Klik disini</a></div>';
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/form_import',$data);
	}

	
	function import(){
		$data['upload_message'] = '';
		$api_url 	= URL_API_SIA.'sia_master/data_view';
		$parameter  = array('api_kode' => 19000, 'api_subkode'=>1, 'api_search' => array());
		$data['prodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/form_import',$data);		
	}
	
	function download(){
		$kd = $this->input->post('kd_prodi');
		redirect(base_url().'uploads/DataBkd/DocDownload/form_asesor_'.$kd.'.xls');
		$api_url 	= URL_API_BKD.'bkd_dosen/data_prodi';
		$parameter  = array('data_search' => array());
		$data['prodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		foreach ($data['prodi'] as $p){
			echo $p->KD_PRODI.' = '.$p->NM_PRODI.'<br/>';
		}
	}
	
	
	public function do_upload(){
		$config['upload_path'] = './uploads/DataBkd/DocUpload/';
		$config['allowed_types'] = 'xls';
		$config['max_size']    = '10000';
		$config['encrypt_name'] = FALSE;
		$config['overwrite'] = TRUE;

		$this->load->library('upload', $config);

		if ( ! $this->upload->do_upload()){
			$data['message'] = '<div class="alert alert-error"><i class="icon icon-warning-sign"></i> Terjadi kesalahan ketika upload file, pastikan jenis file berextensi xls</div>'; 
			$this->output99=$this->s00_lib_output;
			$this->output99->output_display('admbkd/form_import',$data);		
		}
		else{
			$upload_data = array('upload_data' => $this->upload->data());
			$filename = $upload_data['upload_data']['file_name'];
			
			redirect('bkd/admbkd/asesor/import_xls/'.$filename);			
		}
	}
	
	function prodi(){
		$this->load->library('pagination');
		$kode = $this->uri->segment(5);
		$data['kode'] = $this->input->post('kd_prodi');
		if(empty($kode)){
			if($data['kode'] == ''){
				$kode = '25';
			}else{				
				$kode = $data['kode'];
			}
		}
			# CONFIGURASI PAGINASI
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode'=>8888, 'api_subkode'=> 100, 'api_search' => array($kode));
			$tot_page = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			#echo $tot_page;
			
			$config['base_url'] = base_url().'bkd/admbkd/asesor/prodi/'.$kode.'/';
			$config['total_rows'] = $tot_page;
			$config['per_page'] = 20;		
			$config["uri_segment"] = 6;
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
			$page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
			$data['links'] = $this->pagination->create_links(); 
			$batas_akhir='';
			if($page > 1){
				$limit = $page + 20;
			}else{
				$limit = 20;
			}
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode' => 8888, 'api_subkode' => 1, 'api_search' => array($kode, $limit, $this->uri->segment(6)));
			$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# get nama prodi
			$data['nm_prodi'] = $this->namaProdi($kode);
			
		#sia_master/data_view, 17000, 18000, 19000
		$api_url 	= URL_API_SIA.'sia_master/data_view';
		$parameter  = array('api_kode' => 19000, 'api_subkode'=>1, 'api_search' => array());
		$data['prodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/daftar_asesor_prodi',$data);
	}
	
	function namaProdi($kd_prodi){
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter 	= array('api_kode' => 101006, 'api_subkode' => 1, 'api_search' => array(date('d/m/Y'), $kd_prodi));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data[0]->PRO_NM_PRODI;
	}
	
	function detail($nira){
		$data['title'] = 'Data Asesor';
		$nira = $this->security->xss_clean($this->uri->segment(5));
		$api_url 	= URL_API_BKD.'bkd_dosen/biodata_asesor';
		$parameter  = array('api_search' => array($nira));
		$data['data_asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		// load view 
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('admbkd/data_asesor',$data);
	}
	
	/* file location : ../application/controller/admbkd/home.php
	 * last modified : 13/November/2013
	 * By : Sabbana 
	 */

}