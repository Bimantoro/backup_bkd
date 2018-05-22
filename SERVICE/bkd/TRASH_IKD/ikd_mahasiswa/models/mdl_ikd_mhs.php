<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi IKD
 * @subpackage  IKD mhs
 * @category    Master data (1)
 * @creator     Fadli Ikhsan Pratama
 * @created     14-11-2012
*/
 
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
class Mdl_ikd_mhs extends CI_Model {

    function __construct() {
        parent::__construct();		
    }	
	
	function mdl_ikd_cek_waktu() {
        $waktu = $this->db->query("SELECT TO_CHAR(A.FIRST_DATE)FIRST_DATE, TO_CHAR(A.LAST_DATE)LAST_DATE, TO_CHAR(A.FIRST_DATE, '".DATE_FORMAT_FULL."') AS FIRST_DATE_F, TO_CHAR(A.LAST_DATE, '".DATE_FORMAT_FULL."') AS LAST_DATE_F FROM IKD.IKD_D_WAKTU_KUISIONER A")->result_array();
        return $waktu;
    }
	
	function outSoal() {
        $outSoal = $this->db->query("SELECT * FROM IKD.IKD_MD_SOALBARU ORDER BY ID_SOAL ASC")->result_array();
        return $outSoal;
    }
	
	function outCekJwban($nimMhs='',$kdKelas='',$idSOal) {        
		$outId = $this->db->query("SELECT DISTINCT A.ID_SOAL, A.PILIHAN FROM IKD.IKD_D_DETAIL_JAWABAN A, IKD.IKD_D_PENGISI_JAWABAN B 
									WHERE A.ID_IKD_JWB = B.ID_IKD_JWB AND B.NIM = '".$nimMhs."' AND B.KD_KELAS = '".$kdKelas."' 
									AND A.ID_SOAL = ".$idSOal)->result_array();
		return $outId;
    }
	
	function outCekKomen($nimMhs='',$kdKelas='',$kdDosen) {        
		$outId = $this->db->query("SELECT KOMENTAR FROM IKD.IKD_D_PENGISI_JAWABAN WHERE 
					NIM = '".$nimMhs."' AND KD_KELAS = '".$kdKelas."' AND KD_DOSEN = '".$kdDosen."'")->result_array();
		return $outId;
    }
	
	function cekSudahIkd($nimMhs='',$kdTa='',$kdSmt='') {        
		#$outId = $this->db->query("SELECT KD_MK,KD_DOSEN,KD_KELAS FROM IKD.IKD_D_HASIL_QUISIONER WHERE 
		#			NIM = '".$nimMhs."' AND KD_TA = '".$kdTa."' AND KD_SMT = '".$kdSmt."'")->result_array();
		$outId = $this->db->query("SELECT DISTINCT KD_KELAS FROM IKD.IKD_D_HASIL_QUISIONER WHERE 
					NIM = '".$nimMhs."' AND KD_TA = '".$kdTa."' AND KD_SMT = '".$kdSmt."'")->result_array();
		return $outId;
    }
	
	function cekSdhKuesioner($nimMhs='',$kdKelas='',$kdDosen='') {        
		$outId = $this->db->query("SELECT HASIL, NILAI_MAX FROM IKD.IKD_D_HASIL_QUISIONER WHERE NIM = '".$nimMhs."'
					AND KD_KELAS = '".$kdKelas."' AND KD_DOSEN = '".$kdDosen."'")->result_array();
		return $outId;
    }
	
	function cekInsOrUpd($nimMhs='',$kdKelas='',$kdDosen='') {        
		$outId = $this->db->query("SELECT * FROM IKD.IKD_D_PENGISI_JAWABAN WHERE NIM = '".$nimMhs."' 
					AND KD_KELAS = '".$kdKelas."' AND KD_DOSEN = '".$kdDosen."'")->result_array();
		return $outId;
    }
	
	function getIdPengisiIkd($nimMhs='',$kdKelas='',$kdDosen='') {        
		$outId = $this->db->query("SELECT ID_IKD_JWB FROM IKD.IKD_D_PENGISI_JAWABAN 
					WHERE NIM = '".$nimMhs."' AND KD_KELAS = '".$kdKelas."' AND KD_DOSEN = '".$kdDosen."'")->result_array();
		return $outId;
    }
	
	function inPengisi($nimMhs='',$kdDosen='',$kdMk='',$kdKelas='',$kritikSaran='',$kdTa='',$kdSmt=''){
		$this->db->query("INSERT INTO IKD.IKD_D_PENGISI_JAWABAN	(NIM, KD_DOSEN, KD_MK, KD_KELAS, KOMENTAR, KD_TA, KD_SMT, TGL_INPUT) 
				VALUES ('".$nimMhs."','".$kdDosen."','".$kdMk."','".$kdKelas."','".$kritikSaran."','".$kdTa."','".$kdSmt."',SYSDATE)");
		return ($this->db->affected_rows() > 0) ? true : false;
    }
	
	function updPengisi($idIkdJwb,$nimMhs='',$kdKelas='',$kritikSaran=''){
		#$this->db->query("INSERT INTO IKD.IKD_D_PENGISI_JAWABAN	(NIM, KD_DOSEN, KD_MK, KD_KELAS, KOMENTAR, KD_TA, KD_SMT, TGL_INPUT) 
				#VALUES ('".$nimMhs."','".$kdDosen."','".$kdMk."','".$kdKelas."','".$kritikSaran."','".$kdTa."','".$kdSmt."',SYSDATE)");
		#return ($this->db->affected_rows() > 0) ? true : false;
		$this->db->query("UPDATE IKD.IKD_D_PENGISI_JAWABAN
					SET
					KOMENTAR 		= '".$kritikSaran."',
					TGL_INPUT		= SYSDATE					
					WHERE NIM		= '".$nimMhs."' AND ID_IKD_JWB = ".$idIkdJwb." AND KD_KELAS = '".$kdKelas."'");
		return ($this->db->affected_rows() > 0) ? true : false;
    }
	
	function cekDetailJwb($idIkdJwb,$idSoal){
		return $this->db->query("SELECT * FROM IKD.IKD_D_DETAIL_JAWABAN WHERE ID_IKD_JWB = ".$idIkdJwb.", ID_SOAL = ".$idSoal)->result_array();
    }
	
	function inDetailJwb($idIkdJwb,$idSoal,$pilihan=''){
		$this->db->query("INSERT INTO IKD.IKD_D_DETAIL_JAWABAN(ID_IKD_JWB, ID_SOAL, PILIHAN) 
				VALUES (".$idIkdJwb.",".$idSoal.",'".$pilihan."')");
		return ($this->db->affected_rows() > 0) ? true : false;
    }
	
	function updDetailJwb($idIkdJwb,$idSoal,$pilihan=''){
		$this->db->query("UPDATE IKD.IKD_D_DETAIL_JAWABAN
					SET
					PILIHAN 			= '".$pilihan."'
					WHERE ID_IKD_JWB	= ".$idIkdJwb." AND ID_SOAL = ".$idSoal);
		return ($this->db->affected_rows() > 0) ? true : false;
    }
	
	function cekHslPerhitungan($kdTa='',$kdSmt='',$nimMhs='',$kdDosen='',$kdKelas=''){
		return $this->db->query("SELECT * FROM IKD.IKD_D_HASIL_QUISIONER WHERE KD_TA = '".$kdTa."' AND KD_SMT = '".$kdSmt."' AND NIM = '".$nimMhs."' AND KD_DOSEN = '".$kdDosen."' AND KD_KELAS = '".$kdKelas."'")->result_array();
    }
	
	function inHslPerhitungan($kdMk='',$nimMhs='',$sumJwb_,$kdSmt='',$kdTa='',$kdDosen='',$kritikSaran='',$kelasPararel='',$kdKelas='',$nilaiMax=''){
		$this->db->query("INSERT INTO IKD.IKD_D_HASIL_QUISIONER(KD_MK, NIM, HASIL, KD_SMT, KD_TA, KD_DOSEN, COMMENT_Q, KELAS, KD_KELAS, NILAI_MAX) 
				VALUES ('".$kdMk."','".$nimMhs."',".$sumJwb_.",'".$kdSmt."','".$kdTa."','".$kdDosen."','".$kritikSaran."','".$kelasPararel."','".$kdKelas."',".$nilaiMax.")");
		return ($this->db->affected_rows() > 0) ? true : false;
    }
	
	function updHslPerhitungan($nimMhs='',$sumJwb_,$kdDosen='',$kritikSaran='',$kdKelas='',$nilaiMax=''){
		$this->db->query("UPDATE IKD.IKD_D_HASIL_QUISIONER
					SET
					COMMENT_Q	= '".$kritikSaran."',
					HASIL 		= ".$sumJwb_.",
					NILAI_MAX	= ".$nilaiMax."
					WHERE NIM	= '".$nimMhs."' AND KD_DOSEN = '".$kdDosen."' AND KD_KELAS = '".$kdKelas."'");
		return ($this->db->affected_rows() > 0) ? true : false;
    }
	
	function delPengisi($nimMhs='',$kdKelas='',$kdDosen=''){
		$this->db->query("DELETE FROM IKD.IKD_D_PENGISI_JAWABAN WHERE NIM = '".$nimMhs."' AND 
				KD_KELAS = '".$kdKelas."' AND KD_DOSEN = '".$kdDosen."'");
		return ($this->db->affected_rows() > 0) ? true : false;
    }
	
	function delDetailJwb($idIkdJwb=''){
		$this->db->query("DELETE FROM IKD.IKD_D_DETAIL_JAWABAN WHERE ID_IKD_JWB = ".$idIkdJwb);
		return ($this->db->affected_rows() > 0) ? true : false;
    }
	
	function outJawaban() {
        $outJawaban = $this->db->query("SELECT * FROM IKD.IKD_MD_JAWABANSOAL ORDER BY ID_SOAL DESC")->result_array();
        return $outJawaban;
    }
	
	function _mdl_ikd_proc_cekisilengkap($nim) {
		/* $array__ = array(
			array('method' => 'IN', 'name' => ':nim', 'value' => $nim, 'length' => 12, 'type' => SQLT_CHR),
			array('method' => 'OUT', 'name' => ':lengkap', 'value' => '', 'length' => 2, 'type' => SQLT_CHR),
		);
		return $this->ikd->call_procedure('P_CEK_ISI_IKD_LENGKAP', $array__, 'COMPLEX'); */
		#return $datapost;
		return true;
	}
	
	function mdl_ikd_getmatkulterakhirmhs($cekdata='') {
        /* $data = $this->db->query("SELECT G.NIM, A.KD_KRS, DECODE(A.STATUS_ULANG,'B','BARU','U','ULANG') STATUS_ULANG, A.NILAI, A.BOBOT_NILAI, E.TA, F.NM_SMT ,B.KD_KELAS,B.KD_KUR,B.KD_PRODI,B.KD_MK,B.KD_TA, B.KD_SMT,B.KELAS_PARAREL,B.KD_DOSEN,B.TATAP,B.SKS,B.MIN_PESERTA,B.MAX_PESERTA,B.TERISI,B.KD_HARI,B.HARI,B.KD_RUANG,B.KETERANGAN,B.JAM_MULAI,B.NO_RUANG,B.SEMESTER_PAKET,B.NM_PRODI,B.JAM_SELESAI,B.NM_MK,B.NM_MK_SINGKAT,B.JENIS_MK,B.NM_JENIS_MK,B.NM_DOSEN,B.NIP,B.JADWAL1,B.JADWAL2,C.URUT 
									FROM SIA.D_DETAIL_KRS A, SIA.V_KELAS B, SIA.D_URUT_KELAS C , SIA.D_KRS D, SIA.D_TA E, SIA.D_SEMESTER F, SIA.D_MAHASISWA G
									WHERE A.KD_KELAS = B.KD_KELAS AND A.KD_KRS = C.KD_KRS (+) AND A.KD_KELAS = C.KD_KELAS (+) AND D.NIM = '".$cekdata."' AND D.KD_KRS = A.KD_KRS AND 
									D.SEMESTER = (SELECT MAX(SEMESTER) FROM SIA.D_KRS WHERE NIM = '".$cekdata."') AND F.KD_SMT = D.KD_SMT AND 
									E.KD_TA = (SELECT MAX(KD_TA) FROM SIA.D_KRS WHERE NIM = '".$cekdata."') AND 
									G.NIM = '".$cekdata."' AND G.STATUS = 'A'")->result_array();
        return $data; */
		return true;
    }
	
	function mdl_ikd_gettampiltahun($cekdata='') {
        /* $data = $this->db->query("SELECT A.KD_TA, A.TA, C.NM_SMT, C.KD_SMT FROM D_TA A, D_KRS B, D_SEMESTER C WHERE B.NIM= '".$cekdata."' AND A.KD_TA = B.KD_TA AND B.KD_SMT= C.KD_SMT ORDER BY B.SEMESTER DESC")->result_array();
        return $data; */
		return true;
    }
	
	function mdl_ikd_getsoalkuesioneraktif() {
        $data = $this->db->query("SELECT KD_SOAL,ISI,KET,JWB_A,JWB_B,JWB_C,JWB_D FROM IKD.IKD_D_SOAL_QUISIONER WHERE KET = 'Y' ORDER BY KD_SOAL ASC")->result_array();
        return $data;
    }
	
	function mdl_ikd_cekvalidasikuesioner($cekdata1='',$cekdata2='',$cekdata3='') {
        $data = $this->db->query("SELECT * FROM IKD.IKD_D_HASIL_QUISIONER WHERE NIM='".$cekdata1."' AND KD_TA = '".$cekdata2."' AND KD_SMT = '".$cekdata3."'")->result_array();
        return $data;
    }
	
	function mdl_ikd_inserthasilkuesioner($cekdata=''){						
		return $this->db->insert('IKD.IKD_D_HASIL_QUISIONER',$cekdata)->result_array();
	}
	
	function mdl_ikd_getlihatnilai($cekdata1='',$cekdata2='',$cekdata3='') {
        /* $data = $this->db->query("SELECT A.HASIL, C.NM_MK, B.NM_DOSEN||', '||B.GELAR NM_DOSEN
									FROM IKD.IKD_D_HASIL_QUISIONER A, D_DOSEN B, V_KELAS C
									WHERE C.KD_KELAS = A.KD_KELAS AND B.KD_DOSEN = A.KD_DOSEN AND C.KD_MK = A.KD_MK AND A.NIM = '".$cekdata1."' AND A.KD_TA = '".$cekdata2."' AND A.KD_SMT = '".$cekdata3."'")->result_array();
        return $data; */
		return true;
    }	

	function mdl_ikd_gettahunhasilikd($cekdata='') {
        /* $data = $this->db->query("SELECT DISTINCT A.KD_TA, (SELECT B.TA FROM D_TA B WHERE B.KD_TA = A.KD_TA) AS TA FROM IKD.IKD_D_HASIL_QUISIONER A WHERE A.NIM = '".$cekdata."' ORDER BY A.KD_TA ASC")->result_array();
        return $data; */
		return true;
    }
	
	function mdl_ikd_getsmthasilikd($cekdata='') {
        /* $data = $this->db->query("SELECT DISTINCT A.KD_SMT, (SELECT B.NM_SMT FROM D_SEMESTER B WHERE B.KD_SMT = A.KD_SMT) AS NM_SMT FROM IKD.IKD_D_HASIL_QUISIONER A WHERE A.NIM = '".$cekdata."' ORDER BY A.KD_SMT ASC")->result_array();
        return $data; */
		return true;
    }
	
	function get_kategori_kepuasan($kd_ta,$kd_smt) {
        $data = $this->db->query("SELECT * FROM IKD.IKD_PUAS_KAT WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND STATUS = '1' ORDER BY ID_KAT ASC")->result_array();
        return $data;
    }	
	
	function get_pertanyaan_per_kat($kd_ta,$kd_smt,$id_kat) {
        $data = $this->db->query("SELECT * FROM IKD.IKD_PUAS_TANY_P B WHERE B.ID_KAT='".$id_kat."' AND B.STATUS='1' AND B.KD_TA='".$kd_ta."' AND B.KD_SMT='".$kd_smt."' ORDER BY B.ID_TANY ASC")->result_array();
        return $data;
    }	
	
	function cek_jawab_pertanyaan_kepuasan($kd_ta,$kd_smt,$nim,$id_tany) {
        return $this->db->query("SELECT * FROM IKD.IKD_PUAS_JWB WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND NIM = '".$nim."' AND ID_TANY = '".$id_tany."'")->result_array();
    }	
	
	function jawab_pertanyaan_kepuasan($kd_ta,$kd_smt,$nim,$id_tany,$harap,$nyata) {
        return $this->db->query("INSERT INTO IKD.IKD_PUAS_JWB (KD_TA,KD_SMT,NIM,ID_TANY,HARAP,NYATA) VALUES ('".$kd_ta."','".$kd_smt."','".$nim."','".$id_tany."','".$harap."','".$nyata."')");
    }	
	
	function jawab_pertanyaan_kepuasan_baru($kd_ta,$kd_smt,$nim,$id_tany,$harap,$nyata) {
        return $this->db->query("UPDATE IKD.IKD_PUAS_JWB SET HARAP = '".$harap."', NYATA = '".$nyata."' WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND NIM = '".$nim."' AND ID_TANY = '".$id_tany."'");
    }	
	
	function get_jawaban_mhs_tasmtnim($kd_ta,$kd_smt,$nim) {
        return $this->db->query("SELECT * FROM IKD.IKD_PUAS_JWB WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND NIM = '".$nim."'")->result_array();
    }	
	
	function get_puas_point() {
        return $this->db->query("SELECT * FROM IKD.IKD_PUAS_POINT")->result_array();
    }	
	
	function get_max_puas_point($kd_ta,$kd_smt) {
        return $this->db->query("SELECT MAX(GREATEST(HARAP,NYATA)) MAX_POINT FROM IKD.IKD_PUAS_JWB WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."'")->result_array();
    }	
	
	function cek_isi_Kuesioner($kd_ta,$kd_smt,$nim,$jml){
		$array__ = array(
			array('method' => 'IN', 'name' => ':kdta1', 'value' => $kd_ta, 'length' => 32, 'type' => SQLT_CHR),
			array('method' => 'IN', 'name' => ':kdsmt1', 'value' => $kd_smt, 'length' => 32, 'type' => SQLT_CHR),
			array('method' => 'IN', 'name' => ':nim1', 'value' => $nim, 'length' => 32, 'type' => SQLT_CHR),
			array('method' => 'IN', 'name' => ':jml1', 'value' => $jml, 'length' => 32, 'type' => SQLT_INT),
			array('method' => 'OUT', 'name' => ':hasil1', 'value' => '', 'length' => 32, 'type' => SQLT_INT)
		);
		return $this->db->call_procedure('IKD.P_CEK_SUDAH_ISI', $array__, 'COMPLEX');
	}
	
	
}
?>