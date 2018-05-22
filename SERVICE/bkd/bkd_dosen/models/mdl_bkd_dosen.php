<?php if (!defined('BASEPATH')) exit ();

class Mdl_bkd_dosen extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	
	function create_session($username){
		$sql = "SELECT SIA.V_DOSEN.KD_DOSEN AS KODE, BKD.BKD_DOSEN.KD_PRODI AS KPRODI, BKD.BKD_DOSEN.KD_FAK AS KFAK, SIA.V_DOSEN.NIP, BKD.BKD_DOSEN.THN_PROF,
				SIA.V_DOSEN.NIDN, SIA.V_DOSEN.NM_DOSEN, BKD.BKD_DOSEN.KD_JENIS_DOSEN FROM SIA.V_DOSEN 
				LEFT JOIN BKD.BKD_DOSEN ON SIA.V_DOSEN.KD_DOSEN = BKD.BKD_DOSEN.KD_DOSEN
				WHERE SIA.V_DOSEN.KD_DOSEN = '$username'";
		return $this->db->query($sql)->result_array();
	}
	
	function get_data_dosen($id){
		// $sql = "SELECT * FROM SIA.V_DOSEN
				// LEFT JOIN BKD.BKD_DOSEN ON SIA.V_DOSEN.KD_DOSEN = BKD.BKD_DOSEN.KD_DOSEN
				// LEFT OUTER JOIN SIA.MASTER_FAK ON BKD.BKD_DOSEN.KD_FAK = SIA.MASTER_FAK.KD_FAK
				// LEFT OUTER JOIN BKD.BKD_JENIS_DOSEN ON BKD.BKD_DOSEN.KD_JENIS_DOSEN = BKD.BKD_JENIS_DOSEN.KD_JENIS_DOSEN
				// LEFT JOIN SIA.MD_JABATAN_PEG ON SIA.V_DOSEN.KD_JABATAN = SIA.MD_JABATAN_PEG.KD_JABATAN 
				// LEFT JOIN SIA.MD_GOLONGAN_PEG ON SIA.V_DOSEN.KD_GOLONGAN = SIA.MD_GOLONGAN_PEG.KD_GOLONGAN 
				// LEFT JOIN SIA.MASTER_PRODI ON SIA.V_DOSEN.KD_PRODI = SIA.MASTER_PRODI.KD_PRODI 
				// WHERE SIA.V_DOSEN.KD_DOSEN = '$id'";
		$sql = "SELECT * FROM BKD.BKD_DOSEN WHERE KD_DOSEN = '$id'";
		return $this->db->query($sql)->result_array();
	}
	
	function get_data_dosen_new($id){
		$sql = "SELECT KD_DOSEN, NO_KTP, NM_DOSEN, NM_DOSEN_F, NM_DOSEN_P, KD_PRODI, NM_JENIS_DOSEN FROM SIA.V_DOSEN
				LEFT JOIN BKD.BKD_DOSEN ON SIA.V_DOSEN.KD_DOSEN = BKD.BKD_DOSEN.KD_DOSEN
				LEFT OUTER JOIN SIA.MASTER_FAK ON BKD.BKD_DOSEN.KD_FAK = SIA.MASTER_FAK.KD_FAK
				LEFT OUTER JOIN BKD.BKD_JENIS_DOSEN ON BKD.BKD_DOSEN.KD_JENIS_DOSEN = BKD.BKD_JENIS_DOSEN.KD_JENIS_DOSEN
				LEFT JOIN SIA.MD_JABATAN_PEG ON SIA.V_DOSEN.KD_JABATAN = SIA.MD_JABATAN_PEG.KD_JABATAN 
				LEFT JOIN SIA.MD_GOLONGAN_PEG ON SIA.V_DOSEN.KD_GOLONGAN = SIA.MD_GOLONGAN_PEG.KD_GOLONGAN 
				LEFT JOIN SIA.MASTER_PRODI ON SIA.V_DOSEN.KD_PRODI = SIA.MASTER_PRODI.KD_PRODI 
				WHERE SIA.V_DOSEN.KD_DOSEN = '$id'";
		return $this->db->query($sql)->result_array();
	}
	
	function cek_dosen_bkd($kode){
		$sql = "SELECT * FROM BKD.BKD_DOSEN WHERE KD_DOSEN = '$kode' ";	
		return $this->db->query($sql)->num_rows();
	}
	
	function get_email_dosen($kd){
		$sql = "SELECT SIA.D_DOSEN.EMAIL FROM SIA.D_DOSEN WHERE KD_DOSEN = '$kd'";
		return $this->db->query($sql)->result_array();
	}

	function data_fakultas(){
		$sql = "SELECT * FROM BKD.BKD_MD_FAK
				LEFT JOIN SIA.MASTER_FAK ON BKD_MD_FAK.KD_FAK = SIA.MASTER_FAK.KD_FAK
				LEFT JOIN SIA.MASTER_PT ON BKD_MD_FAK.KD_PT = SIA.MASTER_PT.KD_PT"; 
		return $this->db->query($sql)->result_array();
	}
	
	function data_prodi(){
		$sql = "SELECT * FROM SIA.MASTER_PRODI"; 
		return $this->db->query($sql)->result_array();
	}
	
	function get_nama_prodi($kode){
		$sql = "SELECT NM_PRODI FROM SIA.MASTER_PRODI WHERE KD_PRODI = '$kode'";
		foreach($this->db->query($sql)->result_array() as $data);
		return $data['NM_PRODI'];
	}
	
	function data_pt(){
		$sql = "SELECT * FROM BKD.BKD_MD_PT
				LEFT JOIN SIA.MASTER_PT ON BKD.BKD_MD_PT.KD_PT = SIA.MASTER_PT.KD_PT"; 
		return $this->db->query($sql)->result_array();
	}
	
	function data_jabatan(){
		$sql = "SELECT * FROM SIA.MD_JABATAN_PEG";
		return $this->db->query($sql)->result_array();
	}

	function data_golongan(){
		$sql = "SELECT * FROM SIA.MD_GOLONGAN_PEG";
		return $this->db->query($sql)->result_array();
	}

	function get_bkd_dosen($id){
		$sql = "SELECT * FROM BKD.BKD_DOSEN WHERE KD_DOSEN = '$id'";
		return $this->db->query($sql)->result_array();
	}
	function get_biografi_dosen($id){
		$sql = "SELECT DBMS_LOB.SUBSTR(DESKRIPSI_SINGKAT, 5000 ,1) AS DESKRIPSI,NAMA_FILE_CV FROM BKD_BIOGRAFI_DOSEN WHERE KD_DOSEN= '$id'";
		return $this->db->query($sql)->row_array();
	}
	function get_cv_dosen($id){
		$sql = "SELECT NAMA_FILE_CV,FILE_CV FROM BKD_BIOGRAFI_DOSEN WHERE KD_DOSEN= '$id'";
		return $this->db->query($sql)->row_array();
	}
	
	# MODEL JENJANG PENDIDIKAN DOSEN
	# ==============================================================================================================================
	function simpan_jenjang_pendidikan($id, $jenjang, $nm_pt, $jurusan, $masuk, $lulus){
		$sql = "INSERT INTO BKD.BKD_JENJANG_PENDIDIKAN_DOSEN (KD_DOSEN, JENJANG, NM_PT, JURUSAN, TANGGAL_MASUK, TANGGAL_LULUS) 
				VALUES ('$id','$jenjang','$nm_pt','$jurusan', TO_DATE('$masuk','DD/MM/YYYY'), TO_DATE('$lulus','DD/MM/YYYY'))";
		return $this->db->query($sql);
	}
	
	function update_jenjang_pendidikan($id, $jenjang, $nm_pt, $jurusan, $masuk, $lulus){
		#$id = $this->session->userdata('kd_dosen');
		$sql = "UPDATE BKD.BKD_JENJANG_PENDIDIKAN_DOSEN SET JENJANG = '$jenjang', NM_PT = '$nm_pt', 
				JURUSAN = '$jurusan', TANGGAL_MASUK = TO_DATE('$masuk','DD/MM/YYYY'), TANGGAL_LULUS = TO_DATE('$lulus','DD/MM/YYYY')
				WHERE KD_DOSEN = '$id' ";
		return $this->db->query($sql);
	}
	
	function get_jenjang_pendidikan_dosen($id, $jenjang){
		$sql = "SELECT * FROM BKD.BKD_JENJANG_PENDIDIKAN_DOSEN WHERE KD_DOSEN = '$id' AND JENJANG = '$jenjang'";
		return $this->db->query($sql)->result_array();
	}
	
	
	
	/* MODEL CRUD BKD DOSEN 
	================================================================================================================================= */
	function simpan_bkd_dosen($a, $b, $c, $d){
		$sql = "INSERT INTO BKD.BKD_DOSEN(KD_DOSEN, KD_JENIS_DOSEN, KD_PRODI, KD_FAK) VALUES ('$a','$b','$c','$d')";
		return $this->db->query($sql);
	}
	

	function update_identitas_bkd($kd_dosen, $a, $b){
		$sql = "UPDATE BKD.BKD_DOSEN SET 
				THN_TUNJ_PROFESI = '$a',
				THN_TUNJ_KEHORMATAN = '$b'
				WHERE KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql);
	}
	
	function update_identitas_dosen($kd_dosen, $q, $r, $s, $t, $u, $v, $w, $x, $y, $z, $alamat){
		$sql = "UPDATE SIA.D_DOSEN SET
				NM_DOSEN = '$q',
				GELAR_DEPAN = '$r',
				GELAR_BELAKANG = '$s',
				J_KELAMIN = '$t',
				TMP_LAHIR = '$u',
				TGL_LAHIR = TO_DATE('$v','DD/MM/YYYY'),
				TELP_RUMAH = '$w',
				MOBILE = '$x',
				EMAIL = '$y',
				NO_KTP = '$z',
				ALMT_RUMAH = '$alamat'
				WHERE KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql);
	}
	function update_biografi_dosen($param=array()){
		$q1 = $this->db->query("SELECT KD_DOSEN FROM BKD_BIOGRAFI_DOSEN WHERE KD_DOSEN='".$param['kd_dosen']."'")->num_rows();
		if($q1<=0){
			$q2=$this->db->query("INSERT INTO BKD_BIOGRAFI_DOSEN(KD_DOSEN,DESKRIPSI_SINGKAT) 
			VALUES ('".$param['kd_dosen']."','".$param['deskripsi']."')");
		}else{
			$q2=$this->db->query("UPDATE BKD_BIOGRAFI_DOSEN
			SET DESKRIPSI_SINGKAT='".$param['deskripsi']."'
			WHERE KD_DOSEN='".$param['kd_dosen']."'");
		}
		
		if(!empty($param['FILE_CV'])){
			$file =base64_decode($param['FILE_CV']);
			$sql = "UPDATE BKD_BIOGRAFI_DOSEN SET
					NAMA_FILE_CV='".$param['NAMA_FILE_CV']."',
					FILE_CV=EMPTY_BLOB()
					WHERE KD_DOSEN='".$param['kd_dosen']."'
					RETURNING
					FILE_CV INTO :mylob_loc";

			$stmt = oci_parse($this->db->conn_id, $sql);
			$myLOB = oci_new_descriptor($this->db->conn_id, OCI_D_LOB);
			oci_bind_by_name($stmt, ":mylob_loc", $myLOB, -1, OCI_B_BLOB);

			oci_execute($stmt, OCI_DEFAULT)
				or die ("Unable to execute query\n");
			if ( !$myLOB->save($file) ) {
				oci_rollback($this->db->conn_id);
			} else {
				oci_commit($this->db->conn_id);
			}
			oci_free_statement($stmt);
			$myLOB->free(); 
		} 
		return $q1;
	}
	
	function update_kepegawaian_dosen($kd_dosen, $a, $b, $c, $d, $e, $f){
		$sql = "UPDATE BKD.BKD_DOSEN SET 
				KEMENTRIAN_INDUK = '$a',
				STATUS_PEG = '$b',
				NO_KARTU = '$c',
				TMT_GOLONGAN = TO_DATE('$d','DD/MM/YYYY'),
				TMT_JABATAN = TO_DATE('$e','DD/MM/YYYY'),
				NO_SERTIFIKAT = '$f'
				WHERE KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql);
	}
	
	function update_akademik_dosen($kd_dosen, $a, $b){
		$sql = "UPDATE BKD.BKD_DOSEN SET 
				KD_JENIS_DOSEN = '$a',
				THN_PROF = '$b'
				WHERE KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql);
	}
	
	# RIWAYAT PENDIDIKAN DOSEN
	# ======================================================================================================================
	function simpan_riwayat_pendidikan($a, $b, $c, $d, $e, $f, $g, $h, $i, $j){
		$sql = "INSERT INTO BKD.BKD_RIWAYAT_PENDIDIKAN 
				VALUES ('','$a', '$b', '$c', '$d', '$e', '$f', TO_DATE('$g','DD/MM/YYYY'), TO_DATE('$h','DD/MM/YYYY'), '$i', '$j')";
		return $this->db->query($sql);
	}
	
	function update_riwayat_pendidikan($a, $b, $c, $d, $e, $f, $g, $h, $i, $j){
		$sql = "UPDATE BKD.BKD_RIWAYAT_PENDIDIKAN SET
				NM_PRODI = '$b', BIDANG_ILMU = '$c', JENJANG = '$d', NM_PT = '$e', KD_NEGARA = '$f', 
				TGL_MULAI = TO_DATE('$g','DD/MM/YYYY'), TGL_SELESAI = TO_DATE('$h','DD/MM/YYYY'), GELAR = '$i', SUMBER_DANA = '$j'
				WHERE KD_DOSEN = '$a'";
		return $this->db->query($sql);
	}
	
	function riwayat_pendidikan_dosen($kd_dosen){
		$sql = "SELECT * FROM BKD.BKD_RIWAYAT_PENDIDIKAN WHERE KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql)->result_array();
	}
	
	function current_riwayat_pendidikan($a){
		$sql = "SELECT * FROM BKD.BKD_RIWAYAT_PENDIDIKAN WHERE KD_RIWAYAT = '$a'";
		return $this->db->query($sql)->result_array();
	}
	
	function delete_riwayat_pendidikan($a){
		$sql = "DELETE BKD.BKD_RIWAYAT_PENDIDIKAN WHERE KD_RIWAYAT = '$a'";
		return $this->db->query($sql);
	}
	
	
	# ==================================
	
	
	function update_asesor_dosen($a, $q, $r){
		$sql = "UPDATE BKD.BKD_ASESOR_DOSEN SET
				NIRA1 = '$q',
				NIRA2 = '$r'
				WHERE KD_AD = '$a'";
		return $this->db->query($sql);
	}

	function get_all_dosen(){
		$sql = "SELECT A.KD_DOSEN, A.KD_PRODI, A.KD_FAK, A.NO_SERTIFIKAT, B.NIP, B.NM_DOSEN FROM BKD.BKD_DOSEN A 
				LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN ORDER BY B.NM_DOSEN";
		return $this->db->query($sql)->result_array();
	}
	
	function get_dosen_fakultas($kd_fak){
		$sql = "SELECT A.KD_DOSEN, A.KD_PRODI, A.KD_FAK, A.NO_SERTIFIKAT, B.NIP, B.NM_DOSEN FROM BKD.BKD_DOSEN A 
				LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN WHERE A.KD_FAK = '$kd_fak' ORDER BY B.NM_DOSEN";
		return $this->db->query($sql)->result_array();
	}
	
	
	
	/* model data asesor 
	==============================
	==============================
	*/
	
	function simpan_asesor($a, $b, $c, $d, $e, $f, $kode){
		$sql = "INSERT INTO BKD.BKD_ASESOR VALUES ('$a','$b','$c','$d','$e','$f')";
		$sql2 = "INSERT INTO BKD.BKD_ASESOR_PRODI VALUES ('$a','$kode')";
		$simpan_asesor = $this->db->query($sql);
		if ($simpan_asesor){
			return $this->db->query($sql2);
		}else{
			return $this->db->query($sql2);
		}
	}
	
	function simpan_asesor_prodi($a, $b){
		$sql = "INSERT INTO BKD.BKD_ASESOR_PRODI VALUES ('$a','$b')";
		return $this->db->query($sql);
	}
	
	function update_data_asesor($a, $b, $c, $d, $e, $f, $g){
		$sql = "UPDATE BKD.BKD_ASESOR SET 
				KD_DOSEN = '$g',
				NM_ASESOR = '$a',
				NM_PT = '$b',
				RUMPUN = '$c',
				BIDANG_ILMU = '$d',
				TELP = '$e' WHERE NIRA = '$f'";
		return $this->db->query($sql);
	}
	
	function delete_asesor($nira){
		$sql = "DELETE FROM BKD.BKD_ASESOR WHERE NIRA = '$nira'";
		$sql2 = "DELETE FROM BKD.BKD_ASESOR_PRODI WHERE NIRA = '$nira'";
		$this->db->query($sql);
		return $this->db->query($sql2);
	}	

	function biodata_asesor($nira){
		$sql = "SELECT * FROM BKD.BKD_ASESOR WHERE NIRA = '$nira'";
		return $this->db->query($sql)->result_array();
	}
	
	function data_asesor(){
		$sql = "SELECT * FROM BKD.BKD_ASESOR ORDER BY NM_ASESOR ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function data_asesor_limit($limit, $start){
		#$end_row = $page + $jum_per_page;
		$sql = "SELECT * FROM (SELECT R.*, ROWNUM AS ROW_NUMBER FROM ( SELECT * FROM BKD.BKD_ASESOR ORDER BY KD_DOSEN, NM_PT, NM_ASESOR ASC) R WHERE ROWNUM <= ".$limit.") 
				WHERE ".$start." < ROW_NUMBER";
		return $this->db->query($sql)->result_array();
	}
	
	function total_asesor_pt(){
		$sql = "SELECT COUNT(*) AS JML FROM BKD.BKD_ASESOR";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['JML'];
	}
	
	function total_asesor_prodi($kode){
		$sql = "SELECT COUNT(*) AS JML FROM BKD.BKD_ASESOR_PRODI A LEFT JOIN BKD.BKD_ASESOR B ON A.NIRA = B.NIRA WHERE A.KD_PRODI = '$kode'";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['JML'];
	}
	
	function data_asesor_prodi($kode){
		$sql = "SELECT * FROM BKD.BKD_ASESOR_PRODI A 
				RIGHT JOIN BKD.BKD_ASESOR B ON A.NIRA = B.NIRA 
				WHERE A.KD_PRODI = '$kode' ORDER BY B.NM_ASESOR ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function data_asesor_prodi_limit($kode, $limit, $start){
		$sql = "SELECT * FROM (SELECT R.*, ROWNUM AS ROW_NUMBER FROM ( SELECT * FROM BKD.BKD_ASESOR_PRODI A LEFT JOIN BKD.BKD_ASESOR B ON A.NIRA = B.NIRA WHERE A.KD_PRODI = '$kode' ) 
				R WHERE ROWNUM <= ".$limit.") WHERE ".$start." < ROW_NUMBER ";
		return $this->db->query($sql)->result_array();
	}
	
	function cari_data_asesor_prodi($kode, $keyword){
		$keyword = strtolower($keyword);
		$sql = "SELECT * FROM BKD.BKD_ASESOR_PRODI A 
				RIGHT JOIN BKD.BKD_ASESOR B ON A.NIRA = B.NIRA 
				WHERE A.KD_PRODI = '$kode' AND LOWER(B.NM_ASESOR) LIKE '%$keyword%' AND ROWNUM <= 20 ORDER BY B.NM_ASESOR ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function asesor_dosen($id,$ta,$smt){
		$sql = "SELECT * FROM BKD.BKD_ASESOR_DOSEN 
				WHERE KD_DOSEN = '$id' AND KD_TA = '$ta' AND KD_SMT = '$smt'";
		return $this->db->query($sql)->result_array();
	}
	
	function get_nama_asesor($nira){
		$sql = "SELECT NIRA, NM_ASESOR FROM BKD.BKD_ASESOR WHERE NIRA = '$nira' ";
		return $this->db->query($sql)->result_array();
	}
	
	function simpan_asesor_dosen($a, $b, $c, $d, $e){
		$sql = "INSERT INTO BKD.BKD_ASESOR_DOSEN (KD_DOSEN, THN_AKADEMIK, SEMESTER, KD_TA, KD_SMT) 
				VALUES ('$a','$b','$c','$d','$e')";
		return $this->db->query($sql);
	}
	
	function update_asesor($a, $b, $c, $d, $e){
		$sql = "UPDATE BKD_ASESOR_DOSEN SET NIRA1 = '$d', NIRA2 = '$e'
				WHERE KD_DOSEN = '$a' AND THN_AKADEMIK = '$b' AND SEMESTER = '$c'";
		return $this->db->query($sql);
	}
	
	function cek_asdos_semester($a, $b, $c){
		$sql = "SELECT COUNT(*) AS JUMLAH FROM BKD.BKD_ASESOR_DOSEN
				WHERE BKD.KD_DOSEN = '$a' AND BKD.KD_TA = '$b' AND BKD.KD_SMT = '$c'";
		foreach($this->db->query($sql)->result_array() as $data);
		return $data['JUMLAH'];
	}

	
	function get_dekan_fakultas($kd){
		$sql = "SELECT * FROM BKD.BKD_MD_FAK
				LEFT JOIN SIA.MASTER_FAK ON BKD_MD_FAK.KD_FAK = MASTER_FAK.KD_FAK
				WHERE BKD_MD_FAK.KD_FAK = '$kd'";
		return $this->db->query($sql)->result_array();
	}
	
	function generate_nama($kd){
		$kode = $this->security->xss_clean($kd);
		$sql = "SELECT NM_DOSEN_F FROM SIA.V_DOSEN WHERE KD_DOSEN = '$kode'";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['NM_DOSEN_F'];
	}
	
	# count record function
	function tot_record($table_name){
		$sql = "SELECT COUNT(*) AS TOTAL FROM ".$table_name;
		foreach ($this->db->query($sql)->result() as $data);
		return $data->TOTAL;
	}
	
	#pencarian dengan parameter nama 
	function cari_dosen_bkd($keyword){
		$sql = "SELECT A.KD_DOSEN, A.KD_PRODI, A.KD_FAK, A.NO_SERTIFIKAT, A.KD_JENIS_DOSEN, B.NIP, B.NM_DOSEN FROM BKD.BKD_DOSEN A 
				LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN WHERE B.NM_DOSEN LIKE '%".$keyword."%' ORDER BY B.NM_DOSEN";
		return $this->db->query($sql)->result_array();
	}	
	
	function cari_dosen($keyword, $subkey){
		$sql = "SELECT A.KD_DOSEN, A.KD_JENIS_DOSEN, B.NM_DOSEN, B.NIP, A.NO_SERTIFIKAT
				FROM BKD.BKD_DOSEN A LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN
				WHERE $subkey LIKE '%$keyword%' ORDER BY B.NM_DOSEN";
		return $this->db->query($sql)->result_array();
	}	

	
	# pagination model API configuration =========================================================================================================
	# this function equivalen with limit in mysql coy 
	function query_limit($sql = '', $start = 1, $offset = false){
	
		if($offset != false){
			//$start += $offset-1;
		}
		
		$sql1 = "SELECT * FROM (SELECT IQRY.*, ROWNUM IQR_NUM FROM (".$sql.") IQRY WHERE ROWNUM <= ".$start.")";
		
		if($offset != false){
			$sql1 .= " WHERE IQR_NUM >= ".$offset;
		}
		
		return $sql1;
	}
 
	function get_dosen_limit($start = 1, $offset = ''){
		$sql = $this->query_limit("SELECT A.KD_DOSEN, A.KD_PRODI, A.KD_FAK, A.NO_SERTIFIKAT, A.KD_JENIS_DOSEN, B.NIP, B.NM_DOSEN FROM BKD.BKD_DOSEN A 
				LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN ORDER BY B.NM_DOSEN",$start, $offset);
		return $this->db->query($sql)->result_array();
	}	
	
	# 24/02/2014 => NGECEK FAKULTAS DOSEN
	# ===========================================================================================
	function update_fak_dosen($kd, $a){
		$sql = "UPDATE BKD.BKD_DOSEN SET KD_FAK = '$a' WHERE KD_DOSEN = '$kd'";
		return $this->db->query($sql);
	}
	
	# 06/03/2014 => KOMPILASI 
	function cek_kompilasi($kd_dosen, $thn){
		$sql = "SELECT COUNT(*) AS CEK FROM BKD.BKD_KOMPILASI WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$thn'";
		foreach($this->db->query($sql)->result_array() as $data);
		if ($data['CEK'] == 0){
			$sql2 = "INSERT INTO BKD.BKD_KOMPILASI (KD_DOSEN, THN_AKADEMIK) VALUES ('$kd_dosen','$thn')";
			return $this->db->query($sql2);
		}else{
			return false;
		}
	}
	
	# KUMPULAN FUNGSI ATRIBUT
	/*function get_nm_prodi($kd){
		$sql = "SELECT NM_PRODI FROM SIA.MASTER_PRODI WHERE KD_PRODI = '$kd'";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['NM_PRODI'];
	}
	
	function get_nm_fakultas($kd){
		$sql = "SELECT NM_FAK FROM SIA.MASTER_FAK WHERE KD_FAK = '$kd'";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['NM_FAK'];
	}
	
	function update_alamat_dosen($kd_dosen, $alamat){
		$sql = "UPDATE SIA.D_DOSEN SET ALMT_RUMAH = '$alamat' WHERE KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql);
	}*/
	
	# 16/04/2014
	# ============================================
	
	function get_pendidikan($kd_dosen, $jenjang){
		$sql = "SELECT * FROM BKD.BKD_RIWAYAT_PENDIDIKAN WHERE KD_DOSEN = '$kd_dosen' AND JENJANG = '$jenjang'";
		return $this->db->query($sql)->result_array();
	}
	
	function get_partner($kode, $jenis){
		$sql = "SELECT PARTNER FROM BKD.BKD_PARTNER_KINERJA WHERE KD_KINERJA = '$kode' AND JENIS_KINERJA = '$jenis'";
		return $this->db->query($sql)->result_array();
	}
	
	# 05/09/2014
	# ============================================
	
	function get_sks_maks_jabatan($kd_jabatan){
		$sql = "SELECT * FROM BKD.BKD_SKS_MAKS_JABATAN WHERE KD_JAB = '$kd_jabatan'";
		return $this->db->query($sql)->result_array();	
	}
	
	function cari_nama_asesor($keyword){
		$sql = "SELECT * FROM BKD.BKD_ASESOR WHERE LOWER(NM_ASESOR) LIKE LOWER('%$keyword%') OR LOWER(NM_PT) LIKE LOWER('%$keyword%')";
		return $this->db->query($sql)->result_array();
	}
	
	
	
	
	
	
}
