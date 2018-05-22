<?php
// DNG A BMTR [ ++ | 03 | 2018]
class Data_asesor extends CI_Controller {
	function __construct() {
		parent::__construct();
        $this->view 	= $this->s00_lib_output;
        $this->api 		= $this->load->library('s00_lib_api');
        $this->url 		= $this->load->library('lib_url');
        $this->user 	= $this->load->library('lib_user', $this->session->all_userdata());
        $this->util 	= $this->load->library('lib_util');
        $this->lib_skp 	= $this->load->library('lib_skp'); 
        $this->lib_remun = $this->load->library('lib_remun'); 
        //$this->simpeg 	= $this->load->module('remunerasi/simpeg');
       
        $this->load->library('webserv');
        
        $this->session->set_userdata('app', 'remunerasi_app');
        $this->m_remun = $this->load->model('M_remun');
        
		if (!$this->user->is_login()) {
			redirect();
		}

		//$this->url_kue = 'http://service2.uin-suka.ac.id/servsimpeg/remunerasi_dosen/agregasi';

		/*error_reporting(0);*/
	}

	function data_asesor_remunerasi(){
		$this->view->output_display('remunerasi_dosen/form_data_asesor_remunerasi');
	}
	function get_data_fakultas(){
		$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
		$data= $this->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);
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
	function tambah_asesor_remunerasi(){
		$kd_fak = $this->input->post('kd_fak');
		$nip = $this->input->post('nip');
		$thn_akademik = $this->input->post('thn_akademik');
		$semester = $this->input->post('semester');
		$status = $this->input->post('status');

		$param = array('data'=>array($kd_fak, $nip, $thn_akademik, $semester, $status));
		$url = URL_API_REMUN_DOSEN.'agregasi/tambah_asesor_remunerasi';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);
	}
	public function get_asesor_remunerasi(){
		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/get_asesor_remunerasi';
	    $data['remunerasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);
	    $no=1;
	    if($data['remunerasi']){
	    	foreach ($data['remunerasi'] as $key) {

				
				if($key['status']=='1'){
					$icon = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
				}else{
					$icon = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";
				}
				$button = "<button type='button' class='btn btn-mini' value=".$key['id_aturan']." onclick='get_edit(".$key['id_aturan'].");'><i class='icon-edit'></i> Edit</button>";
			
				echo "<tr>
					<td align='center'>".$no.".</td>
					<td align='center'>".$key['nip']."%</td>
					<td align='center'>".$key['kd_fakultas']."%</td>
					<td align='center'>".$key['kd_ta']."%</td>
					<td align='center'>".$key['kd_smt']."%</td>
					<td align='center'>".$icon."</td>
					<td align='center'>".$button."</td>
				</tr>";

				$no++;
			}
	    }else{
			echo "<tr><td colspan='7' align='center'>Data Tidak Ditemukan !</td></tr>";
	    }
	    
	}	
}

