<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Cetak_remun extends CI_Controller{

	function __construct(){
		parent::__construct();
		$this->load->library('bkd_lib_setting','','setting');
		$this->load->library('bkd_lib_history','','history');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}

	function _cek_jenis_mk($kd_kelas){
		#sia_penawaran/data_search, 58000/6, api_search = array(kd_kelas)
		$api_url 	= URL_API_SIA.'sia_penawaran/data_search';
		$parameter  = array('api_kode'=>58000, 'api_subkode' => 6, 'api_search' => array($kd_kelas));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
		$nilai = 0;
		if($data){
			$nilai = $data[0]->NM_JENIS_MK;
		}
		return $nilai;
	}

	function _cek_batas_minimal_mhs($kd_kelas){
		$jenis = $this->_cek_jenis_mk($kd_kelas);
		//echo "jenis mata kuliah : ".$jenis."<br>";
		$syarat = $this->s00_lib_api->get_api_json(
			URL_API_BKD.'/bkd_remun/get_syarat_mengajar',
			'POST',
			array('api_search' => array($jenis))
		);

		return $syarat;
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
		/*echo "<pre>";
		print_r($data['dosen']);
		echo "<pre>";
		die();*/
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

		#get_data_asesor_remunerasi
		$data['asesor_1_2'] = $this->get_asesor_remun_by_kd_fak();
		/*echo "<pre>";
		print_r($data['asesor_1_2']);
		echo "<pre>";
		die();*/


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
			$pdf->SetTitle('Laporan Remunerasi Dosen');

			
			# beban kerja Pendidikan
			$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
			$data['kode'] = 'A';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, $smt));
			$data['pd'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			//jika $data['pd'] kosong atau tidak ada datanya maka form cetak tidak akan ditampilkan, kemudian di kembalikan ke halaman
			//sebelumya dengan memberikan notif "tidak ada data yang dapat dicetak"
			// jika $data['pd'] ada datanya baru melanjutkan eksekusi dan form cetak dapat ditampilkan
			//if(empty($data['pd'])){redirect('kehalaman sebeumnya')}else{lanjutkan eksekusi proses berikutnya}
			if(empty($data['pd'])){
				$this->session->set_flashdata('msg', array('danger', 'Tidak terdapat data laporan remunerasi dosen yang dapat dicetak pada Semester '."<b>".''.$smt.''."</b>".' Tahun Akademik '."<b>".''.$thn.''."</b>".'.'));
				redirect(base_url('bkd/dosen/remun/cetak'));
			}else{

			
 			$i=0;
			foreach ($data['pd'] as $key) {
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
				$data['pd'][$i]->KD_KAT_REMUN = $kd_kat_remun;
				$i++;

				$api_url 	= URL_API_BKD.'bkd_remun/get_jenis_nilai_kat_remun';
				$parameter  = array('api_search' => array($kd_kat_remun));
				$data['nilai_kat'][$kd_kat_remun] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
			}

			$api_url  = URL_API_BKD.'bkd_remun/get_jenis_remun';
			$kode_api = $this->s00_lib_api->get_api_json(
							$api_url,
							'POST',
							array('api_search' => array())
						);
			$data['kd_dosen'] = $this->session->userdata('kd_dosen');
			$jabatan = $this->get_jabatan_fungsional($data['kd_dosen']);
			$thn = $this->input->post("ta");
			$smt = $this->input->post("smt");
			$temporary = array();
			$temp_sks 	= 0;
			$temp_poin 	= 0;
			$temp_satuan = 0;

			$testing_poin = array();
			$a = array();

			foreach ($kode_api as $jbr) {
				$api_url 	= URL_API_BKD.'bkd_remun/data_remun';
				$parameter  = array('api_search' => array($jbr['KD_JBR'], $data['kd_dosen'], $thn, $smt));
				$data['data_remun'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);


				$i=0;
				foreach ($data['data_remun'] as $key) {


					$temp_status_jml_mhs = 1;
					//$temp_batas_jml_mhs  = 0;
					$status_verifikasi = $key['STATUS'];
					if(isset($key['KETERANGAN'])){
						$cek_batas_mhs = $this->_cek_batas_minimal_mhs($key['KETERANGAN']);
						if($key['JML_MHS'] < $cek_batas_mhs){
							$temp_status_jml_mhs = 0;
						}
					}

					if($temp_status_jml_mhs == 1 AND $status_verifikasi == 1){

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
						$api_url 	= URL_API_BKD.'bkd_remun/get_jenis_nilai_kat_remun';
						$parameter  = array('api_search' => array($kd_kat_remun));
						$nilai_kat = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);

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
						echo "<pre>";*/

						if(!isset($temporary[$jbr['KD_JBR']][$kategori])){
							$temporary[$jbr['KD_JBR']][$kategori]['GROUP'] = $jbr['NM_JBR'];
							$temporary[$jbr['KD_JBR']][$kategori]['JUDUL'] = $tmp_kategori['JUDUL'];
							if(!isset($temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN'])){
								$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN']= '';
								/*$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN']['NILAI']= 0;*/
								/*$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN']['NILAI'] = 0;
								$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN']['SATUAN'] = 0;
								$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN']['POIN'] = 0;*/
							}
							$temporary[$jbr['KD_JBR']][$kategori]['NILAI_TOTAL'] = 0;
							$temporary[$jbr['KD_JBR']][$kategori]['SATUAN_TOTAL'] = 0;
							$temporary[$jbr['KD_JBR']][$kategori]['POIN_TOTAL'] = 0;
							
						}

						$api_url 	= URL_API_BKD.'bkd_remun/get_poin_remun';
						$parameter  = array('api_search' => array($jbr['KD_JBR'], $jenjang, $kelas, $jabatan, $semester, $kategori));
						$poin 		= $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
						
					
						$poin_baru = (float) str_replace(',', '.', $poin['POIN']);

						$temp_sks += $sks;
						$poin_satu = $poin_baru * ($sks / $jml_dosen) * ($tatapmuka / $tm_per_minggu);
						$temp_poin += $poin_satu;
						$temp_satuan = $poin['SATUAN'];

						
						$temporary[$jbr['KD_JBR']][$kategori]['NILAI_TOTAL'] += $sks;
						$temporary[$jbr['KD_JBR']][$kategori]['SATUAN_TOTAL'] = $poin['SATUAN'];
						$temporary[$jbr['KD_JBR']][$kategori]['POIN_TOTAL'] += $poin_baru * ($sks / $jml_dosen) * ($tatapmuka / $tm_per_minggu);
						$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN'][]=array('JUDUL_KEGIATAN'=>$key['JENIS_KEGIATAN'], "NILAI"=>$sks, 'SATUAN'=>$poin['SATUAN'], 'POIN'=>$poin_satu);
						/*$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN'][]['NILAI'] = $sks;
						$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN'][]['SATUAN'] = $poin['SATUAN'];*/
						/*$temporary[$jbr['KD_JBR']][$kategori]['JENIS_KEGIATAN'][]['NILAI'] = $sks;*/
					}
					
				}
				
				
			}
			/*echo json_encode($temporary);
			echo "<pre>";
					print_r($temporary);

					echo "<pre>";
			die();*/
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
			/*echo "<pre>";
			print_r($temporary);
			echo "<pre>";

			die();*/
			$data['summary'] = $temporary;

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

			}
			# beban kerja penelitian
			$data['kode'] = 'B';
			$parameter  = array('api_search' => array($data['kode'],$kd_dosen, $thn, $smt));
			$data['pl'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			/*echo "<pre>";
			print_r($data['pl']);
			echo "<pre>";
			die();*/

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
				$api_url 	= URL_API_BKD.'bkd_remun/history_data_remun_prof';
				$parameter  = array('api_search' => array($kd_dosen, $thn, $smt));
				$data['dbp'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			}

			$html = $this->load->view('print/laporan_bkd_remun_dosen',$data,true);
			$pdf->writeHTML($html, true, false, true, false, '');
			
			$pdf->AddPage('P', array(297,210), false, false);
			$pdf->setPageOrientation('L',true,15);
			$html = $this->load->view('print/ttd_asesor',$data,true);
			$pdf->writeHTML($html, true, false, true, false, '');
			
			$pdf->lastPage();
			$pdf->Output('LBKD_REMUN_DOSEN.pdf', 'I');
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
	function cek_summary_remun_dosen($kd_dosen, $kd_smt, $kd_ta){
		$api_url 	= URL_API_BKD.'bkd_remun/cek_summary_remun_dosen';
		$parameter  = array('api_search' => array($kd_dosen, $kd_smt, $kd_ta));
		$get = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		return $get;
	}
	function _generate_ta($ta){
		return substr($ta, 0,4);
	}
	
	function _generate_smt($smt){
		if($smt == 'GENAP') return 2;
		else return 1;
	}
	function get_data_dosen_test(){	
			$kd = $this->session->userdata("kd_dosen");
			$api_url 	= URL_API_BKD.'bkd_dosen/get_data_dosen';
			$parameter  = array('data_search' => array($kd));
			$data['dosen'] = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);
			foreach ($data['dosen'] as $dosen) {
				$kd_fak = $dosen->KD_FAK;
			}
			return $kd_fak;
	}
	function get_asesor_remun_by_kd_fak(){
		$kd_fak = $this->get_data_dosen_test();
		$api_url 	= 'http://service2.uin-suka.ac.id/servsimpeg/index.php/remunerasi_dosen/agregasi/get_asesor_remunerasi_by_kd_fak';
		$parameter  = array('api_search' => array($kd_fak));
		$data['asesor'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		foreach ($data['asesor'] as $key) {
			$nip = $key['nip'];
			$get_data_dosen = $this->cariNIPNamaDosen($nip);
			foreach ($get_data_dosen as $gdd) {
				$nip = $gdd['NIP'];
				$nama = $gdd['NM_DOSEN_F'];
				$temp[] = array("nip"=>$nip, "nama"=>$nama);
			}
		}
		return $temp;
	}
	function cariNIPNamaDosen($q){
		#sia_dosen/data_search, 20000/13
		$api_url = URL_API_SIA.'sia_dosen/data_search';
		$parameter = array('api_kode' => 20000, 'api_subkode' => 14, 'api_search'=> array($q));
		$data['dosen'] = $this->s00_lib_api->get_api_json($api_url,'POST',$parameter);
		return $data['dosen'];
	}
}










