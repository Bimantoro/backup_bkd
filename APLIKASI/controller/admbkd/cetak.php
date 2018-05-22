<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Cetak extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('bkd_lib_setting','','setting');
		$this->load->library('bkd_lib_history','','history');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}
	

    public function index() {
		require_once('includes/pdf_report/config/lang/eng.php');
		require_once('includes/pdf_report/SIApdf.php');
		$pdf = new SIApdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		$data = array('pdf_title' => 'Kompilasi', 'pdf_margin' => array(15,10,10)); //margin = array(kiri, atas, kanan)
		$pdf->sia_set_properties($data);
		$pdf->SetTitle('Laporan Kompilasi Kinerja Dosen');
		$pdf->SetFont('times', '', 7);
		$pdf->AddPage('L', array(297,220), false, false);
		$pdf->setPageOrientation('L',true,10);
		
		$ta = $this->session->userdata('ta');
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['alamat'] = 'Jl. Marsda Adisucipto Yogyakarta';
		$data['ta'] = $this->session->userdata('ta');

		$data['dosen_tetap'] = $this->dosen_tetap();

		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($ta));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$data['namaFakultas'] = array();
		$data['namaDosen'] = array();
		if(!empty($data['kompilasi'])){
			foreach ($data['kompilasi'] as $a){
				$data['namaFakultas']['_'.$a->KD_DOSEN] = $this->namaFak($a->KD_FAK);
				$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			}
		}
		
		$data['judul'] = "LAPORAN EVALUASI TINGKAT PERGURUAN TINGGI TAHUN ".$ta;
		$html = $this->load->view('print/kompilasi',$data,true);
		$pdf->writeHTML($html, true, false, true, false, '');
		$pdf->SetPrintHeader();
		$pdf->SetPrintFooter();

		$data['jabatan'] = "Rektor";
		$data['penandatangan'] = "Prof. Dr. H. Musa Asy'arie";
		$data['nip_penandatangan'] = "NIP. 19511231 198003 1 018";
				
		$html = $this->load->view('print/ttd_rektor',$data,true);
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('Kompilasi_BKD_Perguruan_Tinggi.pdf', 'I');
	}
	
	# LAPORAN EXCEL UNIVERSITAS
	function export_excel(){
		$ta = $this->session->userdata('ta');
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['alamat'] = 'Jl. Marsda Adisucipto Yogyakarta';
		$data['ta'] = $this->session->userdata('ta');
		$data['judul'] = "LAPORAN EVALUASI TINGKAT PERGURUAN TINGGI TAHUN 2013/2014";
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($ta));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$this->load->view('print/kompilasi_excel', $data);
		
		$data['jabatan'] = "Rektor";
		$data['penandatangan'] = "Prof. Dr. H. Musa Asy'arie";
		$data['nip_penandatangan'] = "NIP. 195112311980031018";		
		$this->load->view('print/ttd_rektor', $data);
	}

	# LAPORAN EXCEL FAKULTAS
	function export_excel_fakultas(){
		$ta = $this->session->userdata('ta');
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['alamat'] = 'Jl. Marsda Adisucipto Yogyakarta';
		$data['ta'] = $this->session->userdata('ta');
		$kd = $this->session->userdata('adm_fak');
		$data['judul'] = "LAPORAN EVALUASI TINGKAT FAKULTAS TAHUN 2013/2014";
		$data['label'] = "Fakultas";
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
		$parameter  = array('api_kode' => 21041991, 'api_subkode' => 1, 'api_search' => array($kd));
		$data['label_value'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 21, 'api_search' => array($ta, $kd));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$this->load->view('print/kompilasi_excel', $data);

		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array('api_kode' => 89020, 'api_subkode' => 10, 'api_search' => array($kd));
		$data['dekan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['dekan']);
		foreach ($data['dekan'] as $kaprodi){
			$data['penandatangan'] = $kaprodi->NM_PGW;
			$data['nip_penandatangan'] = "NIP. ".$kaprodi->KD_PGW;
			break;
		}
		
		$data['jabatan'] = 'Dekan';
		$this->load->view('print/ttd_rektor', $data);
	}
	
	# cetak kompilasi persemester
	 public function semester($tingkat = '') {
		require_once('includes/pdf_report/config/lang/eng.php');
		require_once('includes/pdf_report/SIApdf.php');
		
		#init class
		$pdf = new SIApdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		#lebih lanjut lihat di {includes/pdf_report/SIApdf.php}
		$data = array('pdf_title' => 'Kompilasi', 'pdf_margin' => array(15,10,10)); //margin = array(kiri, atas, kanan)
		$pdf->sia_set_properties($data);
		
		#set base font
		$pdf->SetFont('times', '', 7);
		
		$ta = $this->session->userdata('ta');
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['alamat'] = 'Jl. Marsda Adisucipto Yogyakarta';
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		
		$kd_ta = $this->setting->_generate_kd_ta($data['ta']);
		$kd_smt = $this->setting->_generate_kd_smt($data['smt']);
		
		
		if ($tingkat == 'fakultas'){
			$kd = $this->session->userdata('adm_fak');
			$subkode = 21;
			$param = array($ta, $kd);
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
			$parameter  = array('api_kode' => 21041991, 'api_subkode' => 1, 'api_search' => array($kd));
			$data['label_value'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			$data['tingkat'] = 'Fakultas';
			$jabatan = 'Dekan';
			# DATA DEKAN
			$api_url 	= URL_API_SIA.'sia_sistem/data_search';
			$parameter  = array('api_kode' => 89020, 'api_subkode' => 10, 'api_search' => array($kd));
			$data['dekan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			#print_r($data['kaprodi']);
			foreach ($data['dekan'] as $kaprodi){
				$nama = $kaprodi->NM_PGW;
				$nip = "NIP. ".$kaprodi->KD_PGW;
				break;
			}
		}else if ($tingkat == 'prodi'){
			$kd = $this->session->userdata('adm_pro');
			$subkode = 31;
			$param = array($ta, $kd);
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
			$parameter  = array('api_kode' => 21041991, 'api_subkode' => 2, 'api_search' => array($kd));
			$data['label_value'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			$data['tingkat'] = 'Prodi';
			$jabatan = 'Kaprodi/Kajur';
			# DATA KAPRODI
			$api_url 	= URL_API_SIA.'sia_sistem/data_search';
			$parameter  = array('api_kode' => 89020, 'api_subkode' => 9, 'api_search' => array($kd));
			$data['kaprodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			#print_r($data['kaprodi']);
			foreach ($data['kaprodi'] as $kaprodi){
				$nama = $kaprodi->NM_PGW;
				$nip = "NIP. ".$kaprodi->KD_PGW;
				break;
			}
		}else{
			$jabatan = 'Rektor';
			$nama = "Prof. Dr. H. Musa Asy'arie";
			$nip = 'NIP. 195112311980031018';
			$param = array($ta);
			$subkode = 1;
			$data['tingkat'] = 'Perguruan Tinggi';
		}
		
		# ATRIBUT
		$data['judul'] = "LAPORAN EVALUASI TINGKAT ".strtoupper($data['tingkat'])." SEMESTER ".$data['smt']." TAHUN ".$ta;
		$data['label'] = $data['tingkat'];
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => $subkode, 'api_search' => $param);
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data['kompilasi'])){
			    foreach ($data['kompilasi'] as $a){
			        $data['ds']['_'.$a->KD_DOSEN] = $this->history->_status_DS($a->KD_DOSEN, $kd_ta, $kd_smt);
					$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			    }
			}
		#print_r($data);
		$data['dosen_tetap'] = $this->dosen_tetap();

		$pdf->AddPage('P', array(297,210), false, false);
		$pdf->setPageOrientation('P',true,10);
		$html = $this->load->view('print/kompilasi_semester',$data,true);
		#$pdf->writeHTML($html, true, false, true, false, '');

		$data['jabatan'] = $jabatan;
		$data['penandatangan'] = $nama;
		$data['nip_penandatangan'] = $nip;
		#$pdf->AddPage('P', array(297,210), false, false);
		#$pdf->setPageOrientation('P',true,10);
		$html .= $this->load->view('print/ttd_rektor',$data,true);
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->SetPrintHeader();
		$pdf->SetPrintFooter();
		$pdf->lastPage();
		$pdf->Output('Kompilasi_BKD_Perguruan_Tinggi_Semester.pdf', 'I');
	}

	function semester_export_excel(){
		$ta = $this->session->userdata('ta');
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['alamat'] = 'Jl. Marsda Adisucipto Yogyakarta';
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		$data['judul'] = "LAPORAN EVALUASI TINGKAT PERGURUAN TINGGI SEMESTER $data[smt] TAHUN 2013/2014";
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 1, 'api_search' => array($ta));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data['kompilasi'])){
			    foreach ($data['kompilasi'] as $a){
			        #$data['ds']['_'.$a->KD_DOSEN] = $this->history->_status_DS($a->KD_DOSEN, $kd_ta, $kd_smt);
					$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			    }
			}
		$this->load->view('print/kompilasi_excel_semester', $data);

		$data['jabatan'] = "Rektor";
		$data['penandatangan'] = "Prof. Dr. H. Musa Asy'arie";
		$data['nip_penandatangan'] = "NIP. 195112311980031018";		
		
		$this->load->view('print/ttd_rektor', $data);
	}
	
	function semester_export_excel_fakultas(){
		$ta = $this->session->userdata('ta');
		$kd = $this->session->userdata('adm_fak');
		
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['label'] = 'Fakultas';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
		$parameter  = array('api_kode' => 21041991, 'api_subkode' => 1, 'api_search' => array($kd));
		$data['label_value'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		$data['judul'] = "LAPORAN EVALUASI TINGKAT FAKULTAS SEMESTER $data[smt] TAHUN 2013/2014";
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 21, 'api_search' => array($ta, $kd));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data['kompilasi'])){
			    foreach ($data['kompilasi'] as $a){
			        $data['ds']['_'.$a->KD_DOSEN] = $this->history->_status_DS($a->KD_DOSEN, $kd_ta, $kd_smt);
					$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			    }
			}
		$this->load->view('print/kompilasi_excel_semester', $data);

		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array('api_kode' => 89020, 'api_subkode' => 10, 'api_search' => array($kd));
		$data['dekan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['dekan']);
		foreach ($data['dekan'] as $kaprodi){
			$data['penandatangan'] = $kaprodi->NM_PGW;
			$data['nip_penandatangan'] = "NIP. ".$kaprodi->KD_PGW;
			break;
		}
		
		$data['jabatan'] = 'Dekan';
		
		$this->load->view('print/ttd_rektor', $data);
	}
	

# LAPORAN FAKULTAS 
    public function fakultas() {
		require_once('includes/pdf_report/config/lang/eng.php');
		require_once('includes/pdf_report/SIApdf.php');
		
		#init class
		$pdf = new SIApdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		#lebih lanjut lihat di {includes/pdf_report/SIApdf.php}
		$data = array('pdf_title' => 'Kompilasi', 'pdf_margin' => array(15,10,10)); # margin = array(kiri, atas, kanan)
		$pdf->sia_set_properties($data);
		
		#set base font
		$pdf->SetFont('times', '', 9);
			
		#ukuran kertas milimiter
		$pdf->AddPage('P', array(297,220), false, false);
		$pdf->setPageOrientation('L',true,10);
		
		$ta = $this->session->userdata('ta');
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['alamat'] = 'Jl. Marsda Adisucipto Yogyakarta';
		$data['ta'] = $this->session->userdata('ta');
		$adm_fak = $this->session->userdata('adm_fak'); #echo $adm_fak;
		# ATRIBUT
		$data['judul'] = "LAPORAN EVALUASI TINGKAT FAKULTAS TAHUN ".$ta;
		$data['label'] = 'Fakultas';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
		$parameter  = array('api_kode' => 21041991, 'api_subkode' => 1, 'api_search' => array($adm_fak));
		$data['label_value'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 21, 'api_search' => array($ta, $adm_fak));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data);

		$html = $this->load->view('print/kompilasi',$data,true);
		$pdf->writeHTML($html, true, false, true, false, '');

		# sia_sistem/89020/10
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array('api_kode' => 89020, 'api_subkode' => 10, 'api_search' => array($adm_fak));
		$data['dekan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['dekan']);
		foreach ($data['dekan'] as $kaprodi){
			$data['penandatangan'] = $kaprodi->NM_PGW;
			$data['nip_penandatangan'] = "NIP. ".$kaprodi->KD_PGW;
			break;
		}
		
		$data['jabatan'] = 'Dekan';
		$pdf->AddPage('P', array(297,210), false, false);
		$pdf->setPageOrientation('L',true,15);
		$html = $this->load->view('print/ttd_rektor',$data,true);
		$pdf->writeHTML($html, true, false, true, false, '');

		$pdf->lastPage();
		$pdf->Output('Kompilasi_BKD_Perguruan_Tinggi.pdf', 'I');
	}	
	
# LAPORAN PRODI 
    public function prodi() {
		require_once('includes/pdf_report/config/lang/eng.php');
		require_once('includes/pdf_report/SIApdf.php');
		
		$pdf = new SIApdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);		
		$data = array('pdf_title' => 'Kompilasi', 'pdf_margin' => array(15,10,10)); //margin = array(kiri, atas, kanan)
		$pdf->sia_set_properties($data);		
		$pdf->SetFont('times', '', 9);
		
		$ta = $this->session->userdata('ta');
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['alamat'] = 'Jl. Marsda Adisucipto Yogyakarta';
		$data['ta'] = $this->session->userdata('ta');
		$adm_pro = $this->session->userdata('adm_pro');
	
		# ATRIBUT
		$data['judul'] = "LAPORAN EVALUASI TINGKAT PRODI TAHUN ".$ta;
		$data['label'] = 'Program Studi';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
		$parameter  = array('api_kode' => 21041991, 'api_subkode' => 2, 'api_search' => array($adm_pro));
		$data['label_value'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
	
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 31, 'api_search' => array($ta, $adm_pro));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data);
		
		$pdf->AddPage('P', array(297,220), false, false);
		$pdf->setPageOrientation('L',true,10);
		$html = $this->load->view('print/kompilasi',$data,true);
		$pdf->writeHTML($html, true, false, true, false, '');

		# KAPRODI
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array('api_kode' => 89020, 'api_subkode' => 9, 'api_search' => array($adm_pro));
		$data['kaprodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['kaprodi']);
		foreach ($data['kaprodi'] as $kaprodi){
			$data['penandatangan'] = $kaprodi->NM_PGW;
			$data['nip_penandatangan'] = $kaprodi->KD_PGW;
			break;
		}

		$data['jabatan'] = 'Kaprodi/Kajur';		
		$pdf->AddPage('P', array(297,210), false, false);
		$pdf->setPageOrientation('L',true,15);
		$html = $this->load->view('print/ttd_rektor',$data,true);
		$pdf->writeHTML($html, true, false, true, false, '');
		
		$pdf->lastPage();
		$pdf->Output('Kompilasi_BKD_Perguruan_Tinggi.pdf', 'I');
	}	
	
	function semester_export_excel_prodi(){
		$ta = $this->session->userdata('ta');
		$kd = $this->session->userdata('adm_pro');
		
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';
		$data['label'] = 'Program Studi';
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
		$parameter  = array('api_kode' => 21041991, 'api_subkode' => 2, 'api_search' => array($kd));
		$data['label_value'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$data['ta'] = $this->session->userdata('ta');
		$data['smt'] = $this->session->userdata('smt');
		$data['judul'] = "LAPORAN EVALUASI TINGKAT PROGRAM STUDI SEMESTER $data[smt] TAHUN 2013/2014";
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 31, 'api_search' => array($ta, $kd));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			if(!empty($data['kompilasi'])){
			    foreach ($data['kompilasi'] as $a){
			        $data['ds']['_'.$a->KD_DOSEN] = $this->history->_status_DS($a->KD_DOSEN, $kd_ta, $kd_smt);
					$data['namaDosen']['_'.$a->KD_DOSEN] = $this->konvertNama($a->KD_DOSEN);
			    }
			}
		$this->load->view('print/kompilasi_excel_semester', $data);

		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array('api_kode' => 89020, 'api_subkode' => 9, 'api_search' => array($kd));
		$data['dekan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['dekan']);
		foreach ($data['dekan'] as $kaprodi){
			$data['penandatangan'] = $kaprodi->NM_PGW;
			$data['nip_penandatangan'] = "NIP. ".$kaprodi->KD_PGW;
			break;
		}
		
		$data['jabatan'] = 'Kaprodi/Kajur';
		
		$this->load->view('print/ttd_rektor', $data);
	}

	function export_excel_prodi(){
		$ta = $this->session->userdata('ta');
		$data['pt'] = 'Universitas Islam Negeri Sunan Kalijaga Yogyakarta';

		$data['ta'] = $this->session->userdata('ta');
		$kd = $this->session->userdata('adm_pro');
		
		$data['judul'] = "LAPORAN EVALUASI TINGKAT PROGRAM STUDI TAHUN 2013/2014";
		$data['label'] = "Program Studi";
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/atribut';
		$parameter  = array('api_kode' => 21041991, 'api_subkode' => 2, 'api_search' => array($kd));
		$data['label_value'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/kompilasi';
		$parameter  = array('api_kode' => 1000, 'api_subkode' => 31, 'api_search' => array($ta, $kd));
		$data['kompilasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$this->load->view('print/kompilasi_excel', $data);

		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array('api_kode' => 89020, 'api_subkode' => 9, 'api_search' => array($kd));
		$data['dekan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		#print_r($data['dekan']);
		foreach ($data['dekan'] as $kaprodi){
			$data['penandatangan'] = $kaprodi->NM_PGW;
			$data['nip_penandatangan'] = "NIP. ".$kaprodi->KD_PGW;
			break;
		}
		
		$data['jabatan'] = 'Kaprodi/Kajur';
		$this->load->view('print/ttd_rektor', $data);
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

	# DATA PUBLIKASI DOSEN
	function publikasi(){
		require_once('includes/pdf_report/config/lang/eng.php');
		require_once('includes/pdf_report/SIApdf.php');
		
		#init class
		$pdf = new SIApdf(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
		
		#lebih lanjut lihat di {includes/pdf_report/SIApdf.php}
		$data = array('pdf_title' => 'Kompilasi Publikasi', 'pdf_margin' => array(15,10,10)); //margin = array(kiri, atas, kanan)
		$pdf->sia_set_properties($data);
		
		#set base font
		$pdf->SetFont('times', '', 9);
			
		#ukuran kertas milimiter
		$pdf->AddPage('L', array(297,220), false, false);
		$pdf->setPageOrientation('L',true,10);

		$tahun = $this->session->userdata('ta');
		$smt = $this->session->userdata('smt');
		
		$data['judul'] = "Kompilasi Publikasi Dosen Tahun ".$tahun;
		$data['pt'] = "Universitas Islam Negeri Sunan Kalijaga Yogyakarta";
		$data['alamat'] = "Jl. Marsda Adisucipto";
		$data['label'] = '';
		$data['label_value'] = '';
		if($tahun == '' && $smt == '')
		{
			# CETAK SEMUA PUBLIKASI DOSEN
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/publikasi';
			$parameter  = array('api_kode' => 9900, 'api_subkode' => 1, 'api_search' => array());
			$data['publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		
		if($tahun !== '' && $smt == '')
		{
			# CETAK PUBLIKASI DOSEN TAHUNAN
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/publikasi';
			$parameter  = array('api_kode' => 9900, 'api_subkode' => 99, 'api_search' => array($tahun));
			$data['publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		
		if($tahun !== '' && $smt !== '')
		{
			# CETAK PUBLIKASI DOSEN PER SEMESTER
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/publikasi';
			$parameter  = array('api_kode' => 9900, 'api_subkode' => 999, 'api_search' => array($tahun, $smt));
			$data['publikasi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		}
		
		if(!empty($data['publikasi'])){
			$data['nm_dosen'] = array();
			foreach ($data['publikasi'] as $pub){
				$api_url 	= URL_API_SIA.'sia_dosen/data_search';
				$parameter  = array('api_kode' => 20000, 'api_subkode' => 1, 'api_search' => array($pub->KD_DOSEN));
				$data['nm_dosen']['_'.$pub->KD_DOSEN] = $this->konvertNama($pub->KD_DOSEN);
			}
		}
		
		$html = $this->load->view('print/kompilasi_publikasi',$data,true);
		$pdf->writeHTML($html, true, false, true, false, '');
		
		$pdf->lastPage();
		$pdf->Output('Kompilasi_Publikasi.pdf', 'I');
	}

	function namaFak($kd_fak){
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter 	= array('api_kode' => 101006, 'api_subkode' => 5, 'api_search' => array(date('d/m/Y'), $kd_fak));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
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


	
}










