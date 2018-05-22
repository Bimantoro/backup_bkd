<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi IKD
 * @subpackage  IKD Admin
 * @category    Master data (1)
 * @creator     Fadli Ikhsan Pratama
 * @created     14-11-2012
 * recoded 		Rischan Mafrur
 * API 			10 Juni 2013
*/
 //
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
class Mdl_ikd_admin extends CI_Model {

    function __construct() {
        parent::__construct();		
    }	

	function ambil_idmax_soal_baru() {      
        $tampil = $this->db->query("SELECT MAX(ID_SOAL) ID_SOAL FROM IKD.IKD_MD_SOALBARU")->result_array();
		return $tampil;
    }
	
	function ambil_soal_baru() {      
        $tampil = $this->db->query("SELECT * FROM IKD.IKD_MD_SOALBARU ORDER BY ID_SOAL ASC")->result_array();
		return $tampil;
    }
	
	function ambil_soal_baru_det($id) {      
        $tampil = $this->db->query("SELECT A.*, B.PILIHAN, B.JAWABAN, B.POINT FROM IKD.IKD_MD_SOALBARU A, IKD.IKD_MD_JAWABANSOAL B WHERE A.ID_SOAL = B.ID_SOAL AND A.ID_SOAL = '".$id."' ORDER BY A.ID_SOAL, B.PILIHAN ASC")->result_array();
		return $tampil;
    }
	
	function ubah_soal_baru($id,$soal,$ket) {
        return $this->db->query("UPDATE IKD.IKD_MD_SOALBARU SET SOAL = '".$soal."', KET = '".$ket."' WHERE ID_SOAL = '".$id."'");
    }
	
	function ubah_point_soal_baru($id,$pil,$jwb,$point) {
        return $this->db->query("UPDATE IKD.IKD_MD_JAWABANSOAL SET JAWABAN = '".$jwb."', POINT = '".$point."' WHERE ID_SOAL = '".$id."' AND PILIHAN = '".$pil."'");
    }
	
	function insert_soal_baru($soal,$ket) {
        return $this->db->query("INSERT INTO IKD.IKD_MD_SOALBARU (SOAL,KET) VALUES ('".$soal."','".$ket."')");
    }
	
	function insert_point_soal_baru($id,$pil,$jwb,$point) {
        return $this->db->query("INSERT INTO IKD.IKD_MD_JAWABANSOAL (ID_SOAL,PILIHAN,JAWABAN,POINT) VALUES ('".$id."','".$pil."','".$jwb."','".$point."')");
    }
	
	function hapus_soal_baru($id) {
        return $this->db->query("DELETE FROM IKD.IKD_MD_SOALBARU WHERE ID_SOAL = '".$id."'");
    }
	
    function Cek_Hari_Libur($tanggal) {
        $ctl = $this->db->query("SELECT TANGGAL FROM IKD.IKD_D_HARI_LIBUR_NASIONAL WHERE TANGGAL = TO_DATE ('".$tanggal."','MM/DD/YYYY')")->result_array();
        return $ctl;
    }

	function hapustanggallibur($id) {        
		$this->db->where('ID_HR', $id);
		$this->db->delete('IKD.IKD_D_HARI_LIBUR_NASIONAL'); 		
    }

    function Insert_Tanggal_Libur($tanggal,$keterangan) {
        $tgl_libur = "INSERT INTO IKD.IKD_D_HARI_LIBUR_NASIONAL (TANGGAL, KETERANGAN) 
					VALUES (TO_DATE(?,'MM/DD/YYYY'),?)";
        $this->db->query($tgl_libur,array($tanggal,strtoupper($keterangan)))->result_array();
    }
	
	function insertkuesionerman($tabel,$dt) {
        /* $inserttl = "INSERT INTO IKD_D_HASIL_QUISIONER (KD_MK,HASIL,KD_TA,KD_SMT,KD_DOSEN,KELAS,KD_KELAS) VALUES (?,?,?,?,?,?,?)";
        $this->db->query($inserttl,array($kd_mk,$nilai,$ta,$smt,$kd_dosen,$klsp,$kd_kelas))->result_array(); */
		$this->db->insert($tabel, $dt);
    }

    function Tampil_Hari_Libur() {        
        $tampil = $this->db->query("SELECT ID_HR,TO_CHAR(TANGGAL, 'DD MONTH YYYY','nls_date_language = INDONESIAN') TANGGAL,KETERANGAN FROM IKD.IKD_D_HARI_LIBUR_NASIONAL ORDER BY ID_HR DESC")->result_array();
		return $tampil;
    }

    function Jumlah_Hari_Libur() {
        $tmp_tgl_libur = $this->db->query("SELECT * FROM IKD.IKD_D_HARI_LIBUR_NASIONAL ORDER BY ID_HR ")->result_array();
        return $tmp_tgl_libur;
    }

    function Insert_Tgl_Pengumpulan($kd_mk, $kd_dosen, $kd_ta, $kd_smt, $tgl_ujian, $tgl_pengumpulan, $hasil, $kd_kls, $kd_kelas) {
        $sql = "INSERT INTO IKD.IKD_D_PENGUMPULAN_BERKAS (KD_MK, KD_DOSEN, KD_TA, KD_SMT, TGL_UJIAN, TGL_PENGUMPULAN, HASIL, KD_KLS, KD_KELAS) 
					VALUES ('".$kd_mk."','".$kd_dosen."',".$kd_ta.",'".$kd_smt."',TO_DATE ('".$tgl_ujian."','MM/DD/YYYY'), TO_DATE ('".$tgl_pengumpulan."','MM/DD/YYYY'),".$hasil.",'".$kd_kls."','".$kd_kelas."')";
        $this->db->query($sql)->result_array();
    }

    function Update_Tgl_Pengumpulan($kd_berkas, $kd_mk, $kd_dosen, $kd_ta, $kd_smt, $tgl_ujian, $tgl_pengumpulan, $hasil, $kd_kls, $kd_kelas) {
        $utp = $this->db->query("UPDATE IKD.IKD_D_PENGUMPULAN_BERKAS SET KD_MK='".$kd_mk."', KD_DOSEN ='".$kd_dosen."', KD_TA ='".$kd_ta."', KD_SMT='".$kd_smt."', TGL_UJIAN = TO_DATE ('".$tgl_ujian."','MM/DD/YYYY'),TGL_PENGUMPULAN = TO_DATE ('".$tgl_pengumpulan."','MM/DD/YYYY'),HASIL ='".$hasil."', KD_KLS ='".$kd_kls."', KD_KELAS ='".$kd_kelas."' WHERE KD_BERKAS = ".$kd_berkas."")->result_array();
        return $utp;
    }
    
    function printberkasdosen2($kd_dosen, $tahun, $semester) {
		$pbd = $this->db->query("SELECT KD_MK, KD_KELAS, KELAS, ROUND((SUM (HASIL) / COUNT (HASIL)* 0.1),2) AS K3, COUNT (HASIL) AS RES
									FROM IKD.IKD_D_HASIL_QUISIONER 
									WHERE KD_DOSEN = '".$kd_dosen."' AND KD_TA = '".$tahun."' AND KD_SMT = '".$semester."' AND NIM IS NOT NULL
									GROUP BY KD_MK, KD_KELAS, KELAS
									ORDER BY KD_MK, KELAS ASC")->result_array();
		#array($kd_dosen,$tahun,$semester);
		
        return $pbd;
    }
    
    function printberkasprodi($kd_prodi, $tahun, $semester) {
        /* $pbd2 = $this->db->query("SELECT  E.KD_FAK, INITCAP(H.NM_DOSEN||', '||H.GELAR) NM_DOSEN,B.KD_DOSEN, (SELECT COUNT(Z.KD_KELAS) FROM SIA.D_MATERI_KULIAH Z WHERE Z.KD_KELAS = A.KD_KELAS) AS K1,(D.HASIL)AS K2,(SUM (C.HASIL) / COUNT (C.HASIL)* 0.1) AS K3,B.NM_MK,B.KD_KELAS, B.KD_PRODI, B.KD_MK, B.KELAS_PARAREL, B.SKS, B.KD_DOSEN, B.KD_TA, B.KD_SMT, B.NM_PRODI
                                    FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B, IKD.IKD_D_HASIL_QUISIONER C, IKD.IKD_D_PENGUMPULAN_BERKAS D, SIA.MASTER_FAK E, SIA.MASTER_PRODI F, SIA.MASTER_JURUSAN G, SIA.D_DOSEN H
                                    WHERE A.KD_KELAS (+) = B.KD_KELAS AND H.KD_PRODI = '".$kd_prodi."' AND B.KD_TA = '".$tahun."' AND B.KD_SMT = '".$semester."' AND B.KD_DOSEN = C.KD_DOSEN AND B.KD_MK = C.KD_MK AND B.KD_KELAS = C.KD_KELAS AND B.KD_TA = C.KD_TA AND B.KD_SMT = C.KD_SMT AND B.KD_DOSEN = D.KD_DOSEN AND B.KD_KELAS = D.KD_KELAS AND B.KD_TA = D.KD_TA AND B.KD_SMT = D.KD_SMT
                                    AND B.KD_PRODI = F.KD_PRODI AND F.KD_JURUSAN = G.KD_JURUSAN AND G.KD_FAK = E.KD_FAK AND H.KD_DOSEN = B.KD_DOSEN
                                    GROUP BY B.KD_DOSEN, E.KD_FAK, A.KD_KELAS, H.NM_DOSEN, H.GELAR, D.HASIL, C.HASIL , B.NM_MK, B.KD_KELAS, B.KD_PRODI, B.KD_MK, B.KELAS_PARAREL, B.SKS, B.KD_DOSEN, B.KD_TA, B.KD_SMT, B.NM_PRODI ORDER BY H.NM_DOSEN ASC"
									)->result_array(); */
		$pbd2 = $this->db->query("SELECT * FROM IKD.IKD_D_REKAP_PRODI WHERE KD_PRODI = '".$kd_prodi."' AND KD_TA = '".$tahun."' AND KD_SMT = '".$semester."' ORDER BY KD_DOSEN DESC")->result_array(); 
        return $pbd2;
    }
	
	function cekrekapprodi($cekdata1='',$cekdata2='',$cekdata3='',$cekdata4=''){
		$qry_cek = $this->db->query("SELECT * FROM IKD.IKD_D_REKAP_PRODI WHERE KD_DOSEN = '".$cekdata1."' AND KD_PRODI = '".$cekdata2."' AND KD_TA = '".$cekdata3."' AND KD_SMT = '".$cekdata4."'")->result_array();
		return $qry_cek;
	}
	
	function cekrekapfak($cekdata1='',$cekdata2='',$cekdata3=''){
		$qry_cek = $this->db->query("SELECT * FROM IKD.IKD_D_REKAP_FAK WHERE KD_PRODI = '".$cekdata1."' AND KD_TA = '".$cekdata2."' AND KD_SMT = '".$cekdata3."'")->result_array();
		return $qry_cek;
	}
	
	function insertrekapprodi($cekdata1='',$cekdata2='',$cekdata3='',$cekdata4='',$cekdata5='',$cekdata6='',$cekdata7='',$pene='',$publ='',$peng='',$penu='',$pemb=''){
		$hasil = $this->db->query("INSERT INTO IKD.IKD_D_REKAP_PRODI
									(KD_DOSEN, KD_PRODI, KD_TA, KD_SMT, NILAI_K1, NILAI_K2, NILAI_K3, TANGGAL_INPUT, CAP_PENE, CAP_PUBL, CAP_PENG, CAP_PENU, CAP_PEMB) 
									VALUES('".$cekdata1."','".$cekdata2."','".$cekdata3."','".$cekdata4."','".$cekdata5."','".$cekdata6."','".$cekdata7."',SYSDATE,'".$pene."','".$publ."','".$peng."','".$penu."')")->result_array();
        return $hasil;
	}
	
	function insertrekapfak($cekdata1='',$cekdata2='',$cekdata3='',$cekdata4='',$cekdata5='',$cekdata6='',$cekdata7='',$pene='',$publ='',$peng='',$penu='',$pemb=''){		
		$hasil = $this->db->query("INSERT INTO IKD.IKD_D_REKAP_FAK
									(KD_FAK, KD_PRODI, KD_TA, KD_SMT, NILAI_K1, NILAI_K2, NILAI_K3, TANGGAL_INPUT, CAP_PENE, CAP_PUBL, CAP_PENG, CAP_PENU, CAP_PEMB) 
									VALUES('".$cekdata1."','".$cekdata2."','".$cekdata3."','".$cekdata4."','".$cekdata5."','".$cekdata6."','".$cekdata7."',SYSDATE,'".$pene."','".$publ."','".$peng."','".$penu."')")->result_array();
        return $hasil;
	}
	
	function updaterekapprodi($cekdata1='',$cekdata2='',$cekdata3='',$cekdata4='',$cekdata5='',$cekdata6='',$cekdata7='',$pene='',$publ='',$peng='',$penu='',$pemb=''){		
		$hasil = $this->db->query("UPDATE IKD.IKD_D_REKAP_PRODI
					SET 
					NILAI_K1 		= '".$cekdata5."',
					NILAI_K2 		= '".$cekdata6."',
					NILAI_K3		= '".$cekdata7."',
					TANGGAL_INPUT	= SYSDATE,
					CAP_PENE		= '".$pene."',
					CAP_PUBL		= '".$publ."',
					CAP_PENG		= '".$peng."',
					CAP_PENU		= '".$penu."',
					CAP_PEMB		= '".$pemb."'
					WHERE KD_DOSEN	= '".$cekdata1."' AND KD_PRODI = '".$cekdata2."' AND KD_TA = '".$cekdata3."' AND KD_SMT = '".$cekdata4."'")->result_array();
		return $hasil;
	}
	
	function updaterekapfak($cekdata1='',$cekdata2='',$cekdata3='',$cekdata4,$cekdata5,$cekdata6,$pene='',$publ='',$peng='',$penu='',$pemb=''){		
		$hasil = $this->db->query("UPDATE IKD.IKD_D_REKAP_FAK
					SET 
					NILAI_K1 		= ".$cekdata4.",
					NILAI_K2 		= ".$cekdata5.",
					NILAI_K3		= ".$cekdata6.",
					TANGGAL_INPUT	= SYSDATE,
					CAP_PENE		= '".$pene."',
					CAP_PUBL		= '".$publ."',
					CAP_PENG		= '".$peng."',
					CAP_PENU		= '".$penu."',
					CAP_PEMB		= '".$pemb."'
					WHERE KD_PRODI	= '".$cekdata1."' AND KD_TA = '".$cekdata2."' AND KD_SMT = '".$cekdata3."'")->result_array();
		return $hasil;
	}
	
	function printberkasfakultas($kd_fak, $tahun, $semester) {
        /* $pbd3 = $this->db->query("SELECT  E.KD_FAK, INITCAP(H.NM_DOSEN||', '||H.GELAR) NM_DOSEN,B.KD_DOSEN, (SELECT COUNT(Z.KD_KELAS) FROM SIA.D_MATERI_KULIAH Z WHERE Z.KD_KELAS = A.KD_KELAS) AS K1,(D.HASIL)AS K2,(SUM (C.HASIL) / COUNT (C.HASIL)* 0.1) AS K3,B.NM_MK,B.KD_KELAS, B.KD_PRODI, B.KD_MK, B.KELAS_PARAREL, B.SKS, B.KD_DOSEN, B.KD_TA, B.KD_SMT, INITCAP(B.NM_PRODI) AS NM_PRODI, I.NM_FAK
                                    FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B, IKD.IKD_D_HASIL_QUISIONER C, IKD.IKD_D_PENGUMPULAN_BERKAS D, SIA.MASTER_FAK E, SIA.MASTER_PRODI F, SIA.MASTER_JURUSAN G, SIA.D_DOSEN H, SIA.V_PRODI I
                                    WHERE A.KD_KELAS (+) = B.KD_KELAS AND E.KD_FAK = '".$kd_fak."' AND B.KD_TA = '".$tahun."' AND B.KD_SMT = '".$semester."' AND B.KD_DOSEN = C.KD_DOSEN AND B.KD_MK = C.KD_MK AND B.KD_KELAS = C.KD_KELAS AND B.KD_TA = C.KD_TA AND B.KD_SMT = C.KD_SMT AND B.KD_DOSEN = D.KD_DOSEN AND B.KD_KELAS = D.KD_KELAS AND B.KD_TA = D.KD_TA AND B.KD_SMT = D.KD_SMT
                                    AND B.KD_PRODI = F.KD_PRODI AND F.KD_JURUSAN = G.KD_JURUSAN AND G.KD_FAK = E.KD_FAK AND H.KD_DOSEN = B.KD_DOSEN AND I.KD_JURUSAN = F.KD_JURUSAN
                                    GROUP BY E.KD_FAK, B.KD_DOSEN, A.KD_KELAS, H.NM_DOSEN, H.GELAR, D.HASIL, C.HASIL , B.NM_MK, B.KD_KELAS, B.KD_PRODI, B.KD_MK, B.KELAS_PARAREL, B.SKS, B.KD_DOSEN, B.KD_TA, B.KD_SMT, B.NM_PRODI, I.NM_FAK ORDER BY B.KD_PRODI")->result_array(); */
        $pbd3 = $this->db->query("SELECT * FROM IKD.IKD_D_REKAP_FAK WHERE KD_FAK = '".$kd_fak."' AND KD_TA = '".$tahun."' AND KD_SMT = '".$semester."' ORDER BY KD_PRODI ASC")->result_array();
		return $pbd3;
    }

    function Lihat_hari_lbur() {
        $lhl = $this->db->query("SELECT TANGGAL FROM IKD.IKD_D_HARI_LIBUR_NASIONAL")->result_array();
        return $lhl;
    }

    function cek_pengumpulan_berkas($kd_dosen, $kd_mk, $kd_ta, $kd_smt, $kd_kls, $kd_kelas) {
        $lhl = $this->db->query("SELECT * FROM IKD.IKD_D_PENGUMPULAN_BERKAS WHERE KD_MK ='".$kd_mk."' AND KD_DOSEN ='".$kd_dosen."' AND KD_TA ='".$kd_ta."' AND KD_SMT ='".$kd_smt."' AND KD_KLS ='".$kd_kls."' AND KD_KELAS = '".$kd_kelas."'")->result_array();
        return $lhl;
    }

    function Pengumpulan_Berkas($kd_dosen, $kd_mk, $kd_ta, $kd_smt, $kls, $kd_kelas) {
        $pb = $this->db->query("SELECT * FROM IKD.IKD_D_PENGUMPULAN_BERKAS WHERE KD_MK = '$kd_mk' AND KD_DOSEN = '$kd_dosen' AND KD_TA = '$kd_ta' AND KD_SMT = '$kd_smt' AND KD_KLS = '$kls' AND KD_KELAS ='$kd_kelas'")->result_array();
        return $pb;
    }
	
	function prosentase_kepuasan($kd_ta,$kd_smt) {
        $ts = $this->db->query("SELECT A.KATEGORI, A.STATUS STATUS1, B.* FROM IKD.IKD_PUAS_KAT A, IKD.IKD_PUAS_TANY_P B WHERE A.KD_TA=B.KD_TA AND A.KD_SMT=B.KD_SMT AND A.ID_KAT=B.ID_KAT AND B.STATUS='1' AND A.STATUS='1' AND B.KD_TA='".$kd_ta."' AND B.KD_SMT='".$kd_smt."' ORDER BY B.ID_KAT,B.ID_TANY ASC")->result_array();
        return $ts;
    }
	
	function prosentase_kepuasan2($kd_ta,$kd_smt) {
        $ts = $this->db->query("SELECT A.KATEGORI, A.STATUS STATUS1, B.* FROM IKD.IKD_PUAS_KAT A, IKD.IKD_PUAS_TANY_P B WHERE A.KD_TA=B.KD_TA AND A.KD_SMT=B.KD_SMT AND A.ID_KAT=B.ID_KAT AND B.KD_TA='".$kd_ta."' AND B.KD_SMT='".$kd_smt."' ORDER BY B.ID_KAT,B.ID_TANY ASC")->result_array();
        return $ts;
    }
	
	function prosentase_kepuasan_arr($kd_ta,$kd_smt) {
        $ts = $this->db->query("SELECT * FROM IKD.IKD_PUAS_JWB WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' ORDER BY ID_TANY ASC")->result_array();
        return $ts;
    }
	
	function prosentase_dum_nim($kd_ta,$kd_smt) {
        $ts = $this->db->query("SELECT DISTINCT NIM FROM IKD.IKD_PUAS_JWB WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."'")->result_array();
        return $ts;
    }
	
	function prosentase_kepuasan_harap($kd_ta,$kd_smt,$x) {
		$in='1';
		for($i=2;$i<=$x;$i++){
			$in .=','.$i;
		}
        $ts = $this->db->query("SELECT * FROM (
				SELECT X.*,Y.TOTAL FROM
				(
				SELECT B.ID_TANY, B.PERTANYAAN, C.ID_KAT, C.KATEGORI, D.HARAP, COUNT(D.HARAP) JUMLAH
				FROM IKD.IKD_PUAS_TANY_P B, IKD.IKD_PUAS_KAT C, IKD.IKD_PUAS_JWB D
				WHERE B.STATUS = '1' AND C.STATUS = '1' AND B.KD_TA = '".$kd_ta."' AND B.KD_SMT = '".$kd_smt."'
				AND B.ID_KAT = C.ID_KAT AND B.ID_TANY = D.ID_TANY
				GROUP BY (B.ID_TANY, B.PERTANYAAN, C.ID_KAT, C.KATEGORI, D.HARAP)
				) X,
				(
				SELECT B.ID_TANY, B.PERTANYAAN, C.ID_KAT, C.KATEGORI, COUNT(D.ID_TANY) TOTAL
				FROM IKD.IKD_PUAS_TANY_P B, IKD.IKD_PUAS_KAT C, IKD.IKD_PUAS_JWB D
				WHERE B.STATUS = '1' AND C.STATUS = '1' AND B.KD_TA = '".$kd_ta."' AND B.KD_SMT = '".$kd_smt."'
				AND B.ID_KAT = C.ID_KAT AND B.ID_TANY = D.ID_TANY
				GROUP BY (B.ID_TANY, B.PERTANYAAN, C.ID_KAT, C.KATEGORI, D.ID_TANY)
				ORDER BY C.ID_KAT, B.ID_TANY
				) Y
				WHERE X.ID_TANY = Y.ID_TANY
				)
				PIVOT ( SUM(JUMLAH) FOR HARAP IN (".$in.") )
				ORDER BY ID_KAT, ID_TANY")->result_array();
        return $ts;
    }
	
	function prosentase_kepuasan_nyata($kd_ta,$kd_smt,$x) {
		$in='1';
		for($i=2;$i<=$x;$i++){
			$in .=','.$i;
		}
        $ts = $this->db->query("SELECT * FROM (
				SELECT X.*,Y.TOTAL FROM
				(
				SELECT B.ID_TANY, B.PERTANYAAN, C.ID_KAT, C.KATEGORI, D.NYATA, COUNT(D.NYATA) JUMLAH
				FROM IKD.IKD_PUAS_TANY_P B, IKD.IKD_PUAS_KAT C, IKD.IKD_PUAS_JWB D
				WHERE B.STATUS = '1' AND C.STATUS = '1' AND B.KD_TA = '".$kd_ta."' AND B.KD_SMT = '".$kd_smt."'
				AND B.ID_KAT = C.ID_KAT AND B.ID_TANY = D.ID_TANY
				GROUP BY (B.ID_TANY, B.PERTANYAAN, C.ID_KAT, C.KATEGORI, D.NYATA)
				) X,
				(
				SELECT B.ID_TANY, B.PERTANYAAN, C.ID_KAT, C.KATEGORI, COUNT(D.ID_TANY) TOTAL
				FROM IKD.IKD_PUAS_TANY_P B, IKD.IKD_PUAS_KAT C, IKD.IKD_PUAS_JWB D
				WHERE B.STATUS = '1' AND C.STATUS = '1' AND B.KD_TA = '".$kd_ta."' AND B.KD_SMT = '".$kd_smt."'
				AND B.ID_KAT = C.ID_KAT AND B.ID_TANY = D.ID_TANY
				GROUP BY (B.ID_TANY, B.PERTANYAAN, C.ID_KAT, C.KATEGORI, D.ID_TANY)
				ORDER BY C.ID_KAT, B.ID_TANY
				) Y
				WHERE X.ID_TANY = Y.ID_TANY
				)
				PIVOT ( SUM(JUMLAH) FOR NYATA IN (".$in.") )
				ORDER BY ID_KAT, ID_TANY")->result_array();
        return $ts;
    }
	
	function e_kat_soal($kd_ta,$kd_smt,$id_kat,$kat,$st) {
		return $this->db->query("UPDATE IKD.IKD_PUAS_KAT SET KATEGORI = '".$kat."', STATUS = '".$st."' WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND ID_KAT = '".$id_kat."'");
    }
	
	function e_tany_soal($kd_ta,$kd_smt,$id_tany,$tany,$st) {
		return $this->db->query("UPDATE IKD.IKD_PUAS_TANY_P SET PERTANYAAN = '".$tany."', STATUS = '".$st."' WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND ID_TANY = '".$id_tany."'");
    }
	
	function e_point_puas($point) {
		return $this->db->query("UPDATE IKD.IKD_PUAS_POINT SET POINT = '".$point."'");
    }
	
	function del_kat_soal($kd_ta,$kd_smt,$id_kat) {
		return $this->db->query("DELETE FROM IKD.IKD_PUAS_KAT WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND ID_KAT = '".$id_kat."'");
    }
	
	function del_tany_soal($kd_ta,$kd_smt,$id_tany) {
		return $this->db->query("DELETE FROM IKD.IKD_PUAS_TANY_P WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND ID_TANY = '".$id_tany."'");
    }
	
	function get_kat_soal_id($kd_ta,$kd_smt,$id_kat) {
		return $this->db->query("SELECT * FROM IKD.IKD_PUAS_KAT WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND ID_KAT = '".$id_kat."'")->result_array();
    }
	
	function get_tany_soal_id($kd_ta,$kd_smt,$id_tany) {
		return $this->db->query("SELECT * FROM IKD.IKD_PUAS_TANY_P WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND ID_TANY = '".$id_tany."'")->result_array();
    }
	
	function get_arr_kat() {
        return $this->db->query("SELECT * FROM IKD.IKD_PUAS_KAT WHERE KD_TA = (SELECT MAX(KD_TA) FROM IKD.IKD_PUAS_KAT) AND KD_SMT = (SELECT MAX(KD_SMT) FROM IKD.IKD_PUAS_KAT WHERE KD_TA = (SELECT MAX(KD_TA) FROM IKD.IKD_PUAS_KAT)) ORDER BY ID_KAT ASC")->result_array();
    }
	
	function get_arr_kat2($kd_ta,$kd_smt) {
        return $this->db->query("SELECT * FROM IKD.IKD_PUAS_KAT WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' ORDER BY ID_KAT ASC")->result_array();
    }
	
	function get_max_id_kat() {
        return $this->db->query("SELECT MAX(ID_KAT) ID_KAT FROM IKD.IKD_PUAS_KAT")->result_array();
    }
	
	function cek_id_tany_on_p($kd_ta,$kd_smt,$id_kat) {
        return $this->db->query("SELECT * FROM IKD.IKD_PUAS_TANY_P WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND ID_KAT = '".$id_kat."'")->result_array();
    }
	
	function cek_id_kat_on_p($kd_ta,$kd_smt) {
        return $this->db->query("SELECT * FROM IKD.IKD_PUAS_KAT WHERE KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."'")->result_array();
    }
	
	function in_kat_per_x($kd_ta,$kd_smt,$kat,$status) {
		$in = $this->db->query("INSERT INTO IKD.IKD_PUAS_KAT (KD_TA,KD_SMT,KATEGORI,STATUS) VALUES ('".$kd_ta."','".$kd_smt."','".$kat."','".$status."')");
        return $in;
    }
	
	function in_soal_per_x($kd_ta,$kd_smt,$status,$id_kat,$tany) {
		$in = $this->db->query("INSERT INTO IKD.IKD_PUAS_TANY_P (KD_TA,KD_SMT,STATUS,ID_KAT,PERTANYAAN) VALUES ('".$kd_ta."','".$kd_smt."','".$status."','".$id_kat."','".$tany."')");
        return $in;
    }
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	### DI BAWAH INI TIDAK TERPAKAI
	
	function ambil_soal() {      
        $tampil = $this->db->query("SELECT * FROM IKD.IKD_D_SOAL_QUISIONER ORDER BY KD_SOAL ASC")->result_array();
		return $tampil;
    }
	
	function capcus(){
		$hasil = $this->db->query("SELECT * FROM SIA.V_DOSEN WHERE ROWNUM <10 ")->result_array();
		return $hasil;
	}
	
	function cek_prodi($cekdata=''){
		$hasil = $this->db->query("SELECT * FROM SIA.V_KELAS WHERE KD_KELAS = '".$cekdata."' ")->result_array();
		return $hasil;
	}
	
	function data_dosen($kd_dosen,$nip) {      
        $dtdosen = $this->db->query("SELECT NM_DOSEN, NM_DOSEN_F, NIP, KD_DOSEN FROM SIA.V_DOSEN WHERE KD_DOSEN = '".$kd_dosen."' OR NIP = '".$nip."'")->result_array();
		return $dtdosen;
    }

    function ambil_detail($id) {
        $q = $this->db->query("SELECT * from IKD.IKD_D_SOAL_QUISIONER where KD_SOAL ='$id'")->result_array();
        return $q;
    }
	
	function cekkuesionermhs($kd_dosen,$ta,$smt,$kd_kelas){
        $q = $this->db->query("SELECT * FROM IKD.IKD_D_HASIL_QUISIONER WHERE KD_DOSEN = '".$kd_dosen."' AND KD_SMT = '".$smt."' AND KD_TA = '".$ta."' AND KD_KELAS = '".$kd_kelas."' ORDER BY KD_KELAS ASC")->result_array();
        return $q;
    }

    function ambil_detail_tanggal($id) {
        $q = $this->db->query("SELECT ID_HR, TO_CHAR(TANGGAL,'DD MONTH YYYY','nls_date_language = INDONESIAN') AS TANGGAL, KETERANGAN FROM IKD.IKD_D_HARI_LIBUR_NASIONAL where ID_HR ='$id'")->result_array();
        return $q;
    }

    function Update_Soal($tabel, $dt, $id) {
        $this->db->where('KD_SOAL', $id);
        return $this->db->update($tabel, $dt);
    }

    function Update_Tanggal_Libur($tanggal, $keterangan, $id) {
        $utl = $this->db->query("UPDATE IKD.IKD_D_HARI_LIBUR_NASIONAL SET TANGGAL = TO_DATE ('".$tanggal."','MM/DD/YYYY'), KETERANGAN = UPPER('".$keterangan."') WHERE ID_HR = ".$id."")->result_array();
        return $utl;
        //$this->db->where('ID_HR', $id);
        //$this->db->update($tabel, $dt);
    }
	
	function Insert_Soal($tabel, $dt) {
        /* $SQ = "INSERT INTO IKD_D_SOAL_QUISIONER (ISI, JWB_A, JWB_B, JWB_C, JWB_D, KET) 
          VALUES ('$isi','$a',$b,'$c','$d','$ket')";
          $this->db->query($SQ); */
        return $this->db->insert($tabel, $dt);
    }

    function Insert_Jabatan($tabel, $dt) {
        $this->db->insert($tabel, $dt);
    }
	
	function hapusjabatan($id) {
        //$this->db->delete('IKD.IKD_JABATAN_STRUKTURAL', array('ID_JBT' => $id));
		$this->db->where('ID_JBT', $id);
		$this->db->delete('IKD.IKD_D_JABATAN_STRUKTURAL'); 		
    }
	
	function soal_kuesioner() {
        $s = $this->db->query("SELECT * from IKD.IKD_D_SOAL_QUISIONER")->result_array();
        return $s;
    }

    function jum_soal() {
        $s = $this->db->query("select count (KD_SOAL) jum_soal from IKD.IKD_D_SOAL_QUISIONER")->result_array();
        return $s;
    }

    function cek_hasil_pencarian($cek, $limit, $offset) {
        $sql = "SELECT A.KD_DOSEN, A.KD_PRODI, A.NO_KTP, A.NM_DOSEN||', '||A.GELAR NM_DOSEN, A.KD_AGAMA, A.TMP_LAHIR, A.KD_KAB_LAHIR, A.TGL_LAHIR, A.J_KELAMIN, A.GOL_DARAH, A.MOBILE, A.EMAIL, A.NIP, A.KARPEG, A.KD_STATUS, A.KD_JENIS_PEG, A.KD_AKTIF, A.NO_SK_CPNS, A.TGL_SK_CPNS, A.TMT_CPNS, A.TGL_PRAJAB, A.NO_SK_PNS, A.TGL_SK_PNS, A.TMT_PNS, A.KD_GOLONGAN, A.KD_GOLONGAN_AKHIR, A.KD_JABATAN, A.BIDANG_JABATAN, A.KREDIT_JABATAN, A.KD_PEND_AKHIR, A.PND_AKHIR, A.GELAR_AKHIR, A.PT_GELAR, A.BIDANG, A.SK_AJAR, A.IJIN_AJAR, A.KD_PTINDUK, A.NM_PTINDUK, A.ALMT_RUMAH, A.RT, A.DESA, A.KD_PROP_RUMAH, A.KD_KAB_RUMAH, A.KD_KEC_RUMAH, A.TELP_RUMAH, A.STATUS_KAWIN, A.NM_PASANGAN, A.NIDN_PAYUNG FROM SIA.D_DOSEN A, SIA.MASTER_PRODI B, SIA.MD_AKTIF_PEGAWAI C 
				WHERE A.KD_PRODI = B.KD_PRODI (+) AND A.KD_AKTIF = C.KD_AKTIF (+) AND (A.NM_DOSEN LIKE (UPPER ('%$cek%')) OR A.KD_DOSEN LIKE '%$cek%') ORDER BY A.KD_DOSEN";
        $sql_new = $this->db->_limit($sql, $limit, $offset);
        $query = $this->db->query($sql_new)->result_array();
        return $query;


        //$chp = $this->db->query("SELECT * FROM (SELECT K.*, ROWNUM rnum FROM
        //						(SELECT A.KD_DOSEN, A.KD_PRODI, A.NO_KTP, A.NM_DOSEN||', '||A.GELAR NM_DOSEN, A.KD_AGAMA, A.TMP_LAHIR, A.KD_KAB_LAHIR, A.TGL_LAHIR, A.J_KELAMIN, A.GOL_DARAH, A.MOBILE, A.EMAIL, A.NIP, A.KARPEG, A.KD_STATUS, A.KD_JENIS_PEG, A.KD_AKTIF, A.NO_SK_CPNS, A.TGL_SK_CPNS, A.TMT_CPNS, A.TGL_PRAJAB, A.NO_SK_PNS, A.TGL_SK_PNS, A.TMT_PNS, A.KD_GOLONGAN, A.KD_GOLONGAN_AKHIR, A.KD_JABATAN, A.BIDANG_JABATAN, A.KREDIT_JABATAN, A.KD_PEND_AKHIR, A.PND_AKHIR, A.GELAR_AKHIR, A.PT_GELAR, A.BIDANG, A.SK_AJAR, A.IJIN_AJAR, A.KD_PTINDUK, A.NM_PTINDUK, A.ALMT_RUMAH, A.RT, A.DESA, A.KD_PROP_RUMAH, A.KD_KAB_RUMAH, A.KD_KEC_RUMAH, A.TELP_RUMAH, A.STATUS_KAWIN, A.NM_PASANGAN, A.NIDN_PAYUNG FROM D_DOSEN A, MASTER_PRODI B, MD_AKTIF_PEGAWAI C 
        //						WHERE A.KD_PRODI = B.KD_PRODI (+) AND A.KD_AKTIF = C.KD_AKTIF (+) AND (A.NM_DOSEN LIKE (UPPER ('%$cek%')) OR A.KD_DOSEN LIKE '%$cek%') ORDER BY A.KD_DOSEN) K 
        //						WHERE ROWNUM <= $limit)
        //						WHERE rnum >= $offset");
        //return $chp;
    }

    function cek_data_jabatan($cek, $limit, $offset) {
        $chp = $this->db->query("SELECT * FROM (SELECT K.*, ROWNUM rnum FROM 
                                                                (SELECT A.KD_DOSEN, A.KD_PRODI, INITCAP(B.NM_PRODI)NM_PRODI, A.NO_KTP, INITCAP(A.NM_DOSEN||', '||A.GELAR) NM_DOSEN, A.KD_AGAMA, A.TMP_LAHIR, A.KD_KAB_LAHIR, A.TGL_LAHIR, A.J_KELAMIN, A.GOL_DARAH, A.MOBILE, A.EMAIL, A.NIP, A.KARPEG, A.KD_STATUS, A.KD_JENIS_PEG, A.KD_AKTIF, A.NO_SK_CPNS, A.TGL_SK_CPNS, A.TMT_CPNS, A.TGL_PRAJAB, A.NO_SK_PNS, A.TGL_SK_PNS, A.TMT_PNS, A.KD_GOLONGAN, A.KD_GOLONGAN_AKHIR, A.KD_JABATAN, A.BIDANG_JABATAN, A.KREDIT_JABATAN, A.KD_PEND_AKHIR, A.PND_AKHIR, A.GELAR_AKHIR, A.PT_GELAR, A.BIDANG, A.SK_AJAR, A.IJIN_AJAR, A.KD_PTINDUK, A.NM_PTINDUK, A.ALMT_RUMAH, A.RT, A.DESA, A.KD_PROP_RUMAH, A.KD_KAB_RUMAH, A.KD_KEC_RUMAH, A.TELP_RUMAH, A.STATUS_KAWIN, A.NM_PASANGAN, A.NIDN_PAYUNG 
																FROM SIA.D_DOSEN A, SIA.MASTER_PRODI B, SIA.MD_AKTIF_PEGAWAI C 
								WHERE A.KD_PRODI = B.KD_PRODI (+) AND A.KD_AKTIF = C.KD_AKTIF (+) AND (A.NM_DOSEN LIKE (UPPER ('%$cek%')) OR A.KD_DOSEN LIKE '%$cek%') ORDER BY A.KD_DOSEN) K 
								WHERE ROWNUM <= $limit)
								WHERE rnum >= $offset")->result_array();
        return $chp;
    }

    function OutProdiDosen($cek,$tempprodi){
		$quer_ = $this->db->query("SELECT DISTINCT A.KD_PRODI, A.NM_PRODI FROM SIA.V_KELAS A, SIA.V_DOSEN B, SIA.D_TIMAJAR2013 C WHERE A.KD_PRODI = B.KD_PRODI AND A.KD_KELAS = C.KD_KELAS AND C.KD_DOSEN = B.KD_DOSEN AND (UPPER(B.NM_DOSEN) LIKE (UPPER ('%".$cek."%')) OR B.KD_DOSEN LIKE '%".$cek."%') AND (A.KD_PRODI IN ('".$tempprodi."'))")->result_array();
		/* $quer_ = $this->db->query("SELECT DISTINCT A.KD_PRODI, A.NM_PRODI FROM SIA.V_KELAS A, SIA.D_TIMAJAR2013 C WHERE A.KD_KELAS = C.KD_KELAS AND C.KD_DOSEN LIKE '%".$cek."%') AND (A.KD_PRODI IN ('".$tempprodi."'))")->result_array(); */
		return $quer_;
	}
	
	function cek_dosen_lengkap($cek='',$tempprodi='') {
		$out_kd_prodi = $this->db->query("SELECT DISTINCT D.KD_PRODI FROM SIA.D_KELAS2013 D WHERE D.KD_DOSEN = '".$cek."'")->result_array();
		if(!empty($out_kd_prodi)){
			for($i=0; $i<count($out_kd_prodi); $i++){
				if($i==0) $kd_prodi_b[0] = $out_kd_prodi[$i]['KD_PRODI'];
				$kd_prodi_b[0] .= "','".$out_kd_prodi[$i]['KD_PRODI'];
			}
		}else{
			$kd_prodi_b[0] = '';
		}
		$hp = $this->db->query("SELECT DISTINCT A.KD_DOSEN, A.KD_PRODI, A.NM_DOSEN
									FROM SIA.V_DOSEN A, SIA.MASTER_PRODI B, SIA.D_KELAS2013 C
									WHERE A.KD_PRODI = B.KD_PRODI (+) AND B.KD_PRODI = C.KD_PRODI AND A.KD_DOSEN = C.KD_DOSEN AND (UPPER(A.NM_DOSEN) LIKE (UPPER ('%$cek%')) OR A.KD_DOSEN = '".$cek."') AND (C.KD_PRODI IN ('$tempprodi') OR C.KD_PRODI IN ('".$kd_prodi_b[0]."'))")->result_array();
		/* $hp = $this->db->query("SELECT * FROM SIA.MASTER_PRODI WHERE NM_PRODI LIKE '%SYA%'")->result_array(); */
		return $hp;
	}	

    function cek_jabatan_dosen($cek) {
        $hp = $this->db->query("
			SELECT  A.KD_DOSEN, A.KD_PRODI, B.NM_PRODI, INITCAP(A.NM_DOSEN||', '||A.GELAR) NM_DOSEN , A.NIP
			FROM D_DOSEN A , SIA.MASTER_PRODI B, SIA.MD_AKTIF_PEGAWAI C 
			WHERE A.KD_PRODI = B.KD_PRODI (+) AND A.KD_AKTIF = C.KD_AKTIF (+) AND A.NM_DOSEN LIKE (UPPER ('%$cek%'))
			ORDER BY A.KD_PRODI ASC")->result_array();
        return $hp;
    }

   

    function hasil_rekap($kd_dosen) {
        $tampil = $this->db->query("select DISTINCT A.KD_MK, E.NM_MK, D.SKS, F.KD_TA, F.KD_SMT
										from SIA.D_PENGAMPU_MK A, SIA.D_DOSEN B ,SIA.D_KELAS C, SIA.D_DETAIL_KRS D, SIA.MD_MATAKULIAH_KUR_PRODI E, SIA.D_KRS F
										where 	B.KD_DOSEN = '".$kd_dosen."' AND B.KD_DOSEN = C.KD_DOSEN AND D.KD_MK = A.KD_MK AND C.KD_KELAS = D.KD_KELAS 
												and C.KD_TA = (select max (C.KD_TA) FROM SIA.D_DOSEN A, SIA.D_KELAS C WHERE A.KD_DOSEN = '".$kd_dosen."' and B.KD_DOSEN = C.KD_DOSEN) 
												and A.KD_MK = E.KD_MK and D.KD_MK = E.KD_MK and D.KD_KRS = F.KD_KRS")->result_array();
        return $tampil;
    }

    function Tampil_Tahun($kd_dosen, $nip) {
        $query_total = $this->db->query("select DISTINCT G.KD_TA, G.TA, H.NM_SMT, H.KD_SMT
											from SIA.D_PENGAMPU_MK A, SIA.D_DOSEN B ,SIA.D_KELAS C, SIA.D_DETAIL_KRS D, SIA.MD_MATAKULIAH_KUR_PRODI E, SIA.D_KRS F ,SIA.D_TA G, SIA.D_SEMESTER H 
											where (B.KD_DOSEN = '".$kd_dosen."' OR B.NIP = '".$nip."') and B.KD_DOSEN = C.KD_DOSEN and D.KD_MK = A.KD_MK and C.KD_KELAS = D.KD_KELAS 
											and C.KD_TA = F.KD_TA and G.KD_TA = F.KD_TA and F.KD_SMT = H.KD_SMT 
											and A.KD_MK = E.KD_MK and D.KD_MK = E.KD_MK and D.KD_KRS = F.KD_KRS 
											order by G.KD_TA desc")->result_array();
        return $query_total;
    }
	
    function tahun() {
        $tampiltahun = $this->db->query("SELECT DISTINCT A.KD_TA, A.TA, A.TAHUN, B.KD_SMT, B.NM_SMT, A.STATUS, A.KETERANGAN FROM D_TA A, D_SEMESTER B WHERE A.STATUS = B.STATUS AND TO_NUMBER(B.KD_SMT) < 4 ORDER BY TO_NUMBER(A.TAHUN) DESC, B.KD_SMT")->result_array();
        return $tampiltahun;
    }
	
	function detTa($kd_ta){
		$tahun = $this->db->query("SELECT A.TA FROM SIA.D_TA A WHERE A.KD_TA = '".$kd_ta."'")->result_array();
		return $tahun;
	}
	
	function detSmt($kd_smt){
		$smt = $this->db->query("SELECT B.NM_SMT FROM SIA.D_SEMESTER B WHERE B.KD_SMT = '".$kd_smt."'")->result_array();
		return $smt;
	}
	
    function detailprodi($cekprodi) {
        $tampilprodi = $this->db->query(" SELECT * FROM SIA.V_PRODI WHERE KD_PRODI IN ('$cekprodi') ORDER BY KD_FAK ASC, KD_JURUSAN ASC, KD_PRODI ASC")->result_array();
        return $tampilprodi;
    }
	
	function detailprodik() {
        $tampilprodi = $this->db->query(" SELECT * FROM SIA.V_PRODI ORDER BY KD_FAK ASC, KD_JURUSAN ASC, KD_PRODI ASC")->result_array();
        return $tampilprodi;
    }
	
	function detailfakultas($cekfak) {
        $tampilfakultas = $this->db->query(" SELECT KD_FAK, NM_FAK FROM SIA.MASTER_FAK WHERE KD_FAK IN ('".$cekfak."') GROUP BY KD_FAK, NM_FAK")->result_array();
        return $tampilfakultas;
    }
	function detailfakultask() {
        $tampilfakultas = $this->db->query(" SELECT KD_FAK, NM_FAK FROM SIA.MASTER_FAK GROUP BY KD_FAK, NM_FAK")->result_array();
        return $tampilfakultas;
    }

    function Tampil_ByKatergoriBaru($kd_dosen, $tahun, $semester) {
        $tampil = $this->db->query("SELECT B.KD_PRODI, B.KD_MK, B.KD_KELAS, B.NM_MK, B.KELAS_PARAREL, B.SKS, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.NO_RUANG, B.TERISI, B.TATAP, COUNT(A.KD_KELAS) AS JUM_KULIAH, B.KD_DOSEN, B.KD_TA, B.KD_SMT, B.NM_JENIS_MK, TO_CHAR(MAX(A.TGL_KUL),'DD/MM/YYYY') TGL_UPDATE, B.JADWAL1, B.JADWAL2, B.NM_PRODI 
											FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B 
											WHERE A.KD_KELAS (+) = B.KD_KELAS AND B.KD_DOSEN = '$kd_dosen' AND B.KD_TA = '$tahun' AND B.KD_SMT = '$semester'
											GROUP BY B.KD_PRODI, B.KD_MK, B.KD_KELAS, B.NM_MK, B.KELAS_PARAREL, B.SKS, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.NO_RUANG, B.TERISI, B.TATAP, B.KD_DOSEN, B.KD_TA, B.KD_SMT,B.NM_JENIS_MK,JADWAL1, B.JADWAL2, B.NM_PRODI")->result_array();
        return $tampil;
    }

    function printberkasdosen($kd_dosen, $tahun, $semester) {
       /*  $pbd = $this->db->query("SELECT  E.KD_FAK, COUNT(A.KD_KELAS) AS K1 ,(D.HASIL)AS K2,(SUM (C.HASIL) / COUNT (C.HASIL)* 0.1) AS K3,B.NM_MK,B.KD_KELAS, B.KD_PRODI, B.KD_MK, B.KELAS_PARAREL, B.SKS, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.NO_RUANG, B.TERISI, B.TATAP, B.KD_DOSEN, B.KD_TA, B.KD_SMT, B.NM_JENIS_MK,
                                        TO_CHAR(MAX(A.TGL_KUL),'DD/MM/YYYY') TGL_UPDATE, B.JADWAL1, B.JADWAL2, B.NM_PRODI
                                        FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B, IKD.IKD_D_HASIL_QUISIONER C, IKD.IKD_D_PENGUMPULAN_BERKAS D, SIA.MASTER_FAK E, SIA.MASTER_PRODI F, SIA.MASTER_JURUSAN G
                                        WHERE A.KD_KELAS (+) = B.KD_KELAS AND B.KD_DOSEN = '".$kd_dosen."' AND B.KD_TA = '".$tahun."' AND B.KD_SMT = '".$semester."' AND B.KD_DOSEN = C.KD_DOSEN AND B.KD_MK = C.KD_MK AND B.KD_KELAS = C.KD_KELAS AND B.KD_TA = C.KD_TA AND B.KD_SMT = C.KD_SMT AND B.KD_DOSEN = D.KD_DOSEN AND B.KD_KELAS = D.KD_KELAS AND B.KD_TA = D.KD_TA AND B.KD_SMT = D.KD_SMT
                                        AND B.KD_PRODI = F.KD_PRODI AND F.KD_JURUSAN = G.KD_JURUSAN AND G.KD_FAK = E.KD_FAK
                                        GROUP BY E.KD_FAK, B.NM_MK, B.KD_MK, B.KELAS_PARAREL, B.SKS, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.NO_RUANG, B.TERISI,
                                        B.TATAP, B.KD_DOSEN, B.KD_TA, B.KD_SMT,B.NM_JENIS_MK,JADWAL1, B.JADWAL2, B.NM_PRODI ,B.KD_KELAS, B.KD_PRODI,D.HASIL")->result_array(); */
		/* $pbd = $this->db->query("SELECT B.NM_MK, B.KD_MK, B.KD_KELAS, B.SKS, B.KELAS_PARAREL, B.TERISI, ROUND((SUM (C.HASIL) / COUNT (C.HASIL)* 0.1),2) AS K3 
							FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B, IKD.IKD_D_HASIL_QUISIONER C
							WHERE A.KD_KELAS (+) = B.KD_KELAS AND B.KD_DOSEN = '".$kd_dosen."' AND B.KD_TA = '".$tahun."' AND B.KD_SMT = '".$semester."' AND B.KD_DOSEN = C.KD_DOSEN AND B.KD_MK = C.KD_MK AND
							B.KD_KELAS = C.KD_KELAS AND B.KD_TA = C.KD_TA AND B.KD_SMT = C.KD_SMT 
							GROUP BY B.NM_MK, B.KD_MK, B.KD_KELAS, B.SKS , B.KELAS_PARAREL, B.TERISI  
							ORDER BY B.KD_MK,B.KELAS_PARAREL ASC ")->result_array(); */
		$pbd = $this->db->query("SELECT B.NM_MK, B.SKS, B.KELAS_PARAREL, B.TERISI, C.KD_MK, C.KD_KELAS, C.KELAS, ROUND((SUM (C.HASIL) / COUNT (C.HASIL)* 0.1),2) AS K3, COUNT (C.HASIL) AS RES
									FROM SIA.V_KELAS B, IKD.IKD_D_HASIL_QUISIONER C, SIA.D_TIMAJAR2013 DTD
									WHERE 
									B.KD_KELAS = DTD.KD_KELAS AND DTD.KD_DOSEN = C.KD_DOSEN AND
									B.KD_KELAS = C.KD_KELAS AND
									DTD.KD_DOSEN = '".$kd_dosen."' 
									AND C.KD_TA = '".$tahun."' AND C.KD_SMT = '".$semester."' 
									GROUP BY C.KD_MK, C.KD_KELAS, C.KELAS, B.NM_MK, B.SKS, B.KELAS_PARAREL, B.TERISI
									ORDER BY C.KD_MK, C.KELAS ASC")->result_array();
		#array($kd_dosen,$tahun,$semester);
		
        return $pbd;
    }
	
	function outdataprodi($cekdata1=''){
		$qry_cek = $this->db->query("SELECT A.KD_PRODI, A.NM_PRODI, B.KD_FAK  FROM SIA.MASTER_PRODI A, SIA.MASTER_JURUSAN B, SIA.MASTER_FAK C WHERE A.KD_PRODI = '".$cekdata1."' AND A.KD_JURUSAN = B.KD_JURUSAN AND B.KD_FAK = C.KD_FAK ")->result_array();
		return $qry_cek;
	}
	
    function DataBerkasDosen($kd_dosen, $tahun, $semester) {
        $tampil = $this->db->query("SELECT  B.NM_MK,B.KD_KELAS, B.KD_PRODI, B.KD_MK, B.KELAS_PARAREL, B.SKS, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.NO_RUANG, B.TERISI, B.TATAP,
											COUNT(A.KD_KELAS) AS JUM_KULIAH, B.KD_DOSEN, B.KD_TA, B.KD_SMT, B.NM_JENIS_MK,
											TO_CHAR(MAX(A.TGL_KUL),'DD/MM/YYYY') TGL_UPDATE, B.JADWAL1, B.JADWAL2, B.NM_PRODI
											FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B
											WHERE A.KD_KELAS (+) = B.KD_KELAS AND B.KD_DOSEN = '".$kd_dosen."' AND B.KD_TA = '".$tahun."' AND B.KD_SMT = '".$semester."'
											GROUP BY B.NM_MK, B.KD_MK, B.KELAS_PARAREL, B.SKS, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.NO_RUANG, B.TERISI,
											B.TATAP, B.KD_DOSEN, B.KD_TA, B.KD_SMT,B.NM_JENIS_MK,JADWAL1, B.JADWAL2, B.NM_PRODI ,B.KD_KELAS, B.KD_PRODI")->result_array();
        return $tampil;
    }

    function Lihat_Nilai($kd_dosen, $kd_mk, $tahun, $smt, $kls, $kd_kls) {
        $nilai = $this->db->query("select distinct f.KD_MK, e.NM_DOSEN, g.NM_MK, f.KD_DOSEN, f.KD_TA, f.KD_SMT, b.SKS, c.KELAS_PARAREL, c.TERISI, f.KD_MK, (sum (f.HASIL) / count (f.HASIL)*0.1) K3, h.TA , i.NM_PRODI
										from SIA.D_DOSEN e, IKD.IKD_D_HASIL_QUISIONER f , SIA.D_KRS a, SIA.D_DETAIL_KRS b, SIA.D_KELAS c, SIA.MD_MATAKULIAH_KUR_PRODI g, SIA.D_TA h, SIA.MASTER_PRODI i
										where f.KD_DOSEN = '$kd_dosen' and f.KD_MK = '$kd_mk' and f.KD_DOSEN = e.KD_DOSEN and f.KD_TA = '$tahun' and f.KD_SMT = '$smt' and f.KELAS = '$kls' and f.KD_KELAS = '$kd_kls' and e.KD_DOSEN = c.KD_DOSEN and b.KD_KELAS = c.KD_KELAS 
										and b.KD_MK = f.KD_MK and a.KD_KRS = b.KD_KRS and g.KD_MK = f.KD_MK and c.KELAS_PARAREL = f.KELAS and a.KD_TA = f.KD_TA and a.KD_SMT = f.KD_SMT and f.KD_TA = h.KD_TA and e.KD_PRODI = i.KD_PRODI
										GROUP BY e.NM_DOSEN,f.KD_DOSEN,f.KD_TA,f.KD_SMT,b.KD_KRS, b.SKS, f.KD_MK, g.NM_MK, c.TERISI, c.KELAS_PARAREL, h.TA, i.NM_PRODI")->result_array();
        return $nilai;
    }

    function Lihat_Tanggal_Ujian($kd_dosen, $kd_mk, $tahun, $smt, $kls, $kd_kelas) {
        $tanggal = $this->db->query("SELECT B.KD_KELAS, A.KD_J_UJIAN, A.KD_RUANG, TO_CHAR(A.TGL,'MM/DD/YYYY')TGL, TO_CHAR(A.TGL,'DAY, MM/DD/YYYY') TGL1, TO_CHAR(A.JAM_MULAI,'HH24:MI') JAM_MULAI, TO_CHAR(A.JAM_SELESAI,'HH24:MI') JAM_SELESAI, A.JUM_PESERTA, B.KD_KUR, B.KD_PRODI, B.NM_PRODI, B.KD_MK, B.KD_TA, B.KD_SMT, B.NM_MK, B.SKS, B.KELAS_PARAREL, B.KD_DOSEN, B.NM_DOSEN, B.NIP, B.NM_JENIS_MK, B.SEMESTER_PAKET, A.KD_RUANG NO_RUANG, B.TERISI PESERTA, C.NM_J_UJIAN, A.KETERANGAN 
											FROM SIA.D_JADWAL_UJIAN A, SIA.V_KELAS B, SIA.D_JENIS_UJIAN C 
											WHERE A.KD_KELAS (+) = B.KD_KELAS AND A.KD_J_UJIAN = C.KD_J_UJIAN (+) AND B.KD_DOSEN = '$kd_dosen' AND B.KD_MK = '$kd_mk' AND B.KD_TA = '$tahun' AND B.KD_SMT = '$smt' AND B.KELAS_PARAREL = '$kls' AND B.KD_KELAS = '$kd_kelas' AND A.KD_J_UJIAN = '2'")->result_array();

        return $tanggal;
    }
    
    function Lihat_Tanggal_Pengumpulan($kd_dosen, $kd_mk, $tahun, $smt, $kd_kelas) {
        $ltp = $this->db->query("SELECT DISTINCT TO_CHAR(TGL_NILAI,'MM/DD/YYYY')TGL_NILAI
								FROM SIA.D_TRANSKRIP_HISTORI WHERE KD_KELAS = '$kd_kelas' AND KD_DOSEN = '$kd_dosen' AND KD_TA = '".$tahun."' AND KD_SMT = '".$smt."' AND KD_MK = '".$kd_mk."'")->result_array();
        return $ltp;
    }

    function Banyak_Kuliah($kd_dosen, $kd_mk, $tahun, $smt, $kls, $kd_kls) {
        $nilai = $this->db->query("SELECT DISTINCT B.KD_PRODI, C.NM_PRODI, C.NM_JURUSAN, C.NM_FAK, B.KD_TA, B.KD_SMT, E.TA, D.NM_SMT, B.KD_KELAS, B.NM_MK, B.SKS, B.KELAS_PARAREL, B.NM_JENIS_MK, B.KD_DOSEN, B.NIP, B.NM_DOSEN, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.KD_RUANG, B.NO_RUANG, B.TERISI, B.TATAP, COUNT(A.KD_KELAS) AS JUM_KULIAH, BAGI(COUNT(A.KD_KELAS), B.TATAP) PROSEN, TO_CHAR(MAX(A.TGL_KUL),'DD/MM/YYYY') TGL_UPDATE, B.JADWAL1, B.JADWAL2, B.KD_KUR, B.KD_MK 
										FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B, SIA.V_PRODI C, SIA.D_SEMESTER D, SIA.D_TA E 
										WHERE B.KD_DOSEN = '$kd_dosen' AND B.KD_MK = '$kd_mk' AND B.KD_TA = '$tahun' AND B.KD_SMT='$smt' AND B.KELAS_PARAREL='$kls' AND B.KD_KELAS='$kd_kls' AND A.KD_KELAS (+) = B.KD_KELAS AND B.KD_PRODI = C.KD_PRODI AND B.KD_TA = E.KD_TA AND B.KD_SMT = D.KD_SMT 
										GROUP BY B.KD_PRODI, C.NM_PRODI, C.NM_JURUSAN, C.NM_FAK, B.KD_TA, B.KD_SMT, E.TA, D.NM_SMT, B.KD_KELAS, B.NM_MK, B.SKS, B.KELAS_PARAREL, B.NM_JENIS_MK, B.KD_DOSEN, B.NIP, B.NM_DOSEN, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.KD_RUANG, B.NO_RUANG, B.TERISI, B.TATAP, B.TATAP, B.JADWAL1, B.JADWAL2, B.KD_KUR, B.KD_MK")->result_array();
        return $nilai;
    }

    function Judul_MK($id_mk) {
        $matkul = $this->db->query("select distinct nm_mk,kd_mk from SIA.MD_MATAKULIAH_KUR_PRODI where kd_mk = '$id_mk'")->result_array();
        return $matkul;
    }

    function Update_Content($tabel, $isi, $id) {
        $this->db->query("UPDATE IKD.IKD_D_SOAL_QUISIONER SET KET = '$isi' WHERE KD_SOAL = $id")->result_array();
    }

    function Dekan($kd_fak) {
        $dkn = $this->db->query("SELECT A.ID_JBT, A.KD_JBT, A.KD_FAK, A.KD_PRODI, A.KD_DOSEN, A.KETERANGAN, INITCAP(B.NM_DOSEN) AS NM_DOSEN FROM IKD.IKD_D_JABATAN_STRUKTURAL A, SIA.D_DOSEN B WHERE KD_FAK = '".$kd_fak."' AND KD_JBT = 'F' AND A.KD_DOSEN = B.KD_DOSEN")->result_array();
        return $dkn;
    }
    
    function tampilta($kd_ta){
        $ta = $this->db->query("SELECT TA FROM SIA.D_TA WHERE KD_TA = '".$kd_ta."'")->result_array();
        return $ta;
    }
    
    function tampilsmt($kd_smt){
        $smt = $this->db->query("SELECT NM_SMT FROM SIA.D_SEMESTER WHERE KD_SMT = '".$kd_smt."'")->result_array();
        return $smt;
    }
    
    function tampilprodi($kd_prodi){
        $prd = $this->db->query("SELECT KD_PRODI, NM_PRODI FROM SIA.MASTER_PRODI WHERE KD_PRODI = '".$kd_prodi."'")->result_array();
        return $prd;
    }
    
	function tampilfakultas($kd_fak){
        $faku = $this->db->query("SELECT KD_FAK, NM_FAK FROM SIA.MASTER_FAK WHERE KD_FAK = '$kd_fak'")->result_array();
        return $faku;
    }
	
    function Kaprodi($kd_prodi) {
        $kpd = $this->db->query("SELECT A.KD_DOSEN, INITCAP(B.NM_DOSEN) AS NM_DOSEN FROM IKD.IKD_D_JABATAN_STRUKTURAL A, SIA.D_DOSEN B WHERE A.KD_JBT = 'J' AND A.KD_PRODI = '".$kd_prodi."' AND A.KD_DOSEN = B.KD_DOSEN")->result_array();
        return $kpd;
    }

    function Data_jabatan($kd_dosen) {
        $dj = $this->db->query("SELECT A.KD_DOSEN, A.KD_PRODI, INITCAP(B.NM_PRODI)AS NM_PRODI, INITCAP(A.NM_DOSEN||', '||A.GELAR) NM_DOSEN
		FROM SIA.D_DOSEN A, SIA.MASTER_PRODI B, SIA.MD_AKTIF_PEGAWAI C 
		WHERE A.KD_PRODI = B.KD_PRODI (+) AND A.KD_AKTIF = C.KD_AKTIF (+) AND A.KD_DOSEN = '$kd_dosen'")->result_array();
        return $dj;
    }
    
    function Data_jabatan_struktural_dosen(){
        $djSd = $this->db->query("SELECT A.ID_JBT, A.KD_JBT, A.KD_FAK, A.KD_PRODI, A.KD_DOSEN, A.KETERANGAN, A.NM_DOSEN, INITCAP(B.NM_FAK)NM_FAK FROM IKD.IKD_D_JABATAN_STRUKTURAL A, SIA.MASTER_FAK B WHERE A.KD_FAK = B.KD_FAK ORDER BY A.KD_PRODI ASC")->result_array();
        return $djSd;
    }

    function Data_jabatan_struktural($kd_dosen) {
        $djS = $this->db->query("SELECT A.ID_JBT, A.KD_JBT, A.KD_FAK, A.KD_PRODI, A.KD_DOSEN, A.KETERANGAN, A.NM_DOSEN, INITCAP(B.NM_FAK)NM_FAK FROM IKD.IKD_D_JABATAN_STRUKTURAL A, SIA.MASTER_FAK B WHERE A.KD_DOSEN = '$kd_dosen' AND A.KD_FAK = B.KD_FAK")->result_array();
        return $djS;
    }

    function Cek_jabatan_D($kd_dosen, $kd_jbt, $kd_fak) {
        $djS = $this->db->query("SELECT * FROM IKD.IKD_D_JABATAN_STRUKTURAL WHERE KD_DOSEN = '$kd_dosen' AND KD_JBT = '$kd_jbt' AND KD_FAK = '$kd_fak'")->result_array();
        return $djS;
    }

    function Data_fakultas() {
        $df = $this->db->query("SELECT INITCAP(NM_FAK)NM_FAK,KD_FAK,KD_PT,ALAMAT FROM SIA.MASTER_FAK ORDER BY KD_FAK ASC")->result_array();
        return $df;
    }

    function Cek_waktu_kuesioner() {
        $waktu = $this->db->query("SELECT TO_CHAR(FIRST_DATE,'DD Month YYYY')FIRST_DATE, TO_CHAR(LAST_DATE,'DD Month YYYY')LAST_DATE,ID_WAKTU  FROM IKD.IKD_D_WAKTU_KUISIONER")->result_array();
        return $waktu;
    }

    function Insert_waktu($tgl_awal, $tgl_akhir) {
        $new_date = "INSERT INTO IKD.IKD_D_WAKTU_KUISIONER (FIRST_DATE, LAST_DATE) 
					VALUES (TO_DATE ('".$tgl_awal."','MM/DD/YYYY'),(TO_DATE ('".$tgl_akhir."','MM/DD/YYYY'))";
        $this->db->query($new_date)->result_array();
    }

    function Update_waktu($id, $tgl_awal, $tgl_akhir) {
        $n_date = $this->db->query("UPDATE IKD.IKD_D_WAKTU_KUISIONER SET FIRST_DATE = TO_DATE ('".$tgl_awal."','MM/DD/YYYY'), LAST_DATE = TO_DATE ('".$tgl_akhir."','MM/DD/YYYY') WHERE ID_WAKTU = ".$id."")->result_array();
        return $n_date;
    }

    function Tampilkan_Soal() {
        $ts = $this->db->query("SELECT KD_SOAL,ISI,KET,JWB_A,JWB_B,JWB_C,JWB_D FROM IKD.IKD_D_SOAL_QUISIONER WHERE KET = 'Y' ORDER BY KD_SOAL ASC")->result_array();
        return $ts;
    }
	
	function GET_NIP() {
        $ts = $this->db->query("SELECT DISTINCT KD_DOSEN FROM IKD.IKD_D_REKAP_PRODI")->result_array();
        return $ts;
    }
	
	function CHANGE_NIP($nip,$code) {
        $ts = $this->db->query("UPDATE IKD.IKD_D_REKAP_PRODI SET KD_DOSEN = '".$nip."' WHERE KD_DOSEN = '".$code."'");
        return $ts;
    }
	
    function Hasil_pencarian($cek,$tempprodi) {
        /* $hp = $this->db->query("	SELECT A.KD_DOSEN, A.KD_PRODI, NM_DOSEN
									FROM SIA.V_DOSEN A, SIA.MASTER_PRODI B
									WHERE A.KD_PRODI = B.KD_PRODI (+) AND (UPPER(A.NM_DOSEN) LIKE (UPPER ('%$cek%'))) AND A.KD_PRODI IN ('$tempprodi')")->result_array();
        return $hp; */
		
		//D_TIMAJAR2013 (KD_KELAS, KD_DOSEN)
		
		$hp = $this->db->query("SELECT DISTINCT B.KD_DOSEN, B.NM_DOSEN, B.NM_DOSEN_F FROM SIA.V_KELAS A, SIA.V_DOSEN B, SIA.D_TIMAJAR2013 C WHERE A.KD_KELAS = C.KD_KELAS AND C.KD_DOSEN = B.KD_DOSEN AND (UPPER(B.NM_DOSEN) LIKE (UPPER ('%".$cek."%')) OR B.KD_DOSEN LIKE '%".$cek."%') AND (A.KD_PRODI IN ('".$tempprodi."'))")->result_array();
		
		
		/* $out_kd_prodi = $this->db->query("SELECT DISTINCT D.KD_PRODI FROM SIA.V_KELAS D, SIA.D_TIMAJAR2013 E WHERE D.KD_KELAS = E.KD_KELAS(+) AND E.KD_DOSEN = '".$cek."'")->result_array();
		if(!empty($out_kd_prodi)){
			for($i=0; $i<count($out_kd_prodi); $i++){
				if($i==0) $kd_prodi_b[0] = $out_kd_prodi[$i]['KD_PRODI'];
				$kd_prodi_b[0] .= "','".$out_kd_prodi[$i]['KD_PRODI'];
			}
		}else{
			$kd_prodi_b[0] = '';
		}
		$hp = $this->db->query("SELECT DISTINCT B.KD_DOSEN, B.KD_PRODI, B.NM_DOSEN
									FROM SIA.V_KELAS A, SIA.V_DOSEN B, SIA.D_TIMAJAR2013 C 
									WHERE A.KD_KELAS = C.KD_KELAS AND C.KD_DOSEN = B.KD_DOSEN AND (UPPER(B.NM_DOSEN) LIKE (UPPER ('%$cek%')) OR B.KD_DOSEN = '".$cek."') AND (A.KD_PRODI IN ('$tempprodi') OR A.KD_PRODI IN ('".$kd_prodi_b[0]."'))")->result_array(); */
		return $hp;
    }
	
}
?>