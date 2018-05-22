<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Bebankerja extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->load->library('bkd_lib_setting','','setting');
		$this->load->library('bkd_lib_history','','history');
		$this->load->library('bkd_upload_blob','','blob');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}
	
	function test(){
		$kd_dosen = '22607';
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		# KODE PENELITIAN = 'B', PENGABDIAN MASYARAKAT = 'C', PENUNJANG = 'D'
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/bebankerja';
		$parameter  = array('api_kode'=>2000, 'api_subkode' => 5, 'api_search' => array('B', $kd_dosen, $ta, $smt));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	function index(){
		#tampilkan beban kerja ang tlah diambil
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		$data['kode'] = 'A';
		
		$kd_ta = $this->setting->_generate_kd_ta($ta);
		$kd_smt = $this->setting->_generate_kd_smt($smt);
		
		$data['is_crud'] = $this->setting->_is_crud_bkd_lalu($kd_ta, $kd_smt);
		if($data['is_crud'] == 1){
			$data['tombol'] = 'style="display:block"';
		}else{
			$data['tombol'] = 'style="display:none"';
		}
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $ta, $smt));
		$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# load view
		$this->output99->output_display('dosen/daftar_beban_kerja',$data);
	}
	
	/* Load Form Beban Kerja Dosen
		 * Pendidikan
		 * Penelitian
		 * Pengabdian Masyarakat dll
	*/
	
	function data($jenis, $detail = null, $action = null){
			
		$jenis = $this->security->xss_clean($this->uri->segment(5));
		$data['kode'] = $jenis;
		# set parameters 
		$kd_dosen = $this->session->userdata("kd_dosen");
		$thn = $this->session->userdata("ta");
		$smt = $this->session->userdata("smt");
		$status = $this->session->userdata("jenis_dosen");
		
		$kd_ta = $this->setting->_generate_kd_ta($thn);
		$kd_smt = $this->setting->_generate_kd_smt($smt);
		
		 $data['is_crud'] = $this->setting->_is_crud_bkd_lalu($kd_ta, $kd_smt);
		/*if($data['is_crud'] == 1){
			$data['tombol'] = 'style="display:block"';
		}else{
			$data['tombol'] = 'style="display:none"';
		} */
				
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_jadwal';
		$parameter  = array();
		$jadwal = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($jadwal)){
			$data['tombol'] = 'style="display:block"';
		}else{
			$data['tombol'] = 'style="display:none"';
			$data['tombol2'] = 'style="display:"';
		}	
		
		if ($detail !== null){
			if ($action !== ''){
				switch ($action){
					case 'penugasan-isi': $data['view'] = 'dosen/isi_dokumen'; break;
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
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_bkd';
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
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_bkd';
			$parameter  = array('api_search' => array($detail, $data['kode']));
			$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'], $kd_dosen, $thn, $smt));
		$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['data_beban']);
		# load view
		$this->output99->output_display('dosen/daftar_beban_kerja',$data);
	}
	
	function narasumberxxxx(){
		$kd_dosen = '197701032005011003';
		$kd_ta = '2014'; $kd_smt = '1';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode' => 2000, 'api_subkode' => 2, 'api_search' => array('F', $kd_dosen, $kd_ta, $kd_smt));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}

	/* 
		form hybrid (tambah, lihat, update, dan delete)
		dilihat dari segi User Interface dengan layout yang telah ditentukan
		rasanya kurang enak, akan tetapi demi memenuhi requirement user yang 
		telah terbiasa dengan interface yang seperti ini, mungkin akan tetap
		dipertahankan.
	*/
	
	function tambah($kode){

		$kode = $this->security->xss_clean($this->uri->segment(5));
		$data['kode'] = $kode;
		# set parameters 
		$kd_dosen = $this->session->userdata("kd_dosen");
		$thn = $this->session->userdata("ta");
		$smt = $this->session->userdata("smt");
		$status = $this->session->userdata("jenis_dosen");
		
		if($data['kode'] == 'D' && ($status == 'PR' || $status == 'DS')){
			# load view
			$this->output99=$this->s00_lib_output;
			$this->output99->output_display('dosen/beban_tambahan_message');
		}else{	
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, $smt));
			$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# cek jenis beban kerja 
			if($kode == 'F'){
				$data['title'] = "Narasumber/Pembicara Kegiatan";
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/tingkat';
				$parameter  = array('api_kode'=>1000, 'api_subkode'=>1, 'api_search' => array());
				$data['kategori'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			}else if($kode == 'G'){				
			
			}else if($kode == 'H'){				
				$data['jenis_haki'] = $this->s00_lib_api->get_api_jsob(URL_API_BKD.'bkd_beban_kerja/get_jenis_haki','POST',array());	
			}else{
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_kategori';
				$parameter  = array('api_search' => array($data['kode']));
				$data['kategori'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}
			# load view
			$this->output99->output_display('dosen/form_hybrid_dosen',$data);
		}
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
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/history_data_bebankerja_prof';
			$parameter  = array('api_search' => array($kd, $data['ta'], $data['smt']));
			$data['data_beban_prof'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			if($param == 'detail'){
				$kd = $this->uri->segment(6);
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
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_bebankerja_prof';
					$parameter  = array('api_search' => array($kd));
					$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_bebankerja_prof';
				$parameter  = array('api_search' => array($kd));
				$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}
			
			$this->output99->output_display('dosen/daftar_beban_kerja_prof',$data);
		}else{
			$this->output99->output_display('dosen/beban_profesor_message',$data);
		}
		#echo $this->session->userdata("jenis_dosen");
	}
	
	function input_prof(){
		$kd = $this->session->userdata("kd_dosen");
		$data['ta'] = $this->session->userdata("ta");
		$data['smt'] = $this->session->userdata("smt");
		$thn_prof = $this->session->userdata("thn_prof");
		$data['tahun'] = $this->security->xss_clean($this->input->post("thn"));
		$data['kode'] = 'E';
			
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_kategori';
			$parameter  = array('api_search' => array($data['kode']));
			$data['kategori'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja_prof';
		$parameter  = array('api_search' => array($kd, $thn_prof));
		$data['data_beban_prof'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$this->output99->output_display('dosen/form_hybrid_prof',$data);	
	}
	
	function history_prof(){
		$kd = $this->session->userdata("kd_dosen");
		$data['ta'] 	= $this->input->post('thn');
		$data['smt'] 	= $this->input->post('smt');
		$data['title'] = "Kewajiban Khusus Profesor";

		$api_url = URL_API_BKD.'bkd_beban_kerja/history_data_bebankerja_prof';
		$parameter  = array('api_search' => array($kd, $data['ta'], $data['smt']));
		$data['data_beban_prof'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$this->output99->output_display('dosen/history_data_prof',$data);	
	}
	
	/*
		menampilkan daftar beban kerja serta form yang digunakan 
		untuk melakukan edit data beban kerja yang terpilih
	*/
	
	function edit(){
		/* set parameters */
		$kode = $this->security->xss_clean($this->uri->segment(6));
		$data['kode'] = $this->security->xss_clean($this->uri->segment(5));
		$kd_dosen = $this->session->userdata("kd_dosen");
		$thn = $this->session->userdata("ta");
		$smt = $this->session->userdata("smt");

		/* load data form */
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
		$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, $smt));
		$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_bkd';
		$parameter  = array('api_search' => array($kode, $data['kode']));
		$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 
		# print_r($data['current_data']); echo $data['kode'];
		
		# cek jenis beban kerja 
		if($data['kode'] == 'F'){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/tingkat';
			$parameter  = array('api_kode'=>1000, 'api_subkode'=>1, 'api_search' => array());
			$data['kategori'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);		
		}else if($data['kode'] == 'H'){				
			$data['jenis_haki'] = $this->s00_lib_api->get_api_jsob(URL_API_BKD.'bkd_beban_kerja/get_jenis_haki','POST',array());	
		}else{
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_kategori';
			$parameter  = array('api_search' => array($data['kode']));
			$data['kategori'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		/* 		echo"<pre>";
		print_r($data['data_beban']);
			print_r($data['current_data']);
			echo"</pre>"; */
		}
		$this->output99->output_display('dosen/form_hybrid_dosen',$data);
	}
	function move(){
		$kd_bk = $this->uri->segment(6);
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_status_pakai';
		$parameter  = array('api_search'=>array($kd_bk));
		$jadwal = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($jadwal);
		redirect('bkd/dosen/bebankerja/data/A/');
	}
	
	function edit_bkp(){
		/* set parameters */
		$kd = $this->session->userdata("kd_dosen");
		$thn_prof = $this->session->userdata("thn_prof");
		$kode = $this->uri->segment(5);
		$data['kode'] = 'E';
		# data beban
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja_prof';
		$parameter  = array('api_search' => array($kd, $thn_prof));
		$data['data_beban_prof'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# current data
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_bebankerja_prof';
		$parameter  = array('api_search' => array($kode));
		$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			# cek jenis beban kerja 
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_kategori';
			$parameter  = array('api_search' => array($data['kode']));
			$data['kategori'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$this->output99->output_display('dosen/form_hybrid_prof',$data);
	}
	
		
	function direct_beban($kode){
		if ($kode == "A"){
			redirect ('bkd/dosen/bebankerja/data/A');
		}else if ($kode == "B"){
			redirect ('bkd/dosen/bebankerja/data/B');
		}else if ($kode == "C"){
			redirect ('bkd/dosen/bebankerja/data/C');
		}else if ($kode == "D"){
			redirect ('bkd/dosen/bebankerja/data/D');
		}else if ($kode == "F"){
			redirect ('bkd/dosen/bebankerja/data/F');
		}else if ($kode == "H"){
			redirect ('bkd/dosen/bebankerja/data/F');
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
	
	# simpan beban kerja form hybrid  ==========================================================================================
	# ==========================================================================================================================
	
	function simpan_bk_hybrid(){
		
		$this->form_validation->set_rules('jenis_kegiatan','<b>Jenis Kegiatan</b>','required|xss_clean');
		$this->form_validation->set_rules('sks1','<b>Beban Kerja SKS</b>','xss_clean');
		$this->form_validation->set_rules('masa','<b>Masa Penugasan</b>','required|xss_clean');
		$this->form_validation->set_rules('bukti_dokumen','<b>Bukti Dokumen</b>','xss_clean');
		$this->form_validation->set_rules('sks2','<b>Bukti SKS</b>','xss_clean');
		$this->form_validation->set_rules('rekomendasi','<b>Rekomendasi</b>','xss_clean');
		$kd = $this->input->post("kd_jbk");
		
		if ($this->form_validation->run() == FALSE){
			$this->direct_hybrid($kd);
		}
		else{		
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
			$jml_mhs = $this->input->post('jml_mhs');
			$jenjang = $this->input->post('jenjang');
			$sks_rule_1 = round($this->aturan_beban_sks2($jenjang, $sks1, $jml_mhs),2);
			$sks_rule_2 = round($this->aturan_beban_sks2($jenjang, $sks2, $jml_mhs),2);


			
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

			$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja';
			$parameter	= array('api_search' => array($a, $b, $c, $d, $sks_rule_1, $f, $g, $sks_rule2, $i, $j, $k, $l, $m, $q, $fp, $fc, $kd_ta, $kd_smt));
			$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# get last id beban kerja
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
				$parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
				$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 

			
			if($a == 'A'){
				# data SIPKD Pendidikan
				$kd_bk = $getid;
				$kd_kat = $this->input->post('kd_kat');
				#$nm_kegiatan = $c;
				$jenjang = $this->input->post('jenjang');
				$tempat = $this->input->post('tempat');
				$jml_mhs = $this->input->post('jml_mhs');

				//$sks_h = $this->input->post("sks2");
				$jml_sks = $this->input->post('jml_sks');
				$jml_dosen = $this->input->post('jml_dosen');
				$jml_tatap_muka = $this->input->post('jml_tatap_muka');
				$jenis_kelas = $this->input->post('jenis_kelas');
				$pertemuan_pm = $this->input->post('pertemuan_pm');
				$nm_prodi = $this->input->post('nama_prodi');
				#SIMPAN DATA PENDIDIKAN (SIPKD)
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan';
				$parameter	= array('api_search' => array($kd_bk, $kd_kat, $c, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi));
				$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
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
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_penelitian';
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
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PENELITIAN', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
					
				}else if($a == 'C'){
					# simpan data pengabdian
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pengabdian';
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
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'PENGABDIAN', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}else if($a == 'H'){
					# simpan data HAKI
					if($id != null){
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_haki';
						$parameter  = array('api_search' => array($id));
						$ch=$data['curr_haki'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
											
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_bkd';
						$parameter  = array('api_search' => array($ch->KD_BK, 'H'));
						$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					}
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_all_dosen';
					$parameter  = array('api_search' => array());
					$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					#$this->output99=$this->s00_lib_output;
					$this->output99->output_display('dosen/form_haki',$data);
					
					$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
					$data_partner = explode('<$>', $partner);
					
					for($a=0; $a<count($data_partner); $a++){
						if($data_partner[$a] !== ''){
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/partner_kinerja';
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
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
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
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/partner_kinerja';
							$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'NARASUMBER', $b, $data_partner[$a]));
							$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}
			}
		
			redirect('bkd/dosen/bebankerja/data/'.$kd);
		}
	}
	
	
	function update_bk(){
		$this->form_validation->set_rules('jenis_kegiatan','<b>Jenis Kegiatan</b>','required|xss_clean');
		$kd = $this->input->post("kd_jbk");
		$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('smt');
		$ta = $this->session->userdata('ta');
		
		if ($this->form_validation->run() == FALSE){
			$this->direct_hybrid($kd);
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

			$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_beban_kerja';
			$parameter	= array('api_search' => array($kd_bk, $a, $b, $c, $d, $e, $f, $k, $l, $m, $q, $kode, $kd_dosen, $smt, $ta, $fp, $fc));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
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

				#SIMPAN DATA PENDIDIKAN (SIPKD)
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_data_pendidikan';
				$parameter	= array('api_search' => array($kd_bk, $kd_kat, $c, $jenjang, $tempat, $jml_mhs, $jml_sks, $jml_dosen, $jml_tatap_muka, $jenis_kelas, $pertemuan_pm, $nm_prodi));
				$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			}else if ($kode == 'B' || $kode == 'C' || $kode == 'F'){
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
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_data_penelitian';
					$parameter	= array('api_search' => array($kd_bk, $bt_mulai, $bt_selesai, $judul_penelitian, $sumber_dana, $jumlah_dana, $kd_kat, $status_peneliti, $laman_publikasi));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

				}else if ($kode == 'F'){
					$judul_acara = str_replace("'","''",$this->input->post('judul_acara'));
					$kd_tingkat = $this->input->post('kd_tingkat');
					$status_peneliti = $this->input->post("status_peneliti");
					$laman_publikasi = $this->input->post("laman_publikasi");
					$lokasi_acara = $this->input->post("lokasi_acara");
					
					$dataNarasumber = array($kd_bk, $bt_mulai, $bt_selesai, $judul_acara, $kd_tingkat, $status_peneliti, $laman_publikasi, $lokasi_acara);
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
					$parameter  = array('api_kode'=>2001, 'api_subkode'=>2, 'api_search' => $dataNarasumber);
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					
					#print_r($parameter);

				}else{
					# simpan data pengabdian
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_data_pengabdian';
					$parameter	= array('api_search' => array($kd_bk, $bt_mulai, $bt_selesai, $judul_penelitian, $sumber_dana, $jumlah_dana, $kd_kat));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
			}
		
			$this->direct_beban($kode);
		}
	}
	
	
	function hapus_hybrid($id){
		$kode = $this->security->xss_clean($this->uri->segment(5));
		$id = $this->security->xss_clean($this->uri->segment(6));
		$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('smt');
		$ta = $this->session->userdata('ta');
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/hapus_beban_kerja';
		$parameter	= array('api_search' => array($id, $kode, $kd_dosen, $smt, $ta));
		$hapus = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		if ($hapus){
			$this->data($kode);
		}else{
			$this->data($kode);
		}
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

		$this->session->set_userdata('ta_bk', $data['thn']);
		$this->session->set_userdata('smt_bk', $data['smt']);
			
		$kd_ta = $this->setting->_generate_kd_ta($this->session->userdata('ta_bk'));
		$kd_smt = $this->setting->_generate_kd_smt($this->session->userdata('smt_bk'));
		
		$kd = $this->session->userdata("kd_dosen");
		$status = $this->session->userdata("jenis_dosen");
		
		$data['is_crud'] = $this->setting->_is_crud_bkd_lalu($kd_ta, $kd_smt);
		#echo $data['is_crud'];
		if($data['is_crud'] == true){
			$data['tombol'] = 'style="display:block"';
		}else{
			$data['tombol'] = 'style="display:none"';
		}
		
		if($data['kode'] == 'D' && ($status == 'PR' || $status == 'DS')){
			# load view
			$this->output99=$this->s00_lib_output;
			$this->output99->output_display('dosen/beban_tambahan_message');
		}
		else{			
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
			$parameter  = array('api_search' => array($data['kode'], $kd, $data['thn'], $data['smt']));
			$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
			if ($show == true){
				$this->output99->output_display('dosen/history_data',$data);
			}else{
				$this->output99->output_display('dosen/form_history',$data);
			}
		}
	}

	# HISTORY PUBLIKASI 
	# ====================================================================================================
	function history_publikasi(){
		
	}
	
	
	/* Modules untuk dosen Professor 
	========================================= */
	function simpan_bkp(){
		$this->form_validation->set_rules('jenis_kegiatan','<b>Jenis Kegiatan</b>','required|xss_clean');
		$this->form_validation->set_rules('sks1','<b>Beban Kerja SKS</b>','xss_clean');
		#$this->form_validation->set_rules('bukti_dokumen','<b>Bukti Dokumen</b>','xss_clean');
		$this->form_validation->set_rules('sks2','<b>Capaian SKS</b>','xss_clean');

		
		if ($this->form_validation->run() == FALSE){
			$this->input_prof();
		}
		else{
			$a = $this->input->post("kd_dosen");
			$b = $this->input->post("kd_jenis");
			$c = $this->input->post("jenis_kegiatan");
			$penugasan = $this->input->post("bukti_penugasan");
			$e = $this->input->post("sks1");
			$f = $this->input->post("lama").' '.$this->input->post("masa");
			$dokumen = $this->input->post("bukti_dokumen");
			$h = $this->input->post("sks2");
			$i = $this->input->post("rekomendasi");
			$j = $this->input->post('tahun');
			$k = $this->input->post('capaian');
			#$l = $this->input->post('file_penugasan');
			#$m = $this->input->post('file_capaian');
			$n = $this->input->post('kd_kat');
			$o = $this->input->post('sumber_dana');
			$p = $this->input->post('jumlah_dana');
			$x = $this->input->post('pl_awal');
			$y = $this->input->post('pl_akhir');
			$q = $x.'-'.$y;
			$r = $this->input->post('laman_publikasi');

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
				
			/* save data */
			$ta = $this->input->post('ta');
			$smt = $this->input->post('smt');
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_prof';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $n, $o, $p, $q, $r, $ta, $smt, $fp, $fc));
			#print_r($parameter); die();
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			redirect('bkd/dosen/bebankerja/profesor');
		}
	}
	
	function update_bkp(){
		
		$this->form_validation->set_rules('jenis_kegiatan','<b>Jenis Kegiatan</b>','required|xss_clean');
		$this->form_validation->set_rules('kd_jenis','<b>Kategori Jenis</b>','required|xss_clean');
		$this->form_validation->set_rules('sks1','<b>Beban Kerja SKS</b>','xss_clean');
		$this->form_validation->set_rules('bukti_dokumen','<b>Bukti Dokumen</b>','xss_clean');
		$this->form_validation->set_rules('sks2','<b>Capaian SKS</b>','xss_clean');
		$this->form_validation->set_rules('rekomendasi','<b>Rekomendasi</b>','xss_clean');
		$this->form_validation->set_rules('tahun','<b>Tahun</b>','xss_clean');
		$kd_dosen = $this->session->userdata('kd_dosen');
		
		if ($this->form_validation->run() == FALSE){
			$this->profesor();
		}
		else{
			$a = $this->input->post("kd_bkp");
			$b = $this->input->post("kd_jenis");
			$c = $this->input->post("jenis_kegiatan");
			$penugasan = $this->input->post("bukti_penugasan");
			$e = $this->input->post("sks1");
			$f = $this->input->post("lama").' '.$this->input->post("masa");
			$dokumen = $this->input->post("bukti_dokumen");
			$h = $this->input->post("sks2");
			$i = $this->input->post("rekomendasi");
			$j = $this->input->post('tahun');
			$k = $this->input->post('capaian');
			#$l = $this->input->post('file_penugasan');
			#$m = $this->input->post('file_capaian');
			$n = $this->input->post('kd_kat');
			$o = $this->input->post('sumber_dana');
			$p = $this->input->post('jumlah_dana');
			$x = $this->input->post('pl_awal');
			$y = $this->input->post('pl_akhir');
			$q = $x.'-'.$y;
			$r = $this->input->post('laman_publikasi');
		
			$ta = $this->input->post('ta');
			$smt = $this->input->post('smt');
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
		
			/* save data */
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_beban_kerja_prof';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $n, $o, $p, $q, $r, $ta, $smt, $kd_dosen, $fp, $fc));
			#print_r($parameter['api_search']); die();
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			redirect('bkd/dosen/bebankerja/profesor');		
		}
	}
	
	function hapus_bkp($id){
		$id = $this->security->xss_clean($this->uri->segment(5));
		$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('smt');
		$ta = $this->session->userdata('ta');
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/hapus_beban_kerja_prof';
		$parameter  = array('api_search' => array($id, $kd_dosen, $smt, $ta));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		redirect('bkd/dosen/bebankerja/profesor');
	}
	
	
	
	function getDataDosen($kd){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2001, 'api_subkode' => 2, 'api_search' => array($kd));
		$nama = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $nama;
	}

	function kesimpulan(){
		$kd_dosen = $this->session->userdata("id_user");
		/* set parameters */
		$tahun = $this->input->post('thn');
		if($tahun == ''){
			$ta = $this->session->userdata("ta");
			$smt = $this->session->userdata("smt");		
		}else{
			$ta = $tahun;
			$smt = $this->input->post('smt');
		}
		$kd = $this->session->userdata("kd_dosen");
		$data = array('ta'=> $ta, 'smt'=>$smt);
		/* load main content */
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd));
		$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$data['namaLengkap'] = $this->getDataDosen($kd_dosen);
		
		$kd_ta = $this->_generate_ta($ta);
		$kd_smt = $this->_generate_smt($smt);
		$this->session->unset_userdata('jenis_dosen');
		$this->session->set_userdata('jenis_dosen', $this->history->_status_DS($kd, $kd_ta, $kd_smt));

		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($this->session->userdata('jenis_dosen'));
		
		foreach ($data['dosen'] as $val);
			if ($val->NO_SERTIFIKAT == ''){
				$data['noser'] = '-';
			}else{
				$data['noser'] = $val->NO_SERTIFIKAT;
			}
			/* 
				cek status dosen
				dan set syarat serta kinerja
				dan kesimpulan
			*/
			$jenis_dosen = $this->session->userdata('jenis_dosen');



			//get syarat dari API
			$syarat_pendidikan = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_syarat_kesimpulan',
						'POST',
						array(
							'api_kode' => 1000,
							'api_subkode' => 1, //untuk PENDIDIKAN
							'api_search' => array($jenis_dosen)
						)
					); 

			$syarat_penelitian = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_syarat_kesimpulan',
						'POST',
						array(
							'api_kode' => 1000,
							'api_subkode' => 2, //untuk PENELITIAN
							'api_search' => array($jenis_dosen)
						)
					); 

			$syarat_pengabdian = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_syarat_kesimpulan',
						'POST',
						array(
							'api_kode' => 1000,
							'api_subkode' => 3, //untuk PENGABDIAN
							'api_search' => array($jenis_dosen)
						)
					); 

			$syarat_tambahan = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_syarat_kesimpulan',
						'POST',
						array(
							'api_kode' => 1000,
							'api_subkode' => 4, //untuk TAMBAHAN
							'api_search' => array($jenis_dosen)
						)
					); 

			$syarat_total = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_syarat_total',
						'POST',
						array(
							'api_search' => array($jenis_dosen)
						)
					); 

			$syarat_min = $syarat_total['MIN'];
			$syarat_max = $syarat_total['MAX'];

			$data['max'] = $syarat_max;
			$data['min'] = $syarat_min;



			$data['syarat_pendidikan'] 	= $syarat_pendidikan['NILAI'];
			$data['syarat_penelitian']	= $syarat_penelitian['NILAI'];
			$data['syarat_pp'] 			= $data['syarat_pendidikan'] + $data['syarat_penelitian'];

			$data['syarat_pengabdian'] 	= $syarat_pengabdian['NILAI'];
			$data['syarat_tambahan'] 	= $syarat_tambahan['NILAI'];
			$data['syarat_pt'] 			= $data['syarat_pengabdian'] + $data['syarat_tambahan'];
			$data['syarat_total'] 		= $syarat_total['MAX'];

			$api_url 	= URL_API_BKD.'bkd_beban_kerja/jml_kinerja';
			$parameter  = array('api_search' => array('A',$kd, $ta, $smt));
			$data['pd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			$data['kinerja_pendidikan'] = (float) str_replace(',', '.', $data['pd']);
			if ($data['kinerja_pendidikan'] < $data['syarat_pendidikan']){
				$data['kesimpulan_pendidikan'] = 'T';
			}else{
				$data['kesimpulan_pendidikan'] = 'M';
			}

			// if ($this->session->userdata('jenis_dosen') == 'DS' || $this->session->userdata('jenis_dosen') == 'PR'){
			// 	$data['syarat_pendidikan'] = 9;	
			// 	$data['syarat_pp'] = 9;
			// 	# bidang pendidikan
			// 	$api_url 	= URL_API_BKD.'bkd_beban_kerja/jml_kinerja';
			// 	$parameter  = array('api_search' => array('A',$kd, $ta, $smt));
			// 	$data['pd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			// 	$data['kinerja_pendidikan'] = (float) str_replace(',', '.', $data['pd']);
			// 	if ($data['kinerja_pendidikan'] < $data['syarat_pendidikan']){
			// 		$data['kesimpulan_pendidikan'] = 'T';
			// 	}else{
			// 		$data['kesimpulan_pendidikan'] = 'M';
			// 	}
			// }
			// else {
			// 	$data['syarat_pendidikan'] = 3;
			// 	$data['syarat_pp'] = 3;
			// 	$api_url 	= URL_API_BKD.'bkd_beban_kerja/jml_kinerja';
			// 	$parameter  = array('api_search' => array('A',$kd, $ta, $smt));
			// 	$data['pd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			// 	$data['kinerja_pendidikan'] = (float) str_replace(',', '.', $data['pd']); 
			// 	if ($data['kinerja_pendidikan'] < $data['syarat_pendidikan']){
			// 		$data['kesimpulan_pendidikan'] = 'T';
			// 	}else{
			// 		$data['kesimpulan_pendidikan'] = 'M';
			// 	}
			// }

		# bidang penelitian
		$parameter  = array('api_search' => array('B',$kd, $ta, $smt));
		$data['pl'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$data['kinerja_penelitian'] = (float)$data['pl'];
			if ($data['kinerja_penelitian'] < $data['syarat_penelitian']){
				$data['kesimpulan_penelitian'] = 'T';
			}else{
				$data['kesimpulan_penelitian'] = 'M';
			}
			
			# bidang pendidikan + penelitian
			$data['kinerja_pp'] = (float) str_replace(',', '.', $data['pd']) + (float) str_replace(',', '.', $data['pl']); 
			if ($data['kinerja_pp'] < $data['syarat_pp']){
				$data['kesimpulan_pp'] = 'T';
			}else{
				$data['kesimpulan_pp'] = 'M';
			}
		
		# bidang penunjang dan pengambdian
		//pengabdian
		$parameter  = array('api_search' => array('C',$kd, $ta, $smt));
		$data['pk'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//pennjang
		$parameter  = array('api_search' => array('D',$kd, $ta, $smt));
		$data['pg'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			$data['kinerja_pt'] = (float) str_replace(',', '.', $data['pk']) + (float) str_replace(',', '.', $data['pg']); 
			if ($data['kinerja_pt'] < $data['syarat_pt']){
				$data['kesimpulan_pt'] = 'T';
			}else{
				$data['kesimpulan_pt'] = 'M';
			}
			
			$data['total_kinerja'] = $data['kinerja_pendidikan']+$data['kinerja_penelitian']+ $data['pk']+$data['pg'];
			if ($data['total_kinerja'] < $syarat_min || $data['total_kinerja'] > $syarat_max){
				$data['kesimpulan'] = 'T';
			}
			else{
				$data['kesimpulan'] = 'M';
			}
		
		#beban lebih dosen
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/jml_beban_lebih';
		
		$parameter  = array('api_search' => array('A',$kd, $ta, $smt));
		$data['ba'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 
		if(!empty($data['ba'])){
			foreach($data['ba'] as $ba){ $data['bl_a'] = (float)$ba->JML_KINERJA; }
		}
		
		$parameter  = array('api_search' => array('B',$kd, $ta, $smt));
		$data['bb'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);  
		if(!empty($data['bb'])){
			foreach($data['bb'] as $bb){ $data['bl_b'] = (float)$bb->JML_KINERJA; }
		}
		
		$parameter  = array('api_search' => array('C',$kd, $ta, $smt));
		$data['bc'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);  
		if(!empty($data['bc'])){		
			foreach($data['bc'] as $bc){ $data['bl_c'] = (float)$bc->JML_KINERJA; }
		}
		
		$parameter  = array('api_search' => array('D',$kd, $ta, $smt));
		$data['bd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);  
		if(!empty($data['bd'])){		
			foreach($data['bd'] as $bd){ $data['bl_d'] = (float)$bd->JML_KINERJA; }
		}
		
		$thn_prof = $this->session->userdata('thn_prof');
		# kesimpulan untuk beban kerja profesor tahunan*/
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kinerja_prof_tahunan';
		
		$parameter  = array('api_search' => array($kd, 'A', $thn_prof, 0));
		$data['kinerja_tahunan_a'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'B', $thn_prof, 0));
		$data['kinerja_tahunan_b'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'C', $thn_prof, 0));
		$data['kinerja_tahunan_c'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$parameter  = array('api_search' => array($kd, 'A', $thn_prof, 1));
		$data['kinerja_tahunan_a1'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'B', $thn_prof, 1));
		$data['kinerja_tahunan_b1'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'C', $thn_prof, 1));
		$data['kinerja_tahunan_c1'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$parameter  = array('api_search' => array($kd, 'A', $thn_prof, 2));
		$data['kinerja_tahunan_a2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'B', $thn_prof, 2));
		$data['kinerja_tahunan_b2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'C', $thn_prof, 2));
		$data['kinerja_tahunan_c2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		/*$parameter  = array('api_search' => array($kd, 'A', $thn_prof, 3));
		$data['kinerja_tahunan_a3'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'B', $thn_prof, 3));
		$data['kinerja_tahunan_b3'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'C', $thn_prof, 3));
		$data['kinerja_tahunan_c3'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		$parameter  = array('api_search' => array($kd, 'A', $thn_prof, 4));
		$data['kinerja_tahunan_a4'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'B', $thn_prof, 4));
		$data['kinerja_tahunan_b4'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$parameter  = array('api_search' => array($kd, 'C', $thn_prof, 4));
		$data['kinerja_tahunan_c4'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); */

		$this->output99->output_display('dosen/kesimpulan',$data);
	}
	
	# TAMBAHAN DATA PUBLIKASI 04-12-2013
	# ==============================================================================================================
	function publikasi($param = null, $id = null){
		$kd_dosen = $this->session->userdata('kd_dosen');
		
		$data['p_ta'] = $this->input->post("thn");
		$data['p_smt'] = $this->input->post("smt");
		if ($data['p_ta'] == null && $data['p_smt'] == null){
			$data['ta'] = $this->session->userdata('ta');
			$data['smt'] = $this->session->userdata('smt');		
		}else{
			$data['ta'] = $this->input->post("thn");
			$data['smt'] = $this->input->post("smt");
		}
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_data_publikasi';
		$parameter  = array('api_search' => array($kd_dosen, $data['ta'], $data['smt']));
		$data['data_publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
		# get data dosen prodi 
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_all_dosen';
			$parameter  = array('api_search' => array());
			$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		if ($param != null){
			if($param == 'tambah'){
				if($id != null){
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_publikasi';
					$parameter  = array('api_search' => array($id));
					$data['curr_publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);				
				}
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_all_dosen';
				$parameter  = array('api_search' => array());
				$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				
				#$this->output99=$this->s00_lib_output;
				$this->output99->output_display('dosen/form_publikasi',$data);
			}
			else if ($param == 'detail'){
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_publikasi';
				$parameter  = array('api_search' => array($id));
				$data['curr_publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);								

				# GET PARTNER
				$api_url 	= URL_API_BKD.'bkd_dosen/partner';
				$parameter  = array('api_kode' => 11000, 'api_subkode' => 1, 'api_search' => array($id,'PUBLIKASI'));
				$data['partner'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
				#print_r($data['partner']); die();
				$data['nama_partner'] = array();
				foreach ($data['partner'] as $p){
					$data['nama_partner'][$p->PARTNER] = $this->get_nama_partner($p->PARTNER);
				}
				
				#$this->output99=$this->s00_lib_output;
				$this->output99->output_display('dosen/daftar_publikasi',$data);
			}
			else{
				#$this->output99=$this->s00_lib_output;
				$this->output99->output_display('dosen/daftar_publikasi',$data);
			}
		}else{
			#$this->output99=$this->s00_lib_output;
			$this->output99->output_display('dosen/daftar_publikasi',$data);
		}
	}
	
	function simpan_data_publikasi(){
		$this->form_validation->set_rules('judul','<b>Judul Penelitian</b>','required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			redirect('bkd/dosen/bebankerja/publikasi/tambah');
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
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_publikasi';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f, $g, $i, $j, $k, $l));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# get last id beban kerja
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
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
	
	function update_data_publikasi(){
		$this->form_validation->set_rules('judul','<b>Judul Penelitian</b>','required|xss_clean');
		$this->form_validation->set_rules('pada','<b>Dipublikasikan pada</b>','required|xss_clean');
		$this->form_validation->set_rules('tingkat','<b>Tingkat</b>','required|xss_clean');
		$this->form_validation->set_rules('tanggal_pub','<b>Tanggal publikasi</b>','required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			redirect('bkd/dosen/bebankerja/publikasi/tambah');
		}else{
			$a = $this->input->post('kd_dp');
			$b = str_replace("'","''",$this->input->post('judul'));
			$c = $this->input->post('pada');
			$d = $this->input->post('tingkat');
			$e = $this->input->post('tanggal_pub');
			$f = $this->input->post('penerbit');
			$g = $this->input->post('akreditasi');
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_data_publikasi';
			$parameter  = array('api_search' => array($a, $b, $c, $d, $e, $f, $g));
			#print_r($parameter); die();
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			redirect('bkd/dosen/bebankerja/publikasi');
		}
	}
	
	function hapus_publikasi(){
		$kode = $this->security->xss_clean($this->uri->segment(5));
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/hapus_data_publikasi';
		$parameter  = array('api_search' => array($kode));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		redirect('bkd/dosen/bebankerja/publikasi');	
	}
	
	# TAMBAHAN DATA HAKI 10-14-2015
	# ==============================================================================================================
	function haki($param = null, $id = null){
		$kd_dosen = $this->session->userdata('kd_dosen');
		
		$data['p_ta'] = $this->input->post("thn");
		$data['p_smt'] = $this->input->post("smt");
		if ($data['p_ta'] == null && $data['p_smt'] == null){
			$data['ta'] = $this->session->userdata('ta');
			$data['smt'] = $this->session->userdata('smt');		
		}else{
			$data['ta'] = $this->input->post("thn");
			$data['smt'] = $this->input->post("smt");
		}
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_data_haki';
		$parameter  = array('api_search' => array($kd_dosen));
		$data['haki'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
		# get data dosen prodi 
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_all_dosen';
			$parameter  = array('api_search' => array());
			$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_bebankerja';
			
			$thn= $this->session->userdata('ta');
			$smt=$this->session->userdata('smt');
			$parameter  = array('api_search' => array('H',$kd_dosen, $thn, $smt));
			$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		if ($param != null){
			$data['jenis_haki'] = $this->s00_lib_api->get_api_jsob(URL_API_BKD.'bkd_beban_kerja/get_jenis_haki','POST',array());	
			if($param == 'tambah'){
				if($id != null){
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_haki';
					$parameter  = array('api_search' => array($id));
					$ch=$data['curr_haki'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
										
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_bkd';
					$parameter  = array('api_search' => array($ch->KD_BK, 'H'));
					$data['current_data'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_all_dosen';
				$parameter  = array('api_search' => array());
				$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				
				#$this->output99=$this->s00_lib_output;
				$this->output99->output_display('dosen/form_haki',$data);
			}
			else if ($param == 'detail'){
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_data_haki';
				$parameter  = array('api_search' => array($id));
				$data['curr_haki'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);								

				# GET PARTNER
				$api_url 	= URL_API_BKD.'bkd_dosen/partner';
				$parameter  = array('api_kode' => 11000, 'api_subkode' => 1, 'api_search' => array($id,'HAKI'));
				$data['partner'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
				#print_r($data['partner']); die();
				$data['nama_partner'] = array();
				foreach ($data['partner'] as $p){
					$data['nama_partner'][$p->PARTNER] = $this->get_nama_partner($p->PARTNER);
				}
				
				#$this->output99=$this->s00_lib_output;
				$this->output99->output_display('dosen/daftar_haki',$data);
			}
			else{
				#$this->output99=$this->s00_lib_output;
				$this->output99->output_display('dosen/daftar_haki',$data);
			}
		}else{
			#$this->output99=$this->s00_lib_output;
			$this->output99->output_display('dosen/daftar_haki',$data);
		}
	}
	
	function simpan_data_haki(){
		$this->form_validation->set_rules('judul','<b>Judul Penelitian</b>','required|xss_clean');
		if ($this->form_validation->run() == FALSE){
			redirect('bkd/dosen/bebankerja/tambah/H');
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

			$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja';
			$parameter	= array('api_search' => array('H', $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $q, $fp, $fc, $kd_ta, $kd_smt));
			$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# get last id beban kerja
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
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
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_haki';
			$parameter  = array('api_search' => $di);
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# get last id beban kerja
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
				$parameter	= array('api_search' => array('BKD.HAKI','KD_HAKI'));
				$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 

			$dosen = $this->input->post('dosen');
			$mhs = $this->input->post('mahasiswa');
			$lain = implode('<$>', $this->input->post('lain'));
			
			$partner = $dosen.'<$>'.$mhs.'<$>'.$lain;
			$data_partner = explode('<$>', $partner);
			for($x=0; $x<count($data_partner); $x++){
				if($data_partner[$x] !== ''){
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/partner_kinerja';
					$parameter	= array('api_kode' => 1800, 'api_subkode' => 1, 'api_search' => array($getid, 'HAKI', $a, $data_partner[$x]));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
			}

			redirect('bkd/dosen/bebankerja/tambah/H');
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
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_bkd';
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
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_data_haki';
		$parameter  = array('api_search' => $di);
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);			
		#print_r($parameter); die();
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		redirect('bkd/dosen/bebankerja/data/H');
	}
	
	function hapus_haki(){
		$kode = $this->security->xss_clean($this->uri->segment(5));
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/hapus_data_haki';
		$parameter  = array('api_search' => array($kode));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);		
		redirect('bkd/dosen/bebankerja/haki');	
	}
	
	
	function cetak(){
		$this->output99->output_display('dosen/form_report');
	}
	
		
	function get_asesor_prodi(){
		$kd_prodi = $this->session->userdata('kd_prodi');
		if(ISSET($_GET['q'])){
			$q = strtoupper(str_replace("'", "''", $_GET['q']));
			$tot = count(str_split($q));
			if($tot > 1){
				$api_url 	= URL_API_BKD.'bkd_dosen/cari_data_asesor_prodi';
				$parameter  = array('api_search' => array($kd_prodi, $q));
				$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				$new_data = array();
				foreach ($data as $dt){
					$new_data[] = array('id' =>	$dt['NIRA'], 'name'	=>	$dt['NM_ASESOR']);
				}
				echo json_encode($new_data);
			}
		}

	}
	
	function get_mahasiswa(){
		if(ISSET($_GET['q'])){
			$q = strtoupper(str_replace("'", "''", $_GET['q']));
			$tot = count(str_split($q));
			if($tot > 1){
				$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
				$parameter	= array('api_kode' => 26000, 'api_subkode' => 26, 'api_search' => array($q));
				$data 		= $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				$new_data = array();
				foreach ($data as $dt){
					$new_data[] = array('id'=>$dt->NIM, 'name'=>$dt->NAMA.' ('.$dt->NIM.')');
				}
				echo json_encode($new_data);
			}
		}
	}
	
	function get_dosen(){
		if(ISSET($_GET['q'])){
			$q = strtoupper(str_replace("'", "''", $_GET['q']));
			$tot = count(str_split($q));
			if($tot > 1){
				$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
				$parameter 	= array('api_kode' => 2001, 'api_subkode' => 3, 'api_search' => array($q));
				$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);	
				$new_data = array();
				foreach ($data as $dt){
					$new_data[] = array('id' =>	$dt->KD_PGW, 'name'	=>	$dt->NM_PGW_F.' ('.$dt->KD_PGW.')');
				}
				echo json_encode($new_data);
			}
		}
	}
	
	function get_dosen_xxx(){
		if(ISSET($_GET['q'])){
			$q = strtoupper(str_replace("'", "''", $_GET['q']));
			$tot = count(str_split($q));
			if($tot > 1){
				$url = 'tnde_public';
				$parameter = array('api_kode' => 4004, 'api_subkode' => 2, 'api_search' => array($q));
				$data = $this->mdl_bkd->get_api_surat($url.'/tnde_pegawai/get_pegawai', 'json', 'POST', $parameter);
				$new_data = array();
				foreach ($data as $dt){
					$new_data[] = array('id' =>	$dt['KD_PEGAWAI'].'#'.$dt['NM_PEGAWAI'], 'name'	=>	$dt['NM_PEGAWAI'].'('.$dt['KD_PEGAWAI'].')');
				}
				echo json_encode($new_data);
			}
		}
	}
	
	/* function get_surat(){
		if(ISSET($_GET['q'])){
			$q = strtoupper(str_replace("'", "''", $_GET['q']));
			$tot = count(str_split($_GET['q']));
			if($tot > 1){
				$url	= 'tnde_public/'; $jenis_surat = '1,2,5,9';
				$parameter = array('api_kode' => 90009, 'api_subkode' => 2, 'api_search' => array($q, $jenis_surat, 2));
				$data = $this->mdl_bkd->get_api_surat($url.'tnde_surat_keluar/get_surat_keluar_all', 'json', 'POST', $parameter);
				$new_data = array();
				foreach ($data as $dt){
					$new_data[] = array('id' =>	$dt['ID_SURAT_KELUAR'].':'.$dt['PERIHAL'], 'name'	=>	$dt['PERIHAL'].' ('.$dt['NO_SURAT'].')');
				}
				echo json_encode($new_data);
			}
		}
	} */
	
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
	
	/*
		base_url()."internal/surat_keluar/detail/".$surat_masuk['ID_SURAT_KELUAR']
	*/

	# CEK DPL DOSEN :: PENGABDIAN MASYARAKAT
	function cek_dpl(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		# cek data beban kerja
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/cek_data_bkd';
		$parameter  = array('api_search' => array($kd_dosen, 'C', $ta, $smt));
		$data['jml'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#echo $data['jml']; print_r($parameter); die();
		if ($data['jml'] == 0){
			$url		= 'http://service.uin-suka.ac.id/servsiasuper/index.php/';
			$parameter	= array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($kd_dosen));
			$data		= $this->mdl_bkd->get_api_kkn('kkn_admin/data_dpl_kkn', 'json', 'POST', $parameter);
			if(count($data) > 0){
				$tema = $data[0]['TEMA_KKN'];
				$angkatan = $data[0]['ANGKATAN'];
				$periode = $data[0]['PERIODE'];
				$ta = $data[0]['TA'];
				$tgl_mulai = $data[0]['TANGGAL_MULAI'];
				$tgl_selesai = $data[0]['TANGGAL_SELESAI'];
				$kelompok = $data[0]['JUM_KELOMPOK'];
				# BEBAN KERJA
				$jenis_kegiatan = "Menjadi Dosen Pembimbing Lapangan (DPL) KKN, Angkatan ".$angkatan.", Periode ".$periode.", dengan Tema \"".$tema."\", Kelompok ".$kelompok;
				$masa_penugasan = "1 Semester";
				$bukti_penugasan = '-'; $bkt_dokumen = '-';
				$sks_rule = 1;
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
				$parameter  = array('api_search' => array($kd_dosen, 'C', $jenis_kegiatan, $bukti_penugasan, $sks_rule, $masa_penugasan, $sks_rule, $bkt_dokumen, $ta, $smt, 'LANJUTKAN','100'));
				$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if($simpan){
					# get last id beban kerja 
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
					$parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
					$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 
					#simpan data pengabdian
					$sumber_dana = '-'; $jumlah_dana = 0;
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pengabdian';
					$parameter	= array('api_search' => array($getid, $tgl_mulai, $tgl_selesai, $tema, $sumber_dana, $jumlah_dana, '50'));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
				redirect('bkd/dosen/bebankerja/data/C');
			}else{
				redirect('bkd/dosen/bebankerja/data/C');
			}
		}else{
			redirect('bkd/dosen/bebankerja/data/C');		
		}
	}
	
	
	function data_penawaran_kelas(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('kd_smt');
		$ta = $this->session->userdata('kd_ta');
		$thn = $this->session->userdata('ta');
		if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array(
						'api_kode' 		=> 58000,
						'api_subkode' 	=> 32,
						'api_search' 	=> array($ta, $smt , $kd_dosen)
					);
		$data['penawaran'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		
		/*echo "<pre>";
		print_r($data['penawaran']); 
		echo "</pre>";die();*/
		#print_r($parameter); die();
		if(!empty($data['penawaran'])){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/cek_data_bkd';
			$parameter  = array('api_search' => array($kd_dosen, 'A', $thn, $semester));
			$data['jml'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if ($data['jml'] == 0){
				foreach ($data['penawaran'] as $data){
					$tim = $data->TIM_AJAR;
					$jml_tim = explode('#', $tim); $jml_dosen = count($jml_tim);
					$jenis_kegiatan = "Mengajar Matakuliah ".$data->NM_MK." Program Studi ".$data->NM_PRODI.", Kelas ".$data->KELAS_PARAREL.", ".$data->SKS." sks, ".$data->TERISI." Mahasiswa, ".$jml_dosen." Dosen";
					$nm_keg = $jenis_kegiatan; //"Mengajar Matakuliah ".$data->NM_MK;
					$jenjang = $this->jenjang($data->KD_PRODI);
					$sks = $data->SKS;
					$jml_mhs = $data->TERISI;
					//CATATAN:MASIH DITEMUKAN KESALAHAN DATA DIMANA SKS_RULE BELUM DIBAGI DENGAN JUMLAH TIM DOSEN YANG MENGAJAR
					//BELUM DIBENAHI, AKAN DIKONSULTASIKAN TERLEBIH DAHULU
					$sks_rule = round($this->aturan_beban_sks($jenjang, $sks, $jml_mhs, $data->KD_KELAS),2);
					$tatapmuka = $data->TATAP;
					$pertemuan_pm = count(explode('#', $data->JADWAL1));
					$kelas = $data->KD_JENIS_KELAS;
					$nm_prodi = $data->NM_PRODI;
					$masa_penugasan = "1 Semester";
					$bukti_penugasan = '-'; $bkt_dokumen = '-';
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
					$parameter  = array('api_search' => array($kd_dosen, 'A', $jenis_kegiatan, $bukti_penugasan, $sks_rule, $masa_penugasan, $sks_rule, $bkt_dokumen, $thn, $semester, 'LANJUTKAN','100', $ta, $smt));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if ($simpan){
						# get last id beban kerja 
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
						$parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
						$getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter); 
						#simpan data pendidikan
						//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
						//akan berubah menjadi kd_kat untuk membimbing
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan';
						$parameter	= array('api_search' => array($getid, '1', $nm_keg, $jenjang, '-', $jml_mhs, $sks, $jml_dosen, $tatapmuka, $kelas, $pertemuan_pm, $nm_prodi));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					}
				}
			}
		}else{

		}
		$this->cek_tugas_akhir_mhs();
		$this->cek_ujian_akhir_mhs();
		redirect('bkd/dosen/bebankerja/data/A');
	}
	function cek_api_mhs_bimbingan($nim){
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_last_id_mhs_bimbingan';
		$parameter	= array('api_search' => array('3',$nim));
		$get_last_nim = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter); 
		return $get_last_nim;
	}
	function cek_api_mhs_ujian($nim){
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_last_id_mhs_bimbingan';
		$parameter	= array('api_search' => array('4',$nim));
		$get_last_nim = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter); 
		return $get_last_nim;
	}
	function testing_cek_api_mhs_bimbingan(){
		$nim = '11650006';
		$data = $this->cek_api_mhs_bimbingan($nim);
		print_r($data);
	}

	function cek_tugas_akhir_mhs(){
			$bimbingan = $this->cek_mhs_bimbingan_ta();
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

			foreach ($data as $ta) {
				$nim = $ta['NIM'];
				$judul_ta = $ta['JUDUL'];
				$kd_kur_mhs = $this->kurikulum_mahasiswa($nim);
				$temp = $this->get_data_mhs($nim);
				$jenjang = $temp[0]['NM_JENJANG'];
				$prodi = $temp[0]['NM_PRODI'];

				$list_mk = $this->api->get_api_json(
					URL_API_SIA.'sia_kurikulum/data_search',
					'POST',
					array(
						'api_kode'=>40000,
						'api_subkode' => 15,
						'api_search' => array($kd_kur_mhs)
					)
				);

				foreach ($list_mk as $mk) {
					$get_makul = strtoupper($mk['NM_MK']);
					$get_jenjang = strtoupper($mk['NM_PEND']);
					if($get_jenjang == "S0"){
						if($get_makul == "TUGAS AKHIR" || $get_makul == "SKRIPSI" || $get_makul == "TUGAS AKHIR/SKRIPSI" || $get_makul == "SKRIPSI/TUGAS AKHIR"){
							$jml_sks = $mk['SKS_MK'];
						}
					}else if($get_jenjang == "S1"){
						if($get_makul == "TUGAS AKHIR" || $get_makul == "SKRIPSI" || $get_makul == "TUGAS AKHIR/SKRIPSI" || $get_makul == "SKRIPSI/TUGAS AKHIR"){
							$jml_sks = $mk['SKS_MK'];
						}
					}else if($get_jenjang=="S2"){
						if($get_makul == "TESIS" || $get_makul == "TESIS/TUGAS AKHIR" || $get_makul == "TUGAS AKHIR/TESIS"){
							$jml_sks = $mk['SKS_MK'];
						}
					}else if($get_jenjang=="S3"){
						if($get_makul == "DISERTASI" || $get_makul == "DISERTASI/TUGAS AKHIR" || $get_makul == "TUGAS AKHIR/DISERTASI"){
							$jml_sks = $mk['SKS_MK'];
						}
					}
				}

				//BUAT NARASI + INPUT DATA KE DB + ADA CEK DUPLUKASI DATA
				//BUAT NARASI + INPUT DATA KE DB + ADA CEK DUPLUKASI DATA
				$narasi = "Membimbing Tugas Akhir ".$jenjang.", Program Studi ".$prodi.", Judul ".$judul_ta.", ".$jml_sks." SKS";
				$narasi = str_replace("'", "", $narasi);
				$narasi = strip_tags($narasi);
				//$narasi = str_replace(",", "", $narasi);
				$sks_rule = round($this->aturan_beban_sks2(strtoupper($jenjang), $jml_sks, 1, 'TUGAS_AKHIR'),2);
				
				//ATURAN SKS_RULE UNTUK MEMBIMBING TA
				if($jenjang == 'S0'){
					$sks_rule = round(($sks_rule*1), 2);
				}elseif($jenjang == 'S1'){
					$sks_rule = round(($sks_rule*1), 2);
				}elseif($jenjang == 'S2'){
					$sks_rule = round(($sks_rule*1), 2);
				}elseif($jenjang == 'S3'){
					$sks_rule = round(($sks_rule*1), 2);
				}

				$smt = $this->session->userdata('kd_smt');
				$ta = $this->session->userdata('kd_ta');

				// $kd_jbk = 'A';
				$kd_dosen = $this->session->userdata('kd_dosen');
				$jenis_kegiatan = $narasi;
				/*$jenis_kegiatan ='test';*/
				$bukti_penugasan = '-';
				// $sks_penugasan = $sks_rule;
				$masa_penugasan = '1 Semester';
				$bkt_dokumen = '-';
				$kd_jbk 		= 'A';
				$bkt_penugasan 	= '-';
				$bkt_dokumen 	= '-';
				$rekomendasi 	= 'LANJUTKAN';
				$capaian 		= 100;
				$jml_jam 		= 1;
				$outcome 		= '-';
				$file_penugasan = '-';
				$file_capaian 	= '-';
				// $sks_bkt = $sks_rule;
				$thn = $this->session->userdata('ta');
				if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';
				// $rekomendasi = 'LANJUTKAN';
				// $jumlah_jam = null;
				// $capaian = '100';
				// $outcome = null;
				// $file_penugasan = null;
				// $file_capaian = null;

				//insert into BKD_BEBAN_KERJA && BKD_REMUN_KINERJA

				//cek apakah sudah ada data atau belum ?
				$cek_mhs_bimbingan = $this->cek_api_mhs_bimbingan($nim);

				if(!$cek_mhs_bimbingan){

					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
					$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule, $masa_penugasan, $bkt_dokumen, $sks_rule, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						// echo 'simpan ke db bkd_beban_kerja dan BKD_REMUN_KINERJA sukses'; echo '<br>';
						// $api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
						// $parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
						// $getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

						$getid = $simpan;
						$nm_keg = $jenis_kegiatan;
						#simpan data pendidikan
						//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
						//akan berubah menjadi kd_kat untuk membimbing
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
						$parameter	= array('api_search' => array($getid, '3', $nm_keg, $jenjang, '-', '1', $jml_sks, '1', '1', 'A', '1', $prodi, $nim));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						if($simpan){
							//echo 'simpan ke data pendidikan sukses'; echo '<br>';
						}
					}
				 }else{
					
				 }
			}
	}

	function cek_ujian_akhir_mhs(){
			//untuk mendapatkan data semua mahasiswa yang telah di uji TA nya
			$bimbingan = $this->cek_mhs_ujian_ta();
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
			foreach ($data as $ta) {
				$nim = $ta['NIM'];
				$judul_ta = $ta['JUDUL'];
				$kd_kur_mhs = $this->kurikulum_mahasiswa($nim);
				$temp = $this->get_data_mhs($nim);
				$jenjang = $temp[0]['NM_JENJANG'];
				$prodi = $temp[0]['NM_PRODI'];

				$list_mk = $this->api->get_api_json(
					URL_API_SIA.'sia_kurikulum/data_search',
					'POST',
					array(
						'api_kode'=>40000,
						'api_subkode' => 15,
						'api_search' => array($kd_kur_mhs)
					)
				);

				foreach ($list_mk as $mk) {
					$get_makul = strtoupper($mk['NM_MK']);
					$get_jenjang = strtoupper($mk['NM_PEND']);
					if($get_jenjang == "S0"){
						if($get_makul == "TUGAS AKHIR" || $get_makul == "SKRIPSI" || $get_makul == "TUGAS AKHIR/SKRIPSI" || $get_makul == "SKRIPSI/TUGAS AKHIR"){
							$jml_sks = $mk['SKS_MK'];
						}
					}else if($get_jenjang == "S1"){
						if($get_makul == "TUGAS AKHIR" || $get_makul == "SKRIPSI" || $get_makul == "TUGAS AKHIR/SKRIPSI" || $get_makul == "SKRIPSI/TUGAS AKHIR"){
							$jml_sks = $mk['SKS_MK'];
						}
					}else if($get_jenjang=="S2"){
						if($get_makul == "TESIS" || $get_makul == "TESIS/TUGAS AKHIR" || $get_makul == "TUGAS AKHIR/TESIS"){
							$jml_sks = $mk['SKS_MK'];
						}
					}else if($get_jenjang=="S3"){
						if($get_makul == "DISERTASI" || $get_makul == "DISERTASI/TUGAS AKHIR" || $get_makul == "TUGAS AKHIR/DISERTASI"){
							$jml_sks = $mk['SKS_MK'];
						}
					}
				}

				//BUAT NARASI + INPUT DATA KE DB + ADA CEK DUPLUKASI DATA
				//BUAT NARASI + INPUT DATA KE DB + ADA CEK DUPLUKASI DATA
				$narasi = "Menguji Tugas Akhir ".$jenjang.", Program Studi ".$prodi.", Judul ".$judul_ta.", ".$jml_sks." SKS";
				$narasi = str_replace("'", "", $narasi);
				$narasi = strip_tags($narasi);
				//$narasi = str_replace(",", "", $narasi);
				$sks_rule = round($this->aturan_beban_sks2(strtoupper($jenjang), $jml_sks, 1, 'TUGAS_AKHIR'),2);

				//ATURAN SKS_RULE UNTUK MENGUJI TA
				if($jenjang == 'S0'){
					//sks_rule sementara untuk menguji TA S0 
					$sks_rule = round(($sks_rule*(0.5)), 2);
				}elseif($jenjang == 'S1'){
					//sks_rule sementara untuk menguji TA S0 
					$sks_rule = round(($sks_rule*(0.5)), 2);
				}
				elseif($jenjang == 'S2'){
					$sks_rule = round(($sks_rule*(0.5)), 2);
				}elseif($jenjang == 'S3'){
					$sks_rule = round(($sks_rule*(0.5)), 2);
				}

				$smt = $this->session->userdata('kd_smt');
				$ta = $this->session->userdata('kd_ta');

				// $kd_jbk = 'A';
				$kd_dosen = $this->session->userdata('kd_dosen');
				$jenis_kegiatan = $narasi;
				/*$jenis_kegiatan ='test';*/
				$bukti_penugasan = '-';
				// $sks_penugasan = $sks_rule;
				$masa_penugasan = '1 Semester';
				$bkt_dokumen = '-';
				$kd_jbk 		= 'A';
				$bkt_penugasan 	= '-';
				$bkt_dokumen 	= '-';
				$rekomendasi 	= 'LANJUTKAN';
				$capaian 		= 100;
				$jml_jam 		= 1;
				$outcome 		= '-';
				$file_penugasan = '-';
				$file_capaian 	= '-';
				// $sks_bkt = $sks_rule;
				$thn = $this->session->userdata('ta');
				if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';

				// $rekomendasi = 'LANJUTKAN';
				// $jumlah_jam = null;
				// $capaian = '100';
				// $outcome = null;
				// $file_penugasan = null;
				// $file_capaian = null;

				//insert into BKD_BEBAN_KERJA && BKD_REMUN_KINERJA

				//cek apakah sudah ada data atau belum ?
				 $cek_mhs_bimbingan = $this->cek_api_mhs_ujian($nim);
				 
				if(!$cek_mhs_bimbingan){

					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
					$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule, $masa_penugasan, $bkt_dokumen, $sks_rule, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						// echo 'simpan ke db bkd_beban_kerja dan BKD_REMUN_KINERJA sukses'; echo '<br>';
						// $api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_data_tersimpan';
						// $parameter	= array('api_search' => array('BKD.BKD_BEBAN_KERJA','KD_BK'));
						// $getid = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

						$getid = $simpan;
						$nm_keg = $jenis_kegiatan;
						#simpan data pendidikan
						//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
						//akan berubah menjadi kd_kat untuk membimbing
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
						$parameter	= array('api_search' => array($getid, '4', $nm_keg, $jenjang, '-', '1', $jml_sks, '1', '1', 'A', '1', $prodi, $nim));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						if($simpan){
							//echo 'simpan ke data pendidikan sukses'; echo '<br>';
						}
					}
				 }else{
					
				 }
			}
	}
	# GENERAL FUNCTION CALCULATE SKS IN PENDIDIKAN 
	# ==========================================================================
	private function aturan_beban_sks($jenjang, $sks, $mhs, $kd_kelas){
		
		$jenis_mk = $this->_cek_jenis_mk($kd_kelas);
		
		switch ($jenjang){
			case "S0":{
				if($jenis_mk == 'SKRIPSI'){
					if($sks == 0) $rule_sks = 0; else $rule_sks = $mhs/6;
				}else{
					# rule pendidikan untuk jenjang DI/DII/DIII/DIV
					if($mhs == 0) $rule_sks = 0;
					else if ($mhs <= 40) $rule_sks = $sks*1;
					else if ($mhs > 40 && $mhs <= 80) $rule_sks = $sks*1.5;
					else $rule_sks = $sks*2;
				}
			}break;
			case "S1":{
				if($jenis_mk == 'SKRIPSI'){
					if($sks == 0) $rule_sks = 0; else $rule_sks = $mhs/6;
				}else{
					# rule pendidikan untuk jenjang S1 bukan TA
					if($mhs == 0) $rule_sks = 0;
					else if ($mhs <= 40) $rule_sks = $sks*1;
					else if ($mhs > 40 && $mhs <= 80) $rule_sks = $sks*1.5;
					else $rule_sks = $sks*2;
				}
			}break;
			case "S2":{
				if($jenis_mk == 'SKRIPSI'){
					if($sks == 0) $rule_sks = 0; else $rule_sks = $mhs/3;
				}else{
					# rule pendidikan untuk jenjang S2
					if ($mhs <= 25) $rule_sks = $sks*1;
					else if ($mhs > 25 && $mhs <= 50) $rule_sks = $sks*1.5;
					else $rule_sks = $sks*2;
				}
			}break;
			case "S3":{
				if($jenis_mk == 'SKRIPSI'){
					if($sks == 0) $rule_sks = 0; else $rule_sks = $mhs/2;
				}else{
					# rule pendidikan untuk jenjang S2
					if ($mhs <= 25) $rule_sks = $sks*1;
					else if ($mhs > 25 && $mhs <= 50) $rule_sks = $sks*1.5;
					else $rule_sks = $sks*2;
				}
			}break;
		}
		return $rule_sks;
	}

	# [UPDATE] GENERAL FUNCTION CALCULATE SKS IN PENDIDIKAN 
	# ==========================================================================


	private function aturan_beban_sks2($jenjang, $sks, $mhs, $kd_kelas='x0x'){
		if($kd_kelas=='x0x'){
			$jenis_mk = '';
		}else if($kd_kelas == 'TUGAS_AKHIR'){
			$jenis_mk = 'SKRIPSI';
		}else{
			$jenis_mk = $this->_cek_jenis_mk($kd_kelas);
		}
		
		switch ($jenjang){
			case "S0":{
				if($jenis_mk == 'SKRIPSI'){
					if($sks == 0) $rule_sks = 0; else $rule_sks = $mhs/6;
				}else{
					# rule pendidikan untuk jenjang DI/DII/DIII/DIV
					if($mhs == 0) $rule_sks = 0;
					else if ($mhs <= 40) $rule_sks = $sks*1;
					else if ($mhs > 40 && $mhs <= 80) $rule_sks = $sks*1.5;
					else $rule_sks = $sks*2;
				}
			}break;
			case "S1":{
				if($jenis_mk == 'SKRIPSI'){
					if($sks == 0) $rule_sks = 0; else $rule_sks = $mhs/6;
				}else{
					# rule pendidikan untuk jenjang S1 bukan TA
					if($mhs == 0) $rule_sks = 0;
					else if ($mhs <= 40) $rule_sks = $sks*1;
					else if ($mhs > 40 && $mhs <= 80) $rule_sks = $sks*1.5;
					else $rule_sks = $sks*2;
				}
			}break;
			case "S2":{
				if($jenis_mk == 'SKRIPSI'){
					if($sks == 0) $rule_sks = 0; else $rule_sks = $mhs/3;
				}else{
					# rule pendidikan untuk jenjang S2
					if ($mhs <= 25) $rule_sks = $sks*1;
					else if ($mhs > 25 && $mhs <= 50) $rule_sks = $sks*1.5;
					else $rule_sks = $sks*2;
				}
			}break;
			case "S3":{
				if($jenis_mk == 'SKRIPSI'){
					if($sks == 0) $rule_sks = 0; else $rule_sks = $mhs/2;
				}else{
					# rule pendidikan untuk jenjang S2
					if ($mhs <= 25) $rule_sks = $sks*1;
					else if ($mhs > 25 && $mhs <= 50) $rule_sks = $sks*1.5;
					else $rule_sks = $sks*2;
				}
			}break;
		}
		return $rule_sks;
	}
	# FUNGSI CEK JENIS MATA KULIAH
	function _cek_jenis_mk($kd_kelas){
		#sia_penawaran/data_search, 58000/6, api_search = array(kd_kelas)
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array('api_kode'=>58000, 'api_subkode' => 6, 'api_search' => array($kd_kelas));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		return $data[0]->GOLONGAN_MK;
	}
	
	function default_bebankerja(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/clear_data_bkd';
		$parameter  = array('api_search' => array($kd_dosen, 'A', $ta, $smt));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		redirect('bkd/dosen/bebankerja/data_penawaran_kelas');
	}
	
	function cek_dpa_sia(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');
		
		$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
		$parameter  = array(
						'api_kode' 		=> 28000,
						'api_subkode' 	=> 2,
						'api_search' 	=> array($kd_dosen)
					);
		$dpa = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		# cek jumlah beban kerja dpa
		/*echo "<pre>";
		print_r($dpa);
		echo "<pre>";
		die();*/
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/cek_data_bkd';
		$parameter  = array('api_search' => array($kd_dosen, 'D', $ta, $smt));
		$jml = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if ($jml == 0){
			if(count($dpa) > 0){
				$jenjang  = $this->jenjang($dpa[0]->KD_PRODI);
				$kegiatan = "Dosen Pembimbing Akademik, mahasiswa ".$dpa[0]->NM_PRODI.", Jenjang ".$jenjang.", ".count($dpa)." Mahasiswa";
				$masa_penugasan = "1 Tahun";
				$bukti_penugasan = '-'; $bkt_dokumen = '-';
				$sks = floor((count($dpa)/12)*1);
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
				$parameter  = array('api_search' => array($kd_dosen, 'D', $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $sks, $bkt_dokumen, $ta, $smt, 'LANJUTKAN','100', $kd_ta, $kd_smt));
				$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}
		}
		redirect('bkd/dosen/bebankerja/cek_jabatan_struktural');
	}
	function cek_dpa_sia_test(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');
		
		
		$dpa	= $this->api->get_api_jsob(URL_API_SIA.'sia_mahasiswa/data_search', 'POST', 
							array('api_kode'=>28000, 'api_subkode' => 6, 
							'api_search' => array('A', $kd_dosen)));
		$jml_jenjang_bimbingan = $this->cek_api_dpa();

		$jml_s0 = $jml_jenjang_bimbingan[0][0];
		$jml_s1 = $jml_jenjang_bimbingan[1][0];
		$jml_s2 = $jml_jenjang_bimbingan[2][0];
		$jml_s3 = $jml_jenjang_bimbingan[3][0];

		$jenjang_s0 = $jml_jenjang_bimbingan[0][1];
		$jenjang_s1 = $jml_jenjang_bimbingan[1][1];
		$jenjang_s2 = $jml_jenjang_bimbingan[2][1];
		$jenjang_s3 = $jml_jenjang_bimbingan[3][1];

		$prodi_s0 = $jml_jenjang_bimbingan[0][2];
		$prodi_s1 = $jml_jenjang_bimbingan[1][2];
		$prodi_s2 = $jml_jenjang_bimbingan[2][2];
		$prodi_s3 = $jml_jenjang_bimbingan[3][2];
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/cek_data_bkd';
		$parameter  = array('api_search' => array($kd_dosen, 'D', $ta, $smt));
		$jml = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		
		if ($jml == 0){
			if(count($dpa) > 0){
				//$kegiatan = "Dosen Pembimbing Akademik, mahasiswa "
				if($s0>0){
					$kegiatan = "Dosen Pembimbing Akademik, mahasiswa ".$prodi_s0.", Jenjang ".$jenjang_s0.", ".$jml_s0." Mahasiswa";
					$masa_penugasan = "1 Tahun";
					$bukti_penugasan = '-'; $bkt_dokumen = '-';
					$sks = floor((count($dpa)/12)*1);
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
					$parameter  = array('api_search' => array($kd_dosen, 'D', $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $sks, $bkt_dokumen, $ta, $smt, 'LANJUTKAN','100', $kd_ta, $kd_smt));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
				if($s1>0){
					$kegiatan = "Dosen Pembimbing Akademik, mahasiswa ".$prodi_s1.", Jenjang ".$jenjang_s1.", ".$jml_s1." Mahasiswa";
					$masa_penugasan = "1 Tahun";
					$bukti_penugasan = '-'; $bkt_dokumen = '-';
					$sks = floor((count($dpa)/12)*1);
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
					$parameter  = array('api_search' => array($kd_dosen, 'D', $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $sks, $bkt_dokumen, $ta, $smt, 'LANJUTKAN','100', $kd_ta, $kd_smt));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
				if($s2>0){
					$kegiatan = "Dosen Pembimbing Akademik, mahasiswa ".$prodi_s2.", Jenjang ".$jenjang_s2.", ".$jml_s2." Mahasiswa";
					$masa_penugasan = "1 Tahun";
					$bukti_penugasan = '-'; $bkt_dokumen = '-';
					$sks = floor((count($dpa)/12)*1);
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
					$parameter  = array('api_search' => array($kd_dosen, 'D', $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $sks, $bkt_dokumen, $ta, $smt, 'LANJUTKAN','100', $kd_ta, $kd_smt));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
				if($s3>0){
					$kegiatan = "Dosen Pembimbing Akademik, mahasiswa ".$prodi_s3.", Jenjang ".$jenjang_s3.", ".$jml_s3." Mahasiswa";
					$masa_penugasan = "1 Tahun";
					$bukti_penugasan = '-'; $bkt_dokumen = '-';
					$sks = floor((count($dpa)/12)*1);
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
					$parameter  = array('api_search' => array($kd_dosen, 'D', $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $sks, $bkt_dokumen, $ta, $smt, 'LANJUTKAN','100', $kd_ta, $kd_smt));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}
				/*foreach ($dpa as $key) {
					$jenjang = strtoupper($key['NM_JENJANG']);
					if($jenjang == "S0"){

					}else($jenjang == "S1"){

					}else($jenjang == "S2"){

					}else($jenjang == "S3"){

					}
					# code...
				}*/
				/*$jenjang  = $this->jenjang($dpa[0]->KD_PRODI);
				$kegiatan = "Dosen Pembimbing Akademik, mahasiswa ".$dpa[0]->NM_PRODI.", Jenjang ".$jenjang.", ".count($dpa)." Mahasiswa";
				$masa_penugasan = "1 Tahun";
				$bukti_penugasan = '-'; $bkt_dokumen = '-';
				$sks = floor((count($dpa)/12)*1);
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
				$parameter  = array('api_search' => array($kd_dosen, 'D', $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $sks, $bkt_dokumen, $ta, $smt, 'LANJUTKAN','100', $kd_ta, $kd_smt));
				$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);*/
			}
		}
		redirect('bkd/dosen/bebankerja/cek_jabatan_struktural');
	}
	
	
	function cek_jabatan_struktural(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');

		$data = $this->setting->_is_pejabat_penunjang_nt();
		#print_r($data); die();
		if(!empty($data)){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/cek_data_bkd';
			$parameter  = array('api_search' => array($kd_dosen, 'D', $ta, $smt));
			$jml = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# algoritma yang digunakan untuk mengambil jabatan aktif
			# - panggil API untuk mengambil jabatan yang dosen pegang pada semester berjalan
			
			
			if ($jml <= 1){
				$kegiatan = $data['nama_jabatan'];
				$masa_penugasan = $data['masa_penugasan'];
				$bukti_penugasan = $data['bukti_penugasan']; $bkt_dokumen = $data['bukti_penugasan'];
				$sks = $data['sks_maks'];
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia';
				$parameter  = array('api_search' => array($kd_dosen, 'D', $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $sks, $bkt_dokumen, $ta, $smt, 'LANJUTKAN','100', $kd_ta, $kd_smt));
				$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}
			redirect('bkd/dosen/bebankerja/data/D');		
		}
		else{
			redirect('bkd/dosen/bebankerja/data/D');
		}
	}
	
	
	function jenjang($kd_prodi){
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/cek_jenjang_prodi';
		$parameter  = array('api_search' => array($kd_prodi));
		return $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);		
	}
	
	
	function rbkd($jenis, $param = null, $kd = null){
		$data['kode'] = $jenis;

		$data['thn'] = $this->input->post('thn');
		$data['smtr'] = $this->input->post('smt');

		if($data['thn'] != ''){
			# SET SESSION R_TA
			$this->session->unset_userdata('r_ta');
			$this->session->set_userdata('r_ta', $data['thn']);
			$this->session->unset_userdata('r_smt');
			$this->session->set_userdata('r_smt', $data['smtr']);
		}

		$data['ta'] = $this->session->userdata('r_ta');
		$data['smt'] = $this->session->userdata('r_smt');
		
		$kd_ta = $this->setting->_generate_kd_ta($data['ta']);
		$kd_smt = $this->setting->_generate_kd_ta($data['smt']);
		
		$data['is_crud'] = $this->setting->_is_crud_bkd_lalu($kd_ta, $kd_smt);
		

		$data['title'] = 'Rencana Beban Kerja Dosen Bidang '.$data['kode'];
		$kd_dosen = $this->session->userdata('kd_dosen');
		if($param == 'tambah'){
			$data['mode'] = 1;
		}else if($param == 'edit'){
			$data['mode'] = 2;
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_rbkd';
			$parameter  = array('api_search' => array($kd));
			$data['current_rbkd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}else{
			$data['mode'] = 3;
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/current_rbkd';
			$parameter  = array('api_search' => array($kd));
			$data['current_rbkd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_rbkd';
		$parameter  = array('api_search' => array($data['kode'], $kd_dosen, $data['ta'], $data['smt']));
		$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($this->session->all_userdata());
		#echo $data['ta'].'::'.$data['smt'];
		
		$data['jml_rbkd'] = count($data['data_beban']);
		if($data['kode'] == 'E' && ($this->session->userdata('jenis_dosen') == 'DS' || $this->session->userdata('jenis_dosen') == 'DT')){
			$this->output99->output_display('dosen/beban_profesor_message',$data);
		}else{
			$this->output99->output_display('dosen/data_rbkd',$data);		
		}
	
	}
	
	
	function rbkd_api($kode){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->_generate_ta($this->session->userdata('r_ta'));
		$smt = $this->_generate_smt($this->session->userdata('r_smt'));
		
		$tahun = $this->session->userdata('r_ta');
		$semester = $this->session->userdata('r_smt');
		
		if($kode == 'A'){
			# AMBIL DATA PENGAJARAN SEMESTER MENDATANG
			$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
			$parameter  = array(
							'api_kode' 		=> 58000,
							'api_subkode' 	=> 32,
							'api_search' 	=> array($ta, $smt , $kd_dosen)
						);
			$data['penawaran'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			#print_r($data['penawaran']); die();
			
			if(!empty($data['penawaran'])){

				# cek data rbkd pada tabel rbkd semester mendatang
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_rbkd';
				$parameter  = array('api_search' => array($kode, $kd_dosen, $tahun, $semester));
				$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				if(count($data['data_beban']) > 0){
					redirect('bkd/dosen/bebankerja/rbkd/A');
				}else{	
					# EXTRACT DATA DAN MASUKKIN
					foreach ($data['penawaran'] as $bk){
						$tim = $bk->TIM_AJAR;
						$jml_tim = explode('#', $tim); $jml_dosen = count($jml_tim);
						$jenis_kegiatan = "Mengajar Matakuliah ".$bk->NM_MK." Program Studi ".$bk->NM_PRODI.", Kelas ".$bk->KELAS_PARAREL.", ".$bk->SKS." sks, ".$bk->TERISI." Mahasiswa, ".$jml_dosen." Dosen";
						$jenjang = $this->jenjang($bk->KD_PRODI);
						$sks = $bk->SKS;
						$jml_mhs = $bk->TERISI;
						$sks_rule = round($this->aturan_beban_sks($jenjang, $sks, $jml_mhs, $bk->KD_KELAS),2);
						$masa_penugasan = "1 Semester";
						$bukti_penugasan = '-'; $bkt_dokumen = '-';
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_rbkd';
						$parameter  = array('api_search' => array('A', $kd_dosen, $jenis_kegiatan, $bukti_penugasan, $sks_rule, $masa_penugasan, $tahun, $semester, '', $ta, $smt));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					}
					redirect('bkd/dosen/bebankerja/rbkd/A');
				}				
			}else{
				redirect('bkd/dosen/bebankerja/rbkd/A');
			}		
		}else if ($kode == 'B'){
			redirect('bkd/dosen/bebankerja/rbkd/B');
		}else if ($kode == 'C'){
			redirect('bkd/dosen/bebankerja/rbkd/C');
		}else if ($kode == 'D'){
			# CEK DPA 
			$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
			$parameter  = array('api_kode' => 28000,'api_subkode' => 2,'api_search' => array($kd_dosen));
			$dpa = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_rbkd';
			$parameter  = array('api_search' => array($kode, $kd_dosen, $tahun, $semester));
			$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# kegiatan
			$jenjang  = $this->jenjang($dpa[0]->KD_PRODI);
			$kegiatan = "Dosen Pembimbing Akademik, mahasiswa ".$dpa[0]->NM_PRODI.", Jenjang ".$jenjang.", ".count($dpa)." Mahasiswa";
			$masa_penugasan = "1 Tahun";
			$bukti_penugasan = '-'; $bkt_dokumen = '-';
			$sks = floor((count($dpa)/12)*1);
								
			if(count($data['data_beban']) > 0){
				# do nothing
			}else{
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_rbkd';
				$parameter  = array('api_search' => array('D', $kd_dosen, $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $tahun, $semester, '', $ta, $smt));
				$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}
			redirect('bkd/dosen/bebankerja/cek_jabatan_kedepan');
			
		}else if ($kode == 'E'){
			redirect('bkd/dosen/bebankerja/rbkd/E');
		}
	}
	
	
	# RBKD UNTUK JABATAN STRUKTURAL DOSEN
	function cek_jabatan_kedepan(){
		
		$kd_dosen = $this->session->userdata('kd_dosen');
		$ta = $this->_generate_ta($this->session->userdata('r_ta'));
		$smt = $this->_generate_smt($this->session->userdata('r_smt'));
		
		$tahun = $this->session->userdata('r_ta');
		$semester = $this->session->userdata('r_smt');
		
		$data = $this->setting->_is_pejabat_penunjang_nt();
		$tgl_laporan = $this->setting->_tanggal_akhir_laporan($ta, $smt, 'rbkd');
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_rbkd';
		$parameter  = array('api_search' => array('D', $kd_dosen, $tahun, $semester));
		$data['data_beban'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		# data kegiatan
		$kegiatan = $data['nama_jabatan'];
		$masa_penugasan = $data['masa_penugasan'];
		$bukti_penugasan = $data['bukti_penugasan']; $bkt_dokumen = $data['bukti_penugasan'];
		$sks = $data['sks_maks'];
		
		if(count($data['data_beban']) <= 1){
			if($data['tgl_selesai'] == '' || 
				date('d-m-Y', strtotime($data['tgl_selesai'])) > 
				date('d-m-Y', strtotime(str_replace('/','-', $tgl_laporan)))
			){
				
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_rbkd';
				$parameter  = array('api_search' => array('D', $kd_dosen, $kegiatan, $bukti_penugasan, $sks, $masa_penugasan, $tahun, $semester, '', $ta, $smt));
				$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}
		}
		redirect('bkd/dosen/bebankerja/rbkd/D');		
	}
	
	function simpan_rbkd(){
		$this->form_validation->set_rules('jenis_kegiatan','Jenis Kegiatan','required|xss_clean');
		#$this->form_validation->set_rules('bukti_penugasan','Bukti Penugasan','required|xss_clean');
		$this->form_validation->set_rules('sks','Jumlah SKS','required|xss_clean');
		#$this->form_validation->set_rules('masa_penugasan','Masa Penugasan','required|xss_clean');
		$kode = $this->input->post('kd_jbk');
		if($this->form_validation->run() == FALSE){
			$this->rbkd($kode, 'tambah');
		}else{
			$kd_dosen = $this->session->userdata('kd_dosen');
			$ta = $this->session->userdata('r_ta');
			$smt = $this->session->userdata('r_smt');
			$kd_ta = $this->_generate_ta($ta);
			$kd_smt = $this->_generate_smt($ta);
			$jenis_kegiatan = $this->input->post('jenis_kegiatan');
			$input = $this->input->post('bukti_penugasan');
			$sks = $this->input->post('sks');
			$masa_penugasan = $this->input->post('lama').' '.$this->input->post('masa');
			if(strpos($input,':')){
				$data = explode(':',$input);
				$bkt_penugasan = $data[1];
				$file = 'surat:'.$data[0];
			}else{
				$bkt_penugasan = $input;
				$file = '';
			}
			# simpan rbkd
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_rbkd';
			$parameter  = array('api_search' => array($kode, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks, $masa_penugasan, $ta, $smt, $file, $kd_ta, $kd_smt));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
		}
		redirect('bkd/dosen/bebankerja/rbkd/'.$kode);
	}
	
	function update_rbkd(){
		$this->form_validation->set_rules('jenis_kegiatan','Jenis Kegiatan','required|xss_clean');
		#$this->form_validation->set_rules('bukti_penugasan','Bukti Penugasan','required|xss_clean');
		$this->form_validation->set_rules('sks','Jumlah SKS','required|xss_clean');
		#$this->form_validation->set_rules('masa_penugasan','Masa Penugasan','required|xss_clean');
		$kd = $this->input->post('kd_rbkd');
		$kode = $this->input->post('kd_jbk');
		if($this->form_validation->run() == FALSE){
			$this->rbkd($kode, 'edit', $kd);
		}else{
			$jenis_kegiatan = $this->input->post('jenis_kegiatan');
			$input = $this->input->post('bukti_penugasan');
			$sks = $this->input->post('sks');
			$masa_penugasan = $this->input->post('lama').' '.$this->input->post('masa');
			#echo $input; die();
			if($input == ''){
				$bkt_penugasan = $this->input->post('data_penugasan');
				$file = $this->input->post('data_file');
			}else{
				if(strpos($input,':')){
					$data = explode(':',$input);
					$bkt_penugasan = $data[1];
					$file = 'surat:'.$data[0];
				}else{
					$bkt_penugasan = $input;
					$file = '';
				}
			}
			# simpan rbkd
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_rbkd';
			$parameter  = array('api_search' => array($kd, $jenis_kegiatan, $bkt_penugasan, $sks, $masa_penugasan, $file));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		redirect('bkd/dosen/bebankerja/rbkd/'.$kode);
		#echo $kode;
	}
	
	function hapus_rbkd(){
		$kode = $this->uri->segment(5);
		$kd = $this->uri->segment(6);
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/hapus_rbkd';
			$parameter  = array('api_search' => array($kd));
			$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		redirect('bkd/dosen/bebankerja/rbkd/'.$kode);	
	}
	
	function test_surat(){
		$url	= 'tnde_public/';
		$parameter = array('api_kode' => 90009, 'api_subkode' => 1, 'api_search' => array());
		$data	= $this->mdl_bkd->get_api_surat($url.'tnde_surat_keluar/get_surat_keluar_all', 'json', 'POST', $parameter);
		print_r($data);
	}
	
	function editcepat(){
		$data = array(
			'id' => $this->input->post('id'),
			'field' =>  $this->input->post('field'),
			'value' =>  $this->input->post('value')
		);
		
		#echo json_encode($data);
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
		$parameter  = array('api_kode' => 100000, 'api_subkode' => 1, 'api_search' => array($data['id'], $data['field'], $data['value']));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
	}
	
	function editcepat_rbkd(){
		$data = array(
			'id' => $this->input->post('id'),
			'field' =>  $this->input->post('field'),
			'value' =>  $this->input->post('value')
		);
		
		#echo json_encode($data);
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
		$parameter  = array('api_kode' => 100000, 'api_subkode' => 4, 'api_search' => array($data['id'], $data['field'], $data['value']));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
	}
	
	function editcepatprof(){
		$data = array(
			'id' => $this->input->post('id'),
			'field' =>  $this->input->post('field'),
			'value' =>  $this->input->post('value')
		);
		
		#echo json_encode($data);
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
		$parameter  = array('api_kode' => 100000, 'api_subkode' => 2, 'api_search' => array($data['id'], $data['field'], $data['value']));
		$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
	}
	
	
	# GET DATA FILE BLOB
	function getFileBlob($table, $kolom, $kode){
		$get_foto = $this->blob->getBlob($table, $kolom, $kode);
		$foto1 = base64_decode($get_foto[0][$kolom]);
		$n1 = substr($foto1, -200);
		$n2  = substr($foto1, 0, strlen($foto1) - 200);
		if($kolom == 'BLOB_PENUGASAN'){ $field = 'FILE_PENUGASAN'; }else{ $field = 'FILE_CAPAIAN'; }
		$ext = $this->blob->extensi('BKD_BEBAN_KERJA', $field, $kode);	
		#echo $ext;
		if($ext == 'pdf' || $ext == 'PDF'){
			header("Content-type: application/pdf");
			echo base64_decode($n1.$n2);			
		}else{
			header("Content-type: image/jpg");
			echo base64_decode($n1.$n2);
		}
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
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
					$parameter  = array('api_kode' => 1000, 'api_subkode' => 3, 'api_search' => array($kd, $isi, ''));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}break;
				case "cari":{
					# UPDATE ISI BKT PENUGASAN DENGAN MENCARI FILE
					$isi = $this->input->post('surat');
					$data = explode(':', $isi);
					$bkt = $data[1]; $url = 'surat:'.$data[0];
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
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
							redirect('bkd/dosen/bebankerja/data/'.$kode.'/'.$kd.'/penugasan-upload/'.$data['error']);
						}
						else{
							$data['success'] = $this->upload->data();
							$filename = $data['success']['file_name'];
							$ext = end((explode(".", $_FILES['file_upload']['name'])));
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
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
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
					$parameter  = array('api_kode' => 1000, 'api_subkode' => 4, 'api_search' => array($kd, $isi, ''));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}break;
				case "cari":{
					# UPDATE KINERJA
					$isi = $this->input->post('surat');
					$data = explode(':', $isi);
					$bkt = $data[1]; $url = 'surat:'.$data[0];
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
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
							redirect('bkd/dosen/bebankerja/data/'.$kode.'/'.$kd.'/kinerja-upload/'.$data['error']);
						}
						else{
							$data['success'] = $this->upload->data();
							$filename = $data['success']['file_name'];
							$ext = end((explode(".", $_FILES['file_upload']['name'])));
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
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
		redirect('bkd/dosen/bebankerja/data/'.$kode.'/'.$kd);
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
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
					$parameter  = array('api_kode' => 1001, 'api_subkode' => 1, 'api_search' => array($kd, $isi));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}break;
				case "cari":{
					# UPDATE ISI BKT PENUGASAN DENGAN MENCARI FILE
					$isi = $this->input->post('surat');
					$data = explode(':', $isi);
					$bkt = $data[1]; $url = 'surat:'.$data[0];
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
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
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
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
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
					$parameter  = array('api_kode' => 1001, 'api_subkode' => 2, 'api_search' => array($kd, $isi));
					$this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				}break;
				case "cari":{
					$isi = $this->input->post('surat');
					$data = explode(':', $isi);
					$bkt = $data[1]; $url = 'surat:'.$data[0];
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
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
							$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_dokumen';
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
	
	
	
	function get_nama_partner($kode){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2001, 'api_subkode' => 2, 'api_search' => array($kode));
		$staff = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);		
		#$staff = $this->mdl_bkd->get_api_surat($url.'/tnde_pegawai/get_pegawai', 'json', 'POST', $parameter);
		if(count($staff) > 0){
			foreach ($staff as $dsn);
			return $dsn['NM_PGW_F'];
		}else{
			$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
			$parameter	= array('api_kode' => 26000, 'api_subkode' => 26, 'api_search' => array($kode));
			$mhs 		= $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(count($mhs) > 0){
				foreach ($mhs as $mh);
				return $mh->NAMA;
			}else{
				return $kode;
			}
		}
		
	}
	
	function hapus_partner($kode, $jenis, $partner){
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/partner_kinerja';
		$parameter  = array('api_kode' => 1800, 'api_subkode' => 99, 'api_search' => array($kode, $jenis, $partner));
		$a= $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		redirect($_SERVER['HTTP_REFERER']);
		#redirect('bkd/dosen/bebankerja/publikasi/detail/'.$kode);
	}
	
	function _generate_ta($ta){
		return substr($ta, 0,4);
	}
	
	function _generate_smt($smt){
		if($smt == 'GENAP') return 2;
		else return 1;
	}
	
	function cek(){
		$ta = '2013/2014';
		$smt = 2;
		$this->setting->_cek_tgl($ta, $smt);
	}
	
	function dosen_api(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd_dosen));
		$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	
	function ngecek_jabatan(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$kd_ta = $this->session->userdata('kd_ta');
		$kd_smt = $this->session->userdata('kd_smt');
		print_r($this->setting->_cek_jab_struk($kd_dosen, $kd_ta, $kd_smt));
	}
	
	function cek_sks_jabatan(){
		
		$kd_jab 	= 'ST000100';
		$api_url 	= URL_API_BKD.'bkd_dosen/jabatan';
		$parameter  = array('api_kode' => 20000, 'api_subkode' => 1, 'api_search' => array($kd_jab));
		$data 		= $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data);
	}
	
	# PEREMAJAAN :: 22/12/2014
	function narasumber($param = '', $kd = ''){
		$data['title'] = "Narasumber/Pembicara Kegiatan";
		$kd_dosen = $this->session->userdata('id_user');

		$api_url 	= URL_API_BKD.'bkd_beban_kerja/tingkat';
		$parameter  = array('api_kode'=>1000, 'api_subkode'=>1, 'api_search' => array());
		$data['kategori'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		if(isset($_POST['thn'])){
			$this->session->unset_userdata('ta');
			$this->session->set_userdata('ta', $_POST['thn']);
			$this->session->unset_userdata('smt');
			$this->session->set_userdata('smt', $_POST['smt']);
		}
		$ta = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		
		
		# GENERATE TA DAN SMT
		$kd_ta = $this->setting->_generate_kd_ta($ta);
		$kd_smt = $this->setting->_generate_kd_smt($smt);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode'=>2000, 'api_subkode'=>2, 'api_search' => array($kd_dosen, $kd_ta, $kd_smt));
		$data['narasumber'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		if($param == ''){
			$this->output99->output_display('dosen/data_narasumber',$data);
		}else{
			if($param == 'edit'){
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
				$parameter  = array('api_kode'=>2000, 'api_subkode'=>3, 'api_search' => array($kd));
				$data['curr_keg'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			}else if($param == 'delete'){
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
				$parameter  = array('api_kode'=>2001, 'api_subkode'=>3, 'api_search' => array($kd));
				$data['curr_keg'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
				redirect('bkd/dosen/bebankerja/narasumber');
			}else{
				if($param == 'err'){
					$data['msg'] = 'Eksekusi gagal dilakukan';
				}else{
					$data['msg'] = 'Eksekusi berhasil dilakukan';				
				}
			}
			$this->output99->output_display('dosen/form_narasumber',$data);				
		}
	
	}	
	
	function akademik($param = '', $kd = ''){
		$data['title'] = "Kegiatan Akademik";
		$kd_dosen = $this->session->userdata('id_user');
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_kategori';
		$parameter  = array('api_search' => array('F'));
		$data['kategori'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode'=>1000, 'api_subkode'=>1, 'api_search' => array($kd_dosen));
		$data['kegiatan'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		
		if($param == ''){
			$this->output99->output_display('dosen/data_kegiatan_akademik',$data);
		}else{
			if($param == 'edit'){
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
				$parameter  = array('api_kode'=>1000, 'api_subkode'=>2, 'api_search' => array($kd));
				$data['curr_keg'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			}else if($param == 'delete'){
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
				$parameter  = array('api_kode'=>1001, 'api_subkode'=>3, 'api_search' => array($kd));
				$data['curr_keg'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
				redirect('bkd/dosen/bebankerja/akademik');
			}else{
				if($param == 'err'){
					$data['msg'] = 'Eksekusi gagal dilakukan';
				}else{
					$data['msg'] = 'Eksekusi berhasil dilakukan';				
				}
			}
			$this->output99->output_display('dosen/form_keg_akademik',$data);				
		}
	
	}
	
	function simpanNarasumber(){
		$data = array(
			'KD_TINGKAT' => $this->input->post('kd_tingkat'),
			'JUDUL_ACARA' => str_replace("'","''",$this->input->post('judul_acara')),
			'TGL_MULAI' => $this->input->post('tgl_mulai'),
			'TGL_SELESAI' => $this->input->post('tgl_selesai'),
			'LAMAN_PUBLIKASI' => $this->input->post('laman_publikasi'),
			'LOKASI_ACARA' => $this->input->post('lokasi_acara'),
			'TGL_LOG' => date('d/m/Y H:i:s'),
			'KD_DOSEN' => $this->session->userdata('id_user'),
			'KD_TA' => $this->session->userdata('kd_ta'),
			'KD_SMT' => $this->session->userdata('kd_smt'),
		);

		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode'=>2001, 'api_subkode'=>1, 'api_search' => $data);
		$ss = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if($ss){
			redirect('bkd/dosen/bebankerja/narasumber');
		}else{
			redirect('bkd/dosen/bebankerja/narasumber/err');	
		}
	}
	
	function simpanAkademik(){
		$data = array(
			'KD_KAT' => $this->input->post('kd_kat'),
			'JUDUL_KEGIATAN' => str_replace("'","''",$this->input->post('judul_kegiatan')),
			'TGL_MULAI' => $this->input->post('tgl_mulai'),
			'TGL_SELESAI' => $this->input->post('tgl_selesai'),
			'SUMBER_DANA' => $this->input->post('sumber_dana'),
			'JUMLAH_DANA' => $this->input->post('jumlah_dana'),
			'LAMAN_KEGIATAN' => $this->input->post('laman_kegiatan'),
			'LOKASI_KEGIATAN' => $this->input->post('lokasi_kegiatan'),
			'TGL_LOG' => date('d/m/Y H:i:s'),
			'KD_DOSEN' => $this->session->userdata('id_user'),
		);
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode'=>1001, 'api_subkode'=>1, 'api_search' => $data);
		$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if($simpan){
			redirect('bkd/dosen/bebankerja/akademik');
		}else{
			redirect('bkd/dosen/bebankerja/akademik/err');	
		}
	}
	
	
	function updateNarasumber(){
		$id = $this->input->post('kd_np');
		$data = array(
			'KD_TINGKAT' => $this->input->post('kd_tingkat'),
			'JUDUL_ACARA' => str_replace("'","''",$this->input->post('judul_acara')),
			'TGL_MULAI' => $this->input->post('tgl_mulai'),
			'TGL_SELESAI' => $this->input->post('tgl_selesai'),
			'LAMAN_PUBLIKASI' => $this->input->post('laman_publikasi'),
			'LOKASI_ACARA' => $this->input->post('lokasi_acara'),
			'TGL_LOG' => date('d/m/Y H:i:s'),
		);
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode'=>2001, 'api_subkode'=>2, 'api_search' => array($id, $data));
		$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if($simpan){
			redirect('bkd/dosen/bebankerja/narasumber');
		}else{
			redirect('bkd/dosen/bebankerja/narasumber/err');	
		}
	}
	
	
	function updateAkademik(){
		$id = $this->input->post('kd_ka');
		$data = array(
			'KD_KAT' => $this->input->post('kd_kat'),
			'JUDUL_KEGIATAN' => str_replace("'","''",$this->input->post('judul_kegiatan')),
			'TGL_MULAI' => $this->input->post('tgl_mulai'),
			'TGL_SELESAI' => $this->input->post('tgl_selesai'),
			'SUMBER_DANA' => $this->input->post('sumber_dana'),
			'JUMLAH_DANA' => $this->input->post('jumlah_dana'),
			'LAMAN_KEGIATAN' => $this->input->post('laman_kegiatan'),
			'LOKASI_KEGIATAN' => $this->input->post('lokasi_kegiatan'),
			'TGL_LOG' => date('d/m/Y H:i:s'),
			'KD_DOSEN' => $this->session->userdata('id_user'),
		);
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kegiatan_akademik';
		$parameter  = array('api_kode'=>1001, 'api_subkode'=>2, 'api_search' => array($id, $data));
		$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if($simpan){
			redirect('bkd/dosen/bebankerja/akademik');
		}else{
			redirect('bkd/dosen/bebankerja/akademik/err');	
		}
	}
	
	
	function zzz(){
		$ta = $this->session->userdata('kd_ta');
		$smt = $this->session->userdata('kd_smt');
		$kd = '197701032005011003';
		$cek = $this->api->get_api_json(URL_API_SIA.'sia_absensi/data_search', 'POST', array('api_kode'=>70000,'api_subkode' => 7, 'api_search' => array($ta, $smt, $kd)));
		print_r($cek);
	}
	
	function get_prodi(){
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
	function testing(){
		/*$kd_dosen = $this->session->userdata('kd_dosen');
		$smt = $this->session->userdata('kd_smt');
		$ta = $this->session->userdata('kd_ta');
		$thn = $this->session->userdata('ta');*/

		$kd_dosen = 196603051994031003;
		$smt = 2;
		$ta = "2016";
		//$thn = "2016/2017";

		if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array(
						'api_kode' 		=> 58000,
						'api_subkode' 	=> 32,
						'api_search' 	=> array($ta, $smt , $kd_dosen)
					);
		$data['penawaran'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		foreach ($data['penawaran'] as $key) {
			$kd_kelas = $key->KD_KELAS;
			$jenis_mk = $this->_cek_jenis_mk($kd_kelas);
			echo "<pre>";
			/*echo $kd_dosen."<br>";
			echo $smt."<br>";
			echo $ta."<br>";*/
			print_r($key->NM_MK); echo "<br>";
			print_r($jenis_mk);
			echo "</pre>";
		}
		/*$kd_kelas = $data['penawaran'][0]->KD_KELAS;
		$jenis_mk = $this->_cek_jenis_mk($kd_kelas);*/
		//$kd_kelas = $data['penawaran'][0]->KD_KELAS;
		
	}
	function testing2(){
		$kd_dosen = 195910011987031002;
		//$kd_dosen = 197701032005011003;
		$smt = 1;
		$ta = "2016";
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array(
						'api_kode' 		=> 58000,
						'api_subkode' 	=> 32,
						'api_search' 	=> array($ta, $smt , $kd_dosen)
					);
		$data['penawaran'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		foreach ($data['penawaran'] as $key) {
			$kd_kelas = $key->KD_KELAS;
			$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
			$parameter  = array('api_kode'=>58000, 'api_subkode' => 6, 'api_search' => array($kd_kelas));
			$data1['test'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			echo "<pre>";
		print_r($data1);
		echo "</pre>";
		}
		
	}
	function cek_smt_sia(){
		$kd_smt = $this->session->userdata('kd_smt');
		$api_url = URL_API_SIA.'sia_krs/data_view';
		$parameter = array('api_kode'=>51000, 'api_subkode' => 1, 'api_search' => array($this->session->userdata('kd_smt')));
		$data = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		/*$this->cek_smt = $this->api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST', 
		array('api_kode'=>51000, 'api_subkode' => 3, 'api_search' => array($this->session->userdata('kd_smt'))));*/
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}
	function cek_ta_sia(){
		$kd_ta = $this->session->userdata('kd_ta');
		$api_url = URL_API_SIA.'sia_krs/data_view';
		$parameter = array('api_kode'=>50000, 'api_subkode' => 2, 'api_search' => array($this->session->userdata('kd_ta')));
		$data = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		/*$this->cek_smt = $this->api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST', 
		array('api_kode'=>51000, 'api_subkode' => 3, 'api_search' => array($this->session->userdata('kd_smt'))));*/
		echo "<pre>";
		print_r($data);
		echo "</pre>";
	}

	function coba_api(){
		$data = $this->s00_lib_api->get_api_json(URL_API_SIA.'sia_krs/data_search', 'POST',
		array('api_kode'=>52000, 'api_subkode'=>12, 'api_search' => array('01/06/2017','22607')));
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
		$bimbingan = $this->cek_mhs_bimbingan_ta();
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

	}

	function current_data_bimbingan_ta()
	{
		$bimbingan = $this->cek_mhs_bimbingan_ta();
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

		return $data;
	}

	function current_jml_mhs_bimbingan_ta()
	{
		$tmp_mhs = $this->current_data_bimbingan_ta();
		$jml_mhs = array();
		foreach ($tmp_mhs as $key) {
			$nim = $key['NIM'];
			$mhs = $this->get_data_mhs($nim);
			if(isset($jml_mhs[$mhs[0]['KD_JENJANG']])){
				$jml_mhs[$mhs[0]['KD_JENJANG']]['JUMLAH']++;
			}else{
				$jml_mhs[$mhs[0]['KD_JENJANG']]['JENJANG'] = $mhs[0]['NM_JENJANG'];
				$jml_mhs[$mhs[0]['KD_JENJANG']]['JUMLAH'] = 1;

			}
		}

		return $jml_mhs;
	}

	function cek_mhs_bimbingan_ta()
	{
		//$url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/';
		//$data['jadwal'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalTA','json','POST', 
		//array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode)));
		
		//catatan
		//subkode untuk jenis-jenis bimbingan ta :
		//subkode = 0 untuk "jadwal ujian komprehensif"
		//subkode = 1 untuk "jadwal seminar proposal"
		//subkode = 2 untuk "jadwal ujian tugas akhir"
		//subkode = 3 untuk "jadwal ujian tertutup"
		//subkode = 4 untuk "jadwal ujian terbuka"

		//untuk fungsi yang ini, yang baru digunakan adalah subkode = 2, yakni untuk tugas akhir
		//selnajutnya subkode harus dibuat dinamis agar dapat digunkan untuk mengambil data pada ujian ta lainnya
		$kd_dosen = $this->session->userdata('kd_dosen');
		$data = $this->s00_lib_api->get_api_json(
					'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/sia_skripsi_bimbingan/jadwalTA',
					'POST',
					array(
						'api_kode'		=> 1000,
						'api_subkode'	=> 1,
						'api_search'	=> array($kd_dosen, '2')
					)
			);

		//return $data;
		return $data;
	}
	function cek_mhs_ujian_ta(){
		//$url = 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/';
		//$data['jadwal'] = $this->mdl_skripsi->get_api('sia_skripsi_bimbingan/jadwalTA','json','POST', 
		//array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($this->session->userdata('id_user'), $periode)));
		
		//catatan
		//subkode untuk jenis-jenis bimbingan ta :
		//subkode = 0 untuk "jadwal ujian komprehensif"
		//subkode = 1 untuk "jadwal seminar proposal"
		//subkode = 2 untuk "jadwal ujian tugas akhir"
		//subkode = 3 untuk "jadwal ujian tertutup"
		//subkode = 4 untuk "jadwal ujian terbuka"

		//untuk fungsi yang ini, yang baru digunakan adalah subkode = 2, yakni untuk tugas akhir
		//selnajutnya subkode harus dibuat dinamis agar dapat digunkan untuk mengambil data pada ujian ta lainnya
		
		$kd_dosen = $this->session->userdata('kd_dosen');
		$data = $this->s00_lib_api->get_api_json(
					'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/sia_skripsi_bimbingan/jadwalTA',
					'POST',
					array(
						'api_kode'		=> 1000,
						'api_subkode'	=> 2,
						'api_search'	=> array($kd_dosen, '2')
					)
			);

		//return $data;
		return $data;
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
		function get_data_mhs_test($nim="1320012032")
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
			echo "<pre>";
		 	print_r($data);
		 	echo "</pre>";
		}
		function coba_api_kurikulum(){
			//$kd_prodi=22117;//s2 prodi fisika
			$kd_prodi = 22607;//s2 teknik informatika

			$data['kurpilih'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
			array('api_kode'=>38000, 'api_subkode' => 3, 'api_search' => array('S1TIF16')));

			/*$data['kursemua'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
				array('api_kode'=>38000, 'api_subkode' => 7, 'api_search' => array($kd_prodi)));*/
			
			/*$data['kurpilih'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
			array('api_kode'=>38000, 'api_subkode' => 8, 'api_search' => array($kd_prodi)));*/

			$data['kurdaftar'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
				array('api_kode'=>40000, 'api_subkode' => 15, 'api_search' => array($data['kurpilih'][0]['KD_KUR'])));
			/*$data['kursemua'] = $this->api->get_api_json(URL_API_SIA.'sia_kurikulum/data_search', 'POST', 
			array('api_kode'=>38000, 'api_subkode' => 7, 'api_search' => array($kd_prodi)));*/
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}
		function kurikulum_mahasiswa($nim){
			$data = $this->s00_lib_api->get_api_json(
					URL_API_SIA.'/sia_kurikulum/data_search',
					'POST',
					array(
						'api_kode'		=> 40001,
						'api_subkode'	=> 3,
						'api_search'	=> array($nim)
					)
			);

			return $data[0]['KD_KUR'];
		}	
		function cek_bimbingan_kkn(){
		//$this->nip 		= $this->session->userdata('id_user');
		//s$kd_ta = $this->session->userdata('kd_ta');
		//$kd_smt = $this->session->userdata('kd_smt');

		$kd_ta = '2015';
		$kd_smt = '3';

		$this->nip = "197510242009121002";
		$url = 'kkn_dosen/data_search';
		$api_url = URL_API_KKN.$url;
		$parameter = array(	'api_kode' => 201400,
					'api_subkode' => 1,
					'api_search' => array('KKN.KKN_DPL2014','ID_DPL',array('NIP'=>$this->nip)));

		$data = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		$id_dpl2 = $data[0]['ID_DPL'];
		/*print_r($data);*/

		$parameter2 = array(	'api_kode' => 201402,
						'api_subkode' => 1,
						'api_search' => array($kd_ta,$kd_smt,$id_dpl2));
		$data2 = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter2);
		echo "<pre>";
		print_r($data2);
		echo "</pre>";
		echo "<br>"." Jumlah kelompk bimbingan pada KKN tahun ".$kd_ta." adalah ".count($data2)." kelompok";
		/*$kelompok_kkn = $this->mdl_kkn->get_api(
				'kkn_dosen/data_search',
				'json',
				'POST',
				array(	'api_kode' => 201402,
						'api_subkode' => 1,
						'api_search' => array($kd_ta,$kd_smt,$id_dpl2))
			);*/

		/*$id_dpl = $this->mdl_kkn->get_api(
			'kkn_dosen/data_search',
			'json',
			'POST',
			array(	'api_kode' => 201400,
					'api_subkode' => 1,
					'api_search' => array('KKN.KKN_DPL2014','ID_DPL',array('NIP'=>$this->nip)))
		);*/
	}
		function cek_bimbingan_kp(){
			$subkode =1;
			$periode =2;
			$kd_dosen = $this->session->userdata('id_user');
			//$kd_dosen = "197608122009011015";
			$url = 'sia_kp_bimbingan/jadwalTA';
			$api_url = 'http://service.uin-suka.ac.id/servkerjapkt/sia_kp_public/'.$url;
			$parameter = array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => array($kd_dosen, $periode));
			$data['jadwal'] = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}
		function lihat_peserta_kkn(){
			$url = 'kkn_dosen/data_search';
			$api_url = URL_API_KKN.$url;
			$parameter = array(	'api_kode' => 201401,
							'api_subkode' => 2,
							'api_search' => array(/*$kel['ID_KELOMPOK']*/'1893'));

			$member_kkn = $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
			echo "<pre>";
			print_r($member_kkn);
			echo "</pre>";
		}
		function get_mhs_pa(){
			$kd_dosen 	= $this->session->userdata('id_user');
			//$kd_dosen = "198205112006042002";
			$status 	= 'A';
			$api_url 	= URL_API_SIA.'sia_mahasiswa/data_search';
			$parameter 	= array('api_kode' => 28000, 'api_subkode' => 13, 'api_search' => array($status, $kd_dosen));
			$data 		= $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			echo "<pre>";
			print_r($data);
			echo "</pre>";
		}
		function test_api_prodi(){
			$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
			$data['fakultas']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);
			foreach ($data['fakultas'] as $d) {
				$KD_FAK = $d['KD_FAK'];
				$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($KD_FAK));
				$data['prodi']= $this->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);
				foreach ($data['prodi'] as $key) {
					echo "<pre>";
					print_r($key['KD_PRODI']." = ".$key['NM_PRODI']);
					echo "</pre>";
				}
			}
			
		}
		function get_data_dosen_test(){
			$kd_dosen = $this->session->userdata("id_user");
			$ta = $this->session->userdata("ta");
			$smt = $this->session->userdata("smt");	
			$kd = $this->session->userdata("kd_dosen");
			$data = array('ta'=> $ta, 'smt'=>$smt);
			/* load main content */
			$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
			$parameter  = array('data_search' => array($kd));
			$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			echo "<pre>";
			print_r($data['dosen']);
			echo "</pre>";
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

			$tgl_mulai_smt = $data[0]['TGL_MULAI_SMT'];
			$tgl_akhir_smt = $data[0]['TGL_AKHIR_SMT'];

			$tgl_m = str_replace("/", "-", $tgl_mulai_smt);
			$tgl_mulai_baru = date('d/m/Y', strtotime($tgl_mulai_smt));
			$tgl_a = str_replace("/", "-", $tgl_akhir_smt);
			$tgl_akhir_baru = date('Y-m-d', strtotime($tgl_a));
			// Menambah bulan ini + semua bulan pada tahun sebelumnya
			$tgl_mm = strtotime($tgl_mulai_baru);
			$thn_m = date('Y', $tgl_mm);
			$bln_m = date('m', $tgl_mm);

			$tgl_aa = strtotime($tgl_akhir_baru);
			$thn_a = date('Y', $tgl_aa);
			$bln_a = date('m', $tgl_aa);

	        $numBulan = 1+($thn_a - $thn_m)*12;
	        $numBulan += $bln_a-$bln_m;

	        return $numBulan;
			/*echo "<pre>";
			print_r($tgl_mulai_baru." s/d ".$tgl_akhir_baru." jarak bulan : ".$numBulan);
			// print_r($thn_m." : ".$thn_a);
			//print_r($data);
			echo "</pre>";*/
		}

		function jml_mhs_bimbingan_kkn()
		{
			//init smt , ta , nip
			$nip 	= $this->session->userdata('id_user'); //'197510242009121002';//
			$smt 	= array(1,2,3);
			$ta 	= $this->session->userdata('kd_ta');
			$kd_current_smt = $this->session->userdata('kd_smt');
			$jumlah_mahasiswa = 0;

			//GET ID DPL :
			
			$url 		= 'kkn_dosen/data_search';
			$api_url 	= URL_API_KKN.$url;
			$parameter 	= array(
				'api_kode' => 201400,
				'api_subkode' => 1,
				'api_search' => array('KKN.KKN_DPL2014','ID_DPL',array('NIP' => $nip)));

			$dpl = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

			if($dpl){
				//GET LIST KELOMPOK KKN
				$id_dpl 	= $dpl[0]['ID_DPL'];

				foreach ($smt as $kd_smt) {
					$parameter2 = array(
						'api_kode' => 201402,
						'api_subkode' => 1,
						'api_search' => array($ta,$kd_smt,$id_dpl)
					);

					$kelompok = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter2);

					foreach ($kelompok as $kel) {
						//GET LIST DATA MAHASISWA PER KELOMPOK
						$id_kelompok = $kel['ID_KELOMPOK'];

						$parameter 	= array(	'api_kode' => 201401,
										'api_subkode' => 2,
										'api_search' => array($id_kelompok));

						$member 	= $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
						foreach ($member as $mbr) {
							//CEK SEMESTER MAHASISWA KKN
							$nim 	= $mbr['NIM'];
							$tgl 	= str_replace('-', '/', $mbr['TGL_DAFTAR']);
							$data_mhs 	= $this->get_data_mhs($nim);
							$kd_prodi 	= $data_mhs[0]['KD_PRODI'];

							$semester 	= $this->periode_semester($tgl, $kd_prodi);

							$kd_ta_data 	= $semester[0]['KD_TA'];
							$kd_smt_data 	= $semester[0]['KD_SMT'];

							//JIKA SESUAI DENGAN SEMESTER SAAT INI MAKA :
							if($ta == $kd_ta_data && $kd_current_smt == $kd_smt_data){
								$jumlah_mahasiswa++;
							}
							//$jumlah_mahasiswa++;

						}

					}
				}
				
			}

			return $jumlah_mahasiswa;
		}

		function jml_mhs_bimbingan_kp()
		{
			$kd_dosen 	= $this->session->userdata('kd_dosen');
			$periode 	= 2;
			$ta 		= $this->session->userdata('kd_ta');
			$kd_smt 	= $this->session->userdata('kd_smt');
			$jumlah_mahasiswa = 0;

			//subkode untuk pembimbing : 1, untuk penguji : 2

			$url 		= 'sia_kp_bimbingan/jadwalTA';
			$api_url 	= 'http://service.uin-suka.ac.id/servkerjapkt/sia_kp_public/'.$url;
			$parameter 	= array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($kd_dosen, $periode));
			$data 	 	= $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);
			if($data){
				foreach ($data as $key) {
					$nim 	= $key['NIM'];
					$tgl 	= str_replace('-', '/', $key['TGL_MUNA']);

					$data_mhs 	= $this->get_data_mhs($nim);
					$kd_prodi 	= $data_mhs[0]['KD_PRODI'];

					$semester 	= $this->periode_semester($tgl, $kd_prodi);

					$kd_ta_data 	= $semester[0]['KD_TA'];
					$kd_smt_data 	= $semester[0]['KD_SMT'];

					if($ta == $kd_ta_data && $kd_smt == $kd_smt_data){
						$jumlah_mahasiswa++;
					}

				}
			}

			return $jumlah_mahasiswa;
		}

		function jml_mhs_ujian_semprop()
		{
			$kd_dosen 	= $this->session->userdata('kd_dosen');
			$periode 	= 1;
			$ta 		= $this->session->userdata('kd_ta');
			$kd_smt 	= $this->session->userdata('kd_smt');
			$jumlah_mahasiswa = 0;

			$url 		= 'sia_skripsi_bimbingan/jadwalTA';
			$api_url 	= 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
			$parameter 	= array('api_kode' => 1000, 'api_subkode' => 2, 'api_search' => array($kd_dosen, $periode));
			$data 	 	= $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);

			if($data){
				foreach ($data as $key) {
					$nim 	= $key['NIM'];
					$tgl 	= str_replace('-', '/', $key['TGL_MUNA']);

					$data_mhs 	= $this->get_data_mhs($nim);
					$kd_prodi 	= $data_mhs[0]['KD_PRODI'];

					$semester 	= $this->periode_semester($tgl, $kd_prodi);

					$kd_ta_data 	= $semester[0]['KD_TA'];
					$kd_smt_data 	= $semester[0]['KD_SMT'];

					if($ta == $kd_ta_data && $kd_smt == $kd_smt_data){
						$jumlah_mahasiswa++;
					}

				}
			}
			return $jumlah_mahasiswa;

		}

		function jml_mhs_ujian_ta($periode = ''){

			// periode : 	 1 --> untuk ujian seminar proposal
			// 		 		 2 --> untuk ujian tugas akhir (munaqasah)

			$kd_dosen 	= $this->session->userdata('kd_dosen');
			$ta 		= $this->session->userdata('kd_ta');
			$kd_smt 	= $this->session->userdata('kd_smt');
			$jumlah_mahasiswa = 0;

			$url 		= 'sia_skripsi_bimbingan/jadwalTA';
			$api_url 	= 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
			$parameter 	= array('api_kode' => 1000, 'api_subkode' => 2, 'api_search' => array($kd_dosen, $periode));
			$data 	 	= $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);

			if($data){
				foreach ($data as $key) {
					$nim 	= $key['NIM'];
					$tgl 	= str_replace('-', '/', $key['TGL_MUNA']);

					$data_mhs 	= $this->get_data_mhs($nim);
					$kd_prodi 	= $data_mhs[0]['KD_PRODI'];

					$semester 	= $this->periode_semester($tgl, $kd_prodi);

					$kd_ta_data 	= $semester[0]['KD_TA'];
					$kd_smt_data 	= $semester[0]['KD_SMT'];

					if($ta == $kd_ta_data && $kd_smt == $kd_smt_data){
						$jumlah_mahasiswa++;
					}

				}
			}
			return $jumlah_mahasiswa;
		}
		function jml_mhs_bimbingan_ta($periode = ''){

			// periode : 	 1 --> untuk ujian seminar proposal
			// 		 		 2 --> untuk ujian tugas akhir (munaqasah)

			$kd_dosen 	= $this->session->userdata('kd_dosen');
			$ta 		= $this->session->userdata('kd_ta');
			$kd_smt 	= $this->session->userdata('kd_smt');
			$jumlah_mahasiswa = 0;

			$url 		= 'sia_skripsi_bimbingan/jadwalTA';
			$api_url 	= 'http://service.uin-suka.ac.id/servtugasakhir/sia_skripsi_public/'.$url;
			$parameter 	= array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($kd_dosen, $periode));
			$data 	 	= $this->s00_lib_api->get_api_json($api_url, 'POST', $parameter);

			if($data){
				foreach ($data as $key) {
					$nim 	= $key['NIM'];
					$tgl 	= str_replace('-', '/', $key['TGL_MUNA']);

					$data_mhs 	= $this->get_data_mhs($nim);
					$kd_prodi 	= $data_mhs[0]['KD_PRODI'];

					$semester 	= $this->periode_semester($tgl, $kd_prodi);

					$kd_ta_data 	= $semester[0]['KD_TA'];
					$kd_smt_data 	= $semester[0]['KD_SMT'];

					if($ta == $kd_ta_data && $kd_smt == $kd_smt_data){
						$jumlah_mahasiswa++;
					}

				}
			}
			return $jumlah_mahasiswa;
		}

		function auto_bimbingan_dosen(){
		 	//BIMBINGAN KKN
			$kkn = $this->jml_mhs_bimbingan_kkn();
		 	if($kkn <= 0){
				//do nothing :)
		 	}else{
		 		//cek apakah data sudah ada apa belum di dsatabase :
		 		//parameter : kd_jbk, kd_dosen, kd_ta, kd_smt, kd_kat
				$kd_kat 	= 9;
		 		$kd_jbk 	= 'A';
		 		$kd_dosen 	= $this->session->userdata('kd_dosen');
		 		$kd_ta 		= $this->session->userdata('kd_ta');
		 		$kd_smt 	= $this->session->userdata('kd_smt');

		 		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_bimbingan_mhs';
		 		$parameter	= array('api_search' => array($kd_kat, $kd_jbk, $kd_dosen, $kd_ta, $kd_smt));
		 		$kd_bk 		= $this->api->get_api_json($api_url, 'POST', $parameter);

				if($kd_bk){
					//ini nanti update BKD_DATA_PENDIDIKAN berdasarkan kd_bk yang didapat (yang diupdate jml_mhs)
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_jml_mhs_bimbingan';
					$parameter	= array('api_search' => array($kd_bk, $kkn));
					$update_bk	= $this->api->get_api_json($api_url, 'POST', $parameter);

				}else{
					//ini nanti insert data BKD_BEBAN_KERJA, BKD_REMUN_kINERJA, BKD_DATA_PENDIDIKAN
					$narasi = "Membimbing Kuliah Kerja Nyata";
					$sks_rule = 1;
					$smt = $this->session->userdata('kd_smt');
					$ta = $this->session->userdata('kd_ta');

					// $kd_jbk = 'A';
					$kd_dosen = $this->session->userdata('kd_dosen');
					$jenis_kegiatan = $narasi;
					/*$jenis_kegiatan ='test';*/
					$bukti_penugasan = '-';
					// $sks_penugasan = $sks_rule;
					$masa_penugasan = '1 Semester';
					$bkt_dokumen = '-';
					$kd_jbk 		= 'A';
					$bkt_penugasan 	= '-';
					$bkt_dokumen 	= '-';
					$rekomendasi 	= 'LANJUTKAN';
					$capaian 		= 100;
					$jml_jam 		= 1;
					$outcome 		= '-';
					$file_penugasan = '-';
					$file_capaian 	= '-';
					$thn = $this->session->userdata('ta');
					if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';

					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
					$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule, $masa_penugasan, $bkt_dokumen, $sks_rule, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$getid = $simpan;
						$nm_keg = $jenis_kegiatan;
						#simpan data pendidikan
						//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
						//akan berubah menjadi kd_kat untuk membimbing
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
						$parameter	= array('api_search' => array($getid, '9', $nm_keg, 'S1', '-', $kkn, 1, '1', '1', 'A', '1', '-', '-'));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					}
				}
		 	}

			//BIMBINGAN KP
			$kp = $this->jml_mhs_bimbingan_kp();

			if($kp <= 0){
				//do nothing
			}else{

				$kd_kat 	= 7;
				$kd_jbk 	= 'A';
				$kd_dosen 	= $this->session->userdata('kd_dosen');
				$kd_ta 		= 2014;//$this->session->userdata('kd_ta');
				$kd_smt 	= 1;//$this->session->userdata('kd_smt');

				$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_current_bimbingan_mhs';
				$parameter	= array('api_search' => array($kd_kat, $kd_jbk, $kd_dosen, $kd_ta, $kd_smt));
				$kd_bk 		= $this->api->get_api_json($api_url, 'POST', $parameter);

				if($kd_bk){
					//ini nanti update BKD_DATA_PENDIDIKAN berdasarkan kd_bk yang didapat (yang diupdate jml_mhs)
					$api_url 	= URL_API_BKD.'bkd_beban_kerja/update_jml_mhs_bimbingan';
					$parameter	= array('api_search' => array($kd_bk, $kp));
					$update_bk	= $this->api->get_api_json($api_url, 'POST', $parameter);

				}else{
					//ini nanti insert data BKD_BEBAN_KERJA, BKD_REMUN_kINERJA, BKD_DATA_PENDIDIKAN
					$narasi = "Membimbing Kuliah Praktek Lapangan";
					$sks_rule = 1;
					$smt 	= $this->session->userdata('kd_smt');
					$ta 	= $this->session->userdata('kd_ta');

					// $kd_jbk = 'A';
					$kd_dosen = $this->session->userdata('kd_dosen');
					$jenis_kegiatan = $narasi;
					/*$jenis_kegiatan ='test';*/
					$bukti_penugasan = '-';
					// $sks_penugasan = $sks_rule;
					$masa_penugasan = '1 Semester';
					$bkt_dokumen = '-';
					$kd_jbk 		= 'A';
					$bkt_penugasan 	= '-';
					$bkt_dokumen 	= '-';
					$rekomendasi 	= 'LANJUTKAN';
					$capaian 		= 100;
					$jml_jam 		= 1;
					$outcome 		= '-';
					$file_penugasan = '-';
					$file_capaian 	= '-';
					$thn = $this->session->userdata('ta');
					if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';

					$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
					$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule, $masa_penugasan, $bkt_dokumen, $sks_rule, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
					$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					if($simpan){
						$getid = $simpan;
						$nm_keg = $jenis_kegiatan;
						#simpan data pendidikan
						//catatan: $parameter ke dua adalah kd_kat, parameter ini nantinya otomatis, misalya ketika ada status sebagai pembimbing, maka
						//akan berubah menjadi kd_kat untuk membimbing
						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_pendidikan_ta';
						$parameter	= array('api_search' => array($getid, '7', $nm_keg, 'S1', '-', $kp, 1, '1', '1', 'A', '1', '-', '-'));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
					}
				}				
			}
		}

		function get_satuan_data_pendidikan(){
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_satuan_data_remun';
			$parameter	= array('api_search' => array(9));
			$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			echo '<pre>';
			print_r($simpan);
			echo '</pre>';
		}

		function cek_fn(){
			$data = $this->jml_mhs_bimbingan_kkn();
			echo 'Jumlah Mahasiswa Bimbingan KKN Semester ini :'.$data;
			echo '<br>';
			$data = $this->jml_mhs_bimbingan_kp();
			echo 'Jumlah Mahasiswa Bimbingan KP Semester ini :'.$data;
			echo '<br>';
			$data = $this->jml_mhs_ujian_ta(1);
			echo 'Jumlah Mahasiswa Ujian Seminar Proposal Semester ini :'.$data;
			echo '<br>';
			$data = $this->jml_mhs_ujian_ta(2);
			echo 'Jumlah Mahasiswa Ujian Tugas Akhir Semester ini :'.$data;
			echo '<br>';
			$data = $this->jml_mhs_bimbingan_ta(1);
			echo 'Jumlah Mahasiswa Bimbingan Seminar Proposal Semester ini :'.$data;
			echo '<br>';
			$data = $this->jml_mhs_bimbingan_ta(2);
			echo 'Jumlah Mahasiswa Bimbingan Tugas Akhir Semester ini :'.$data;
			echo '<br>';
			//$this->auto_bimbingan_dosen();
			$data = $this->periode_semester('04/02/2015', '22607');
			echo '<br>';
			echo 'periode semester :';
			echo '<br>';
			echo '<pre>';
			print_r($data);
			echo '</pre>';

			$test = $this->current_jml_mhs_bimbingan_ta();
			echo '<br>';
			echo '<pre>';
			print_r($test);
			echo '</pre>';

		}

		function get_jumlah_mhs_dpa(){
			$mahasiswa = $this->api->get_api_json(URL_API_SIA.'sia_mahasiswa/data_search', 'POST', 
						array('api_kode'=>28000, 'api_subkode' => 6, 
						'api_search' => array('A', $this->session->userdata('id_user'))));

			$bimbingan_pa = array();
			foreach ($mahasiswa as $mhs) {
				if(isset($bimbingan_pa[strtoupper($mhs['NM_JENJANG'])])){
					$bimbingan_pa[strtoupper($mhs['NM_JENJANG'])]['JUMLAH']++;
				}else{
					$bimbingan_pa[strtoupper($mhs['NM_JENJANG'])]['JENJANG'] = strtoupper($mhs['NM_JENJANG']);
					$bimbingan_pa[strtoupper($mhs['NM_JENJANG'])]['NM_PRODI'] = $mhs['NM_PRODI'];
					$bimbingan_pa[strtoupper($mhs['NM_JENJANG'])]['JUMLAH'] = 1;
				}
			}

			return $bimbingan_pa;
		}

		function auto_bimbingan_akademik(){
			$mhs 		= $this->get_jumlah_mhs_dpa();
			$kd_dosen 	= $this->session->userdata('kd_dosen');
			$kd_jbk 	= 'D';
			$kd_ta 		= $this->session->userdata('kd_ta');
			$kd_smt 	= $this->session->userdata('kd_smt');
			$kd_kat 	= '71';

			if($mhs){
				foreach ($mhs as $key) {
					$jenjang 		= $key['JENJANG'];
					$api_url 		= '';
					$parameter 		= array(array(
										$kd_jbk, $kd_dosen, $kd_ta, $kd_smt, $kd_kat, $jenjang 
									));
					$cek_bimbingan 	= $this->api->get_api_json($api_url, 'POST', $parameter);

					if(!$cek_bimbingan){
						$narasi 	= "Dosen Pembimbing Akademik, mahasiswa ".$key['NM_PRODI'].", Jenjang ".$key['JENJANG'].", ".$key['JUMLAH']." Mahasiswa";
						$kd_dosen = $this->session->userdata('kd_dosen');
						$jenis_kegiatan = $narasi;
						/*$jenis_kegiatan ='test';*/
						$bukti_penugasan = '-';
						// $sks_penugasan = $sks_rule;
						$masa_penugasan = '1 Tahun';
						$bkt_dokumen = '-';
						$kd_jbk 		= 'A';
						$bkt_penugasan 	= '-';
						$bkt_dokumen 	= '-';
						$rekomendasi 	= 'LANJUTKAN';
						$capaian 		= 100;
						$jml_jam 		= 1;
						$outcome 		= '-';
						$file_penugasan = '-';
						$file_capaian 	= '-';
						$thn = $this->session->userdata('ta');
						if($smt == 2) $semester = 'GENAP'; else $semester = 'GANJIL';

						$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_beban_kerja_sia_ta';
						$parameter  = array('api_search'=>array($kd_jbk, $kd_dosen, $jenis_kegiatan, $bkt_penugasan, $sks_rule, $masa_penugasan, $bkt_dokumen, $sks_rule, $thn, $semester, $rekomendasi, $jml_jam, $capaian, $outcome, $file_penugasan, $file_capaian, $ta, $smt));
						$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						if($simpan){
							$getid = $simpan;
							$nm_keg = $jenis_kegiatan;

							$api_url 	= URL_API_BKD.'bkd_beban_kerja/simpan_data_penunjang_ta';
							$parameter	= array('api_search' => array($getid, $kd_kat, $nm_keg, 'S1', '-', $kkn, 1, '1', '1', 'A', '1', '-', '-'));
							$simpan = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
						}
					}
				}
			}
		}

		function cek_api_dpa(){
			$v_nip = $this->session->userdata('id_user');
			$data['m_aktif']	= $this->api->get_api_json(URL_API_SIA.'sia_mahasiswa/data_search', 'POST', 
							array('api_kode'=>28000, 'api_subkode' => 6, 
							'api_search' => array('A', $v_nip)));
			/*echo "<pre>";
			print_r($data['m_aktif']);
			echo "</pre>";
			die();*/

			$h=0; $i = 0; $k =0; $l =0;
			$g=0; $j=0; $m=0; $n =0;
			//$jng = "S1";
			foreach ($data['m_aktif'] as $key) {
				$jenjang = strtoupper($key['NM_JENJANG']);
				$prodi = $key['NM_PRODI'];
				if($jenjang == 'S0'){
					$jjg = "S0";
					$prodi = $key['NM_PRODI'];
					$g = $h+1;
					$h++;
				}elseif($jenjang == 'S1'){
					$jjg = "S1";
					$prodi = $key['NM_PRODI'];
					$j = $i+1;
					$i++;
				}elseif($jenjang == 'S2'){
					$jjg = "S2";
					$prodi = $key['NM_PRODI'];
					$m = $k+1;
					$k++;
				}elseif($jenjang == 'S3'){
					$jjg = "S3";
					$prodi = $key['NM_PRODI'];
					$n = $l+1;
					$l++;
				}
				
			}
			$jml_s0 = $g;
			$jml_s1 = $j;
			$jml_s2 = $m;
			$jml_s3 = $n;
			$jenjang_studi = $jjg;
			$program_sudi = $prodi;
			$temp = array(array($jml_s0, $jenjang_studi, $prodi), array($jml_s1, $jenjang_studi, $prodi), array($jml_s2, $jenjang_studi, $prodi), array($jml_s3, $jenjang_studi, $prodi));
			/*echo "<pre>";
			print_r($temp);
			echo "<pre>";*/
			return $temp;
		}
		public function test_dpa(){
			$get_s1 = $this->cek_api_dpa();
			$s0 = $get_s1[0][0];
			$s1 = $get_s1[1][0];
			$s2 = $get_s1[2][0];
			$s3 = $get_s1[3][0];
			//echo $s2;
			
			if(!empty($s0)){
				echo "QUERY 1 : "."<br>";
				echo "=================<br>";
				echo "MASUKKAN KE TABLE JUMLAH MAHASISWA BIMBINGAN S0= ".$s0."<br>";
			}
			if(!empty($s1)){
				echo "QUERY 2 : "."<br>";
				echo "=================<br>";
				echo "MASUKKAN KE TABLE JUMLAH MAHASISWA BIMBINGAN S1= ".$s1."<br>";
			}
			if(!empty($s2)){
				echo "QUERY 3 : "."<br>";
				echo "=================<br>";
				echo "MASUKKAN KE TABLE JUMLAH MAHASISWA BIMBINGAN S2= ".$s2."<br>";
			}
			if(!empty($s3)){
				echo "QUERY 4 : "."<br>";
				echo "=================<br>";
				echo "MASUKKAN KE TABLE INTO TABLE JUMLAH MAHASISWA BIMBINGAN S3= ".$s3."<br>";
			}
		}
		
	/* file location : ../application/controller/dosen/bebankerja.php
	 * last modified : 16/Mar/2013
	 * By : Sabbana 
	 */

}
