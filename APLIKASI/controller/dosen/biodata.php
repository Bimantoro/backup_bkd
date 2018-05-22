<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Biodata extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('rbkd_lib_setting','','setting_r');
		$this->load->library('bkd_lib_setting','','setting');
		$this->load->library('bkd_lib_history','','history');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}
	
	function index(){
		$kd_dosen 	= $this->session->userdata('kd_dosen');
		$kd_ta 	= $this->session->userdata('kd_ta');
		$kd_smt 	= $this->session->userdata('kd_smt');
		$kd_prodi 	= $this->session->userdata('kd_prodi');		
		$_ctas = $this->setting->_cek_status_akhir_smt($kd_ta, $kd_smt); #echo $_ctas;
		
		# session status dosen
		$this->session->unset_userdata('jenis_dosen');
		$this->session->set_userdata('jenis_dosen', $this->history->_status_DS($kd_dosen, $kd_ta, $kd_smt));
		
		if($_ctas == 1){
			$api_url 	= URL_API_SIA.'sia_master/data_search';
			$parameter  = array(
							'api_kode' 		=> 19000,
							'api_subkode' 	=> 4,
							'api_search' 	=> array($kd_prodi)
						);
			$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data)){
				foreach ($data as $d);
				# execution
				$api_url 	= URL_API_BKD.'bkd_dosen/update_fak_dosen';
				$parameter  = array('api_search' => array($kd_dosen, $d->KD_FAK));
				$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}else{
				# CEK SK NGAJAR DOSEN		
				$api_url 	= URL_API_SIA.'sia_dosen/data_search';
				$parameter  = array('api_kode'=> 35000,'api_subkode'=> 1,'api_search'=> array($kd_dosen));		
				$x = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(empty($x)) $data['pesan'] = 'Mohon maaf, Anda belum mempunyai prodi, SK Anda belum diisi, silahkan hubungi petugas yang bersangkutan';
				else $data['pesan'] = 'Mohon maaf, data prodi Anda belum terdaftar dalam sistem, silakan hubungi petugas PTIPD';
				$data['status_err_bkd'] = 1;
			}
		}
		else{
			$data['pesan'] = 'Pengisian Kinerja Dosen tidak dapat dilakukan, tanggal akhir semester prodi Anda belum disetting.<br/>Silakan hubungi petugas akademik';
			$data['status_err_bkd'] = 1;
		}
		
		$data['namaLengkap'] = $this->getDataDosen($kd_dosen);
		$api_url 	= 'http://service2.uin-suka.ac.id/servsimpeg/index.php/remunerasi_dosen/agregasi/get_jadwal_pengisian_remunerasi_periode_saat_ini';
		$parameter  = array('api_search' => array($kd_ta, $kd_smt, 'remun', 1));
		$data['jadwal_remun'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$parameter  = array('api_search' => array($kd_ta, $kd_smt, 'sertifikasi', 1));
		$data['jadwal_sertifikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);


		/*echo "<pre>";
		print_r($data['jadwal_remun']);
		echo "<pre>";
		die();*/

		# load view
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('dosen/identitas', $data);
	}
	
	
	function edit_biodata(){
		
		$kd_dosen = $this->session->userdata("kd_dosen");
		$ta = $this->session->userdata("ta");
		$smt = $this->session->userdata("smt");
		$kd_ta = $this->session->userdata("kd_ta");
		$kd_smt = $this->session->userdata("kd_smt");
		$kd_prodi = $this->session->userdata("kd_prodi");
		$data['title'] = 'Edit Identitas Dosen';
		
		# get data dosen
		# data di D_DOSEN:
		# sia_dosen/data_search, 20000/1 api_search = array(kd_dosen)
		# simpeg_mix/data_search, 2001/1 api_search = array(kd_pegawai)
		$api_url 	= URL_API_SIA.'sia_dosen/data_search';
		$parameter  = array('api_kode'=>20000, 'api_subkode'=>3, 'api_search' => array($kd_dosen));
		$data['identity'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['identity']); #die();

		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode'=>2001, 'api_subkode'=>2, 'api_search' => array($kd_dosen));
		$data['pegawai'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		#print_r($data['identity']);
		foreach ($data['pegawai'] as $i);
			$data['nm_kec'] = $this->get_nama_kecamatan($i->KD_KEC_RUMAH);
			$data['nm_kab'] = $this->get_nama_kabupaten($i->KD_KAB_RUMAH);
			$data['nm_prop'] = $this->get_nama_propinsi($i->KD_KAB_RUMAH);
		
		$api_url 	= URL_API_BKD.'bkd_dosen/data_asesor_prodi';
		$parameter  = array('api_search' => array($kd_prodi));
		$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_dosen/biografi_dosen/'.$kd_dosen;
		$parameter  = array();
		$data['bio'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		if(!empty($data['identity'])){
			foreach ($data['identity'] as $dt);
			$nama = explode('^',$dt->NM_DOSEN_P);
			$data['gda'] = $nama[0];
			$data['gdna'] = $nama[1];
			$data['gbna'] = $nama[3];
			$data['gba'] = $nama[4];			
		}else{
			$data['gda'] = '';
			$data['gdna'] = '';
			$data['gbna'] = '';
			$data['gba'] = '';			
		}
		
		$data['negara'] = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_master/data_view', 'POST', array('api_kode'=>11001, 'api_subkode' => 2));	

		# get asesor dosen
		$api_url 	= URL_API_BKD.'bkd_dosen/asesor_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $kd_ta, $kd_smt));
		$data['asdos'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		#print_r($data['asdos']); 
		if(!empty($data['asdos'])){
			foreach ($data['asdos'] as $ad);
			$data['kd_ad'] = $ad->KD_AD;
			if($ad->NIRA1 == '') $data['nira1'] = ''; else $data['nira1'] = $ad->NIRA1;
			if($ad->NIRA2 == '') $data['nira2'] = ''; else $data['nira2'] = $ad->NIRA2;
			$data['namaAsesor1'] = $this->getNamaAsesor($ad->NIRA1);
			$data['namaAsesor2'] = $this->getNamaAsesor($ad->NIRA2);
		}else{
			$data['kd_ad'] = '';
			$data['nira1'] = ''; 
			$data['nira2'] = ''; 
			$data['namaAsesor1'] = ''; 
			$data['namaAsesor2'] = ''; 
		}
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>13000, 'api_subkode' => 6, 'api_search' => array());
		$data['kecamatan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$kec = array();
		foreach ($data['kecamatan'] as $a){
			$kec[] = array('value' => $a->NM_KEC, 'data'=>$a->KD_KEC.'#'.$a->KD_KAB.'#'.$a->NM_KAB.'#'.$a->KD_PROP.'#'.$a->NM_PROP);
		}
		$data['kec'] = json_encode($kec);

		# load view 
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('dosen/form_identitas',$data);
	
	}

	function update_biodata(){

		$this->form_validation->set_error_delimiters('<li class="error"><span>&times;</span>','</li>');
		$this->form_validation->set_rules('nama','<b>Nama Dosen</b>','required|xss_clean');
		

		if ($this->form_validation->run() == FALSE){
			$this-> edit_biodata();
		}
		else{
			# set parameters 
			$aplikasi_id = 'BKD';
			$kd_dosen = $this->security->xss_clean($this->session->userdata("kd_dosen"));
			$nip_eksekutor = $kd_dosen;
			$telp_rumah = $this->input->post("telp_rumah");
			$telp_hp = $this->input->post("mobile");
			$email = $this->input->post("email");
			$alamat = $this->input->post("alamat");
			$rt = $this->input->post("rt");
			$rw = $this->input->post("rw");
			$desa = $this->input->post("desa");
			$kd_kec = $this->input->post("kd_kec");
			$kd_kab = $this->input->post("kd_kab");
			$kd_prop = $this->input->post("kd_prop");
			$kd_negara = $this->input->post("kd_negara");
			$deskripsi = $this->input->post("deskripsi");
			$kodepos = (string)$this->input->post("pos");
			
			$api_url = URL_API_SIMPEG1.'simpeg_mix/data_procedure';
			/*array($aplikasi_id, $nip_eksekutor, '3', $kd_dosen, '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', $telp_hp, $telp_rumah, '', '', $email, '', '','','','','', $alamat, $rt, $rw, '', $desa, $kd_kec, $kd_kab, $kd_prop, $kd_negara, $kodepos, '')*/
			
			$arr_dp = array('',$aplikasi_id, $nip_eksekutor,'SPBKD1',
							$kd_dosen,'','','','', '',
							'','','','','', '',
							$alamat,$kodepos,$rt,$rw,'', $desa,'',$kd_kec,'',$kd_kab, $kd_prop,$kd_negara,
							
							$telp_hp,'',$telp_rumah,'','',
							'','',
							$email,'',
							'','','','',
							
							'','','','','', '','','','','', '','',
							
							);
			$parameter = array('api_kode' => 2001, 'api_subkode' => 1, 
								'api_datapost'=> $arr_dp);
			$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			# print_r($simpan); die();
			# parameter bkd
			$a = $this->input->post("thn_tunj_profesi");
			$b = $this->input->post("thn_tunj_kehormatan");

			# parameters for asesor
			$kd = $this->input->post("kd_ad");
			$asdos = $this->input->post('asesor');
			if($asdos !== ''){
				$as = explode('<$>', $asdos);
				if(count($as) >= 2){
					$nira1 = $as[0];
					$nira2 = $as[1];
				}else{
					$nira1 = $as[0];
					$nira2 = '';
				}
			}else{
				$nira1 = '';
				$nira2 = '';
			}
			# update identitas bkd
			$api_url 	= URL_API_BKD.'bkd_dosen/update_identitas_bkd';
			$parameter  = array('api_search' => array($kd_dosen, $a, $b));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			#update biografi dosen
			$api_url 	= URL_API_BKD.'bkd_dosen/update_biografi_dosen';
				$di['kd_dosen']=$kd_dosen;
				$di['deskripsi']=$deskripsi;
				if(!empty($_FILES['file_cv']['tmp_name'])){
					$blobdata = base64_encode(file_get_contents($_FILES['file_cv']['tmp_name']));
					$di['FILE_CV']=$blobdata;
					$di['NAMA_FILE_CV']=$_FILES['file_cv']['name'];
				}
			$parameter  = array('api_search' => $di);
			#print_r($parameter);
			$dat=$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			#update biografi dosen
			$api_url 	= URL_API_BKD.'bkd_dosen/update_biografi_dosen';
			$parameter  = array('api_search' => array($kd_dosen, $a, $b));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# update ASESOR DOSEN
			#if($asdos !== ''){
				$api_url 	= URL_API_BKD.'bkd_dosen/update_asesor_dosen';
				$parameter  = array('api_search' => array($kd, $nira1, $nira2));
				$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			#}
		}
		redirect ('bkd/dosen/biodata/edit_kepegawaian');
	}
	
	function asesor($nira){
		$data['title'] = 'Data Asesor';
		$nira = $this->security->xss_clean($this->uri->segment(5));
		$api_url 	= URL_API_BKD.'bkd_dosen/biodata_asesor';
		$parameter  = array('api_search' => array($nira));
		$data['data_asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		# load view 
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('dosen/data_asesor',$data);
	}
	
	function getNamaAsesor($nira){
		$api_url 	= URL_API_BKD.'bkd_dosen/biodata_asesor';
		$parameter  = array('api_search' => array($nira));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($data)){
			return $data[0]->NM_ASESOR;
		}else{
			return $nira;
		}
	}
	
	function simpan_pendidikan_dosen(){
		$data = array(
			'KD_DOSEN'	=> $this->input->post('KD_DOSEN'),
			'JENJANG'	=> $this->input->post('JENJANG'),
			'NM_PT'		=> $this->input->post('NM_PT1'),
			'JURUSAN'	=> $this->input->post('JURUSAN1'),
			'TANGGAL_MASUK'	=> $this->input->post('MASUK1'),
			'TANGGAL_LULUS'	=> $this->input->post('LULUS1'),
		);
		$api_url 	= URL_API_BKD.'bkd_dosen/simpan_jenjang_pendidikan';
		$parameter  = array('api_search' => $data);
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
	}
	
	
	# TAMBAHAN MARGE APLIKASI DENGAN SIPKD 
	# DATA KEPEGAWAIAN 
	
	function edit_kepegawaian(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');
		
		# biodata
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		#$api_url 	= URL_API_BKD.'sia_dosen/data_search';
		$parameter  = array('data_search' => array($kd_dosen));
		$data['identity'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$api_url 	= URL_API_SIA.'sia_dosen/data_search';
		$parameter  = array('api_kode'=>20000, 'api_subkode'=>3, 'api_search' => array($kd_dosen));
		$data['identity2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode'=>2001, 'api_subkode'=>2, 'api_search' => array($kd_dosen));
		$data['pegawai'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# GET JABATAN FUNGSIONAL DAN GOLONGAN PER SEMESTER
		$api_url_jabatan 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 1122, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		$data['fungsional'] = $this->s00_lib_api->get_api_jsob($api_url_jabatan,'POST',$parameter);
		//print_r($data['fungsional']);
		if(!empty($data['fungsional'])){ 
			foreach($data['fungsional'] as $fun);
			$data['fungsional_dosen'] = $fun->FUN_NAMA;
		}else{
			$data['fungsional_dosen'] = '';
		}
		
		$parameter  = array('api_kode' => 1123, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		$data['golongan'] = $this->s00_lib_api->get_api_jsob($api_url_jabatan,'POST',$parameter);
		if(!empty($data['golongan'])){ 
			foreach($data['golongan'] as $gol);
			$data['golongan_dosen'] = $gol->HIE_GOLONGAN;
			$data['ruang_dosen'] = $gol->HIE_RUANG;
		}else{
			$data['golongan_dosen'] = '';
			$data['ruang_dosen'] = '';
		}
			

		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('dosen/form_kepegawaian',$data);
	}
	
	function update_kepegawaian(){
		# parameter data kepegawaian dosen
		$kd_dosen = $this->session->userdata('kd_dosen');
		$a = $this->input->post('kementrian_induk');
		$b = $this->input->post('status_peg');
		$c = $this->input->post('no_kartu');
		$d = $this->input->post('tmt_golongan');
		$e = $this->input->post('tmt_jabatan');
		$f = $this->input->post('no_sertifikat');
		$api_url 	= URL_API_BKD.'bkd_dosen/update_kepegawaian_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $a, $b, $c, $d, $e, $f));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($parameter); die();
		redirect ('bkd/dosen/biodata/edit_akademik');
	}
	
	
	#sia_master/data_view, 17000, 18000, 19000
	function edit_akademik(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');
		# biodata
		$api_url 	= URL_API_SIA.'sia_dosen/data_search';
		$parameter  = array('api_kode'=>20000, 'api_subkode'=>3, 'api_search' => array($kd_dosen));
		$data['identity'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		# get data_prodi 
		$api_url 	= URL_API_SIA.'sia_master/data_view';
		$parameter  = array('api_kode' => 17000, 'api_subkode'=>1, 'data_search' => array());
		$data['prodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		# fakultas
		$data['namaFak'] = $this->namaFak($this->session->userdata('kd_fak'));

		# sia_master/data_search, 16000/1, api_search = array($kode)
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' => 16000, 'api_subkode'=>1, 'api_search'=> array('202-004'));
		$data['uni'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['uni']);
		
		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($this->session->userdata('jenis_dosen'));
		
		# GET JENJANG PENDIIDKAN DOSEN (TIMELINE)
		# =========================================================================================
		$api_url	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2102, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		$data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		# PENDIDIKAN S1 - S3
		if(!empty($data['pendidikan'])){
			$data['ps1'] = '';
			$data['ps2'] = '';
			$data['ps3'] = '';

			foreach ($data['pendidikan'] as $dt){
				if($dt->NM_JENIS == 'S1'){
					$data['ps1'] = $dt->KONSENTRASI.' '.$dt->NM_SEKOLAH;
				}else if($dt->NM_JENIS == 'S2'){
					$data['ps2'] = $dt->KONSENTRASI.' '.$dt->NM_SEKOLAH;
				}else if($dt->NM_JENIS == 'S3'){
					$data['ps3'] = $dt->KONSENTRASI.' '.$dt->NM_SEKOLAH;
				}
			}
		}else{
			# get riwayat pendidikan
			$api_url 	= URL_API_BKD.'bkd_dosen/riwayat_pendidikan_dosen';
			$parameter  = array('api_search' => array($kd_dosen));
			//print_r ($parameter);
			$data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			//print_r ($data['pendidikan']);
			
			foreach($data['pendidikan'] as $pen){
				$data['ps1'] = '';
				$data['ps2'] = '';
				$data['ps3'] = '';
			
				if($pen->JENJANG == 'S1'){
					$data['ps1'] = $pen->BIDANG_ILMU.' - '.$pen->NM_PT;
				}else if($pen->JENJANG == 'S2'){
					$data['ps2'] = $pen->BIDANG_ILMU.' - '.$pen->NM_PT;
				}else if($pen->JENJANG == 'S3'){
					$data['ps3'] = $pen->BIDANG_ILMU.' - '.$pen->NM_PT;
				}
			}
		}
		
		$this->output99=$this->s00_lib_output;
		$this->output99->output_display('dosen/form_akademik',$data);
	}
	
	function update_akademik(){
		# parameter data kepegawaian dosen
		$kd_dosen = $this->session->userdata('kd_dosen');
		$a = $this->input->post('kd_jenis_dosen');
		$b = $this->input->post('tahun_gubes');
		# set session tahun gubes 
		$this->session->unset_userdata('thn_prof');
		$this->session->set_userdata('thn_prof',$b);
		
		$api_url 	= URL_API_BKD.'bkd_dosen/update_akademik_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $a, $b));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($parameter); die();
		redirect ('bkd/dosen/biodata/edit_riwayat_pendidikan');
	}
	
	function edit_riwayat_pendidikan($id = null){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');

		if ($id !== null || $id !== ''){
			$api_url 	= URL_API_BKD.'bkd_dosen/current_riwayat_pendidikan';
			$parameter  = array('api_search' => array($id));
			$data['current'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}

		$api_url 	= URL_API_BKD.'bkd_dosen/riwayat_pendidikan_dosen';
		$parameter  = array('api_search' => array($kd_dosen));
		$data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#api_kode = 2*/
		#102, api_subkode = 2, api_search = array(ta, smt, kd_pgw)
		
		
		// $api_url	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		// $parameter  = array('api_kode' => 2102, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		// $data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		// #print_r($data['pendidikan']); die();
		
		// $data['negara'] = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_master/data_view', 'POST', array('api_kode'=>11001, 'api_subkode' => 2));	
		
		$this->output99->output_display('dosen/form_pendidikan',$data);
	}
	
	function simpan_riwayat_pendidikan(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$b = $this->input->post('nm_prodi');
		$c = $this->input->post('bidang_ilmu');
		$d = $this->input->post('jenjang');
		$e = $this->input->post('nm_pt');
		$f = $this->input->post('kd_negara');
		$g = $this->input->post('tanggal_masuk');
		$h = $this->input->post('tanggal_lulus');
		$i = $this->input->post('gelar');
		$j = $this->input->post('sumber_dana');

		$api_url 	= URL_API_BKD.'bkd_dosen/simpan_riwayat_pendidikan';
		$parameter  = array('api_search' => array($kd_dosen, $b, $c, $d, $e, $f, $g, $h, $i, $j));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($parameter); die();

		redirect ('bkd/dosen/biodata/edit_riwayat_pendidikan');
	}

	function update_riwayat_pendidikan(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$b = $this->input->post('nm_prodi');
		$c = $this->input->post('bidang_ilmu');
		$d = $this->input->post('jenjang');
		$e = $this->input->post('nm_pt');
		$f = $this->input->post('kd_negara');
		$g = $this->input->post('tanggal_masuk');
		$h = $this->input->post('tanggal_lulus');
		$i = $this->input->post('gelar');
		$j = $this->input->post('sumber_dana');

		$api_url 	= URL_API_BKD.'bkd_dosen/update_riwayat_pendidikan';
		$parameter  = array('api_search' => array($kd_dosen, $b, $c, $d, $e, $f, $g, $h, $i, $j));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($parameter); die();

		redirect ('bkd/dosen/biodata/edit_riwayat_pendidikan');
	}
	
	function delete_riwayat_pendidikan($kd){
		$kd = $this->security->xss_clean($this->uri->segment(5));

		$api_url 	= URL_API_BKD.'bkd_dosen/delete_riwayat_pendidikan';
		$parameter  = array('api_search' => array($kd));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		redirect ('bkd/dosen/biodata/edit_riwayat_pendidikan');
	}
	
	
		
	function test(){
		$nim = $_GET['q'];
		if(strlen($nim) < 3){ $nim = 'UNDEFINED'; }else{ $nim = $nim; }
		$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
		$parameter  = array('api_kode' =>26000, 'api_subkode' => 26, 'api_search' => array($nim));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	function get_kecamatan(){
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>13000, 'api_subkode' => 6, 'api_search' => array());
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$new_data = array();
		foreach ($data as $dt){
			$new_data[] = array('value' =>	$dt->NM_KEC, 'data' =>	$dt->KD_KEC);
		}
		echo json_encode($new_data);
		#print_r($new_data);
	}
	
	function get_asesor(){
		if(isset($_GET['q'])){
			$q = strtolower(str_replace("'", "''", $_GET['q']));
			$tot = count(str_split($q));
			if($tot > 1){
				$api_url 	= URL_API_BKD.'bkd_dosen/asesor';
				$parameter 	= array('api_kode' => 9999, 'api_subkode' => 1, 'api_search' => array($q));
				$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				#print_r($data); die();
				$new_data = array();
				foreach ($data as $dt){
					$new_data[] = array('id' =>	$dt->NIRA, 'name'	=>	$dt->NM_ASESOR.' ('.$dt->NIRA.')');
				}
				echo json_encode($new_data);
			}
		}
	}
	
	function update_alamat(){
		$api_url = URL_API_SIMPEG1.'simpeg_mix/data_procedure';
		$arr_dp = array('',$aplikasi_id, $nip_eksekutor,'SPBKD1',
							$kd_dosen,'','','','', '',
							'','','','','', '',
							$alamat,$kodepos,$rt,$rw,'', $desa,'',$kd_kec,'',$kd_kab, $kd_prop,$kd_negara,
							
							$telp_hp,'',$telp_rumah,'','',
							'','',
							$email,'',
							'','','','',
							
							'','','','','', '','','','','', '','',
							
							);
		$parameter = array('api_kode' => 2001, 'api_subkode' => 1, 'api_datapost'=> $arr_dp);
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
	}

	
	function get_nama_kecamatan($kd_kec){
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>13000, 'api_subkode' => 3, 'api_search' => array($kd_kec));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($data)){
			foreach ($data as $kec);
			return $kec->NM_KEC;
		}else{
			return '';
		}
	}
	function get_nama_kabupaten($kd_kab){
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>13000, 'api_subkode' => 4, 'api_search' => array($kd_kab));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($data)){
			foreach ($data as $kab);
			return $kab->NM_KAB;
		}else{
			return '';
		}
	}
	function get_nama_propinsi($kd_kab){
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>13000, 'api_subkode' => 4, 'api_search' => array($kd_kab));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($data)){
			foreach ($data as $kab);
			return $kab->NM_PROP;
		}
		else{
			return '';
		}
	}
	
	
	# DISPENSASI PENGISIAN DATA BKD
	# =================================================================
	
	function dispensasi(){
		#sia_sistem/data_view, 200002/1
		$api_url 	= URL_API_SIA.'sia_sistem/data_view';
		$parameter  = array('api_kode' =>200002, 'api_subkode' => 1, 'api_search' => array());
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	
	# CEK AKHIR SEMESTER PRODI BERSANGKUTAN
	#16001/1, api_search = array(kd_ta, kd_smt, kd_prodi)
	function cek_tanggal_akhir_smt(){
		$kd_ta 		= $this->session->userdata('kd_ta');
		$kd_smt 	= $this->session->userdata('kd_smt');
		$kd_prodi 	= $this->session->userdata('kd_prodi');
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		echo $data[0]->T_SMT_AKT_1_F."<hr/>".$data[0]->T_SMT_AKT_2_F;
	}
	
	

	function status(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');
		$api_url_jabatan 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 1122, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		$data = $this->s00_lib_api->get_api_jsob($api_url_jabatan,'POST',$parameter);
		print_r($data);
	}

	function ceknip(){
		echo $this->setting->_generate_nip('197701032005011003');
	}


	function cari(){
		$this->output99->output_display('dosen/cariDosen');
	}



    function kode(){
        echo md5('secret');
    }


	function getDataDosen($kd){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2001, 'api_subkode' => 2, 'api_search' => array($kd));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data;
	}

	function historiPT(){
		#$mhs = $this->biodataMhs($nim);
		#101006/7, api_search = array($tanggal, $kode_ptn)
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter 	= array('api_kode' => 101006, 'api_subkode' => 7, 'api_search' => array(date('d/m/Y'), '202-004'));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
		return ($data);
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





	
	/* file location : ../application/controller/dosen/biodata
	 * last modified : 19-12-2013
	 * author : sabbana */
	 
	 
}










