<?php 

/**
* created by DNG A BMTR [31 Jan 2018]
*/
class Setting extends CI_Controller
{
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
		$this->api 		= $this->s00_lib_api;
		$this->output99	= $this->s00_lib_output;
		$this->load->library('bkd_lib_sks_rule', '', 'sksrule');
		$this->load->model('mdl_bkd');
		$this->session->set_userdata('app','app_bkd');
		if($this->session->userdata('id_user') == '') redirect(base_url());
	}

	// function index(){
		
	// }

	function testing(){
		$data = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_subgroup_nilai_sks',
			'POST',
			array('api_search' => array(42))
		);

		echo '<pre>';
		print_r($data);
		echo '</pre>';

		echo 'hai';
	}

	function pengaturan_sks(){
		$group = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_group_nilai_sks',
			'POST',
			array('api_search' => array())
		);

		$data['group'] = $group;
		$this->output99->output_display('admbkd/data_pengaturan_sks', $data);
	}

	function get_sub(){
		$id_g = $this->input->post('group');

		$subgroup = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_subgroup_nilai_sks',
			'POST',
			array('api_search' => array($id_g))
		);

		echo '<option> -- Pilih -- </option>';
 		foreach ($subgroup as $sg){
			echo '<option value='.$sg['ID_SG'].'>'.$sg['NM_SUBGROUP'].'</option>'; 
		}
	}

	function get_pengaturan_sks(){
		$id_s = $this->input->post('subgroup');

		$rule = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_nilai_rule_sks',
			'POST',
			array('api_search' => array($id_s))
		);

		if($rule){
			$no = 1;
			foreach ($rule as $r) {
				if($r['STATUS']==1){
					$icon = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
				}else{
					$icon = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";
				}

				$button = "<button type='button' class='btn btn-mini' value=".$r['ID_RULE']." onclick='get_edit(".$r['ID_RULE'].");'><i class='icon-edit'></i> Edit</button>";

				$nilai = $this->sksrule->_konversi_float($r['NILAI']);
				echo "<tr>
						<td align='center'>".$no.".</td>
						<td align='center'>".$r['KD_MODE']."</td>
						<td align='center'>".$r['BATAS']."</td>
						<td align='center'>".$r['NM_OPERATOR']."</td>
						<td align='center'>".$nilai."</td>
						<td align='center'>".$r['TGL']."</td>
						<td align='center'>".$icon."</td>
						<td align='center'>".$button."</td>
					  </tr>";
				$no++;
			}
		}else{
			echo "<tr><td colspan='7' align='center'>Data Tidak Ditemukan !</td></tr>";
		}
	}

	function get_edit(){
		$id_rule 		= $this->input->post('id_rule');
	
		$data['MODE']	= $this->api->get_api_json(
							URL_API_BKD.'/bkd_beban_kerja/get_mode_pengaturan_sks',
							'POST',
							array('api_search' => array())
						 );

		$data['OPERATOR'] = $this->api->get_api_json(
							URL_API_BKD.'/bkd_beban_kerja/get_operator_pengaturan_sks',
							'POST',
							array('api_search' => array())
						 );

		$data['RULE']   = $this->api->get_api_json(
							URL_API_BKD.'/bkd_beban_kerja/get_current_rule_sks',
							'POST',
							array('api_search' => array($id_rule))
						 );

		$data['RULE']['NILAI']   = $this->sksrule->_konversi_float($data['RULE']['NILAI']); 

		$data['STATUS'] = array('1' => 'AKTIF', '0' => 'TIDAK AKTIF');

		echo json_encode($data);
	}

	function fill_form(){
		$data['MODE']	= $this->api->get_api_json(
							URL_API_BKD.'/bkd_beban_kerja/get_mode_pengaturan_sks',
							'POST',
							array('api_search' => array())
						 );

		$data['OPERATOR'] = $this->api->get_api_json(
							URL_API_BKD.'/bkd_beban_kerja/get_operator_pengaturan_sks',
							'POST',
							array('api_search' => array())
						 );

		$data['STATUS'] = array('1' => 'AKTIF', '0' => 'TIDAK AKTIF');

		echo json_encode($data);
	}

	function tambah_rule(){
		$id_s	  = $this->input->post('subgroup');
		$kd_mode  = $this->input->post('mode');
		$operator = $this->input->post('operator');
		$batas 	  = $this->input->post('batas');
		$nilai 	  = $this->input->post('nilai');
		$status   = $this->input->post('status');

		$input    = $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/tambah_pengaturan_sks',
						'POST',
						array(
							'api_search' => array($id_s, $kd_mode, $batas, $operator, $nilai, $status)
						)
					); 
		if($input){
			$simpan = 1;
		}else{
			$simpan = 0;
		}

		echo $simpan;
	}

	function update_rule(){
		$id_r	  = $this->input->post('id_rule');
		$kd_mode  = $this->input->post('mode');
		$operator = $this->input->post('operator');
		$batas 	  = $this->input->post('batas');
		$nilai 	  = $this->input->post('nilai');
		$status   = $this->input->post('status');

		$input    = $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/update_pengaturan_sks',
						'POST',
						array(
							'api_search' => array($id_r, $kd_mode, $batas, $operator, $nilai, $status)
						)
					); 
		if($input){
			$update = 1;
		}else{
			$update = 0;
		}

		echo $update;
	}

	function get_keterangan(){
		$mode = $this->api->get_api_json(
					URL_API_BKD.'/bkd_beban_kerja/get_mode_pengaturan_sks',
					'POST',
					array('api_search' => array())
				);

		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Mode :</b></td></tr>";
		
		foreach ($mode as $m) {
			echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
			echo "
			<tr>
				<td style='padding-left: 20px;'>".$m['KD_MODE']."</td>
				<td> &nbsp; : <i>Return Value </i><b>".ucwords(strtolower($m['NM_MODE']))."</b></td>
			</tr>
			";
		}

		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Status :</b></td></tr>";
		
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
		<tr>
			<td style='padding-left: 20px;'><span class='badge badge-success'> <i class='icon-white icon-ok'></i></span></td>
			<td> &nbsp; : Aktif</b></td>
		</tr>
		";
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
		<tr>
			<td style='padding-left: 20px;'><span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span></td>
			<td> &nbsp; : Tidak Aktif</b></td>
		</tr>
		";

	}

	function get_keterangan_syarat(){
		$mode = $this->api->get_api_json(
					URL_API_BKD.'/bkd_beban_kerja/get_mode_pengaturan_sks',
					'POST',
					array('api_search' => array())
				);

		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Status :</b></td></tr>";
		
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
		<tr>
			<td style='padding-left: 20px;'><span class='badge badge-success'> <i class='icon-white icon-ok'></i></span></td>
			<td> &nbsp; : Aktif</b></td>
		</tr>
		";
		echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
		echo "
		<tr>
			<td style='padding-left: 20px;'><span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span></td>
			<td> &nbsp; : Tidak Aktif</b></td>
		</tr>
		";

	}

	function pengaturan_syarat(){
		// echo "halaman pengaturan syarat beban kerja";
		$dosen = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_group_jenis_dosen',
			'POST',
			array('api_search' => array())
		);

		$bidang = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_group_bidang',
			'POST',
			array('api_search' => array())
		);

		$data['dosen'] = $dosen;
		$data['bidang'] = $bidang;

		$this->output99->output_display('admbkd/data_pengaturan_syarat', $data);
	}

	function get_pengaturan_syarat(){
		$jenis = $this->input->post('jenis');
		$bidang = $this->input->post('bidang');
		$kat = $this->input->post('kategori');

		$syarat = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_all_syarat_kesimpulan',
			'POST',
			array('api_search' => array($jenis, $bidang, $kat))
		);

		$dosen = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_group_jenis_dosen',
			'POST',
			array('api_search' => array())
		);

		$bdg = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_group_bidang',
			'POST',
			array('api_search' => array())
		);

		$kategori = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_kategori',
						'POST',
						array('api_search' => array($bidang))
					);

		$i = 1;
		if($syarat){
			foreach ($syarat as $s) {

				$button = "<button type='button' class='btn btn-mini' value=".$s['ID_SYARAT']." onclick='get_edit(".$s['ID_SYARAT'].");'><i class='icon-edit'></i> Edit</button>";

				echo "<tr>";
					echo "<td align='center'>".$i."</td>"; $i++;
					foreach ($dosen as $d) {
						if($s['JENIS'] == $d['KD_JENIS_DOSEN']){
							echo "<td align='center'>".strtoupper(strtolower($d['KD_JENIS_DOSEN']))."</td>";
						}
					}

					foreach ($bdg as $b) {
						if($s['BIDANG'] == $b['KD_BIDANG']){
							echo "<td align='center'>".ucwords(strtolower($b['NM_BIDANG']))."</td>";
						}
					}

					foreach ($kategori as $k) {
						if($s['KD_KAT'] == $k['KD_KAT']){
							echo "<td align='center'>".ucwords(strtolower($k['NM_KAT']))."</td>";
						}
					}

					//echo "<td align='center'>".$s['KD_KAT']."</td>";

					echo "<td align='center'>".$s['NILAI']."</td>";
					echo "<td align='center'>".$s['TGL']."</td>";

					if($s['STATUS'] == 1){
						echo "<td align='center'><span class='badge badge-success'> <i class='icon-white icon-ok'></i></span></td>";
					}else{
						echo "<td align='center'><span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span></td>";
					}

					echo "<td align='center'>".$button."</td>";

				echo "</tr>";
			}
		}else{
			echo "<tr><td colspan='7' align='center'>Data Tidak Ditemukan !</td></tr>";
		}
		//echo json_encode($syarat);
	}

	function get_jenis_dosen(){
		$dosen = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_group_jenis_dosen',
			'POST',
			array('api_search' => array())
		);

		echo json_encode($dosen);
	}

	function tambah_syarat(){
		$jenis 		= $this->input->post('jenis');
		$bidang 		= $this->input->post('bidang');
		$nilai 		= $this->input->post('nilai');
		$status 	= $this->input->post('status');
		$kategori 	= $this->input->post('kategori');

		$input    	= $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/tambah_pengaturan_syarat',
						'POST',
						array(
							'api_search' => array($jenis, $bidang, $nilai, $status, $kategori)
						)
					);
		$r=0;
		if($input){
			$r = 1;
		}
		echo json_encode($r);
	}

	function update_syarat(){
		$id_syarat 	= $this->input->post('id');
		$jenis 		= $this->input->post('jenis');
		$bidang 	= $this->input->post('bidang');
		$nilai 		= $this->input->post('nilai');
		$status 	= $this->input->post('status');

		$update 	= $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/update_pengaturan_syarat',
						'POST',
						array(
							'api_search' => array($id_syarat, $jenis, $bidang, $status)
						)
					);
		$r=0;
		if($update){
			$r = 1;
		}
		echo json_encode($r);

	}

	function get_edit_syarat(){
		$id 	= $this->input->post('id_syarat');

		$edit 	= $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/edit_pengaturan_syarat',
						'POST',
						array(
							'api_search' => array($id)
						)
					);

		echo json_encode($edit);
	}

	function pengaturan_batas(){
		$this->output99->output_display('admbkd/data_pengaturan_batas');
	}

	function get_pengaturan_batas(){
		$batas = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_all_syarat_total',
			'POST',
			array('api_search' => array())
		);

		$dosen = $this->api->get_api_json(
			URL_API_BKD.'/bkd_beban_kerja/get_group_jenis_dosen',
			'POST',
			array('api_search' => array())
		);



		if($batas){
			$no = 1;
			foreach ($batas as $b) {
				if($b['STATUS']==1){
					$icon = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
				}else{
					$icon = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";
				}

				foreach ($dosen as $d) {
					if($d['KD_JENIS_DOSEN'] == $b['JENIS']){
						$jenis = $d['NM_JENIS_DOSEN'];
					}
				}

				$button = "<button type='button' class='btn btn-mini' value=".$b['ID_SYARAT']." onclick='get_edit(".$b['ID_SYARAT'].");'><i class='icon-edit'></i> Edit</button>";

				//$nilai = $this->sksrule->_konversi_float($r['NILAI']);
				echo "<tr>
						<td align='center'>".$no.".</td>
						<td>".$jenis."</td>
						<td align='center'>".$b['MIN']."</td>
						<td align='center'>".$b['MAX']."</td>
						<td align='center'>".$b['TGL']."</td>
						<td align='center'>".$icon."</td>
						<td align='center'>".$button."</td>
					  </tr>";
				$no++;
			}
		}else{
			echo "<tr><td colspan='7' align='center'>Data Tidak Ditemukan !</td></tr>";
		}

		//echo json_encode($batas);

	}

	function get_edit_batas(){
		$id = $this->input->post('id_syarat');

		$edit 	= $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/edit_pengaturan_batas',
						'POST',
						array(
							'api_search' => array($id)
						)
					);

		echo json_encode($edit);


	}

	function tambah_batas(){
		$jenis 		= $this->input->post('jenis');
		$min 		= $this->input->post('min');
		$max 		= $this->input->post('max');
		$status 	= $this->input->post('status');

		$input    	= $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/tambah_pengaturan_batas',
						'POST',
						array(
							'api_search' => array($jenis, $max, $min, $status)
						)
					);
		$r=0;
		if($input){
			$r = 1;
		}
		echo json_encode($r);
	}

	function update_batas(){
		$id_syarat 	= $this->input->post('id');
		$jenis 		= $this->input->post('jenis');
		$status 	= $this->input->post('status');

		$update 	= $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/update_pengaturan_batas',
						'POST',
						array(
							'api_search' => array($id_syarat, $jenis, $status)
						)
					);
		$r=0;
		if($update){
			$r = 1;
		}
		echo json_encode($r);

	}

	function cek_fn(){
		$api_url = "http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_view";

		$upt = 0;
		$dekan = 0;
		$wadek = 0;
		$kaprodi = 0;
		$sekprodi = 0;
		$lem = 0;
		$spi = 0;
		$rektor = 0;
		$warek = 0;
		$direktur = 0;
		
		$data = $this->api->get_api_json(
			$api_url,
			'POST',
			array(
				'api_kode' => 1001,
				'api_subkode' => 3,
				'api_search' => array()
			)
		);


		$result = array();

		foreach ($data as $d) {
			$unit = $d['UNIT_ID'];

			$api_url = "http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search";
		
			$dat_unt = $this->api->get_api_json(
				$api_url,
				'POST',
				array(
					'api_kode' => 1121,
					'api_subkode' => 15,
					'api_search' => array($unit)
				)
			);


			if($dat_unt){
				foreach ($dat_unt as $du) {
					if($du['RR_STATUS'] == 1 && strtoupper($du['STR_NAMA_A244']) == "KEPALA" && strtoupper($du['JENIS_NAMA_S1']) == "UPT"){
						$result[$du['JENIS_NAMA_S1']][$upt]['ID_UNIT'] = $du['UNIT_ID'];
						$result[$du['JENIS_NAMA_S1']][$upt]['ID_STR'] = $du['STR_ID'];
						$result[$du['JENIS_NAMA_S1']][$upt]['LEADER'] = $du['STR_NAMA'];
						$result[$du['JENIS_NAMA_S1']][$upt]['PEJABAT'] = $du['NM_PGW_F'];
						$upt++;
					}

					if($du['RR_STATUS'] == 1 && strtoupper($du['STR_NAMA_A244']) == "DEKAN" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
						$result['DEKAN'][$dekan]['ID_UNIT'] = $du['UNIT_ID'];
						$result['DEKAN'][$dekan]['ID_STR'] = $du['STR_ID'];
						$result['DEKAN'][$dekan]['LEADER'] = $du['STR_NAMA'];
						$result['DEKAN'][$dekan]['PEJABAT'] = $du['NM_PGW_F'];
						$dekan++;
					}

					if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA_A244'], 0, 11)) == "WAKIL DEKAN" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
						$result['WADEK'][$wadek]['ID_UNIT'] = $du['UNIT_ID'];
						$result['WADEK'][$wadek]['ID_STR'] = $du['STR_ID'];
						$result['WADEK'][$wadek]['LEADER'] = $du['STR_NAMA'];
						$result['WADEK'][$wadek]['PEJABAT'] = $du['NM_PGW_F'];
						$wadek++;
					}

					// if($du['RR_STATUS'] == 1 && strtoupper($du['STR_NAMA_A244']) == "KETUA" && strtoupper($du['SUB_JENIS_NAMA_S1']) == "PRODI"){
					// 	$result['KAPRODI'][$kaprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['KAPRODI'][$kaprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['KAPRODI'][$kaprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['KAPRODI'][$kaprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$kaprodi++;
					// }

					// if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA'],0,19)) == "KETUA PROGRAM STUDI" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
					// 	$result['KAPRODI'][$kaprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['KAPRODI'][$kaprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['KAPRODI'][$kaprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['KAPRODI'][$kaprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$kaprodi++;
					// }

					// if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA'],0,11)) == "KETUA PRODI" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
					// 	$result['KAPRODI'][$kaprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['KAPRODI'][$kaprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['KAPRODI'][$kaprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['KAPRODI'][$kaprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$kaprodi++;
					// }

					// if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA'],0,13)) == "KETUA JURUSAN" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
					// 	$result['KAPRODI'][$kaprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['KAPRODI'][$kaprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['KAPRODI'][$kaprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['KAPRODI'][$kaprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$kaprodi++;
					// }

					// if($du['RR_STATUS'] == 1 && strtoupper($du['STR_NAMA_A244']) == "SEKRETARIS" && strtoupper($du['SUB_JENIS_NAMA_S1']) == "PRODI"){
					// 	$result['SEKPRODI'][$sekprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['SEKPRODI'][$sekprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$sekprodi++;
					// }

					// if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA'],0,24)) == "SEKRETARIS PROGRAM STUDI" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
					// 	$result['SEKPRODI'][$sekprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['SEKPRODI'][$sekprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$sekprodi++;
					// }

					// if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA'],0,16)) == "SEKRETARIS PRODI" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
					// 	$result['SEKPRODI'][$sekprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['SEKPRODI'][$sekprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$sekprodi++;
					// }

					// if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA'],0,18)) == "SEKRETARIS JURUSAN" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
					// 	$result['SEKPRODI'][$sekprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['SEKPRODI'][$sekprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$sekprodi++;
					// }

					// if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA'],0,18)) == "SEKPRODI" && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
					// 	$result['SEKPRODI'][$sekprodi]['ID_UNIT'] = $du['UNIT_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['ID_STR'] = $du['STR_ID'];
					// 	$result['SEKPRODI'][$sekprodi]['LEADER'] = $du['STR_NAMA'];
					// 	$result['SEKPRODI'][$sekprodi]['PEJABAT'] = $du['NM_PGW_F'];
					// 	$sekprodi++;
					// }

					if($du['RR_STATUS'] == 1 && strpos(strtoupper($du['STR_NAMA']),'SEKRETARIS') !== false && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
						$result['SEKPRODI'][$sekprodi]['ID_UNIT'] = $du['UNIT_ID'];
						$result['SEKPRODI'][$sekprodi]['ID_STR'] = $du['STR_ID'];
						$result['SEKPRODI'][$sekprodi]['LEADER'] = $du['STR_NAMA'];
						$result['SEKPRODI'][$sekprodi]['PEJABAT'] = $du['NM_PGW_F'];
						$sekprodi++;
					}

					if($du['RR_STATUS'] == 1 && strpos(strtoupper($du['STR_NAMA']),'KETUA') !== false && strtoupper($du['JENIS_NAMA_S1']) == "FAK"){
						$result['KAPRODI'][$kaprodi]['ID_UNIT'] = $du['UNIT_ID'];
						$result['KAPRODI'][$kaprodi]['ID_STR'] = $du['STR_ID'];
						$result['KAPRODI'][$kaprodi]['LEADER'] = $du['STR_NAMA'];
						$result['KAPRODI'][$kaprodi]['PEJABAT'] = $du['NM_PGW_F'];
						$kaprodi++;
					}

					if($du['RR_STATUS'] == 1 && strpos(strtoupper($du['STR_NAMA']),'DIREKTUR') !== false){
						$result['DIREKTUR'][$direktur]['ID_UNIT'] = $du['UNIT_ID'];
						$result['DIREKTUR'][$direktur]['ID_STR'] = $du['STR_ID'];
						$result['DIREKTUR'][$direktur]['LEADER'] = $du['STR_NAMA'];
						$result['DIREKTUR'][$direktur]['PEJABAT'] = $du['NM_PGW_F'];
						$direktur++;
					}



					if($du['RR_STATUS'] == 1 && strtoupper($du['STR_NAMA_A244']) == "KETUA" && strtoupper($du['JENIS_NAMA']) == "LEMBAGA"){
						$result['LEMBAGA'][$lem]['ID_UNIT'] = $du['UNIT_ID'];
						$result['LEMBAGA'][$lem]['ID_STR'] = $du['STR_ID'];
						$result['LEMBAGA'][$lem]['LEADER'] = $du['STR_NAMA'];
						$result['LEMBAGA'][$lem]['PEJABAT'] = $du['NM_PGW_F'];
						$lem++;
					}

					if($du['RR_STATUS'] == 1 && strtoupper($du['STR_NAMA_A244']) == "KETUA" && strtoupper($du['UNIT_NAMA_S2']) == "SPI"){
						$result['SPI'][$spi]['ID_UNIT'] = $du['UNIT_ID'];
						$result['SPI'][$spi]['ID_STR'] = $du['STR_ID'];
						$result['SPI'][$spi]['LEADER'] = $du['STR_NAMA'];
						$result['SPI'][$spi]['PEJABAT'] = $du['NM_PGW_F'];
						$spi++;
					}

					if($du['RR_STATUS'] == 1 && strtoupper($du['STR_NAMA_A244']) == "REKTOR" && strtoupper($du['JENIS_NAMA']) == "REKTORAT"){
						$result['REKTOR'][$rektor]['ID_UNIT'] = $du['UNIT_ID'];
						$result['REKTOR'][$rektor]['ID_STR'] = $du['STR_ID'];
						$result['REKTOR'][$rektor]['LEADER'] = $du['STR_NAMA'];
						$result['REKTOR'][$rektor]['PEJABAT'] = $du['NM_PGW_F'];
						$rektor++;
					}

					if($du['RR_STATUS'] == 1 && strtoupper(substr($du['STR_NAMA_A244'], 0, 12)) == "WAKIL REKTOR" && strtoupper($du['JENIS_NAMA']) == "REKTORAT"){
						$result['WAREK'][$warek]['ID_UNIT'] = $du['UNIT_ID'];
						$result['WAREK'][$warek]['ID_STR'] = $du['STR_ID'];
						$result['WAREK'][$warek]['LEADER'] = $du['STR_NAMA'];
						$result['WAREK'][$warek]['PEJABAT'] = $du['NM_PGW_F'];
						$warek++;
					}

				}
			}
			

		}


		// $api_url = "http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search";
		
		// $data = $this->api->get_api_json(
		// 	$api_url,
		// 	'POST',
		// 	array(
		// 		'api_kode' => 1121,
		// 		'api_subkode' => 15,
		// 		'api_search' => array($unit)
		// 	)
		// );

		foreach ($result as $r => $v) {
			echo "KATEGORI : ".$r; echo '<br> =============================================== <br>';
			foreach ($v as $k => $w) {

				if($r == 'WAREK'){
					$kat = 'B';
				}
				else if($r == 'DEKAN'){
					$kat = 'C';
				}
				else if($r == 'DIREKTUR'){
					$kat = 'D';
				}
				else if($r == 'WADEK'){
					$kat = 'E';
				}
				else if($r == 'LEMBAGA'){
					$kat = 'F';
				}
				else if($r == 'UPT'){
					$kat = 'G';
				}
				else if($r == 'SPI'){
					$kat = 'H';
				}
				else if($r == 'KAPRODI'){
					$kat = 'I';
				}
				else if($r == 'SEKPRODI'){
					$kat = 'J';
				}				
				else{
					$kat = 'X0X';
				}

				echo "INSERT INTO BKD_MAP_DOSEN_DT VALUES('".$w['ID_STR']."', '".$kat."');";
				echo '<br>';
			}
			echo '<br>';
		}

		echo '<pre>';
		print_r($result);
		echo '</pre>';


	}
 
	function cek_fn2(){
		$unit = "UN02901";

		$api_url = "http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search";
		
		$data = $this->api->get_api_json(
			$api_url,
			'POST',
			array(
				'api_kode' => 1121,
				'api_subkode' => 15,
				'api_search' => array($unit)
			)
		);

		echo '<pre>';
		print_r($data);
		echo '</pre>';
	}



	function cek_fn3(){
		$nomer = "7654321V";
		
		$data = $this->api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_map_dosen_dt',
						'POST',
						array(
							'api_search' => array($nomer)
						)
					); 

		echo '<pre>';
		print_r($data);
		echo '</pre>';

		echo '<br>';
		echo '<br>';
		echo '<br>';
		$this->load->library('bkd_lib_setting', '', 'sett');
		$cek = $this->sett->_get_sks_max($nomer);


		echo '<pre>';
		print_r($cek);
		echo '</pre>';
		
	}

	function nosertif($kd = "197701032005011003"){
		$kd_dosen = $kd;
		$api_url 	= URL_API_SIMPEG1.'simpeg_mix/data_search';
		$parameter  = array('api_kode'=>2001, 'api_subkode'=>2, 'api_search' => array($kd_dosen));
		$data = $this->s00_lib_api->get_api_jsob($api_url,'POST',$parameter);

		echo '<pre>';
		print_r($data);
		echo '</pre>';

	}

	function get_univ($something, $val1='', $val2='')
	{
		$CI =& get_instance();
		switch ($something) {
			case 'fak':
				$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
				$data= $CI->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);
				break;
			case 'prod':
				$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($val1));
				$data= $CI->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);
				break;
			case 'mhs':
				$parameter = array(	'api_kode' => 26000,'api_subkode' => 16,'api_search'=> array($val1,$val2));
				$data= $CI->api->get_api_json(URL_API_SIA.'sia_mahasiswa/data_search','POST',$parameter);
				break;
			default:
				$data = false;
				break;
		}
		return $data;
	}

	function cek_fnn(){
		$jenis_dosen = 'DS';
		$syarat_pendidikan = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_syarat_kesimpulan',
						'POST',
						array(
							'api_kode' => 1000,
							'api_subkode' => 1, //untuk PENDIDIKAN
							'api_search' => array($jenis_dosen)
						)
					);
		echo '<pre>';
		print_r($syarat_pendidikan);
		echo '</pre>';
	}

	//PENGATURAN DATA KONVERSI SERTIFIKASI DOSEN <> REMUNERASI DOSEN
	function pengaturan_konversi(){
		//panggil view aja :)
		$this->output99->output_display('admbkd/data_pengaturan_konversi');
	} 

	function get_kat_serdos(){
		$data = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_kat_serdos_available',
						'POST',
						array()
					);
		
		echo json_encode($data);

	}

	function get_kat_remun(){
		$data = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_kat_remun_available',
						'POST',
						array()
					);
		
		echo json_encode($data);
	}

	function get_data_konversi(){
		$data = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_all_konversi',
						'POST',
						array()
					);
		
		echo json_encode($data);
	}

	function delete_data_konversi(){
		$kd_kat = $this->input->post('kd_kat');
		$data = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/delete_konversi_kategori',
						'POST',
						array('api_search' => array($kd_kat))
					);
		
		if($data){
			echo '1';
		}else{
			echo '0';
		}

		//echo json_encode($_POST);

	}

	function tambah_data_konversi(){
		$kd_kat_serdos = $this->input->post('kd_serdos');
		$kd_kat_remun = $this->input->post('kd_remun');
		// $kd_kat_serdos = '72';
		// $kd_kat_remun = '10';//$this->input->post('kd_remun');
		$data = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/tambah_konversi_kategori',
						'POST',
						array('api_search' => array($kd_kat_serdos, $kd_kat_remun))
					);
		
		if($data){
			echo '1';
		}else{
			echo '0';
		}


		//echo json_encode($_POST);
	}

	function get_kategori(){
		$bidang = $this->input->post('bidang');

		$data = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/get_kategori',
						'POST',
						array('api_search' => array($bidang))
					);

		echo json_encode($data);
	}

	function pengaturan_jalur(){
		$this->output99->output_display('admbkd/data_pengaturan_jalur');
	}

	function pindah_jalur_kat(){
		$kode = $this->input->post('kd_kat');

		$data = $this->s00_lib_api->get_api_json(
						URL_API_BKD.'/bkd_beban_kerja/pindah_jalur_kat',
						'POST',
						array('api_search' => array($kode))
					);
		if($data){
			echo '1';
		}else{
			echo '0';
		}
		// echo $data;
	}



	function experiment(){





		$url = 'http://service5.uin-suka.ac.id/servremun/remun/api/';

		$tahun = '2017';
		$nip = '198205112006042002';
		$triwulan = 'I';
		$id = $nip . $tahun;
		$api_url = $url . 'nilai_realisasi';
		$parameter = array('id'=> $id, 'triwulan'=>$triwulan); 
		$iku = 0;//$this->s00_lib_api->post($api_url, $parameter);

		$iku = $this->s00_lib_api->get_api_json(
			$api_url,
			'POST',
			$parameter
		);

		
		echo '<pre>';
		print_r($iku);
		echo '</pre>';

		// iku
		// $tahun = date('Y');
		// $id = $nip . $tahun;
		// $parameter = array('id'=> $id, 'triwulan'=>$triwulan); //nilai triwulan  = 'I', 'II', 'III', 'IV'
		// $api_url = $url . 'nilai_realisasi';
		// $iku = $this->s00_lib_api->post($url, $parameter);



		$api_url = $url . 'nilai_skp_remun';
		$parameter = array('ID_PEGAWAI'=>'197102092005011003', 'TAHUN'=>'2017');
		$skp = 0;//$this->s00_lib_api->post($api_url, $parameter);

		$skp = $this->s00_lib_api->get_api_json(
			$api_url,
			'POST',
			$parameter
		);

		echo '<pre>';
		print_r($skp);
		echo '</pre>';

		//echo '<'
		// skp
		// $parameter = array('ID_PEGAWAI'=>$nip, 'TAHUN'=>$tahun);
		// $api_url = $url . 'nilai_skp_remun';
		// $skp = $this->s00_lib_api->post($url, $parameter);

		//presensi kehadiran
		$api_url = $url . 'kehadiran_pegawai';
		$parameter = array('ID_PEGAWAI'=> '197701032005011003', 'TAHUN'=>'2017', 'BULAN'=>'1');
		$kehadiran = 0;//$this->s00_lib_api->post($api_url, $parameter);

		$kehadiran = $this->s00_lib_api->get_api_json(
			$api_url,
			'POST',
			$parameter
		);

		//$presensi = $this->s00_lib_api->api_post($api_url, $parameter);

		
		echo '<pre>';
		print_r($kehadiran);
		echo '</pre>';

		//service5.uin-suka.ac.id			
	}

	function exp(){
		$url = "http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_view";
		$unit = $this->s00_lib_api->get_api_json(
			$url,
			'POST',
			array(
				'api_kode' => 1001,
				'api_subkode' => 3
			)
		);

		/*echo '<pre>';
		print_r($unit);
		echo '</pre>';

		die();*/
		
		$url = "http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search";
		$peg = $this->s00_lib_api->get_api_json(
			$url,
			'POST',
			array(
				'api_kode' => 1121,
				'api_subkode' => 15,
				'api_search' => array('UN02006')
			)
		);
		foreach ($peg as $p) {
			if($p['RR_STATUS_N']=='Aktif'){
				/*if($p['STR_NAMA_S2']=='Wadek Bid. Bidang Akademik' || $p['STR_NAMA_S2']=='Wadek Bid. ADUM, Perencanaan dan Keuangan'){
					$nip = $p['NIP'];
					$nama = $p['NM_PGW_F'];
					$jabatan = $p['STR_NAMA'];
					$temp[]=array("NIP"=>$nip, "NAMA_PEGAWAI"=>$nama, "JABATAN"=>$jabatan);
				}*/
				$nip = $p['NIP'];
				$nama = $p['NM_PGW_F'];
				$jabatan = $p['STR_NAMA'];
				$temp[]=array("NIP"=>$nip, "NAMA_PEGAWAI"=>$nama, "JABATAN"=>$jabatan);
			}
		}
		echo '<pre>';
		print_r($temp);
		echo '</pre>';
		
	}

	function exp_mdl(){
		$kd_dosen = '196611261996031001';

		$cek = $this->s00_lib_api->get_api_json(
			URL_API_BKD.'bkd_beban_kerja/cek_kewajiban_serdos',
			'POST',
			array(
				'api_search' => array($kd_dosen)
			)
		);

		echo $cek;

		//Fungsi untuk mengecek wajib serdos atau tidak
		//Return value 0 untuk tidak wajib / 1 untuk wajib



	}
	function cek_jabatan_dosen(){
		$NIP = '197701032005011003';
		//$NIP = '198205112006042002';
		$url = 'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search';

		$cek = $this->s00_lib_api->get_api_json(
			$url,
			'POST',
			array(
				'api_kode' => 1121,
				'api_subkode' => 3,
				'api_search' => array(date('d-m-Y'),$NIP,1)
			)
		);
		$kd_fak='';
		foreach ($cek as $c) {
			if($c['RR_STATUS_N']=='Aktif'){
				if($c['STR_NAMA_S2']=='Wadek Bid. Bidang Akademik' || $c['STR_NAMA_S2']=='Wadek Bid. ADUM, Perencanaan dan Keuangan'){
					$kd_unit = $c['UNIT_ID'];
					$url = 'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search';
					$cek2 = $this->s00_lib_api->get_api_json(
								$url,
								'POST',
								array(
									'api_kode' => 1901,
									'api_subkode' => 4,
									'api_search' => array(date('d/m/Y'),$kd_unit)
								)
							);
					foreach ($cek2 as $c) {
						$kd_fak = $c['SIA_KODE'];
					}
					/*echo $kd_fak;*/
				}
			}

		}
		return $kd_fak;
	}
	function get_data_fakultas(){
		$parameter = array(	'api_kode' => 17000,'api_subkode' => 1,'api_search'=> array());
		$data= $this->api->get_api_json(URL_API_SIA.'sia_master/data_view','POST',$parameter);
		echo "<pre>";
		print_r($data);
		echo "<pre>";

	}
	function get_data_prodi(){
		//$fak = $this->input->post('fak');
		$kd_fak = $this->cek_jabatan_dosen();
		if(!empty($kd_fak)){
			$fak = $kd_fak;
			$parameter = array(	'api_kode' => 19000,'api_subkode' => 6,'api_search'=> array($fak));
		$data= $this->api->get_api_json(URL_API_SIA.'sia_master/data_search','POST',$parameter);

		/*echo json_encode($data);*/
		echo "<pre>";
		print_r($data);
		echo "</pre>";
		}
		
	}
	function get_kd_fakultas(){
		$kd_unit = 'UN02006';
		$url = 'http://service2.uin-suka.ac.id/servsimpeg/simpeg_public/simpeg_mix/data_search';
		$cek = $this->s00_lib_api->get_api_json(
					$url,
					'POST',
					array(
						'api_kode' => 1901,
						'api_subkode' => 4,
						'api_search' => array(date('d/m/Y'),$kd_unit)
					)
				);
				echo "<pre>";
				print_r($cek);
				echo "<pre>";
			}
}