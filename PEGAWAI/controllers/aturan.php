<?php
class Aturan extends CI_Controller {
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
        //$this->session->set_userdata('app','app_bkd');
        $this->m_remun = $this->load->model('M_remun');
        
		if (!$this->user->is_login()) {
			redirect();
		}

		//$this->url_kue = 'http://service2.uin-suka.ac.id/servsimpeg/remunerasi_dosen/agregasi';

		/*error_reporting(0);*/
	}
	public function TanggalIndo($date){
		$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
	 
		$tahun = substr($date, 0, 4);
		$bulan = substr($date, 5, 2);
		$tgl   = substr($date, 8, 2);
	 	/*date_default_timezone_set("Asia/Jakarta");*/
		$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun/*." ".date('h:i:s a')*/;		
		return($result);
	}
	public function aturan_agregasi(){
		/*$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);*/

	    $param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/jenis_dosen';
	    $data['jenis_dosen'] = json_decode($this->curl->simple_post($url, $param), TRUE);

        $this->view->output_display('remunerasi_dosen/form_aturan_agregasi', $data);
	}
	public function prosentase_nilai_max(){
		$this->view->output_display('remunerasi_dosen/form_prosentase_nilai_max');
	}
	public function hitung_nilai_ikd(){
		$this->view->output_display('remunerasi_dosen/form_hitung_nilai_ikd');
	}
	public function get_aturan_agregasi(){
		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);
	    $no=1;
	    if($data['agregasi']){
	    	foreach ($data['agregasi'] as $key) {

				if($key['status_dosen'] == 'DT'){
					$jenis_dosen = "DOSEN DENGAN TUGAS TAMBAHAN";
				}elseif($key['status_dosen'] == 'DK'){
					$jenis_dosen = "DOSEN DENGAN TUGAS KHUSUS";
				}elseif($key['status_dosen'] == 'DS'){
					$jenis_dosen = "DOSEN BIASA";
				}

				if($key['status']=='1'){
					$icon = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
				}else{
					$icon = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";
				}
				$button = "<button type='button' class='btn btn-mini' value=".$key['id_aturan']." onclick='get_edit(".$key['id_aturan'].");'><i class='icon-edit'></i> Edit</button>";
			
				echo "<tr>
					<td align='center'>".$no.".</td>
					<td>".$jenis_dosen."</td>
					<td align='center'>".$key['kehadiran']."%</td>
					<td align='center'>".$key['skr']."%</td>
					<td align='center'>".$key['iku']."%</td>
					<td align='center'>".$key['skp']."%</td>
					<td align='center'>".$key['ikd']."%</td>
					<td align='center'>".$this->TanggalIndo($key['tanggal'])."</td>
					<td align='center'>".$icon."</td>
					<td align='center'>".$button."</td>
				</tr>";

				$no++;
			}
	    }else{
			echo "<tr><td colspan='10' align='center'>Data Tidak Ditemukan !</td></tr>";
	    }
	    
	}
	function get_edit_agregasi(){
		$id = $this->input->post('id_aturan');

		//$param = array('id_aturan'=>$id);
		$param = array('id_aturan'=>array($id));
		$url = URL_API_REMUN_DOSEN.'agregasi/edit_aturan_agregasi';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);

		echo json_encode($data['agregasi']);
	}
	function get_edit_prosentase_nilai_max(){
		$id = $this->input->post('id_aturan');

		//$param = array('id_aturan'=>$id);
		$param = array('id_aturan'=>array($id));
		$url = URL_API_REMUN_DOSEN.'agregasi/edit_prosentase_nilai_max';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);

		echo json_encode($data['agregasi']);
	}
	function get_edit_nilai_batas_ikd(){
		$id = $this->input->post('id_aturan');

		//$param = array('id_aturan'=>$id);
		$param = array('id_aturan'=>array($id));
		$url = URL_API_REMUN_DOSEN.'agregasi/edit_nilai_batas_ikd';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);

		echo json_encode($data['agregasi']);
	}
	function update_aturan_agregasi(){
		$id = $this->input->post('id_aturan');
		$status_dosen = $this->input->post('status_dosen');
		$kehadiran = $this->input->post('kehadiran');
		$skr = $this->input->post('skr');
		$iku = $this->input->post('iku');
		$skp = $this->input->post('skp');
		$ikd = $this->input->post('ikd');
		$status = $this->input->post('status');

		$param = array('data'=>array($id, $status_dosen, $kehadiran, $skr, $iku, $skp, $ikd, $status));
		$url = URL_API_REMUN_DOSEN.'agregasi/update_aturan_agregasi';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);

		echo json_encode($data['agregasi']);
	}
	function update_prosentase_nilai_max(){
		$id = $this->input->post('id_aturan');
		$kehadiran = $this->input->post('kehadiran');
		$skr = $this->input->post('skr');
		$iku = $this->input->post('iku');
		$skp = $this->input->post('skp');
		$ikd = $this->input->post('ikd');
		$status = $this->input->post('status');

		$param = array('data'=>array($id, $kehadiran, $skr, $iku, $skp, $ikd, $status));
		$url = URL_API_REMUN_DOSEN.'agregasi/update_prosentase_nilai_max';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);

		echo json_encode($data['agregasi']);
	}
	function update_nilai_batas_ikd(){
		$id = $this->input->post('id_aturan');
		$batas_bawah = $this->input->post('batas_bawah');
		$batas_atas = $this->input->post('batas_atas');
		$prosentase = $this->input->post('prosentase');
		$status = $this->input->post('status');

		$param = array('data'=>array($id, $batas_bawah, $batas_atas, $status, $prosentase));
		$url = URL_API_REMUN_DOSEN.'agregasi/update_nilai_batas_ikd';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);

		echo json_encode($data['agregasi']);
	}
	function tambah_aturan_agregasi(){
		$status_dosen = $this->input->post('status_dosen');
		$kehadiran = $this->input->post('kehadiran');
		$skr = $this->input->post('skr');
		$iku = $this->input->post('iku');
		$skp = $this->input->post('skp');
		$ikd = $this->input->post('ikd');
		$status = $this->input->post('status');

		$param = array('data'=>array($status_dosen, $kehadiran, $skr, $iku, $skp, $ikd, $status));
		$url = URL_API_REMUN_DOSEN.'agregasi/tambah_aturan_agregasi';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);
	}
	function get_keterangan_syarat(){
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Status :</b></td></tr>";
		
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
		<tr>
			<td style='padding-left: 20px;'><span class='badge badge-success'> <i class='icon-white icon-ok'></i></span></td>
			<td> &nbsp; : Aktif</b></td>
		</tr>
		";
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
		<tr>
			<td style='padding-left: 20px;'><span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span></td>
			<td> &nbsp; : Tidak Aktif</b></td>
		</tr>
		";
	}
	function get_aturan_agregasi_ikd(){
		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/get_aturan_agregasi_ikd';
	    $data['aturan_ikd'] = json_decode($this->curl->simple_post($url, $param), TRUE);
	    return $data['aturan_ikd'];
	}
	function get_nilai_max_komponen_agregasi(){
		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/get_nilai_max_komponen_agregasi';
	    $data['nilai_max'] = json_decode($this->curl->simple_post($url, $param), TRUE);
	    return  $data['nilai_max'];
	}
	public function get_max_prosentase(){
	    $data['nilai_max'] = $this->get_nilai_max_komponen_agregasi();
	    $no=1;
	    if($data['nilai_max']){
	    	foreach ($data['nilai_max'] as $key) {
				if($key['status']=='1'){
					$icon = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
				}else{
					$icon = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";
				}
				$button = "<button type='button' class='btn btn-mini' value=".$key['id_aturan']." onclick='get_edit(".$key['id_aturan'].");'><i class='icon-edit'></i> Edit</button>";
			
				echo "<tr>
					<td align='center'>".$no.".</td>
					<td align='center'>".$key['kehadiran']."%</td>
					<td align='center'>".$key['skr']."%</td>
					<td align='center'>".$key['iku']."%</td>
					<td align='center'>".$key['skp']."%</td>
					<td align='center'>".$key['ikd']."%</td>
					<td align='center'>".$this->TanggalIndo($key['tanggal'])."</td>
					<td align='center'>".$icon."</td>
					<td align='center'>".$button."</td>
				</tr>";

				$no++;
			}
	    }else{
			echo "<tr><td colspan='10' align='center'>Data Tidak Ditemukan !</td></tr>";
	    }
	    
	}
	public function get_hitung_nilai_batas_ikd(){
	    $data['nilai_ikd'] = $this->get_aturan_agregasi_ikd();
	    $no=1;
	    if($data['nilai_ikd']){
	    	foreach ($data['nilai_ikd'] as $key) {
				if($key['status']=='1'){
					$icon = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
				}else{
					$icon = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";
				}
				$button = "<button type='button' class='btn btn-mini' value=".$key['id_aturan']." onclick='get_edit(".$key['id_aturan'].");'><i class='icon-edit'></i> Edit</button>";
			
				echo "<tr>
					<td align='center'>".$no.".</td>
					<td align='center'>".$key['batas_bawah']."</td>
					<td align='center'>".$key['batas_atas']."</td>
					<td align='center'>".$key['prosentase']."%</td>
					<td align='center'>".$this->TanggalIndo($key['tanggal'])."</td>
					<td align='center'>".$icon."</td>
					<td align='center'>".$button."</td>
				</tr>";

				$no++;
			}
	    }else{
			echo "<tr><td colspan='10' align='center'>Data Tidak Ditemukan !</td></tr>";
	    }
	    
	}
	function jadwal_pengisian_remun(){
		$this->view->output_display('remunerasi_dosen/form_jadwal_pengisian_remun');
	}
	function tambah_jadwal_pengisian(){
		$tgl_mulai = $this->input->post('tgl_mulai');
		$tgl_m = date('Y-m-d', strtotime($tgl_mulai));

		$tgl_selesai = $this->input->post('tgl_selesai');
		$tgl_s = date('Y-m-d', strtotime($tgl_selesai));

		$jam_mulai = $this->input->post('jam_mulai');
		$jam_m = date('H:i', strtotime($jam_mulai));

		$jam_selesai = $this->input->post('jam_selesai');
		$jam_s = date('H:i', strtotime($jam_selesai));

		$tgl_mulai_isi= date(''.$tgl_m.' '.$jam_m.'');
        $tgl_selesai_isi= date(''.$tgl_s.' '.$jam_s.'');


        $thn_akademik = $this->input->post('thn_akademik');
		$semester = $this->input->post('semester');
		$kategori = $this->input->post('kategori');
		$status = $this->input->post('status');

		//cek
		$cek = $this->cek_jadwal_pengisian($thn_akademik, $semester, $kategori);
		if(!empty($cek)){
			//jika tidak kosong, (sudah ada data jadwal pengisian) maka update
			$param = array('data'=>array($cek, $tgl_mulai_isi, $tgl_selesai_isi, $thn_akademik, $semester, $kategori, $status));
			$url = URL_API_REMUN_DOSEN.'agregasi/update_jadwal_pengisian_dari_tambah';
		    $data['jadwal'] = json_decode($this->curl->simple_post($url, $param), TRUE);
		}else{
			//jika belum ada data jadwal pengisian maka insert
			//cek lagi apakah sudah ada jenis kategori yang tersimpan di database,
			//jika sudah ada maka update ganti status nya = 0 sesuai kategori, jika status yg akan di insertkan sama dengan 1 (aktif) jika
			//statusnya dama dengan 0(tidak aktif), maka status tidak perlu diganti sesuai kategori
			//jika belum ada maka insert langsung
			$param = array('data'=>array($tgl_mulai_isi, $tgl_selesai_isi, $thn_akademik, $semester, $kategori, $status));
			$url = URL_API_REMUN_DOSEN.'agregasi/tambah_jadwal_pengisian';
		    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);
		}
		

	}
	function get_jadwal_pengisian_remun(){
		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/get_jadwal_pengisian_remun';
	    $data['jadwal'] = json_decode($this->curl->simple_post($url, $param), TRUE);
	    $no=1;
	    if(!empty($data['jadwal'])){
	    	foreach ($data['jadwal'] as $key) {
	    		$a = $key['kd_ta'];
	    		$b = $a+1;
	    		$thn_akademik = $a.' / '.$b; 
	    		if($key['kd_smt'] == 1){
	    			$semester = 'Ganjil';
	    		}elseif($key['kd_smt'] == 2){
	    			$semester = 'Genap';
	    		}
	    		if($key['kategori']=='remun'){
	    			$kategori = "BKD Remun";
	    		}elseif($key['kategori']=='sertifikasi'){
	    			$kategori = "BKD Sertifikasi";
	    		}
	    		if($key['status']=='1'){
					$icon = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
				}else{
					$icon = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";
				}
	    		$button = "<button type='button' class='btn btn-mini' value=".$key['id_jadwal']." onclick='get_edit(".$key['id_jadwal'].");'><i class='icon-edit'></i> Edit</button>";
	    		$periode = $this->cek_periode_semester_saat_ini();
	    		$kd_ta = $periode[0]['KD_TA'];
	    		$kd_smt = $periode[0]['KD_SMT'];
	    		if($key['kd_ta']==$kd_ta AND $key['kd_smt']==$kd_smt){
	    			echo "<tr style='background-color: #FFEEBC;'>
						<td align='center'>".$no.".</td>
						<td align='center'>".$key['tgl_mulai']."</td>
						<td align='center'>".$key['tgl_selesai']."</td>
						<td align='center'>".$thn_akademik."</td>
						<td align='center'>".$semester."</td>
						<td align=''>".$kategori."</td>
						<td align='center'>".$icon."</td>
						<td align='center'>".$button."</td>
					</tr>";
	    		}else{
	    			echo "<tr>
						<td align='center'>".$no.".</td>
						<td align='center'>".$key['tgl_mulai']."</td>
						<td align='center'>".$key['tgl_selesai']."</td>
						<td align='center'>".$thn_akademik."</td>
						<td align='center'>".$semester."</td>
						<td align=''>".$kategori."</td>
						<td align='center'>".$icon."</td>
						<td align='center'>".$button."</td>
					</tr>";
	    		}
	    		

				$no++;
	    	}
	    }else{
	    	echo "<tr><td colspan='8' align='center'>Data Tidak Ditemukan !</td></tr>";
	    }
	    
	}
	function get_edit_jadwal_pengisian(){
		$id = $this->input->post('id_jadwal');
		$param = array('id_jadwal'=>array($id));
		$url = URL_API_REMUN_DOSEN.'agregasi/edit_jadwal_pengisian_remun';
	    $data['jadwal'] = json_decode($this->curl->simple_post($url, $param), TRUE);
		echo json_encode($data['jadwal']);
	}
	function update_jadwal_pengisian(){
		$id = $this->input->post('id_jadwal');
		$tgl_mulai = $this->input->post('tgl_mulai');
		$tgl_selesai = $this->input->post('tgl_selesai');
		$thn_akademik = $this->input->post('thn_akademik');
		$semester = $this->input->post('semester');
		$kategori = $this->input->post('kategori');
		$status = $this->input->post('status');

		//sebelum insert cek terlebih dahulu apakah sudah ada jadwal pengisian berdasarkan kategori, semester, kategori

		$param = array('data'=>array($id, $tgl_mulai, $tgl_selesai, $thn_akademik, $semester, $kategori, $status));
		$url = URL_API_REMUN_DOSEN.'agregasi/update_jadwal_pengisian';
	    $data['jadwal'] = json_decode($this->curl->simple_post($url, $param), TRUE);

		echo json_encode($data['jadwal']);
	}
	function cek_periode_semester_saat_ini(){
		$d = $this->session->userdata('smt');
		$d = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST',
		array('api_kode'=>52000, 'api_subkode'=>12, 'api_search' => array(date('d/m/Y'),'')));
		/*echo "<pre>";
		print_r($d);
		echo "<pre>";*/
		return $d;
	}
	function get_keterangan_jadwal(){
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Status :</b></td></tr>";
		
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
		<tr>
			<td style='padding-left: 20px;'><span class='badge badge-success'> <i class='icon-white icon-ok'></i></span></td>
			<td> &nbsp; : Aktif</b></td>
		</tr>
		";
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
		<tr>
			<td style='padding-left: 20px;'><span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span></td>
			<td> &nbsp; : Tidak Aktif</b></td>
		</tr>
		";
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
			<tr>
				<td style='width: 10px;padding-left: 20px;'><div class='foo magenta'></div></td>
				<td style=''>&nbsp; : Jadwal pengisian BKD Sertifikasi dan BKD Remunerasi dosen yang digunakan pada periode saat ini</td>
			</tr>";
					
	}
	function cek_jadwal_pengisian($thn_akademik, $semester, $kategori){
		$param = array('data'=>array($thn_akademik, $semester, $kategori));
		$url = URL_API_REMUN_DOSEN.'agregasi/cek_jadwal_pengisian';
	    $data['jadwal'] = json_decode($this->curl->simple_post($url, $param), TRUE);
	    $id = $data['jadwal']['id_jadwal'];
	    return $id;
	}
	function tf_test(){
		echo URL_API_REMUN_DOSEN;
	}
	/*function test(){
		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/get_all_aturan_agregasi_aktif';
	    $data['agregasi'] = json_decode($this->curl->simple_post($url, $param), TRUE);

	    print_r($data['agregasi']);
	}
	function test2(){
		$kd_dosen = '197701032005011003';
		$kd_ta = '2017';
		$kd_smt = '2';

		$url = "http://service.uin-suka.ac.id/servbkd/index.php/bkd_public/";
		$api_url 	= $url.'bkd_remun/get_prosentase_skr_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $kd_smt, $kd_ta));
		$persen_skr = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		print_r($persen_skr);
	}*/
		
}
?>
