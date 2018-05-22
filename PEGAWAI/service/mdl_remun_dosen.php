<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi SIMPEG
 * @subpackage  SIMPEG Staff 
 * @category    Master data (3101)
 * @creator     Wihikan Mawi Wijna
 * @created     03-03-2014
*/
 
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
class Mdl_remun_dosen extends CI_Model {

    function __construct() {
        parent::__construct();
    }

	function lihat_aturan_agregasi(){
		$query = "SELECT * FROM remun_aturan_agregasi";
    	$sql = $this->db_remun->query($query);
    	return $sql->result_array();
	}
	function get_jenis_dosen(){
		$query = "SELECT status_dosen FROM remun_aturan_agregasi GROUP BY status_dosen";
    	$sql = $this->db_remun->query($query);
    	return $sql->result_array();
	}
	function edit_aturan_agregasi($id){
		$query = "SELECT * FROM remun_aturan_agregasi WHERE id_aturan='$id'";
    	$sql = $this->db_remun->query($query);
    	return $sql->row_array();
	}
	function edit_prosentase_nilai_max($id){
		$query = "SELECT * FROM nilai_max_komponen_agregasi WHERE id_aturan='$id'";
    	$sql = $this->db_remun->query($query);
    	return $sql->row_array();
	}
	function edit_nilai_batas_ikd($id){
		$query = "SELECT * FROM aturan_hitung_agregasi_ikd WHERE id_aturan='$id'";
    	$sql = $this->db_remun->query($query);
    	return $sql->row_array();
	}
	function update_aturan_agregasi($a, $b, $c, $d, $e, $f, $g, $h, $i, $j){
		/*if($h==1){
			$sql 	= "UPDATE remun_aturan_agregasi SET status = 0 WHERE status_dosen='$b'";
			$q 		= $this->db_remun->query($sql);
		}*/
		$query = "UPDATE remun_aturan_agregasi SET status_dosen='$b', kehadiran='$c', skr='$d', iku='$e', skp='$f', ikd= '$g', status='$h', tanggal=CURRENT_DATE, kd_ta = '$i', kd_smt='$j' WHERE id_aturan = '$a'";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function update_prosentase_nilai_max($a, $b, $c, $d, $e, $f, $g, $h, $i){
		$query = "UPDATE nilai_max_komponen_agregasi SET kehadiran='$b', skr='$c', iku='$d', skp='$e', ikd= '$f', status='$g', tanggal=CURRENT_DATE, kd_ta='$h', kd_smt='$i' WHERE id_aturan = '$a'";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function tambah_prosentase_nilai_max($a, $b, $c, $d, $e, $f, $g, $h){
		$query = "INSERT INTO nilai_max_komponen_agregasi (kehadiran, skr, iku, skp, ikd, status, tanggal, kd_ta, kd_smt) VALUES ('$a', '$b', '$c', '$d', '$e', '$f', CURRENT_DATE, '$g', '$h')";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function update_nilai_batas_ikd($a, $b, $c, $d, $e, $f, $g){
		$query = "UPDATE aturan_hitung_agregasi_ikd SET batas_bawah='$b', batas_atas='$c', status='$d', prosentase='$e', tanggal=CURRENT_DATE, kd_ta = '$f', kd_smt = '$g' WHERE id_aturan = '$a'";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function tambah_nilai_batas_ikd($a, $b, $c, $d, $e, $f){
		$query = "INSERT INTO aturan_hitung_agregasi_ikd (batas_bawah, batas_atas, status, prosentase, tanggal, kd_ta, kd_smt) VALUES('$a', '$b', '$c', '$d', CURRENT_DATE, '$e', '$f')";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function tambah_aturan_agregasi($a, $b, $c, $d, $e, $f, $g, $h, $i){
		/*if($g==1){
			$sql 	= "UPDATE remun_aturan_agregasi SET status = 0 WHERE status_dosen='$a'";
			$q 		= $this->db_remun->query($sql);
		}*/
		$query = "INSERT INTO remun_aturan_agregasi (status_dosen, kehadiran, skr, iku, skp, ikd, tanggal, status, kd_ta, kd_smt) VALUES ('$a', '$b', '$c', '$d', '$e', '$f', CURRENT_DATE, '$g', '$h', '$i')";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function get_all_aturan_agregasi_aktif(){
		$query = "SELECT * FROM remun_aturan_agregasi WHERE status =1";
		$sql = $this->db_remun->query($query);
		return $sql->result_array();
	}
	function get_aturan_agregasi_ikd(){
		$query = "SELECT * FROM aturan_hitung_agregasi_ikd ORDER BY id_aturan DESC";
		$sql = $this->db_remun->query($query);
		return $sql->result_array();
	}
	function get_nilai_max_komponen_agregasi(){
		$query = "SELECT * FROM nilai_max_komponen_agregasi ORDER BY id_aturan DESC";
		$sql = $this->db_remun->query($query);
		return $sql->result_array();
	}
	function tambah_jadwal_pengisian($a, $b, $c, $d, $e, $f){
		$query = "INSERT INTO jadwal_pengisian_remun(tgl_mulai, tgl_selesai, kd_ta, kd_smt, kategori, status) VALUES ('$a', '$b', '$c', '$d', '$e', '$f')";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function lihat_jadwal_pengisian(){
		$query = "SELECT * FROM jadwal_pengisian_remun";
    	$sql = $this->db_remun->query($query);
    	return $sql->result_array();
	}
	function edit_jadwal_pengisian_remun($id){
		$query = "SELECT * FROM jadwal_pengisian_remun WHERE id_jadwal='$id'";
    	$sql = $this->db_remun->query($query);
    	return $sql->row_array();
	}
	function update_jadwal_pengisian_dari_tambah($a, $b, $c, $d, $e, $f, $g){
		if($g==1){
			$sql 	= "UPDATE jadwal_pengisian_remun SET status = 0 WHERE id_jadwal = $a";
			$q 		= $this->db_remun->query($sql);
		}
		$query = "INSERT INTO jadwal_pengisian_remun(tgl_mulai, tgl_selesai, kd_ta, kd_smt, kategori, status) VALUES ('$b', '$c', '$d', '$e', '$f', '$g')";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function update_jadwal_pengisian($a, $b, $c, $d, $e, $f, $g){
		if($g==1){
			$sql 	= "UPDATE jadwal_pengisian_remun SET status = 0 WHERE kategori='$f' AND kd_ta='$d' AND kd_smt='$e'";
			$q 		= $this->db_remun->query($sql);
		}
		$query = "UPDATE jadwal_pengisian_remun SET tgl_mulai='$b', tgl_selesai='$c', kd_ta='$d', kd_smt='$e', kategori='$f', status='$g' WHERE id_jadwal = '$a'";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function cek_jadwal_pengisian($a, $b, $c){
		$query = "SELECT * from jadwal_pengisian_remun where kd_ta = '$a' AND kd_smt='$b' AND kategori='$c'";
		$sql = $this->db_remun->query($query);
		return $sql->row_array();
	}
	function get_jadwal_pengisian_remunerasi_periode_saat_ini($a, $b, $c, $d){
		$query = "SELECT * FROM jadwal_pengisian_remun WHERE kd_ta='$a' AND kd_smt='$b' AND kategori='$c' AND status='$d'";
		$sql = $this->db_remun->query($query);
		return $sql->row_array();
	}
	function tambah_asesor_remunerasi($a, $b, $c, $d, $e){
		$query = "INSERT INTO asesor_remunerasi (kd_fakultas, nip, kd_ta, kd_smt, status) VALUES ('$a', '$b', '$c', '$d', '$e')";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function get_asesor_remunerasi(){
		$query = "SELECT * FROM asesor_remunerasi ORDER BY kd_fakultas, kd_ta, kd_smt, nip ASC, status=1";
		$sql = $this->db_remun->query($query);
    	return $sql->result_array();
	}
	function get_asesor_remunerasi_by_kd_dosen($kd_dosen, $kd_ta, $kd_smt, $status){
		$query = "SELECT * FROM asesor_remunerasi WHERE nip = '$kd_dosen' AND kd_ta='$kd_ta' AND kd_smt='$kd_smt' AND status = '$status'";
		$sql = $this->db_remun->query($query);
    	return $sql->row_array();
	}
	function cek_daftar_asesor_remunerasi($nip, $kd_fak, $kd_ta, $kd_smt){
		$query = "SELECT * FROM asesor_remunerasi WHERE kd_fakultas = '$kd_fak' AND nip = '$nip' AND kd_ta = '$kd_ta' AND kd_smt='$kd_smt'";
		$sql = $this->db_remun->query($query);
    	return $sql->result_array();
	}
	function insert_asesor_remunerasi($nip, $kd_fak, $kd_ta, $kd_smt, $status){
		$query = "INSERT INTO asesor_remunerasi (kd_fakultas, nip, kd_ta, kd_smt, status) VALUES ('$kd_fak', '$nip', '$kd_ta', '$kd_smt', '$status')";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function edit_asesor_remunerasi($id_asesor){
		$query = "SELECT * FROM asesor_remunerasi WHERE id_asesor='$id_asesor'";
    	$sql = $this->db_remun->query($query);
    	return $sql->row_array();
	}
	function update_asesor_remunerasi($id_asesor, $kd_fak, $nip, $kd_ta, $kd_smt, $status){
		$query = "UPDATE asesor_remunerasi SET kd_fakultas = '$kd_fak', nip = '$nip', kd_ta='$kd_ta', kd_smt='$kd_smt', status='$status' WHERE id_asesor = '$id_asesor'";
		$sql = $this->db_remun->query($query);
		return $sql;
	}
	function get_asesor_remunerasi_by_kd_fak($kd_fak){
		$query = "SELECT nip FROM asesor_remunerasi WHERE kd_fakultas = '$kd_fak'";
		$sql = $this->db_remun->query($query);
		return $sql->result_array();
	}

	function get_jadwal_kinerja_remun(){
		$sql = "SELECT * FROM jadwal_kinerja_remun ORDER BY ta DESC, periode ASC";
		$q   = $this->db_remun->query($sql);
		return $q->result_array();
	}

	function get_jadwal_kinerja_by_tgl($tanggal){
		//format tanggal 'dd-mm-yyy';
		$sql 		= "SELECT * FROM jadwal_kinerja_remun WHERE to_date('$tanggal', 'dd-mm-yyyy') >= tgl_mulai_per AND to_date('$tanggal', 'dd-mm-yyyy') <= tgl_akhir_per";
		$q 			= $this->db_remun->query($sql);
		return $q->row_array();
	}

	function get_jadwal_kinerja_by_param($ta, $periode){
		$sql = "SELECT TO_CHAR(tgl_mulai_per, 'dd-mm-yyyy') AS tgl_mulai_per, TO_CHAR(tgl_akhir_per, 'dd-mm-yyyy') AS tgl_akhir_per FROM jadwal_kinerja_remun WHERE ta = '$ta' AND periode = '$periode'";
		$q   = $this->db_remun->query($sql);

		return $q->row_array();
	}

	function get_current_jadwal_kinerja_remun(){
		$sql 		= "SELECT * FROM jadwal_kinerja_remun WHERE CURRENT_DATE >= tgl_mulai_per AND CURRENT_DATE <= tgl_akhir_per";
		$q 			= $this->db_remun->query($sql);
		return $q->row_array();
	}

	function insert_jadwal_kinerja_remun($ta, $periode, $awal, $akhir, $nip){
		$sql 	= "INSERT INTO jadwal_kinerja_remun (ta, periode, tgl_mulai_per, tgl_akhir_per, petugas) VALUES ('$ta', '$periode', to_date('$awal', 'dd-mm-yyyy'), to_date('$akhir', 'dd-mm-yyyy'), '$nip')";
		$q 		= $this->db_remun->query($sql);
		return $q;
	}

	function delete_jadwal_kinerja_remun($id){
		$sql 	= "DELETE FROM jadwal_kinerja_remun WHERE id = '$id'";
		$q 		= $this->db_remun->query($sql);
		return $q;
	}


	//tambahan aturan prosentase berdasarkan 
	function get_current_aturan_agregasi($ta, $per){
		$sql 	= "SELECT * FROM jadwal_kinerja_remun";
		$q 		= $this->remun_db->query($sql);
		return $q->result_array();
	}


}
