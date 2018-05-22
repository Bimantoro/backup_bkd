<?php
class test extends CI_Controller {
    function __construct() {
        parent::__construct();
        $this->view = $this->s00_lib_output;
        $this->api = $this->load->library('s00_lib_api');
        $this->url = $this->load->library('lib_url');
        $this->user = $this->load->library('lib_user', $this->session->all_userdata());
	}
	
	function get_api($url, $output='json', $postorget='GET', $parameter){	
		$api_url = 'http://service2.uin-suka.ac.id/servtnde/tnde_public/'.$url.'/'.$output;
		// $api_url = 'http://service.uin-suka.ac.id/servsiasuper/index.php/tnde_public/'.$url.'/'.$output;
		$hasil = null;
		if ($postorget == 'POST'){
			$hasil = $this->curl->simple_post($api_url, $parameter);
		} else {
			$hasil = $this->curl->simple_get($api_url);
		}
		return json_decode($hasil, TRUE);
	}
	
	function api_simpeg($url, $output='json', $postorget='GET', $parameter){	
		$api_url = 'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/'.$url.'/'.$output;
		// $api_url = 'http://service.uin-suka.ac.id/servsiasuper/simpeg_public/'.$url.'/'.$output;
		$hasil = null;
		
		$this->curl->option('HTTPHEADER', array('HeaderName: '.$this->encrypt001('dedy5u__4t')));
						
		if ($postorget == 'POST'){
			$hasil = $this->curl->simple_post($api_url, $parameter);
		} else {
			$hasil = $this->curl->simple_get($api_url);
		}
		return json_decode($hasil, TRUE);
	}	
	
	function kepada($id){
		// $this->api = $this->load->library('s00_lib_api');
		// $this->url = $this->load->library('lib_url');
		$kepada = $this->get_api('tnde_surat_keluar/get_penerima_sk', 'json', 'POST', array('api_kode' => 90009, 'api_subkode' => 1, 'api_search' => array($id, 'PS')));
		echo json_encode($kepada);
		echo '<br />';
		echo '<br />';
		echo '<br />';
		$psd = $this->get_api('tnde_general/get_data', 'json', 'POST', array('api_kode' => 1001, 'api_subkode' => 2, 'api_search' => array('MD_PSD', 'ID_PSD', '301')));
		echo '<br />';
		echo json_encode($psd);
		echo '<br />';
		$parameter = array('api_kode' => 1121, 'api_subkode' => 9, 'api_search' => array('17-05-2017', $psd['KD_JABATAN'], 1));
		//$data = $this->api_simpeg('simpeg_mix/data_search', 'json', 'POST', $parameter);
		$data = $this->api->post($this->url->simpeg_search, $parameter);;
		echo '<br />';
		echo json_encode($data);
	}
	
	function upload(){
		$this->view->output_display('upload', $data=null);
	}
	
	function proses_upload(){
		log_message('error', 'starting');
        $config['upload_path'] = './uploads/data/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['overwrite'] = true;
		
        $this->load->library('upload', $config);

		set_time_limit(1200);
        
        if ($this->upload->do_upload('file')) {
            require_once('includes/xls_report/PHPExcel.php');
            
            $inputFileName 	= $config['upload_path'] . $this->upload->data()['file_name'];
            $objPHPExcel 	= PHPExcel_IOFactory::load($inputFileName);
            // $sheetData 		= $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
            $sheet 			= $objPHPExcel->getActiveSheet();
			$datasheet		= $sheet->toArray(null, true, true, false);
			// $datasheet		= $sheet->toArray(null, true, true, true);
			
			// log_message('error', json_encode($datasheet));
			
			// if (($datasheet[0][0] == 'NOMENKLATUR JABATAN') && ($datasheet[0][1] == 'URAIAN PEKERJAAN') && ($datasheet[0][2] == 'OBJEK KERJA')) {
			if (($datasheet[0][0] == 'NOMENKLATUR JABATAN') && ($datasheet[0][1] == 'URAIAN PEKERJAAN') && ($datasheet[0][2] == 'OUTPUT') && ($datasheet[0][3] == 'BEBAS PILIH') && ($datasheet[0][4] == 'OBJEK KERJA')) {
					
				$c_sheetData 	= count($datasheet);
				
				$tabel_jabatan	= "NOMENKLATUR_JABATAN";
				$tabel_uraian 	= "URAIAN_KERJA_JABATAN";
				$tabel_objek	= "OBJEK_KERJA_JABATAN";
				
				$kdjab = '';
				$nmjab = '';
				$nmura = '';
				for ($i = 1; $i < $c_sheetData; $i++) {
					$sd = $datasheet[$i];
					if (!empty($sd)){
						// log_message('error', 'saving data ' . $i);
						// log_message('error', 'saving data ' . $i . ': ' . json_encode($sd));
						
						$search   = array("\r\n", "\n", "\r");
						
						$nm_jab = $sd[0];
						$nm_ura = str_replace($search, '', $sd[1]);
						$output = $sd[2];
						$bbs_pil= $sd[3];
						$nm_obj = str_replace($search, '', $sd[4]);
						
						// if ($i == 115) log_message('error', $nmjab . '-' . $nm_jab . ': ' . $nm_ura . ' | ' . $nm_obj);
						// log_message('error', $nmjab . '-' . $nm_jab . ': ' . $nm_ura . ' | ' . $nm_obj);
						
						if ($nmjab != $nm_jab) {
							if (!empty($nm_jab)){
							
								$nmjab 	= $nm_jab;
								$kdjab 	= null;
								$str_id = '';
								
								$parameter 	= array('api_kode' => 1101, 'api_subkode' => 2, 'api_search' => array($nm_jab));
								$hasil		= $this->api->get_api($this->url->simpeg_search, 'POST', $parameter);
								$jabatan	= json_decode($hasil);
									// log_message('error', json_encode($jabatan));
								if (!empty($jabatan)){
									log_message('error', 'jumlah : ' . count($jabatan) . ' tipe: ' . gettype($jabatan[0]) . ' ' . json_encode($jabatan[0]));
									// $str_id = $jabatan[0]->STR_ID;
									foreach($jabatan as $key=>$jab){
										$str_id .= $jab->STR_ID . ',';
									}
								}
								// if (!empty()
								
								$nm_jab = ucfirst(rtrim(ltrim($nm_jab)));
								
								log_message('error', 'str id : '. $str_id . ' ' . $nm_jab);

								
								$jabatancek['TABLE']						= $tabel_jabatan;
								$jabatancek['WHERE']['NM_JABATAN']			= $nm_jab;

								$jabatansimpan['TABLE']						= $tabel_jabatan;
								$jabatansimpan['DATA']['NM_JABATAN']		= $nm_jab;
								$jabatansimpan['DATA']['KD_JAB_SIMPEG']		= "$str_id";

								$datajabatan = $this->api->post($this->url->skp.'/get_data', $jabatancek);
								if (!empty($datajabatan)){
									$kdjab = $datajabatan[0]['ID'];
									
									if (empty($str_id)){
										$jabatansimpan['DATA']['KD_JAB_SIMPEG']	= $datajabatan[0]['KD_JAB_SIMPEG'];
									} else {
										if ($str_id != $datajabatan[0]['KD_JAB_SIMPEG']){
											$jabatansimpan['DATA']['KD_JAB_SIMPEG']		= "$str_id";
										}
									}
									log_message('error', 'update jabatan ' . $kdjab . ' : ' . $jabatansimpan['DATA']['KD_JAB_SIMPEG']);
									$jabatansimpan['WHERE']['ID']	= $kdjab;
									$update_jabatan = $this->api->post($this->url->skp.'/update_data', $jabatansimpan);
								} else {
									$jabatansimpan['OUTPUT'] = TRUE;
									log_message('error', 'simpan jabatan : ' . json_encode($jabatansimpan));
									$kdjab	= $this->api->post($this->url->skp.'/insert_data', $jabatansimpan);
								}
							}
						}

						log_message('error', 'kd jabatan : ' . $kdjab);
						
						
						if (empty($kdjab)) continue;
						
						$nm_ura = ucfirst(rtrim(ltrim($nm_ura)));
						
						$uraian['TABLE']						= $tabel_uraian;
						$uraian['DATA']['ID_JABATAN']			= $kdjab;
						$uraian['DATA']['NM_URAIAN']			= $nm_ura;
						$uraian['DATA']['PILIHAN']				= (strtolower($bbs_pil)=='ya') ? 1 : 0;
						$uraian['DATA']['OUTPUT']				= ucfirst(rtrim(ltrim($output)));
										
						$uraiancek['TABLE']						= $tabel_uraian;
						$uraiancek['WHERE']['ID_JABATAN']		= $kdjab;
						$uraiancek['WHERE']['NM_URAIAN']		= $nm_ura;
										
						$objek['TABLE']							= $tabel_objek;
						$objek['DATA']['ID_JABATAN']			= $kdjab;
						$objek['DATA']['NM_OBJEK']				= rtrim(ltrim($nm_obj));
						
						$objekcek['TABLE']							= $tabel_objek;
						$objekcek['WHERE']['ID_JABATAN']			= $kdjab;
						$objekcek['WHERE']['NM_OBJEK']				= rtrim(ltrim($nm_obj));
						
						$uraianada = $this->api->post($this->url->skp.'/get_data', $uraiancek);
						$objekada  = $this->api->post($this->url->skp.'/get_data', $objekcek);
						
						// log_message('error', $i . ':' . json_encode($uraian));
						// log_message('error', $i . ':' . json_encode($objek));
						
						if (!empty($nm_ura)) {
							if (empty($uraianada)) {
								log_message('error', 'tambah uraian');
								$insert_ura	= $this->api->post($this->url->skp.'/insert_data', $uraian);
							} else {
								$uraian['WHERE']['ID']				= $uraianada[0]['ID'];
								log_message('error', 'update uraian');
								$insert_ura	= $this->api->post($this->url->skp.'/update_data', $uraian);
							}
						}

						if (!empty($nm_obj) && (empty($objekada))) $insert_obj	= $this->api->post($this->url->skp.'/insert_data', $objek);
						
						//$nmjab = ($nmjab != $nm_jab) ? $nm_jab : $nmjab;
						
						
					}
				}
			}
			echo true;
        } else {
            echo $this->upload->display_errors();
        }
	}
	
}
