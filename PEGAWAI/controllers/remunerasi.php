<?php
class Remunerasi extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->view = $this->s00_lib_output;
        $this->api = $this->load->library('s00_lib_api');
        $this->url = $this->load->library('lib_url');
        $this->user = $this->load->library('lib_user', $this->session->all_userdata());
        $this->util = $this->load->library('lib_util');
        $this->lib_skp = $this->load->library('lib_skp'); 
        $this->lib_remun = $this->load->library('lib_remun'); 
        $this->simpeg = $this->load->module('remunerasi/simpeg');
        $this->penilaian = $this->load->module('remunerasi/penilaian');
        $this->penilaian_eselon = $this->load->module('remunerasi/penilaian_eselon');
		$this->load->helper('simpeg');

        $this->session->set_userdata('app', 'remunerasi_app');

        if (!$this->lib_user->is_login()) {
            redirect();
        }
        /*if ($this->user->get_id() != '199303110000001101') {
            //$this->masalah();
        } else {
            $this->user->set_id('198206012005012017');
        }*/
    }
    
    function riwayat_penilaian_perilaku() {
		$tanggal = date('d-m-Y');
		/*
        $data['pegawai'] 			= $this->api->post($this->url->skp . '/pegawai/' . $this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->api->post($this->url->skp . '/jabatan/' . $this->user->get_id() . '/' . date('d-m-Y'));
        $data['bawahan'] 			= $this->api->post($this->url->skp . '/bawahan/' . $this->user->get_id());
        */
        $data['pegawai'] 			= $this->simpeg->pegawai($this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
        $data['bawahan'] 			= $this->simpeg->bawahan($this->user->get_id());
        		
        $this->view->output_display('riwayat_penilaian_perilaku', $data);
        //penambahan filter periode:tahun bulan: bulan
    }

    function riwayat_perilaku_bawahan($kd_bawahan,$periode=null, $bulan=null) {
		$par		= array('PERIODE'=> $periode, 'BULAN'=>$bulan);
		$masa		= $this->api->post($this->url->remun . '/masa_penilaian_pegawai', $par);
        $periode 	= is_null($periode) ? date('Y') : $periode; 
        $bulan		= is_null($bulan) ? date('m') : $bulan;
        $kd_bawahan = is_null($kd_bawahan) ? '-' : $kd_bawahan;
        /*
        $data['pegawai'] 	= $this->api->post($this->url->skp . '/pegawai/' . $this->user->get_id());
        $data['bawahan'] 	= $this->api->post($this->url->skp . '/pegawai/' . $kd_bawahan);
        */
        $data['pegawai'] 	= $this->simpeg->pegawai($this->user->get_id());
        $data['bawahan'] 	= $this->simpeg->pegawai($kd_bawahan);
        $data['nilai']		= $this->api->post($this->url->remun . '/perilaku_pegawai/' . $kd_bawahan . '/' . $periode . '/' . $bulan);
        $data['perilaku'] 	= $this->api->post($this->url->remun . '/perilaku');
        $data['periode']	= $periode;
        $data['bulan']		= $bulan;
        $data['kd_bawahan']	= $kd_bawahan;
        $data['masa'] 		= $masa;

        $this->view->output_display('riwayat_perilaku_bawahan', $data);
    }
    
    function simpan_perilaku_pegawai(){
       echo json_encode(array('hasil'=>$this->api->post($this->url->remun . '/simpan_perilaku_pegawai', $_POST)));
	}
	
	function update_perilaku_pegawai(){
       echo json_encode(array('hasil'=>$this->api->post($this->url->remun . '/update_perilaku_pegawai', $_POST)));
	}
	
	function pegawai_adl_pejabat($id){
		$ljabatan = $this->api->post($this->url->remun . '/jabatan_iku');
		$parameter = array('api_kode' => 1121, 'api_subkode' => 3, 'api_search' => array(date('d-m-Y'), $id, 1)); #tgl, kd_pgw, status aktif/tidak aktif
		//$parameter = array('api_kode' => 1121, 'api_subkode' => 3, 'api_search' => array(date('d-m-Y'), $this->user->get_id(), 1)); #tgl, kd_pgw, status aktif/tidak aktif
		$data = $this->api->post($this->url->simpeg_search, $parameter);
		$jabatan	= (!empty($data)) ? $data[0]['STR_ID'] : '';

		$pejabat = FALSE;
		if ($jabatan === '7654321V'){ //rektor
			$pejabat = TRUE;
		} else {
			if (!empty($jabatan)){
				for($i=0;$i<count($ljabatan);$i++){
					$jab = $ljabatan[$i];
					$pejabat = ($jabatan==$jab['STR_ID']);
					if ($pejabat) { break; }
				}
			}
		}
		
		//echo ($pejabat) ? "Pejabat" : "Bukan Pejabat";
		return $pejabat;
	}
	
	function cetak_kinerja_pegawai(){
        $periode = date('Y'); $bulan=date('m');
        /*
        $data['pegawai'] 			= $this->api->post($this->url->skp . '/pegawai/' . $this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->api->post($this->url->skp . '/jabatan/' . $this->user->get_id() . '/' . date('d-m-Y'));
        */
		
        $data['pejabat'] 			= $this->pegawai_adl_pejabat($this->user->get_id());
        $data['pegawai'] 			= $this->simpeg->pegawai($this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
        //$data['bawahan'] 			= $this->simpeg->bawahan($this->user->get_id());
        $data['periode']			= $periode;
        $data['bulan']				= $bulan;
		$data['dosen']				= $this->simpeg->data_dosen($this->user->get_id());

        $this->view->output_display('cetak_capaian_kinerja', $data);
        //penambahan filter periode:tahun bulan: bulan
	}
	
	function cetak_kinerja_bawahan(){
        $periode = date('Y'); $bulan=date('m');
        $data['bawahan'] 			= $this->simpeg->bawahan($this->user->get_id());
        $data['periode']			= $periode;
        $data['bulan']				= $bulan;

        $this->view->output_display('cetak_capaian_kinerja_bawahan', $data);
	}
	
	function pdf_capaian_kinerja($id, $periode, $bulan){
		$tglawal 			= '01-'.$bulan.'-'.$periode;
		if ($this->pegawai_adl_pejabat($id)==true){
			//echo 'pejabat';
			$wn['TABLE'] 				= "PENILAIAN_LKP";
			$wn['WHERE']['ID_LKP'] 		= $id . $periode . intval($bulan);
			$data['kinerja'] 			= $this->api->post($this->url->skp . '/get_data', $wn);

			$data['pegawai'] 					= $this->simpeg->pegawai($id);
			$data['pegawai'] 					= $this->simpeg->pegawai($id);
			//$data['pegawai']['JABATAN'] 		= $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
			$data['pegawai']['JABATAN'] 		= $this->simpeg->jabatan($id, date('d-m-Y'));
			$data['pegawai']['JABATAN_LGSG'] 	= true;
			$data['pegawai']['UNIT'] 			= $this->simpeg->simpeg_history_unit($tglakhir, $data['pegawai']['JABATAN']['DETAIL']['STR_UNIT'])[0];
			$data['pegawai']['PANGKAT'] 		= $this->simpeg->simpeg_pangkat_pegawai($id, $tglakhir);
			$data['pegawai']['DOSEN'] 			= $this->simpeg->data_dosen($id);
			$data['pegawai']['NILAI_MANAJERIAL']= $this->api->post($this->url->remun . '/skor_nilai_manajerial', array('ID_PEGAWAI'=> $id, 'TAHUN'=>$periode, 'PERIODE'=>$bulan));
			$data['pegawai']['NILAI_IKU']		= $this->api->post($this->url->remun . '/nilai_realisasi', array('id'=> $id.$periode, 'triwulan'=>$bulan));
			//$data['pegawai']['NILAI'] 			= $this->api->post($this->url->remun . '/perilaku_pegawai/' . $id . '/' . $periode . '/' . $bulan);
			$data['pegawai']['KEHADIRAN']		= $this->api->post($this->url->remun . '/kehadiran_pegawai_triwulan', array('ID_PEGAWAI'=> $id, 'TAHUN'=>$periode, 'BULAN'=>$bulan));
			$data['pegawai']['TUGAS_TAMBAHAN']	= $this->api->post($this->url->remun . '/poin_tugas_tambahan', array('ID_PEGAWAI'=> $id, 'TAHUN'=>$periode, 'TRIWULAN'=>$bulan));
			$data['penilai'] 					= $this->simpeg->atasan($id, $tglawal);
			$data['penilai']['JABATAN'] 		= $this->simpeg->jabatan($data['penilai']['DETAIL']['KD_PGW'] , date('d-m-Y'));
			$data['periode']					= $periode;
			$data['triwulan']					= $bulan;

			$this->printPDF('pdf_capaian_kinerja_eselon', $data, 'laporan_capaian_kinerja' . $id. '_'. $bulan . $periode);
		} else {
			$tglakhir 			= date('t-m-Y', strtotime($tglawal));
			$data['tanggal'] 	= array('awal' => $tglawal, 'akhir' => $tglakhir);
			
			// $wn['TABLE'] 				= "PENILAIAN_LKP";
			// $wn['WHERE']['ID_LKP'] 		= $id . $periode . intval($bulan);
			// $data['kinerja'] 			= $this->api->post($this->url->skp . '/get_data', $wn);

			$this->penilaian->set_id_pegawai($id);
			$this->penilaian->set_periode($periode);
			$this->penilaian->set_bulan($bulan);
			$this->penilaian->hitung();

			$data['pegawai'] 					= $this->simpeg->pegawai($id);
			//$data['pegawai']['JABATAN'] 		= $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
			$data['pegawai']['JABATAN'] 		= $this->simpeg->jabatan($id, date('d-m-Y'));
			$data['pegawai']['JABATAN_LGSG'] 	= true;
			$data['pegawai']['UNIT'] 			= $this->simpeg->simpeg_history_unit($tglakhir, $data['pegawai']['JABATAN']['DETAIL']['STR_UNIT'])[0];
			$data['pegawai']['PANGKAT'] 		= $this->simpeg->simpeg_pangkat_pegawai($id, $tglakhir);
			$data['pegawai']['DOSEN'] 			= $this->simpeg->data_dosen($id);
			// $data['pegawai']['NILAI'] 			= $this->api->post($this->url->remun . '/perilaku_pegawai/' . $id . '/' . $periode . '/' . $bulan);
			// $data['pegawai']['KEHADIRAN']		= $this->api->post($this->url->remun . '/kehadiran_pegawai', array('ID_PEGAWAI'=> $id, 'TAHUN'=>$periode, 'BULAN'=>$bulan));
			$data['penilai'] 					= $this->simpeg->atasan($id, $tglawal);
			$data['penilai']['JABATAN'] 		= $this->simpeg->jabatan($data['penilai']['DETAIL']['KD_PGW'] , date('d-m-Y'));
			$data['periode']					= $periode;
			$data['bulan']						= $bulan;

			$v_dosen = ($data['pegawai']['DOSEN']) ? 'pdf_capaian_kinerja_dosen' : 'pdf_capaian_kinerja';
			
			//$this->printPDF('pdf_capaian_kinerja', $data, 'laporan_capaian_kinerja' . $id. '_'. $bulan . $periode);
			$this->printPDF($v_dosen, $data, 'laporan_capaian_kinerja' . $id. '_'. $bulan . $periode);
		}
	}
	
	function printPDF($view, $data, $output, $settings=null){
		$laporan 	= $this->load->module('remunerasi/laporan');
		$html 		= $this->load->view($view, $data, true);
		
		$laporan->pdf->loadHtml($html);
		$laporan->pdf->render();
		$laporan->setSubject(ucwords(str_replace('_', ' ', $str=$output)));
		$laporan->pdf->stream($output,array('Attachment'=>0));
		
		//$laporan->test();
	}
	
	function masa(){
		$par		= array('PERIODE'=> $periode, 'BULAN'=>$bulan);
		$masa		= $this->api->post($this->url->remun . '/masa_penilaian_pegawai', $par);
		print_r($masa);
		echo '<br>';
		echo 'nilai';
		echo '<br>';
		for($i=0;$i<count($masa['MASA']);$i++){
			echo $masa[$i]['T_AWAL_NILAI'] . ' ' . $masa[$i]['T_AKHIR_NILAI'];
		  $awal = date_create_from_format('m-d-Y', $masa[$i]['AWAL_NILAI']);
		  $akhir = date_create_from_format('m-d-Y', $masa[$i]['AKHIR_NILAI']);
		  
		  $awal_nilai = strtotime($awal->format('m-d-Y'));
		  $akhir_nilai = strtotime($akhir->format('m-d-Y'));
		  $r_nilai = (($now >= $awal_nilai) && ($now <= $akhir_nilai));
		  echo $awal_nilai . ' ' . $akhir_nilai;
		  echo (int)$r_nilai . '<br>';
		}
	}
	
	function nilai($id, $periode, $bulan){
		$wn['TABLE'] 				= "PENILAIAN_LKP";
		$wn['WHERE']['ID_LKP'] 		= $id . $periode . $bulan;
        $data['kinerja'] 			= $this->api->post($this->url->skp . '/get_data', $wn);
		print_r($data['kinerja'][0]['NILAI_CAPAIAN']);
	}
	
	function cetak_penerimaan_remun(){
        $periode = date('Y'); $bulan=date('m');
        /*
        $data['pegawai'] 			= $this->api->post($this->url->skp . '/pegawai/' . $this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->api->post($this->url->skp . '/jabatan/' . $this->user->get_id() . '/' . date('d-m-Y'));
        */
        $data['pegawai'] 			= $this->simpeg->pegawai($this->user->get_id());
        $data['pegawai']['JABATAN'] = $this->simpeg->jabatan($this->user->get_id(), date('d-m-Y'));
        $data['bawahan'] 			= $this->simpeg->bawahan($this->user->get_id());
        $data['periode']			= $periode;
        $data['bulan']				= $bulan;
		$data['dosen']				= $this->simpeg->data_dosen($this->user->get_id());
		$data['pejabat']			= pegawai_adl_pejabat($this->user->get_id());

		/*
		$pejabat = pegawai_adl_pejabat($this->user->get_id());
		if ($pejabat){
			echo 'pejabat';
			return false;
		}
		*/
        $this->view->output_display('cetak_penerimaan_remun', $data);
	}
	
	function pdf_penerimaan_remun($id, $periode, $bulan){
		$pejabat = pegawai_adl_pejabat($id);
		if ($pejabat){
			$this->penilaian_eselon->set_id_pegawai($id);
			$this->penilaian_eselon->set_tahun($periode);
			$this->penilaian_eselon->set_triwulan($bulan);
			
			//$simpan_remun = FALSE;
			$this->penilaian_eselon->simpan_penerimaan_remun();
			$nilai_remun = array(
									'KINERJA' 	=> $this->penilaian_eselon->total_nilai,
									'P1'		=> $this->penilaian_eselon->nilai_p1,
									'PAJAK_P1'	=> $this->penilaian_eselon->pajak_gol,
									'P2'		=> $this->penilaian_eselon->nilai_p2,
									'POT_P2_A'	=> $this->penilaian_eselon->potongan_skp,
									'POT_P2_B'	=> $this->penilaian_eselon->potongan_hukuman,
									'PAJAK_GOL'	=> $this->penilaian_eselon->pajak_gol,
									'HUKUMAN'	=> $this->penilaian_eselon->nm_hukuman_disiplin
								);
			log_message('error', json_encode($nilai_remun));
			//nilai skp
			$par						= array('ID_PEGAWAI'=>$id, 'TAHUN'=>$periode-1);
			$data['nilai_skp'] 			= $this->api->post($this->url->remun . '/nilai_skp_remun', $par);
			
			$data['pegawai'] 					= $this->simpeg->pegawai($id);
			$data['pegawai']['JABATAN'] 		= $this->simpeg->jabatan($id, date('d-m-Y'));
			$data['pegawai']['JABATAN_LGSG'] 	= true;
			$data['pegawai']['UNIT'] 			= $this->simpeg->simpeg_history_unit($tglakhir, $data['pegawai']['JABATAN']['DETAIL']['STR_UNIT'])[0];
			$data['pegawai']['PANGKAT'] 		= $this->simpeg->simpeg_pangkat_pegawai($id, $tglakhir);
			$data['pegawai']['GRADE_REMUN']		= $this->api->post($this->url->remun . '/grade_pegawai', array('ID_PEGAWAI'=>$id));
			$data['pegawai']['PENERIMAAN_REMUN']= $nilai_remun;
			$data['periode']					= $periode;
			$data['bulan']						= $bulan;
			
			$this->printPDF('pdf_penerimaan_remun_eselon', $data, 'penerimaan_remun_' . $id. '_'. $bulan . $periode);
		} else {
			//penambahan fungsi simpan penerimaan remun
			$this->penilaian->set_id_pegawai($id);
			$this->penilaian->set_periode($periode);
			$this->penilaian->set_bulan($bulan);

			//$simpan_remun = FALSE;
			$this->penilaian->simpan_penerimaan_remun();

			$tglawal 			= '01-'.$bulan.'-'.$periode;
			$tglakhir 			= date('t-m-Y', strtotime($tglawal));
			$data['tanggal'] 	= array('awal' => $tglawal, 'akhir' => $tglakhir);
			/*
			//lkp
			$wn['TABLE'] 				= "PENILAIAN_LKP";
			$wn['WHERE']['ID_LKP'] 		= $id . $periode . intval($bulan);
			$data['kinerja'] 			= $this->api->post($this->url->skp . '/get_data', $wn);
			
			//nilai skp
			$par						= array('ID_PEGAWAI'=>$id, 'TAHUN'=>$periode-1);
			$data['nilai_skp'] 			= $this->api->post($this->url->remun . '/nilai_skp_remun', $par);
			//hukuman disliplin
			$par						= array("ID_PEGAWAI"=>$id, $periode=>"TO_CHAR(TGL_HUKUMAN, 'YYYY')");
			$data['hukuman_disiplin']	= $this->api->post($this->url->remun . '/hukuman_disiplin', $par);        
			*/
			
			$data['pegawai'] 					= $this->simpeg->pegawai($id);
			$data['pegawai']['JABATAN'] 		= $this->simpeg->jabatan($id, date('d-m-Y'));
			$data['pegawai']['JABATAN_LGSG'] 	= true;
			$data['pegawai']['UNIT'] 			= $this->simpeg->simpeg_history_unit($tglakhir, $data['pegawai']['JABATAN']['DETAIL']['STR_UNIT'])[0];
			$data['pegawai']['PANGKAT'] 		= $this->simpeg->simpeg_pangkat_pegawai($id, $tglakhir);
			$data['pegawai']['DOSEN'] 			= $this->simpeg->data_dosen($id);  //jika pegawai adalah dosen
			$data['pegawai']['GRADE_REMUN']		= $this->api->post($this->url->remun . '/grade_pegawai', array('ID_PEGAWAI'=>$id));
			// $data['pegawai']['NILAI'] 			= $this->api->post($this->url->remun . '/perilaku_pegawai/' . $id . '/' . $periode . '/' . $bulan);
			// $data['pegawai']['KEHADIRAN']		= $this->api->post($this->url->remun . '/kehadiran_pegawai', array('ID_PEGAWAI'=> $id, 'TAHUN'=>$periode, 'BULAN'=>$bulan));
			//$data['penilai'] 					= $this->simpeg->atasan($id, $tglawal);
			//$data['penilai']['JABATAN'] 		= $this->simpeg->jabatan($data['penilai']['DETAIL']['KD_PGW'] , date('d-m-Y'));
			$data['periode']					= $periode;
			$data['bulan']						= $bulan;
					
			$this->printPDF('pdf_penerimaan_remun', $data, 'penerimaan_remun_' . $id. '_'. $bulan . $periode);
		}
	}
	
	function skr($id, $periode){
        $par						= array('ID_PEGAWAI'=>$id, 'TAHUN'=>$periode-1);
        $data['nilai_skp'] 			= $this->api->post($this->url->remun . '/nilai_skp_remun', $par);        
        print_r($data);
	}
	
	function hukuman($id, $periode){
		$par	= array('ID_PEGAWAI'=>$id, $periode=>"TO_CHAR(TGL_HUKUMAN, 'YYYY')");
        $data	= $this->api->post($this->url->remun . '/hukuman_disiplin', $par);
        /*$hukuman = array();
        for($i=0;$i<count($data);$i++){
			
		}*/
        print_r($data);
	}
	
	function test($id, $tahun, $periode){
		$data = $this->api->post($this->url->remun . '/poin_tugas_tambahan', array('ID_PEGAWAI'=> $id, 'TAHUN'=>$tahun, 'TRIWULAN'=>$periode));
		print_r($data);
	}
}
?>
