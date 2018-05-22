<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

 class Remun extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('bkd_lib_setting','','setting');
		$this->load->library('bkd_lib_history','','history');
		$this->load->library('bkd_upload_blob','','blob');
		$this->load->library('bkd_lib_sks_rule','','sksrule');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}

	function data($jenis, $detail = null, $action = null){
		$data['title'] = "Pendidikan";
		$jenis = $this->security->xss_clean($this->uri->segment(5));
		$data['kode'] = $jenis;
		$data['kd_dosen'] = $this->session->userdata('kd_dosen');
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');


		switch ($jenis) {
			case 'A':
				$this->auto_data_pendidikan();
				break;

			case 'B':
				$this->auto_data_penelitian();
				break;

			case 'D':
				$this->auto_data_penunjang();
				break;
			
			default:
				# do nothing !
				break;
		}


		$data['is_crud'] = $this->setting->_is_crud_bkd_lalu($data['ta'], $data['smt']);
		$api_url 	= URL_API_BKD.'bkd_remun/get_jadwal';
		$parameter  = array();
		$jadwal = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($jadwal)){
			$data['tombol'] = 'style="display:block"';
		}else{
			$data['tombol'] = 'style="display:none"';
			$data['tombol2'] ='style="display:;"';
			$data['tombol3'] ='style="display:;pointer-events:none; cursor:default;"';
		}

		if ($detail !== null){
			if ($action !== ''){
				switch ($action){
					case 'penugasan-isi': $data['view'] = 'dosen/isi_dokumen_remun'; break;
					case 'penugasan-cari': {
							$data['view'] = 'dosen/cari_dokumen_remun';
						}break;
					case 'penugasan-upload': $data['view'] = 'dosen/upload_dokumen_remun'; break;
					case 'kinerja-isi': $data['view'] = 'dosen/isi_dokumen_remun'; break;
					case 'kinerja-cari': {
							$data['view'] = 'dosen/cari_dokumen_remun'; 
					}break;
					case 'kinerja-upload': $data['view'] = 'dosen/upload_dokumen_remun'; break;
					default : $data['view'] = 'dosen/isi_dokumen_remun'; break;
				}
				$api_url 	= URL_API_BKD.'bkd_remun/current_data_bkd';
				$parameter  = array('api_search' => array($detail, $data['kode']));
				$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				# GET PARTNER
				$api_url 	= URL_API_BKD.'bkd_dosen/partner';
				$parameter  = array('api_kode' => 11000, 'api_subkode' => 1, 'api_search' => array($detail,'PENELITIAN'));
				$data['partner'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				
				$data['nama_partner'] = array();
				foreach ($data['partner'] as $p){
					$data['name_partner'][$p->PARTNER] = $this->get_nama_partner($p->PARTNER);
				}
				
			}
			$api_url 	= URL_API_BKD.'bkd_remun/current_data_bkd';
			$parameter  = array('api_search' => array($detail, $data['kode']));
			$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}

		$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
		$parameter  = array('api_search' => array($data['kode'], $data['kd_dosen'], $data['ta'], $data['smt']));
		$data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$i =0;
		foreach ($data['data_remun'] as $key) {

			if(isset($key->KETERANGAN)){
				$min_mhs = $this->_cek_batas_minimal_mhs($key->KETERANGAN);
				if($key->JML_MHS >= $min_mhs ){
					$data['data_remun'][$i]->STS_JML_MHS = 1;
				}else{
					$data['data_remun'][$i]->STS_JML_MHS = 0;
				}
			}

			$kd_kat = $key->KD_KAT;
			$status_pindah = $key->STATUS_PINDAH;
			if($status_pindah == 2){
				$kd_kat_remun = $kd_kat;
			}elseif($status_pindah == 0){
				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_remun2';
				$parameter  = array('api_search' => array($kd_kat));
				$kd_kat_remun = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
				/*$kd_kat_remun = $kode['KD_KAT_REMUN'];*/
			}
			
			$data['data_remun'][$i]->KD_KAT_REMUN = $kd_kat_remun;
			$i++;

			$api_url 	= URL_API_BKD.'bkd_remun/get_jenis_nilai_kat_remun';
			$parameter  = array('api_search' => array($kd_kat_remun));
			$data['nilai_kat'][$kd_kat_remun] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

			/*echo "<pre>";
			print_r($kd_kat_remun); 
			echo "<pre>";*/

			$kd_bk = $key->KD_BK;
			$api_url 	= URL_API_BKD.'bkd_remun/ambil_kd_kat_remun';
			$parameter 	= array('api_search'=>array($kd_bk));
			$kd_kat = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			
			$api_url 	= URL_API_BKD.'bkd_remun/lihat_kd_konversi';
			$parameter 	= array('api_search'=>array($kd_kat));
			$data['konversi'][$kd_kat] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);			
		}
		/*die();*/
		/*echo "<pre>";
		print_r($datap); 
		echo "<pre>";
		die();*/ 
		$this->output99->output_display('dosen/form_remun_pendidikan',$data);				
			
	}



	//FUNGSI UNTUK CEK JENIS MATAKULIAH :
	function _cek_jenis_mk($kd_kelas){
		#sia_penawaran/data_search, 58000/6, api_search = array(kd_kelas)
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array('api_kode'=>58000, 'api_subkode' => 6, 'api_search' => array($kd_kelas));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		return $data[0]->NM_JENIS_MK;
	}

	function _cek_batas_minimal_mhs($kd_kelas){
		$jenis = $this->_cek_jenis_mk($kd_kelas);

		$syarat = $this->api->get_api_json(
			URL_API_BKD.'/bkd_remun/get_syarat_mengajar',
			'POST',
			array('api_search' => array($jenis))
		);
	}




	function pendidikan($jenis, $detail = null, $action = null){
		$data['title'] = "Pendidikan";
		$jenis = $this->security->xss_clean($this->uri->segment(5));
		$data['kode'] = $jenis;
		$data['kd_dosen'] = $this->session->userdata('kd_dosen');
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');

		$data['is_crud'] = $this->setting->_is_crud_bkd_lalu($data['ta'], $data['smt']);
		$api_url 	= URL_API_BKD.'bkd_remun/get_jadwal';
		$parameter  = array();
		$jadwal = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($jadwal)){
			$data['tombol'] = 'style="display:block"';
		}else{
			$data['tombol'] = 'style="display:none"';
		}

		if ($detail !== null){
			if ($action !== ''){
				switch ($action){
					case 'penugasan-isi': $data['view'] = 'dosen/isi_dokumen_remun'; break;
					case 'penugasan-cari': {
							$data['view'] = 'dosen/cari_dokumen';
						}break;
					case 'penugasan-upload': $data['view'] = 'dosen/upload_dokumen'; break;
					case 'kinerja-isi': $data['view'] = 'dosen/isi_dokumen'; break;
					case 'kinerja-cari': {
							$data['view'] = 'dosen/cari_dokumen'; 
					}break;
					case 'kinerja-upload': $data['view'] = 'dosen/upload_dokumen'; break;
					default : $data['view'] = 'dosen/isi_dokumen'; break;
				}
				$api_url 	= URL_API_BKD.'bkd_remun/current_data_bkd';
				$parameter  = array('api_search' => array($detail, $data['kode']));
				$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				# GET PARTNER
				$api_url 	= URL_API_BKD.'bkd_dosen/partner';
				$parameter  = array('api_kode' => 11000, 'api_subkode' => 1, 'api_search' => array($detail,'PENELITIAN'));
				$data['partner'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				
				$data['nama_partner'] = array();
				foreach ($data['partner'] as $p){
					$data['nama_partner'][$p->PARTNER] = $this->get_nama_partner($p->PARTNER);
				}
				
			}
			$api_url 	= URL_API_BKD.'bkd_remun/current_data_bkd';
			$parameter  = array('api_search' => array($detail, $data['kode']));
			$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}

		$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
		$parameter  = array('api_search' => array($data['kode'], $data['kd_dosen'], $data['ta'], $data['smt']));
		$data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$this->output99->output_display('dosen/form_remun_pendidikan',$data);				
			
	}

	function cek_fn(){
		//$data = $this->get_jabatan_fungsional($this->session->userdata('kd_dosen'));
		$nip = $this->session->userdata('kd_dosen');
		$data = $this->s00_lib_api->get_api_json(
					'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search',
					'POST',
					array(
						'api_kode'		=> 1122,
						'api_subkode'	=> 3,
						'api_search'	=> array(date('d-m-Y'),$nip,1)
					)
			);
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

	function get_jabatan_fungsional($nip)
		{
			//$CI =& get_instance();
			$data = $this->s00_lib_api->get_api_json(
					'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search',
					'POST',
					array(
						'api_kode'		=> 1122,
						'api_subkode'	=> 3,
						'api_search'	=> array(date('d-m-Y'),$nip,1)
					)
			);

			return $data[0]['FUN_NAMA'];
		}

	function tambah(){
		$data['title'] = "Pendidikan";

		$kode = $this->security->xss_clean($this->uri->segment(5));
		$data['kode'] = $kode;

		$this->session->set_flashdata('item', $data['kode']);

		$data['kd_dosen'] = $this->session->userdata('kd_dosen');
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		$data['status'] = $this->session->userdata('jenis_dosen');

		if($data['kode'] == 'D' && ($data['status'] == 'PR' || $data['status'] == 'DS')){
			# load view
			$this->output99=$this->s00_lib_output;
			$this->output99->output_display('dosen/beban_tambahan_message');
		}else{
			$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
			$parameter  = array('api_search' => array($data['kode'],$data['kd_dosen'], $data['ta'], $data['smt']));
			$data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			$i=0;
			foreach ($data['data_remun'] as $key) {
				$kd_kat = $key->KD_KAT;
				$status_pindah = $key->STATUS_PINDAH;
				if($status_pindah == 2){
					$kd_kat_remun = $kd_kat;
				}elseif($status_pindah == 0){
					$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_remun2';
					$parameter  = array('api_search' => array($kd_kat));
					$kd_kat_remun = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
					/*$kd_kat_remun = $kode['KD_KAT_REMUN'];*/
				}
				$data['data_remun'][$i]->KD_KAT_REMUN = $kd_kat_remun;
				$i++;
				$api_url 	= URL_API_BKD.'bkd_remun/get_jenis_nilai_kat_remun';
				$parameter  = array('api_search' => array($kd_kat_remun));
				$data['nilai_kat'][$kd_kat_remun] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			}

			# cek jenis beban kerja 
			if($kode == 'F'){
				$data['title'] = "Narasumber/Pembicara Kegiatan";
				$api_url 	= URL_API_BKD.'bkd_remun/tingkat';
				$parameter  = array('api_kode'=>1000, 'api_subkode'=>1, 'api_search' => array());
				$data['kategori'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			}else if($kode == 'G'){				
			
			}else if($kode == 'H'){				
				$data['jenis_haki'] = $this->s00_lib_api->get_api_jsob(URL_API_BKD.'bkd_remun/get_jenis_haki','POST',array());	
			}else{
				$api_url 	= URL_API_BKD.'bkd_remun/get_kategori';
				$parameter  = array('api_search' => array($data['kode']));
				$data['kategori'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}
			$data['name_prodi'] = $this->get_prodi();
			$data['list_kategori'] = $this->daftar_kategori_remun($data['kode']);
			/*echo "<pre>";
			print_r($data['name_prodi']);
			echo "<pre>";
			die;*/
			$this->output99->output_display('dosen/form_tambah_remun_pendidikan',$data);
		}
		
		
	}
	function penelitian(){
		$data['title'] = "Penelitian";
		$data['kd_dosen'] = $this->session->userdata('kd_dosen');
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		
		$this->output99->output_display('dosen/form_remun_penelitian',$data);				
			
	}
	
	function penunjang(){
		$data['title'] = "Penunjang";
		$data['kd_dosen'] = $this->session->userdata('kd_dosen');
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		
		$this->output99->output_display('dosen/form_remun_penunjang',$data);				
			
	}
	
	function kesimpulan_old(){
		$data['title'] = "Kesimpulan";
		$jenis = $this->security->xss_clean($this->uri->segment(5));
		//$data['kode'] = $jenis;
		$data['kode'] = "A";
		$data['kd_dosen'] = $this->session->userdata('kd_dosen');

		$tahun = $this->input->post('thn');

		if($tahun == ''){
			$data['ta'] = $this->session->userdata('ta');
			$data['smt'] = $this->session->userdata('smt');		
		}else{
			$data['ta'] = $tahun;
			$data['smt'] = $this->input->post('smt');
		}

		/*$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');*/
		$jabatan = $this->get_jabatan_fungsional($data['kd_dosen']);
		
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($data['kd_dosen']));
		$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$data['namaLengkap'] = $this->getDataDosen($data['kd_dosen']);

		$kd_ta = $this->_generate_ta($data['ta']);
		$kd_smt = $this->_generate_smt($data['smt']);
		$this->session->unset_userdata('jenis_dosen');
		$this->session->set_userdata('jenis_dosen', $this->history->_status_DS($data['kd_dosen'], $data['ta'], $data['smt']));

		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($this->session->userdata('jenis_dosen'));
		foreach ($data['dosen'] as $val) {
			if ($val->NO_SERTIFIKAT == ''){
				$data['noser'] = '-';
			}else{
				$data['noser'] = $val->NO_SERTIFIKAT;
			}
		}
		
		$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
		$parameter  = array('api_search' => array($data['kode'], $data['kd_dosen'], $data['ta'], $data['smt']));
		$data['data_remun'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		$temp_sks 	= 0;
		$temp_poin 	= 0;
		$temp_satuan = 0;
		$temporary = array();

		foreach ($data['data_remun'] as $key) {
			$jenjang 	= $key['JENJANG'];
			$kelas 		= $key['KELAS'];
			$semester   = $key['SEMESTER'];
			$kd_kat  	= $key['KD_KAT'];
			$kd_kat_remun = $key['KD_KAT_REMUN'];

			/*if($kd_kat == '1'){
				$sks 	= $key['JML_SKS'];
			}elseif($kd_kat=='3'){
				$sks = 1;
			}*/
			switch ($kd_kat_remun) {
				case '1':
					$sks 	= $key['JML_SKS'];
					break;
				case '2':
					$sks 	= $key['JML_MHS'];
					break;
				case '4':
					$sks 	= $key['JML_MHS'];
					break;
				case '67':
					$sks 	= $key['JML_MHS'];
					break;
				case '68':
					$sks 	= $key['JML_MHS'];
					break;
				case '21':
					$sks 	= $key['JML_MHS'];
					break;
				default:
					$sks = $key['JML_SKS'];
					break;
			}
			
			$tatapmuka  = $key['JML_TM'];
			$tm_per_minggu  = $key['JML_PERTEMUAN_PM']; // pertemuan perminggu ()
			$jml_dosen 		= $key['JML_DOSEN'];


			$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_remun';
			$parameter  = array('api_search' => array($kd_kat));
			$tmp_kategori = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			$kategori 	= $tmp_kategori['KD_KAT_REMUN'];

			if(!isset($temporary[$kategori])){
				$temporary[$kategori]['JUDUL'] = $tmp_kategori['JUDUL'];
				$temporary[$kategori]['NILAI'] = 0;
				$temporary[$kategori]['SATUAN'] = 0;
				$temporary[$kategori]['POIN'] = 0;

			}

			$api_url 	= URL_API_BKD.'bkd_remun/get_poin_remun';
			$parameter  = array('api_search' => array($data['kode'], $jenjang, $kelas, $jabatan, $semester, $kategori));
			$poin 		= $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

						
			$temp_sks += $sks;
			$temp_poin += $poin['POIN'] * ($sks / $jml_dosen) * ($tatapmuka / $tm_per_minggu);
			$temp_satuan = $poin['SATUAN'];

			$temporary[$kategori]['NILAI'] += $sks;
			$temporary[$kategori]['SATUAN'] = $poin['SATUAN'];
			$temporary[$kategori]['POIN'] += $poin['POIN'] * ($sks / $jml_dosen) * ($tatapmuka / $tm_per_minggu);
		}
		$poin_a = $temp_poin;
		$data_penunjang = $this->hitung_kesimpulan_d();

		$temp = array();
		foreach ($data_penunjang as $dp) {
			$temp = $dp['POIN'];
		}
		$poin_d = $temp;
		$poin_total = $poin_a+$poin_d;


		$jabatan = $this->cek_jenis_dosen();
		//hanya sementara, karena di poin remun yang diinut masih nama jabatan pegawai, belum berupa kode pegawai
		//start
		switch ($jabatan) {
			case 'Tenaga Pengajar':
				$kd_jabatan = 1;
				break;
			case 'Asisten Ahli':
				$kd_jabatan = 2;
				break;
			case 'Lektor':
				$kd_jabatan = 3;
				break;
			case 'Lektor Kepala':
				$kd_jabatan = 4;
				break;
			case 'Guru Besar':
				$kd_jabatan =5;
				break;
			default:
				break;
		}
		//finish
		$jml_bulan = $this->hitung_jarak_bulan_semester();
		//$rata_poin = $temp_poin/$jml_bulan;
		$rata_poin = $poin_total/$jml_bulan;

		$api_url 	= URL_API_BKD.'bkd_remun/get_poin_skr';
		$parameter  = array('api_search' => array($kd_jabatan));
		$data['poin_skr'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		$poin_skr = $data['poin_skr']['POIN_SKR'];
		$nilai_kinerja = number_format(($rata_poin/$poin_skr)*100, 2);

		$api_url 	= URL_API_BKD.'bkd_remun/get_max_poin_skr';
		$parameter  = array('api_search' => array());
		$data['max_poin_skr'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		$max_poin_skr = $data['max_poin_skr']['MAX_POIN_SKR'];

		if($nilai_kinerja > $max_poin_skr){
			$prosentase_skr = $max_poin_skr;
		}elseif($nilai_kinerja<=$max_poin_skr){
			$prosentase_skr = $nilai_kinerja;
		}

		$data['kesimpulan'] = array(
			'KODE' => 'A',
			'KATEGORI' => 'MELAKSANAKAN PERKULIAHAN',
			'NILAI' => $temp_sks,
			'JML_POIN' => $poin_total,
			'RATA_POIN' => $rata_poin,
			'NILAI_KINERJA' => $prosentase_skr,
			'SATUAN' =>$temp_satuan
		);

		//$data['summary'] = $temporary;
		$data['summary'] = $temporary;
		$data['summary_d'] = $this->hitung_kesimpulan_d();
		//$data['summary'] = array_merge($data['summary_a'], $data['summary_d']);

		/*echo "<pre>";
		print_r($data['summary']);
		echo "</pre>";
		die();*/

		$this->output99->output_display('dosen/form_remun_kesimpulan_old',$data);
	}
	function hitung_kesimpulan_d(){
		$data['title'] = "Kesimpulan";
		$jenis = $this->security->xss_clean($this->uri->segment(5));
		//$data['kode'] = $jenis;
		$data['kode'] = "D";
		$data['kd_dosen'] = $this->session->userdata('kd_dosen');

		$tahun = $this->input->post('thn');

		if($tahun == ''){
			$data['ta'] = $this->session->userdata('ta');
			$data['smt'] = $this->session->userdata('smt');		
		}else{
			$data['ta'] = $tahun;
			$data['smt'] = $this->input->post('smt');
		}

		/*$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');*/
		$jabatan = $this->get_jabatan_fungsional($data['kd_dosen']);
		
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($data['kd_dosen']));
		$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$data['namaLengkap'] = $this->getDataDosen($data['kd_dosen']);

		$kd_ta = $this->_generate_ta($data['ta']);
		$kd_smt = $this->_generate_smt($data['smt']);
		$this->session->unset_userdata('jenis_dosen');
		$this->session->set_userdata('jenis_dosen', $this->history->_status_DS($data['kd_dosen'], $data['ta'], $data['smt']));

		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($this->session->userdata('jenis_dosen'));
		foreach ($data['dosen'] as $val) {
			if ($val->NO_SERTIFIKAT == ''){
				$data['noser'] = '-';
			}else{
				$data['noser'] = $val->NO_SERTIFIKAT;
			}
		}
		
		$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
		$parameter  = array('api_search' => array($data['kode'], $data['kd_dosen'], $data['ta'], $data['smt']));
		$data['data_remun'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		// echo "<pre>";
		// print_r($data);
		// echo "</pre>";
		$temp_sks 	= 0;
		$temp_poin 	= 0;
		$temp_satuan = 0;
		$temporary = array();

		foreach ($data['data_remun'] as $key) {
			$jenjang 	= $key['JENJANG'];
			$kelas 		= $key['KELAS'];
			$semester   = $key['SEMESTER'];
			$kd_kat  	= $key['KD_KAT'];
			$kd_kat_remun = $key['KD_KAT_REMUN'];

			/*if($kd_kat == '1'){
				$sks 	= $key['JML_SKS'];
			}elseif($kd_kat=='3'){
				$sks = 1;
			}*/
			switch ($kd_kat_remun) {
				case '1':
					$sks 	= $key['JML_SKS'];
					break;
				case '2':
					$sks 	= $key['JML_MHS'];
					break;
				case '4':
					$sks 	= $key['JML_MHS'];
					break;
				case '67':
					$sks 	= $key['JML_MHS'];
					break;
				case '68':
					$sks 	= $key['JML_MHS'];
					break;
				case '21':
					$sks 	= $key['JML_MHS'];
					break;
				default:
					$sks = $key['JML_SKS'];
					break;
			}
			
			$tatapmuka  = $key['JML_TM'];
			$tm_per_minggu  = $key['JML_PERTEMUAN_PM']; // pertemuan perminggu ()
			$jml_dosen 		= $key['JML_DOSEN'];


			$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_remun';
			$parameter  = array('api_search' => array($kd_kat));
			$tmp_kategori = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			$kategori 	= $tmp_kategori['KD_KAT_REMUN'];

			if(!isset($temporary[$kategori])){
				$temporary[$kategori]['JUDUL'] = $tmp_kategori['JUDUL'];
				$temporary[$kategori]['NILAI'] = 0;
				$temporary[$kategori]['SATUAN'] = 0;
				$temporary[$kategori]['POIN'] = 0;

			}

			$api_url 	= URL_API_BKD.'bkd_remun/get_poin_remun';
			$parameter  = array('api_search' => array($data['kode'], $jenjang, $kelas, $jabatan, $semester, $kategori));
			$poin 		= $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

						
			$temp_sks += $sks;
			$temp_poin += $poin['POIN'] * ($sks / $jml_dosen) * ($tatapmuka / $tm_per_minggu);
			$temp_satuan = $poin['SATUAN'];

			$temporary[$kategori]['NILAI'] += $sks;
			$temporary[$kategori]['SATUAN'] = $poin['SATUAN'];
			$temporary[$kategori]['POIN'] += $poin['POIN'] * ($sks / $jml_dosen) * ($tatapmuka / $tm_per_minggu);
		}

		$jabatan = $this->cek_jenis_dosen();
		//hanya sementara, karena di poin remun yang diinut masih nama jabatan pegawai, belum berupa kode pegawai
		//start
		switch ($jabatan) {
			case 'Tenaga Pengajar':
				$kd_jabatan = 1;
				break;
			case 'Asisten Ahli':
				$kd_jabatan = 2;
				break;
			case 'Lektor':
				$kd_jabatan = 3;
				break;
			case 'Lektor Kepala':
				$kd_jabatan = 4;
				break;
			case 'Guru Besar':
				$kd_jabatan =5;
				break;
			default:
				break;
		}
		//finish
		$jml_bulan = $this->hitung_jarak_bulan_semester();
		$rata_poin = $temp_poin/$jml_bulan;

		$api_url 	= URL_API_BKD.'bkd_remun/get_poin_skr';
		$parameter  = array('api_search' => array($kd_jabatan));
		$data['poin_skr'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		$poin_skr = $data['poin_skr']['POIN_SKR'];
		$nilai_kinerja = number_format(($rata_poin/$poin_skr)*100, 2);

		$api_url 	= URL_API_BKD.'bkd_remun/get_max_poin_skr';
		$parameter  = array('api_search' => array());
		$data['max_poin_skr'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		$max_poin_skr = $data['max_poin_skr']['MAX_POIN_SKR'];

		if($nilai_kinerja > $max_poin_skr){
			$prosentase_skr = $max_poin_skr;
		}elseif($nilai_kinerja<=$max_poin_skr){
			$prosentase_skr = $nilai_kinerja;
		}

		$data['kesimpulan'] = array(
			'KODE' => 'A',
			'KATEGORI' => 'MELAKSANAKAN PERKULIAHAN',
			'NILAI' => $temp_sks,
			'JML_POIN' => $temp_poin,
			'RATA_POIN' => $rata_poin,
			'NILAI_KINERJA' => $prosentase_skr,
			'SATUAN' =>$temp_satuan
		);

		$data['summary'] = $temporary;
		
		return $data['summary'];
		//$this->output99->output_display('dosen/form_remun_kesimpulan',$data);
	}
	function getDataDosen($kd){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2001, 'api_subkode' => 2, 'api_search' => array($kd));
		$nama = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $nama;
	}
	function _generate_ta($ta){
		return substr($ta, 0,4);
	}
	
	function _generate_smt($smt){
		if($smt == 'GENAP') return 2;
		else return 1;
	}
	//yang blm ada notif ketika input kategori secara manual
	/*function simpan_data_remun(){
		$this->form_validation->set_rules('pend_kategori','<b>Kategori</b>','required|xss_clean');
		$this->form_validation->set_rules('jenis_kegiatan','<b>Jenis Kegiatan</b>','required|xss_clean');
		$this->form_validation->set_rules('sks1','<b>Beban Kerja SKS</b>','xss_clean');
		$this->form_validation->set_rules('masa','<b>Masa Penugasan</b>','required|xss_clean');
		$this->form_validation->set_rules('bukti_dokumen','<b>Bukti Dokumen</b>','xss_clean');
		$this->form_validation->set_rules('sks2','<b>Bukti SKS</b>','xss_clean');
		$this->form_validation->set_rules('rekomendasi','<b>Rekomendasi</b>','xss_clean');
		
		$kd = $this->input->post("kd_jbk");
		
		if ($this->form_validation->run() == FALSE){
			$this->direct_remun($kd);
		}
		else{		
			$a = $kd;
			$b = $this->session->userdata("kd_dosen");
			$temp = $this->input->post("jenis_kegiatan");
			$temp1 = $this->input->post("pend_kategori");
			$c=$temp1." ".$temp;
			$penugasan = $this->input->post("bukti_penugasan");
			$e = $this->input->post("sks1");
			$f = $this->input->post("lama").' '.$this->input->post("masa");
			$dokumen = $this->input->post("bukti_dokumen");
			$h = $this->input->post("sks2");
			$i = $this->session->userdata("ta");
			$j = $this->session->userdata("smt");
			$k = $this->input->post("rekomendasi");
			# data SIPKD 
			$l = $this->input->post("jml_jam");
			$m = $this->input->post("capaian");
			$q = $this->input->post("outcome");
			
			if(strpos($penugasan,':') > 0){
				$split = explode(':', $penugasan);
				$d = $split[1];
				$fp = 'surat:'.$split[0];
			}else{
				$d = $penugasan;
				$fp = '';
			}
			if(strpos($dokumen,':') > 0){
				$split2 = explode(':', $dokumen);
				$g = $split2[1];
				$fc = 'surat:'.$split2[0];
			}else{
				$g = $dokumen;
				$fc = '';
			}
			
			#$fp = $this->input->post("file_penugasan");
			#$fc = $this->input->post("file_capaian");
			$kd_ta = $this->session->userdata('kd_ta');
			$kd_smt = $this->session->userdata('kd_smt');

			$api_url 	= URL_API_BKD.'bkd_remun/simpan_remun';
			$parameter	= array('api_search' => array($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $q, $fp, $fc, $kd_ta, $kd_smt));
			$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# get last id beban kerja
			if($simpan){
				$api_url 	= URL_API_BKD.'bkd_remun/get_current_data_tersimpan';
				$parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
				$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 
			}
			
			if($a == 'A'){
				# data SIPKD Pendidikan
				$kd_bk = $getid;
				$kd_kat = $this->input->post('kd_kat');
				#$nm_kegiatan = $c;
				$jenjang = $this->input->post('jenjang');
				$tempat = $this->input->post('tempat');
				$jml_mhs = $this->input->post('jml_mhs');

				$jml_sks = $this->input->post('jml_sks');
				$jml_dosen = $this->input->post('jml_dosen');
				$jml_tatap_muka = $this->input->post('jml_tatap_muka');
				$jenis_kelas = $this->input->post('jenis_kelas');
				$pertemuan_pm = $this->input->post('pertemuan_pm');
				$nm_prodi = $this->input->post('nama_prodi');
				$satuan = $this->input->post('satuan');

				
				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
				$parameter = array('api_search'=>array($kd_kat));
				$kd_kat_remun = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				if($simpan){
					#SIMPAN DATA PENDIDIKAN (SIPKD)
					$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_pendidikan';
					$parameter	= array('api_search' => array($kd_bk, $kd_kat_remun, $c, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi, $satuan));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$this->session->set_flashdata('msg', array('success', 'Data berhasil disimpan'));
					}else{

						$this->session->set_flashdata('msg', array('danger', 'Data gagal disimpan'));
					}
					
				}else{
					$this->session->set_flashdata('msg', array('danger', 'Data gagal disimpan'));
				}
			
			}else if ($a == 'B' || $a == 'C' || $a == 'F'){
				$kd_bk = $getid;
				$bt_mulai = $this->input->post("bt_mulai");
				$bt_selesai = $this->input->post("bt_selesai");
				$judul_penelitian = $this->input->post("judul_penelitian");
				$sumber_dana = $this->input->post("sumber_dana");
				$jumlah_dana = $this->input->post("jumlah_dana");
				$kd_kat = $this->input->post("kd_kat");
				if ($a == 'B'){
					$status_peneliti = $this->input->post("status_peneliti");
					$laman_publikasi = $this->input->post("laman_publikasi");
					# simpan data penelitian 
					$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_penelitian';
					$parameter	= array('api_search' => array($kd_bk, $bt_mulai, $bt_selesai, $judul_penelitian, $sumber_dana, $jumlah_dana, $kd_kat, $status_peneliti, $laman_publikasi));
					#print_r($parameter['api_search']);
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					# SIMPAN PARTNER PENELITIAN
					$dosen = $this->input->post('dosen');
					$mhs = $this->input->post('mahasiswa');
					$lain = implode('<$>', $this->input->post('lain'));
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PENELITIAN', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
					
				}else if($a == 'C'){
					# simpan data pengabdian
					$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_pengabdian';
					$parameter	= array('api_search' => array($kd_bk, $bt_mulai, $bt_selesai, $judul_penelitian, $sumber_dana, $jumlah_dana, $kd_kat));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					# SIMPAN PARTNER PENGABDIAN
					$dosen = $this->input->post('dosen');
					$mhs = $this->input->post('mahasiswa');
					$lain = implode('<$>', $this->input->post('lain'));
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PENGABDIAN', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}else if($a == 'H'){
					# simpan data HAKI
					if($id != null){
						$api_url 	= URL_API_BKD.'bkd_remun/current_data_haki';
						$parameter  = array('api_search' => array($id));
						$ch=$data['curr_haki'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
											
						$api_url 	= URL_API_BKD.'bkd_remun/current_data_bkd';
						$parameter  = array('api_search' => array($ch->KD_BK, 'H'));
						$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					}
					$api_url 	= URL_API_BKD.'bkd_remun/get_all_dosen';
					$parameter  = array('api_search' => array());
					$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					#$this->output99=$this->s00_lib_output;
					$this->output99->output_display('dosen/form_haki',$data);
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PENGABDIAN', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}else{
					# DATA NARASUMBER
					$judul_acara = $this->input->post('judul_acara');
					$lokasi = $this->input->post('lokasi_acara');
					$kd_tingkat = $this->input->post('kd_tingkat');
					$laman_publikasi = $this->input->post('laman_publikasi');
					$status_peneliti = $this->input->post('status_peneliti');
					
					$dataNarasumber = array(
						'KD_BK' => $kd_bk,
						'BT_MULAI' => $bt_mulai,
						'BT_SELESAI' => $bt_selesai,
						'STATUS_PENELITI' => $status_peneliti,
						'LAMAN_PUBLIKASI' => $laman_publikasi,
						'LOKASI_ACARA' => $lokasi,
						'JUDUL_ACARA' => $judul_acara,
						'KD_TINGKAT' => $kd_tingkat,
					);
					$api_url 	= URL_API_BKD.'bkd_remun/kegiatan_akademik';
					$parameter  = array('api_kode'=>2001, 'api_subkode'=>1, 'api_search' => $dataNarasumber);
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					# SIMPAN PARTNER NARASUMBER
					$dosen = $this->input->post('dosen');
					$mhs = $this->input->post('mahasiswa');
					$lain = implode('<$>', $this->input->post('lain'));
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'NARASUMBER', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}
			}
		
			redirect('bkd/dosen/remun/data/'.$kd);
		}
	}*/
	//yang sudah ada ntif ketika input kategori secara manual
	function simpan_data_remun(){
		$this->form_validation->set_rules('pend_kategori','<b>Kategori</b>','required|xss_clean');
		$this->form_validation->set_rules('jenis_kegiatan','<b>Jenis Kegiatan</b>','required|xss_clean');
		$this->form_validation->set_rules('sks1','<b>Beban Kerja SKS</b>','xss_clean');
		$this->form_validation->set_rules('masa','<b>Masa Penugasan</b>','required|xss_clean');
		$this->form_validation->set_rules('bukti_dokumen','<b>Bukti Dokumen</b>','xss_clean');
		$this->form_validation->set_rules('sks2','<b>Bukti SKS</b>','xss_clean');
		$this->form_validation->set_rules('rekomendasi','<b>Rekomendasi</b>','xss_clean');

		$kd = $this->input->post("kd_jbk");
		$kd_kat = $this->input->post('kd_kat');
		$a = $kd;
		$b = $this->session->userdata("kd_dosen");
		$temp = $this->input->post("jenis_kegiatan");
		$temp1 = $this->input->post("pend_kategori");

		//ambil nama kategori
		$api_url 	= URL_API_BKD.'bkd_remun/get_spesifik_kategori';
		$parameter  = array('api_search' => array($temp1));
		$list_spesifik_kategori = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		$nm_temp = $list_spesifik_kategori['NM_KAT'];

		/*$c=$nm_temp." ".$temp;*/
		$c=$temp;
		$jenjang = $this->input->post('jenjang');
		$jml_mhs = $this->input->post('jml_mhs');
		$jenis_kelas = $this->input->post('jenis_kelas');
		$penugasan = $this->input->post("bukti_penugasan");
		$jml_sks = $this->input->post('jml_sks');
		$jml_tatap_muka = $this->input->post('jml_tatap_muka');
		$pertemuan_pm = $this->input->post('pertemuan_pm');
		$nm_prodi = $this->input->post('nama_prodi');
		$jml_dosen = $this->input->post('jml_dosen');
		$tempat = $this->input->post('tempat');
		$e = $this->input->post("sks1");
		$f1 = $this->input->post("masa");
		$f = $this->input->post("lama").' '.$f1;
		$dokumen = $this->input->post("bukti_dokumen");
		$h = $this->input->post("sks2");
		$i = $this->session->userdata("ta");
		$j = $this->session->userdata("smt");
		$k = $this->input->post("rekomendasi");
		# data SIPKD 
		$l = $this->input->post("jml_jam");
		$m = $this->input->post("capaian");
		if($m=='NaN'){
			$m=0;
		}
		$q = $this->input->post("outcome");
		# data SIPKD Pendidikan
		

		//$kd = $this->input->post("kd_jbk");
		
		if ($this->form_validation->run() == FALSE){
			$this->direct_remun($kd);
		}else{
			if($kd_kat==''){

			$this->session->set_flashdata('msg', array('warning', 'Gagal di proses, pastikan anda mengisi kategori kegiatan sesuai pilihan yang tersedia.'));
			echo '<script type="text/javascript">alert("Gagal di simpan, pastikan anda mengisi kategori kegiatan sesuai pilihan yang tersedia.");history.back(-1);</script>';

			}else{
				//saat kd_kat nya ada nilainya, baru proses simpan dapat dilanjutkan
			if(strpos($penugasan,':') > 0){//maksudnya kalo sudah diberi judul upload dokumennya baru bisa di eksekusi
				$split = explode(':', $penugasan);
				$d = $split[1];
				$fp = 'surat:'.$split[0];
			}else{
				$d = $penugasan;
				$fp = '';
			}
			if(strpos($dokumen,':') > 0){
				$split2 = explode(':', $dokumen);
				$g = $split2[1];
				$fc = 'surat:'.$split2[0];
			}else{
				$g = $dokumen;
				$fc = '';
			}
			
			#$fp = $this->input->post("file_penugasan");
			#$fc = $this->input->post("file_capaian");
			$kd_ta = $this->session->userdata('kd_ta');
			$kd_smt = $this->session->userdata('kd_smt');

			$api_url 	= URL_API_BKD.'bkd_remun/simpan_remun';
			$parameter	= array('api_search' => array($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $q, $fp, $fc, $kd_ta, $kd_smt));
			$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# get last id beban kerja
			if($simpan){
				$api_url 	= URL_API_BKD.'bkd_remun/get_current_data_tersimpan';
				$parameter	= array('api_search' => array('BKD.BKD_REMUN_KINERJA','KD_BK'));
				$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 
			}

			if($a == 'A'){
				$kd_bk = $getid;
				$kd_kat = $this->input->post('kd_kat');
				#$nm_kegiatan = $c;
				$jenjang = $this->input->post('jenjang');
				$tempat = $this->input->post('tempat');
				$jml_mhs = $this->input->post('jml_mhs');
				$jml_sks = $this->input->post('jml_sks');
				$jml_dosen = $this->input->post('jml_dosen');
				$jml_tatap_muka = $this->input->post('jml_tatap_muka');
				$jenis_kelas = $this->input->post('jenis_kelas');
				$pertemuan_pm = $this->input->post('pertemuan_pm');
				$nm_prodi = $this->input->post('nama_prodi');
				$satuan = $this->input->post('satuan');

				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
				$parameter = array('api_search'=>array($kd_kat));
				$kd_kat_serdos = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				if(!empty($kd_kat_serdos)){
					$status_pindah = 0;
					$kd_kat_serdos = $kd_kat_serdos;
				}else{
					$status_pindah = 2;
					$kd_kat_serdos = $kd_kat;
				}

				if($simpan){
					#SIMPAN DATA PENDIDIKAN (SIPKD)
					$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_pendidikan';
					$parameter	= array('api_search' => array($kd_bk, $kd_kat_serdos, $c, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi, $satuan, $status_pindah));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$this->session->set_flashdata('msg', array('success', 'Data berhasil disimpan'));
					}else{

						$this->session->set_flashdata('msg', array('danger', 'Data gagal disimpan'));
					}
					
				}else{
					$this->session->set_flashdata('msg', array('danger', 'Data gagal disimpan2'));
				}
			
			}elseif($a == 'B'){
				$kd_bk = $getid;
				$kd_kat = $this->input->post('kd_kat');

				$jenjang = 'S1';
				$tempat = $this->input->post('tempat');
				$jml_mhs = $this->input->post('jml_mhs');
				$jml_sks = '0';
				$jml_dosen = '1';
				$jml_tatap_muka = '0';
				$jenis_kelas = 'A';
				$pertemuan_pm = '0';
				$nm_prodi = '-';
				$satuan = $this->input->post('satuan');

				/*$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
				$parameter = array('api_search'=>array($kd_kat));
				$kd_kat_remun = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);*/

				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
				$parameter = array('api_search'=>array($kd_kat));
				$kd_kat_serdos = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				if(!empty($kd_kat_serdos)){
					$status_pindah = 0;
					$kd_kat_serdos = $kd_kat_serdos;
				}else{
					$status_pindah = 2;
					$kd_kat_serdos = $kd_kat;
				}

				if($simpan){
					#SIMPAN DATA PENDIDIKAN (SIPKD)
					$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_penelitian';
					$parameter	= array('api_search' => array($kd_bk, $kd_kat_serdos, $c, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi, $satuan, $status_pindah));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$this->session->set_flashdata('msg', array('success', 'Data berhasil disimpan'));
					}else{

						$this->session->set_flashdata('msg', array('danger', 'Data gagal disimpan ke penelitian '));
					}
					
				}else{
					$this->session->set_flashdata('msg', array('danger', 'Data gagal disimpan'));
				}

			}if($a == 'D'){
				$kd_bk = $getid;
				$kd_kat = $this->input->post('kd_kat');
				#$nm_kegiatan = $c;
				$jenjang = 'S1';
				$tempat = $this->input->post('tempat');
				$jml_mhs = $this->input->post('jml_mhs');
				$jml_sks = '0';
				$jml_dosen = '1';
				$jml_tatap_muka = '0';
				$jenis_kelas = 'A'; /*SEMENTARA JENIS KELAS UNTUK DATA PENUNJANG DI DEFAULT=A*/
				$pertemuan_pm = '0';
				$nm_prodi = '-';
				$satuan = $this->input->post('satuan');

				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
				$parameter = array('api_search'=>array($kd_kat));
				$kd_kat_serdos = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				if(!empty($kd_kat_serdos)){
					$status_pindah = 0;
					$kd_kat_serdos = $kd_kat_serdos;
				}else{
					$status_pindah = 2;
					$kd_kat_serdos = $kd_kat;
				}
				//ketika tidak ada kode_kat_remun konversi dari kd_kat
				/*if(empty($kd_kat_remun)){
					$kd_kat_remun = $kd_kat;
				}*/

				if($simpan){
					#SIMPAN DATA PENDIDIKAN (SIPKD)
					$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_penunjang';
					$parameter	= array('api_search' => array($kd_bk, $kd_kat_serdos, $c, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi, $satuan, $status_pindah));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$this->session->set_flashdata('msg', array('success', 'Data berhasil disimpan'));
					}else{

						$this->session->set_flashdata('msg', array('danger', 'Data gagal disimpan'));
					}
					
				}else{
					$this->session->set_flashdata('msg', array('danger', 'Data gagal disimpan'));
				}
			
			}else if (/*$a == 'B' ||*/ $a == 'C' || $a == 'F'){
				$kd_bk = $getid;
				$bt_mulai = $this->input->post("bt_mulai");
				$bt_selesai = $this->input->post("bt_selesai");
				$judul_penelitian = $this->input->post("judul_penelitian");
				$sumber_dana = $this->input->post("sumber_dana");
				$jumlah_dana = $this->input->post("jumlah_dana");
				$kd_kat = $this->input->post("kd_kat");
				if ($a == 'B'){
					$status_peneliti = $this->input->post("status_peneliti");
					$laman_publikasi = $this->input->post("laman_publikasi");
					# simpan data penelitian 
					$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_penelitian';
					$parameter	= array('api_search' => array($kd_bk, $bt_mulai, $bt_selesai, $judul_penelitian, $sumber_dana, $jumlah_dana, $kd_kat, $status_peneliti, $laman_publikasi));
					#print_r($parameter['api_search']);
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					# SIMPAN PARTNER PENELITIAN
					$dosen = $this->input->post('dosen');
					$mhs = $this->input->post('mahasiswa');
					$lain = implode('<$>', $this->input->post('lain'));
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PENELITIAN', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
					
				}else if($a == 'C'){
					# simpan data pengabdian
					$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_pengabdian';
					$parameter	= array('api_search' => array($kd_bk, $bt_mulai, $bt_selesai, $judul_penelitian, $sumber_dana, $jumlah_dana, $kd_kat));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					# SIMPAN PARTNER PENGABDIAN
					$dosen = $this->input->post('dosen');
					$mhs = $this->input->post('mahasiswa');
					$lain = implode('<$>', $this->input->post('lain'));
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PENGABDIAN', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}else if($a == 'H'){
					# simpan data HAKI
					if($id != null){
						$api_url 	= URL_API_BKD.'bkd_remun/current_data_haki';
						$parameter  = array('api_search' => array($id));
						$ch=$data['curr_haki'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
											
						$api_url 	= URL_API_BKD.'bkd_remun/current_data_bkd';
						$parameter  = array('api_search' => array($ch->KD_BK, 'H'));
						$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					}
					$api_url 	= URL_API_BKD.'bkd_remun/get_all_dosen';
					$parameter  = array('api_search' => array());
					$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					#$this->output99=$this->s00_lib_output;
					$this->output99->output_display('dosen/form_haki',$data);
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PENGABDIAN', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}else{
					# DATA NARASUMBER
					$judul_acara = $this->input->post('judul_acara');
					$lokasi = $this->input->post('lokasi_acara');
					$kd_tingkat = $this->input->post('kd_tingkat');
					$laman_publikasi = $this->input->post('laman_publikasi');
					$status_peneliti = $this->input->post('status_peneliti');
					
					$dataNarasumber = array(
						'KD_BK' => $kd_bk,
						'BT_MULAI' => $bt_mulai,
						'BT_SELESAI' => $bt_selesai,
						'STATUS_PENELITI' => $status_peneliti,
						'LAMAN_PUBLIKASI' => $laman_publikasi,
						'LOKASI_ACARA' => $lokasi,
						'JUDUL_ACARA' => $judul_acara,
						'KD_TINGKAT' => $kd_tingkat,
					);
					$api_url 	= URL_API_BKD.'bkd_remun/kegiatan_akademik';
					$parameter  = array('api_kode'=>2001, 'api_subkode'=>1, 'api_search' => $dataNarasumber);
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					# SIMPAN PARTNER NARASUMBER
					$dosen = $this->input->post('dosen');
					$mhs = $this->input->post('mahasiswa');
					$lain = implode('<$>', $this->input->post('lain'));
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'NARASUMBER', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}
			}
		
			redirect('bkd/dosen/remun/data/'.$kd);
			}
		}
	}
	function simpan_data_publikasi(){
		$this->form_validation->set_rules('judul','<b>Judul Penelitian</b>','required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			redirect('bkd/dosen/remun/publikasi/tambah');
		}else{
			$a = $this->session->userdata('kd_dosen');
			$b = str_replace("'","''",$this->input->post('judul'));
			$c = $this->input->post('pada');
			$d = $this->input->post('tingkat');
			$e = $this->input->post('tanggal_pub');
			$f = $this->input->post('penerbit');
			$g = $this->input->post('akreditasi');
			$i = $this->session->userdata('ta');
			$j = $this->session->userdata('smt');
			$k = $this->session->userdata('kd_ta');
			$l = $this->session->userdata('kd_smt');
			$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_publikasi';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f, $g, $i, $j, $k, $l));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# get last id beban kerja
				$api_url 	= URL_API_BKD.'bkd_remun/get_current_data_tersimpan';
				$parameter	= array('api_search' => array('BKD.BKD_DATA_PUBLIKASI','KD_DP'));
				$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 

			$dosen = $this->input->post('dosen');
			$mhs = $this->input->post('mahasiswa');
			$lain = implode('<$>', $this->input->post('lain'));
			
			$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
			$data_partner = explode('<$>', $partner);
			for($x=0; $x<count($data_partner); $x++){
				if($data_partner[$x] !== ''){
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/partner_kinerja';
					$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PUBLIKASI', $a, $data_partner[$x]));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
			}

			redirect('bkd/dosen/bebankerja/publikasi');
		}
	}

	function simpan_data_haki(){
		$this->form_validation->set_rules('judul','<b>Judul Penelitian</b>','required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			redirect('bkd/dosen/remun/tambah/H');
		}else{
			#SIMPAN BKD
			$a = $kd;
			$b = $this->session->userdata("kd_dosen");
			$c = $this->input->post("jenis_kegiatan");
			$penugasan = $this->input->post("bukti_penugasan");
			$e = $this->input->post("sks1");
			$f = $this->input->post("lama").' '.$this->input->post("masa");
			$dokumen = $this->input->post("bukti_dokumen");
			$h = $this->input->post("sks2");
			$i = $this->session->userdata("ta");
			$j = $this->session->userdata("smt");
			$k = $this->input->post("rekomendasi");
			# data SIPKD 
			$l = $this->input->post("jml_jam");
			$m = $this->input->post("capaian");
			$q = $this->input->post("outcome");
			
			if(strpos($penugasan,':') > 0){
				$split = explode(':', $penugasan);
				$d = $split[1];
				$fp = 'surat:'.$split[0];
			}else{
				$d = $penugasan;
				$fp = '';
			}
			if(strpos($dokumen,':') > 0){
				$split2 = explode(':', $dokumen);
				$g = $split2[1];
				$fc = 'surat:'.$split2[0];
			}else{
				$g = $dokumen;
				$fc = '';
			}
			
			#$fp = $this->input->post("file_penugasan");
			#$fc = $this->input->post("file_capaian");
			$kd_ta = $this->session->userdata('kd_ta');
			$kd_smt = $this->session->userdata('kd_smt');

			$api_url 	= URL_API_BKD.'bkd_remun/simpan_beban_kerja';
			$parameter	= array('api_search' => array('H', $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $q, $fp, $fc, $kd_ta, $kd_smt));
			$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# get last id beban kerja
				$api_url 	= URL_API_BKD.'bkd_remun/get_current_data_tersimpan';
				$parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
				$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 

			##===##
			
			$kd_bk = $getid;
			$kd_dosen = $this->session->userdata('kd_dosen');
			$judul = str_replace("'","''",$this->input->post('judul'));
			$jenis_haki = $this->input->post('jenis_haki');
			$nomor_sk = $this->input->post('nomor_sk');
			$tingkat = $this->input->post('tingkat');
			$tanggal_sk = $this->input->post('tanggal_sk');
			$penerbit = $this->input->post('penerbit');
			$pemilik_hak = $this->input->post('pemilik_hak');
			$di=array(
				'KD_BK'=>$kd_bk,
				'KD_DOSEN'=>$kd_dosen,
				'JUDUL_HAKI'=>$judul,
				'KD_JENIS_HAKI'=>$jenis_haki,
				'TINGKAT'=>$tingkat,
				'NOMOR_SK'=>$nomor_sk,
				'TANGGAL_SK'=>$tanggal_sk,
				'PENERBIT_SK'=>$penerbit,
				'PEMILIK_HAK'=>$pemilik_hak
			);
			$api_url 	= URL_API_BKD.'bkd_remun/simpan_data_haki';
			$parameter  = array('api_search' => $di);
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# get last id beban kerja
				$api_url 	= URL_API_BKD.'bkd_remun/get_current_data_tersimpan';
				$parameter	= array('api_search' => array('BKD.HAKI','KD_HAKI'));
				$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 

			$dosen = $this->input->post('dosen');
			$mhs = $this->input->post('mahasiswa');
			$lain = implode('<$>', $this->input->post('lain'));
			
			$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
			$data_partner = explode('<$>', $partner);
			for($x=0; $x<count($data_partner); $x++){
				if($data_partner[$x] !== ''){
					$api_url 	= URL_API_BKD.'bkd_remun/partner_kinerja';
					$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'HAKI', $a, $data_partner[$x]));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
			}

			redirect('bkd/dosen/remun/tambah/H');
		}
	}
	function update_remun(){
		$this->form_validation->set_rules('jenis_kegiatan','<b>Jenis Kegiatan</b>','required|xss_clean');
		$kd = $this->input->post("kd_jbk");
		$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('smt');
		$ta = $this->session->userdata('ta');
		
		if ($this->form_validation->run() == FALSE){
			$this->direct_remun($kd);
		}
		else{
			$kode = $kd;
			$kd_bk = $this->input->post("kd_bk");
			$a = $this->input->post("jenis_kegiatan");
			$penugasan = $this->input->post("bukti_penugasan");
			$c = $this->input->post("sks1");
			$d = $this->input->post("lama").' '.$this->input->post("masa");
			$dokumen = $this->input->post("bukti_dokumen");
			$f = $this->input->post("sks2");

			$i = $this->session->userdata("ta");
			$j = $this->session->userdata("smt");
			$k = $this->input->post("rekomendasi");
			# data SIPKD 
			$l = $this->input->post("jml_jam");
			$m = $this->input->post("capaian");
			$q = $this->input->post("outcome");
			if(strpos($penugasan,':') > 0){
				$split = explode(':', $penugasan);
				$b = $split[1];
				$fp = 'surat:'.$split[0];
			}else{
				$b = $penugasan;
				$fp = '';
			}
			if(strpos($dokumen,':') > 0){
				$split2 = explode(':', $dokumen);
				$e = $split2[1];
				$fc = 'surat:'.$split2[0];
			}else{
				$e = $dokumen;
				$fc = '';
			}

			$api_url 	= URL_API_BKD.'bkd_remun/update_remun';
			$parameter	= array('api_search' => array($kd_bk, $a, $b, $c, $d, $e, $f, $k, $l, $m, $q, $kode, $kd_dosen, $smt, $ta, $fp, $fc));
			$update = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			#print_r($parameter); die();
			if($kode == 'A'){
				# data SIPKD Pendidikan
				$kd_kat = $this->input->post('kd_kat');
				$nm_kegiatan = $a;
				$jenjang = $this->input->post('jenjang');
				$tempat = $this->input->post('tempat');
				$jml_mhs = $this->input->post('jml_mhs');

				$jml_sks = $this->input->post('jml_sks');
				$jml_dosen = $this->input->post('jml_dosen');
				$jml_tatap_muka = $this->input->post('jml_tatap_muka');
				$jenis_kelas = $this->input->post('jenis_kelas');
				$pertemuan_pm = $this->input->post('pertemuan_pm');
				$nm_prodi = $this->input->post('nama_prodi');
				$satuan = $this->input->post('satuan');

				/*$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
				$parameter = array('api_search'=>array($kd_kat));
				$kd_kat_remun = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);*/

				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
					$parameter = array('api_search'=>array($kd_kat));
					$kd_kat_serdos = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

					if(!empty($kd_kat_serdos)){
						$status_pindah = 0;
						$kd_kat_serdos = $kd_kat_serdos;
					}else{
						$status_pindah = 2;
						$kd_kat_serdos = $kd_kat;
					}

				#SIMPAN DATA PENDIDIKAN (SIPKD)
				if($update){

					$api_url 	= URL_API_BKD.'bkd_remun/update_data_pendidikan';
					$parameter	= array('api_search' => array($kd_bk, $kd_kat_serdos, $nm_kegiatan, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi, $satuan, $status_pindah));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$this->session->set_flashdata('msg_update', array('success', 'Data berhasil diperbaharui'));
					}else{
						$this->session->set_flashdata('msg_update', array('danger', 'Data gagal diperbaharui'));
					}
				}else{
					$this->session->set_flashdata('msg_update', array('danger', 'Data gagal diperbaharui'));
				}
			}elseif($kode == 'D'){
				# data SIPKD Pendidikan
				$kd_kat = $this->input->post('kd_kat');
				$nm_kegiatan = $a;

				$jenjang = 'S1';
				$tempat = $this->input->post('tempat');
				$jml_mhs = $this->input->post('jml_mhs');
				$jml_sks = '0';
				$jml_dosen = '1';
				$jml_tatap_muka = '0';
				$jenis_kelas = 'A';
				$pertemuan_pm = '0';
				$nm_prodi = '-';
				$satuan = $this->input->post('satuan');

				/*$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
				$parameter = array('api_search'=>array($kd_kat));
				$kd_kat_remun = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);*/

				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
					$parameter = array('api_search'=>array($kd_kat));
					$kd_kat_serdos = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

					if(!empty($kd_kat_serdos)){
						$status_pindah = 0;
						$kd_kat_serdos = $kd_kat_serdos;
					}else{
						$status_pindah = 2;
						$kd_kat_serdos = $kd_kat;
					}

				#SIMPAN DATA PENDIDIKAN (SIPKD)
				if($update){

					$api_url 	= URL_API_BKD.'bkd_remun/update_data_penunjang';
					$parameter	= array('api_search' => array($kd_bk, $kd_kat_serdos, $nm_kegiatan, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi, $satuan, $status_pindah));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$this->session->set_flashdata('msg_update', array('success', 'Data berhasil diperbaharui'));
					}else{
						$this->session->set_flashdata('msg_update', array('danger', 'Data gagal diperbaharui'));
					}
				}else{
					$this->session->set_flashdata('msg_update', array('danger', 'Data gagal diperbaharui'));
				}
			}elseif($kode == 'B'){
				# data SIPKD Pendidikan
				$kd_kat = $this->input->post('kd_kat');
				$nm_kegiatan = $a;

				$jenjang = 'S1';
				$tempat = $this->input->post('tempat');
				$jml_mhs = $this->input->post('jml_mhs');
				$jml_sks = '0';
				$jml_dosen = '1';
				$jml_tatap_muka = '0';
				$jenis_kelas = 'A';
				$pertemuan_pm = '0';
				$nm_prodi = '-';
				$satuan = $this->input->post('satuan');

				/*$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
				$parameter = array('api_search'=>array($kd_kat));
				$kd_kat_remun = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);*/

				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_serdos';
					$parameter = array('api_search'=>array($kd_kat));
					$kd_kat_serdos = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

					if(!empty($kd_kat_serdos)){
						$status_pindah = 0;
						$kd_kat_serdos = $kd_kat_serdos;
					}else{
						$status_pindah = 2;
						$kd_kat_serdos = $kd_kat;
					}

				#SIMPAN DATA PENDIDIKAN (SIPKD)
				if($update){

					$api_url 	= URL_API_BKD.'bkd_remun/update_data_penilai_penelitian';
					$parameter	= array('api_search' => array($kd_bk, $kd_kat_serdos, $nm_kegiatan, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi, $satuan, $status_pindah));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$this->session->set_flashdata('msg_update', array('success', 'Data berhasil diperbaharui'));
					}else{
						$this->session->set_flashdata('msg_update', array('danger', 'Data gagal diperbaharui'));
					}
				}else{
					$this->session->set_flashdata('msg_update', array('danger', 'Data gagal diperbaharui'));
				}
			}elseif($kode == 'C' || $kode == 'F'){
				$bt_mulai = $this->input->post("bt_mulai");
				$bt_selesai = $this->input->post("bt_selesai");
				$judul_penelitian = $this->input->post("judul_penelitian");
				$dosen_partner = $this->input->post("dosen");							
				# SPESIAL OPERATION
				$sumber_dana = $this->input->post("sumber_dana");
				$jumlah_dana = $this->input->post("jumlah_dana");
				$kd_kat = $this->input->post("kd_kat");
				if ($kode == 'B'){

					$status_peneliti = $this->input->post("status_peneliti");
					$laman_publikasi = $this->input->post("laman_publikasi");
					# SIMPAN DATA PENELITIAN
					$api_url 	= URL_API_BKD.'bkd_remun/update_data_penelitian';
					$parameter	= array('api_search' => array($kd_bk, $bt_mulai, $bt_selesai, $judul_penelitian, $sumber_dana, $jumlah_dana, $kd_kat, $status_peneliti, $laman_publikasi));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				}else if ($kode == 'F'){
					$judul_acara = str_replace("'","''",$this->input->post('judul_acara'));
					$kd_tingkat = $this->input->post('kd_tingkat');
					$status_peneliti = $this->input->post("status_peneliti");
					$laman_publikasi = $this->input->post("laman_publikasi");
					$lokasi_acara = $this->input->post("lokasi_acara");
					
					$dataNarasumber = array($kd_bk, $bt_mulai, $bt_selesai, $judul_acara, $kd_tingkat, $status_peneliti, $laman_publikasi, $lokasi_acara);
					$api_url 	= URL_API_BKD.'bkd_remun/kegiatan_akademik';
					$parameter  = array('api_kode'=>2001, 'api_subkode'=>2, 'api_search' => $dataNarasumber);
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					#print_r($parameter);

				}else{
					# simpan data pengabdian
					$api_url 	= URL_API_BKD.'bkd_remun/update_data_pengabdian';
					$parameter	= array('api_search' => array($kd_bk, $bt_mulai, $bt_selesai, $judul_penelitian, $sumber_dana, $jumlah_dana, $kd_kat));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
			}
		
			$this->direct_remun($kode);
		}
	}
	function direct_remun($kode){
		if ($kode == "A"){
			redirect ('bkd/dosen/remun/data/A');
		}else if ($kode == "B"){
			redirect ('bkd/dosen/remun/data/B');
		}else if ($kode == "C"){
			redirect ('bkd/dosen/remun/data/C');
		}else if ($kode == "D"){
			redirect ('bkd/dosen/remun/data/D');
		}else if ($kode == "F"){
			redirect ('bkd/dosen/remun/data/F');
		}else if ($kode == "H"){
			redirect ('bkd/dosen/remun/data/F');
		}
	}
	function update_data_publikasi(){
		$this->form_validation->set_rules('judul','<b>Judul Penelitian</b>','required|xss_clean');
		$this->form_validation->set_rules('pada','<b>Dipublikasikan pada</b>','required|xss_clean');
		$this->form_validation->set_rules('tingkat','<b>Tingkat</b>','required|xss_clean');
		$this->form_validation->set_rules('tanggal_pub','<b>Tanggal publikasi</b>','required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			redirect('bkd/dosen/remun/publikasi/tambah');
		}else{
			$a = $this->input->post('kd_dp');
			$b = str_replace("'","''",$this->input->post('judul'));
			$c = $this->input->post('pada');
			$d = $this->input->post('tingkat');
			$e = $this->input->post('tanggal_pub');
			$f = $this->input->post('penerbit');
			$g = $this->input->post('akreditasi');
			$api_url 	= URL_API_BKD.'bkd_remun/update_data_publikasi';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f, $g));
			#print_r($parameter); die();
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			redirect('bkd/dosen/remun/publikasi');
		}
	}
	function update_data_haki(){
		
		$kd_haki = $this->input->post('kd_haki');
		$judul = str_replace("'","''",$this->input->post('judul'));
		$jenis_haki = $this->input->post('jenis_haki');
		$nomor_sk = $this->input->post('nomor_sk');
		$tingkat = $this->input->post('tingkat');
		$tanggal_sk = $this->input->post('tanggal_sk');
		$penerbit = $this->input->post('penerbit');
		$pemilik_hak = $this->input->post('pemilik_hak');
		
		$kd_jbk = 'H';
		$kd_bk = $this->input->post("kd_bk");
		$jenis_kegiatan = $this->input->post("jenis_kegiatan");
		$penugasan = $this->input->post("bukti_penugasan");
		$sks_penugasan = $this->input->post("sks1");
		$masa_penugasan = $this->input->post("lama").' '.$this->input->post("masa");
		$dokumen = $this->input->post("bukti_dokumen");
		$sks_bkt = $this->input->post("sks2");

		$i = $this->session->userdata("ta");
		$j = $this->session->userdata("smt");
		
			
			$kd_dosen = $this->session->userdata("kd_dosen");
		$rekomendasi = $this->input->post("rekomendasi");
		# data SIPKD 
		$jml_jam = $this->input->post("jml_jam");
		$capaian = $this->input->post("capaian");
		$outcome = $this->input->post("outcome");
		if(strpos($penugasan,':') > 0){
			$split = explode(':', $penugasan);
			$bkt_penugasan = $split[1];
			$fp = 'surat:'.$split[0];
		}else{
			$bkt_penugasan = $penugasan;
			$fp = '';
		}
		if(strpos($dokumen,':') > 0){
			$split2 = explode(':', $dokumen);
			$bkt_dokumen = $split2[1];
			$fc = 'surat:'.$split2[0];
		}else{
			$bkt_dokumen = $dokumen;
			$fc = '';
		}
		
		$dbk=array(
			'KD_JBK'=>'H',
			'JENIS_KEGIATAN'=>$jenis_kegiatan,
			'BKT_PENUGASAN'=>$bkt_penugasan,
			'SKS_PENUGASAN'=>$sks_penugasan,
			'MASA_PENUGASAN'=>$masa_penugasan,
			'BKT_DOKUMEN'=>$bkt_dokumen,
			'SKS_BKT'=>$sks_bkt,
			'THN_AKADEMIK'=>$i,
			'SEMESTER'=>$j,
			'REKOMENDASI'=>$rekomendasi,
			'JML_JAM'=>$jml_jam,
			'CAPAIAN'=>$capaian,
			'OUTCOME'=>$outcome,
			'KD_BK'=>$kd_bk
			);
		$api_url 	= URL_API_BKD.'bkd_remun/update_bkd';
		$parameter	= array('api_search' => array($kd_bk, $jenis_kegiatan, $bkt_penugasan, $sks_penugasan, $masa_penugasan, 
		$bkt_dokumen, $sks_bkt, $rekomendasi, $jml_jam, $capaian, $outcome, $kd_bk, $kd_dosen, $i, $j, $fp, $fc));
		$data=$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//print_r($data); die();
		
	
		#UPDATE DATA HAKI#
		$di=array(
			'KD_HAKI'=>$kd_haki,
			'JUDUL_HAKI'=>$judul,
			'KD_JENIS_HAKI'=>$jenis_haki,
			'TINGKAT'=>$tingkat,
			'NOMOR_SK'=>$nomor_sk,
			'TANGGAL_SK'=>$tanggal_sk,
			'PENERBIT_SK'=>$penerbit,
			'PEMILIK_HAK'=>$pemilik_hak
		);
		$api_url 	= URL_API_BKD.'bkd_remun/update_data_haki';
		$parameter  = array('api_search' => $di);
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);			
		#print_r($parameter); die();
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		redirect('bkd/dosen/bebankerja/data/H');
	}
	function edit(){
		/* set parameters */
		$kode = $this->security->xss_clean($this->uri->segment(6));
		$data['kode'] = $this->security->xss_clean($this->uri->segment(5));

		$data['kd_dosen'] = $this->session->userdata('kd_dosen');
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');

		/* load data form */
		$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
		$parameter  = array('api_search' => array($data['kode'],$data['kd_dosen'], $data['ta'], $data['smt']));
		$data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$i =0;
		foreach ($data['data_remun'] as $key) {
			$kd_kat = $key->KD_KAT;
			$status_pindah = $key->STATUS_PINDAH;
			if($status_pindah == 2){
				$kd_kat_remun = $kd_kat;
			}elseif($status_pindah == 0){
				$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_remun2';
				$parameter  = array('api_search' => array($kd_kat));
				$kd_kat_remun = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
				/*$kd_kat_remun = $kode['KD_KAT_REMUN'];*/
			}
			$data['data_remun'][$i]->KD_KAT_REMUN = $kd_kat_remun;
				$i++;
			$api_url 	= URL_API_BKD.'bkd_remun/get_jenis_nilai_kat_remun';
			$parameter  = array('api_search' => array($kd_kat_remun));
			$data['nilai_kat'][$kd_kat_remun] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		}

		$api_url 	= URL_API_BKD.'bkd_remun/current_data_bkd';
		$parameter  = array('api_search' => array($kode, $data['kode']));
		$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 
		# print_r($data['current_data']); echo $data['kode'];
		
		# cek jenis remun
		if($data['kode'] == 'F'){
			$api_url 	= URL_API_BKD.'bkd_remun/tingkat';
			$parameter  = array('api_kode'=>1000, 'api_subkode'=>1, 'api_search' => array());
			$data['kategori'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);		
		}else if($data['kode'] == 'H'){				
			$data['jenis_haki'] = $this->s00_lib_api->get_api_jsob(URL_API_BKD.'bkd_beban_kerja/get_jenis_haki','POST',array());	
		}else{
			$api_url 	= URL_API_BKD.'bkd_remun/get_kategori';
			$parameter  = array('api_search' => array($data['kode']));
			$data['kategori'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		/* 		echo"<pre>";
		print_r($data['data_beban']);
			print_r($data['current_data']);
			echo"</pre>"; */
		}
		$data['name_prodi'] = $this->get_prodi();
		$data['list_kategori'] = $this->daftar_kategori_remun($data['kode']);
		/*echo "<pre>";
		print_r($data['list_kategori']);
		echo "<pre>";
		die();*/
		$this->output99->output_display('dosen/form_tambah_remun_pendidikan',$data);
	}
	function hapus_remun($id){
		$kode = $this->security->xss_clean($this->uri->segment(5));
		$id = $this->security->xss_clean($this->uri->segment(6));
		$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('smt');
		$ta = $this->session->userdata('ta');
		$api_url 	= URL_API_BKD.'bkd_remun/hapus_remun';
		$parameter	= array('api_search' => array($id, $kode, $kd_dosen, $smt, $ta));
		$hapus = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		if($hapus){
			$this->session->set_flashdata('msg', array('success', 'Data berhasil dihapus'));
			/*redirect('bkd/dosen/remun/data/'.$kd);*/
		}else{
			/*$this->data($kode);*/
			$this->session->set_flashdata('msg', array('danger', 'Data gagal dihapus'));
		}
		redirect('bkd/dosen/remun/data/'.$kode);
	}

	function upload_dokumen(){
		$kd = $this->input->post('kd_bk');
		$aksi = $this->input->post('aksi');
		$kode = $this->input->post('kode');
		if($aksi !==''){
			$_aksi = explode('-',$aksi);
			$field = $_aksi[0];
			$key = $_aksi[1];
		}
		# DO SOMETHING
		if($field == 'penugasan'){
			switch($key){
				case "isi":{
					#UPDATE ISI BKT_PENUGASAN
					$isi = $this->input->post('bkt_penugasan');
					$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
					$parameter  = array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($kd, $isi, ''));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}break;
				case "cari":{
					# UPDATE ISI BKT PENUGASAN DENGAN MENCARI FILE
					$isi = $this->input->post('surat');
					$data = explode(':', $isi);
					$bkt = $data[1]; $url = 'surat:'.$data[0];
					$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
					$parameter  = array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($kd, $bkt, $url));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);					
				}break;
				case "upload":{
					# UPLOAD DOKUMEN PENUGASAN
					$bkt = $this->input->post('bukti');
					$file = $_FILES['file_upload']['size'];
					if ($file != 0){
						$config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|pdf|PDF';
						$config['max_size']    = '4096';
						$config['encrypt_name'] = TRUE;
						$config['overwrite'] = FALSE;
						$config['upload_path'] = './uploads/DataBkd/FilePenugasan/';
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('file_upload')){
							$err = $this->upload->display_errors();
							$data['error'] = str_replace(',','-', strip_tags($err));
							redirect('bkd/dosen/remun/data/'.$kode.'/'.$kd.'/penugasan-upload/'.$data['error']);
						}
						else{
							$data['success'] = $this->upload->data();
							$filename = $data['success']['file_name'];
							$ext = end((explode(".", $_FILES['file_upload']['name'])));
							$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
							$parameter  = array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($kd, $bkt, $ext));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
							
							# UPLOAD FILE BLOB
							$file   = $_FILES['file_upload']['tmp_name'];
							$upload = $this->blob->insertBlob('BKD.BKD_DOC_KINERJA', 'BLOB_PENUGASAN', array('BLOB_PENUGASAN' => base64_encode(file_get_contents($file)) ), array('KD_BK' => $kd));
							$locationFile = './uploads/DataBkd/FilePenugasan/'.$file_name;
							unlink($locationFile);
							
						}
					}else{
						echo "<script>alert('Anda belum memilih file untuk diupload.')</script>";
					}
				}break;
				default :{
					
				}break;
			}
		}else{
			switch($key){
				case "isi":{
					#UPDATE ISI BKT_KINERJA
					$isi = $this->input->post('bkt_penugasan');
					$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
					$parameter  = array('api_kode' => 1000, 'api_subkode' => 4, 'api_search' => array($kd, $isi, ''));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}break;
				case "cari":{
					# UPDATE KINERJA
					$isi = $this->input->post('surat');
					$data = explode(':', $isi);
					$bkt = $data[1]; $url = 'surat:'.$data[0];
					$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
					$parameter  = array('api_kode' => 1000, 'api_subkode' => 4, 'api_search' => array($kd, $bkt, $url));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);					
				}break;
				case "upload":{
					# UPLOAD DOKUMEN KINERJA
					$bkt = $this->input->post('bukti');
					$file = $_FILES['file_upload']['size'];
					if ($file != 0){
						$config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|pdf|PDF';
						$config['max_size']    = '4096';
						$config['encrypt_name'] = TRUE;
						$config['overwrite'] = FALSE;
						$config['upload_path'] = './uploads/DataBkd/FileKinerja/';
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('file_upload')){
							$err = $this->upload->display_errors();
							$data['error'] = str_replace(',','-', strip_tags($err));
							redirect('bkd/dosen/remun/data/'.$kode.'/'.$kd.'/kinerja-upload/'.$data['error']);
						}
						else{
							$data['success'] = $this->upload->data();
							$filename = $data['success']['file_name'];
							$ext = end((explode(".", $_FILES['file_upload']['name'])));
							$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
							$parameter  = array('api_kode' => 1000, 'api_subkode' => 4, 'api_search' => array($kd, $bkt, $ext));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						
							# UPLOAD FILE BLOB
							$file   = $_FILES['file_upload']['tmp_name'];
							$upload = $this->blob->insertBlob('BKD.BKD_DOC_KINERJA', 'BLOB_CAPAIAN', array('BLOB_CAPAIAN' => base64_encode(file_get_contents($file)) ), array('KD_BK' => $kd));
							$locationFile = './uploads/DataBkd/FileKinerja/'.$file_name;
							unlink($locationFile);
						}
					}else{
						echo "<script>alert('Anda belum memilih file untuk diupload.')</script>";
					}				
				}break;
				default :{
					
				}break;
			}		
		}
		redirect('bkd/dosen/remun/data/'.$kode.'/'.$kd);
	}
	function upload_dokumen_prof(){
		$kd = $this->input->post('kd_bk');
		$aksi = $this->input->post('aksi');
		$kode = $this->input->post('kode');
		if($aksi !==''){
			$_aksi = explode('-',$aksi);
			$field = $_aksi[0];
			$key = $_aksi[1];
		}
		# DO SOMETHING
		if($field == 'penugasan'){
			switch($key){
				case "isi":{
					#UPDATE ISI BKT_PENUGASAN
					$isi = $this->input->post('bkt_penugasan');
					$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
					$parameter  = array('api_kode' => 1001, 'api_subkode' => 1, 'api_search' => array($kd, $isi));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}break;
				case "cari":{
					# UPDATE ISI BKT PENUGASAN DENGAN MENCARI FILE
					$isi = $this->input->post('surat');
					$data = explode(':', $isi);
					$bkt = $data[1]; $url = 'surat:'.$data[0];
					$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
					$parameter  = array('api_kode' => 1001, 'api_subkode' => 3, 'api_search' => array($kd, $bkt, $url));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);					
				}break;
				case "upload":{
					# UPLOAD DOKUMEN PENUGASAN
					$bkt = $this->input->post('bukti');
					$file = $_FILES['file_upload']['size'];
					if ($file != 0){
						$config['allowed_types'] = 'jpg|JPG|jpeg|JPEG|pdf|PDF';
						$config['max_size']    = '4096';
						$config['encrypt_name'] = TRUE;
						$config['overwrite'] = FALSE;
						$config['upload_path'] = './uploads/DataBkd/FilePenugasan/';
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('file_upload')){
							$err = $this->upload->display_errors();
							$data['error'] = str_replace(',','-', strip_tags($err));
							redirect('bkd/dosen/bebankerja/profesor/detail/'.$kd.'/penugasan-upload/'.$data['error']);
						}
						else{
							$data['success'] = $this->upload->data();
							$filename = $data['success']['file_name'];
							$url = base_url().'uploads/DataBkd/FilePenugasan/'.$filename;
							$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
							$parameter  = array('api_kode' => 1001, 'api_subkode' => 3, 'api_search' => array($kd, $bkt, $url));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}else{
						echo "<script>alert('Anda belum memilih file untuk diupload.')</script>";
					}
				}break;
				default :{
					
				}break;
			}
		}else{
			switch($key){
				case "isi":{
					#UPDATE ISI BKT_KINERJA
					$isi = $this->input->post('bkt_penugasan');
					$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
					$parameter  = array('api_kode' => 1001, 'api_subkode' => 2, 'api_search' => array($kd, $isi));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}break;
				case "cari":{
					$isi = $this->input->post('surat');
					$data = explode(':', $isi);
					$bkt = $data[1]; $url = 'surat:'.$data[0];
					$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
					$parameter  = array('api_kode' => 1001, 'api_subkode' => 4, 'api_search' => array($kd, $bkt, $url));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);					
				
				}break;
				case "upload":{
					# UPLOAD DOKUMEN KINERJA
					$bkt = $this->input->post('bukti');
					$file = $_FILES['file_upload']['size'];
					if ($file != 0){
						$config['allowed_types'] = 'jpg|JPG|jpeg|JEPG|pdf|PDF';
						$config['max_size']    = '4096';
						$config['encrypt_name'] = TRUE;
						$config['overwrite'] = FALSE;
						$config['upload_path'] = './uploads/DataBkd/FileKinerja/';
						$this->load->library('upload', $config);
						if (!$this->upload->do_upload('file_upload')){
							$err = $this->upload->display_errors();
							$data['error'] = str_replace(',','-', strip_tags($err));
							redirect('bkd/dosen/bebankerja/profesor/detail/'.$kd.'/kinerja-upload/'.$data['error']);
						}
						else{
							$data['success'] = $this->upload->data();
							$filename = $data['success']['file_name'];
							$url = base_url().'uploads/DataBkd/FileKinerja/'.$filename;
							$api_url 	= URL_API_BKD.'bkd_remun/update_dokumen';
							$parameter  = array('api_kode' => 1001, 'api_subkode' => 4, 'api_search' => array($kd, $bkt, $url));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}else{
						echo "<script>alert('Anda belum memilih file untuk diupload.')</script>";
					}				
				}break;
				default :{
					
				}break;
			}		
		}
		redirect('bkd/dosen/bebankerja/profesor/detail/'.$kd);	
	}
	function profesor($param = '', $show = FALSE, $action = ''){
		
		$kd = $this->session->userdata("kd_dosen");
		$data['ta'] = $this->session->userdata("ta");
		$data['smt'] = $this->session->userdata("smt");
		$thn_prof = $this->session->userdata("thn_prof");
		$data['tahun'] = $this->security->xss_clean($this->input->post("thn"));
		$data['kode'] = 'E';
		if($thn_prof == 0) $tp = date('Y'); else $tp = $thn_prof;
		$data['title'] = "Kewajiban Khusus Profesor";
		if ($this->session->userdata("jenis_dosen") == "PR" || $this->session->userdata("jenis_dosen") == "PT"){
			// data beban
			$api_url 	= URL_API_BKD.'bkd_remun/history_data_bebankerja_prof';
			$parameter  = array('api_search' => array($kd, $data['ta'], $data['smt']));
			$data['data_beban_prof'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			if($param == 'detail'){
				$kd = $this->uri->segment(6);
				if ($action !== ''){
					switch ($action){
						case 'penugasan-isi': $data['view'] = 'dosen/isi_dokumen_remun'; break;
						case 'penugasan-cari': {
								$data['view'] = 'dosen/cari_dokumen_remun';
							}break;
						case 'penugasan-upload': $data['view'] = 'dosen/upload_dokumen_remun'; break;
						case 'kinerja-isi': $data['view'] = 'dosen/isi_dokumen_remun'; break;
						case 'kinerja-cari': {
								$data['view'] = 'dosen/cari_dokumen_remun'; 
						}break;
						case 'kinerja-upload': $data['view'] = 'dosen/upload_dokumen_remun'; break;
						default : $data['view'] = 'dosen/isi_dokumen_remun'; break;
					}
					$api_url 	= URL_API_BKD.'bkd_remun/current_bebankerja_prof';
					$parameter  = array('api_search' => array($kd));
					$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
				$api_url 	= URL_API_BKD.'bkd_remun/current_bebankerja_prof';
				$parameter  = array('api_search' => array($kd));
				$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}
			//ini belum dibuat form yg khusus remun
			$this->output99->output_display('dosen/daftar_beban_kerja_prof',$data);
		}else{//ini belum dibuat form yg khusus remun
			$this->output99->output_display('dosen/beban_profesor_message',$data);
		}
		#echo $this->session->userdata("jenis_dosen");
	}
	function get_surat(){
		if(ISSET($_GET['q'])){
			$q = 'surat';
			$q = strtoupper(str_replace("'", "''", $_GET['q']));
			//$tot = count(str_split($_GET['q']));
			//if($tot > 1){
				$url	= 'tnde_public/'; $jenis_surat = '';
				$parameter = array('api_kode' => 90009, 'api_subkode' => 2, 'api_search' => array($q, $jenis_surat, 2));
				//print_r $parameter;
				$data = $this->mdl_bkd->get_api_surat($url.'tnde_surat_keluar/get_surat_keluar_all', 'json', 'POST', $parameter);				
				$new_data = array();
				foreach ($data as $dt){
					$new_data[] = array('id' =>	$dt['ID_SURAT_KELUAR'].':'.$dt['PERIHAL'], 'name'	=>	$dt['PERIHAL'].' ('.$dt['NO_SURAT'].')');
				}
				echo json_encode($new_data);
			//}
		}
	}
	function direct_hybrid($kode){
		if ($kode == "A"){
			redirect ('bkd/dosen/bebankerja/tambah/A');
		}else if ($kode == "B"){
			redirect ('bkd/dosen/bebankerja/tambah/B');
		}else if ($kode == "C"){
			redirect ('bkd/dosen/bebankerja/tambah/C');
		}else if ($kode == "D"){
			redirect ('bkd/dosen/bebankerja/tambah/D');
		}else if ($kode == "F"){
			redirect ('bkd/dosen/bebankerja/tambah/F');
		}else if ($kode == "H"){
			redirect ('bkd/dosen/bebankerja/tambah/H');
		}
	}
	function search(){
		 //$kode=$this->input->post('kode',TRUE);

	  //$keyword2 = trim(strip_tags($_POST['query']));
	  /*$keyword2 = "ME";
	  $kat = "A";

	  $api_url 	= URL_API_BKD.'bkd_remun/kategori_bkd_remun2';
	  $parameter  = array('api_search' => array($keyword2, $kat));
	  $data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
	  $json_array = array();
		foreach($data_remun as $row){
			 $json_array[]=$row->NM_KAT;
		}
     
     echo json_encode($json_array);*/
	  
	}
	function search2(){
		$keyword = $this->uri->segment(5);
		$keyword2 = preg_replace('/%20/', '', $keyword);
		$kategori = "A";
		/*print_r($keyword);*/
		//api kategori bkd
		$api_url 	= URL_API_BKD.'bkd_remun/kategori_bkd_remun2';
		$parameter  = array('api_search' => array($keyword2, $kategori));
		$data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//pengambilan data dan pengrimimannya ke view
		foreach($data['data_remun'] as $row)
		{
			$arr['query'] = $keyword;
			$arr['suggestions'][] = array(
				'value'	=>$row->NM_KAT,
				'nilai'=>$row->KD_KAT,
				'satuan' =>$row->SATUAN,
				'nilai_kat' => $row->NILAI_KAT,
				'set_masa_tugas' =>1,
				'set_rincian_masa' =>'Semester',
			);
		}
		// minimal PHP 5.2
		echo json_encode($arr);
	}
	function search3(){
		$keyword = $this->uri->segment(5);
		$keyword2 = preg_replace('/%20/', '', $keyword);
		$kategori = "B";
		/*print_r($keyword);*/
		//api kategori bkd
		$api_url 	= URL_API_BKD.'bkd_remun/kategori_bkd_remun2';
		$parameter  = array('api_search' => array($keyword2, $kategori));
		$data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//pengambilan data dan pengrimimannya ke view
		//print_r($data);
		foreach($data['data_remun'] as $row)
		{
			$arr['query'] = $keyword;
			$arr['suggestions'][] = array(
				'value'	=>$row->NM_KAT,
				'nilai'=>$row->KD_KAT,
			);
		}
		// minimal PHP 5.2
		echo json_encode($arr);
	}
	function search4(){
		$keyword = $this->uri->segment(5);
		$keyword2 = preg_replace('/%20/', '', $keyword);
		$kategori = "D";
		/*print_r($keyword);*/
		//api kategori bkd
		$api_url 	= URL_API_BKD.'bkd_remun/kategori_bkd_remun2';
		$parameter  = array('api_search' => array($keyword2, $kategori));
		$data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//pengambilan data dan pengrimimannya ke view
		//print_r($data);
		foreach($data['data_remun'] as $row)
		{
			$arr['query'] = $keyword;
			$arr['suggestions'][] = array(
				'value'	=>$row->NM_KAT,
				'nilai'=>$row->KD_KAT,
			);
		}
		// minimal PHP 5.2
		echo json_encode($arr);
	}

	function move(){
		$kd_bk = $this->uri->segment(6);
		$api_url 	= URL_API_BKD.'bkd_remun/update_status_pakai';
		$parameter  = array('api_search'=>array($kd_bk));
		$jadwal = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if($jadwal){
			$this->session->set_flashdata('msg_move', array('success', 'Data berhasil dipindah ke BKD Sertifikasi'));
		}else{
			$this->session->set_flashdata('msg_move', array('success', 'Data gagal dipindah ke BKD Sertifikasi'));
		}
		//print_r($kd_bk);
		redirect('bkd/dosen/remun/data/A/');
	}
	function test(){
		//get data fakultas
		$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
		$data= $this->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);
		
		$KD_FAK = $data[0]['KD_FAK'];
		//$KD_FAK = "06";
		$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($KD_FAK));
		$data= $this->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	function get_prodi_x(){
		$ip = $this->uri->segment(5);
		$input = $ip;

		$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
		$data['fakultas']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);
		$arr = array();
		foreach ($data['fakultas'] as $d) {
			$KD_FAK = $d['KD_FAK'];
			$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($KD_FAK));
			$data['prodi']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);
			foreach ($data['prodi'] as $key) {
				$source  = strtoupper($key['NM_PRODI']);
				$keyword = strtoupper($input);
				if(stristr($source, $keyword)){
					$arr['query'] = $input;
					$arr['suggestions'][] = array(
						'value'	=>$key['NM_PRODI'],
					);
				}
			}
		}

		echo json_encode($arr);
	}

	function get_club(){
		$ip = $this->uri->segment(5);
		$input = preg_replace('/%20/', '', $ip);
		$data = array('barcelona', 'mancester united', 'real madrid', 'juventus', 'chelsea');
		$result = array_filter($data, function ($item) use ($input) {
	    if (stripos($item, $input) !== false) {
	        return true;
	    }
		    return false;
		});

		var_dump($result);
	}

	function get_prodi_old(){
		$ip = $this->uri->segment(5);
		$input = preg_replace('/%20/', ' ', $ip);

		$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
		$data['fakultas']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);
		$arr = array();
		foreach ($data['fakultas'] as $d) {
			$KD_FAK = $d['KD_FAK'];
			$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($KD_FAK));
			$data['prodi']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);
			foreach ($data['prodi'] as $key) {
				//$items[] = $key['NM_PRODI'];
				$source  = strtoupper($key['NM_PRODI']);
				$keyword = strtoupper($input);
				if(stristr($source, $keyword)){
					$arr['query'] = $input;
					$arr['suggestions'][] = array(
						'value'	=>$key['NM_PRODI'],
					);
				}


			}
		}

		echo json_encode($arr);

	}
	function get_prodi(){
		$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
		$data['fakultas']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);
		$arr = array();
		foreach ($data['fakultas'] as $d) {
			$KD_FAK = $d['KD_FAK'];
			$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($KD_FAK));
			$data['prodi']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);
			foreach ($data['prodi'] as $key) {
				$nama_prodi = $key['NM_PRODI'];
				$dat[] = $nama_prodi;
				
			}
		}
		
		return json_encode($dat);

	}

		/* fungsi history data */
	
	function history($jenis, $show = ''){
		/*set parameter */
		$jenis = $this->security->xss_clean($this->uri->segment(5));
		$show = $this->security->xss_clean($this->uri->segment(6));
		$data = array(
			'kode' => $jenis,
			'thn' => $this->input->post("thn"),
			'smt' => $this->input->post("smt")
		);
		
		# set new session 																	!important
		// $this->session->unset_userdata('ta');
		// $this->session->set_userdata('ta', $data['thn']);
		// $this->session->unset_userdata('smt');
		// $this->session->set_userdata('smt', $data['smt']);

		$this->session->set_userdata('ta_remun', $data['thn']);
		$this->session->set_userdata('smt_remun', $data['smt']);
			
		$kd_ta = $this->setting->_generate_kd_ta($this->session->userdata('ta_remun'));
		$kd_smt = $this->setting->_generate_kd_smt($this->session->userdata('smt_remun'));
		
		$kd = $this->session->userdata("kd_dosen");
		$status = $this->session->userdata("jenis_dosen");
		
		$data['is_crud'] = $this->setting->_is_crud_bkd_lalu($kd_ta, $kd_smt);


		#echo $data['is_crud'];
		if($data['is_crud'] == true){
			$data['tombol'] = 'style="display:none"';
		}else{
			$data['tombol'] = 'style="display:none"';
		}
		if($data['kode'] == 'D' && ($status == 'PR' || $status == 'DS')){
			# load view
			$this->output99=$this->s00_lib_output;
			$this->output99->output_display('dosen/beban_tambahan_message');
		}
		else{
			
			$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
			$parameter  = array('api_search' => array($data['kode'], $kd, $data['thn'], $data['smt']));
			$data['data_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			$i =0;
			foreach ($data['data_remun'] as $key) {
				//$kd_kat_remun = $key->KD_KAT_REMUN;
				$kd_kat = $key->KD_KAT;
				$status_pindah = $key->STATUS_PINDAH;
				if($status_pindah == 2){
					$kd_kat_remun = $kd_kat;
				}elseif($status_pindah == 0){
					$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_remun2';
					$parameter  = array('api_search' => array($kd_kat));
					$kd_kat_remun = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
					/*$kd_kat_remun = $kode['KD_KAT_REMUN'];*/
				}
			
				$data['data_remun'][$i]->KD_KAT_REMUN = $kd_kat_remun;
				$i++;

				/*$data['data_remun'][$i]->KD_KAT_REMUN = $kd_kat_remun;
				$i++;*/

				$api_url 	= URL_API_BKD.'bkd_remun/get_jenis_nilai_kat_remun';
				$parameter  = array('api_search' => array($kd_kat_remun));
				$data['nilai_kat'][$kd_kat_remun] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			}
		
			if ($show == true){
				$this->output99->output_display('dosen/history_data_remun',$data);
			}else{
				$this->output99->output_display('dosen/form_history_remun',$data);
			}

		}
	}

	function testing()
	{
		$kd_dosen = '196403121995031001';
		$smt = 2;
		$ta = 2016;
		$thn = 2016;
		if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array(
						'api_kode' 		=> 58000,
						'api_subkode' 	=> 32,
						'api_search' 	=> array($ta, $smt , $kd_dosen)
					);
		$data['penawaran'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		echo '<pre>';
		print_r($data['penawaran']);
		echo '</pre>';
		
		foreach ($data['penawaran'] as $key) {
			$test = $this->testing2($key->KD_KELAS);
			echo $key->NM_MK; echo '<br>';
			echo $test; echo '<br>';
			echo '<br>';
		}
	}

	function testing2($kd_kelas){
		#sia_penawaran/data_search, 58000/6, api_search = array(kd_kelas)
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array('api_kode'=>58000, 'api_subkode' => 6, 'api_search' => array($kd_kelas));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		return $data[0]->GOLONGAN_MK;
	}

	function ujicoba(){
		// $data['kode'] ='B';
		// $data['kd_dosen'] = $this->session->userdata('kd_dosen');
		// $data['ta'] = $this->session->userdata('ta');
		// $data['smt'] =$this->session->userdata('smt');;
		// $api_url 	= URL_API_BKD.'bkd_remun/data_remun';
		// $parameter  = array('api_search' => array($data['kode'], $data['kd_dosen'], $data['ta'], $data['smt']));
		// $data['data_remun'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		// echo "<pre>";
		// print_r($data['data_remun']);
		// echo "</pre>";

		/*$d = $this->session->userdata('kd_smt');
		echo $d;*/

	//<START> API UNTUK MENGETAHUI PEMBIMBING
		//cara menulis ulang format api dari api aslinya
		//$periode = 2 menunjukkan jadwal ujian tugas akhir

		//api aslinya
		/*$data['jadwal'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalTA','json','POST', 
						array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode)));*/
		/*$periode =2;
		
		$url = "sia_skripsi_bimbingan/jadwalTA";
		$api_url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
		$subkode =1;
		$parameter = array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode));

		$data['temp'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		echo "<pre>";
		print_r($data['temp']);
		echo "</pre>";*/

		//api aslinya
		/*$data['pembimbing'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalMunaqosah','json','POST', 
					array('api_kode' => 1001, 'api_subkode' => 2, 'api_search' => array($daftar, $periode)));*/

		/*$daftar = $data['temp'][0]['ID_PENDAFTARAN'];
		$url = "sia_skripsi_bimbingan/jadwalMunaqosah";
		$api_url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
		$parameter = array('api_kode' => 1001, 'api_subkode' => 2, 'api_search' => array($daftar, $periode));
		$data['pembimbing'] =  $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);

		echo "<pre>";
		print_r($data['pembimbing']);
		echo "</pre>";*/

		
	//<END> API UNTUK MENGETAHUI PEMBIMBNG

	//<START> API UNTUK MENGETAHUI PENGUJI

	//<END> API UNTUK MENGETAHUI PENGUJI

	}
	function test_api_get_info_pembimbing(){
		//<START> API UNTUK MENGETAHUI PEMBIMBING
		//cara menulis ulang format api dari api aslinya
		//$periode = 2 menunjukkan jadwal ujian tugas akhir

		//api aslinya
		//pembimbing telah berlangsung
		/*$data['jadwal'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalTA','json','POST', 
						array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode)));*/
		
		//pembimbing sedang berlangsung
		/*$data['jadwal'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalTA','json','POST', 
						array('api_kode' => 1003, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode)));
						$data['label'] = "<span class='label label-warning'>Sedang berlangsung</span>";*/
		$kode_ta = $this->session->userdata('ta');
		$pecah = explode('/', $kode_ta);
		$ta_depan = $pecah[0];
		$ta_belakang = $pecah[1];

		$semester_ganjil_mulai = "09/01/".$ta_depan;
		$semester_ganjil_selesai = "01/31/".$ta_belakang;
		$semester_genap_mulai = "02/01/".$ta_belakang;
		$semester_genap_selesai = "05/31".$ta_belakang;//catatan: baru semester ganjil dan genap saja, belum termasuk semester pendek.
		//rentang periode semester pendek 01/06/tahun s.d 31/08/tahun

		$periode =2;//menunjukkan ujian tugas akhir
		
		$url = "sia_skripsi_bimbingan/jadwalTA";
		$api_url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
		$subkode =1;//untuk pembimbing
		$parameter = array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode));

		$data['temp'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		print_r($data['temp']);
		$tgl_mulai = date('Y-m-d', strtotime($semester_ganjil_mulai));
		$tgl_selesai = date('Y-m-d', strtotime($semester_ganjil_selesai));
		/*echo "tgl api = ".$tgl_api."<br>";
		echo "tgl mulai semester = ".$tgl_mulai."<br>";
		echo "tgl selesai semester = ".$tgl_selesai."<br>";*/
		$i=0;
		//print_r($data['temp'][0]['TGL_MUNA']);
		echo "mahasiswa yang telah selesai dibimbing adalah mahasiwa dengan nim : "."<br>";
		foreach ($data['temp'] as $key) {
			$tgl_api = date('Y-m-d', strtotime($key['TGL_MUNA']));
			if(($tgl_api > $tgl_mulai) && ($tgl_api < $tgl_selesai)){
				$no = $i+1;
				echo $no.". ".$key['NIM'];

				/*print_r($key['NIM']);
				$i++;
				echo "<br>".$i;*/
				$i++;
			}
		}
		echo "<br>"."total mahasiswa yang telah selesai dibimbing adalah : ".$i;
		
		//print_r($data['temp'][0]['TGL_MUNA']);
		

		
		
	
		/*foreach ($data['temp'] as $key) {
			if($key['TGL_MUNA'] )
		}

		$thn = "2017";
		$i=0;
		foreach ($data['temp'] as $key) {
			if(stristr(substr($key['TGL_MUNA'], 6, 4), $thn)){
				$tgl_munaq = "Yang telah selesai dibimbing pada periode tahun : ". substr($key['TGL_MUNA'], 6, 4);
				echo "<pre>";
				print_r($tgl_munaq);
				echo "</pre>";
				$i++;

			}

		}
		echo "<br>"." Jumlah Mahasiswa yang telah selesai dibimbing pada tahun ".$ta_depan." : ".$i." Mahasiswa";*/
		
		
		/*echo "<pre>";
		print_r($data['temp']);
		echo "</pre>";*/

		//echo "<br>"." Jumlah Mahasiswa yang telah selesai dibimbing : ".count($data['temp'])." Mahasiswa";
		//print_r(count($data['temp']));
		//api aslinya
		/*$data['pembimbing'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalMunaqosah','json','POST', 
					array('api_kode' => 1001, 'api_subkode' => 2, 'api_search' => array($daftar, $periode)));*/

		/*$daftar = $data['temp'][0]['ID_PENDAFTARAN'];
		$url = "sia_skripsi_bimbingan/jadwalMunaqosah";
		$api_url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
		$parameter = array('api_kode' => 1001, 'api_subkode' => 2, 'api_search' => array($daftar, $periode));
		$data['pembimbing'] =  $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);

		echo "<pre>";
		print_r($data['pembimbing']);
		echo "</pre>";*/

		//status keluaran (output) => ID_JENIS_PEM
		
	//<END> API UNTUK MENGETAHUI PEMBIMBNG
	}
	function test_api_get_info_peguji(){
		//<START> API UNTUK MENGETAHUI PENGUJI
			$kode_ta = $this->session->userdata('ta');
			$pecah = explode('/', $kode_ta);
			$ta_depan = $pecah[0];
			$ta_belakang = $pecah[1];

			$semester_ganjil_mulai = "09/01/".$ta_depan;
			$semester_ganjil_selesai = "01/31/".$ta_belakang;
			$semester_genap_mulai = "02/01/".$ta_belakang;
			$semester_genap_selesai = "05/31".$ta_belakang;
			$periode =2;//menunjukkan ujian tugas akhir
		
			$url = "sia_skripsi_bimbingan/jadwalTA";
			$api_url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
			$subkode =2;//untuk penguji
			$parameter = array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode));

			$data['temp'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			
			$tgl_mulai = date('Y-m-d', strtotime($semester_ganjil_mulai));
			$tgl_selesai = date('Y-m-d', strtotime($semester_ganjil_selesai));
			/*echo "tgl api = ".$tgl_api."<br>";
			echo "tgl mulai semester = ".$tgl_mulai."<br>";
			echo "tgl selesai semester = ".$tgl_selesai."<br>";*/
			$i=0;
			//print_r($data['temp'][0]['TGL_MUNA']);
			echo "mahasiswa yang telah selesai diuji adalah mahasiwa dengan nim : "."<br>";
			foreach ($data['temp'] as $key) {
				$tgl_api = date('Y-m-d', strtotime($key['TGL_MUNA']));
				if(($tgl_api > $tgl_mulai) && ($tgl_api < $tgl_selesai)){
					$no = $i+1;
					echo $no.". ".$key['NIM'];

					/*print_r($key['NIM']);
					$i++;
					echo "<br>".$i;*/
					$i++;
				}
			}
			echo "<br>"."total mahasiswa yang telah selesai diuji adalah : ".$i;

			//echo "<br>"." Jumlah Mahasiswa yang telah diuji : ".count($data['temp'])." Mahasiswa";


			//api aslinya
			/*$data['penguji'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalMunaqosah','json','POST', 
					array('api_kode' => 1001, 'api_subkode' => 3, 'api_search' => array($daftar, $periode)));*/
/*
			$daftar = $data['temp'][0]['ID_PENDAFTARAN'];
			$url = "sia_skripsi_bimbingan/jadwalMunaqosah";
			$api_url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
			$parameter = array('api_kode' => 1001, 'api_subkode' => 3, 'api_search' => array($daftar, $periode));
			$data['penguji'] =  $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);

			echo "<pre>";
			print_r($data['penguji']);
			echo "</pre>";*/

			//<END> API UNTUK MENGETAHUI PENGUJI
	}
	function cek_jadwal_sia(){
		$nim = 11650006;
		$periode= 2;
		$url = "sia_skripsi_bimbingan/mhs_daftar";
		$api_url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
		$parameter = array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($nim, $periode));
		$daftar = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		$url2 = "sia_skripsi_bimbingan/jadwalMunaqosah";
		$api_url2 = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url2;
		$parameter2 = array('api_kode' => 1001, 'api_subkode' => 1, 'api_search' => array($daftar, $periode));
		$data = $this->s00_lib_api->get_api_json($api_url2,'POST',$parameter2);

		/*$data = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalMunaqosah','json','POST', 
			array('api_kode' => 1001, 'api_subkode' => 1, 'api_search' => array($daftar, $periode)));*/

		echo "<pre>";
		print_r($data);
		echo "</pre>";

		/*$daftar = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/mhs_daftar','json','POST', 
				array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($nim, $periode)));
		$data = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalMunaqosah','json','POST', 
			array('api_kode' => 1001, 'api_subkode' => 1, 'api_search' => array($daftar, $periode)));
		return $data;*/
	}
	function cek_semester_sia(){
		$kd_smt = $this->session->userdata('kd_smt');
		$api_url = URL_API_SIA.'sia_krs/data_search';
		$parameter = array('api_kode'=>51000, 'api_subkode' => 3, 'api_search' => array($this->session->userdata('kd_smt')));
		$data = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		/*$this->cek_smt = $this->api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST', 
		array('api_kode'=>51000, 'api_subkode' => 3, 'api_search' => array($this->session->userdata('kd_smt'))));*/
		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}

	function testing_tugas_akhir()
	{
		//$url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/';
		//$data['jadwal'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalTA','json','POST', 
		//array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode)));

		$data = $this->s00_lib_api->get_api_json(
					'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/sia_skripsi_bimbingan/jadwalTA',
					'POST',
					array(
						'api_kode'		=> 1000,
						'api_subkode'	=> 1,
						'api_search'	=> array($this->session->userdata('id_user'), '2')
					)
			);

		return $data;

	}

	function coba_api(){
		$data = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST',
		array('api_kode'=>52000, 'api_subkode'=>12, 'api_search' => array('18/06/2016','200101')));
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		// $test = $this->session->userdata('kd_ta');
		// echo $test;
		// $data = $this->get_data_mhs('13651060');
		// echo '<pre>';
		// print_r($data);
		// echo '</pre>';
	}

	function periode_semester($tgl_munaq, $kd_prodi){
		//$tgl_munaq = d/m/Y
		$data = $this->s00_lib_api->get_api_json(
			URL_API_SIA.'sia_krs/data_search',
			'POST',
			array(
				'api_kode'		=> 52000,
				'api_subkode'	=> 12,
				'api_search'	=> array($tgl_munaq, $kd_prodi)
			)
		);

		return $data;
	}

	function get_data_bimbingan_ta(){
		$bimbingan = $this->testing_tugas_akhir();
		$data = array();
		$ta = $this->session->userdata('kd_ta');
		$smt = $this->session->userdata('kd_smt');

		foreach ($bimbingan as $bbg) {
			$mhs 	= $this->get_data_mhs($bbg['NIM']);
			$waktu 	= $this->periode_semester(str_replace('-', '/', $bbg['TGL_MUNA']), $mhs[0]['KD_PRODI']);
			if($waktu){
				foreach ($waktu as $wkt) {
					if($ta == $wkt['KD_TA'] && $smt == $wkt['KD_SMT']){
						$data[] = $bbg;
					}
				}
			}
			
		}

		echo '<pre>';
		print_r($data);
		echo '</pre>';

	}

	function get_data_bimbingan_ta_test(){
		$bimbingan = $this->testing_tugas_akhir();
		$data = array();
		$ta = $this->session->userdata('kd_ta');
		$smt = $this->session->userdata('kd_smt');

		foreach ($bimbingan as $bbg) {
			$mhs 	= $this->get_data_mhs($bbg['NIM']);
			$waktu 	= $this->periode_semester(str_replace('-', '/', $bbg['TGL_MUNA']), $mhs[0]['KD_PRODI']);
			echo '<pre>';
			print_r($bbg['NIM']);
			print_r($waktu);
			echo '</pre>';
			
			echo '================================================================';
			echo '<br>';
			echo '<br>';
			
		}
		

		echo 'data bimbingan :'; echo '<br>';
		echo '<pre>';
		print_r($bimbingan);
		echo '</pre>';


	}

	function get_data_mhs($nim)
		 {
			$data = $this->s00_lib_api->get_api_json(
					URL_API_SIA.'/sia_mahasiswa/data_search',
					'POST',
					array(
						'api_kode'		=> 26000,
						'api_subkode'	=> 10,
						'api_search'	=> array($nim)
					)
			);

		 	return $data;
		}
	function get_data_mhs_test($nim=1320012040)
		 {
			$data = $this->s00_lib_api->get_api_json(
					URL_API_SIA.'/sia_mahasiswa/data_search',
					'POST',
					array(
						'api_kode'		=> 26000,
						'api_subkode'	=> 10,
						'api_search'	=> array($nim)
					)
			);

		 	echo '<pre>';
			print_r($data);
			echo '</pre>';
		}

		/*API KERJA PRAKTIK*/
		function test_api_kp(){
			$periode = 2;// periode = 2, menunjukkan jadwal ujian kerja praktik
			$subkode = 2; //subkode = 1 untuk pembimbing, subkode = 2 untuk penguji
			$url = 'sia_kp_bimbingan/jadwalTA';
			$api_url = 'http://service.uin-suka.ac.id/servkerjapkt/sia_kp_public/'.$url;
			$parameter = array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode));
			$data = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

			echo "<pre>";
			print_r($data);
			echo "</pre>";
			/*$data['jadwal'] = $this->mdl_kp->get_api('sia_kp_bimbingan/jadwalTA','json','POST', 
						array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode)));
				$data['jadwal_upcoming'] = $this->mdl_kp->get_api('sia_kp_bimbingan/jadwalTA','json','POST', 
						array('api_kode' => 1002, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode)));*/
		}
		function coba_api_kurikulum(){
			//$kd_prodi=200101;//s2 prodi interdisiplenarry
			$kd_prodi = 22607;//s2 teknik informatika

		/*	$data['kurpilih'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
			array('api_kode'=>38000, 'api_subkode' => 3, 'api_search' => array($v_kur)));*/
			
			// $data['kurpilih'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
			// array('api_kode'=>38000, 'api_subkode' => 8, 'api_search' => array($kd_prodi)));

			$kd_kur = $this->kurikulum_mahasiswa('13690001');

			$data['kurdaftar'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
				array('api_kode'=>40000, 'api_subkode' => 15, 'api_search' => array($kd_kur)));
			/*$data['kursemua'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
			array('api_kode'=>38000, 'api_subkode' => 7, 'api_search' => array($kd_prodi)));*/
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}

		function kurikulum_mahasiswa($nim='13650042'){
			$data = $this->s00_lib_api->get_api_json(
					URL_API_SIA.'/sia_kurikulum/data_search',
					'POST',
					array(
						'api_kode'		=> 40001,
						'api_subkode'	=> 3,
						'api_search'	=> array($nim)
					)
			);

			//return $data[0]['KD_KUR'];
			print_r($data);
		}

		function list_mahasiswa_prodi(){
			$parameter = array(	'api_kode' => 26000,'api_subkode' => 16,'api_search'=> array('22117','2013'));
			$data= $this->api->get_api_json(URL_API_SIA.'sia_mahasiswa/data_search','POST',$parameter);

			echo "<pre>";
			print_r($data);
			echo "</pre>";

		}

		function cek_bimbingan_kp_mhs(){
			$kd_dosen = $this->session->userdata('kd_dosen');
			$periode = 2;
			$jadwal = $this->api->get_api_json(
				'http://service.uin-suka.ac.id/servkerjapkt/sia_kp_public/sia_kp_bimbingan/jadwalTA',
				'POST',
				array(
					'api_kode' 		=> 1000,
					'api_subkode' 	=> 2,
					'api_search'	=> array($kd_dosen, $periode)
				)
			);

			echo '<pre>';
			print_r($jadwal);
			echo '</pre>';

		}

		function cek_bimbingan_kkn(){
			$kd_dosen 	= '197510242009121002';
			$ta 		= '2015';
			$smt 		= '3';
			$jadwal = $this->api->get_api_json(
				URL_API_KKN.'kkn_dosen/data_search',
				'POST',
				array(
					'api_kode' 		=> 201400,
					'api_subkode' 	=> 1,
					'api_search'	=> array('KKN.KKN_DPL2014','ID_DPL',array('NIP'=>$kd_dosen))
				)
			);

			if($jadwal){
				$id_dpl = $jadwal[0]['ID_DPL'];
				$kelompok = $this->api->get_api_json(
					URL_API_KKN.'kkn_dosen/data_search',
					'POST',
					array(
						'api_kode' 		=> 201402,
						'api_subkode' 	=> 1,
						'api_search'	=> array($ta,$smt,$id_dpl)
					)
				);

				echo '<pre>';
				print_r($kelompok);
				echo '</pre>';
				}

			echo '<pre>';
			print_r($jadwal);
			echo '</pre>';


		}
		function hitung_jarak_bulan_semester(){
			$kd_dosen = $this->session->userdata("id_user");
			$ta = $this->session->userdata("ta");
			$smt = $this->session->userdata("smt");	
			$kd = $this->session->userdata("kd_dosen");
			$data = array('ta'=> $ta, 'smt'=>$smt);
			/* load main content */
			$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
			$parameter  = array('data_search' => array($kd));
			$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$kd_prodi = $data['dosen'][0]->KD_PRODI;
			$tgl = date('d/m/Y');
			$data = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST',
			array('api_kode'=>52000, 'api_subkode'=>12, 'api_search' => array($tgl, $kd_prodi)));
			/*echo "<pre>";
			print_r($data);
			echo "</pre>";
			die();*/

			$tgl_mulai_smt = $data[0]['TGL_MULAI_SMT'];
			$tgl_akhir_smt = $data[0]['TGL_AKHIR_SMT'];

			$tgl_m = str_replace("/", "-", $tgl_mulai_smt);
			$tgl_mulai_baru = date('d/m/Y', strtotime($tgl_mulai_smt));
			$tgl_a = str_replace("/", "-", $tgl_akhir_smt);
			$tgl_akhir_baru = date('Y-m-d', strtotime($tgl_a));
			
			$tgl_mm = strtotime($tgl_mulai_baru);
			$thn_m = date('Y', $tgl_mm);
			$bln_m = date('m', $tgl_mm);

			$tgl_aa = strtotime($tgl_akhir_baru);
			$thn_a = date('Y', $tgl_aa);
			$bln_a = date('m', $tgl_aa);

	        $numBulan = 1+($thn_a - $thn_m)*12;
	        $numBulan += $bln_a-$bln_m;

	        /*echo $numBulan;
	        die();*/

	        return $numBulan;
		}
		function cek_jenis_dosen(){
			$kd_ta = $this->session->userdata('kd_ta');
			$kd_smt = $this->session->userdata('kd_smt');
			$kd_dosen = $this->session->userdata('id_user');

			$api_url_jabatan 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
			$parameter  = array('api_kode' => 1122, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
			$data['fungsional'] = $this->s00_lib_api->get_api_jsob($api_url_jabatan,'POST',$parameter);
			$jabatan = $data['fungsional'][0]->FUN_NAMA;
			
			return $jabatan;
		}

		//testing data untuk ujian 

		function cek_ujian_proposal_mhs(){
			//$periode = 0 //periode untuk ujian komprehensif
			$periode =1; //periode untuk ujian seminar proposal
			//$periode =2; // periode untuk ujian tugas akhir
			//$periode = 3; //periode untuk ujian tertutup
			//$periode = 4; //periode untuk ujian terbuka
			//api_kode => 1000 untuk yang telah selesai menguji

			$subkode = 2; //subkode=2 untuk penguji
			$url 			= 'sia_skripsi_bimbingan/jadwalTA';
			$api_url 		= 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
			$parameter 		= array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode));
			$data['jadwal'] = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
			echo "<pre>";
			print_r($data['jadwal']);
			echo "</pre>";
		}
		public function get_jml_pengabdian(){
			$kd_dosen = $this->session->userdata("id_user");
			$ta = $this->session->userdata("ta"); //2017/2018
			$kd_ta = $this->session->userdata("kd_ta"); //2017
		
			$smt = $this->session->userdata("smt");	
			$kd = $this->session->userdata("kd_dosen");
			$data = array('ta'=> $ta, 'smt'=>$smt);
			/* load main content */
			$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
			$parameter  = array('data_search' => array($kd));
			$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$kd_prodi = $data['dosen'][0]->KD_PRODI;
			$tgl = date('d/m/Y');
			$data = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST',
			array('api_kode'=>52000, 'api_subkode'=>12, 'api_search' => array($tgl, $kd_prodi)));

			$tgl_mulai_smt = $data[0]['TGL_MULAI_SMT'];
			$tgl_akhir_smt = $data[0]['TGL_AKHIR_SMT'];

			$tgl_m = str_replace("/", "-", $tgl_mulai_smt);
			$tgl_mulai_baru = date('Y-m-d', strtotime($tgl_m));
			$tgl_a = str_replace("/", "-", $tgl_akhir_smt);
			$tgl_akhir_baru = date('Y-m-d', strtotime($tgl_a));

			echo "Tanggal Awal Semester : ".$tgl_mulai_baru."<br>";
			echo "Tanggal Akhir Semester : ".$tgl_akhir_baru."<br>";

			echo "======================"."<br><br>";

			$nim = $this->session->userdata('id_user');
			/*$nim = '197610280000001301';*/
			$url = 'sia_pengabdian_dsn/get_riwayat_pengabdian';
			$api_url = URL_API_PPM2.$url;
			$parameter = array(	'api_kode' => 1000, 'api_subkode' => 1, 'nim'=>$nim);

			$riwayat_abdi = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
			/*echo "<pre>";
			print_r($riwayat_abdi);
			echo "<pre>";
			die();*/
			
			foreach ($riwayat_abdi as $rwyt){
				$STATUS = $rwyt['STATUS'];
				$TGL_KEPUTUSAN = $rwyt['TGL_KEPUTUSAN'];
				$tgl_pecah = explode(" ", $TGL_KEPUTUSAN);
				$tgl_putusan = $tgl_pecah[0];

				$tgl_kep = str_replace("/", "-", $tgl_putusan);
				$tgl_kep2 = date('m-d-Y', strtotime($tgl_kep));

				//cttn : kondisi di bawah ini harus dipenuhi sebagai syarat suatu dosen melakukan sebuah penelitian
				// kodisi : jika status penelitian sudah diterima dan tgl_keputusan penelitian berada diantara tgl_awal dan akhir_semester
				if($STATUS == 'A'){
					$pengabdian = $rwyt;
					$kd_abdi = $rwyt['KD_PENGABDIAN'];

					$url = 'sia_pengabdian_dsn/get_all_data_pengabdian';
					$api_url = URL_API_PPM2.$url;
					$parameter = array('api_kode' => 1000, 'api_subkode' => 101, 'api_search' => array($kd_abdi));
					$detail_abdi = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);

					/*echo "<pre>";
					print_r($detail_abdi);
					echo "<pre>";*/

					foreach ($detail_abdi as $key) {
						$tgl_m_pengabdian = $key['TGL_MULAI_PENGABDIAN'];
						$tgl_mm_pengabdian = explode(" ", $tgl_m_pengabdian);
						$tgl_mmm_pengabdian = $tgl_mm_pengabdian[0];

						$tgl_a_pengabdian = $key['TGL_AKHIR_PENGABDIAN'];
						$tgl_aa_pengabdian = explode(" ", $tgl_a_pengabdian);
						$tgl_aaa_pengabdian = $tgl_aa_pengabdian[0];

						$tgl_m = str_replace("/", "-", $tgl_mmm_pengabdian);
						$tgl_start_pengabdian = date('Y-m-d', strtotime($tgl_m));
						$tgl_a = str_replace("/", "-", $tgl_aaa_pengabdian);
						$tgl_end_pengabdian = date('Y-m-d', strtotime($tgl_a));


						 if(($tgl_start_pengabdian <= $tgl_mulai_baru || $tgl_start_pengabdian >= $tgl_mulai_baru) && ($tgl_end_pengabdian <= $tgl_akhir_baru || $tgl_end_pengabdian >= $tgl_akhir_baru) && ($tgl_end_pengabdian >= $tgl_mulai_baru)  && ($tgl_start_pengabdian <= $tgl_akhir_baru)){
							
							$kd_pengabdian = $key['KD_PENGABDIAN'];
							$nama_pengabdian = $key['NAMA_PENGABDIAN'];
							$periode_pengabdian = $key['PERIODE'];
							$status_pengabdian = $key['STATUS'];
							$lama_pengabdian = $key['LAMA_PENGABDIAN'];
							$thn_awal_pengabdian = $key['TAHUN_AWAL_PENGABDIAN'];
							$tgl_mulai_pengabdian = $key['TGL_MULAI_PENGABDIAN'];
							$tgl_akhir_pengabdian = $key['TGL_AKHIR_PENGABDIAN'];
							$ID_TRANSAKSI = $rwyt['ID_TRANSAKSI'];
								
							
							$judul = "Melakukan pengabdian dengan judul ".$nama_pengabdian."";
							$var = array("ID_TRANSAKSI" =>$ID_TRANSAKSI, "KD_PENELITIAN"=>$kd_pengabdian, "JUDUL_PENELITIAN"=>$judul);

							
						}
					}
					
				}	
				/*$temp[] = $rwyt;*/

			}
			/*echo "<pre>";
			print_r($var);
			echo "<pre>";*/
			return $var;
		}
		public function get_jml_penelitian(){
			$kd_dosen = $this->session->userdata("id_user");
			$ta = $this->session->userdata("ta"); //2017/2018
			$kd_ta = $this->session->userdata("kd_ta"); //2017
		
			$smt = $this->session->userdata("smt");	
			$kd = $this->session->userdata("kd_dosen");
			$data = array('ta'=> $ta, 'smt'=>$smt);
			/* load main content */
			$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
			$parameter  = array('data_search' => array($kd));
			$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$kd_prodi = $data['dosen'][0]->KD_PRODI;
			$tgl = date('d/m/Y');
			$data = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST',
			array('api_kode'=>52000, 'api_subkode'=>12, 'api_search' => array($tgl, $kd_prodi)));

			$tgl_mulai_smt = $data[0]['TGL_MULAI_SMT'];
			$tgl_akhir_smt = $data[0]['TGL_AKHIR_SMT'];

			$tgl_m = str_replace("/", "-", $tgl_mulai_smt);
			$tgl_mulai_baru = date('Y-m-d', strtotime($tgl_m));
			$tgl_a = str_replace("/", "-", $tgl_akhir_smt);
			$tgl_akhir_baru = date('Y-m-d', strtotime($tgl_a));

			/*$tgl_coba = date('m-d-Y', strtotime('01-01-2018'));
			if($tgl_coba > $tgl_mulai_baru){
				echo "benar";
			}else{
				echo "salah";
			}
			echo "<br>";*/
			/*die();*/

			$nim = $this->session->userdata('id_user');
			/*$nim = '197510242009121002';*/
			$url = 'sia_penelitian_dsn/get_riwayat_penelitian';
			$api_url = URL_API_PPM1.$url;
			$parameter = array(	'api_kode' => 1000, 'api_subkode' => 1, 'nim'=>$nim);
			
			$riwayat_teliti = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
			
			/*echo "<pre>";
			print_r($riwayat_teliti);
			echo "<pre>";
			die();*/

			foreach ($riwayat_teliti as $rwyt){
				$STATUS = $rwyt['STATUS'];
				$TGL_KEPUTUSAN = $rwyt['TGL_KEPUTUSAN'];
				$tgl_pecah = explode(" ", $TGL_KEPUTUSAN);
				$tgl_putusan = $tgl_pecah[0];

				$tgl_kep = str_replace("/", "-", $tgl_putusan);
				$tgl_kep2 = date('m-d-Y', strtotime($tgl_kep));

				//cttn : kondisi di bawah ini harus dipenuhi sebagai syarat suatu dosen melakukan sebuah penelitian
				// kodisi : jika status penelitian sudah diterima dan tgl_keputusan penelitian berada diantara tgl_awal dan akhir_semester
				if($STATUS == 'A'){
					$penelitian = $rwyt;
					$kd_teliti = $rwyt['KD_PENELITIAN'];
					$ID_TRANSAKSI = $rwyt['ID_TRANSAKSI'];
					
					$url = 'sia_penelitian_dsn/anggota_penelitian';
					$api_url = URL_API_PPM1.$url;
					$parameter = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($ID_TRANSAKSI, 'staff'));
					$anggota_teliti = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
					
					$url = 'sia_penelitian_dsn/get_all_data_penelitian';
					$api_url = URL_API_PPM1.$url;
					$parameter = array('api_kode' => 1000, 'api_subkode' => 101, 'api_search' => array($kd_teliti));
					$detail_teliti = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
					foreach ($detail_teliti as $key) {
						$tgl_m_penelitian = $key['TGL_MULAI_PENELITIAN'];
						$tgl_mm_penelitian = explode(" ", $tgl_m_penelitian);
						$tgl_mmm_penelitian = $tgl_mm_penelitian[0];

						$tgl_a_penelitian = $key['TGL_AKHIR_PENELITIAN'];
						$tgl_aa_penelitian = explode(" ", $tgl_a_penelitian);
						$tgl_aaa_penelitian = $tgl_aa_penelitian[0];

						$tgl_m = str_replace("/", "-", $tgl_mmm_penelitian);
						$tgl_start_penelitian = date('Y-m-d', strtotime($tgl_m));
						$tgl_a = str_replace("/", "-", $tgl_aaa_penelitian);
						$tgl_end_penelitian = date('Y-m-d', strtotime($tgl_a));
						
						 if(($tgl_start_penelitian <= $tgl_mulai_baru || $tgl_start_penelitian >= $tgl_mulai_baru) && ($tgl_end_penelitian <= $tgl_akhir_baru || $tgl_end_penelitian >= $tgl_akhir_baru) && ($tgl_end_penelitian >= $tgl_mulai_baru)  && ($tgl_start_penelitian <= $tgl_akhir_baru)){
							
							$kd_penelitian = $key['KD_PENELITIAN'];
							$nama_penelitian = $key['NAMA_PENELITIAN'];
							$periode_penelitian = $key['PERIODE'];
							$status_penelitian = $key['STATUS'];
							$lama_penelitian = $key['LAMA_PENELITIAN'];
							$thn_awal_penelitian = $key['TAHUN_AWAL_PENELITIAN'];
							$tgl_mulai_penelitian = $key['TGL_MULAI_PENELITIAN'];
							$tgl_akhir_penelitian = $key['TGL_AKHIR_PENELITIAN'];

							/*echo "Tanggal Mulai Penelitian : ".$tgl_start_penelitian."<br>";
							echo "Tanggal Selesai Penelitian : ".$tgl_end_penelitian."<br>"."======================="."<br>";*/
								
							$temp = array("KD_PENELITIAN" => $kd_penelitian, "NAMA_PENELITIAN"=>$nama_penelitian, "PERIODE_PENELITIAN"=>$periode_penelitian, "STATUS_PENELITIAN"=>$status_penelitian, "LAMA_PENELITIAN"=>$lama_penelitian, "TAHUN_AWAL_PENELITIAN"=>$thn_awal_penelitian, "TGL_MULAI_PENELITIAN"=>$tgl_mulai_penelitian, "TGL_AKHIR_PENELITIAN"=>$tgl_akhir_penelitian);
							$temp2 = "Melakukan penelitian dengan judul ".$temp['NAMA_PENELITIAN']."";

							echo "Judul Penelitian : <br>";
							echo "=================================<br>";
							echo $temp2;
							echo "<br>=================================<br>";
							echo "Daftar Anggota Penelitian : <br>";
							echo "=================================<br>";
							if(!empty($anggota_teliti)){
								foreach ($anggota_teliti as $key) {
									$nip_anggota = $key['NOMOR_INDUK'];
									$judul_penelitian = "Menjadi anggota peneliti ".$temp['NAMA_PENELITIAN'];
									$datap[] = array($nip_anggota, $judul_penelitian);
								}
							}
							echo "<pre>";
							print_r($datap);
							echo "<pre>";
							
						}
					}
				}	
				/*$temp[] = $rwyt;*/
			}
			
		}
		public function get_keterangan_penelitian(){
			$kd_teliti = '46';

			$url = 'sia_penelitian_dsn/get_all_data_penelitian';
			$api_url = URL_API_PPM1.$url;
			$parameter = array('api_kode' => 1000, 'api_subkode' => 101, 'api_search' => array($kd_teliti));
			$detail_teliti = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
			echo "<pre>";
			print_r($detail_teliti);
			echo "<pre>";
		}
		/*function cek_tahun_penawaran_penelitian(){
			$arrtahun = $this->mdl_teliti->get_api('sia_penelitian_dsn/get_all_data_penelitian', 'json', 'POST',
			array('api_kode' => 1000, 'api_subkode' => 121, 'api_search' => array()));
			echo "<pre>";
			print_r($arrtahun);
			echo "<pre>";
		}*/
		/*function cek_api_dpa(){
			$v_nip = $this->session->userdata('id_user');
			$data['m_aktif']	= $this->api->get_api_json(URL_API_SIA.'sia_mahasiswa/data_search', 'POST', 
							array('api_kode'=>28000, 'api_subkode' => 6, 
							'api_search' => array('A', $v_nip)));
			$h=0; $i = 0; $j=0; $k =0;
			$s0=0; $s1=0; $s2=0; $s3=0;
			//$jng = "S1";
			foreach ($data['m_aktif'] as $key) {
				$jenjang = strtoupper($key['NM_JENJANG']);
				if($jenjang == 'S0'){
					$s0 = $h+1;
					$h++;
				}elseif($jenjang == 'S1'){
					$s1 = $i+1;
					$i++;
				}elseif($jenjang == 'S2'){
					$s2 = $j+1;
					$j++;
				}elseif($jenjang == 'S3'){
					$s3 = $k+1;
					$k++;
				}	
			}
			if(!empty($s0)){
				echo "QUERY : "."<br>";
				echo "=================<br>";
				echo "MASUKKAN KE TABLE JUMLAH MAHASISWA BIMBINGAN S0 = ".$s0."<br>";
			}
			if(!empty($s1)){
				echo "QUERY : "."<br>";
				echo "=================<br>";
				echo "MASUKKAN KE TABLE JUMLAH MAHASISWA BIMBINGAN S1 = ".$s1."<br>";
			}
			if(!empty($s2)){
				echo "QUERY : "."<br>";
				echo "=================<br>";
				echo "MASUKKAN KE TABLE JUMLAH MAHASISWA BIMBINGAN S2 = ".$s2."<br>";
			}
			if(!empty($s3)){
				echo "QUERY : "."<br>";
				echo "=================<br>";
				echo "MASUKKAN KE TABLE JUMLAH MAHASISWA BIMBINGAN S3 = ".$s3."<br>";
			}

		}*/

		//-------------- create by DNG A BMTR ------------------//

		function kesimpulan(){
			$data['title'] = "Kesimpulan";
			$jenis = $this->security->xss_clean($this->uri->segment(5));

			$api_url  = URL_API_BKD.'bkd_remun/get_jenis_remun';
			$kode_api = $this->api->get_api_json(
							$api_url,
							'POST',
							array('api_search' => array())
						);

			$data['kd_dosen'] = $this->session->userdata('kd_dosen');
			$tahun = $this->input->post('thn');

			if($tahun == ''){
				$data['ta'] = $this->session->userdata('ta');
				$data['smt'] = $this->session->userdata('smt');		
			}else{
				$data['ta'] = $tahun;
				$data['smt'] = $this->input->post('smt');
			}

			$jabatan = $this->get_jabatan_fungsional($data['kd_dosen']);
			
			$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
			$parameter  = array('data_search' => array($data['kd_dosen']));
			$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$data['namaLengkap'] = $this->getDataDosen($data['kd_dosen']);

			$kd_ta = $this->_generate_ta($data['ta']);
			$kd_smt = $this->_generate_smt($data['smt']);
			$this->session->unset_userdata('jenis_dosen');
			$this->session->set_userdata('jenis_dosen', $this->history->_status_DS($data['kd_dosen'], $data['ta'], $data['smt']));

			$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($this->session->userdata('jenis_dosen'));
			foreach ($data['dosen'] as $val) {
				if ($val->NO_SERTIFIKAT == ''){
					$data['noser'] = '-';
				}else{
					$data['noser'] = $val->NO_SERTIFIKAT;
				}
			}

			$temporary = array();
			$temp_sks 	= 0;
			$temp_poin 	= 0;
			$temp_satuan = 0;

			$testing_poin = array();

			foreach ($kode_api as $jbr) {
				$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
				$parameter  = array('api_search' => array($jbr['KD_JBR'], $data['kd_dosen'], $data['ta'], $data['smt']));
				$data['data_remun'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);



				$i=0;
				foreach ($data['data_remun'] as $key) {
					/*echo "<pre>";
					print_r($key['JENIS_KEGIATAN']);
					echo "<pre>";*/
					$jenjang 	= $key['JENJANG'];
					$kelas 		= $key['KELAS'];
					$semester   = $key['SEMESTER'];
					$kd_kat  	= $key['KD_KAT'];
					$status_pindah = $key['STATUS_PINDAH'];
					if($status_pindah == 0){
						$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_remun2';
						$parameter  = array('api_search' => array($kd_kat));
						$kd_kat_remun = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);	
					}elseif($status_pindah == 2){
						$kd_kat_remun = $kd_kat;
					}
					
					//$kd_kat_remun = $key['KD_KAT_REMUN'];


					$api_url 	= URL_API_BKD.'bkd_remun/get_jenis_nilai_kat_remun';
					$parameter  = array('api_search' => array($kd_kat_remun));
					$nilai_kat = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
					/*echo "<pre>";
					print_r($nilai_kat);
					echo "</pre>";
					die();*/

					if($nilai_kat['NILAI_KAT'] == 'SKS'){
						$sks = $key['JML_SKS'];
					}elseif($nilai_kat['NILAI_KAT'] == 'JUMLAH'){
						$sks = $key['JML_MHS'];
					}
					
					$tatapmuka  	= $key['JML_TM'];
					$tm_per_minggu  = $key['JML_PERTEMUAN_PM'];
					if($tm_per_minggu == 0){
						$tm_per_minggu = 1;
					}else{
						$tm_per_minggu = $tm_per_minggu;
					}

					if($tatapmuka == 0){
						$tatapmuka = 1;
					}else{
						$tatapmuka = $tatapmuka;
					}

					$jml_dosen 		= $key['JML_DOSEN'];


					$api_url 	= URL_API_BKD.'bkd_remun/get_kd_kat_remun3';
					$parameter  = array('api_search' => array($kd_kat_remun));
					$tmp_kategori = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

					$kategori 	= $tmp_kategori['KD_KAT_REMUN'];

					$a['JENIS_KEGIATAN'] = $key['JENIS_KEGIATAN'];
					$tmp_kategori = array_merge($tmp_kategori, $a);

					/*echo "<pre>";
					print_r($tmp_kategori);
					echo "</pre>";*/
					

					if(!isset($temporary[$jbr['KD_JBR']][$kategori])){
						$temporary[$jbr['KD_JBR']][$kategori]['GROUP'] = $jbr['NM_JBR'];
						$temporary[$jbr['KD_JBR']][$kategori]['JUDUL'] = $tmp_kategori['JUDUL'];
						$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN'] = $tmp_kategori['JENIS_KEGIATAN'];
						$temporary[$jbr['KD_JBR']][$kategori]['NILAI'] = 0;
						$temporary[$jbr['KD_JBR']][$kategori]['SATUAN'] = 0;
						$temporary[$jbr['KD_JBR']][$kategori]['POIN'] = 0;

					}

					$api_url 	= URL_API_BKD.'bkd_remun/get_poin_remun';
					$parameter  = array('api_search' => array($jbr['KD_JBR'], $jenjang, $kelas, $jabatan, $semester, $kategori));
					$poin 		= $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
					/*echo "<pre>";
					print_r(array("judul"=>$tmp_kategori["JUDUL"], "kode_jbr" => $jbr['KD_JBR'], "jenjang" => $jenjang, "kelas"=>$kelas, "jabatan"=>$jabatan, "semester"=>$semester, "kategori"=>$kategori));

					echo "<pre>";*/
				
					$poin_baru = (float) str_replace(',', '.', $poin['POIN']);

					$temp_sks += $sks;
					$temp_poin += $poin_baru * ($sks / $jml_dosen) * ($tatapmuka / $tm_per_minggu);
					$temp_satuan = $poin['SATUAN'];

					$temporary[$jbr['KD_JBR']][$kategori]['NILAI'] += $sks;
					$temporary[$jbr['KD_JBR']][$kategori]['SATUAN'] = $poin['SATUAN'];
					$temporary[$jbr['KD_JBR']][$kategori]['POIN'] += $poin_baru * ($sks / $jml_dosen) * ($tatapmuka / $tm_per_minggu);
				}
			}
			/*die();*/
			/*echo "<pre>";
				print_r($temporary);
				echo "<pre>";*/
			
			/*die();*/
			$poin_total = $temp_poin;


			$jabatan = $this->cek_jenis_dosen();
			//hanya sementara, karena di poin remun yang diinut masih nama jabatan pegawai, belum berupa kode pegawai
			//start
			switch ($jabatan) {
				case 'Tenaga Pengajar':
					$kd_jabatan = 1;
					break;
				case 'Asisten Ahli':
					$kd_jabatan = 2;
					break;
				case 'Lektor':
					$kd_jabatan = 3;
					break;
				case 'Lektor Kepala':
					$kd_jabatan = 4;
					break;
				case 'Guru Besar':
					$kd_jabatan =5;
					break;
				default:
					break;
			}
			//finish
			$jml_bulan = $this->hitung_jarak_bulan_semester();
			//$rata_poin = $temp_poin/$jml_bulan;
			$rata_poin = $poin_total/$jml_bulan;

			$api_url 	= URL_API_BKD.'bkd_remun/get_poin_skr';
			$parameter  = array('api_search' => array($kd_jabatan));
			$data['poin_skr'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			$poin_skr = $data['poin_skr']['POIN_SKR'];
			$nilai_kinerja = number_format(($rata_poin/$poin_skr)*100, 2);

			$api_url 	= URL_API_BKD.'bkd_remun/get_max_poin_skr';
			$parameter  = array('api_search' => array());
			$data['max_poin_skr'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			$max_poin_skr = $data['max_poin_skr']['MAX_POIN_SKR'];

			if($nilai_kinerja > $max_poin_skr){
				$prosentase_skr = $max_poin_skr;
			}elseif($nilai_kinerja<=$max_poin_skr){
				$prosentase_skr = $nilai_kinerja;
			}

			$data['kesimpulan'] = array(
				'KODE' => 'A',
				'KATEGORI' => 'MELAKSANAKAN PERKULIAHAN',
				'NILAI' => $temp_sks,
				'JML_POIN' => $poin_total,
				'RATA_POIN' => $rata_poin,
				'NILAI_KINERJA' => $prosentase_skr,
				'SATUAN' =>$temp_satuan
			);

			//$data['summary'] = $temporary;
			$data['summary'] = $temporary;
			//$data['summary'] = array_merge($data['summary_a'], $data['summary_d']);

			/*echo "<pre>";
			print_r($data['summary']);
			echo "</pre>";
			die();*/
			$data['kd_dosen'] = $this->session->userdata('kd_dosen');
			$tahun = $this->input->post('thn');

			if($tahun == ''){
				$data['ta'] = $this->session->userdata('ta');
				$data['smt'] = $this->session->userdata('smt');		
			}else{
				$data['ta'] = $tahun;
				$data['smt'] = $this->input->post('smt');
			}

			/*$data['ta'] = $this->session->userdata('ta');
			$data['smt'] = $this->session->userdata('smt');*/

			$kd_dosen = $data['kd_dosen'];
			$kd_ta = $this->_generate_ta($data['ta']);
			$kd_smt = $this->_generate_smt($data['smt']);

			$cek_summary_remun_dosen = $this->cek_summary_remun_dosen($kd_dosen, $kd_smt, $kd_ta);
			
			if(!$cek_summary_remun_dosen){
				//jika belum ada insert data summary remun dosen
				$total_poin = $poin_total;
				$prosentase = $prosentase_skr;

				$api_url 	= URL_API_BKD.'bkd_remun/insert_summary_remun_dosen';
				$parameter  = array('api_search' => array($kd_dosen, $kd_smt, $kd_ta, $total_poin, $prosentase));
				$get = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

			}else{
				//jika sudah ada update data summary remun dosen
				$total_poin = $poin_total;
				$prosentase = $prosentase_skr;

				$api_url 	= URL_API_BKD.'bkd_remun/update_summary_remun_dosen';
				$parameter  = array('api_search' => array($kd_dosen, $kd_smt, $kd_ta, $total_poin, $prosentase));
				$get = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			}
			$this->output99->output_display('dosen/form_remun_kesimpulan',$data);

		}

		function auto_fill_sks(){

			$kategori 	= $this->input->post('kd_kat');
			$jenjang 	= $this->input->post('jenjang');
			$sks 		= $this->input->post('jml_sks');
			$mhs 		= $this->input->post('jml_mhs');
			$nilai 		= 0;

			$kategori   = $this->s00_lib_api->get_api_json(
					URL_API_BKD.'bkd_remun/get_kd_kat_serdos',
					'POST',
					array('api_search' => array($kategori))
			); 

			switch ($kategori) {
				case 1: //mengajar matakuliah
					if($sks != null){
						switch ($jenjang) {
							case 'D3': $nilai = $this->sksrule->_nilai_sks($mhs, 1000, 1, $sks); break;
							case 'S1': $nilai = $this->sksrule->_nilai_sks($mhs, 1000, 1, $sks); break;
							case 'S2': $nilai = $this->sksrule->_nilai_sks($mhs, 1000, 2, $sks); break;
							case 'S3': $nilai = $this->sksrule->_nilai_sks($mhs, 1000, 3, $sks); break;
						}
					}
				break;

				case 3: //membimbing tugas akhir
					switch ($jenjang) {
						case 'D3': $nilai = $this->sksrule->_nilai_sks($mhs, 1001, 2); break;
						case 'S1': $nilai = $this->sksrule->_nilai_sks($mhs, 1001, 2); break;
						case 'S2': $nilai = $this->sksrule->_nilai_sks($mhs, 1001, 3); break;
						case 'S3': $nilai = $this->sksrule->_nilai_sks($mhs, 1001, 4); break;
					}
				break;

				case 73: //menguji tugas akhir (ketua sidang)
					switch ($jenjang) {
						case 'D3': $nilai = $this->sksrule->_nilai_sks($mhs, 1003, 2); break;
						case 'S1': $nilai = $this->sksrule->_nilai_sks($mhs, 1003, 2); break;
						case 'S2': $nilai = $this->sksrule->_nilai_sks($mhs, 1003, 3); break;
						case 'S3': $nilai = $this->sksrule->_nilai_sks($mhs, 1003, 4); break;
					}
				break;

				case 74: //menguji tugas akhir (anggota team sidang)
					switch ($jenjang) {
						case 'D3': $nilai = $this->sksrule->_nilai_sks($mhs, 1003, 2); break;
						case 'S1': $nilai = $this->sksrule->_nilai_sks($mhs, 1003, 2); break;
						case 'S2': $nilai = $this->sksrule->_nilai_sks($mhs, 1003, 3); break;
						case 'S3': $nilai = $this->sksrule->_nilai_sks($mhs, 1003, 4); break;
					}
				break;
				
				default:break;
			}

			echo $nilai;
		}


		//-------------- create by DNG A BMTR ------------------//
		public function test_nilai_kat(){
			$kd_kat_remun = 2;
			$api_url 	= URL_API_BKD.'bkd_remun/get_jenis_nilai_kat_remun';
			$parameter  = array('api_search' => array($kd_kat_remun));
			$nilai_kat = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			print_r($nilai_kat);
		}
		public function daftar_kategori_remun($kode){
			$api_url 	= URL_API_BKD.'bkd_remun/daftar_kategori_remun';
			$parameter  = array('api_search' => array($kode));
			$list_kategori = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			return $list_kategori;
		}
		public function get_spesifik_kategori(){
			$kd_kat = $this->input->post('kode');
			$api_url 	= URL_API_BKD.'bkd_remun/get_spesifik_kategori';
			$parameter  = array('api_search' => array($kd_kat));
			$list_spesifik_kategori = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			//return $list_spesifik_kategori;
			echo json_encode($list_spesifik_kategori);
		}

		/* create by TF*/
		//MENGAMBIL ASESOR DARI DATA SIA
		public function daftar_asesor_semua(){
			//jumlah total asesor
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode'=>7777, 'api_subkode'=> 100, 'api_search' => array());
			$tot_page = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$limit=$tot_page;
			//menampilkan detail data seluruh asesor
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode'=>7777, 'api_subkode'=> 1, 'api_search' => array($limit, $this->uri->segment(5)));
			$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			echo "<pre>";
			print_r($data['asesor']);
			echo "<pre>";
		}
		//MENGAMBIL ASESOR DARI DATA SIA
		public function daftar_asesor_uin(){
			//jumlah total asesor
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode'=>7777, 'api_subkode'=> 100, 'api_search' => array());
			$tot_page = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$limit=$tot_page;
			//menampilkan detail data seluruh asesor
			$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
			$parameter  = array('api_kode'=>7777, 'api_subkode'=> 1, 'api_search' => array($limit, $this->uri->segment(5)));
			$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			$temp =0;
			foreach ($data['asesor'] as $key) {
				if($key->NM_PT == "UIN SUNAN KALIJAGA, JOGYAKARTA"){
					$dataq[] = $key;						
				}
			}
			echo "<pre>";
			print_r($dataq);
			echo "<pre>";
		}
		public function cek_nira_asesor_dosen_uin(){
			$data['kode'] = $this->session->userdata('kd_dosen');
			$data['ta'] = $this->session->userdata('ta');
			$data['smt'] = $this->session->userdata('smt');
			$api_url 	= URL_API_BKD.'bkd_remun/cek_nira_asesor_dosen_uin';
			$parameter  = array('api_search' => array($data['kode']));
			$nira = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			$nira = '991110720021837708923';
			if(!empty($nira)){
				$api_url 	= URL_API_BKD.'bkd_remun/cek_data_asesor';
				$parameter  = array('api_search' => array($nira, $data['ta'], $data['smt']));
				$data['asesor'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
				$jumlah = count($data['asesor']);
				foreach ($data['asesor'] as $key) {
					$kd_dosen = $key['KD_DOSEN'];
					$nama = $this->getDataDosen($kd_dosen);
					foreach ($nama as $nm) {
						$nama_dosen[] = $nm->NM_PGW_F;
					}
				}
				$data['detail_asesor'] = $nama_dosen;
				return $data['detail_asesor'];
			}
		}
		public function ambil_data_asesor(){
			$asesor = $this->cek_nira_asesor_dosen_uin();
			foreach ($asesor as $as) {
				$nm_dosen = $as;
				echo "<pre>";
				$query = "insert INTO DATABASE (JENIS_KEGIATAN) VALUES (Mengasesori dosen atas nama :'".$nm_dosen."')";
				echo $query;
				echo "<pre>";
			}
		}
		//MENGAMBIL DATA ASESOR DARI TABEL BKD
		public function data_asesor_dosen_bkd(){
			$data['ta'] = $this->session->userdata('ta');
			$data['smt'] = $this->session->userdata('smt');
			$api_url 	= URL_API_BKD.'bkd_remun/get_data_asesor_dosen';
			$parameter  = array('api_search' => array($data['ta'], $data['smt']));
			$data['asesor_dosen'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			echo "<pre>";
			print_r($data['asesor_dosen']);
			echo "<pre>";
		}
		public function get_data_asesor_dosen_by_nip(){
			$data['kode'] = $this->session->userdata('kd_dosen');
			//$data['kode'] = '198205112006042002';
			$data['ta'] = $this->session->userdata('ta');
			$data['smt'] = $this->session->userdata('smt');
			$api_url 	= URL_API_BKD.'bkd_remun/get_data_asesor_dosen_by_nip';
			$parameter  = array('api_search' => array($data['kode'], $data['ta'], $data['smt']));
			$nira = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			return $nira;
		}
		/* create by TF*/
	/*function getFileBlob($table, $kolom, $kode){
		$get_foto = $this->blob->getBlob($table, $kolom, $kode);
		$foto1 = base64_decode($get_foto[0][$kolom]);
		$n1 = substr($foto1, -200);
		$n2  = substr($foto1, 0, strlen($foto1) - 200);
		if($kolom == 'BLOB_PENUGASAN'){ $field = 'FILE_PENUGASAN'; }else{ $field = 'FILE_CAPAIAN'; }
		$ext = $this->blob->extensi('BKD_REMUN_KINERJA', $field, $kode);	
		#echo $ext;
		if($ext == 'pdf' || $ext == 'PDF'){
			header("Content-type: application/pdf");
			echo base64_decode($n1.$n2);			
		}else{
			header("Content-type: image/jpg");
			echo base64_decode($n1.$n2);
		}
	}*/
	/* create by TF*/
	function getFileBlob($table, $kolom, $kode){
		$get_foto = $this->getblob_bkd_remun($table, $kolom, $kode);
		$foto1 = base64_decode($get_foto[0][$kolom]);
		$n1 = substr($foto1, -200);
		$n2  = substr($foto1, 0, strlen($foto1) - 200);
		if($kolom == 'BLOB_PENUGASAN'){ $field = 'FILE_PENUGASAN'; }else{ $field = 'FILE_CAPAIAN'; }
		//$ext = $this->blob->extensi('BKD_REMUN_KINERJA', $field, $kode);
		$ext = $this->getextensi_bkd_remun('BKD_REMUN_KINERJA', $field, $kode);	
		#echo $ext;
		if($ext == 'pdf' || $ext == 'PDF'){
			header("Content-type: application/pdf");
			echo base64_decode($n1.$n2);			
		}else{
			header("Content-type: image/jpg");
			echo base64_decode($n1.$n2);
		}
	}
	function getblob_bkd_remun($table, $kolom, $kode){
		$api_url 	= URL_API_BKD.'bkd_remun/getblob_bkd_remun';
		$parameter  = array('api_search' => array($table, $kolom, $kode));
		$getblob = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		return $getblob;
	}
	function getextensi_bkd_remun($table, $kolom, $kode){
		$api_url 	= URL_API_BKD.'bkd_remun/getextensi_bkd_remun';
		$parameter  = array('api_search' => array($table, $kolom, $kode));
		$get = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		return $get;
	}
	/* create by TF*/
	function cetak(){
		$this->output99->output_display('dosen/form_report_remun');
	}
	function cek_input_nilai(){
		$kd_kelas = "22607KX10436658"; 
		$data['berkas'] = $this->api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST', 
								array('api_kode'=>64005, 'api_subkode' => 4, 'api_search' => array($kd_kelas)));
		echo "<pre>";
		print_r($data['berkas']);
		echo "<pre>";
	}
	function cek_summary_remun_dosen($kd_dosen, $kd_smt, $kd_ta){
		$api_url 	= URL_API_BKD.'bkd_remun/cek_summary_remun_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $kd_smt, $kd_ta));
		$get = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		return $get;
	}
	function cek_no_sertifikasi_dosen($kd_dosen){
		//$data['kd_dosen'] = $this->session->userdata('kd_dosen');
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd_dosen));
		$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		foreach ($data['dosen'] as $key) {
			$no_sertifikat = $key->NO_SERTIFIKAT;
		}
		return $no_sertifikat;
	}
	function tf(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		//$kd_dosen = '198505140000001301';
		$cek = $this->cek_no_sertifikasi_dosen($kd_dosen);
		if($cek==''){
			echo "dosen non sertifikasi";
		}else{
			echo "dosen sertifikasi";
		}
	}

	function auto_data_pendidikan(){
		// session_flashdata : buat identifier di bebankerja
		$flash_bebankerja = $this->session->flashdata('bebankerja');

		if(!$flash_bebankerja){
			$this->session->set_flashdata('remunerasi', true);
			redirect(base_url('bkd/dosen/bebankerja/data_penawaran_kelas'));
		}	
	}

	function auto_data_penelitian(){
		// session_flashdata : buat identifier di bebankerja
		$flash_bebankerja = $this->session->flashdata('bebankerja');

		if(!$flash_bebankerja){
			$this->session->set_flashdata('remunerasi', true);
			redirect(base_url('bkd/dosen/bebankerja/auto_penelitian_for_remun'));
		}	
	}

	function auto_data_penunjang(){
		// session_flashdata : buat identifier di bebankerja
		$flash_bebankerja = $this->session->flashdata('bebankerja');

		if(!$flash_bebankerja){
			$this->session->set_flashdata('remunerasi', true);
			redirect(base_url('bkd/dosen/bebankerja/auto_penunjang_for_remun'));
		}	
	}
	
}
