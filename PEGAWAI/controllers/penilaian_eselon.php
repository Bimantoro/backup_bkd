<?php
class Penilaian_eselon extends CI_Controller {
	protected $id_pegawai;
	protected $tahun;
	protected $triwulan;
	protected $skor_kinerja				= 0;
	protected $persen_bobot_kehadiran	= 20;
	protected $persen_bobot_iku			= 60;
	protected $persen_bobot_manajerial	= 20;
	protected $persen_bobot_tugas_tmb	= 50;
	
	public $npwp;
	public $kdlayer;
	public $nilai_p1;
	public $nilai_p2;
	public $grade_p1;
	public $grade_p2;
	public $grade_total_remun;
	public $total_nilai;
	public $gol_pegawai;
	public $pangkat_pegawai;
	public $pajak_gol;
	public $pegawai_kontrak= FALSE;
	public $potongan_kontrak= 50;
	public $potongan_skp;
	public $nm_hukuman_disiplin;
	public $potongan_hukuman;
	
	function __construct($id_pegawai=null, $tahun=null, $triwulan=null) {
		parent::__construct();
        $this->view = $this->s00_lib_output;
        $this->api = $this->load->library('s00_lib_api');
        $this->url = $this->load->library('lib_url');
        $this->user = $this->load->library('lib_user', $this->session->all_userdata());
        $this->util = $this->load->library('lib_util');
        $this->lib_skp = $this->load->library('lib_skp'); 
        $this->lib_remun = $this->load->library('lib_remun'); 
        $this->simpeg = $this->load->module('remunerasi/simpeg');
        
        $this->id_pegawai	= $id_pegawai ? $id_pegawai : '';
        $this->tahun		= $tahun ? $tahun : '';
        $this->triwulan		= $triwulan ? $triwulan : '';
        
		if (!$this->user->is_login()) {
			redirect();
		}

		error_reporting(0);
	}

	function set_id_pegawai($id_pegawai){
        $this->id_pegawai	= $id_pegawai;
	}
	
	function set_tahun($tahun){
        $this->tahun	= $tahun;
	}
	
	function set_triwulan($triwulan){
        $this->triwulan	= $triwulan;
	}	
	
	function set_pegawai($id_pegawai=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;

		$pangkat	= $this->simpeg->simpeg_pangkat_pegawai($id_pegawai);
		$this->gol_pegawai		= $pangkat['HIE_GOLONGAN'];
		$this->pangkat_pegawai	= $pangkat['HIE_RUANG'];
		$this->pajak_gol		= $this->lib_remun->pajak_gol($this->gol_pegawai);
		$this->pegawai_kontrak	= (empty($this->gol_pegawai)) || (substr($id_pegawai, 8, 6) === '000000'); //jika golongan kosong dia pegawai kontrak
	}
	
	function grade_remun_pegawai($id_pegawai=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		return $this->api->post($this->url->remun . '/grade_pegawai', array('ID_PEGAWAI'=>$id_pegawai));
	}

	function hukuman_disiplin($id_pegawai=null, $tahun=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$tahun	= (!empty($tahun)) ? $tahun : $this->tahun;

		$par	= array("ID_PEGAWAI"=>$id_pegawai, $tahun=>"TO_CHAR(TGL_HUKUMAN, 'YYYY')");
        $data	= $this->api->post($this->url->remun . '/hukuman_disiplin', $par);
        //print_r( $data );
        return $data;
	}
	
	function detail_hukuman_disiplin($id_pegawai=null, $tahun=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$tahun	= (!empty($tahun)) ? $tahun : $this->tahun;

		$format 	= 'd-m-Y';
		$sekarang	= strtotime(date($format));
		
		$detail['TERHUKUM']		= FALSE;
		$detail['KD_HUKUMAN']	= NULL;
		$detail['NM_HUKUMAN']	= NULL;
		$detail['POTONGAN']		= NULL;
		$detail['TGL_BERLAKU']	= NULL;
		$detail['TGL_BERAKHIR']	= NULL;
		$detail['DETAIL']		= array();
		
		$hukuman = $this->hukuman_disiplin($id_pegawai, $tahun);
		for($i=0;$i<count($hukuman);$i++){
			
			$awal 	= date_create_from_format('d'. $slash . 'm' . $slash . 'Y', $hukuman[$i]['TGL_BERLAKU_HUKUMAN']);
			$akhir 	= date_create_from_format('d'. $slash . 'm' . $slash . 'Y', $hukuman[$i]['TGL_BERAKHIR_HUKUMAN']);
			/* 
			$tgl_awal	= strtotime($awal->format($format));
			$tgl_akhir	= strtotime($akhir->format($format));
			*/
			/*
			$tgl_awal	= strtotime(date_format($awal,$format));
			$tgl_akhir	= strtotime(date_format($akhir,$format));
			*/
			
			$tgl_awal	= strtotime($hukuman[$i]['TGL_BERLAKU_HUKUMAN']);
			$tgl_akhir	= strtotime($hukuman[$i]['TGL_BERAKHIR_HUKUMAN']);
			
			//echo "$sekarang - $tgl_awal - $tgl_akhir<br>";
			$terhukum	= (($sekarang >= $tgl_awal) && ($sekarang <= $tgl_akhir)) || !$detail['TERHUKUM'];
			if($terhukum){
				$detail['TERHUKUM']	= $terhukum;
				$detail['KD_HUKUMAN']	= $hukuman[$i]['KD_HUKUMAN'];
				$detail['NM_HUKUMAN']	= $this->lib_remun->nm_hukuman_disiplin($hukuman[$i]['KD_HUKUMAN']);
				$detail['TGL_BERLAKU']	= $hukuman[$i]['TGL_BERLAKU_HUKUMAN'];
				$detail['TGL_BERAKHIR']	= $hukuman[$i]['TGL_BERAKHIR_HUKUMAN'];
				$detail['POTONGAN']		= $hukuman[$i]['POTONGAN_REMUN'];
			}
			
			$detail['DETAIL'][$i]=$hukuman[$i];
			$detail['DETAIL'][$i]['NM_HUKUMAN']	= $this->lib_remun->nm_hukuman_disiplin($hukuman[$i]['KD_HUKUMAN']);
			
		}
		
		//print_r( $detail );
		return $detail;
	}
	
	function kehadiran($id_pegawai=null, $tahun=null, $triwulan=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$tahun		= (!empty($tahun)) ? $tahun : $this->tahun;
		$triwulan	= (!empty($triwulan)) ? $triwulan : $this->triwulan;
		//return $this->api->post($this->url->remun . '/kehadiran_pegawai', array('ID_PEGAWAI'=> $id_pegawai, 'TAHUN'=>$tahun, 'triwulan'=>$triwulan));
		$data 		= $this->api->post($this->url->remun . '/kehadiran_pegawai_triwulan', array('ID_PEGAWAI'=> $id_pegawai, 'TAHUN'=>$tahun, 'TRIWULAN'=>$triwulan));
		return $data;
	}
	
	function iku($id_pegawai=null, $tahun=null, $triwulan=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$tahun		= (!empty($tahun)) ? $tahun : $this->tahun;
		$triwulan	= (!empty($triwulan)) ? $triwulan : $this->triwulan;
		
		//log_message('error', 'id: ' . $id_pegawai . ' tahun: ' . $tahun . ' triwulan: ' . $triwulan);
		$data 		= $this->api->post($this->url->remun . '/nilai_realisasi', array('id'=> $id_pegawai.$tahun, 'triwulan'=>$triwulan));
		return $data;
	}
	
	function manajerial($id_pegawai=null, $tahun=null, $triwulan=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$tahun		= (!empty($tahun)) ? $tahun : $this->tahun;
		$triwulan	= (!empty($triwulan)) ? $triwulan : $this->triwulan;
		$data 		= $this->api->post($this->url->remun . '/skor_nilai_manajerial', array('ID_PEGAWAI'=> $id_pegawai, 'TAHUN'=>$tahun, 'PERIODE'=>$triwulan));
		return $data;
	}
	
	function tugas_tambahan($id_pegawai=null, $tahun=null, $triwulan=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$tahun		= (!empty($tahun)) ? $tahun : $this->tahun;
		$triwulan	= (!empty($triwulan)) ? $triwulan : $this->triwulan;
		$data 		= $this->api->post($this->url->remun . '/poin_tugas_tambahan', array('ID_PEGAWAI'=> $id_pegawai, 'TAHUN'=>$tahun, 'TRIWULAN'=>$triwulan));
		return $data;
	}
	
	function set_total_nilai($id_pegawai=null, $tahun=null, $triwulan=null){
		//kehadiran
		$kehadiran		= $this->kehadiran($id_pegawai, $tahun, $triwulan);
		//log_message('error', 'kehadiran: ' . json_encode($kehadiran));
		$jml_hari 		= isset($kehadiran[0]['JML_HARI']) ? $kehadiran[0]['JML_HARI']: '1'; //default 1 agar tidak devision by zero
		$jml_hadir		= isset($kehadiran[0]['JML_HADIR']) ? $kehadiran[0]['JML_HADIR']: '0';
		$jml_terlambat	= isset($kehadiran[0]['JML_TELAT']) ? $kehadiran[0]['JML_TELAT']: '0';

		$jml_remun_hadir= $jml_hadir - $jml_terlambat;
		$jml_remun_hadir=  number_format((float)$jml_remun_hadir, 2);
		
		$skor_remun_hadir = ($jml_remun_hadir/$jml_hari)*100;
		$skor_remun_hadir = number_format((float)$skor_remun_hadir, 2); 
		
		$nilai_remun_hadir =  (!$this->persen_bobot_kehadiran==0) ? number_format(($this->persen_bobot_kehadiran/100)*$skor_remun_hadir,2) : '0';
		
		//iku
		$iku = $this->iku($id_pegawai, $tahun, $triwulan);
		//log_message('error', 'iku: ' . json_encode($iku));
		$total_iku 	= (isset($iku[0]['TOTAL_NILAI'])) ? $iku[0]['TOTAL_NILAI'] : 0;
		$jml_iku	= (isset($iku[0]['JML_KUESIONER'])) ? $iku[0]['JML_KUESIONER'] : 1; // default 1 agar tidak division by zero
		$skor_iku 	= $total_iku/$jml_iku;
		
		$nilai_iku =  (!$this->persen_bobot_iku==0) ? number_format(($this->persen_bobot_iku/100)*$skor_iku,2) : '0';
		
		//manajerial
		$manajerial = $this->manajerial($id_pegawai, $tahun, $triwulan);
		//log_message('error', 'manajerial: ' . json_encode($manajerial));
		$total_manajerial	= (isset($manajerial[0]['TOTAL_SKOR'])) ? $manajerial[0]['TOTAL_SKOR'] : 0;
		$jml_manajerial		= (isset($manajerial[0]['JML_KUESIONER'])) ? $manajerial[0]['JML_KUESIONER'] : 1; //default 1 agar tidak devision by zero
		$skor_manajerial 	= $total_manajerial/$jml_manajerial;
		
		$nilai_manajerial =  (!$this->persen_bobot_manajerial==0) ? number_format(($this->persen_bobot_manajerial/100)*$skor_manajerial,2) : '0';
		
		//tugas_tambahan
		$tugas_tambahan = $this->tugas_tambahan($id_pegawai, $tahun, $triwulan);
		$poin_tugas		= (isset($tugas_tambahan [0]['TOTAL_POIN'])) ? $tugas_tambahan [0]['TOTAL_POIN'] : 0;
		$jml_tugas		= (isset($tugas_tambahan [0]['JML_TUGAS'])) ? $tugas_tambahan [0]['JML_TUGAS'] : 1; //agar tidak devision by zero
		$skor_tugas 	= $poin_tugas/$jml_tugas;
		
		$nilai_tugas =  (!$this->persen_bobot_tugas_tmb==0) ? number_format(($this->persen_bobot_tugas_tmb/100)*$skor_tugas,2) : '0';

		$this->total_nilai = $nilai_remun_hadir + $nilai_iku + $nilai_manajerial + $nilai_tugas;
		//log_message('error', 'nilai total: ' . $this->total_nilai);
	}
	
	function skp_sebelum_periode($id_pegawai=null, $periode=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$periode	= (!empty($periode)) ? $periode : $this->periode;
		
        $par	= array('ID_PEGAWAI'=>$id_pegawai, 'TAHUN'=>$periode-1);
        return $this->api->post($this->url->remun . '/nilai_skp_remun', $par);
	}
	
	function set_skp($id_pegawai=null, $periode=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$periode	= (!empty($periode)) ? $periode : $this->periode;

		$skp 		= $this->skp_sebelum_periode($id_pegawai, $periode);
		$nilai_ 	= 4;
		$terbilang 	= 'BAIK';
		$this->potongan_skp	= 0;
		if (!empty($skp)){
			$nilai_		= $skp[0]['NILAI'];
			switch ($nilai_){
				case 1: $terbilang = 'BURUK'; break;
				case 2: $terbilang = 'KURANG'; break;
				case 3: $terbilang = 'CUKUP'; break;
				default: $terbilang = 'BAIK'; break;
			}
			$this->potongan_skp		= $skp[0]['PERSEN_POTONGAN'];
		}
	}
	
	function set_hukuman($id_pegawai=null, $tahun=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$tahun	= (!empty($tahun)) ? $tahun : $this->tahun;

		$hukuman 	= $this->detail_hukuman_disiplin($id_pegawai, $tahun);
		$this->nm_hukuman_disiplin = $hukuman['NM_HUKUMAN'];
		$this->potongan_hukuman = $hukuman['POTONGAN'];
	}
	
	function set_nominal_grade($id_pegawai=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		
		$grade_remun 	= $this->grade_remun_pegawai($id_pegawai);
		
		$this->nilai_p1 			= (!$this->pegawai_kontrak) ? $grade_remun[0]['P1'] : ($this->potongan_kontrak/100) * $grade_remun[0]['P1'];
		$this->nilai_p2 			= (!$this->pegawai_kontrak) ? $grade_remun[0]['P2'] : ($this->potongan_kontrak/100) * $grade_remun[0]['P2'];
		$this->nilai_p1				= round($this->nilai_p1, 2);
		$this->nilai_p2				= round($this->nilai_p2, 2);
		$this->kdlayer	 			= $grade_remun[0]['KD_LAYER'];
		$this->grade_p1 			= $grade_remun[0]['P1'];
		$this->grade_p2 			= $grade_remun[0]['P2'];
		$this->grade_total_remun 	= $grade_remun[0]['TOTAL_REMUN'];
	}
	
	function simpan_penerimaan_remun($id_pegawai=null, $tahun=null, $triwulan=null){
		$id_pegawai	= (!empty($id_pegawai)) ? $id_pegawai : $this->id_pegawai;
		$tahun	= (!empty($tahun)) ? $tahun : $this->tahun;
		$triwulan		= (!empty($triwulan)) ? $triwulan : $this->triwulan;
		
		$this->set_pegawai($id_pegawai);
		$this->set_skp($id_pegawai, $tahun);
		$this->set_hukuman($id_pegawai, $tahun);
		$this->set_nominal_grade($id_pegawai);
		$this->set_total_nilai();
		
		//perhitungan P1
		$nilai_pajak_p1 = ($this->pajak_gol !=0) ? ($this->pajak_gol/100) * $this->nilai_p1 : '0';
		$nilai_pajak_p1	= round($nilai_pajak_p1, 2);
		$penerimaan_p1 	= ($this->pajak_gol !=0) ? $this->nilai_p1 - $nilai_pajak_p1 : $this->nilai_p1;
		
		//perhitungan P2
		$nominal_perhitungan_p2 = $this->nilai_p2 * ($this->total_nilai/100); //100=persen
		$nominal_perhitungan_p2 = round($nominal_perhitungan_p2, 2);
		
		//potongan A
		$nilai_potongan_skp = ($this->potongan_skp/100) * $nominal_perhitungan_p2;
		$nilai_potongan_skp	= round($nilai_potongan_skp, 2);
		$nominal_p2_setelah_potongan_A = $nominal_perhitungan_p2 - $nilai_potongan_skp;
		
		//potongan B
		$nominal_potongan_hukuman = ($this->potongan_hukuman/100) * $nominal_p2_setelah_potongan_A;
		$nominal_potongan_hukuman = round($nominal_potongan_hukuman, 2);
		$nominal_p2_setelah_potongan_B = $nominal_p2_setelah_potongan_A - $nominal_potongan_hukuman;
		
		//pajak
		$nilai_pajak_p2 	= ($this->pajak_gol !=0) ? ($this->pajak_gol/100) * $nominal_p2_setelah_potongan_B : '0';
		$nilai_pajak_p2		= round($nilai_pajak_p2, 2);
		$penerimaan_p2 		= ($this->pajak_gol !=0) ? $nominal_p2_setelah_potongan_B - $nilai_pajak_p2 : $nominal_p2_setelah_potongan_B;
		$total_penerimaan 	= $penerimaan_p1 + $penerimaan_p2;
		
		/*field
		"tahun", "triwulan", "ID_PEGAWAI", "P1", "PAJAK_P1", "NOMINAL_PAJAK_P1", "P2", 
		"POT_A_P2", "NOMINAL_POT_A_P2", "POT_B_P2", "NOMINAL_POT_B_P2", "PAJAK_P2", 
		"NOMINAL_PAJAK_P2", "TOTAL_TERIMA", "GOL_PANGKAT", "KD_LAYER", "PEROLEHAN_P2"
		*/
			
		$par	= array(
						"TAHUN"					=> $tahun, 
						"PERIODE"				=> $triwulan, 
						"ID_PEGAWAI"			=> $id_pegawai, 
						"P1"					=> $this->nilai_p1, 
						"PAJAK_P1"				=> $this->pajak_gol, 
						"NOMINAL_PAJAK_P1"		=> $nilai_pajak_p1, 
						"P2"					=> $this->nilai_p2, 
						"POT_A_P2"				=> $this->potongan_skp, 
						"NOMINAL_POT_A_P2"		=> $nilai_potongan_skp, 
						"POT_B_P2"				=> $this->potongan_hukuman, 
						"NOMINAL_POT_B_P2"		=> $nominal_potongan_hukuman, 
						"PAJAK_P2"				=> $this->pajak_gol, 
						"NOMINAL_PAJAK_P2"		=> $nilai_pajak_p2, 
						"TOTAL_TERIMA"			=> $total_penerimaan,
						"GOL_PANGKAT"			=> $this->gol_pegawai . '/' . $this->pangkat_pegawai,
						"KD_LAYER"				=> $this->kdlayer,		// GRADE
						"PEROLEHAN_P2"			=> $nominal_perhitungan_p2
						);
		
		log_message('error', json_encode($par));
        $data	= $this->api->post($this->url->remun . '/simpan_penerimaan_remun', $par);
        
        //$data 	= $par;
        return $data;
	}
}
?>
