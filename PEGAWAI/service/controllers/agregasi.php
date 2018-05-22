<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Agregasi extends CI_Controller {
	function __construct(){
		parent::__construct();
		error_reporting(0);
		$this->db_remun = $this->load->database('remun',TRUE);
		$this->load->model('remunerasi_dosen/mdl_remun_dosen', 'mdl_remun');
		
	}

	function index()
	{
		//echo json_encode("agregasi");
		/*$query = "SELECT * FROM remun_aturan_agregasi";
    	$sql = $this->db_remun->query($query);*/
    	$data = $this->mdl_remun->lihat_aturan_agregasi();
    	echo json_encode($data);
	}
	function jenis_dosen(){
		$data = $this->mdl_remun->get_jenis_dosen();
    	echo json_encode($data);
	}
	function edit_aturan_agregasi($format = 'json'){
		$api_search = $this->input->post('id_aturan');

		$data = $this->mdl_remun->edit_aturan_agregasi($api_search[0]);
		//$data = $this->mdl_remun->lihat_aturan_agregasi();
    	echo json_encode($data);
	}
	function edit_prosentase_nilai_max($format = 'json'){
		$api_search = $this->input->post('id_aturan');
		$data = $this->mdl_remun->edit_prosentase_nilai_max($api_search[0]);
    	echo json_encode($data);
	}
	function edit_nilai_batas_ikd($format = 'json'){
		$api_search = $this->input->post('id_aturan');
		$data = $this->mdl_remun->edit_nilai_batas_ikd($api_search[0]);
    	echo json_encode($data);
	}
	function get_status_dosen(){

	}
	function update_aturan_agregasi($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->update_aturan_agregasi($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8], $api_search[9]);
		echo json_encode($data);
	}
	function update_prosentase_nilai_max($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->update_prosentase_nilai_max($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8]);
		echo json_encode($data);
	}
	function tambah_prosentase_nilai_max($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->tambah_prosentase_nilai_max($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7]);
		echo json_encode($data);
	}
	function update_nilai_batas_ikd($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->update_nilai_batas_ikd($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6]);
		echo json_encode($data);
	}
	function tambah_nilai_batas_ikd($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->tambah_nilai_batas_ikd($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		echo json_encode($data);
	}
	function tambah_aturan_agregasi($format='json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->tambah_aturan_agregasi($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6], $api_search[7], $api_search[8]);
		echo json_encode($data);
	}
	function get_all_aturan_agregasi_aktif($format='json'){
		$data = $this->mdl_remun->get_all_aturan_agregasi_aktif();
		echo json_encode($data);
	}
	function get_aturan_agregasi_ikd($format='json'){
		$data = $this->mdl_remun->get_aturan_agregasi_ikd();
    	echo json_encode($data);
	}
	function get_nilai_max_komponen_agregasi($format='json'){
		$data = $this->mdl_remun->get_nilai_max_komponen_agregasi();
    	echo json_encode($data);
	}
	function tambah_jadwal_pengisian($format='json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->tambah_jadwal_pengisian($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		echo json_encode($data);
	}
	function get_jadwal_pengisian_remun(){
		$data = $this->mdl_remun->lihat_jadwal_pengisian();
    	echo json_encode($data);
	}
	function edit_jadwal_pengisian_remun($format = 'json'){
		$api_search = $this->input->post('id_jadwal');
		$data = $this->mdl_remun->edit_jadwal_pengisian_remun($api_search[0]);
    	echo json_encode($data);
	}
	function update_jadwal_pengisian($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->update_jadwal_pengisian($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6]);
		echo json_encode($data);
	}
	function update_jadwal_pengisian_dari_tambah($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->update_jadwal_pengisian_dari_tambah($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5], $api_search[6]);
		echo json_encode($data);
	}
	function cek_jadwal_pengisian($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->cek_jadwal_pengisian($api_search[0], $api_search[1], $api_search[2]);
		echo json_encode($data);
	}
	function get_jadwal_pengisian_remunerasi_periode_saat_ini($format='json'){
		$api_search = $this->input->post('api_search');
		$data = $this->mdl_remun->cek_jadwal_pengisian($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		echo json_encode($data);
	}
	function tambah_asesor_remunerasi($format='json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->tambah_asesor_remunerasi($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4]);
		echo json_encode($data);
	}
	function get_asesor_remunerasi($format = 'json'){
		$data = $this->mdl_remun->get_asesor_remunerasi();
    	echo json_encode($data);
	}
	function get_asesor_remunerasi_by_kd_dosen($format = 'json'){
		$api_search = $this->input->post('api_search');
		$data = $this->mdl_remun->get_asesor_remunerasi_by_kd_dosen($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
    	echo json_encode($data);
	}
	function cek_daftar_asesor_remunerasi($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->cek_daftar_asesor_remunerasi($api_search[0], $api_search[1], $api_search[2], $api_search[3]);
		echo json_encode($data);
	}
	function insert_asesor_remunerasi($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->insert_asesor_remunerasi($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4]);
		echo json_encode($data);
	}
	function edit_asesor_remunerasi($format = 'json'){
		$api_search = $this->input->post('id_asesor');
		$data = $this->mdl_remun->edit_asesor_remunerasi($api_search[0]);
		echo json_encode($data);
	}
	function update_asesor_remunerasi($format = 'json'){
		$api_search = $this->input->post('data');
		$data = $this->mdl_remun->update_asesor_remunerasi($api_search[0], $api_search[1], $api_search[2], $api_search[3], $api_search[4], $api_search[5]);
		echo json_encode($data);
	}
	function get_asesor_remunerasi_by_kd_fak($format = 'json'){
		$api_search = $this->input->post('api_search');
		$data = $this->mdl_remun->get_asesor_remunerasi_by_kd_fak($api_search[0]);
		echo json_encode($data);
	}


	function get_jadwal_kinerja_remun($format = 'json'){
		//$api_search = $this->input->post('api_search');
		$data = $this->mdl_remun->get_jadwal_kinerja_remun();
		echo json_encode($data);
	}



 }