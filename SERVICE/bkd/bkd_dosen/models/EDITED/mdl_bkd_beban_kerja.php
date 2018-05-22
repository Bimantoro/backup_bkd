<?php if (!defined('BASEPATH')) exit();
	
class Mdl_bkd_beban_kerja extends CI_Model{
		
	function __construct(){
		parent::__construct();
		$this->bkd	= $this->load->database('bkd', TRUE);
	}
	
	function data_bebankerja($jenis,$kd_dosen, $thn, $smt){
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
					FROM BKD.BKD_BEBAN_KERJA A LEFT JOIN BKD.BKD_DATA_NARASUMBER B ON A.KD_BK = B.KD_BK
					WHERE A.KD_JBK = '$jenis' AND A.KD_DOSEN = '$kd_dosen'
					AND A.THN_AKADEMIK = '$thn' AND A.SEMESTER = '$smt' ORDER BY KD_BK ASC";		
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
					FROM BKD.BKD_BEBAN_KERJA 
					WHERE KD_JBK = '$jenis' AND KD_DOSEN = '$kd_dosen'
					AND THN_AKADEMIK = '$thn' AND SEMESTER = '$smt' ORDER BY KD_BK ASC";
			}
		return $this->db->query($sql)->result_array();
	}
	
	function data_bebankerja_tahunan($jenis,$kd_dosen, $thn){
		$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA 
				LEFT JOIN SIA.D_DOSEN ON BKD.BKD_BEBAN_KERJA.KD_DOSEN = SIA.D_DOSEN.KD_DOSEN
				WHERE BKD.BKD_BEBAN_KERJA.KD_JBK = '$jenis' AND SIA.D_DOSEN.KD_DOSEN = '$kd_dosen'
				AND BKD.BKD_BEBAN_KERJA.THN_AKADEMIK = '$thn' ORDER BY BKD.BKD_BEBAN_KERJA.SEMESTER DESC";
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
	
		
	function simpan_beban_kerja($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $q, $fp, $fc, $aa, $bb){
		$sql = "INSERT INTO BKD.BKD_BEBAN_KERJA (KD_JBK, KD_DOSEN, JENIS_KEGIATAN, BKT_PENUGASAN, SKS_PENUGASAN, MASA_PENUGASAN, BKT_DOKUMEN, SKS_BKT, 
				THN_AKADEMIK, SEMESTER, REKOMENDASI, JML_JAM, CAPAIAN, OUTCOME, FILE_PENUGASAN, FILE_CAPAIAN, KD_TA, KD_SMT)
				VALUES ('$a','$b','$c','$d','$e','$f','$g','$h','$i','$j','$k','$l','$m','$q', '$fp', '$fc', '$aa', '$bb')";
		$this->db->query($sql);
		
		return $this->trigger_kompilasi($a, $b, $j, $i);
	}


	function update_beban_kerja($kode, $c, $d, $e, $f, $g, $h, $i, $l, $p, $q, $kd_jbk, $kd_dosen, $smt, $tahun, $fp, $fc){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET JENIS_KEGIATAN = '$c', BKT_PENUGASAN = '$d', SKS_PENUGASAN = '$e',
				MASA_PENUGASAN = '$f', BKT_DOKUMEN = '$g', SKS_BKT = '$h', REKOMENDASI = '$i',
				JML_JAM = '$l', CAPAIAN = '$p', OUTCOME = '$q', FILE_PENUGASAN = '$fp', FILE_CAPAIAN = '$fc' WHERE KD_BK = '$kode'";
		$this->db->query($sql);
		return $this->trigger_kompilasi($kd_jbk, $kd_dosen, $smt, $tahun);
	}
	
	function hapus_beban_kerja($id, $kode, $kd_dosen, $smt, $tahun){
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
	

	/* data beban profesor */
	function data_bebankerja_prof($kd_dosen, $thn_prof){
		$sql = "SELECT * FROM BKD.BKD_BEBAN_KHUSUS_PROF
				LEFT JOIN BKD.BKD_JENIS_KEG ON BKD.BKD_BEBAN_KHUSUS_PROF.KD_JENIS_KEG = BKD.BKD_JENIS_KEG.KD_JENIS_KEG
				WHERE BKD.BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = '$kd_dosen' AND BKD.BKD_BEBAN_KHUSUS_PROF.TAHUN IN 
				($thn_prof, $thn_prof+1, $thn_prof+2)";
		return $this->db->query($sql)->result_array();
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
	
	function simpan_bebankerja_prof($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $n, $o, $p, $q, $r, $ta, $smt, $fp, $fc){
		$sql = "INSERT INTO BKD.BKD_BEBAN_KHUSUS_PROF (KD_DOSEN, KD_JENIS_KEG, JENIS_KEGIATAN, BKT_PENUGASAN, SKS_PENUGASAN,
				MASA_PENUGASAN, BKT_DOKUMEN, SKS_BKT, REKOMENDASI, TAHUN, CAPAIAN, KD_KAT, SUMBER_DANA, JUMLAH_DANA, PERIODE_LAPORAN, LAMAN_PUBLIKASI, THN_AKADEMIK, SEMESTER, FILE_PENUGASAN, FILE_CAPAIAN) VALUES ('$a','$b','$c','$d','$e','$f','$g','$h','$i','$j','$k','$n','$o','$p','$q','$r','$ta','$smt', '$fp', '$fc')";
		$this->db->query($sql);
		return $this->trigger_kompilasi_prof($a, $smt, $ta);
	}

	function update_bebankerja_prof($kode, $a, $b, $c, $d, $e, $f, $g, $h, $j, $k, $n, $o, $p, $q, $r, $ta, $smt, $kd_dosen, $fp, $fc){
		$sql = "UPDATE BKD.BKD_BEBAN_KHUSUS_PROF SET KD_JENIS_KEG = '$a', JENIS_KEGIATAN = '$b', 
				BKT_PENUGASAN = '$c', SKS_PENUGASAN = '$d', MASA_PENUGASAN = '$e', BKT_DOKUMEN = '$f', 
				SKS_BKT = '$g', REKOMENDASI = '$h', TAHUN = '$j', CAPAIAN = '$k',
				KD_KAT = '$n', SUMBER_DANA = '$o', JUMLAH_DANA = '$p', PERIODE_LAPORAN = '$q', LAMAN_PUBLIKASI = '$r', THN_AKADEMIK = '$ta', SEMESTER = '$smt', 
				FILE_PENUGASAN = '$fp', FILE_CAPAIAN = '$fc' WHERE KD_BKP = '$kode'";
		$this->db->query($sql);
		return $this->trigger_kompilasi_prof($kd_dosen, $smt, $ta);
	}

	function hapus_beban_prof($id, $kd_dosen, $smt, $ta){
		$sql = "DELETE FROM BKD.BKD_BEBAN_KHUSUS_PROF WHERE KD_BKP = '$id'";
		$this->db->query($sql);
		return $this->trigger_kompilasi_prof($kd_dosen, $smt, $ta);
	}


	/* counting jml beban dosen tiap semester */
	
	function jml_beban($jenis, $kode, $thn, $smt){
		$sql = "SELECT SUM (SKS_PENUGASAN) AS JML_BEBAN_SKS FROM BKD.BKD_BEBAN_KERJA 
				WHERE KD_JBK = '$jenis' AND KD_DOSEN = '$kode'
				AND THN_AKADEMIK = '$thn' AND SEMESTER = '$smt'";
		return $this->db->query($sql)->result_array();
	}
	
	function jml_beban_tahunan($jenis, $kode, $thn){
		$sql = "SELECT SUM (SKS_PENUGASAN) AS JML_BEBAN_SKS FROM BKD.BKD_BEBAN_KERJA 
				WHERE KD_JBK = '$jenis' AND KD_DOSEN = '$kode'
				AND THN_AKADEMIK = '$thn' ORDER BY SEMESTER ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function jml_kinerja($jenis, $kode, $thn, $smt){
		$sql = "SELECT SUM (SKS_BKT) AS JML_KINERJA FROM BKD.BKD_BEBAN_KERJA 
				WHERE KD_JBK = '$jenis' AND KD_DOSEN = '$kode'
				AND THN_AKADEMIK = '$thn' AND SEMESTER = '$smt' AND REKOMENDASI IN ('LANJUTKAN','SELESAI','LAINNYA')";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['JML_KINERJA'];
	}

	function jml_beban_lebih($jenis, $kode, $thn, $smt){
		$sql = "SELECT SUM (SKS_BKT) AS JML_KINERJA FROM BKD.BKD_BEBAN_KERJA 
				WHERE KD_JBK = '$jenis' AND KD_DOSEN = '$kode'
				AND THN_AKADEMIK = '$thn' AND SEMESTER = '$smt' AND REKOMENDASI = 'BEBAN LEBIH'";
		return $this->db->query($sql)->result_array();
	}
	
	function jml_kinerja_tahunan($jenis, $kode, $thn){
		$sql = "SELECT SUM (SKS_BKT) AS JML_KINERJA FROM BKD.BKD_BEBAN_KERJA 
				WHERE KD_JBK = '$jenis' AND KD_DOSEN = '$kode'
				AND THN_AKADEMIK = '$thn' ORDER BY SEMESTER ASC";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['JML_KINERJA'];
	}
		
	
	/* 
		counting jml kinerja untuk tiap2 jenis 
		kegiatan profesor setiap tahun selama 
		3 tahun terakhir 
	*/
	
	function jml_beban_prof($kode){
		$sql = "SELECT SUM (SKS_PENUGASAN) AS JML_BEBAN_SKS FROM BKD.BKD_BEBAN_KHUSUS_PROF 
				LEFT JOIN BKD.BKD_DOSEN ON BKD.BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = BKD.BKD_DOSEN.KD_DOSEN
				WHERE BKD.BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = '$kode' AND TAHUN IN 
				(BKD.BKD_DOSEN.THN_PROF,BKD.BKD_DOSEN.THN_PROF+1,BKD.BKD_DOSEN.THN_PROF+2)";
		return $this->db->query($sql)->result_array();
	}
	
	function jml_kinerja_prof($kode){
		$sql = "SELECT SUM (SKS_BKT) AS JML_KINERJA FROM BKD.BKD_BEBAN_KHUSUS_PROF 
				LEFT JOIN BKD.BKD_DOSEN ON BKD.BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = BKD.BKD_DOSEN.KD_DOSEN
				WHERE BKD.BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = '$kode' AND TAHUN IN 
				(BKD.BKD_DOSEN.THN_PROF,BKD.BKD_DOSEN.THN_PROF+1,BKD.BKD_DOSEN.THN_PROF+2)";
		return $this->db->query($sql)->result_array();
	}
	
	/* 
		HISTORY
		counting jml kinerja untuk tiap2 jenis 
		kegiatan profesor setiap tahun selama 
		3 tahun terakhir 
	*/
	
	function history_jml_beban_prof($kode, $tahun){
		$sql = "SELECT SUM (SKS_PENUGASAN) AS JML_BEBAN_SKS FROM BKD.BKD_BEBAN_KHUSUS_PROF 
				LEFT JOIN BKD.BKD_DOSEN ON BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = BKD_DOSEN.KD_DOSEN
				WHERE BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = '$kode' AND TAHUN = '$tahun'";
		return $this->db->query($sql)->result_array();
	}
	
	function history_jml_kinerja_prof($kode, $tahun){
		$sql = "SELECT SUM (SKS_BKT) AS JML_KINERJA FROM BKD.BKD_BEBAN_KHUSUS_PROF 
				LEFT JOIN BKD.BKD_DOSEN ON BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = BKD_DOSEN.KD_DOSEN
				WHERE BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = '$kode' AND TAHUN = '$tahun'";
		return $this->db->query($sql)->result_array();
	}
	
	
	function kinerja_prof_tahunan($kode, $jenis, $thn_prof, $thn_tambah){
		$sql = "SELECT SUM (SKS_BKT) AS JML_KINERJA FROM BKD.BKD_BEBAN_KHUSUS_PROF
				WHERE KD_DOSEN = '$kode' AND KD_JENIS_KEG = '$jenis' AND TAHUN = '$thn_prof' + '$thn_tambah'";
		return $this->db->query($sql)->result_array();
	}
	
	/* total kinerja selama 3 tahun terakhir */
	
	function total_kinerja_prof_perjenis($kode, $jenis){
		$sql = "SELECT SUM (SKS_BKT) AS JML_KINERJA FROM BKD_BEBAN_KHUSUS_PROF
				LEFT JOIN BKD_DOSEN ON BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = BKD_DOSEN.KD_DOSEN
				WHERE BKD_BEBAN_KHUSUS_PROF.KD_DOSEN = '$kode' AND 
				BKD_BEBAN_KHUSUS_PROF.KD_JENIS_KEG = '$jenis' AND
				TAHUN IN (BKD_DOSEN.THN_PROF,BKD_DOSEN.THN_PROF + 1,BKD_DOSEN.THN_PROF + 2)";
		return $this->db->query($sql)->result_array();
	}
	
	# DATA DATA BEBAN KERJA : PENELITIAN DAN PENGABDIAN SERTA PUBLIKASI
	# ===============================================================================================================================
	
	function simpan_data_penelitian($a, $c, $d, $e, $g, $h, $i, $j, $k){
		$sql = "INSERT INTO BKD.BKD_DATA_PENELITIAN (KD_BK, BT_MULAI, BT_SELESAI, JUDUL_PENELITIAN, SUMBER_DANA, JUMLAH_DANA, KD_KAT, STATUS_PENELITI, LAMAN_PUBLIKASI) 
				VALUES ('$a', TO_DATE('$c','DD/MM/YYYY'), TO_DATE('$d','DD/MM/YYYY'), '$e','$g','$h','$i','$j','$k')";
		return $this->db->query($sql);
	}
	
	function simpan_data_pengabdian($a, $c, $d, $e, $f, $g, $h){
		$sql = "INSERT INTO BKD.BKD_DATA_PENGABDIAN (KD_BK, BT_MULAI, BT_SELESAI, JUDUL_PENGABDIAN, SUMBER_DANA, JUMLAH_DANA, KD_KAT) 
				VALUES ('$a', TO_DATE('$c','DD/MM/YYYY'), TO_DATE('$d','DD/MM/YYYY'), '$e','$f','$g','$h')";
		return $this->db->query($sql);
	}	
	
	function get_current_data_tersimpan($table, $kolom){
		$sql = "SELECT MAX($kolom) AS KD_BK FROM $table";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['KD_BK'];
	}
	
	function update_data_penelitian($a, $c, $d, $e, $g, $h, $i, $j, $k){
		$sql = "UPDATE BKD.BKD_DATA_PENELITIAN SET BT_MULAI = TO_DATE('$c', 'DD/MM/YYYY'), BT_SELESAI = TO_DATE('$d', 'DD/MM/YYYY'), JUDUL_PENELITIAN = '$e',
				SUMBER_DANA = '$g', JUMLAH_DANA = '$h', KD_KAT = '$i', STATUS_PENELITI = '$j', LAMAN_PUBLIKASI = '$k' WHERE KD_BK = '$a' ";
		return $this->db->query($sql);
	}
	
	function update_data_narasumber($data){
		$sql = "UPDATE BKD.BKD_DATA_NARASUMBER SET BT_MULAI = TO_DATE('$data[1]', 'DD/MM/YYYY'), BT_SELESAI = TO_DATE('$data[2]', 'DD/MM/YYYY'), 
				JUDUL_ACARA = '$data[3]', KD_TINGKAT = '$data[4]', STATUS_PENELITI = '$data[5]', LAMAN_PUBLIKASI = '$data[6]', LOKASI_ACARA = '$data[7]' 
				WHERE KD_BK = '$data[0]' ";
		return $this->db->query($sql);
	}

	function update_data_pengabdian($a, $c, $d, $e, $g, $h, $i){
		$sql = "UPDATE BKD.BKD_DATA_PENGABDIAN SET BT_MULAI = TO_DATE('$c', 'DD/MM/YYYY'), BT_SELESAI = TO_DATE('$d', 'DD/MM/YYYY'), JUDUL_PENGABDIAN = '$e',
				SUMBER_DANA = '$g', JUMLAH_DANA = '$h', KD_KAT = '$i' WHERE KD_BK = '$a' ";
		return $this->db->query($sql);
	}
	
	# DATA PUBLIKASI DOSEN
	# ======================================================================================================================================
	function get_data_publikasi($kd_dosen, $ta, $smt){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI A
				LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
				WHERE A.KD_DOSEN = '$kd_dosen' AND A.THN_AKADEMIK = '$ta' AND A.SEMESTER = '$smt' ORDER BY A.TANGGAL_PUB DESC";
		return $this->db->query($sql)->result_array();
	}
	
	function get_data_publikasi_fakultas($kd_fak, $ta, $smt){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI A
				LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
				WHERE Z.KD_FAK = '$kd_fak' AND A.THN_AKADEMIK = '$ta' AND A.SEMESTER = '$smt' ORDER BY A.TANGGAL_PUB DESC";
		return $this->db->query($sql)->result_array();
	}
	
	function get_data_publikasi_prodi($kd_prodi, $ta, $smt){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI A
				LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
				WHERE Z.KD_PRODI = '$kd_prodi' AND A.THN_AKADEMIK = '$ta' AND A.SEMESTER = '$smt' ORDER BY A.TANGGAL_PUB DESC";
		return $this->db->query($sql)->result_array();
	}
	
	function simpan_data_publikasi($a, $b, $c, $d, $e, $f, $g, $thn, $smt, $kd_ta, $kd_smt){
		$sql = "INSERT INTO BKD.BKD_DATA_PUBLIKASI (KD_DOSEN, JUDUL_PUBLIKASI, PADA, TINGKAT, TANGGAL_PUB, PENERBIT, AKREDITASI, THN_AKADEMIK, SEMESTER, KD_TA, KD_SMT) 
				VALUES ('$a','$b','$c','$d', TO_DATE('$e','DD/MM/YYYY'),'$f','$g','$thn','$smt', $kd_ta, $kd_smt) ";
		return $this->db->query($sql);
	}
	
	function update_data_publikasi($kd_dp, $b, $c, $d, $e, $f, $g){
		$sql = "UPDATE BKD.BKD_DATA_PUBLIKASI SET JUDUL_PUBLIKASI = '$b', PADA = '$c', TINGKAT = '$d', TANGGAL_PUB = TO_DATE('$e','DD/MM/YYYY'), 
				PENERBIT = '$f', AKREDITASI = '$g' WHERE KD_DP = '$kd_dp' ";
		return $this->db->query($sql);
	}
	
	function current_data_publikasi($kd){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI WHERE KD_DP = '$kd'";
		return $this->db->query($sql)->result_array();
	}
	
	function hapus_data_publikasi($kd){
		$sql = "DELETE FROM BKD.BKD_PARTNER_KINERJA WHERE KD_KINERJA = '$kd' AND JENIS_KINERJA='PUBLIKASI'";
		$sql2 = "DELETE FROM BKD.BKD_DATA_PUBLIKASI WHERE KD_DP = '$kd'";
		$this->db->query($sql);
		return $this->db->query($sql2);
	}
	
	
	# DATA HAKI DOSEN
	# ======================================================================================================================================
	function get_jenis_haki(){
		$sql = "SELECT * FROM JENIS_HAKI ORDER BY KD_JENIS_HAKI ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function get_data_haki($kd_dosen){
		$sql = "SELECT A.KD_HAKI, A.KD_DOSEN,  A.JUDUL_HAKI, A.TINGKAT, A.NOMOR_SK, A.TANGGAL_SK, A.PENERBIT_SK, A.PEMILIK_HAK, JH.JENIS_HAKI
				FROM HAKI A
				LEFT JOIN JENIS_HAKI JH ON JH.KD_JENIS_HAKI=A.KD_JENIS_HAKI
				WHERE A.KD_DOSEN = '$kd_dosen' ORDER BY A.TANGGAL_SK DESC";
		return $this->db->query($sql)->result_array();
	}
	
	function current_data_haki($kd){
		$sql = "SELECT A.KD_HAKI, A.KD_DOSEN,  A.JUDUL_HAKI, A.TINGKAT, A.NOMOR_SK, A.TANGGAL_SK, A.PENERBIT_SK, A.PEMILIK_HAK,A.KD_JENIS_HAKI,
				A.KD_BK
				FROM HAKI A
				LEFT JOIN JENIS_HAKI JH ON JH.KD_JENIS_HAKI=A.KD_JENIS_HAKI
				WHERE A.KD_HAKI = '$kd'";
		return $this->db->query($sql)->row_array();
	}
	
	function simpan_data_haki($param){
		$sql = "INSERT INTO BKD.HAKI (KD_BK,KD_DOSEN, JUDUL_HAKI,KD_JENIS_HAKI,TINGKAT,NOMOR_SK,TANGGAL_SK,PENERBIT_SK,PEMILIK_HAK) 
				VALUES ('".$param['KD_BK']."','".$param['KD_DOSEN']."','".$param['JUDUL_HAKI']."','".$param['KD_JENIS_HAKI']."','".$param['TINGKAT']."','".$param['NOMOR_SK']."',TO_DATE('".$param['TANGGAL_SK']."','DD/MM/YYYY'),'".$param['PENERBIT_SK']."','".$param['PEMILIK_HAK']."') ";
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
	function hapus_data_haki($kd){
		$sql = "DELETE FROM BKD.BKD_PARTNER_KINERJA WHERE KD_KINERJA = '$kd' AND JENIS_KINERJA='HAKI'";
		$sql2 = "DELETE FROM BKD.HAKI WHERE KD_HAKI = '$kd'";
		$this->db->query($sql);
		return $this->db->query($sql2);
	}
	
	# DATA SEMUA DOSEN 
	function get_all_dosen(){
		$sql = "SELECT A.KD_DOSEN, A.NIP, B.NM_PRODI, A.NM_DOSEN FROM SIA.D_DOSEN A LEFT JOIN SIA.MASTER_PRODI B ON A.KD_PRODI = B.KD_PRODI";
		return $this->db->query($sql)->result_array();
	}
	
	
	# TAMBAHAN SIPKD ======================================================= MOSOK NGRUBAH DESAIN TABLE, MODEL, KONTROLLER, API KON DADI CEPET ??? tenang aja lanjut terus 
	function get_kategori($kode){
		$sql = "SELECT * FROM BKD.BKD_KATEGORI_KEG WHERE KD_JBK = '$kode' ORDER BY KD_KAT ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function simpan_data_pendidikan($a, $b, $c, $d, $e, $f){
		$sql = "INSERT INTO BKD.BKD_DATA_PENDIDIKAN (KD_BK, KD_KAT, NM_KEGIATAN, JENJANG, TEMPAT, JML_MHS) VALUES ('$a', '$b', '$c', '$d', '$e', '$f')";
		return $this->db->query($sql);
	}
	
	function update_data_pendidikan($a, $b, $c, $d, $e, $f){
		$sql = "UPDATE BKD.BKD_DATA_PENDIDIKAN SET KD_KAT = '$b', NM_KEGIATAN = '$c', JENJANG = '$d', TEMPAT = '$e', JML_MHS = '$f' WHERE KD_BK = '$a'";
		return $this->db->query($sql);
	}
	
	function get_file_bebankerja($kd_bk){
		$sql = "SELECT FILE_CAPAIAN, FILE_PENUGASAN FROM BKD.BKD_BEBAN_KERJA WHERE KD_BK = '$kd_bk'";
		return $this->db->query($sql)->result_array();
	}
	
	
	
	
	# AUTO SAVE BEBAN KERJA FROM SIA
	function simpan_beban_kerja_sia($a, $b, $c, $d, $e, $f, $g, $h, $i, $j, $k, $l, $m, $n){
		$sql = "INSERT INTO BKD.BKD_BEBAN_KERJA (KD_DOSEN, KD_JBK, JENIS_KEGIATAN, BKT_PENUGASAN, SKS_PENUGASAN, MASA_PENUGASAN, SKS_BKT, BKT_DOKUMEN, THN_AKADEMIK, SEMESTER, REKOMENDASI, CAPAIAN, KD_TA, KD_SMT) 
				VALUES ('$a','$b','$c','$d','$e','$f', '$g','$h','$i','$j','$k', '$l', '$m', '$n')";
		$this->db->query($sql);
		return $this->trigger_kompilasi($b, $a, $j, $i);
	}
	
	function cek_data_bkd($kd_dosen, $kode, $ta, $smt){
		$sql = "SELECT COUNT(*) AS JML FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$ta' AND SEMESTER = '$smt' AND KD_JBK = '$kode'";
		foreach ($this->db->query($sql)->result() as $data);
		return $data->JML;
	}
	
	function clear_data_bkd($kd_dosen, $kode, $ta, $smt){
		$sql2 = "DELETE FROM BKD.BKD_DATA_PENDIDIKAN WHERE KD_BK IN 
				(SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$ta' AND SEMESTER = '$smt' AND KD_JBK = '$kode')";
		$sql = "DELETE FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$ta' AND SEMESTER = '$smt' AND KD_JBK = '$kode'";
		$this->db->query($sql2);
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
	
	# cek jenjang prodi
	function cek_jenjang_prodi($kd_prodi){
		$sql = "SELECT JENJANG FROM BKD.JENJANG_PRODI WHERE KD_PRODI = '$kd_prodi'";
		foreach ($this->db->query($sql)->result() as $data);
		return $data->JENJANG;
	}
	
	
	# RENCANA BEBAN KERJA DOSEN
	# ========================================================================================================================================
	# 23/02/2014
	
	function simpan_rbkd($a, $b, $c, $d, $e, $f, $g, $h, $file, $kd_ta, $kd_smt){
		$sql = "INSERT INTO BKD.RBKD (KD_JBK, KD_DOSEN, JENIS_KEGIATAN, BKT_PENUGASAN, SKS_PENUGASAN, MASA_PENUGASAN, THN_AKADEMIK, SEMESTER, FILE_PENUGASAN, KD_TA, KD_SMT) 
				VALUES('$a','$b','$c','$d','$e','$f','$g','$h', '$file', '$kd_ta','$kd_smt')";
		return $this->db->query($sql);
	}

	function update_rbkd($id, $a, $b, $c, $d, $file){
		$sql = "UPDATE BKD.RBKD SET JENIS_KEGIATAN = '$a', BKT_PENUGASAN = '$b', SKS_PENUGASAN = '$c', MASA_PENUGASAN = '$d', FILE_PENUGASAN = '$file' WHERE KD_RBK = '$id'";
		return $this->db->query($sql);
	}

	function current_rbkd($id){
		$sql = "SELECT * FROM BKD.RBKD WHERE KD_RBK = '$id'";
		return $this->db->query($sql)->result_array();
	}

	function hapus_rbkd($id){
		$sql = "DELETE FROM BKD.RBKD WHERE KD_RBK = '$id'";
		return $this->db->query($sql);
	}
	
	function data_rbkd($kd_jbk, $kd_dosen, $ta, $smt){
		$sql = "SELECT * FROM BKD.RBKD WHERE KD_JBK = '$kd_jbk' AND KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$ta' AND SEMESTER = '$smt' ORDER BY KD_RBK ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function data_rbkd_dosen($kd_jbk, $kd_dosen, $ta, $smt){
		$sql = "SELECT * FROM BKD.RBKD WHERE KD_JBK = '$kd_jbk' AND KD_DOSEN = '$kd_dosen' AND KD_TA = '$ta' AND KD_SMT = '$smt' ORDER BY KD_RBK ASC";
		return $this->db->query($sql)->result_array();
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


	# TRIGGER KOMPILASI =============================================
	function trigger_kompilasi($kode, $kd_dosen, $smt, $tahun){
		$sqlHitung = "SELECT SUM(SKS_BKT) AS TOT_SKS FROM BKD.BKD_BEBAN_KERJA WHERE KD_JBK = '$kode' AND KD_DOSEN = '$kd_dosen' AND SEMESTER = '$smt' AND THN_AKADEMIK = '$tahun'";
		foreach ($this->db->query($sqlHitung)->result_array() as $data);
		$tot = $data['TOT_SKS']; 
		if ($smt == 'GANJIL'){
			switch ($kode){
				case "A" : $sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PD1 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'"; break;
				case "B" : $sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PL1 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'"; break;
				case "C" : $sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PG1 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'"; break;
				case "D" : $sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PK1 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'"; break;
			}
			return $this->db->query($sqlKom);		
		}else if($smt == 'GENAP'){
			switch ($kode){
				case "A" : $sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PD2 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'"; break;
				case "B" : $sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PL2 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'"; break;
				case "C" : $sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PG2 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'"; break;
				case "D" : $sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PK2 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'"; break;
			}
			return $this->db->query($sqlKom);		
		}
	}
	

	function trigger_kompilasi_prof($kd_dosen, $smt, $tahun){
		$sqlHitung = "SELECT SUM(SKS_BKT) AS TOT_SKS FROM BKD.BKD_BEBAN_KHUSUS_PROF WHERE KD_DOSEN = '$kd_dosen' AND SEMESTER = '$smt' AND THN_AKADEMIK = '$tahun'";
		foreach ($this->db->query($sqlHitung)->result_array() as $data);
		$tot = $data['TOT_SKS']; 
		if ($smt == 'GANJIL'){
			$sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PR1 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'";
			return $this->db->query($sqlKom);		
		}else if($smt == 'GENAP'){
			$sqlKom = "UPDATE BKD.BKD_KOMPILASI SET PR2 = '$tot' WHERE KD_DOSEN = '$kd_dosen' AND THN_AKADEMIK = '$tahun'";
			return $this->db->query($sqlKom);		
		}
	}
	
	 
	function get_dosen_limit($start = 1, $offset = ''){
		$sql = $this->query_limit("SELECT A.KD_DOSEN, A.KD_PRODI, A.KD_FAK, A.NO_SERTIFIKAT, A.KD_JENIS_DOSEN, B.NIP, B.NM_DOSEN FROM BKD.BKD_DOSEN A 
				LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN ORDER BY B.NM_DOSEN ",$start, $offset);
		return $this->db->query($sql)->result_array();
	}	
	

	# MODEL UNTUK QUERY _LIMIT
	function query_limit($sql = '', $start = 1, $offset = false){
	
		if($offset != TRUE){
			$start += $offset-1;
		}
		
		$sql1 = "SELECT * FROM (SELECT IQRY.*, ROWNUM IQR_NUM FROM (".$sql.") IQRY WHERE ROWNUM <= ".$start.")";
		
		if($offset != false){
			$sql1 .= " WHERE IQR_NUM > ".$offset;
		}
		
		return $sql1;
	}

	
	# administrator :: 07-03-2014
	# ============================================================
	
	function tot_record($table){
		$sql = "SELECT * FROM $table";
		return $this->db->query($sql)->num_rows();
	}
	
	function tot_record_semester($table, $ta, $smt){
		$sql = "SELECT * FROM $table WHERE THN_AKADEMIK = '$ta' AND SEMESTER = '$smt'";
		return $this->db->query($sql)->num_rows();
	}
	
	function jml_kompilasi_dosen($tahun){
		$sql = "SELECT COUNT(*) AS JML FROM BKD.BKD_KOMPILASI WHERE THN_AKADEMIK = '$tahun'";
		foreach ($this->db->query($sql)->result_array() as $data);
		return $data['JML'];
	}
	
	function kompilasi_dosen_limit($tahun, $limit, $start){
		$sql = "SELECT * FROM (SELECT R.*, ROWNUM AS ROW_NUMBER FROM 
				(SELECT A.PD1, A.PL1, A.PG1, A.PK1, A.PD2, A.PL2, A.PG2, A.PK2, B.NO_SERTIFIKAT, B.KD_JENIS_DOSEN,
				A.KD_DOSEN, A.PR1, A.PR2 FROM BKD.BKD_KOMPILASI A 
				LEFT JOIN BKD.BKD_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN 
				WHERE A.THN_AKADEMIK = '$tahun') 
				R WHERE ROWNUM <= ".$limit.") WHERE ".$start." < ROW_NUMBER ";
		return $this->db->query($sql)->result_array();
	}
	
	function kompilasi_dosen($tahun){
		$sql = "SELECT A.PD1, A.PL1, A.PG1, A.PK1, A.PD2, A.PL2, A.PG2, A.PK2, B.NO_SERTIFIKAT, B.KD_JENIS_DOSEN, B.KD_FAK,
				A.KD_DOSEN, A.PR1, A.PR2 FROM BKD.BKD_KOMPILASI A 
				LEFT JOIN BKD.BKD_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN 
				WHERE A.THN_AKADEMIK = '$tahun' ORDER BY B.KD_FAK ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function cari_kompilasi_dosen($field, $value, $tahun){
		$sql = "SELECT A.PD1, A.PL1, A.PG1, A.PK1, A.PD2, A.PL2, A.PG2, A.PK2, B.NO_SERTIFIKAT, B.KD_JENIS_DOSEN,
				C.NM_DOSEN, C.NM_DOSEN_F, C.NIP, D.NM_FAK, A.KD_DOSEN, A.PR1, A.PR2 FROM BKD.BKD_KOMPILASI A 
				LEFT JOIN BKD.BKD_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN 
				LEFT JOIN SIA.V_DOSEN C ON A.KD_DOSEN = C.KD_DOSEN 
				LEFT JOIN SIA.MASTER_FAK D ON B.KD_FAK = D.KD_FAK 
				WHERE A.THN_AKADEMIK = '$tahun' AND LOWER($field) LIKE LOWER('%$value%') ORDER BY C.NM_DOSEN ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function data_pubikasi_limit(){
		$sql = "SELECT A.*, B.NM_DOSEN FROM BKD.BKD_DATA_PUBLIKASI A 
				LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN";
		return $this->db->query($sql)->result_array();
	}
	
	function data_pubikasi_limit_fakultas($kd){
		$sql = "SELECT A.*, B.NM_DOSEN FROM BKD.BKD_DATA_PUBLIKASI A 
				LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN
				LEFT JOIN BKD.BKD_DOSEN C ON A.KD_DOSEN = C.KD_DOSEN WHERE C.KD_FAK = '$kd'";
		return $this->db->query($sql)->result_array();
	}
	
	function data_pubikasi_limit_prodi($kd){
		$sql = "SELECT A.*, B.NM_DOSEN FROM BKD.BKD_DATA_PUBLIKASI A 
				LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN
				LEFT JOIN BKD.BKD_DOSEN C ON A.KD_DOSEN = C.KD_DOSEN WHERE C.KD_PRODI = '$kd'";
		return $this->db->query($sql)->result_array();
	}
	
	function publikasi_tingkat($tingkat){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI WHERE TINGKAT = '$tingkat' ORDER BY JUDUL_PUBLIKASI ASC";
		return $this->db->query($sql)->result_array();		
	}
	
	# FAKULTAS ADMIN
	function kompilasi_dosen_fakultas($tahun, $kd_fak){
		$sql = "SELECT A.PD1, A.PL1, A.PG1, A.PK1, A.PD2, A.PL2, A.PG2, A.PK2, B.NO_SERTIFIKAT, B.KD_JENIS_DOSEN,
				C.NM_DOSEN, C.NIP, D.NM_FAK, A.KD_DOSEN, A.PR1, A.PR2 FROM BKD.BKD_KOMPILASI A 
				LEFT JOIN BKD.BKD_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN 
				LEFT JOIN SIA.D_DOSEN C ON A.KD_DOSEN = C.KD_DOSEN 
				LEFT JOIN SIA.MASTER_FAK D ON B.KD_FAK = D.KD_FAK 
				WHERE A.THN_AKADEMIK = '$tahun' AND B.KD_FAK = '$kd_fak' ORDER BY C.NM_DOSEN ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function cari_kompilasi_dosen_fakultas($field, $value, $tahun, $fak_id){
		$sql = "SELECT A.PD1, A.PL1, A.PG1, A.PK1, A.PD2, A.PL2, A.PG2, A.PK2, B.NO_SERTIFIKAT, B.KD_JENIS_DOSEN,
				C.NM_DOSEN, C.NIP, D.NM_FAK, A.KD_DOSEN, A.PR1, A.PR2 FROM BKD.BKD_KOMPILASI A 
				LEFT JOIN BKD.BKD_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN 
				LEFT JOIN SIA.D_DOSEN C ON A.KD_DOSEN = C.KD_DOSEN 
				LEFT JOIN SIA.MASTER_FAK D ON B.KD_FAK = D.KD_FAK 
				WHERE A.THN_AKADEMIK = '$tahun' AND B.KD_FAK = '$fak_id' AND LOWER($field) LIKE LOWER('%$value%') ORDER BY C.NM_DOSEN ASC";
		return $this->db->query($sql)->result_array();
	}
	

	# PRODI ADMIN
	function kompilasi_dosen_prodi($tahun, $kd_prodi){
		$sql = "SELECT A.PD1, A.PL1, A.PG1, A.PK1, A.PD2, A.PL2, A.PG2, A.PK2, B.NO_SERTIFIKAT, B.KD_JENIS_DOSEN,
				C.NM_DOSEN, C.NIP, D.NM_FAK, A.KD_DOSEN, A.PR1, A.PR2 FROM BKD.BKD_KOMPILASI A 
				LEFT JOIN BKD.BKD_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN 
				LEFT JOIN SIA.D_DOSEN C ON A.KD_DOSEN = C.KD_DOSEN 
				LEFT JOIN SIA.MASTER_FAK D ON B.KD_FAK = D.KD_FAK 
				WHERE A.THN_AKADEMIK = '$tahun' AND B.KD_PRODI = '$kd_prodi' ORDER BY C.NM_DOSEN ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function cari_kompilasi_dosen_prodi($field, $value, $tahun, $prodi_id){
		$sql = "SELECT A.PD1, A.PL1, A.PG1, A.PK1, A.PD2, A.PL2, A.PG2, A.PK2, B.NO_SERTIFIKAT, B.KD_JENIS_DOSEN,
				C.NM_DOSEN, C.NIP, D.NM_FAK, A.KD_DOSEN, A.PR1, A.PR2 FROM BKD.BKD_KOMPILASI A 
				LEFT JOIN BKD.BKD_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN 
				LEFT JOIN SIA.D_DOSEN C ON A.KD_DOSEN = C.KD_DOSEN 
				LEFT JOIN SIA.MASTER_FAK D ON B.KD_FAK = D.KD_FAK 
				WHERE A.THN_AKADEMIK = '$tahun' AND B.KD_PRODI = '$prodi_id' AND LOWER($field) LIKE LOWER('%$value%') ORDER BY C.NM_DOSEN ASC";
		return $this->db->query($sql)->result_array();
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

	# API UNTUK APLIKASI IKD
	# ==========================================
	function capaian_bebankerja($jenis,$kd_dosen, $thn, $smt){
		$sql = "SELECT
				KD_DOSEN, 
				JENIS_KEGIATAN, 
				THN_AKADEMIK, 
				SEMESTER, 
				CAPAIAN
				FROM BKD.BKD_BEBAN_KERJA 
				WHERE KD_JBK = '$jenis' AND KD_DOSEN = '$kd_dosen'
				AND THN_AKADEMIK = '$thn' AND SEMESTER = '$smt' ORDER BY KD_BK ASC";
		return $this->db->query($sql)->result_array();
	}
	
	function detail_beban_kerja($jbk,$kd_dosen, $kd_ta, $kd_smt){
		if ($jbk == 'B'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENELITIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND KD_TA = 
					'$kd_ta' AND KD_SMT = '$kd_smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'C'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENGABDIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND KD_TA = 
					'$kd_ta' AND KD_SMT = '$kd_smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'A'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENDIDIKAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND KD_TA = 
					'$kd_ta' AND KD_SMT = '$kd_smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else{
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_DOSEN = '$kd_dosen' AND A.KD_TA = 
					'$kd_ta' AND A.KD_SMT = '$kd_smt' AND A.KD_JBK = '$jbk'";
			return $this->db->query($sql)->result_array();
		}
	}
	
	function detail_beban_kerja_fakultas($jbk, $kd_fak, $thn, $smt){
		if ($jbk == 'B'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENELITIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE Z.KD_FAK = '$kd_fak' AND THN_AKADEMIK = 
					'$thn' AND SEMESTER = '$smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'C'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENGABDIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE Z.KD_FAK = '$kd_fak' AND THN_AKADEMIK = 
					'$thn' AND SEMESTER = '$smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'A'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENDIDIKAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE Z.KD_FAK = '$kd_fak' AND THN_AKADEMIK = 
					'$thn' AND SEMESTER = '$smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else{
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE Z.KD_FAK = '$kd_fak' AND A.THN_AKADEMIK = 
					'$thn' AND A.SEMESTER = '$smt' AND A.KD_JBK = '$jbk'";
			return $this->db->query($sql)->result_array();
		}
	}

	function detail_beban_kerja_prodi($jbk, $kd_prodi, $thn, $smt){
		if ($jbk == 'B'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENELITIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE Z.KD_PRODI = '$kd_prodi' AND THN_AKADEMIK = 
					'$thn' AND SEMESTER = '$smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'C'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENGABDIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE Z.KD_PRODI = '$kd_prodi' AND THN_AKADEMIK = 
					'$thn' AND SEMESTER = '$smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'A'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENDIDIKAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE Z.KD_PRODI = '$kd_prodi' AND THN_AKADEMIK = 
					'$thn' AND SEMESTER = '$smt' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else{
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE Z.KD_PRODI = '$kd_prodi' AND A.THN_AKADEMIK = 
					'$thn' AND A.SEMESTER = '$smt' AND A.KD_JBK = '$jbk'";
			return $this->db->query($sql)->result_array();
		}
	}
	
	# DATA PUBLIKASI 
  	function publikasi_tahunan($tahun){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI WHERE THN_AKADEMIK = '$tahun'";
		return $this->db->query($sql)->result_array();
	}
	
	function publikasi_semester($tahun, $smt){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI WHERE THN_AKADEMIK = '$tahun' AND SEMESTER = '$smt'";
		return $this->db->query($sql)->result_array();
	}

	function semua_publikasi(){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI A LEFT JOIN 
				SIA.V_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN";
		return $this->db->query($sql)->result_array();	
	}

	function cari_publikasi($kolom, $value){
		$sql = "SELECT * FROM BKD.BKD_DATA_PUBLIKASI WHERE LOWER($kolom) LIKE '%$value%' ORDER BY TANGGAL_PUB DESC";
		return $this->db->query($sql)->result_array();
	}
	
	function publikasi_semester_limit($tahun, $smt, $limit, $start){
		$sql = "SELECT * FROM (SELECT R.*, ROWNUM AS ROW_NUMBER FROM 
				(SELECT * FROM BKD.BKD_DATA_PUBLIKASI WHERE THN_AKADEMIK = '$tahun' AND SEMESTER = '$smt' ORDER BY TANGGAL_PUB DESC) 
				R WHERE ROWNUM <= ".$limit.") WHERE ".$start." < ROW_NUMBER ";
		return $this->db->query($sql)->result_array();
	}
	
	# KOMPILASI RBKD
	# 11/07/2014
	# ===============================
	
	function rbkd_semester($ta, $smt){
		$sql = "SELECT * FROM BKD.RBKD WHERE THN_AKADEMIK = '$ta' AND SEMESTER = '$smt'";
		return $this->db->query($sql)->result_array();
	}
	
	function semua_publikasi_dosen($kd_dosen){
		$sql = "SELECT KD_DP, KD_DOSEN, JUDUL_PUBLIKASI, PADA, TINGKAT, TO_CHAR(TANGGAL_PUB,'DD/MM/YYYY') AS TANGGAL_PUB, PENERBIT, AKREDITASI, THN_AKADEMIK, SEMESTER, KD_TA, KD_SMT 
				FROM BKD.BKD_DATA_PUBLIKASI WHERE KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql)->result_array();
	}
	
	function SemuaDataBebanKerjaDosenXXX($kd_jbk, $kd_dosen){
		$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A LEFT JOIN BKD.BKD_DATA_PENELITIAN B ON A.KD_DOSEN = B.KD_DOSEN 
				WHERE A.KD_JBK = '$kd_jbk' AND KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql)->result_array();
	}
	
	function SemuaDataBebanKerjaDosen($jbk, $kd_dosen){
		if ($jbk == 'B'){
			$sql = "SELECT A.*, B.JUDUL_PENELITIAN, TO_CHAR(B.BT_MULAI,'DD/MM/YYYY') AS BT_MULAI, TO_CHAR(B.BT_SELESAI,'DD/MM/YYYY') AS BT_SELESAI, 
					B.STATUS_PENELITI, C.NM_KAT FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENELITIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'C'){
			$sql = "SELECT A.*, B.JUDUL_PENGABDIAN, TO_CHAR(B.BT_MULAI,'DD/MM/YYYY') AS BT_MULAI, TO_CHAR(B.BT_SELESAI,'DD/MM/YYYY') AS BT_SELESAI, 
					C.NM_KAT FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENGABDIAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else if ($jbk == 'A'){
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A 
					LEFT JOIN BKD.BKD_DATA_PENDIDIKAN B ON A.KD_BK = B.KD_BK 
					LEFT OUTER JOIN BKD.BKD_KATEGORI_KEG C ON B.KD_KAT = C.KD_KAT 
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_BK IN (SELECT KD_BK FROM BKD.BKD_BEBAN_KERJA WHERE KD_DOSEN = '$kd_dosen' AND KD_JBK = '$jbk')";
			return $this->db->query($sql)->result_array();
		}else{
			$sql = "SELECT * FROM BKD.BKD_BEBAN_KERJA A
					LEFT JOIN BKD.BKD_DOSEN Z ON A.KD_DOSEN = Z.KD_DOSEN
					WHERE A.KD_DOSEN = '$kd_dosen' AND A.KD_JBK = '$jbk'";
			return $this->db->query($sql)->result_array();
		}
	}
	
	function historiPendidikanDosen($kd_dosen){
		$sql = "SELECT KD_RIWAYAT, KD_DOSEN, NM_PRODI, BIDANG_ILMU, JENJANG, NM_PT, KD_NEGARA, 
				TO_CHAR(TGL_MULAI,'DD/MM/YYYY') AS TGL_MULAI, TO_CHAR(TGL_SELESAI,'DD/MM/YYYY') AS TGL_SELESAI, GELAR, SUMBER_DANA
				FROM BKD.BKD_RIWAYAT_PENDIDIKAN WHERE KD_DOSEN = '$kd_dosen'";
		return $this->db->query($sql)->result_array();
	}
	
	# PERUBAHAN ISI DATA :data
	function dataBKD(){
		$sql = "SELECT KD_BK, THN_AKADEMIK, SEMESTER, SUBSTR(THN_AKADEMIK,0,4) AS KD FROM BKD.BKD_BEBAN_KERJA";
		$data = $this->db->query($sql)->result_array();
		foreach ($data as $z => $a){
			if($a['SEMESTER'] == 'GENAP'){
				$sqlUpdate1 = "UPDATE BKD.BKD_BEBAN_KERJA SET KD_TA = '".$a['KD']."', KD_SMT = '2' WHERE KD_BK = '".$a['KD_BK']."'";
				$this->db->query($sqlUpdate1);
			}else{
				$sqlUpdate2 = "UPDATE BKD.BKD_BEBAN_KERJA SET KD_TA = '".$a['KD']."', KD_SMT = '1' WHERE KD_BK = '".$a['KD_BK']."'";
				$this->db->query($sqlUpdate2);			
			}
		}
		return true;
		
	}
	
	
	# BKD TAMBAHAN AKHIR TAHUN 2014
	# 1. KEGIATAN AKADEMIK
	# 2. NARASUMBER/PEMBICARA
	
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
	# TINGKAT KEGIATAN
	function tingkatKegiatan(){
		$sql = "SELECT * FROM BKD.BKD_TINGKAT_KEGIATAN ORDER BY KD_TINGKAT ASC";
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
	
}
