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
		$kd_dosen = $this->input->post("kd_dosen");
		$data = array('pdf_title' => 'RBKD_'.$kd_dosen, 'pdf_margin' => array(20,10,15)); //margin = array(kiri, atas, kanan)
		$pdf->sia_set_properties($data);
		$pdf->SetFont('times', '', 8);
		#ukuran kertas milimiter
		$pdf->AddPage('P', array(297,210), false, false);
		$pdf->setPageOrientation('L',true,15);
		
		#tulis konten html ke PDF

		$jenis = $this->input->post('jenis');
		#ta-smt
		$thn = $this->input->post("ta");
		$smt = $this->input->post("smt");
		$kd_prodi = $this->input->post("kd_prodi");
		$kf = $this->input->post("kd_fak");
		
		# kode ta-smt
		$kd_ta = $this->setting->_generate_kd_ta($thn);
		$kd_smt = $this->setting->_generate_kd_smt($smt);
		
		# rta-rsmt
		$r_ta = $thn; #$this->session->userdata("r_ta");
		$r_smt = $smt; #$this->session->userdata("r_smt");
		$thn_prof = $this->session->userdata('thn_prof');
		#$kd_prodi = $this->session->userdata('kd_prodi');
		
		# CEK TANGGAL CETAK
		$api_url 	= URL_API_SIA.'sia_master/data_search';
		$parameter  = array('api_kode' =>16001, 'api_subkode' => 1, 'api_search' => array($kd_ta, $kd_smt, $kd_prodi));
		$data 		= $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		if(!empty($data)){
			$tanggal =  date('d/m/Y', strtotime($data[0]->T_SMT_AKT_1_F));
		}else{
			$tanggal = date('d/m/Y');
		}
		
		//$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		//$parameter 	= array('api_kode' => 1221, 'api_subkode' => 8, 'api_search' => array($kd_ta, $kd_smt, 'KAPRODI', $kf));
		//print_r ($parameter);
		//echo $parameter.' cek';
		//$data['kaprodi_historis'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//print_r($data['kaprodi_historis']); die();
		
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter 	= array('api_kode' => 1221, 'api_subkode' => 9, 'api_search' => array($tanggal, 'KAPRODI', $kd_prodi));
		//print_r ($parameter);
		//echo $parameter.' cek';
		$data['kaprodi_historis'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//print_r($data['kaprodi_historis']); die();
		
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter  = array('api_kode' 	=> 101006, 'api_subkode' => 1, 'api_search' 	=> array($tanggal,$kd_prodi));
		$data['kaprodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//print_r($data['kaprodi']); die();
		
		if(!empty($data['kaprodi_historis'])){
			foreach($data['kaprodi_historis'] as $kp);
			$kaprodi[0] = $kp->KD_PGW;
			$kaprodi[1] = $kp->NM_PGW_F;
			$kaprodi[2] = $kp->STR_NAMA_S1;
			$kaprodi[3] = $kp->SUB_UNIT_NAMA;
			
			foreach($data['kaprodi'] as $kpr);
		    $kaprodi2[0] = $kpr->PRO_NM_SEBUTAN;
		}else{
			$api_url 	= URL_API_SIA.'sia_sistem/data_search';
			$parameter  = array('api_kode' 	=> 89020, 'api_subkode' => 9, 'api_search' 	=> array($kd_prodi));
			$data['kaprodi'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			foreach ($data['kaprodi'] as $kp){
				if(substr($kp->KD_JABATAN,0,3) == 'KPD'){
					$kaprodi[0] = $kp->KD_PGW;
					$kaprodi[1] = $kp->NM_PGW;
				}
			}
		}
		
		
		if($jenis == 'lbkd'){
			$data = array ('ta'=>$thn, 'r_ta'=>$r_ta, 'smt'=>$smt, 'r_smt'=>$r_smt);
		}else{
			$data = array ('ta'=>$thn, 'r_ta'=>$r_ta, 'smt'=>$smt, 'r_smt'=>$r_smt, 'nip_kaprodi'=>$kaprodi[0], 'nama_kaprodi'=>$kaprodi[1],'lb_kpro'=>$kaprodi[2],'nm_kpro'=>$kaprodi[3],'sb_kpro'=>$kaprodi2[0]);
		}
		
		# get data dosen
		$data['dosen'] = $this->getDataDosen($kd_dosen);
		$data['dosenBkd'] = $this->getDataDosenBKD($kd_dosen);
		$data['dosenSia'] = $this->biodataDosen($kd_dosen);
		$data['universitas'] = $this->historiPT();
		#print_r($data['universitas']); die();
		$data['namaFakultas'] = $this->namaFak($data['dosenBkd'][0]->KD_FAK);
		$data['namaProdi'] = $this->namaProdi($data['dosenBkd'][0]->KD_PRODI);
		
		# DEKAN
		#$kf = $this->session->userdata('kd_fak');
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter 	= array('api_kode' => 1221, 'api_subkode' => 8, 'api_search' => array($kd_ta, $kd_smt, 'DEKAN', $kd_prodi));
		$data['dekan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		
		#print_r($parameter);
		
		if (!empty($data['dekan'])){
			foreach ($data['dekan'] as $dekan);
				$data['nama_fakultas'] = $dekan->STR_NAMA;
				$data['nama_dekan'] = $dekan->NM_PGW_F;
				$data['nip_dekan'] = $dekan->KD_PGW;
		}else{
				$data['nama_fakultas'] = '';
				$data['nama_dekan'] = '';
				$data['nip_dekan'] = '....................................';
		}
		
		# get asesor
		$x_thn = substr($thn,0,4);
		if($smt=='GANJIL') $x_smt = '1'; else $x_smt = '2';
		$api_url 	= URL_API_BKD.'bkd_dosen/asesor_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $x_thn, $x_smt));
		//print_r ($parameter);
		$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//print_r $data['asesor'];
		if(!empty($data['asesor'])){
			foreach ($data['asesor'] as $asdos);
			if($asdos->NIRA1 == null){
				$data['nira1'] = ''; 
				$data['asesor1'] = '';
			}else{
				$api_url 	= URL_API_BKD.'bkd_dosen/get_nama_asesor';
				$parameter  = array('api_search' => array($asdos->NIRA1));
				$data['asesor1'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				foreach ($data['asesor1'] as $a);
					$data['nira1'] = $a->NIRA;
					$data['asesor1'] = $a->NM_ASESOR;
			}
			
			if ($asdos->NIRA2 ==  null){
				$data['nira2'] = '';
				$data['asesor2'] = '';
			}else{
				$api_url 	= URL_API_BKD.'bkd_dosen/get_nama_asesor';
				$parameter  = array('api_search' => array($asdos->NIRA2));
				$data['asesor2'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
				foreach ($data['asesor2'] as $b);
					$data['nira2'] = $b->NIRA;
					$data['asesor2'] = $b->NM_ASESOR;
			}
		}else{
			$data['nira1'] = ''; $data['asesor1'] = '';
			$data['nira2'] = ''; $data['asesor2'] = '';
		}
		#print_r($data); die();

		# GET JABATAN FUNGSIONAL DAN GOLONGAN PER SEMESTER
		$api_url_jabatan 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 1122, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		$data['fungsional'] = $this->s00_lib_api->get_api_jsob($api_url_jabatan,'POST',$parameter);
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
			
		# GET JENJANG PENDIIDKAN DOSEN (TIMELINE)
		# =========================================================================================
		$api_url	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2102, 'api_subkode' => 2, 'api_search' => array($kd_ta, $kd_smt, $kd_dosen));
		$data['pendidikan'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//print_r ($data['pendidikan']);
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
		

		# STATUS JENIS DOSEN HISTORIS
		$data['jenis_dosen'] = $this->history->_status_DS($kd_dosen, $kd_ta, $kd_smt);
		$data['nama_jenis_dosen'] = $this->setting->_jenisDosen($data['jenis_dosen']);
			
		# TANGGAL CETAK
		$data['tgl_cetak'] = $this->setting->_tanggal_akhir_semester($kd_ta, $kd_smt, $jenis);
		#$data['tgl_cetak'] = $this->setting->_tanggal_akhir_semester($kd_ta, $kd_smt);
		
 		if ($jenis == 'rbkd'){
			$pdf->SetTitle('Rencana Beban Kerja Dosen');
			# beban kerja Pendidikan
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_rbkd';
			$data['kode'] = 'A';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $r_ta, $r_smt));
			$data['pd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# beban kerja penelitian
			$data['kode'] = 'B';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $r_ta, $r_smt));
			$data['pl'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# beban kerja pengabdian masyarakat
			$data['kode'] = 'C';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $r_ta, $r_smt));
			$data['pk'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# beban kerja tambahan
			$data['kode'] = 'D';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $r_ta, $r_smt));
			$data['pg'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# data kewajiban profesor
			if ($data['jenis_dosen'] == 'PR' || $data['jenis_dosen'] == 'PT'){
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/history_data_bebankerja_prof';
				$parameter  = array('api_search' => array($kd_dosen, $r_ta, $r_smt));
				$data['dbp'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}

			$html = $this->load->view('print/rencana_bkd_dosen',$data,true);
			$pdf->writeHTML($html, true, false, true, false, '');
			
			$pdf->AddPage('P', array(297,210), false, false);
			$pdf->setPageOrientation('L',true,15);
			$html = $this->load->view('print/ttd_dosen',$data,true);
			$pdf->writeHTML($html, true, false, true, false, '');

			$pdf->lastPage();
			$pdf->Output('RBKD_DOSEN.pdf', 'I');
		}
		else{
			$pdf->SetTitle('Laporan Kinerja Dosen');
			# beban kerja Pendidikan
			$api_url 	= URL_API_BKD.'bkd_beban_kerja/data_cetak_bebankerja';
			$data['kode'] = 'A';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, $smt));
			$data['pd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# beban kerja penelitian
			$data['kode'] = 'B';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, $smt));
			$data['pl'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# beban kerja pengabdian masyarakat
			$data['kode'] = 'C';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, $smt));
			$data['pk'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

			# beban kerja tambahan
			$data['kode'] = 'D';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, $smt));
			$data['pg'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			
			# data kewajiban profesor
			if ($data['jenis_dosen'] == 'PR' || $data['jenis_dosen'] == 'PT'){
				$api_url 	= URL_API_BKD.'bkd_beban_kerja/history_data_bebankerja_prof';
				$parameter  = array('api_search' => array($kd_dosen, $thn, $smt));
				$data['dbp'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}

			$html = $this->load->view('print/laporan_bkd_dosen',$data,true);
			$pdf->writeHTML($html, true, false, true, false, '');
			
			$pdf->AddPage('P', array(297,210), false, false);
			$pdf->setPageOrientation('L',true,15);
			$html = $this->load->view('print/ttd_asesor',$data,true);
			$pdf->writeHTML($html, true, false, true, false, '');
			
			$pdf->lastPage();
			$pdf->Output('LBKD_DOSEN.pdf', 'I');
		}		
	}
	
	function getDataDosen($kd){
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode' => 2001, 'api_subkode' => 2, 'api_search' => array($kd));
		$nama = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		//print_r ($nama);
		return $nama;
	}

	function getDataDosenBKD($kd_dosen){
		$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
		$parameter  = array('data_search' => array($kd_dosen));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data;
	}
	

	function biodataDosen($kd_dosen){
		$api_url 	= URL_API_SIA.'sia_dosen/data_search';
		$parameter  = array('api_kode'=>20000, 'api_subkode'=>3, 'api_search' => array($kd_dosen));
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
	
	function namaProdi($kd_prodi){
		$api_url 	= URL_API_SIA.'sia_sistem/data_search';
		$parameter 	= array('api_kode' => 101006, 'api_subkode' => 1, 'api_search' => array(date('d/m/Y'), $kd_prodi));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		return $data[0]->PRO_NM_PRODI;
		#print_r($data);
	}
	
	function cek_asesor(){
		$kd_dosen = $this->session->userdata('kd_dosen');
		$thn = $this->session->userdata("ta");
		$smt = $this->session->userdata("smt");
		# AMBIL NILAI KAPRODI NIP DAN NAMA
		$api_url 	= URL_API_BKD.'bkd_dosen/asesor_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $thn, $smt));
		$data['asesor'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		print_r($data['asesor']);
	}
	
}










