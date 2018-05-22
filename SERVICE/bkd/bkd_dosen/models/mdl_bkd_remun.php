<?php if (!defined('BASEPATH')) exit();
	
class Mdl_bkd_remun extends CI_Model{
		
	function __construct(){
		parent::__construct();
		$this->bkd	= $this->load->database('bkd', TRUE);
	}
	
	function data_remun($jenis,$kd_dosen, $thn, $smt){
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
					AND A.THN_AKADEMIK = '$thn' AND A.SEMESTER = '$smt' AND A.STATUS_PAKAI = 1 ORDER BY KD_BK ASC";		
		}elseif($jenis=='D'){
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
					A.STATUS_PENUGASAN,
					A.STATUS_CAPAIAN,
					A.STATUS,
					B.JENJANG, B.TEMPAT, B.JML_MHS, B.JML_SKS, B.JML_TM, B.KELAS, B.JML_PERTEMUAN_PM, B.JML_DOSEN, B.NM_PRODI, B.KD_KAT, B.SATUAN, B.STATUS_PINDAH
					FROM BKD.BKD_REMUN_KINERJA A JOIN BKD.BKD_DATA_PENUNJANG B ON A.KD_BK = B.KD_BK
					WHERE A.KD_JBK = '$jenis' AND A.KD_DOSEN = '$kd_dosen'
					AND A.THN_AKADEMIK = '$thn' AND A.SEMESTER = '$smt' AND A.STATUS_PAKAI = 1 ORDER BY A.KD_BK ASC";
		}elseif($jenis=='B'){
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
					A.STATUS_PENUGASAN,
					A.STATUS_CAPAIAN,
					A.STATUS,
					B.JENJANG, B.TEMPAT, B.JML_MHS, B.JML_SKS, B.JML_TM, B.KELAS, B.JML_PERTEMUAN_PM, B.JML_DOSEN, B.NM_PRODI, B.KD_KAT, B.SATUAN, B.STATUS_PINDAH
					FROM BKD.BKD_REMUN_KINERJA A JOIN BKD.BKD_DATA_PENELITIAN B ON A.KD_BK = B.KD_BK
					WHERE A.KD_JBK = '$jenis' AND A.KD_DOSEN = '$kd_dosen'
					AND A.THN_AKADEMIK = '$thn' AND A.SEMESTER = '$smt' AND A.STATUS_PAKAI = 1 ORDER BY A.KD_BK ASC";
		}else{
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
					A.STATUS_PENUGASAN,
					A.STATUS_CAPAIAN,
					A.STATUS,
					B.JENJANG, B.TEMPAT, B.JML_MHS, B.JML_SKS, B.JML_TM, B.KELAS, B.JML_PERTEMUAN_PM, B.JML_DOSEN, B.NM_PRODI, B.KD_KAT, B.SATUAN, B.STATUS_PINDAH
					FROM BKD.BKD_REMUN_KINERJA A JOIN BKD.BKD_DATA_PENDIDIKAN B ON A.KD_BK = B.KD_BK
					WHERE A.KD_JBK = '$jenis' AND A.KD_DOSEN = '$kd_dosen'
					AND A.THN_AKADEMIK = '$thn' AND A.SEMESTER = '$smt' AND A.STATUS_PAKAI = 1 ORDER BY A.KD_BK ASC";
			}
		return $this->db->query($sql)->result_array();
	}
	
	function get_data($kode, $jbk){
		if ($jbk == 'B'){
			$sql = "SELECT * FROM BKD.BKD_REMUN_KINERJA A 
					LEFT JOIN BKD.BKD_DATA_PENELITIAN B ON A.KD_BK = B.KD_BK JOIN BKD.BKD_REMUN_KATEGORI_KEGIATAN C ON B.KD_KAT = C.KD_KAT/*JOIN BKD.BKD_REMUN_KONVERSI_KAT D ON B.KD_KAT = D.KD_KAT
					LEFT OUTER JOIN BKD.BKD_REMUN_KATEGORI_KEGIATAN C ON D.KD_KAT_REMUN = C.KD_KAT*/ 
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
			$sql = "SELECT * FROM BKD.BKD_REMUN_KINERJA A 
					LEFT JOIN BKD.BKD_DATA_PENDIDIKAN B ON A.KD_BK = B.KD_BK JOIN BKD.BKD_REMUN_KONVERSI_KAT D ON B.KD_KAT = D.KD_KAT
					LEFT OUTER JOIN BKD.BKD_REMUN_KATEGORI_KEGIATAN C ON D.KD_KAT_REMUN = C.KD_KAT 
					WHERE A.KD_BK = '$kode'";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'D'){
			$sql = "SELECT * FROM BKD.BKD_REMUN_KINERJA A 
					LEFT JOIN BKD.BKD_DATA_PENUNJANG B ON A.KD_BK = B.KD_BK JOIN BKD.BKD_REMUN_KATEGORI_KEGIATAN C ON B.KD_KAT = C.KD_KAT/*JOIN BKD.BKD_REMUN_KONVERSI_KAT D ON B.KD_KAT = D.KD_KAT
					LEFT OUTER JOIN BKD.BKD_REMUN_KATEGORI_KEGIATAN C ON D.KD_KAT_REMUN = C.KD_KAT*/ 
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
	function get_kategori_remun($kode){
		$sql = "SELECT * FROM BKD.BKD_REMUN_KATEGORI_KEGIATAN WHERE KD_JBR = '$kode' ORDER BY KD_KAT ASC";
		return $this->db->query($sql)->result_array();
	}
	function get_jenis_haki(){
		$sql = "SELECT * FROM JENIS_HAKI ORDER BY KD_JENIS_HAKI ASC";
		return $this->db->query($sql)->result_array();
	}
	function simpan_remun($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $q, $fp, $fc, $aa, $bb){

		$kd_bk = $this->get_last_seq_numb();

		$sql = "INSERT INTO BKD.BKD_REMUN_KINERJA (KD_BK, KD_JBK, KD_DOSEN, JENIS_KEGIATAN, BKT_PENUGASAN, SKS_PENUGASAN, MASA_PENUGASAN, BKT_DOKUMEN, SKS_BKT, 
				THN_AKADEMIK, SEMESTER, REKOMENDASI, JML_JAM, CAPAIAN, OUTCOME, FILE_PENUGASAN, FILE_CAPAIAN, KD_TA, KD_SMT, STATUS_PAKAI)
				VALUES ('$kd_bk','$a','$b','$c','$d',".$e.",'$f','$g',".$h.",'$i','$j','$k','$l','$m','$q', '$fp', '$fc', '$aa', '$bb', 1)";
		$this->db->query($sql);

		$sql2 = "INSERT INTO BKD.BKD_BEBAN_KERJA (KD_BK, KD_JBK, KD_DOSEN, JENIS_KEGIATAN, BKT_PENUGASAN, SKS_PENUGASAN, MASA_PENUGASAN, BKT_DOKUMEN, SKS_BKT, 
				THN_AKADEMIK, SEMESTER, REKOMENDASI, JML_JAM, CAPAIAN, OUTCOME, FILE_PENUGASAN, FILE_CAPAIAN, KD_TA, KD_SMT, STATUS_PAKAI)
				VALUES ('$kd_bk','$a','$b','$c','$d',".$e.",'$f','$g',".$h.",'$i','$j','$k','$l','$m','$q', '$fp', '$fc', '$aa', '$bb', 0)";
		return $this->db->query($sql2);

		/*return $this->trigger_kompilasi($a, $b, $j, $i);*/
	}
	function get_current_data_tersimpan($table, $kolom){
		$sql = "SELECT MAX($kolom) AS KD_BK FROM $table";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['KD_BK'];
	}
	function simpan_data_pendidikan($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n){
		$sql = "INSERT INTO BKD.BKD_DATA_PENDIDIKAN (KD_BK, KD_KAT, NM_KEGIATAN, JENJANG, TEMPAT, JML_MHS, JML_SKS, JML_DOSEN, JML_TM, KELAS, JML_PERTEMUAN_PM, NM_PRODI, SATUAN, STATUS_PINDAH) VALUES ('$a', '$b', '$c', '$d', '$e', '$f', '$g', '$h', '$i', '$j', '$k', '$l', '$m', '$n')";
		return $this->db->query($sql);
	}
	function simpan_data_penelitian($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n){
		$sql = "INSERT INTO BKD.BKD_DATA_PENELITIAN (KD_BK, KD_KAT, JUDUL_PENELITIAN, JENJANG, TEMPAT, JML_MHS, JML_SKS, JML_DOSEN, JML_TM, KELAS, JML_PERTEMUAN_PM, NM_PRODI, SATUAN, STATUS_PINDAH, BT_MULAI, BT_SELESAI, SUMBER_DANA, JUMLAH_DANA, STATUS_PENELITI, LAMAN_PUBLIKASI) VALUES ('$a', '$b', '$c', '$d', '$e', '$f', '$g', '$h', '$i', '$j', '$k', '$l', '$m', '$n', '', '', '', '', '', '')";
		return $this->db->query($sql);
	}
	function simpan_data_penunjang($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n){
		$sql = "INSERT INTO BKD.BKD_DATA_PENUNJANG (KD_BK, KD_KAT, NM_KEGIATAN, JENJANG, TEMPAT, JML_MHS, JML_SKS, JML_DOSEN, JML_TM, KELAS, JML_PERTEMUAN_PM, NM_PRODI, SATUAN, STATUS_PINDAH) VALUES ('$a', '$b', '$c', '$d', '$e', '$f', '$g', '$h', '$i', '$j', '$k', '$l', '$m', '$n')";
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
		$sql = "UPDATE BKD.BKD_REMUN_KINERJA SET JENIS_KEGIATAN = '$c', BKT_PENUGASAN = '$d', SKS_PENUGASAN = ".$e.",
				MASA_PENUGASAN = '$f', BKT_DOKUMEN = '$g', SKS_BKT = ".$h.", REKOMENDASI = '$i',
				JML_JAM = '$l', CAPAIAN = '$p', OUTCOME = '$q', FILE_PENUGASAN = '$fp', FILE_CAPAIAN = '$fc' WHERE KD_BK = '$kode'";
		$this->db->query($sql);
		//ditambahi, jadi ketika update data di remun,data yang di sertifikasi dosen juga di update
		$sql2 = "UPDATE BKD.BKD_BEBAN_KERJA SET JENIS_KEGIATAN = '$c', BKT_PENUGASAN = '$d', SKS_PENUGASAN = ".$e.",
				MASA_PENUGASAN = '$f', BKT_DOKUMEN = '$g', SKS_BKT = ".$h.", REKOMENDASI = '$i',
				JML_JAM = '$l', CAPAIAN = '$p', OUTCOME = '$q', FILE_PENUGASAN = '$fp', FILE_CAPAIAN = '$fc' WHERE KD_BK = '$kode'";
		
		
		return $this->db->query($sql2);
		//return $this->trigger_kompilasi($kd_jbk, $kd_dosen, $smt, $tahun);
	}

	function update_status_pakai($kd_bk){
		$sql = "UPDATE BKD.BKD_REMUN_KINERJA SET STATUS_PAKAI = 0 WHERE KD_BK = '$kd_bk'";
		$q   = $this->db->query($sql);

		if($q){
			$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET STATUS_PAKAI = 1 WHERE KD_BK = '$kd_bk'";
			$q   = $this->db->query($sql);
		}

		return $q;
	}
	
	function update_data_pendidikan($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n){
		$sql = "UPDATE BKD.BKD_DATA_PENDIDIKAN SET KD_KAT = '$b', NM_KEGIATAN = '$c', JENJANG = '$d', TEMPAT = '$e', JML_MHS = '$f', JML_SKS='$g', JML_DOSEN='$h', JML_TM='$i', KELAS='$j', JML_PERTEMUAN_PM='$k', NM_PRODI='$l', SATUAN='$m', STATUS_PINDAH='$n' WHERE KD_BK = '$a'";
		return $this->db->query($sql);
	}
	function update_data_penunjang($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n){
		$sql = "UPDATE BKD.BKD_DATA_PENUNJANG SET KD_KAT = '$b', NM_KEGIATAN = '$c', JENJANG = '$d', TEMPAT = '$e', JML_MHS = '$f', JML_SKS='$g', JML_DOSEN='$h', JML_TM='$i', KELAS='$j', JML_PERTEMUAN_PM='$k', NM_PRODI='$l', SATUAN='$m', STATUS_PINDAH='$n' WHERE KD_BK = '$a'";
		return $this->db->query($sql);
	}
	function update_data_penilai_penelitian($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n){
		$sql = "UPDATE BKD.BKD_DATA_PENILAI_PENELITIAN SET KD_KAT = '$b', NM_KEGIATAN = '$c', JENJANG = '$d', TEMPAT = '$e', JML_MHS = '$f', JML_SKS='$g', JML_DOSEN='$h', JML_TM='$i', KELAS='$j', JML_PERTEMUAN_PM='$k', NM_PRODI='$l', SATUAN='$m', STATUS_PINDAH='$n' WHERE KD_BK = '$a'";
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
		/*$sqlx = "DELETE FROM BKD.BKD_PARTNER_KINERJA WHERE KD_KINERJA = '$id'";
		$sqly = "DELETE FROM BKD.BKD_DATA_NARASUMBER WHERE KD_BK = '$id'";
		$sql = "DELETE FROM BKD.BKD_DATA_PENELITIAN WHERE KD_BK = '$id'";
		$sql2 = "DELETE FROM BKD.BKD_DATA_PENGABDIAN WHERE KD_BK = '$id'";*/
		
		if($kode == 'A'){
			$sql3 = "DELETE FROM BKD.BKD_DATA_PENDIDIKAN WHERE KD_BK = '$id'";
			$this->db->query($sql3);
		}elseif($kode=='B'){
			$sql3 = "DELETE FROM BKD.BKD_DATA_PENELITIAN WHERE KD_BK = '$id'";
			$this->db->query($sql3);
		}elseif($kode=='D'){
			$sql3 = "DELETE FROM BKD.BKD_DATA_PENUNJANG WHERE KD_BK = '$id'";
			$this->db->query($sql3);
		}
		$sql4 = "DELETE FROM BKD.BKD_BEBAN_KERJA WHERE KD_BK = '$id'";
		$this->db->query($sql4);

		$sql6 = "DELETE FROM BKD.BKD_REMUN_KINERJA WHERE KD_BK = '$id'";
		return $this->db->query($sql6);
		/*$sql5 = "DELETE FROM BKD.HAKI WHERE KD_BK = '$id'";*/
		/*$this->db->query($sqlx);
		$this->db->query($sqly);
		$this->db->query($sql);
		$this->db->query($sql2);*/
		/*$this->db->query($sql3);
		$this->db->query($sql4);*/
		/*$this->db->query($sql5);*/
		/*return $this->db->query($sql6);*/
		/*return $this->trigger_kompilasi($kode, $kd_dosen, $smt, $tahun);*/
	}
	# file upload tambahan 04/Marc/2014
	function update_bkt_001($id, $isi){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET BKT_PENUGASAN = '$isi' WHERE KD_BK = '$id'";
		return $this->db->query($sql);		
	}

	function update_bkt_002($id, $isi, $url){
		$sql = "UPDATE BKD.BKD_REMUN_KINERJA SET BKT_PENUGASAN = '$isi', FILE_PENUGASAN = '$url' WHERE KD_BK = '$id'";
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
	function kategori_bkd_remun2($kode, $kat){
		$kode = strtoupper($kode);
		$query = $this->db->query("SELECT * FROM BKD_REMUN_KATEGORI_KEGIATAN WHERE upper(REPLACE(NM_KAT, ' ', '')) like '%$kode%' AND KD_JBR='$kat'");
		//$query = $this->db->query("SELECT A.KD_KAT, A.KD_JBR, A.NM_KAT, A.NILAI_KAT,B.SATUAN FROM BKD_REMUN_KATEGORI_KEGIATAN A JOIN BKD_REMUN_POIN_PENDIDIKAN B ON A.KD_KAT = B.KATEGORI WHERE upper(REPLACE(A.NM_KAT, ' ', '')) like '%$kode%' AND A.KD_JBR='$kat'");
		return $query->result();
	}

	function get_last_seq_numb(){
		$sql = "SELECT SQ_BKD_BEBAN_KERJA.NEXTVAL AS NILAI FROM DUAL";
		$q 	 = $this->db->query($sql);
		$q   = $q->row_array();
		return $q['NILAI'];
	}

	function get_poin_remun($kd_jbr, $jenjang, $kelas, $jabatan, $semester, $kategori){
		$poin = false;
		if($kd_jbr == 'A'){

			$sql  = "SELECT POIN, SATUAN FROM BKD.BKD_REMUN_POIN_PENDIDIKAN
					WHERE UPPER(JENJANG) = UPPER('$jenjang') AND UPPER(KELAS) = UPPER('$kelas')
					AND UPPER(JABATAN) = UPPER('$jabatan') AND UPPER(SEMESTER) = UPPER('$semester')
					AND KATEGORI = '$kategori' ";
			$q    = $this->db->query($sql)->row_array();
			$poin = $q; 
		}elseif($kd_jbr== 'D'){
			$sql  = "SELECT POIN, SATUAN FROM BKD.BKD_REMUN_POIN_PENDIDIKAN
					WHERE UPPER(JENJANG) = UPPER('$jenjang') AND UPPER(KELAS) = UPPER('$kelas')
					AND UPPER(JABATAN) = UPPER('$jabatan') AND UPPER(SEMESTER) = UPPER('$semester')
					AND KATEGORI = '$kategori' ";
			$q    = $this->db->query($sql)->row_array();
			$poin = $q; 
		}elseif($kd_jbr== 'B'){
			$sql  = "SELECT POIN, SATUAN FROM BKD.BKD_REMUN_POIN_PENDIDIKAN
					WHERE UPPER(JENJANG) = UPPER('$jenjang') AND UPPER(KELAS) = UPPER('$kelas')
					AND UPPER(JABATAN) = UPPER('$jabatan') AND UPPER(SEMESTER) = UPPER('$semester')
					AND KATEGORI = '$kategori' ";
			$q    = $this->db->query($sql)->row_array();
			$poin = $q; 
		}
		
		return $poin;
	}

	function get_kd_kat_remun($kd_kat){
		$sql = "SELECT A.KD_KAT_REMUN AS KD_KAT_REMUN, B.NM_KAT AS JUDUL FROM BKD.BKD_REMUN_KONVERSI_KAT A JOIN BKD.BKD_REMUN_KATEGORI_KEGIATAN B ON A.KD_KAT_REMUN = B.KD_KAT  WHERE A.KD_KAT = '$kd_kat'";
		$q   = $this->db->query($sql);
		$q   = $q->row_array();
		return $q;
	}
	function get_kd_kat_remun2($kd_kat){
		$sql = "SELECT KD_KAT_REMUN FROM BKD.BKD_REMUN_KONVERSI_KAT WHERE KD_KAT = '$kd_kat'";
		$q   = $this->db->query($sql);
		$q   = $q->row_array();
		return $q['KD_KAT_REMUN'];
	}

	function get_kd_kat_remun3($kd_kat){
		$sql = "SELECT KD_KAT AS KD_KAT_REMUN, NM_KAT AS JUDUL FROM BKD.BKD_REMUN_KATEGORI_KEGIATAN WHERE KD_KAT = '$kd_kat'";
		$q   = $this->db->query($sql);
		$q   = $q->row_array();
		return $q;
	}
	function get_kd_kat_serdos($kd_kat_remun){
		$sql = "SELECT KD_KAT FROM BKD.BKD_REMUN_KONVERSI_KAT WHERE KD_KAT_REMUN = '$kd_kat_remun'";
		$q   = $this->db->query($sql);
		$q   = $q->row_array();
		return $q['KD_KAT'];
	}

	function get_poin_skr($kode){
		$sql = "SELECT * FROM BKD.POIN_SKR_REMUN WHERE KD_JABATAN='$kode'";
		$q = $this->db->query($sql);
		return $q->row_array();
	}
	function get_max_poin_skr(){
		$sql = "SELECT * FROM BKD.POIN_MAKSIMAL_SKR_REMUN";
		$q = $this->db->query($sql);
		return $q->row_array();
	}
	function get_satuan_data_remun($kd_kat){
		$satuan = "SELECT SATUAN FROM BKD.BKD_REMUN_KATEGORI_KEGIATAN WHERE KD_KAT = (SELECT KD_KAT_REMUN FROM BKD.BKD_REMUN_KONVERSI_KAT WHERE KD_KAT = ".$kd_kat.")";
		$r = $this->db->query($satuan)->row_array();
		$stn = $r['SATUAN'];
		return $stn;
	}

	function get_jenis_remun(){
		$sql 	= "SELECT * FROM BKD.BKD_JENIS_BEBAN_REMUN";
		return $this->db->query($sql)->result_array();
	}
	function get_jenis_nilai_kat_remun($kd_kat){
		$sql = "SELECT KD_KAT, NILAI_KAT FROM BKD.BKD_REMUN_KATEGORI_KEGIATAN WHERE KD_KAT = '$kd_kat'";
		$r = $this->db->query($sql)->row_array();
		return $r;
	}
	function ambil_kd_kat_remun($kd_bk){
		$sql = "SELECT * FROM BKD.BKD_DATA_PENDIDIKAN WHERE KD_BK = '$kd_bk'";
		$q = $this->db->query($sql);
		$kode = $q->row_array();
		$kd_kat = $kode['KD_KAT']; 
		return $kd_kat;
	}
	function lihat_kd_konversi($kd_kat){
		$sql = "SELECT KD_KAT, KD_KAT_REMUN FROM BKD.BKD_REMUN_KONVERSI_KAT WHERE KD_KAT='$kd_kat'";
		$q = $this->db->query($sql);
		$kode = $q->result_array();
		foreach ($kode as $key) {
			return $key;
		}
	}

	function get_semua_poin_pendidikan(){
		$sql = "SELECT * FROM BKD.BKD_REMUN_POIN_PENDIDIKAN";
		$q   = $this->db->query($sql);

		return $q->result_array();
	}

	function get_jbr(){
		$sql = "SELECT * FROM BKD.BKD_JENIS_BEBAN_REMUN";
		$q 	 = $this->db->query($sql);
		return $q->result_array();
	}

	function get_kat_remun($jbr){
		$sql = "SELECT * FROM BKD.BKD_REMUN_KATEGORI_KEGIATAN WHERE KD_JBR = '$jbr'";
		$q   = $this->db->query($sql);
		return $q->result_array();
	}

	function get_poin_kat_pendidikan($kd_kat){ //get list poin remun by kategori kegiatan remun
		$sql = "SELECT * FROM BKD.BKD_REMUN_POIN_PENDIDIKAN WHERE KATEGORI = '$kd_kat' ORDER BY JENJANG ASC, KELAS ASC, JABATAN ASC, SEMESTER ASC";
		$q   = $this->db->query($sql);

		return $q->result_array();
	}

	function get_detail_poin_pendidikan($kd_p){
		$sql = "SELECT * FROM BKD.BKD_REMUN_POIN_PENDIDIKAN WHERE KD_P = '$kd_p'";
		$q   = $this->db->query($sql);

		return $q->row_array();
	}

	function tambah_poin_remun($kd_jbk, $kd_kat, $jenjang, $kelas, $jabatan, $semester, $poin, $satuan){
		$cek = "SELECT * FROM BKD.BKD_REMUN_POIN_PENDIDIKAN WHERE KATEGORI = '$kd_kat' AND KELAS = '$kelas' AND JENJANG = '$jenjang'
				AND JABATAN = '$jabatan' AND KD_JBK = '$kd_jbk' AND SEMESTER = '$semester'";

		$q   = $this->db->query($cek);
		$jml = count($q->result_array());
		if($jml > 0){
			//tidak boleh insert, soalnya duplikasi data !
			$r = 'duplicate';
			//$r = $q->result_array();
		}else{
			$sql = "INSERT INTO BKD.BKD_REMUN_POIN_PENDIDIKAN (KD_JBK, JENJANG, KELAS, JABATAN, SEMESTER, KATEGORI, POIN, SATUAN) VALUES 
					('$kd_jbk', '$jenjang', '$kelas', '$jabatan', '$semester', '$kd_kat', '$poin', '$satuan')";
			$qq  = $this->db->query($sql);
			if($qq){
				$r = 1;
			}else{
				$r = 0;
			}
		}

		return $r;
	}

	function hapus_poin_remun($kd_p){
		$sql = "DELETE FROM BKD.BKD_REMUN_POIN_PENDIDIKAN WHERE KD_P = '$kd_p'";
		$q   = $this->db->query($sql);

		return $q;
	}

	function update_poin_remun($kd_p, $jenjang, $kelas, $jabatan, $semester, $poin, $satuan){
			$sql = "UPDATE BKD.BKD_REMUN_POIN_PENDIDIKAN SET JENJANG = '$jenjang', KELAS = '$kelas', JABATAN = '$jabatan', SEMESTER = '$semester', POIN = '$poin', SATUAN = '$satuan'
				WHERE KD_P = '$kd_p'";
			$q  = $this->db->query($sql);

		return $q; 
	}

	function daftar_kategori_remun($kd_jbr){
		$sql = "SELECT * FROM BKD.BKD_REMUN_KATEGORI_KEGIATAN WHERE KD_JBR='$kd_jbr' ORDER BY KD_KAT ASC";
		$q = $this->db->query($sql);
		return $q->result_array();
	}
	function get_spesifik_kategori($kd_kat){
		$sql = "SELECT * FROM BKD.BKD_REMUN_KATEGORI_KEGIATAN WHERE KD_KAT='$kd_kat'";
		$q = $this->db->query($sql);
		return $q->row_array();
	}
	function get_data_asesor_dosen($thn_akademik, $smt){
		$sql = "SELECT * from BKD.BKD_ASESOR_DOSEN WHERE THN_AKADEMIK = '$thn_akademik' AND SEMESTER='$smt' AND (NIRA1 is not null  NIRA2 is not null)";
		$q = $this->db->query($sql);
		return $q->result_array();
	}
	function get_data_asesor_dosen_by_nip($kd_dosen, $thn_akademik, $smt){
		$sql = "SELECT * from BKD.BKD_ASESOR_DOSEN WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$thn_akademik' AND SEMESTER='$smt'";
		$q = $this->db->query($sql);
		return $q->row_array();
	}
	function cek_nira_asesor_dosen_uin($kd_dosen){
		$sql = "SELECT NIRA from BKD.BKD_ASESOR WHERE NM_PT = 'UIN SUNAN KALIJAGA, JOGYAKARTA' AND KD_DOSEN = '$kd_dosen'";
		$q = $this->db->query($sql);
		$r = $q->row_array();
		$nr= $r['NIRA'];
		return $nr;
	}
	function cek_data_asesor($nira, $thn_akademik, $smt){
		$query = "SELECT * FROM BKD.BKD_ASESOR_DOSEN WHERE THN_AKADEMIK = '$thn_akademik' AND SEMESTER = '$smt' AND (NIRA1='$nira' OR NIRA2='$nira')";
		$sql = $this->db->query($query);
		$q = $sql->result_array();
		return $q;
	}
	function getblob_bkd_remun($table, $kolom, $kode){
		$sql = "SELECT ".$kolom." FROM ".$table." WHERE KD_BK = '$kode'";
		 $q = $this->db->query($sql)->result_array();
        if(!empty($q)){ $q1 = $q[0][$kolom]->load(); $q[0][$kolom] = base64_encode($q1); }
        return $q;
	}
	function getextensi_bkd_remun($table, $kolom, $kode){
		$sql = "SELECT ".$kolom." FROM ".$table." WHERE KD_BK = '$kode'";
		$data = $this->db->query($sql)->result_array();
		return $data[0][$kolom];
		/*return $data;*/
	}
	function cek_summary_remun_dosen($kd_dosen, $kd_smt, $kd_ta){
		$query = "SELECT * FROM BKD.BKD_REMUN_SUMMARY WHERE KD_DOSEN='$kd_dosen' AND KD_SMT='$kd_smt' AND KD_TA='$kd_ta'";
		$sql = $this->db->query($query);
		$q = $sql->row_array();
		return $q;
	}
	function insert_summary_remun_dosen($a, $b, $c, $d, $e){
		$query = "INSERT INTO BKD.BKD_REMUN_SUMMARY (KD_DOSEN, KD_SMT, KD_TA, TOTAL_SKR, PERSEN_SKR) VALUES ('$a', '$b', '$c', ".$d.", ".$e.")";
		$sql = $this->db->query($query);
		return $sql;
	}
	function update_summary_remun_dosen($a, $b, $c, $d, $e){
		$query = "UPDATE BKD.BKD_REMUN_SUMMARY SET TOTAL_SKR =".$d.", PERSEN_SKR= ".$e." WHERE KD_DOSEN = '$a' AND KD_SMT = '$b' AND KD_TA='$c'";
		$sql = $this->db->query($query);
		return $sql;
	}
	function get_prosentase_skr_dosen($kd_dosen, $kd_smt, $kd_ta){
		$query = "SELECT * FROM BKD.BKD_REMUN_SUMMARY WHERE KD_DOSEN='$kd_dosen' AND KD_SMT='$kd_smt' AND KD_TA='$kd_ta'";
		$sql = $this->db->query($query);
		$q = $sql->row_array();
		return $q['PERSEN_SKR'];
	}

	
	function get_all_syarat_mengajar(){
		$sql = "SELECT * FROM BKD.BKD_SYARAT_MENGAJAR ORDER BY STATUS DESC";
		$q 	 = $this->db->query($sql);
		return $q->result_array();
	}

	function get_syarat_mengajar($jenis){
		$syarat = 0;
		$sql = "SELECT BATAS FROM BKD.BKD_SYARAT_MENGAJAR WHERE UPPER(JENIS) = UPPER('$jenis') AND STATUS = 1";
		$q   = $this->db->query($sql)->row_array();
		if($q){
			$syarat = $q['BATAS'];
		}

		return $syarat;
	}
	
	/*function get_kd_kat_remun($kd_bk){
		$sql = "SELECT KD_KAT FROM BKD.BKD_DATA_PENDIDIKAN WHERE KD_BK = '$kd_bk'";
		$q = $this->db->query($sql);
		$kode = $q->row_array();
		$kd_kat = $kode['KD_KAT'];
		return $kd_kat;
	}
	function cek_kd_konversi_remun($kd_kat){
		$sql = "SELECT KD_KAT, KD_KAT_REMUN FROM BKD.BKD_REMUN_KONVERSI_KAT WHERE KD_KAT='$kd_kat'";
		$q = $this->db->query($sql);
		$kode = $q->result_array();
		foreach ($kode as $key) {
			return $key;
		}
	}*/
}
