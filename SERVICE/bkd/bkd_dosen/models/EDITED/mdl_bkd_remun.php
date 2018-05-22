<?php if (!defined('BASEPATH')) exit();
	
class Mdl_bkd_remun extends CI_Model{
		
	function __construct(){
		parent::__construct();
		$this->bkd	= $this->load->database('bkd', TRUE);
	}
	
	function data_remun($jenis,$kd_dosen, $thn, $smt, $status_pakai=1){
		if($jenis == 'F'){
			$sql = "SELECT 
					A.KD_BK, 
					A.KD_JBK, 
					A.KD_DOSEN, 
					A.JENIS_KEGIATAN, 
					A.BKT_PENUGASAN, 
					A.SKS_PENUGASAN, 
					A.MASA_PENUGASAN, 
					A.BKT_DOKUMEN, 
					A.SKS_BKT, 
					A.THN_AKADEMIK, 
					A.SEMESTER, 
					A.REKOMENDASI,
					B.BT_MULAI, B.BT_SELESAI, B.LAMAN_PUBLIKASI, B.LOKASI_ACARA, B.JUDUL_ACARA
					FROM BKD.BKD_REMUN_KINERJA A LEFT JOIN BKD.BKD_DATA_NARASUMBER B ON A.KD_BK = B.KD_BK
					WHERE A.KD_JBK = '$jenis' AND A.KD_DOSEN = '$kd_dosen'
					AND A.THN_AKADEMIK = '$thn' AND A.SEMESTER = '$smt' AND A.STATUS_PAKAI = '$status_pakai' ORDER BY KD_BK ASC";
		}else{
			$sql = "SELECT 
					KD_BK, 
					KD_JBK, 
					KD_DOSEN, 
					JENIS_KEGIATAN, 
					BKT_PENUGASAN, 
					SKS_PENUGASAN, 
					MASA_PENUGASAN, 
					BKT_DOKUMEN, 
					SKS_BKT, 
					THN_AKADEMIK, 
					SEMESTER, 
					REKOMENDASI,
					STATUS_PENUGASAN,
					STATUS_CAPAIAN,
					STATUS
					FROM BKD.BKD_REMUN_KINERJA 
					WHERE KD_JBK = '$jenis' AND KD_DOSEN = '$kd_dosen'
					AND THN_AKADEMIK = '$thn' AND SEMESTER = '$smt' AND STATUS_PAKAI = '$status_pakai' ORDER BY KD_BK ASC"; //FROM BKD.BKD_REMUN_KINERJA
			}
		return $this->db->query($sql)->result_array();
	}
	
	function get_data($kode, $jbk){
		if ($jbk == 'B'){
			$sql = "SELECT A.*, B.JUDUL_PENELITIAN, TO_CHAR(B.BT_MULAI,'DD/MM/YYYY') AS BT_MULAI, TO_CHAR(B.BT_SELESAI,'DD/MM/YYYY') AS BT_SELESAI, 
					B.STATUS_PENELITI, B.JUMLAH_DANA, B.SUMBER_DANA, B.KD_KAT, B.LAMAN_PUBLIKASI, C.NM_KAT FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENELITIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					WHERE A.KD_BK = '$kode'";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'G'){
			$sql = "SELECT A.*, B.JUDUL_PUBLIKASI, B.PADA, TO_CHAR(B.TANGGAL_PUB,'DD/MM/YYYY') AS TANGGAL_SK, 
					B.PENERBIT, B.TINGKAT, B.KD_DP
					FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PUBLIKASI B ON A.KD_BK = B.KD_BK
					WHERE A.KD_BK = '$kode'";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'H'){
			$sql = "SELECT A.*, B.JUDUL_HAKI, B.NOMOR_SK, TO_CHAR(B.TANGGAL_SK,'DD/MM/YYYY') AS TANGGAL_SK, 
					B.PENERBIT_SK, B.PEMILIK_HAK, B.TINGKAT, B.KD_HAKI, D.JENIS_HAKI,B.KD_JENIS_HAKI
					FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.HAKI B ON A.KD_BK = B.KD_BK
					LEFT JOIN BKD.JENIS_HAKI D ON B.KD_JENIS_HAKI = D.KD_JENIS_HAKI
					WHERE A.KD_BK = '$kode'";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'C'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENGABDIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					WHERE A.KD_BK = '$kode'";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'A'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENDIDIKAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					WHERE A.KD_BK = '$kode'";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'F'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_NARASUMBER B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_TINGKAT_KEGIATAN C ON B.KD_TINGKAT = C.KD_TINGKAT 
					WHERE A.KD_BK = '$kode'";
			return $this->db->query($sql)->result_array();
		}else{
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA WHERE KD_BK = '$kode'";
			return $this->db->query($sql)->result_array();
		}
	}
	function tingkatKegiatan(){
		$sql = "SELECT * FROM BKD.BKD_TINGKAT_KEGIATAN ORDER BY KD_TINGKAT ASC";
		return $this->db->query($sql)->result_array();
	}
	function get_kategori($kode){
		$sql = "SELECT * FROM BKD.BKD_KATEGORI_KEG WHERE KD_JBK = '$kode' ORDER BY KD_KAT ASC";
		return $this->db->query($sql)->result_array();
	}
	function get_jenis_haki(){
		$sql = "SELECT * FROM JENIS_HAKI ORDER BY KD_JENIS_HAKI ASC";
		return $this->db->query($sql)->result_array();
	}

	function simpan_remun($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $q, $fp, $fc, $aa, $bb){
		$sts_remun = 1; $sts_serdos = 0;
		$sql = "INSERT INTO BKD.BKD_REMUN_KINERJA (KD_JBK, KD_DOSEN, JENIS_KEGIATAN, BKT_PENUGASAN, SKS_PENUGASAN, MASA_PENUGASAN, BKT_DOKUMEN, SKS_BKT, 
				THN_AKADEMIK, SEMESTER, REKOMENDASI, JML_JAM, CAPAIAN, OUTCOME, FILE_PENUGASAN, FILE_CAPAIAN, KD_TA, KD_SMT, STATUS_PAKAI)
				VALUES ('$a','$b','$c','$d','$e','$f','$g','$h','$i','$j','$k','$l','$m','$q', '$fp', '$fc', '$aa', '$bb', '$sts_remun')"; // : TAMBAH STATUS_PAKAI (1)
		$this->db->query($sql);

		$sql2 = "INSERT INTO BKD.BKD_BEBAN_KERJA (KD_JBK, KD_DOSEN, JENIS_KEGIATAN, BKT_PENUGASAN, SKS_PENUGASAN, MASA_PENUGASAN, BKT_DOKUMEN, SKS_BKT, 
				THN_AKADEMIK, SEMESTER, REKOMENDASI, JML_JAM, CAPAIAN, OUTCOME, FILE_PENUGASAN, FILE_CAPAIAN, KD_TA, KD_SMT, STATUS_PAKAI)
				VALUES ('$a','$b','$c','$d','$e','$f','$g','$h','$i','$j','$k','$l','$m','$q', '$fp', '$fc', '$aa', '$bb', '$sts_serdos')"; // : TAMBAH STATUS_PAKAI (1)
		$this->db->query($sql2);

		return $this->trigger_kompilasi($a, $b, $j, $i);
	}
	function get_current_data_tersimpan($table, $kolom){
		$sql = "SELECT MAX($kolom) AS KD_BK FROM $table";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['KD_BK'];
	}
	function simpan_data_pendidikan($a, $b, $c, $d, $e, $f){
		$sql = "INSERT INTO BKD.BKD_DATA_PENDIDIKAN (KD_BK, KD_KAT, NM_KEGIATAN, JENJANG, TEMPAT, JML_MHS) VALUES ('$a', '$b', '$c', '$d', '$e', '$f')";
		return $this->db->query($sql);
	}
	function simpan_data_penelitian($a, $c, $d, $e, $g, $h, $i, $j, $k){
		$sql = "INSERT INTO BKD.BKD_DATA_PENELITIAN (KD_BK, BT_MULAI, BT_SELESAI, JUDUL_PENELITIAN, SUMBER_DANA, JUMLAH_DANA, KD_KAT, STATUS_PENELITI, LAMAN_PUBLIKASI) 
				VALUES ('$a', TO_DATE('$c','DD/MM/YYYY'), TO_DATE('$d','DD/MM/YYYY'), '$e','$g','$h','$i','$j','$k')";
		return $this->db->query($sql);
	}
	# PARTNER KINERJA :: 18/04/2014
	# ==========================================================================
	function simpan_partner($kd, $jenis, $kd_dosen, $arr_partner){
		$sql = "INSERT INTO BKD.BKD_PARTNER_KINERJA VALUES ('$kd','$jenis','$kd_dosen','$arr_partner')";
		return $this->db->query($sql);
	}
	
	function hapus_partner($kode, $jenis, $partner){
		$kd = $this->security->xss_clean($partner);
		$sql ="DELETE FROM BKD.BKD_PARTNER_KINERJA WHERE KD_KINERJA = '$kode' AND JENIS_KINERJA = '$jenis' AND PARTNER = '$kd'";
		return $this->db->query($sql);
	}
	function simpan_data_pengabdian($a, $c, $d, $e, $f, $g, $h){
		$sql = "INSERT INTO BKD.BKD_DATA_PENGABDIAN (KD_BK, BT_MULAI, BT_SELESAI, JUDUL_PENGABDIAN, SUMBER_DANA, JUMLAH_DANA, KD_KAT) 
				VALUES ('$a', TO_DATE('$c','DD/MM/YYYY'), TO_DATE('$d','DD/MM/YYYY'), '$e','$f','$g','$h')";
		return $this->db->query($sql);
	}
	function current_data_haki($kd){
		$sql = "SELECT A.KD_HAKI, A.KD_DOSEN,  A.JUDUL_HAKI, A.TINGKAT, A.NOMOR_SK, A.TANGGAL_SK, A.PENERBIT_SK, A.PEMILIK_HAK,A.KD_JENIS_HAKI,
				A.KD_BK
				FROM HAKI A
				LEFT JOIN JENIS_HAKI JH ON JH.KD_JENIS_HAKI=A.KD_JENIS_HAKI
				WHERE A.KD_HAKI = '$kd'";
		return $this->db->query($sql)->row_array();
	}
	# DATA SEMUA DOSEN 
	function get_all_dosen(){
		$sql = "SELECT A.KD_DOSEN, A.NIP, B.NM_PRODI, A.NM_DOSEN FROM SIA.D_DOSEN A LEFT JOIN SIA.MASTER_PRODI B ON A.KD_PRODI = B.KD_PRODI";
		return $this->db->query($sql)->result_array();
	}
	function data_kegiatan_akademik_dosen($kd_dosen){
		$sql = "SELECT * FROM BKD.BKD_KEGIATAN_AKADEMIK A LEFT JOIN BKD.BKD_KATEGORI_KEG B ON A.KD_KAT = B.KD_KAT 
				WHERE A.KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql)->result_array();
	}
	
	function current_kegiatan_akademik($kd_kegiatan){
		$sql = "SELECT * FROM BKD.BKD_KEGIATAN_AKADEMIK A LEFT JOIN BKD.BKD_KATEGORI_KEG B ON A.KD_KAT = B.KD_KAT 
				WHERE A.KD_KA = '$kd_kegiatan'";
		return $this->db->query($sql)->result_array();
	}
	
	function simpan_kegiatan_akademik($data){
		return $this->db->insert('BKD.BKD_KEGIATAN_AKADEMIK', $data);
	}
	
	function update_kegiatan_akademik($kd, $data){
		$this->db->where('KD_KA', $kd);
		return $this->db->update('BKD.BKD_KEGIATAN_AKADEMIK', $data);
	}

	function delete_kegiatan_akademik($id){
		$this->db->where('KD_KA', $id);
		return $this->db->delete('BKD.BKD_KEGIATAN_AKADEMIK');
	}
	# NARASUMBER/PEMBICARA
	function data_narasumber_dosen($kd_dosen){
		$sql = "SELECT * FROM BKD.BKD_DATA_NARASUMBER A LEFT JOIN BKD.BKD_TINGKAT_KEGIATAN B ON A.KD_TINGKAT = B.KD_TINGKAT 
				WHERE A.KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql)->result_array();
	}

	function data_narasumber_dosen_semester($jenis,$kd_dosen, $thn, $smt){
		$sql = "SELECT 
				A.KD_BK, 
				A.KD_JBK, 
				A.KD_DOSEN, 
				A.JENIS_KEGIATAN, 
				A.BKT_PENUGASAN, 
				A.SKS_PENUGASAN, 
				A.MASA_PENUGASAN, 
				A.BKT_DOKUMEN, 
				A.SKS_BKT, 
				A.THN_AKADEMIK, 
				A.SEMESTER, 
				A.REKOMENDASI,
				B.BT_MULAI, B.BT_SELESAI, B.LAMAN_PUBLIKASI, B.LOKASI_ACARA, B.JUDUL_ACARA
				FROM BKD.BKD_BEBAN_KERJA A LEFT JOIN BKD.BKD_DATA_NARASUMBER B ON A.KD_BK = B.KD_BK
				WHERE A.KD_JBK = '$jenis' AND A.KD_DOSEN = '$kd_dosen'
				AND A.KD_TA = '$thn' AND A.KD_SMT = '$smt' ORDER BY KD_BK ASC";		
		return $this->db->query($sql)->result_array();
	}
	
	function current_narasumber($kd_kegiatan){
		$sql = "SELECT * FROM BKD.BKD_DATA_NARASUMBER A LEFT JOIN BKD.BKD_TINGKAT_KEGIATAN B ON A.KD_TINGKAT = B.KD_TINGKAT 
				WHERE A.KD_NP = '$kd_kegiatan'";
		return $this->db->query($sql)->result_array();
	}
	
	function simpan_narasumber($data){
		return $this->db->insert('BKD.BKD_DATA_NARASUMBER', $data);
	}
	
	function update_narasumber($kd, $data){
		$this->db->where('KD_NP', $kd);
		return $this->db->update('BKD.BKD_DATA_NARASUMBER', $data);
	}
	function delete_narasumber($id){
		$this->db->where('KD_NP', $id);
		return $this->db->delete('BKD.BKD_DATA_NARASUMBER');
	}
	
	function data_narasumber_semester_limit($ta, $smt, $limit, $start){
		$sql = "SELECT * FROM (SELECT R.*, ROWNUM AS ROW_NUMBER FROM 
				(SELECT 
				A.KD_BK, 
				A.KD_JBK, 
				A.KD_DOSEN, 
				A.JENIS_KEGIATAN, 
				A.BKT_PENUGASAN, 
				A.SKS_PENUGASAN, 
				A.MASA_PENUGASAN, 
				A.BKT_DOKUMEN, 
				A.SKS_BKT, 
				A.THN_AKADEMIK, 
				A.SEMESTER, 
				A.REKOMENDASI,
				B.BT_MULAI, B.BT_SELESAI, B.LAMAN_PUBLIKASI, B.LOKASI_ACARA, B.JUDUL_ACARA
				FROM BKD.BKD_BEBAN_KERJA A LEFT JOIN BKD.BKD_DATA_NARASUMBER B ON A.KD_BK = B.KD_BK
				WHERE A.KD_JBK = 'F' AND A.THN_AKADEMIK = '$ta' AND A.SEMESTER = '$smt' ORDER BY KD_BK ASC) 
				R WHERE ROWNUM <= ".$limit.") WHERE ".$start." < ROW_NUMBER ";
		return $this->db->query($sql)->result_array();
	}
	function count_kegiatan_akademik(){
		$sql = "SELECT COUNT(*) AS JML FROM BKD.BKD_KEGIATAN_AKADEMIK";
		foreach($this->db->query($sql)->result_array() as $data);
		return $data['JML'];
	}
	
	function current_kegiatan_akademik_semua_dosen_limit($limit, $start){
		#$sql = "SELECT * FROM BKD.BKD_KEGIATAN_AKADEMIK ORDER BY KD_DOSEN, JUDUL_KEGIATAN ASC";
		$sql = "SELECT * FROM (SELECT R.*, ROWNUM AS ROW_NUMBER FROM 
				(SELECT * FROM BKD.BKD_KEGIATAN_AKADEMIK ORDER BY KD_DOSEN, JUDUL_KEGIATAN ASC) 
				R WHERE ROWNUM <= ".$limit.") WHERE ".$start." < ROW_NUMBER ";
		return $this->db->query($sql)->result_array();
	}
	function simpan_data_publikasi($a, $b, $c, $d, $e, $f, $g, $thn, $smt, $kd_ta, $kd_smt){
		$sql = "INSERT INTO BKD.BKD_DATA_PUBLIKASI (KD_DOSEN, JUDUL_PUBLIKASI, PADA, TINGKAT, TANGGAL_PUB, PENERBIT, AKREDITASI, THN_AKADEMIK, SEMESTER, KD_TA, KD_SMT) 
				VALUES ('$a','$b','$c','$d', TO_DATE('$e','DD/MM/YYYY'),'$f','$g','$thn','$smt', $kd_ta, $kd_smt) ";
		return $this->db->query($sql);
	}
	function simpan_data_haki($param){
		$sql = "INSERT INTO BKD.HAKI (KD_BK,KD_DOSEN, JUDUL_HAKI,KD_JENIS_HAKI,TINGKAT,NOMOR_SK,TANGGAL_SK,PENERBIT_SK,PEMILIK_HAK) 
				VALUES ('".$param['KD_BK']."','".$param['KD_DOSEN']."','".$param['JUDUL_HAKI']."','".$param['KD_JENIS_HAKI']."','".$param['TINGKAT']."','".$param['NOMOR_SK']."',TO_DATE('".$param['TANGGAL_SK']."','DD/MM/YYYY'),'".$param['PENERBIT_SK']."','".$param['PEMILIK_HAK']."') ";
		return $this->db->query($sql);
	}
	function update_remun($kode, $c, $d, $e, $f, $g, $h, $i, $l, $p, $q, $kd_jbk, $kd_dosen, $smt, $tahun, $fp, $fc){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET JENIS_KEGIATAN = '$c', BKT_PENUGASAN = '$d', SKS_PENUGASAN = '$e',
				MASA_PENUGASAN = '$f', BKT_DOKUMEN = '$g', SKS_BKT = '$h', REKOMENDASI = '$i',
				JML_JAM = '$l', CAPAIAN = '$p', OUTCOME = '$q', FILE_PENUGASAN = '$fp', FILE_CAPAIAN = '$fc' WHERE KD_BK = '$kode'";
		$this->db->query($sql);
		return $this->trigger_kompilasi($kd_jbk, $kd_dosen, $smt, $tahun);
	}
	
	function update_data_pendidikan($a, $b, $c, $d, $e, $f){
		$sql = "UPDATE BKD.BKD_DATA_PENDIDIKAN SET KD_KAT = '$b', NM_KEGIATAN = '$c', JENJANG = '$d', TEMPAT = '$e', JML_MHS = '$f' WHERE KD_BK = '$a'";
		return $this->db->query($sql);
	}
	function update_data_penelitian($a, $c, $d, $e, $g, $h, $i, $j, $k){
		$sql = "UPDATE BKD.BKD_DATA_PENELITIAN SET BT_MULAI = TO_DATE('$c', 'DD/MM/YYYY'), BT_SELESAI = TO_DATE('$d', 'DD/MM/YYYY'), JUDUL_PENELITIAN = '$e',
				SUMBER_DANA = '$g', JUMLAH_DANA = '$h', KD_KAT = '$i', STATUS_PENELITI = '$j', LAMAN_PUBLIKASI = '$k' WHERE KD_BK = '$a' ";
		return $this->db->query($sql);
	}
	function update_data_pengabdian($a, $c, $d, $e, $g, $h, $i){
		$sql = "UPDATE BKD.BKD_DATA_PENGABDIAN SET BT_MULAI = TO_DATE('$c', 'DD/MM/YYYY'), BT_SELESAI = TO_DATE('$d', 'DD/MM/YYYY'), JUDUL_PENGABDIAN = '$e',
				SUMBER_DANA = '$g', JUMLAH_DANA = '$h', KD_KAT = '$i' WHERE KD_BK = '$a' ";
		return $this->db->query($sql);
	}
	function update_data_publikasi($kd_dp, $b, $c, $d, $e, $f, $g){
		$sql = "UPDATE BKD.BKD_DATA_PUBLIKASI SET JUDUL_PUBLIKASI = '$b', PADA = '$c', TINGKAT = '$d', TANGGAL_PUB = TO_DATE('$e','DD/MM/YYYY'), 
				PENERBIT = '$f', AKREDITASI = '$g' WHERE KD_DP = '$kd_dp' ";
		return $this->db->query($sql);
	}
	function update_data_haki($param){
		$sql = "UPDATE HAKI SET 
				JUDUL_HAKI = '".$param['JUDUL_HAKI']."',
				KD_JENIS_HAKI = '".$param['KD_JENIS_HAKI']."',
				TINGKAT = '".$param['TINGKAT']."',
				NOMOR_SK = '".$param['NOMOR_SK']."',
				TANGGAL_SK = TO_DATE('".$param['TANGGAL_SK']."','DD/MM/YYYY'),
				PENERBIT_SK = '".$param['PENERBIT_SK']."',
				PEMILIK_HAK = '".$param['PEMILIK_HAK']."'
				WHERE KD_HAKI = '".$param['KD_HAKI']."' ";
		return $this->db->query($sql);
	}
	function hapus_remun($id, $kode, $kd_dosen, $smt, $tahun){
		$sqlx = "DELETE FROM BKD.BKD_PARTNER_KINERJA WHERE KD_KINERJA = '$id'";
		$sqly = "DELETE FROM BKD.BKD_DATA_NARASUMBER WHERE KD_BK = '$id'";
		$sql = "DELETE FROM BKD.BKD_DATA_PENELITIAN WHERE KD_BK = '$id'";
		$sql2 = "DELETE FROM BKD.BKD_DATA_PENGABDIAN WHERE KD_BK = '$id'";
		$sql3 = "DELETE FROM BKD.BKD_DATA_PENDIDIKAN WHERE KD_BK = '$id'";
		$sql4 = "DELETE FROM BKD.BKD_BEBAN_KERJA WHERE KD_BK = '$id'";
		$sql5 = "DELETE FROM BKD.HAKI WHERE KD_BK = '$id'";
		$this->db->query($sqlx);
		$this->db->query($sqly);
		$this->db->query($sql);
		$this->db->query($sql2);
		$this->db->query($sql3);
		$this->db->query($sql4);
		$this->db->query($sql5);
		return $this->trigger_kompilasi($kode, $kd_dosen, $smt, $tahun);
	}
	# file upload tambahan 04/Marc/2014
	function update_bkt_001($id, $isi){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET BKT_PENUGASAN = '$isi' WHERE KD_BK = '$id'";
		return $this->db->query($sql);		
	}

	function update_bkt_002($id, $isi, $url){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET BKT_PENUGASAN = '$isi', FILE_PENUGASAN = '$url' WHERE KD_BK = '$id'";
		return $this->db->query($sql);		
	}

	function update_bkt_003($id, $isi){
		$sql = "UPDATE BKD.BKD_BEBAN_KHUSUS_PROF SET BKT_PENUGASAN = '$isi' WHERE KD_BKP = '$id'";
		return $this->db->query($sql);		
	}

	function update_bkt_004($id, $isi, $url){
		$sql = "UPDATE BKD.BKD_BEBAN_KHUSUS_PROF SET BKT_PENUGASAN = '$isi', FILE_PENUGASAN = '$url' WHERE KD_BKP = '$id'";
		return $this->db->query($sql);
	}

	function update_kinerja_001($id, $isi){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET BKT_DOKUMEN = '$isi' WHERE KD_BK = '$id'";
		return $this->db->query($sql);		
	}

	function update_kinerja_002($id, $isi, $url){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET BKT_DOKUMEN = '$isi', FILE_CAPAIAN = '$url' WHERE KD_BK = '$id'";
		return $this->db->query($sql);		
	}
	
	function update_kinerja_003($id, $isi){
		$sql = "UPDATE BKD.BKD_BEBAN_KHUSUS_PROF SET BKT_DOKUMEN = '$isi' WHERE KD_BKP = '$id'";
		return $this->db->query($sql);		
	}

	function update_kinerja_004($id, $isi, $url){
		$sql = "UPDATE BKD.BKD_BEBAN_KHUSUS_PROF SET BKT_DOKUMEN = '$isi', FILE_CAPAIAN = '$url' WHERE KD_BKP = '$id'";
		return $this->db->query($sql);		
	}
	# update data backdoor proces 
	function update_per_cell_bkd($kode, $kolom, $value){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET $kolom = '$value' WHERE KD_BK = '$kode'";
		return $this->db->query($sql);
	}
	
	function update_per_cell_rbkd($kode, $kolom, $value){
		$sql = "UPDATE BKD.RBKD SET $kolom = '$value' WHERE KD_RBK = '$kode'";
		return $this->db->query($sql);
	}
	
	# update data backdoor proces 
	function update_per_cell_bkd_prof($kode, $kolom, $value){
		$sql = "UPDATE BKD.BKD_BEBAN_KHUSUS_PROF SET $kolom = '$value' WHERE KD_BKP = '$kode'";
		return $this->db->query($sql);
	}
	
	# update data backdoor proces 
	function update_per_cell_publikasi($kode, $kolom, $value){
		$sql = "UPDATE BKD.BKD_DATA_PUBLIKASI SET $kolom = '$value' WHERE KD_DP = '$kode'";
		return $this->db->query($sql);
	}
	/* data history beban profesor */
	function history_data_bebankerja_prof($kd_dosen, $thn, $smt){
		$sql = "SELECT * FROM BKD.BKD_BEBAN_KHUSUS_PROF
				LEFT JOIN BKD.BKD_JENIS_KEG ON BKD.BKD_BEBAN_KHUSUS_PROF.KD_JENIS_KEG = BKD.BKD_JENIS_KEG.KD_JENIS_KEG
				WHERE BKD.BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$thn' AND SEMESTER = '$smt'";
		return $this->db->query($sql)->result_array();
	}
	function get_beban_prof($kode){
		$sql = "SELECT * FROM BKD.BKD_BEBAN_KHUSUS_PROF 
				LEFT JOIN BKD.BKD_JENIS_KEG ON BKD.BKD_BEBAN_KHUSUS_PROF.KD_JENIS_KEG = BKD.BKD_JENIS_KEG.KD_JENIS_KEG
				LEFT JOIN BKD.BKD_KATEGORI_KEG ON BKD.BKD_BEBAN_KHUSUS_PROF.KD_KAT = BKD.BKD_KATEGORI_KEG.KD_KAT
				WHERE KD_BKP = '$kode'";
		return $this->db->query($sql)->result_array();
	}

	function get_poin_remun($kd_jbr, $jenjang, $kelas, $jabatan, $semester, $kategori){
		$poin = 0;
		if($kd_jbr == 'A'){
			$sql  = "SELECT POIN FROM BKD.BKD_REMUN_POIN_PENDIDIKAN
					WHERE UPPER(JENJANG) = UPPER('$jenjang') AND UPPER(KELAS) = UPPER('$kelas')
					AND UPPER(JABATAN) = UPPER('$jabatan') AND UPPER(SEMESTER) = UPPER('$semester')
					AND KATEGORI = '$kategori' ";
			$q    = $this->db->query($sql)->row_array();
			$poin = $q['POIN']; 
		}
		
		return $poin;
	}
}
