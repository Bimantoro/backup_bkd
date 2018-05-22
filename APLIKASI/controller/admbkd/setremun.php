<?php 
/**
* NITIP, NANTI DIPINDAH KE SIMPEG
*/
class Setremun extends CI_Controller
{
	
	function __construct()
	{
		

		parent::__construct();
		$this->load->library('form_validation');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->library('bkd_lib_sks_rule', '', 'sksrule');
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
		if($this->session->userdata('id_user') == '') redirect(base_url());
		$user = $this->session->userdata('id_user');
		if(strtoupper($user) != 'PKSI100'){
			redirect(base_url('bkd/admbkd/home'));
		}
	}

	function testing(){
		echo 'ini untuk konfigurasi poin remun, sementara';
		
	}

	function poin(){
		$this->output99->output_display('admbkd/poin_remun');
	}

	function poin_remun(){
		//ambil data all poin dari remun :
			//poin remun itu ada 3, pendidikan, kegiatan akademik dan penunjang lain
		$pendidikan = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/get_semua_poin_pendidikan',
			'POST',
			array('api_search' => array())
		);

		echo json_encode($pendidikan);
	}

	function get_jbr(){
		$kat = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/get_jbr',
			'POST',
			array('api_search' => array())
		);

		echo json_encode($kat);
	}

	 function get_kat_remun(){
	 	$jbr = $this->input->post('kd_jbr');

		$kat = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/get_kategori_remun',
			'POST',
			array('api_search' => array($jbr))
		);

		echo json_encode($kat);
	}

	function get_poin_remun(){
		$kd_kat = $this->input->post('kd_kat');

		$poin = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/get_poin_kat_pendidikan',
			'POST',
			array('api_search' => array($kd_kat))
		);


		echo json_encode($poin);
	}

	function get_form_remun(){

		$jenjang = array(
			array('ID_JENJANG' => 'D3', 'NM_JENJANG' => 'D3'),
			array('ID_JENJANG' => 'S1', 'NM_JENJANG' => 'S1'),
			array('ID_JENJANG' => 'S2', 'NM_JENJANG' => 'S2'),
			array('ID_JENJANG' => 'S3', 'NM_JENJANG' => 'S3')
		);

		$kelas = array(
			array('KD_KELAS' => 'A', 'NM_KELAS' => 'REGULER'),
			array('KD_KELAS' => 'B', 'NM_KELAS' => 'NON REGULER'),
			array('KD_KELAS' => 'C', 'NM_KELAS' => 'INTERNASIONAL')
		);

		$jabatan = array(
			array('ID_JABATAN' => 'Guru Besar', 'NM_JABATAN' => 'Guru Besar'),
			array('ID_JABATAN' => 'Lektor Kepala', 'NM_JABATAN' => 'Lektor Kepala'),
			array('ID_JABATAN' => 'Lektor', 'NM_JABATAN' => 'Lektor'),
			array('ID_JABATAN' => 'Asisten Ahli', 'NM_JABATAN' => 'Asisten Ahli')
		);

		$semester = array(
			array('KD_SEMESTER' => 'GANJIL', 'NM_SEMESTER' => 'GANJIL'),
			array('KD_SEMESTER' => 'GENAP', 'NM_SEMESTER' => 'GENAP'),
			array('KD_SEMESTER' => 'PENDEK', 'NM_SEMESTER' => 'PENDEK')
		);

		$data['JENJANG'] = $jenjang;
		$data['KELAS'] = $kelas;
		$data['JABATAN'] = $jabatan;
		$data['SEMESTER'] = $semester;

		echo json_encode($data);
	}

	function get_edit(){
		$id_p = $this->input->post('id_p');

		$poin = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/get_detail_poin_pendidikan',
			'POST',
			array('api_search' => array($id_p))
		);

		$jenjang = array(
			array('ID_JENJANG' => 'D3', 'NM_JENJANG' => 'D3'),
			array('ID_JENJANG' => 'S1', 'NM_JENJANG' => 'S1'),
			array('ID_JENJANG' => 'S2', 'NM_JENJANG' => 'S2'),
			array('ID_JENJANG' => 'S3', 'NM_JENJANG' => 'S3')
		);

		$kelas = array(
			array('KD_KELAS' => 'A', 'NM_KELAS' => 'REGULER'),
			array('KD_KELAS' => 'B', 'NM_KELAS' => 'NON REGULER'),
			array('KD_KELAS' => 'C', 'NM_KELAS' => 'INTERNASIONAL')
		);

		$jabatan = array(
			array('ID_JABATAN' => 'Guru Besar', 'NM_JABATAN' => 'Guru Besar'),
			array('ID_JABATAN' => 'Lektor Kepala', 'NM_JABATAN' => 'Lektor Kepala'),
			array('ID_JABATAN' => 'Lektor', 'NM_JABATAN' => 'Lektor'),
			array('ID_JABATAN' => 'Asisten Ahli', 'NM_JABATAN' => 'Asisten Ahli')
		);

		$semester = array(
			array('KD_SEMESTER' => 'GANJIL', 'NM_SEMESTER' => 'GANJIL'),
			array('KD_SEMESTER' => 'GENAP', 'NM_SEMESTER' => 'GENAP'),
			array('KD_SEMESTER' => 'PENDEK', 'NM_SEMESTER' => 'PENDEK')
		);

		$data['POIN'] = $poin;
		$data['JENJANG'] = $jenjang;
		$data['KELAS'] = $kelas;
		$data['JABATAN'] = $jabatan;
		$data['SEMESTER'] = $semester;

		echo json_encode($data);

	}

	function tambah_poin_remun(){
		$kd_jbk = $this->input->post('kd_jbk');
		$kd_kat = $this->input->post('kd_kat');
		$jenjang = $this->input->post('jenjang');
		$kelas = $this->input->post('kelas');
		$jabatan = $this->input->post('jabatan');
		$semester = $this->input->post('semester');
		$poin = $this->input->post('poin');
		$satuan = $this->input->post('satuan');

		$simpan = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/tambah_poin_remun',
			'POST',
			array('api_search' => array($kd_jbk, $kd_kat, $jenjang, $kelas, $jabatan, $semester, $poin, $satuan))
		);

		echo json_encode($simpan);
	}

	function hapus_poin_remun(){
		$kd_p = $this->input->post('kd_p');

		$hapus = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/hapus_poin_remun',
			'POST',
			array('api_search' => array($kd_p))
		);

		if($hapus){
			$r = 1;
		}else{
			$r = 0;
		}

		echo $r;
	}

	function update_poin_remun(){
		$kd_p = $this->input->post('kd_p');
		$jenjang = $this->input->post('jenjang');
		$kelas = $this->input->post('kelas');
		$jabatan = $this->input->post('jabatan');
		$semester = $this->input->post('semester');
		$poin = $this->input->post('poin');
		$satuan = $this->input->post('satuan');

		$update = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/update_poin_remun',
			'POST',
			array('api_search' => array($kd_p, $jenjang, $kelas, $jabatan, $semester, $poin, $satuan))
		);

		echo json_encode($update);
	}
}

 ?>