<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Libsimpeg extends CI_Controller {
	public $dummy = array(
					  'DETAIL' => array(
						  'NAMA_LENGKAP' => 'BELUM DIRELASIKAN',
						  'KD_PGW' => 'BELUM DIRELASIKAN'
					  ),
					  'JABATAN' => array(
						  'DETAIL' => array(
							  'STR_ID' => 'NOT_FOUND',
							  'STR_NAMA' => 'NOT_FOUND',
							  'UNIT_NAMA' => 'NOT_FOUND'
						  )
					  )
					);

    function __construct() {
		$this =& get_instance();
       
		$this->api = $this->load->library('s00_lib_api');
        $this->url = $this->load->library('lib_url');
        $this->user = $this->load->library('lib_user', $this->session->all_userdata());
        $this->util = $this->load->library('lib_util');
		
	}

  function simpeg_one_jabatan($kd_jabatan) {
    $data = $this->api->post($this->url->simpeg_search, array('api_kode' => 1101, 'api_subkode' => 1, 'api_search' => array($kd_jabatan)));
    if (isset($data[0])) {
      return $data[0];
    } else {
      $data = $this->api->post($this->url->simpeg_search, array('api_kode' => 1102, 'api_subkode' => 1, 'api_search' => array($kd_jabatan)));
      return $data[0];
      // return array('STR_NAMA' => 'Nama Tidak Ditemukan');
    }
  }

  function simpeg_ranting_pegawai($arah, $kd_pegawai, $tanggal = null) {
    $tanggal = $tanggal ? $tanggal : date('d-m-Y');
	//$data = $this->api->post($this->url->simpeg_search, array('api_kode' => 1216, 'api_subkode' => ($arah == 'bawah' ? 14 : 17), 'api_search' => array($tanggal, $kd_pegawai, 1, 8))); #by Dedy
	$data = $this->api->post($this->url->simpeg_search, array('api_kode' => 1215, 'api_subkode' => ($arah == 'bawah' ? 1 : 2), 'api_search' => array($tanggal, $kd_pegawai, 1, 1)));  //STATUS AKTIF
	log_message('error', $this->url->simpeg_search . 'ranting: ' . json_encode($data));	
    return $data;
  }

  function simpeg_atasan_fungsional($kd_pegawai, $tanggal = null) {
    $tanggal = $tanggal ? $tanggal : date('d-m-Y');
    // $tgl = date('d/m/Y');
    // atasan fung bawahan fung
    $fungfung = $this->api->post($this->url->simpeg_search, array('api_kode' => 1191, 'api_subkode' => 5, 'api_search' => array($tgl, $kd_pegawai)));
    // atasan struk bawahan fung
    $strukfung = $this->api->post($this->url->simpeg_search, array('api_kode' => 1192, 'api_subkode' => 5, 'api_search' => array($tgl, $kd_pegawai)));

    if ($fungfung) {
      $fungfung[0]['TOP_KD_PGW'] = $fungfung[0]['TFF_TOP_KD_PGW'];
      return $fungfung[0];
    } else if ($strukfung) {
      $strukfung[0]['TOP_KD_PGW'] = $strukfung[0]['TFS_TOP_KD_PGW'];
      return $strukfung[0];
    }

    return array($fungfung, $strukfung);
  }

  function simpeg_jabatan_struktural($kd_pegawai, $tanggal = null) {
    $tanggal = $tanggal ? $tanggal : date('d-m-Y');
    // 5 riwayat
    // 13 sesuai tanggal
    // $data = $this->api->post($this->url->simpeg_search, array('api_kode' => 1121, 'api_subkode' => 13, 'api_search' => array(date($tanggal), $kd_pegawai)));
    $jbt = $this->api->post($this->url->simpeg_search, array('api_kode' => 1121, 'api_subkode' => 5, 'api_search' => array($kd_pegawai)));

    if (strlen($tanggal) > 4) {
      $tgltime = strtotime(date($tanggal));

      $c_jbt = count($jbt);
      $jbt2 = [];
      for ($i = 0; $i < $c_jbt; $i++) {
        $tm_mulai = strtotime($jbt[$i]['RR_TANGGAL_MULAI']);
        $tm_akhir = strtotime($jbt[$i]['RR_TANGGAL_AKHIR']);
        if (($tm_mulai <= $tgltime && $tm_akhir >= $tgltime) || ($tm_mulai <= $tgltime && !$tm_akhir)) {
          $jbt2[] = $jbt[$i];
        }
      }
    } else {
      $newest = null;
      $time_akhir_periode = strtotime('31-12-' . $tanggal);
      $tgl_awal_diuin = null;
      foreach ($jbt as $j) {
        if (!$tgl_awal_diuin) {
          $tgl_awal_diuin = $j['RR_TANGGAL_MULAI'];
        }
        $time_awal_diuin = strtotime($tgl_awal_diuin);
        $time_jbt_awal_diuin = strtotime($j['RR_TANGGAL_MULAI']);
        if ($time_jbt_awal_diuin < $time_awal_diuin) {
          $tgl_awal_diuin = $j['RR_TANGGAL_MULAI'];
        }
      }
      foreach ($jbt as $j) {
        if (!$newest) {
          $newest = $j;
        }
        $time_jbt_mulai = strtotime($j['RR_TANGGAL_MULAI']);
        $time_jbt_akhir = strtotime($j['RR_TANGGAL_AKHIR']);
        $time_newest_mulai = strtotime($newest['RR_TANGGAL_MULAI']);
        $time_newest_akhir = strtotime($newest['RR_TANGGAL_AKHIR']);

        if ($time_jbt_mulai <= $time_akhir_periode) {
          if ($time_newest_akhir) {
            if (!$time_jbt_akhir) {
              $newest = $j;
            } else if ($time_jbt_akhir > $time_newest_akhir) {
              $newest = $j;
            }
          }
          if ($time_jbt_akhir == $time_newest_akhir) {
            if ($time_jbt_mulai < $time_newest_mulai) {
              $newest = $j;
            }
          }
        }
        $newest['TGL_AWAL_DI_UIN'] = $tgl_awal_diuin;
        $jbt2 = array($newest);
      }
    }
    return $jbt2;
  }

  function simpeg_jabatan_fungsional($kd_pegawai, $tanggal = null) {
    $tanggal = $tanggal ? $tanggal : date('d-m-Y');
    if (strlen($tanggal) > 4) {
      $tgltime = strtotime(date($tanggal));

      $data = $this->api->post($this->url->simpeg_search, array('api_kode' => 1122, 'api_subkode' => 13, 'api_search' => array(date($tanggal), $kd_pegawai)));

      $c_data = count($data);
      for ($i = 0; $i < $c_data; $i++) {
        if ($data[$i]['RF_TANGGAL_AKHIR']) {
          $akhir_jabatan = strtotime($data[$i]['RF_TANGGAL_AKHIR']);
          if ($akhir_jabatan < $tgltime) {
            array_splice($data, $i, 1);
          }
        }
      }
    } else {
      $data = $this->api->post($this->url->simpeg_search, array('api_kode' => 1122, 'api_subkode' => 5, 'api_search' => array($kd_pegawai)));
      $newest = null;
      $time_akhir_periode = strtotime('31-12-' . $tanggal);
      foreach ($data as $j) {
        $time_jbt_mulai = strtotime($j['RF_TANGGAL_MULAI_F']);
        $time_jbt_akhir = strtotime($j['RF_TANGGAL_AKHIR_F']);

        if (!$newest) {
          $newest = $j;
        } else {
          if ($time_jbt_mulai <= $time_akhir_periode) {
            $time_newest_mulai = strtotime($newest['RF_TANGGAL_MULAI']);
            $time_newest_akhir = strtotime($newest['RF_TANGGAL_AKHIR']);

            if (!$time_jbt_akhir) {
              if ($time_newest_mulai < $time_jbt_mulai) {
                $newest = $j;
              }
            } else {
              if ($time_newest_akhir < $time_jbt_akhir) {
                $newest = $j;
              }
            }
          }
        }
      }
      $data = array($newest);
    }

    return $data;
  }

  function jabatan($kd_pegawai, $tanggal = null) {
    $tanggal = $tanggal ? $tanggal : date('d-m-Y');
    $struk = $this->simpeg_jabatan_struktural($kd_pegawai, $tanggal);
    $fung = $this->simpeg_jabatan_fungsional($kd_pegawai, $tanggal);
    
    if($kd_pegawai == '198603122011011009'){
      #return "hohohho";
      #exit();
    }

    $struk_resmi = false; //1
    $fung_resmi = false; //2
    
    /* 
     * edit by pri 26/05/2017
     * menghindari menampilkan jabatan bayangan dan pejabat pembuat komitmen sebagai default jabatan
     * 
    $struk_bayang = false; //3
    $fung_bayang = false; //4
    */
    // return false //5
    // jenis2 == 1 resmi ? resmi : tidak resmi
    // return array($struk, $fung);

    $c_struk = count($struk);
    for ($i = 0; $i < $c_struk; $i++) {
      //if ($struk[$i]['RR_STATUS'] > 0) {
      if (($struk[$i]['RR_JENIS2'] == 1) && (!stripos($struk[$i]['STR_NAMA'], 'komitmen'))) {
        $struk_resmi = $struk[$i];
        break;
      /*
      } else {
        $struk_bayang = $fung[$i];
        break;
        */
      }
      //}
    }

    $c_fung = count($fung);
    for ($i = 0; $i < $c_fung; $i++) {
      if (($fung[$i]['RF_JENIS2'] == 1) && (!stripos($struk[$i]['STR_NAMA'], 'komitmen'))) {
        $fung_resmi = $fung[$i];
        break;
      /*
      } else {
        $fung_bayang = $fung[$i];
        break;
        */
      }
    }

    if ($struk_resmi) {
      $struk_resmi['DETAIL'] = $this->simpeg_one_jabatan($struk_resmi['RR_STR_ID']);
      return $struk_resmi;
    } else if ($fung_resmi) {
      //$fung_resmi['DETAIL'] = $this->simpeg_one_jabatan($fung_resmi['RF_STR_ID']);
      $fung_resmi['DETAIL']['STR_ID'] = $fung_resmi['FUN_ID'];
      $fung_resmi['DETAIL']['STR_NAMA'] = $fung_resmi['FUN_NAMA'];
      $fung_resmi['DETAIL']['UNIT_NAMA'] = $fung_resmi['UNIT_NAMA'];
      return $fung_resmi;
    /*
    } else if ($struk_bayang) {
      $struk_bayang['DETAIL'] = $this->simpeg_one_jabatan($struk_bayang['RR_STR_ID']);
      return $struk_bayang;
    } else if ($fung_bayang) {
      //$fung_bayang['DETAIL'] = $this->simpeg_one_jabatan($fung_bayang['RF_STR_ID']);
      $fung_bayang['DETAIL'] = $fung_bayang;
      $fung_bayang['DETAIL']['STR_ID'] = $fung_bayang['FUN_ID'];
      $fung_bayang['DETAIL']['STR_NAMA'] = $fung_bayang['FUN_NAMA'];
      $fung_bayang['DETAIL']['UNIT_NAMA'] = $fung_bayang['UNIT_NAMA'];
      return $fung_bayang;
      */
    } else {
      return false;
    }
  }

  function simpeg_one_pegawai($kd_pegawai) {
    $data = $this->api->post($this->url->simpeg_search, array('api_kode' => 2001, 'api_subkode' => 1, 'api_search' => array($kd_pegawai)));
    return $data[0];
  }

  function pegawai($kd_pegawai, $jabatan = true, $tanggal = false) {
    $tanggal = $tanggal ? $tanggal : date('d-m-Y');

    $data['DETAIL'] = $this->simpeg_one_pegawai($kd_pegawai);

    $data['DETAIL']['NAMA_LENGKAP'] = $data['DETAIL']['NM_PGW'];
    if ($data['DETAIL']['GELAR_DEPAN']) {
      $data['DETAIL']['NAMA_LENGKAP'] = $data['DETAIL']['GELAR_DEPAN'] . ' ' . $data['DETAIL']['NAMA_LENGKAP'];
    }

    if ($data['DETAIL']['GELAR_BELAKANG']) {
      $data['DETAIL']['NAMA_LENGKAP'] = $data['DETAIL']['NAMA_LENGKAP'] . ', ' . $data['DETAIL']['GELAR_BELAKANG'];
    }

    if ($jabatan) {
      $data['JABATAN'] = $this->jabatan($kd_pegawai, $tanggal);
    } else {
      $data['JABATAN'] = array();
    }

    return $data;
  }

  function atasan($kd_pegawai, $tanggal = null) {
    $tanggal = $tanggal ? $tanggal : date('d-m-Y');
    $atasan = $this->dummy;
    $jabatan_pegawai = $this->jabatan($kd_pegawai, $tanggal);
    $is_struk = isset($jabatan_pegawai['RR_STR_ID']);
    $kd_jabatan_pegawai = $is_struk ? $jabatan_pegawai['RR_STR_ID'] : $jabatan_pegawai['FUN_ID'];

    if ($is_struk) {
      //$data = $this->simpeg_ranting_pegawai('atas', $kd_pegawai, $tanggal)[$kd_jabatan_pegawai];
      //$data = $this->simpeg_ranting_pegawai('atas', $kd_pegawai, $tanggal);//[$kd_jabatan_pegawai];
      $data = $this->simpeg_ranting_pegawai('atas', $kd_pegawai);//[$kd_jabatan_pegawai];

      for ($i = 0; $i < count($data); $i++) {
          $tgltime = strtotime(date($tanggal));
          $tm_mulai = strtotime($data[$i]['TOP_JSTR_TGL_MULAI']);
          $tm_akhir = strtotime($data[$i]['TOP_JSTR_TGL_AKHIR']);
          $jbt_berlaku = ($tm_mulai <= $tgltime && $tm_akhir >= $tgltime) || ($tm_mulai <= $tgltime && !$tm_akhir);
          if ($jbt_berlaku) {
            $kd_atasan = $data[$i]['TOP_KD_PGW'];
            $atasan = $this->pegawai($kd_atasan);
            break;
          }
      }
    } else {
      $data = $this->simpeg_atasan_fungsional($kd_pegawai, $tanggal);
      $kd_atasan = $data['TOP_KD_PGW'];

      $fung = $this->simpeg_jabatan_fungsional($kd_atasan, $tanggal);
      $jabatan_atasan = array();

      $c_fung = count($fung);
      for ($i = 0; $i < $c_fung; $i++) {
        if (isset($data['TFF_TOP_FUN_ID'])) {
          $jabatan_atasan = $fung[$i];
          $jabatan_atasan['DETAIL'] = array();
          $jabatan_atasan['DETAIL']['STR_ID'] = $fung[$i]['FUN_ID'];
          $jabatan_atasan['DETAIL']['STR_NAMA'] = $fung[$i]['FUN_NAMA'];
          $jabatan_atasan['DETAIL']['UNIT_NAMA'] = $fung[$i]['UNIT_NAMA'];
          break;
        } else if (isset($data['TFS_TOP_RR_STR_ID'])) {
          $jabatan_atasan = $fung[$i];
          $jabatan_atasan['DETAIL'] = array();
          $jabatan_atasan['DETAIL']['STR_ID'] = $fung[$i]['FUN_ID'];
          $jabatan_atasan['DETAIL']['STR_NAMA'] = $fung[$i]['FUN_NAMA'];
          $jabatan_atasan['DETAIL']['UNIT_NAMA'] = $fung[$i]['UNIT_NAMA'];
          break;
        }
      }

      $atasan = $this->pegawai($kd_atasan, false);
      $atasan['JABATAN'] = $jabatan_atasan;
    }

    return $atasan;
  }

  function bawahan($kd_pegawai) {
	/* 
	 * edited by pri
	 * menghindari menampilkan jabatan bayangan dan pejabat pembuat komitmen sebagai default jabatan
	 * 
    $kd_jabatan_pegawai = $this->simpeg_jabatan_struktural($kd_pegawai)[0]['RR_STR_ID'];
    */
    $kd_jabatan_pegawai = $this->jabatan($kd_pegawai)['DETAIL']['STR_ID'];
    //return $this->simpeg_jabatan_struktural($kd_pegawai);
    //$data = $this->simpeg_ranting_pegawai('bawah', $kd_pegawai)[$kd_jabatan_pegawai];
    $data = $this->simpeg_ranting_pegawai('bawah', $kd_pegawai); print_r($data);
    $bawahans = array();
    $nips = array();
    $cur_id = 0;

    $c_data = count($data);
    for ($i = 0; $i < $c_data; $i++) {
        $kd_bawahan = $data[$i]['LOW_KD_PGW'];
        // $bawahan_data = $this->pegawai($kd_bawahan);
        if (!in_array($data[$i]['LOW_KD_PGW'], $nips)) {
          array_push($nips, $data[$i]['LOW_KD_PGW']);
          $bawahans[$cur_id] = $data[$i];
          $bawahans[$cur_id]['DETAIL'] = $this->pegawai($data[$i]['LOW_KD_PGW'], false)['DETAIL'];
          $bawahans[$cur_id]['JABATAN'] = $this->jabatan($data[$i]['LOW_KD_PGW']);
          $cur_id++;
        }
    }

    return $bawahans;
  }

  function kolega($kd_pegawai){
	  $atasan  = $this->atasan($kd_pegawai);
	  $kd_atasan = $atasan['DETAIL']['KD_PGW'];
	  $bawahan = $this->bawahan($kd_atasan);
	  $sejawat = array();
	  if (!empty($bawahan)){
		for ($i= 0; $i<count($bawahan);$i++){
		  if ($kd_pegawai != $bawahan[$i]['DETAIL']['KD_PGW']){
			$sejawat[] = $bawahan[$i];  
		  }
		}
	  }
	  
	  return $sejawat;
  }
	
}