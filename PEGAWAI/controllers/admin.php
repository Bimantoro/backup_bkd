<?php

class Admin extends CI_Controller {

    function __construct() {
        parent::__construct();
		//require_once 'includes/PHPExcel/PHPExcel.php';
		//require_once 'includes/excel_reader/excel_reader.php';
        $this->view = $this->s00_lib_output;
        $this->api = $this->load->library('s00_lib_api');
        $this->url = $this->load->library('lib_url');
        $this->user = $this->load->library('lib_user', $this->session->all_userdata());
        $this->util = $this->load->library('lib_util');
        $this->lib_skp = $this->load->library('lib_skp');
        $this->lib_remun = $this->load->library('lib_remun');
        $this->simpeg = $this->load->module('remunerasi/simpeg');
        $this->penilaian = $this->load->module('remunerasi/penilaian');

        $this->session->set_userdata('app', 'remunerasi_admin_app');
		

        if (!$this->user->is_login() || !$this->user->is_skp_admin())
            redirect();
    }

    function index() { 
        redirect();
    }

    function form_masa_penilaian() {
		$periode			= conv_to_int($this->uri->segment(4));
		$bulan				= conv_to_int($this->uri->segment(5));
        if (empty($periode)) {
            $periode = date('Y');
        }        
		if (empty($bulan)) {
            $bulan = date('m');
        }
		$par['PERIODE']		= $periode;
		$par['BULAN']		= $bulan;
		$par['FORMAT_TGL']	= 'DD-MM-YYYY';
		
		$data['masa'] 		= $this->api->post($this->url->remun . '/masa_penilaian_pegawai', $par);
        $data['periode']	= $periode;
        $data['bulan'] 		= $bulan;
        $this->view->output_display('admin/form_masa_penilaian', $data);
    }
    
    function simpan_masa_penilaian(){
        $this->util->pre($this->api->post($this->url->remun . '/simpan_masa_penilaian', $_POST));
	}
	
	function template_kehadiran(){
        $data = array();
        $this->view->output_display('admin/rekap_data_template', $data);
	}
	
    function upload_template() {
        $config['upload_path'] = './uploads/remunerasi/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['overwrite'] = true;
        //$config['file_name'] = $_POST['jabatan'] . '.xlsx';
        $this->load->library('upload', $config);

        
        if ($this->upload->do_upload('file')) {
            require_once('includes/xls_report/PHPExcel.php');
            
            $inputFileName = $config['upload_path'] . $this->upload->data()['file_name'];
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
            $data = array();
			
            //$this->util->pre($sheetData);
			
            if (($sheetData[0][0] == 'TAHUN') && ($sheetData[1][0] == 'BULAN') && 
				($sheetData[2][0] == 'NIP') && ($sheetData[2][1] == 'NAMA') && ($sheetData[2][2] == 'JUMLAH HARI')) {
					
				if ((!empty($sheetData[0][1])) && (!empty($sheetData[1][1]))){
					$tahun			= $sheetData[0][1];
					$bulan			= $sheetData[1][1];
					$c_sheetData 	= count($sheetData);
					
					//data dimulai baris ke dua karena merging nip
					for ($i = 4; $i < $c_sheetData; $i++) {
						$sd = $sheetData[$i];
						if(!$sd[0]) break;
						$data[$i - 4] = array();
						$data[$i - 4]['NIP'] 		= $sd[0];
						$data[$i - 4]['NAMA'] 		= $sd[1];
						$data[$i - 4]['JML_HARI'] 	= str_replace(',', '.', $sd[2]);
						$data[$i - 4]['JML_HADIR'] 	= str_replace(',', '.', $sd[3]);
						$data[$i - 4]['JML_TELAT'] 	= str_replace(',', '.', $sd[4]);
						
						$post_data = array(
										// 'NIP'			=> $sd[0],
										'NIP'			=> str_replace(' ', '', $sd[0]),
										'JML_HARI'		=> str_replace(',', '.', $sd[2]),
										'JML_HADIR'		=> str_replace(',', '.', $sd[3]),
										'JML_TELAT'		=> str_replace(',', '.', $sd[4]),
										'TAHUN'			=> $tahun,
										'BULAN'			=> $bulan,
										'ID_PETUGAS'	=> $this->user->get_id()
									);
						$simpan = $this->api->post($this->url->remun . '/simpan_kehadiran', $post_data);
						$data[$i - 4]['SIMPAN'] 	= $simpan;
						
						//penambahan fungsi simpan penerimaan remun
						$this->penilaian->set_id_pegawai($sd[0]);
						$this->penilaian->set_periode($tahun);
						$this->penilaian->set_bulan($bulan);

						//$simpan_remun = FALSE;
						$simpan_remun = $this->penilaian->simpan_penerimaan_remun();
						$data[$i - 4]['SIMPAN_REMUN'] 	= $simpan_remun;
						
					}

					if($data) {
						echo json_encode($data);
					} else {
						echo 0;
					}
					
				} else {
					echo "Tahun atau bulan kosong";
				}
				
            } else {
                echo 0;
            }
        } else {
            print_r($this->upload->display_errors());
        }

        //$this->util->pre($_POST);
        //$this->util->pre($_FILES);
    }
    
    function tampil_kehadiran(){
		$periode			= conv_to_int($this->uri->segment(4));
		$bulan				= conv_to_int($this->uri->segment(5));
        if (empty($periode)) {
            $periode = date('Y');
        }        
		if (empty($bulan)) {
            $bulan = date('m');
        }
        $kehadiran = $this->api->post($this->url->remun . '/kehadiran_pegawai', array('TAHUN'=>$periode, 'BULAN'=>$bulan));
        /*
        for($i=0;$i<count($kehadiran);$i++){
			$pegawai= $this->simpeg->pegawai($kehadiran[$i]['ID_PEGAWAI']);
			$kehadiran[$i]['DETAIL']= $pegawai['DETAIL'];
		}*/
		
		$data['kehadiran']	= $kehadiran;
		$data['periode']	= $periode;
		$data['bulan']		= $bulan;
        $this->view->output_display('admin/daftar_kehadiran_pegawai', $data);
	}
    
    
    function daftar_grade(){
		$grade				= $this->api->post($this->url->remun . '/grade');
		$data['grade']		= $grade;
        $this->view->output_display('admin/daftar_grade', $data);
	}
	
	function import_grade(){
		$grade				= null;
		$data['grade']		= $grade;
        $this->view->output_display('admin/rekap_data_grade', $data);
	}
	
	function tambah_grade(){
		$grade				= null;
		$data['grade']		= $grade;
        $this->view->output_display('admin/form_tambah_grade', $data);
	}
	
	function simpan_grade(){
		$simpan= FALSE;
		$grade = $this->input->post('grade');
		$layer = $this->input->post('layer');
		
		if (!empty($grade) && !empty($layer)){
			$par = array('KD_GRADE' => $grade, 'KD_LAYER'=>$layer);
			
			$simpan = $this->api->post($this->url->remun . '/simpan_grade', $par);
		}
		echo $simpan;
	}
	
	function upload_template_grade(){
        $config['upload_path'] = './uploads/remunerasi/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['overwrite'] = true;
        //$config['file_name'] = $_POST['jabatan'] . '.xlsx';
        $this->load->library('upload', $config);

        
        if ($this->upload->do_upload('file')) {
            require_once('includes/xls_report/PHPExcel.php');
            
            $inputFileName = $config['upload_path'] . $this->upload->data()['file_name'];
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
            $data = array();
			
            //$this->util->pre($sheetData);
			
            if (($sheetData[0][0] == 'GRADE') && ($sheetData[0][1] == 'LAYER')) {
					
				$c_sheetData 	= count($sheetData);
				$grade			= '';
				//data dimulai baris ke dua karena merging nip
				for ($i = 1; $i < $c_sheetData; $i++) {
					$sd = $sheetData[$i];
					if(!$sd[0] && !$grade) break;
					$data[$i - 1] = array();
					$data[$i - 1]['GRADE'] 		= (string)(!empty($sd[0])) ? $sd[0] : $grade;
					$data[$i - 1]['LAYER'] 		= (string)$sd[1];
					
					$post_data = array(
									'KD_GRADE'		=> (string)(!empty($sd[0])) ? (string)$sd[0] : $grade,
									'KD_LAYER'		=> (string)$sd[1]
								);
					$simpan = $this->api->post($this->url->remun . '/simpan_grade', $post_data);
					
					$grade = (string)(!empty($sd[0])) ? $sd[0] : $grade;
					$data[$i - 1]['SIMPAN'] 	= $simpan;
				}

				if($data) {
					echo json_encode($data);
				} else {
					echo 0;
				}
					
            } else {
                echo 0;
            }
        } else {
            print_r($this->upload->display_errors());
        }
	}
	
	function grade_pegawai(){
		$data['grade_pegawai']	= $this->api->post($this->url->remun . '/grade_pegawai');
        $this->view->output_display('admin/daftar_grade_pegawai', $data);
	}
	
	function import_grade_pegawai(){
		$grade				= null;
		$data['grade']		= $grade;
        $this->view->output_display('admin/rekap_data_grade_pegawai', $data);
	}
	
	function tambah_grade_pegawai(){
		$data['grade']		= $this->api->post($this->url->remun . '/grade');
        $this->view->output_display('admin/form_grade_pegawai', $data);
        //$this->view->output_display('admin/form_masa_penilaian', $data);
	}
	
	function simpan_grade_pegawai(){
		$simpan	= FALSE;
		$hasil	= array();
		$nip   	= $this->input->post('nip');
		$grade 	= $this->input->post('grade');
		$tgl 	= $this->input->post('tanggal');
		//$tgl   = date('d/m/Y');
		
		if (!empty($nip) && !empty($grade) && !empty($tgl)){
			$pegawai= $this->simpeg->simpeg_one_pegawai($nip);
			//$hasil['pegawai'] = $pegawai;
			if (!empty($pegawai)){
				$par = array('ID_PEGAWAI' => $nip, 'ID_GRADE' => $grade, 'TGL_MULAI'=>$tgl);
				//$par = array('ID_PEGAWAI' => '198311292011011007', 'ID_GRADE' => '50', 'TGL_MULAI'=>$tgl);
				
				$simpan = $this->api->post($this->url->remun . '/simpan_grade_pegawai', $par);
				
				if($simpan){
					$hasil['success'] = notifications('Grade pegawai telah diperbarui.', 'success');
				}else{
					$hasil['errors'] = notifications('Gagal memperbarui grade pegawai', 'errors');
				}
			} else {
				$hasil['errors'] = notifications('Pegawai dengan NIP ' . $nip . ' tidak diketahui.', 'errors');
			}
		} else {
			if (empty($nip)){
				$hasil['errors'] = notifications('NIP pegawai tidak boleh kosong', 'errors');
			} else if (empty($grade)) {
				$hasil['errors'] = notifications('Grade pegawai tidak boleh kosong', 'errors');
			} else if (empty($tgl)) {
				$hasil['errors'] = notifications('Tanggal tidak boleh kosong', 'errors');
			}
		}
		echo json_encode($hasil);
	}
	
	function upload_template_grade_pegawai(){
        $config['upload_path'] = './uploads/remunerasi/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['overwrite'] = true;
        //$config['file_name'] = $_POST['jabatan'] . '.xlsx';
        $this->load->library('upload', $config);

        
        if ($this->upload->do_upload('file')) {
            require_once('includes/xls_report/PHPExcel.php');
            
            $inputFileName = $config['upload_path'] . $this->upload->data()['file_name'];
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
            $data = array();
			
            //$this->util->pre($sheetData);
			
            if (($sheetData[0][0] == 'NIP') && ($sheetData[0][1] == 'GRADE')) {
					
				$c_sheetData 	= count($sheetData);
				for ($i = 1; $i < $c_sheetData; $i++) {
					$sd = $sheetData[$i];
					if(!$sd[0]) break;
					$data[$i - 1] = array();
					$data[$i - 1]['NIP'] 				= (string)$sd[0];
					$data[$i - 1]['GRADE'] 				= (string)$sd[1];
					$data[$i - 1]['TANGGAL BERLAKU'] 	= (string)$sd[2];
					
					$par		= array('KD_LAYER' => (string)$sd[1]);
					$grade		= $this->api->post($this->url->remun . '/grade', $par);
					$id_grade 	= '';
					if (!empty($grade)){
						$id_grade = $grade[0]['ID_GRADE'];
					}
					$par 	= array('ID_PEGAWAI' => (string)$sd[0], 'ID_GRADE' => $id_grade, 'TGL_MULAI'=>(string)$sd[2]);
					$simpan = $this->api->post($this->url->remun . '/simpan_grade_pegawai', $par);
					
					$data[$i - 1]['ID_GRADE'] 	= $id_grade;
					$data[$i - 1]['SIMPAN'] 	= $simpan;
				}

				if($data) {
					echo json_encode($data);
				} else {
					echo 0;
				}
					
            } else {
                echo 0;
            }
        } else {
            print_r($this->upload->display_errors());
        }
	}
	
	function pegawai(){
		$kd_pegawai = $this->input->post('id');
		$pegawai['data'] 			= $this->simpeg->simpeg_one_pegawai($kd_pegawai);
		$pegawai['data']['pangkat']  = $this->simpeg->simpeg_pangkat_pegawai($kd_pegawai);
		echo json_encode($pegawai);
		//echo json_encode($kd_pegawai);
	}
	
	function nominal_grade(){
		$data['nominal_grade']	= $this->api->post($this->url->remun . '/nominal_grade');
        $this->view->output_display('admin/daftar_nominal_grade', $data);
	}
	
	function import_nominal_grade(){
		$grade				= null;
		$data['grade']		= $grade;
        $this->view->output_display('admin/rekap_data_nominal_grade', $data);
	}
	
	function tambah_nominal_grade(){
		$data['grade']		= $this->api->post($this->url->remun . '/grade');
        $this->view->output_display('admin/form_nominal_grade', $data);
	}
	
	function simpan_nominal_grade(){
		$simpan	= FALSE;
		$hasil	= array();
		$grade 	= $this->input->post('grade');
		$total 	= $this->input->post('total');
		$p1 	= $this->input->post('p1');
		$p2 	= $this->input->post('p2');
		$tgl 	= $this->input->post('tanggal');
		//$tgl   = date('d/m/Y');
		
		if (!empty($nip) && !empty($grade) && !empty($tgl)){
			$par = array('ID_GRADE' => $grade, 'TOTAL_REMUN' => $total, 'P1' => $p1, 'P2' => $p2, 'TGL_MULAI'=>$tgl);
			
			$simpan = $this->api->post($this->url->remun . '/simpan_nominal_grade', $par);
			
			if($simpan){
				$hasil['success'] = notifications('Nominal grade telah diperbarui.', 'success');
			}else{
				$hasil['errors'] = notifications('Gagal memperbarui nominal pegawai', 'errors');
			}
		} else {
			if (empty($grade)){
				$hasil['errors'] = notifications('Grade pegawai tidak boleh kosong', 'errors');
			} else if (empty($total)) {
				$hasil['errors'] = notifications('Total nominal remun tidak boleh kosong', 'errors');
			} else if (empty($tgl)) {
				$hasil['errors'] = notifications('Tanggal tidak boleh kosong', 'errors');
			}
		}
		echo json_encode($hasil);
	}
	
	function upload_template_nominal_grade(){
        $config['upload_path'] = './uploads/remunerasi/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['overwrite'] = true;
        //$config['file_name'] = $_POST['jabatan'] . '.xlsx';
        $this->load->library('upload', $config);

        
        if ($this->upload->do_upload('file')) {
            require_once('includes/xls_report/PHPExcel.php');
            
            $inputFileName = $config['upload_path'] . $this->upload->data()['file_name'];
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
            $data = array();
			
            //$this->util->pre($sheetData);
			
            if (($sheetData[0][0] == 'GRADE') && ($sheetData[0][1] == 'P1 (30%)') && ($sheetData[0][2] == 'P2 (70%)') && ($sheetData[0][3] == 'TOTAL')) {
					
				$c_sheetData 	= count($sheetData);
				for ($i = 1; $i < $c_sheetData; $i++) {
					$sd = $sheetData[$i];
					if(!$sd[0]) break;
					$data[$i - 1] = array();
					$data[$i - 1]['ID_GRADE'] 			= (string)$sd[0];
					$data[$i - 1]['P1'] 				= (string)$sd[1];
					$data[$i - 1]['P2'] 				= (string)$sd[2];
					$data[$i - 1]['TOTAL_REMUN'] 				= (string)$sd[3];
					$data[$i - 1]['TANGGAL BERLAKU'] 	= (string)$sd[4];
					
					$par		= array('KD_LAYER' => (string)$sd[0]);
					$grade		= $this->api->post($this->url->remun . '/grade', $par);
					$id_grade 	= '';
					if (!empty($grade)){
						$id_grade = $grade[0]['ID_GRADE'];
					}
					$par 	= array('ID_GRADE' => $id_grade, 'P1' => (string)$sd[1],'P2' => (string)$sd[2],'TOTAL_REMUN' => (string)$sd[3], 'TGL_MULAI'=>(string)$sd[4]);
					$simpan = $this->api->post($this->url->remun . '/simpan_nominal_grade', $par);
					
					$data[$i - 1]['ID_GRADE'] 	= $id_grade;
					$data[$i - 1]['SIMPAN'] 	= $simpan;
				}

				if($data) {
					echo json_encode($data);
				} else {
					echo 0;
				}
					
            } else {
                echo 0;
            }
        } else {
            print_r($this->upload->display_errors());
        }
	}

	function nilai_skp_remun(){
		$periode			= conv_to_int($this->uri->segment(4));
        if (empty($periode)) {
            $periode = date('Y');
        }        
        $par				= array('TAHUN'=>$periode);
		$data['periode']	= $periode;
		$data['skp_remun']	= $this->api->post($this->url->remun . '/nilai_skp_remun', $par);
        $this->view->output_display('admin/daftar_skp_remun', $data);
	}
	
	function tambah_nilai_skp(){
		
		$periode		= conv_to_int($this->uri->segment(4));
		$unit			= $this->uri->segment(5);
        if (empty($periode)) {
            $periode = date('Y');
        }        
        $pegawai_unit	=  (empty($unit)) ? null : $this->simpeg->simpeg_pegawai_unit($unit);
        
		$data['kd_unit']	= $unit;
		$data['periode']	= $periode;
		$data['unit']		= $this->simpeg->simpeg_all_unit();
		$data['pegawai']	= $pegawai_unit;
		
        $this->view->output_display('admin/form_nilai_skp_remun', $data);
		
	}
	
	function pegawai_unit($unit){
		$pegawai = $this->simpeg->simpeg_pegawai_unit($unit);
		print_r($pegawai);
	}
	
	function simpan_penilaian_skp(){
		$tahun 	= $this->input->post('y');
		$nip	= $this->input->post('id');
		$nilai 	= $this->input->post('nil');
			
		switch ($nilai) {
			case 1: $potongan = 75; break;
			case 2: $potongan = 50; break;
			case 3: $potongan = 25; break;
			default: $potongan = 0; break;
		}
		
		$par 	= array('ID_PEGAWAI' => $nip, 'TAHUN' => intval($tahun),'NILAI' => intval($nilai),'PERSEN_POTONGAN' => intval($potongan), 'ID_PETUGAS' => $this->user->get_id());
		$simpan = $this->api->post($this->url->remun . '/simpan_nilai_skp', $par);
		echo json_encode($simpan);
	}
	
	function hukuman_disiplin(){
		$periode		= conv_to_int($this->uri->segment(4));
        if (empty($periode)) {
            $periode = date('Y');
        }        
        
		$data['periode']	= $periode;
		$data['hukuman_disiplin']	= $this->api->post($this->url->remun . '/hukuman_disiplin');
		
        $this->view->output_display('admin/daftar_hukuman_disiplin', $data);
	}
	
	function tambah_hukuman_disiplin(){
        $this->view->output_display('admin/form_hukuman_disiplin', $data);
	}
	
	function simpan_hukuman_disiplin(){
		$nip 	= $this->input->post('nip');
		$kd_huk	= $this->input->post('hukuman');
		$tgl 	= $this->input->post('tanggal');
		$lama_huk = 0;
		$pot_remun= 0;
		
		if (!empty($nip) && !empty($kd_huk) && !empty($tgl)){
			switch($kd_huk){
				case "1.1": $lama_huk=1; $pot_remun=20;break;
				case "1.2": $lama_huk=2; $pot_remun=20;break;
				case "1.3": $lama_huk=3; $pot_remun=20;break;
				case "2.1": $lama_huk=4; $pot_remun=30;break;
				case "2.2": $lama_huk=5; $pot_remun=30;break;
				case "2.3": $lama_huk=6; $pot_remun=30;break;
				case "3.1": $lama_huk=12; $pot_remun=40;break;
				case "3.2": $lama_huk=12; $pot_remun=50;break;
				case "3.3": $lama_huk=12; $pot_remun=60;break;
			}
	
			$par	= array(
							'ID_PEGAWAI' => $nip,
							'KD_HUKUMAN' => $kd_huk,
							'LAMA_HUKUMAN' => $lama_huk,
							'POTONGAN_REMUN' => $pot_remun,
							'TGL_HUKUMAN' => $tgl,
							'ID_PETUGAS' => $this->user->get_id()
						);
			
			$simpan = $this->api->post($this->url->remun . '/simpan_hukuman_disiplin', $par);
			if($simpan){
				$hasil['success'] = notifications('Hukuman disiplin telah disimpan.', 'success');
			}else{
				$hasil['errors'] = notifications('Gagal menyimpan hukuman disiplin', 'errors');
			}
		} else {
			if (empty($nip)){
				$hasil['errors'] = notifications('NIP pegawai tidak boleh kosong', 'errors');
			} else if (empty($kd_huk)) {
				$hasil['errors'] = notifications('Hukuman disiplin tidak boleh kosong', 'errors');
			} else if (empty($tgl)) {
				$hasil['errors'] = notifications('Tanggal berlaku hukuman tidak boleh kosong', 'errors');
			}
		}
		echo json_encode($hasil);
	}
	
	function import_npwp(){
		$grade				= null;
		$data['grade']		= $grade;
        $this->view->output_display('admin/rekap_npwp', $data);
	}

	function upload_template_npwp(){
        $config['upload_path'] = './uploads/remunerasi/';
        $config['allowed_types'] = 'xls|xlsx';
        $config['overwrite'] = true;
        //$config['file_name'] = $_POST['jabatan'] . '.xlsx';
        $this->load->library('upload', $config);

        
        if ($this->upload->do_upload('file')) {
            require_once('includes/xls_report/PHPExcel.php');
            
            $inputFileName = $config['upload_path'] . $this->upload->data()['file_name'];
            $objPHPExcel = PHPExcel_IOFactory::load($inputFileName);
            $sheetData = $objPHPExcel->getActiveSheet()->toArray(null, true, true, false);
            $data = array();
			
            //$this->util->pre($sheetData);
			
            if (($sheetData[0][0] == 'NIP') && ($sheetData[0][1] == 'NPWP') && ($sheetData[0][2] == 'NO.REK')) {
					
				$c_sheetData 	= count($sheetData);
				for ($i = 1; $i < $c_sheetData; $i++) {
					$sd = $sheetData[$i];
					if(!$sd[0]) break;
					$data[$i - 1] = array();
					$data[$i - 1]['ID_PEGAWAI'] 		= (string)$sd[0];
					$data[$i - 1]['NPWP'] 				= (string)$sd[1];
					$data[$i - 1]['NOREK'] 				= (string)$sd[2];

					$simpan = $this->simpeg->simpan_npwp((string)$sd[0], (string)$sd[1], (string)$sd[2]);
					
					$data[$i - 1]['SIMPAN'] 	= $simpan;
				}

				if($data) {
					echo json_encode($data);
				} else {
					echo 0;
				}
					
            } else {
                echo 0;
            }
        } else {
            print_r($this->upload->display_errors());
        }
	}
	
	function rekap_remun(){
        $this->view->output_display('admin/rekap_remun');
	}
	
	function printPDF($view, $data, $output, $settings=null){
		$laporan 	= $this->load->module('remunerasi/laporan');
		$html 		= $this->load->view($view, $data, true);
		
		$laporan->pdf->loadHtml($html);
		if(!empty($settings)){
			$paperOrientation = 'portrait';
			$paperSize		  = 'A4';
			if (array_key_exists('paper_size', $settings)){
				$paperSize	= $settings['paper_size'];
			}
			if (array_key_exists('orientation', $settings)){
				$paperOrientation	= $settings['orientation'];
			}
			$laporan->pdf->setPaper($paperSize, $paperOrientation);
		}
		$laporan->pdf->render();
		$laporan->setSubject(ucwords(str_replace('_', ' ', $str=$output)));
		$laporan->pdf->stream($output,array('Attachment'=>0));
		
		//$laporan->test();
	}
	
	function pdf_rekap_remun_total($periode, $bulan){
		//$laporan 	= $this->load->module('remunerasi/laporan');
		$par			= array('PERIODE'=>$periode, 'BULAN'=>$bulan);
		$data['tanggal']= '01-'.$bulan.'-'.$periode;
		$data['rekap']	= $this->api->post($this->url->remun . '/rekap_remun', $par);
		//print_r($data);
        $this->printPDF('admin/pdf_rekap_remun_total', $data, 'rekap_remun_' . $bulan . '_' . $periode);
        //$laporan->exportPDF('admin/pdf_rekap_remun_total', $data, 'rekap_remun_' . $bulan . '_' . $periode);
	}
	
	function pdf_rekap_remun_detail($periode, $bulan){
		//$laporan 	= $this->load->module('remunerasi/laporan');
		$par			= array('PERIODE'=>$periode, 'BULAN'=>$bulan);
		$data['tanggal']= '01-'.$bulan.'-'.$periode;
		$data['rekap']	= $this->api->post($this->url->remun . '/rekap_remun', $par);
		//print_r($data);
		$kertas	= array('paper_size'=>'A4', 'orientation'=>'landscape');
        $this->printPDF('admin/pdf_rekap_remun_detail', $data, 'rekap_remun_' . $bulan . '_' . $periode, $kertas);
        //$laporan->exportPDF('admin/pdf_rekap_remun_detail', $data, 'rekap_remun_' . $bulan . '_' . $periode, $kertas);
	}
	
}
?>
