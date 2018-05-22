<?php
	
	# FUNGSI GENERATE NIP DOSEN/PEGAWAI
	function _generate_nip($nip){
		$pnjNip = strlen($nip);
		if($pnjNip == 18){
			$tgl_lahir = substr($nip, 0,8);
			$bln_masuk = substr($nip, 8,6);
			$jk = substr($nip, 14,1);
			$no = substr($nip, 15,3);
			return $tgl_lahir.' '.$bln_masuk.' '.$jk.' '.$no;
		}else{
			return $nip;
		}
	}

	function konv_label_ta($kd_ta){
		$blkg = $kd_ta + 1;
		$label = $kd_ta.'/'.$blkg;
		return $label;
	}
	
	function konv_label_smt($kd_smt){
		if($kd_smt == 1){
			return 'GANJIL';
		}else{
			return 'GENAP';
		}
	}
	
?>