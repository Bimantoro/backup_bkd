<?php if (!defined('BASEPATH')) exit ('Uukkh... Kamu kok nakal banget sich...');

class Mdl_kompilasi extends CI_Model{

	function __construct(){
		parent::__construct();
	}
	


	function mdl_dosen_diasesori($kd_dosen, $kd_ta, $kd_smt){
		$sql = "SELECT * FROM BKD.BKD_ASESOR_DOSEN WHERE 
				(NIRA1 = (SELECT NIRA FROM BKD.BKD_ASESOR WHERE KD_DOSEN = '$kd_dosen') OR
                NIRA2 = (SELECT NIRA FROM BKD.BKD_ASESOR WHERE KD_DOSEN = '$kd_dosen'))       
                AND KD_TA = '$kd_ta' AND KD_SMT = '$kd_smt'";
				
		return $this->db->query($sql)->result_array();
		
	}

	function mdl_update_status_kinerja($kd_bk, $kolom, $value){
		$sql = "UPDATE BKD.BKD_BEBAN_KERJA SET $kolom = '$value' WHERE KD_BK = '$kd_bk'";
		return $this->db->query($sql);
	}
	
	function mdl_update_status_rencana($kd_bk, $kolom, $value){
		$sql = "UPDATE BKD.RBKD SET $kolom = '$value' WHERE KD_RBK = '$kd_bk'";
		return $this->db->query($sql);
	}
	
}

