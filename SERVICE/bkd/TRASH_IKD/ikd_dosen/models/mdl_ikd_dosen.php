<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi IKD
 * @subpackage  IKD Dosen
 * @category    Master data (1)
 * @creator     Rischan Mafrur
 * @created     5-Juni-2013
*/
 
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
class Mdl_ikd_dosen extends CI_Model {

    function __construct() {
        parent::__construct();		
    }
	
	######### FUNCTION TIDAK TERPAKAI (JADUL)
	
		/*private function reindex($tablename, $id_name) {
			$query = $this->db->query("SELECT IFNULL((MAX(".$id_name.")+1),1) As id FROM `".$tablename."`");
			foreach($query->result_array() as $row) {
				$max = $row['id'];			
			}
			$this->db->query('ALTER TABLE `'.$tablename.'` AUTO_INCREMENT ='.$max.';');
		}*/

		function Coba_Ikd(){

			return $this->db->query("SELECT * FROM ikd.ikd_d_soal_quesioner")->result_array();
		}
		
		function Tampil_Data($username)
		{
			$tampil = $this->db->query("select a.kd_smt,a.kd_krs,a.semester,a.kd_prodi,a.nim,b.kd_krs,b.kd_kelas,b.kd_kur,b.kd_prodi,b.kd_mk,b.sks,c.kd_kur,c.kd_prodi,c.kd_mk,c.nm_mk 
										from ikd.d_krs a ,ikd.d_detail_krs b,ikd.md_matakuliah_kur_prodi c 
										where a.nip = '$username' and a.semester = (select max(semester) from d_krs where nim = '$nim') and a.kd_krs=b.kd_krs and b.KD_MK=c.KD_MK")->result_array();
			return $tampil;
		}
		function Tampil_Soal($kd_dosen)
		{
			$tampil = $this->db->query("select DISTINCT a.KD_MK, e.NM_MK, d.SKS, f.KD_TA, f.KD_SMT
										from SIA.D_PENGAMPU_MK a, SIA.D_DOSEN b ,SIA.D_KELAS c, SIA.D_DETAIL_KRS d, SIA.MD_MATAKULIAH_KUR_PRODI e, SIA.D_KRS f
										where b.KD_DOSEN = '$kd_dosen' and b.KD_DOSEN = c.KD_DOSEN and d.KD_MK = a.KD_MK and c.KD_KELAS = d.KD_KELAS 
										and c.KD_TA = (select max (c.KD_TA) from SIA.D_DOSEN a, SIA.D_KELAS c where a.KD_DOSEN = '$kd_dosen' and b.KD_DOSEN = c.KD_DOSEN) 
										and a.KD_MK = e.KD_MK and d.KD_MK = e.KD_MK and d.KD_KRS = f.KD_KRS")->result_array();
			return $tampil;	
		}
		
		function Tampil_ByKatergori($kd_dosen,$tahun,$semester)
		{
			$tampil = $this->db->query("select DISTINCT a.KD_MK, e.NM_MK, d.SKS, f.KD_TA, f.KD_SMT
											from SIA.D_PENGAMPU_MK a, SIA.D_DOSEN b ,SIA.D_KELAS c, SIA.D_DETAIL_KRS d, SIA.MD_MATAKULIAH_KUR_PRODI e, SIA.D_KRS f
											where 	b.KD_DOSEN = '$kd_dosen' and b.KD_DOSEN = c.KD_DOSEN and d.KD_MK = a.KD_MK and c.KD_KELAS = d.KD_KELAS 
											and c.KD_TA = '$tahun'  and c.KD_SMT = '$semester'
											and a.KD_MK = e.KD_MK and d.KD_MK = e.KD_MK and d.KD_KRS = f.KD_KRS ")->result_array();
			return $tampil;	
		}
		
		function Tampil_ByKatergoriBaru($kd_dosen,$tahun,$semester){
				$parameter = array($kd_dosen, $tahun, $semester);		
				$tampil = $this->db->query("SELECT B.KD_MK, B.NM_MK, B.KELAS_PARAREL, B.KD_KELAS, B.SKS, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.NO_RUANG, B.TERISI, B.TATAP, COUNT(A.KD_KELAS) AS JUM_KULIAH, B.KD_DOSEN, B.KD_TA, B.KD_SMT, B.NM_JENIS_MK, TO_CHAR(MAX(A.TGL_KUL),'DD/MM/YYYY') TGL_UPDATE, B.JADWAL1, B.JADWAL2, B.NM_PRODI 
												FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B 
												WHERE A.KD_KELAS (+) = B.KD_KELAS AND B.KD_DOSEN = ? AND B.KD_TA = ? AND B.KD_SMT = ?
												GROUP BY B.KD_MK, B.NM_MK, B.KELAS_PARAREL, B.SKS, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.NO_RUANG, B.TERISI, B.TATAP, B.KD_DOSEN, B.KD_TA, B.KD_SMT,B.NM_JENIS_MK,JADWAL1, B.JADWAL2, B.NM_PRODI, B.KD_KELAS",$parameter)->result_array();
				#return $parameter;
				return $tampil;	
		}
		
		function Total_Soal($nim)
		{
			$query_total=$this->db->query("select a.kd_smt,a.kd_krs,a.semester,a.kd_prodi,a.nim,b.kd_krs,b.kd_kelas,b.kd_kur,b.kd_prodi,b.kd_mk,b.sks,c.kd_kur,c.kd_prodi,c.kd_mk,c.nm_mk 
											from SIA.d_krs a ,SIA.d_detail_krs b,SIA.md_matakuliah_kur_prodi c 
											where a.nim = '$nim' and a.semester = (select max(a.semester) from d_krs a where a.nim = '$nim') and a.kd_krs=b.kd_krs and b.KD_MK=c.KD_MK")->result_array();
			return $query_total;
		}
		function Lihat_Soal_Quesioner($batas,$ofset)
		{
			$tampil = $this->db->query("select * from IKD.IKD_D_SOAL_QUISIONER where $batas >= rownum and rownum >= $ofset");
			return $tampil;
		}
		function Total_Lihat_Soal($id_mk,$nim)
		{
			$query_total=$this->db->query("select distinct a.kd_mk, a.nm_mk from SIA.md_matakuliah_kur_prodi a,SIA.d_krs b,SIA.d_detail_krs c where a.kd_mk ='$id_mk' and b.nim='$nim' and a.kd_mk = c.kd_mk and b.kd_krs= c.kd_krs")->result_array();
			return $query_total;
		}
		function Tampilkan_Soal()
		{
			$query_total=$this->db->query("select isi,ket,jwb_a,jwb_b,jwb_c,jwb_d from IKD.IKD_D_SOAL_QUISIONER")->result_array();
			return $query_total;
		}
		
		function Tampil_Tahun($kd_dosen)
		{
			$query_total=$this->db->query("select DISTINCT g.KD_TA, g.TA, h.NM_SMT, h.KD_SMT
											FROM SIA.D_PENGAMPU_MK a, SIA.D_DOSEN b ,SIA.D_KELAS c, SIA.D_DETAIL_KRS d, SIA.MD_MATAKULIAH_KUR_PRODI e, SIA.D_KRS f ,SIA.D_TA g, SIA.D_SEMESTER h 
											where 	b.KD_DOSEN = '$kd_dosen' and b.KD_DOSEN = c.KD_DOSEN and d.KD_MK = a.KD_MK and c.KD_KELAS = d.KD_KELAS 
											and c.KD_TA = f.KD_TA and g.KD_TA = f.KD_TA and f.KD_SMT = h.KD_SMT 
											and a.KD_MK = e.KD_MK and d.KD_MK = e.KD_MK and d.KD_KRS = f.KD_KRS 
											order by g.KD_TA desc")->result_array();
			return $query_total;
		}
		
		function Judul_MK($id_mk)
		{
			$matkul=$this->db->query("select distinct nm_mk,kd_mk from SIA.MD_MATAKULIAH_KUR_PRODI where kd_mk = '$id_mk'")->result_array();
			return $matkul;
		}
		
		function Judul_MK_Soal($id_mk,$nim)
		{
			$matkul=$this->db->query("select distinct a.nm_mk,a.kd_mk,b.kd_ta,b.kd_smt 
										from SIA.MD_MATAKULIAH_KUR_PRODI a, SIA.D_KRS b, SIA.D_DETAIL_KRS c 
										where a.kd_mk = '$id_mk' and b.NIM = '$nim' and b.KD_KRS = c.KD_KRS and a.KD_MK = c.KD_MK")->result_array();
			return $matkul;
		}
		
		function Hitung_Hasil($id_mk,$no_soal)
		{
			$query=$this->db->query("select * from tblsoal left join tblmatkul on tblsoal.id_matkul=tblmatkul.id_mk where id_matkul='$id_mk' AND no_soal='$no_soal'")->result_array();
			return $query;
		}
		function Simpan_Hasil($kd_mk,$username,$hasil,$smt,$tahun)
		{
			$sql = "INSERT INTO IKD.IDK_D_HASIL_QUISIONER (KD_MK, NIM, HASIL, KD_SMT, KD_TA) 
					VALUES (".$this->db->escape($kd_mk).",".$this->db->escape($username).",".$this->db->escape($hasil).",".$this->db->escape($smt).",".$this->db->escape($tahun).")";
			return $this->db->query($sql)->result_array();
			
		}
		function Validasi_Kuesioner($id_mk,$user)
		{
			$valid=$this->db->query("SELECT * FROM IKD.IKD_D_HASIL_QUISIONER where KD_MK='$id_mk' AND NIM='$user'")->result_array();
			return $valid;
		}
		function Lihat_Nilai($kd_dosen,$kd_mk,$tahun,$smt,$kd_kls)
		{
			$nilai=$this->db->query("SELECT DISTINCT f.KD_MK, e.NM_DOSEN, g.NM_MK, f.KD_DOSEN, f.KD_TA, f.KD_SMT, b.SKS, c.KELAS_PARAREL, c.TERISI, f.KD_MK, (sum (f.HASIL) / count (f.HASIL)*0.1) K3, h.TA , i.NM_PRODI
										FROM SIA.D_DOSEN e, IKD.IKD_D_HASIL_QUISIONER f , SIA.D_KRS a, SIA.D_DETAIL_KRS b, SIA.D_KELAS c, SIA.MD_MATAKULIAH_KUR_PRODI g, SIA.D_TA h, SIA.MASTER_PRODI i
										where f.KD_DOSEN = '197409111999032001' and f.KD_MK = 'KUI-401-3' and f.KD_DOSEN = e.KD_DOSEN and f.KD_TA = '2012' and f.KD_SMT = '1' and e.KD_DOSEN = c.KD_DOSEN and b.KD_KELAS = c.KD_KELAS AND F.KD_KELAS = '2222110014245' AND F.KD_KELAS = B.KD_KELAS
										and b.KD_MK = f.KD_MK and a.KD_KRS = b.KD_KRS and g.KD_MK = f.KD_MK and c.KELAS_PARAREL = f.KELAS and a.KD_TA = f.KD_TA and a.KD_SMT = f.KD_SMT and f.KD_TA = h.KD_TA and e.KD_PRODI = i.KD_PRODI
										GROUP BY e.NM_DOSEN,f.KD_DOSEN,f.KD_TA,f.KD_SMT,b.KD_KRS, b.SKS, f.KD_MK, g.NM_MK, c.TERISI, c.KELAS_PARAREL, h.TA, i.NM_PRODI")->result_array();
			return $nilai;
		}
		
		function Lihat_Nilai_K3($kd_dosen='',$kd_mk='',$tahun='',$smt='',$kd_kls='')
		{
			$nilai=$this->db->query("SELECT (SUM (A.HASIL) / COUNT (A.HASIL)*0.1) AS K3, COUNT(A.HASIL)JUMLAH
										FROM IKD.IKD_D_HASIL_QUISIONER A 
										WHERE A.KD_DOSEN = '".$kd_dosen."' AND A.KD_MK = '".$kd_mk."' AND A.KD_TA = '".$tahun."' AND A.KD_SMT = '".$smt."' AND A.KD_KELAS = '".$kd_kls."' AND A.HASIL <> 0")->result_array();
			return $nilai;
		}
		
		function Isi_Kuesioner($kd_dosen,$kd_mk,$tahun,$smt,$kls,$kd_kls)
		{
			$IK=$this->db->query("SELECT COUNT(NIM)ISI_KUISIONER FROM IKD.IKD_D_HASIL_QUISIONER WHERE KD_DOSEN = '".$kd_dosen."' AND KD_MK = '".$kd_mk."' AND KD_TA = '".$tahun."' AND KD_SMT = '".$smt."' AND KELAS = '".$kls."' AND KD_KELAS = '".$kd_kls."'")->result_array();
			return $IK;
		}
		
		function Banyak_Kuliah($kd_dosen,$kd_mk,$tahun,$smt,$kls,$kd_kls)
		{
			$nilai=$this->db->query("SELECT DISTINCT B.KD_PRODI, C.NM_PRODI, C.NM_JURUSAN, C.NM_FAK, B.KD_TA, B.KD_SMT, E.TA, D.NM_SMT, B.KD_KELAS, B.NM_MK, B.SKS, B.KELAS_PARAREL, B.NM_JENIS_MK, B.KD_DOSEN, B.NIP, B.NM_DOSEN, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.KD_RUANG, B.NO_RUANG, B.TERISI, COUNT(F.NIM)ISI_KUISIONER,B.TATAP, (SELECT COUNT(A.KULIAH_KE) FROM D_MATERI_KULIAH A WHERE A.KD_KELAS = '".$kd_kls."')JUM_KULIAH, BAGI(COUNT(A.KD_KELAS), B.TATAP) PROSEN, TO_CHAR(MAX(A.TGL_KUL),'DD/MM/YYYY') TGL_UPDATE, B.JADWAL1, B.JADWAL2, B.KD_KUR, B.KD_MK 
										FROM SIA.D_MATERI_KULIAH A, SIA.V_KELAS B, SIA.V_PRODI C, SIA.D_SEMESTER D, SIA.D_TA E , IKD.IKD_D_HASIL_QUISIONER F
										WHERE B.KD_DOSEN = '".$kd_dosen."' AND B.KD_MK = '".$kd_mk."' AND B.KD_TA = '".$tahun."' AND B.KD_SMT= '".$smt."' AND B.KELAS_PARAREL= '".$kls."' AND F.KD_KELAS = '".$kd_kls."' AND B.KD_KELAS = F.KD_KELAS AND A.KD_KELAS (+) = B.KD_KELAS AND B.KD_PRODI = C.KD_PRODI AND B.KD_TA = E.KD_TA AND B.KD_SMT = D.KD_SMT AND F.KD_MK = B.KD_MK AND F.KD_DOSEN = B.KD_DOSEN
										GROUP BY B.KD_PRODI, C.NM_PRODI, C.NM_JURUSAN, C.NM_FAK, B.KD_TA, B.KD_SMT, E.TA, D.NM_SMT, B.KD_KELAS, B.NM_MK, B.SKS, B.KELAS_PARAREL, B.NM_JENIS_MK, B.KD_DOSEN, B.NIP, B.NM_DOSEN, B.KD_HARI, B.HARI, B.JAM_MULAI, B.JAM_SELESAI, B.KD_RUANG, B.NO_RUANG, B.TERISI, B.TATAP, B.TATAP, B.JADWAL1, B.JADWAL2, B.KD_KUR, B.KD_MK")->result_array();
			return $nilai;
		}
		
		function Detail_Nilai($kd_dosen,$kd_mk,$limit,$ofset,$tahun,$smt)
		{
			$nilai=$this->db->query("select distinct b.KD_MK, e.NM_DOSEN, g.NM_MK, e.KD_DOSEN, a.KD_TA, a.KD_SMT, b.SKS, c.KELAS_PARAREL, c.TERISI, h.TA , i.NM_PRODI
										from SIA.D_DOSEN e , SIA.D_KRS a, SIA.D_DETAIL_KRS b, SIA.D_KELAS c, SIA.MD_MATAKULIAH_KUR_PRODI g, SIA.D_TA h, SIA.MASTER_PRODI i
										where e.KD_DOSEN = '$kd_dosen' and g.KD_MK = '$kd_mk' and c.KD_DOSEN = e.KD_DOSEN and a.KD_TA = '$tahun' and a.KD_SMT = '$smt' and e.KD_DOSEN = c.KD_DOSEN and b.KD_KELAS = c.KD_KELAS 
										and b.KD_MK = c.KD_MK and a.KD_KRS = b.KD_KRS and g.KD_MK = c.KD_MK and a.KD_TA = c.KD_TA and a.KD_SMT = c.KD_SMT and c.KD_TA = h.KD_TA and e.KD_PRODI = i.KD_PRODI
										and $limit >= rownum and rownum >= $ofset order by c.KELAS_PARAREL ASC")->result_array();
			return $nilai;
		}
		
		function Lihat_Nilai_Bykatergori($username,$limit,$ofset,$tahun,$semester)
		{
			$nilai=$this->db->query("select distinct a.KD_MK,a.NIM,a.HASIL,b.NM_MK 
									from IKD.IKD_D_HASIL_QUISIONER a, SIA.MD_MATAKULIAH_KUR_PRODI b, SIA.D_KRS c 
									where a.NIM = '$username' and c.KD_TA='$tahun' and a.KD_SMT='$semester' and a.nim=c.nim and a.KD_SMT = c.KD_SMT  and a.kd_ta=c.kd_ta and a.kd_mk=b.kd_mk and $limit >= rownum and rownum >= $ofset order by a.KD_MK")->result_array();
			return $nilai;
		}

		function Pengumpulan_Berkas($kd_dosen,$kd_mk,$kd_ta,$kd_smt,$kls,$kd_kls){	
			$pb	= $this->db->query("SELECT TO_CHAR(TGL_UJIAN,'DD MONTH YYYY','nls_date_language = INDONESIAN')TGL_UJIAN,TO_CHAR(TGL_PENGUMPULAN,'DD MONTH YYYY','nls_date_language = INDONESIAN')TGL_PENGUMPULAN,KD_BERKAS,KD_MK,KD_DOSEN,KD_TA,KD_SMT,HASIL,KD_KLS,KD_KELAS 
			FROM IKD.IKD_D_PENGUMPULAN_BERKAS 
			WHERE KD_MK = '".$kd_mk."' AND KD_DOSEN = '".$kd_dosen."' AND KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND KD_KLS = '".$kls."' AND KD_KELAS = '".$kd_kls."'")->result_array();
			return $pb;
		}
		
		/* function Pengumpulan_Berkas_coba(){	
			$pb	= $this->db->query("SELECT TGL_UJIAN,TO_CHAR(TGL_UJIAN,'DD MONTH YYYY')AS TGL_UJIAN_A,TO_CHAR(TGL_PENGUMPULAN,'DD MONTH YYYY')TGL_PENGUMPULAN,KD_BERKAS,KD_MK,KD_DOSEN,KD_TA,KD_SMT,HASIL,KD_KLS,KD_KELAS FROM IKD.IKD_D_PENGUMPULAN_BERKAS WHERE KD_DOSEN = '197409111999032001'")->result_array();
			return $pb;
		} */
		
		function Lihat_komen_mhs($kd_dosen,$kd_mk,$kd_ta,$kd_smt,$kls,$kd_kls){	
			$pb	= $this->db->query("SELECT COMMENT_Q,HASIL FROM IKD.IKD_D_HASIL_QUISIONER WHERE KD_MK = '".$kd_mk."' AND KD_DOSEN = '".$kd_dosen."' AND KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND KELAS = '".$kls."' AND KD_KELAS = '".$kd_kls."' AND HASIL <> 0")->result_array();
			return $pb;
		}
        
        function Lihat_Tanggal_Ujian($kd_dosen, $kd_mk, $tahun, $smt, $kls, $kd_kelas) {
            $tanggal = $this->db->query("SELECT B.KD_KELAS, A.KD_J_UJIAN, A.KD_RUANG, TO_CHAR(A.TGL,'MM/DD/YYYY')AS TGL, TO_CHAR(A.TGL,'DAY, MM/DD/YYYY') TGL1, TO_CHAR(A.JAM_MULAI,'HH24:MI') JAM_MULAI, TO_CHAR(A.JAM_SELESAI,'HH24:MI') JAM_SELESAI, A.JUM_PESERTA, B.KD_KUR, B.KD_PRODI, B.NM_PRODI, B.KD_MK, B.KD_TA, B.KD_SMT, B.NM_MK, B.SKS, B.KELAS_PARAREL, B.KD_DOSEN, B.NM_DOSEN, B.NIP, B.NM_JENIS_MK, B.SEMESTER_PAKET, A.KD_RUANG NO_RUANG, B.TERISI PESERTA, C.NM_J_UJIAN, A.KETERANGAN 
                                                                                            FROM SIA.D_JADWAL_UJIAN A, SIA.V_KELAS B, SIA.D_JENIS_UJIAN C 
                                                                                            WHERE A.KD_KELAS (+) = B.KD_KELAS AND A.KD_J_UJIAN = C.KD_J_UJIAN (+) AND B.KD_DOSEN = '$kd_dosen' AND B.KD_MK = '$kd_mk' AND B.KD_TA = '$tahun' AND B.KD_SMT = '$smt' AND B.KELAS_PARAREL = '$kls' AND B.KD_KELAS = '$kd_kelas' AND A.KD_J_UJIAN = '2'")->result_array();

            return $tanggal;
        }
        function Lihat_Tanggal_Pengumpulan($kd_dosen, $kd_mk, $tahun, $smt, $kd_kelas) {
            $ltp = $this->db->query("SELECT DISTINCT TO_CHAR(TGL_NILAI,'MM/DD/YYYY')AS TGL_NILAI, TO_CHAR(LAST_UPDATE,'MM/DD/YYYY')LAST_UPDATE FROM SIA.D_TRANSKRIP_HISTORI WHERE KD_KELAS = '".$kd_kelas."' AND KD_DOSEN = '".$kd_dosen."' AND KD_TA = '".$tahun."' AND KD_SMT = '".$smt."' AND KD_MK = '".$kd_mk."'")->result_array();
            return $ltp;
        } 
		//'197108231999031003', 'TIF21723', '2012', '1', '2260710019796'
		/* function Lihat_Tanggal_Pengumpulan_coba() {
            $ltp = $this->db->query("SELECT DISTINCT TO_CHAR(TGL_NILAI,'MM/DD/YYYY')AS TGL_NILAI FROM SIA.D_TRANSKRIP_HISTORI WHERE KD_KELAS = '2222110014222' AND KD_DOSEN = '197409111999032001' AND KD_TA = '2012' AND KD_SMT = '1' AND KD_MK = 'KUI-307-3'")->result_array();
            return $ltp;
        } */
        
        function Lihat_hari_lbur() {
            $lhl = $this->db->query("SELECT TANGGAL FROM IKD.IKD_D_HARI_LIBUR_NASIONAL")->result_array();
            return $lhl;
        }

        function cek_pengumpulan_berkas($kd_dosen='', $kd_mk='', $kd_ta='', $kd_smt='', $kd_kls='', $kd_kelas='') {
            $lhl = $this->db->query("SELECT * FROM IKD.IKD_D_PENGUMPULAN_BERKAS WHERE KD_MK ='$kd_mk' AND KD_DOSEN ='$kd_dosen' AND KD_TA ='$kd_ta' AND KD_SMT ='$kd_smt' AND KD_KLS ='$kd_kls' AND KD_KELAS = '$kd_kelas' ")->result_array();
            return $lhl;
        }
        
        function Update_Tgl_Pengumpulan($kd_berkas='', $kd_mk='', $kd_dosen='', $kd_ta='', $kd_smt='', $tgl_ujian='', $tgl_pengumpulan='', $hasil='', $kd_kls='', $kd_kelas=''){
            $utp = $this->db->query("UPDATE IKD.IKD_D_PENGUMPULAN_BERKAS SET KD_MK='".$kd_mk."', KD_DOSEN ='".$kd_dosen."', KD_TA ='".$kd_ta."', KD_SMT='".$kd_smt."', TGL_UJIAN = '".$tgl_ujian."',TGL_PENGUMPULAN = '".$tgl_pengumpulan."',HASIL = '".$hasil."', KD_KLS = '".$kd_kls."', KD_KELAS = '".$kd_kelas."' WHERE KD_BERKAS = $kd_berkas")->result_array();
            return $utp;
        }
        
        function Insert_Tgl_Pengumpulan($kd_mk, $kd_dosen, $kd_ta, $kd_smt, $tgl_ujian, $tgl_pengumpulan, $hasil, $kd_kls, $kd_kelas) {
            $sql = "INSERT INTO IKD.IKD_D_PENGUMPULAN_BERKAS (KD_MK, KD_DOSEN, KD_TA, KD_SMT, TGL_UJIAN, TGL_PENGUMPULAN, HASIL, KD_KLS, KD_KELAS) 
                                            VALUES ('".$kd_mk."','".$kd_dosen."','".$kd_ta."','".$kd_smt."','".$tgl_ujian."','".$tgl_pengumpulan."',$hasil,'".$kd_kls."','".$kd_kelas."')";
          	return $this->db->query($sql)->result_array();
        }
		
		######### FUNCTION DI ATAS TIDAK TERPAKAI
		
		
		function outValK3($kd_dosen='',$tahun='',$smt='',$kd_kls=''){
			$nilai=$this->db->query("SELECT (SUM (A.HASIL) / COUNT (A.HASIL)*0.1) AS K3, COUNT(A.HASIL)JUMLAH
										FROM IKD.IKD_D_HASIL_QUISIONER A 
										WHERE A.KD_DOSEN = '".$kd_dosen."' AND A.KD_TA = '".$tahun."' AND A.KD_SMT = '".$smt."' AND A.KD_KELAS = '".$kd_kls."' AND A.HASIL <> 0")->result_array();
			return $nilai;
		}
		
		function outSumIn($kd_dosen,$tahun,$smt,$kd_kls){
			$IK=$this->db->query("SELECT COUNT(NIM)AS ISI_KUISIONER FROM IKD.IKD_D_HASIL_QUISIONER WHERE KD_DOSEN = '".$kd_dosen."' AND KD_TA = '".$tahun."' AND KD_SMT = '".$smt."' AND KD_KELAS = '".$kd_kls."'")->result_array();
			return $IK;
		}
		
		function outKomen($kd_dosen,$kd_ta,$kd_smt,$kd_kls){	
			$pb	= $this->db->query("SELECT COMMENT_Q, HASIL FROM IKD.IKD_D_HASIL_QUISIONER WHERE KD_DOSEN = '".$kd_dosen."' AND KD_TA = '".$kd_ta."' AND KD_SMT = '".$kd_smt."' AND KD_KELAS = '".$kd_kls."' AND HASIL <> 0")->result_array();
			return $pb;
		}
        
        function outDetailJawaban($kd_dosen,$kd_ta,$kd_smt,$kd_kls){
			$pb	= $this->db->query("SELECT A.NIM, A.KD_DOSEN, A.KD_MK,  B.ID_IKD_JWB, B.ID_SOAL, B.PILIHAN FROM IKD.IKD_D_PENGISI_JAWABAN A, IKD.IKD_D_DETAIL_JAWABAN B WHERE A.KD_DOSEN = '".$kd_dosen."' AND A.KD_TA = '".$kd_ta."' AND A.KD_SMT = '".$kd_smt."' AND A.KD_KELAS = '".$kd_kls."' AND A.ID_IKD_JWB = B.ID_IKD_JWB ORDER BY B.ID_IKD_JWB, B.ID_SOAL")->result_array();
			return $pb;
		}
        
        function outDetailJawaban_pivot($kd_dosen,$kd_ta,$kd_smt,$kd_kls){
			$pb	= $this->db->query("SELECT * FROM (
									SELECT X.*,Y.TOTAL FROM
									(SELECT C.ID_SOAL, C.SOAL, D.POINT, COUNT(D.POINT) JUMLAH
									FROM IKD.IKD_D_PENGISI_JAWABAN A, IKD.IKD_D_DETAIL_JAWABAN B, IKD.IKD_MD_SOALBARU C, IKD.IKD_MD_JAWABANSOAL D
									WHERE A.KD_DOSEN = '".$kd_dosen."' AND A.KD_TA = '".$kd_ta."' AND A.KD_SMT = '".$kd_smt."' AND A.KD_KELAS = '".$kd_kls."'
									AND A.ID_IKD_JWB = B.ID_IKD_JWB AND B.ID_SOAL = C.ID_SOAL AND B.ID_SOAL = D.ID_SOAL AND B.PILIHAN = D.PILIHAN 
									GROUP BY C.ID_SOAL, C.SOAL, D.POINT 
									ORDER BY C.ID_SOAL
									) X,
									(SELECT C.ID_SOAL, C.SOAL, COUNT(C.ID_SOAL) TOTAL
									FROM IKD.IKD_D_PENGISI_JAWABAN A, IKD.IKD_D_DETAIL_JAWABAN B, IKD.IKD_MD_SOALBARU C
									WHERE A.KD_DOSEN = '".$kd_dosen."' AND A.KD_TA = '".$kd_ta."' AND A.KD_SMT = '".$kd_smt."' AND A.KD_KELAS = '".$kd_kls."' AND A.ID_IKD_JWB = B.ID_IKD_JWB AND B.ID_SOAL = C.ID_SOAL
									GROUP BY C.ID_SOAL, C.SOAL
									ORDER BY C.ID_SOAL
									) Y
									WHERE X.ID_SOAL = Y.ID_SOAL
									)
									PIVOT ( SUM(JUMLAH) FOR POINT IN (1,2,3,4) )
									ORDER BY ID_SOAL")->result_array();
			return $pb;
		}
	
}
?>