<?php
// DNG A BMTR [ ++ | 03 | 2018]
class Data_dosen extends CI_Controller {
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

	function data_dosen_agregasi(){
		$this->view->output_display('remunerasi_dosen/form_data_dosen_agregasi');
	}

	function get_data_fakultas(){
		$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
		$data= $this->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);

		echo json_encode($data);
	}

	function get_data_prodi(){
		$fak = $this->input->post('fak');
		$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($fak));
		$data= $this->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);

		echo json_encode($data);
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

	function get_ds(){
		$data = array(
			array('KD' =>'X0X', 'STATUS' => 'Semua'),
			array('KD' =>'DT', 'STATUS' => 'Dosen dengan Tugas Tambahan'),
			array('KD' =>'DK', 'STATUS' => 'Dosen dengan Tugas Khusus'),
			array('KD' =>'DS', 'STATUS' => 'Dosen Biasa')
		);

		echo json_encode($data);
	}

	function get_thn(){
		//$now = $this->session->userdata('kd_ta');
		
		$now = date('Y');
		$data = array();
		$temp_ta = $now; 
		for ($a=$now; $a>=$now-5; $a--){ 
			$b = $a+1; $tahun = $a;
			if($tahun == $temp_ta){ $pilih = 'selected';}else {$pilih = '';}

			$data[] = array(
					'KD_TA' => $a,
					'TA' 	=> $tahun,
					'STATUS' => $pilih
			);

		}

		echo json_encode($data);

	}

	function get_bulan(){
		$bulan = array(
					array('KD_BLN' => '01', 'NM_BLN' => 'JANUARI'),
					array('KD_BLN' => '02', 'NM_BLN' => 'FEBRUARI'),
					array('KD_BLN' => '03', 'NM_BLN' => 'MARET'),
					array('KD_BLN' => '04', 'NM_BLN' => 'APRIL'),
					array('KD_BLN' => '05', 'NM_BLN' => 'MEI'),
					array('KD_BLN' => '06', 'NM_BLN' => 'JUNI'),
					array('KD_BLN' => '07', 'NM_BLN' => 'JULI'),
					array('KD_BLN' => '08', 'NM_BLN' => 'AGUSTUS'),
					array('KD_BLN' => '09', 'NM_BLN' => 'SEPTEMBER'),
					array('KD_BLN' => '10', 'NM_BLN' => 'OKTOBER'),
					array('KD_BLN' => '11', 'NM_BLN' => 'NOVEMBER'),
					array('KD_BLN' => '12', 'NM_BLN' => 'DESEMBER'),
				);

		$now = date('m');
		//$data = array();
		for ($i=0; $i < count($bulan); $i++) { 
			if($bulan[$i]['KD_BLN'] == $now){
				$bulan[$i]['STATUS'] = "selected";
			}else{
				$bulan[$i]['STATUS'] = " ";
			}
		}

		echo json_encode($bulan);

	}

	function get_data_dosen(){
		$kd_prodi 	= $this->input->post('prod');
		$asal 		= $this->input->post('asal');
		$status 	= $this->input->post('status');
		$jenis 		= $this->input->post('jenis');
		$tahun 		= $this->input->post('ta');
		$bln 		= $this->input->post('bln');

		// $bln = '12';

		// $tahun = '2018';

		$tgl = '15/'.$bln.'/'.$tahun;
		$semester = $this->get_data_semester($tgl, $kd_prodi);

		// $smt = '2';
		// $ta  = date('Y');

		if($semester){
			$smt = $semester[0]['KD_SMT'];
			$ta  = $semester[0]['KD_TA'];
		}

		if($smt == 2){
			$smt--;
		}else{
			$ta--;
			$smt++;
		}

		$dosen= $this->api->get_api_json(URL_API_SIA.'sia_penawaran/data_search', 'POST', array('api_kode'=>58002, 'api_subkode' => 9, 'api_search' => array($kd_prodi, $asal, $status)));
		$data = array();


		if($dosen){
			//GET ATURAN BISA DISINI, BIAR AMBILNYA SEKALI AJA
			$aturan = $this->get_all_aturan_agregasi_aktif();
			$maks   = $this->get_batas_maksimum_agregasi();

			foreach ($dosen as $d) {
				$kd_dosen = $d['KD_DOSEN'];

				$cek_nip = substr($kd_dosen, -3, 1);

				if(($jenis == '0' && $cek_nip != '3') || ($jenis == '1' && $cek_nip == '3') || ($jenis == '2')){

					$status_dosen = $this->_status_DS($kd_dosen, $ta, $smt);

					//PRESENSI
					$presensi = $this->get_presensi($kd_dosen, $tahun, $bln);
					if($presensi != 'X0X'){
						if(!empty($maks)){
							if($presensi > $maks['PRESENSI']){
								$presensi = $maks['PRESENSI'];
							}
						}

						$presensi_s = ($aturan[$status_dosen]['PRESENSI'] / 100) * $presensi;
						$presensi = number_format($presensi, 2);
					}else{
						$presensi_s = 0;
						$presensi = 'BU';
					}

					//SKR :
					$skr = $this->get_skr($kd_dosen, $ta, $smt);
					if(!empty($maks)){
						if($skr > $maks['SKR']){
							$skr = $maks['SKR'];
						}
					}
					$skr_s = ($aturan[$status_dosen]['SKR'] / 100) * $skr;

					//IKU
					$iku = $this->get_iku($kd_dosen, $ta, $smt);
					if($iku != 'X0X'){
						if(!empty($maks)){
							if($iku > $maks['IKU']){
								$iku = $maks['IKU'];
							}
						}
						$iku_s = ($aturan[$status_dosen]['IKU'] / 100) * $iku;
						$iku   = number_format($iku, 2);
					}else{
						$iku_s = 0;
						$iku   = 'BU';
					}
					

					//SKP
					$skp = $this->get_skp($kd_dosen, $ta, $smt);
					if($skp != 'X0X'){
						if(!empty($maks)){
							if($skp > $maks['SKP']){
								$skp = $maks['SKP'];
							}
						}
						$skp_s = ($aturan[$status_dosen]['SKP'] / 100) * $skp;
						$skp   = number_format($skp, 2);
					}else{
						$skp_s = 0;
						$skp   = 'BU';
					}
				

					//IKD
					$ikd = $this->get_ikd($kd_dosen, $ta, $smt);
					if(!empty($maks)){
						if($ikd > $maks['IKD']){
							$ikd = $maks['IKD'];
						}
					}
					$ikd_s = ($aturan[$status_dosen]['IKD'] / 100) * $ikd;

					//TOTAL
					$total = $presensi_s + $skr_s + $iku_s + $skp_s + $ikd_s;


					$data[] = array(
						'KD_DOSEN'	=> $kd_dosen,
						'NAMA' 		=> $d['NM_DOSEN_F'],
						'JENIS' 	=> $status_dosen,
						'PRESENSI'  => $presensi,
						'SKR'		=> number_format($skr, 2),
						'IKU' 		=> $iku,
						'SKP' 		=> $skp,
						'IKD' 		=> number_format($ikd, 2),
						'TOTAL' 	=> number_format($total, 2)
					);
				}
			}
		}




		if(!empty($data)){
			echo json_encode($data);
		}
		else{
			echo '0';
		}
	}


	function testing(){
		//$cek = $this->get_skr('197701032005011003', '2017', '2');

		$kd_prodi 	= '22607';
		$asal 		= '2';
		$status 	= '0';
		$jenis 		= '2';
		$tahun 		= '2018';
		$bln 		= '03';


		$tgl = '15/'.$bln.'/'.$tahun;
		$semester = $this->get_data_semester($tgl, $kd_prodi);

		echo "tanggal : ".$tgl." </br>";
		echo "prodi : ".$kd_prodi." </br></br>";

		echo '<pre>';
		print_r($semester);
		echo '</pre>';


		// $smt = '2';
		// $ta  = date('Y');

		if($semester){
			$smt = $semester[0]['KD_SMT'];
			$ta  = $semester[0]['KD_TA'];
		}

		if($smt == 2){
			$smt--;
		}else{
			$ta--;
			$smt++;
		}

		$dosen= $this->api->get_api_json(URL_API_SIA.'sia_penawaran/data_search', 'POST', array('api_kode'=>58002, 'api_subkode' => 9, 'api_search' => array($kd_prodi, $asal, $status)));
		$data = array();


		if($dosen){
			//GET ATURAN BISA DISINI, BIAR AMBILNYA SEKALI AJA
			$aturan = $this->get_all_aturan_agregasi_aktif();

			foreach ($dosen as $d) {
				$kd_dosen = $d['KD_DOSEN'];

				$cek_nip = substr($kd_dosen, -3, 1);

				
					$status_dosen = $this->_status_DS($kd_dosen, $ta, $smt);

					//PRESENSI
					$presensi = $this->get_presensi($kd_dosen, $ta, $smt);
					$presensi = number_format((($aturan[$status_dosen]['PRESENSI'] / 100) * $presensi), 2);
					//SKR :
					$skr = $this->get_skr($kd_dosen, $ta, $smt);
					$skr = number_format((($aturan[$status_dosen]['SKR'] / 100) * $skr), 2);

					//IKU
					$iku = $this->get_iku($kd_dosen, $ta, $smt);
					$iku = number_format((($aturan[$status_dosen]['IKU'] / 100) * $iku), 2);

					//SKP
					$skp = $this->get_skp($kd_dosen, $ta, $smt);
					$skp = number_format((($aturan[$status_dosen]['SKP'] / 100) * $skp), 2);

					//IKD
					$ikd = $this->get_ikd($kd_dosen, $ta, $smt);
					$ikd = number_format((($aturan[$status_dosen]['IKD'] / 100) * $ikd), 2);

					//TOTAL
					$total = $presensi + $skr + $iku + $skp + $ikd;


					$data[] = array(
						'KD_DOSEN'	=> $kd_dosen,
						'NAMA' 		=> $d['NM_DOSEN_F'],
						'JENIS' 	=> $status_dosen,
						'PRESENSI'  => $presensi,
						'SKR'		=> $skr,
						'IKU' 		=> $iku,
						'SKP' 		=> $skp,
						'IKD' 		=> $ikd,
						'TOTAL' 	=> $total
					);
				


				
			}
		}

		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}


	//FUNGSI UNTUK MENGETAHUI STATUS JENIS DOSEN :

	function _status_DS($kd_dosen, $kd_ta, $kd_smt){
		$struk = $this->_cek_jab_struk($kd_dosen, $kd_ta, $kd_smt);
		$fungs = $this->_cek_jab_fungsional($kd_dosen, $kd_ta, $kd_smt);
		if ($struk == 1){
			if($fungs == 1) $status = 'DT';//'PT';
			else $status = 'DT';
		}else{
			if($fungs == 1) $status = 'DS';//'PR';
			else $status = 'DS';
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

	function _cek_jab_struk($kd_dosen, $kd_ta, $kd_smt){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' =>1121, 'api_subkode' => 5, 'api_search' => array($kd_dosen));
		$data = $this->api->get_api_jsob($api_url,'POST',$parameter);
		#return $data; die();
		$tgl_dok = $this->tanggal_akhir_ta_default($kd_ta, $kd_smt);
		$status = 0;
		foreach ($data as $jab){
			if($jab->RR_STATUS == 1 || $jab->RR_TANGGAL_AKHIR_F == ''){
				$status = 1;
				break;
			}
			
			if($jab->RR_TANGGAL_MULAI !== '' && $jab->RR_TANGGAL_MULAI > $tgl_dok){
				$status = 0;
				break;
			}
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
	function get_all_aturan_agregasi_aktif(){
		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/get_all_aturan_agregasi_aktif';
	    $aktif = json_decode($this->curl->simple_post($url, $param), TRUE);

	    $data = array();
	    foreach ($aktif as $a) {
	    	$data[$a['status_dosen']] = array(
	    		'PRESENSI' 	=> $a['kehadiran'],
	    		'SKR'		=> $a['skr'],
	    		'IKU' 		=> $a['iku'],
	    		'SKP' 		=> $a['skp'],
	    		'IKD' 		=> $a['ikd']
	    	);
	    }

	    // echo '<pre>';
	    // print_r($data);
	    // echo '</pre>';
	    //return $data['ag_aktif'];

	    return $data;
	}



	function get_presensi($kd_dosen, $tahun, $bulan){
		$url 		= 'http://service5.uin-suka.ac.id/servremun/remun/api/';
		$parameter 	= array('ID_PEGAWAI'=> $kd_dosen, 'TAHUN'=>$tahun, 'BULAN'=>$bulan);
		$data 	= $this->s00_lib_api->get_api_json($url.'kehadiran_pegawai', 'POST', $parameter);

		$presensi = 'X0X'; // BELUM UPLOAD DATA 
		if(!empty($data)){
			$jml_hari = $data[0]['JML_HARI'];
			$jml_hadir = $data[0]['JML_HADIR'];

			$presensi = number_format(($jml_hadir / $jml_hari ) * 100 ,2);// INI NANTI BENTUKNYA SUDAH PERSENTASE
		}

		return $presensi;
	}

	function get_skr($kd_dosen, $kd_ta, $kd_smt){
		$url = "http://service.uin-suka.ac.id/servbkd/index.php/bkd_public/";
		$api_url 	= $url.'bkd_remun/get_prosentase_skr_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $kd_smt, $kd_ta));
		$skr = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		if($skr){
			$data = $skr;
		}else{
			$data = 0;
		}
		return (float) str_replace(',', '.', $data);
	}

	function get_iku($kd_dosen, $kd_ta, $kd_smt){
		return 'X0X';
	}

	function get_skp($kd_dosen, $kd_ta, $kd_smt){
		return 'X0X';
	}

	/*function get_ikd($kd_dosen, $kd_ta, $kd_smt){
		return 0;
	}*/

	function get_ikd($kd_dosen, $kd_ta, $kd_smt){
		$url = "http://service.uin-suka.ac.id/servikd/index.php/ikd_public/";

		$data	= $this->s00_lib_api->get_api_json(
						$url.'ikd_dosen/ikd_smt',
						'POST',
						array(  'api_kode' => 1000,
								'api_subkode' => 1,
								'api_search' => array($kd_dosen, $kd_ta, $kd_smt))
			);



		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/get_aturan_agregasi_ikd';
	    $aktif = json_decode($this->curl->simple_post($url, $param), TRUE);

	    $nilai_max = 4;
	    if($aktif){
	    	$nilai_max = (float) str_replace(',', '.', $aktif[0]['batas_bawah']);
	    }


		$ikd = 0;
		if($data){
			$ikd = (float) str_replace(',', '.', $data[0]['IKD']);
		}
		
		$ikd = $ikd * 100 / $nilai_max;

		if($ikd > 100){
			$ikd = 100;
		}

		return $ikd;
	}

	function get_data_semester($tanggal, $kd_prodi){
		$semester = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST', array('api_kode'=>52000, 'api_subkode'=>12, 'api_search' => array($tanggal, $kd_prodi)));
		return $semester;
	}

	function get_batas_maksimum_agregasi(){

		$param = array();
		$url = URL_API_REMUN_DOSEN.'agregasi/get_nilai_max_komponen_agregasi';
	    $aktif = json_decode($this->curl->simple_post($url, $param), TRUE);

	   	$data = array();
	   	if($aktif){
	   		$data = array(
	    		'PRESENSI' 	=> (float) str_replace(',', '.', $aktif[0]['kehadiran']),
	    		'SKR'		=> (float) str_replace(',', '.', $aktif[0]['skr']),
	    		'IKU' 		=> (float) str_replace(',', '.', $aktif[0]['iku']),
	    		'SKP' 		=> (float) str_replace(',', '.', $aktif[0]['skp']),
	    		'IKD' 		=> (float) str_replace(',', '.', $aktif[0]['ikd'])
	    	);
	   	}

	   	return $data;
	}


	function cek_api(){
		$url = "http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search";
		$data = $this->s00_lib_api->get_api_json(
			$url,
			'POST',
			array('api_kode'=>1121, 'api_subkode'=>15, 'api_search' => array('UN01028'))
		);

		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}
		
}

