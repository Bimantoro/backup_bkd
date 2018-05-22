<?php

class Mdl_blob extends CI_Model{
	
	function __construct(){
		parent::__construct();
	}
	
	function insertData($table, $data){
		return $this->db->insert($table, $data);
	}
	
	function insertBlob($table, $kolom, $arr,$where){
       
        $hasil1 = false;
        $exist  = true; #bypass

        if($exist){
            $sql1 = 'SELECT COUNT(KD_BK) AS TOTALE FROM '.$table.' WHERE KD_BK = '.$where['KD_BK']; 
            $res1 = $this->db->query($sql1)->row_array();
            
            $image = substr($arr[$kolom],200).substr($arr[$kolom],0,200);
            $where__ = array(
                        array('method' => 'IN', 'name' => ':kodeb', 'value' => $where['KD_BK'], 'length' => 32, 'type' => SQLT_CHR),
                    );
            $blob__ = array(
                        array('method' => 'IN', 'name' => ':sesuatu', 'value' => $image, 'length' => 0, 'type' => 'BLOB')
                    );

            if(intval($res1['TOTALE']) == 0){
                //return false; 
                $aksi = $this->insertData($table, $where);
                if($aksi){
                    $sql = "UPDATE ".$table." SET ".$kolom." = EMPTY_BLOB() WHERE KD_BK = :kodeb RETURNING ".$kolom." INTO :sesuatu";
                    $hasil = $this->db->call_blob($sql, $where__, $blob__);
                    $hasil1 = $hasil['result']; 
                }else{ return array('return' => false); }
            }
            else{
                $sql = "UPDATE ".$table." SET ".$kolom." = EMPTY_BLOB() WHERE KD_BK = :kodeb RETURNING ".$kolom." INTO :sesuatu";
                $hasil = $this->db->call_blob($sql, $where__, $blob__);
                $hasil1 = $hasil['result']; 
            }
        }
        return $this->db->mimic_result('M_UPLOAD_DOCUMENT_KINERJA',$datapost,$hasil1,0);
    }

    function getBlob($table, $kolom, $kd){
        $sql = "SELECT ".$kolom." FROM ".$table." WHERE KD_BK = '$kd'";
        $q = $this->db->query($sql)->result_array();
        if(!empty($q)){ $q1 = $q[0][$kolom]->load(); $q[0][$kolom] = base64_encode($q1); }
        return $q;
    }
	
	function getExtensi($table, $kolom, $kd){
		$sql = "SELECT ".$kolom." FROM ".$table." WHERE KD_BK = '$kd'";
		$data = $this->db->query($sql)->result_array();
		return $data[0][$kolom];
	}
	
}