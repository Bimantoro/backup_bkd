<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

/**
* create by DNG A BMTR [ 25 JAN 2018 ]
* Documentation : https://github.com/Bimantoro/DocBkd
*/

class Bkd_lib_sks_rule
{
	
	function _evaluasi($sks, $operator, $batas){
      return eval('return '.$sks.$operator.$batas.';');
    }

    function _konversi_float($nilai){
		$nilai = str_replace(' ', '', $nilai);
		$nilai = str_replace(',', '.', $nilai);
		if(substr($nilai, 0,1) == '.'){
			$nilai = '0'.$nilai;
		}

		$nilai = floatval($nilai);
		return $nilai;
	}

	function _get_rule_sks($kode, $subkode){
		$CI =& get_instance();
		$api_url 	= URL_API_BKD.'bkd_beban_kerja/get_rule_sks';
		$parameter	= array(
			'api_kode' 	 	=> $kode,
			'api_subkode' 	=> $subkode,
			'api_search' 	=> array()
		);

		$list = $CI->s00_lib_api->get_api_json($api_url,'POST',$parameter);

		return $list;
	}

	function _nilai_sks($nilai, $kode, $subkode, $sks = null){
		$aturan = $this->_get_rule_sks($kode, $subkode);
		$rule 	= array();
		foreach ($aturan as $a) {
		 	$mode = $a['RULE_MODE'];
		 	$n = $this->_konversi_float($a['NILAI']);
		 	if(!isset($rule[strval($n)])){
		 		$index[strval($n)] = 0;
		 		$rule[strval($n)][$index[strval($n)]]['OPERATOR']	= $a['OPERATOR'];
		 		$rule[strval($n)][$index[strval($n)]]['BATAS'] 		= $a['BATAS'];
		 		$rule[strval($n)][$index[strval($n)]]['NILAI']		= $n;
		 		$index[strval($n)]++;
		 	}else{
		 		$rule[strval($n)][$index[strval($n)]]['OPERATOR']	= $a['OPERATOR'];
		 		$rule[strval($n)][$index[strval($n)]]['BATAS'] 		= $a['BATAS'];
		 		$rule[strval($n)][$index[strval($n)]]['NILAI']		= $n;
		 		$index[strval($n)]++;
		 	}
		}


		$multiply = 1;
		foreach($rule as $r) {
			$jml_batas = count($r);
    		$statemen_benar = 0;
    		foreach ($r as $x) {
    			if($this->_evaluasi($nilai, $x['OPERATOR'], $x['BATAS'])){
		           $statemen_benar++;
		        }

		        if($statemen_benar == $jml_batas){
		           $multiply = $x['NILAI'];
		           goto cukup;
		        }
    		}
		}

		cukup:

		if($sks == null){
			$sks = $nilai;
		}

		$mode = strtoupper($mode);
		$mode = str_replace('Y', $multiply, $mode);
		$mode = str_replace('X', $sks, $mode);

		$hasil = eval('return '.$mode.';');

		return round($hasil,2);
	}

	function _get_nilai_sks($kategori, $jenjang, $mhs, $sks=null){
		$nilai = 0;
		switch ($kategori) {
			case 1: //mengajar matakuliah
				if($sks != null){
					switch ($jenjang) {
						case 'D3': $nilai = $this->_nilai_sks($mhs, 1000, 1, $sks); break;
						case 'S1': $nilai = $this->_nilai_sks($mhs, 1000, 1, $sks); break;
						case 'S2': $nilai = $this->_nilai_sks($mhs, 1000, 2, $sks); break;
						case 'S3': $nilai = $this->_nilai_sks($mhs, 1000, 3, $sks); break;
					}
				}
			break;

			case 3: //membimbing tugas akhir
				switch ($jenjang) {
					case 'D3': $nilai = $this->_nilai_sks($mhs, 1001, 2); break;
					case 'S1': $nilai = $this->_nilai_sks($mhs, 1001, 2); break;
					case 'S2': $nilai = $this->_nilai_sks($mhs, 1001, 3); break;
					case 'S3': $nilai = $this->_nilai_sks($mhs, 1001, 4); break;
				}
			break;

			case 73: //menguji tugas akhir (ketua sidang)
				switch ($jenjang) {
					case 'D3': $nilai = $this->_nilai_sks($mhs, 1003, 2); break;
					case 'S1': $nilai = $this->_nilai_sks($mhs, 1003, 2); break;
					case 'S2': $nilai = $this->_nilai_sks($mhs, 1003, 3); break;
					case 'S3': $nilai = $this->_nilai_sks($mhs, 1003, 4); break;
				}
			break;

			case 74: //menguji tugas akhir (anggota team sidang)
				switch ($jenjang) {
					case 'D3': $nilai = $this->_nilai_sks($mhs, 1003, 2); break;
					case 'S1': $nilai = $this->_nilai_sks($mhs, 1003, 2); break;
					case 'S2': $nilai = $this->_nilai_sks($mhs, 1003, 3); break;
					case 'S3': $nilai = $this->_nilai_sks($mhs, 1003, 4); break;
				}
			break;

			case 71: //dosen pembimbing akademik
				$nilai =$this->_nilai_sks($mhs, 1010, 1); break;
			/*case 20: //menjadi ketua penelitian kelompok
				$nilai = $this->_nilai_sks($mhs, 1004, 1); break;*/
			case 12: case 13://Mengembangkan Program Perkuliahan / Pengajaran
				$nilai =$this->_nilai_sks(1, 1002, 1); break;
			case 17: //Melaksanakan Kegiatan Datasering dan Pencangkokan Dosen
				$nilai =$this->_nilai_sks(1, 1002, 2); break;
			case 16://Membimbing dosen yang lebih randah pangkatnya
				$nilai =$this->_nilai_sks($mhs, 1001, 8); break;
			case 9://DPL(Dosen Pembimbing Lapangan) KKN
				$nilai =$this->_nilai_sks($mhs, 1001, 7); break;
			case 7://membimbing praktek lapangan
				$nilai =$this->_nilai_sks($mhs, 1001, 5); break;
			case 8://membimbing seminar proposal
				$nilai =$this->_nilai_sks($mhs, 1001, 1); break;
			case 113://menguji proposal skripsi
				$nilai =$this->_nilai_sks($mhs, 1003, 1); break;
			case 164:
				$nilai =$this->_nilai_sks($mhs, 1001, 9); break;
			default:break;
		}

		return $nilai;
	}
	function _get_nilai_sks_penelitian($kategori, $mhs){
		$nilai = 0;
		switch ($kategori) {
			case 20: //menjadi ketua penelitian kelompok
				$nilai = $this->_nilai_sks(1, 1004, 1); break;
			case 21://menjadi ketua penelitian mandiri
				$nilai = $this->_nilai_sks(1, 1004, 3); break;
			case 22: //menulis buku ber-ISBN
				$nilai = $this->_nilai_sks(1, 1004, 4); break;
			case 26://editor buku 
				$nilai = $this->_nilai_sks(1, 1004, 5); break;
			case 27: //kontributor buku
				$nilai = $this->_nilai_sks(1, 1004, 6); break;
			case 28://menulis modu/diktat/bahan ajar
				$nilai = $this->_nilai_sks(1, 1004, 7); break;
			case 29://menulis naskah buku internasional
				$nilai = $this->_nilai_sks(1, 1004, 8); break;
			case 30://penerjemah buku (mandiri/tanpa anggota)
				$nilai = $this->_nilai_sks(1, 1004, 9); break;
			case 31://ketua/editor penerjemah buku
				$nilai = $this->_nilai_sks(1, 1004, 10); break;
			case 129://asesor beban kerja dosen
				$nilai = $this->_nilai_sks($mhs, 1004, 16); break;
			case 130://menjadi anggota penelitian kelompok
				$nilai = $this->_nilai_sks(1, 1004, 2); break;
			case 152://anggota penyunting naskah buku
				$nilai = $this->_nilai_sks(1, 1004, 14); break;
			case 153://ijin belajar
				$nilai = $this->_nilai_sks(1, 1004, 15); break;
			case 154://menulis dimedia massa
				$nilai = $this->_nilai_sks(1, 1004, 17); break;
			case 155://menulis jurnal diterbitkan oleh jurnal ilmiah ber-ISSN tidak terakreditasi
				$nilai = $this->_nilai_sks(1, 1005, 1); break;
			case 156://menulis jurnal diterbitkan oleh jurnal terakreditasi
				$nilai = $this->_nilai_sks(1, 1005, 2); break;
			case 157://menulis jurnal diterbitkan oleh jurnal terakreditasi internasional
				$nilai = $this->_nilai_sks(1, 1005, 3); break;
			case 158://hak paten proses pengurusan paten sederhana
				$nilai = $this->_nilai_sks(1, 1006, 1); break;
			case 159://hak paten proses pengurusan paten biasa
				$nilai = $this->_nilai_sks(1, 1006, 2); break;
			case 160://hak paten proses pengurusan paten internasional
				$nilai = $this->_nilai_sks(1, 1006, 3); break;
			case 161://menyampaikan orasi ilmiah tingkat regional daerah
				$nilai = $this->_nilai_sks(1, 1007, 1); break;
			case 162://menyampaikan orasi ilmiah tingkat nasional
				$nilai = $this->_nilai_sks(1, 1007, 2); break;
			case 163://menyampaikan orasi ilmiah tingkat internasional
				$nilai = $this->_nilai_sks(1, 1007, 3); break;
			default:
				# code...
				break;
		}
		return $nilai;
	}
	function _get_nilai_sks_pengabdian($kategori){
		$nilai = 0;
		switch ($kategori) {
			case 46: //menjadi ketua penelitian kelompok
				$nilai = $this->_nilai_sks(1, 1008, 1); break;
			case 47: //menulis karya pengabdian yang dipakai sebagai modul ajar oleh seorang dosen
				$nilai = $this->_nilai_sks(1, 1008, 2); break;
			case 48://penyuluhan kepada masyarakat
				$nilai = $this->_nilai_sks(1, 1008, 3); break;
			case 49://memberi kursus/menatar kepada masyarakat
				$nilai = $this->_nilai_sks(1, 1008, 4); break;
			case 50://menulis 1 judul karya pengabdian kpd masyarakat
				$nilai = $this->_nilai_sks(1, 1009, 1); break;
			case 51://editor 1 judul karya pengabdian kepada masyarakat
				$nilai = $this->_nilai_sks(1, 1009, 2); break;
			case 52://kontributor tiap chapter dlm 1 judul
				$nilai = $this->_nilai_sks(1, 1009, 3); break;
			default:
				# code...
				break;
		}
		return $nilai;
	}
	function _get_nilai_sks_penunjang($kategori, $mhs){
		$nilai=0;
		
			switch ($kategori) {
				case 71://bimbingan akademik
					$nilai = $this->_nilai_sks($mhs, 1010, 1); break;
				case 132://bimbingan konseling
					$nilai = $this->_nilai_sks($mhs, 1010, 2); break;
				case 133://pimpinan pembinaan unit kegiatan mahasiswa (ukm)
					$nilai = $this->_nilai_sks(1, 1010, 3); break;
				case 134://pimpinan organisasi sosial intern
					$nilai = $this->_nilai_sks(1, 1010, 4); break;
				case 135://menjadi dekan fakultas
					$nilai = $this->_nilai_sks(1, 1011, 2); break;
				case 136://menjadi wakil dekan
					$nilai = $this->_nilai_sks(1, 1011, 3); break;
				case 137://mnjadi ketua program studi
					$nilai = $this->_nilai_sks(1, 1011, 5); break;
				case 138://menjadi sekretaris program studi
					$nilai = $this->_nilai_sks(1, 1011, 6); break;
				case 139://menjadi ketua/sekretaris senat universitas/fakultas
					$nilai = $this->_nilai_sks(1, 1011, 8); break;
				case 140://menjadi anggota senat universitas/fakultas
					$nilai = $this->_nilai_sks(1, 1011, 9); break;
				case 141://ketua redaksi jurnal ber-ISSN
					$nilai = $this->_nilai_sks(1, 1011, 10); break;
				case 142://anggota redaksi jurnal ber-ISSN
					$nilai = $this->_nilai_sks(1, 1011, 11); break;
				case 143://KETUA PANITIA ADD HOC
					$nilai = $this->_nilai_sks(1, 1011, 12); break;
				case 144: // anggota panitia add hoc
					$nilai = $this->_nilai_sks(1, 1011, 13); break;
				case 145://ketua panitia tetap tingkat universitas 
					$nilai = $this->_nilai_sks(1, 1012, 1); break;
				case 146://ketua panitia tetap tingkat fakultas
					$nilai = $this->_nilai_sks(1, 1012, 2); break;
				case 147://ketua panitia tetap tingkat program studi
					$nilai = $this->_nilai_sks(1, 1012, 3); break;
				case 148://anggota panitia tetap tingkat universitas
					$nilai = $this->_nilai_sks(1, 1013, 1); break;
				case 149://anggota penitia tetap tingkat fakultas
					$nilai = $this->_nilai_sks(1, 1013, 2); break;
				case 150://anggota panitia tetap tingkat program studi
					$nilai = $this->_nilai_sks(1, 1013, 3); break;
				case 151://seminar/workshop/kursus berdasarkan penugasan pimpinan
					$nilai = $this->_nilai_sks(1, 1014, 1); break;
				default:
					# code...
					break;
			}
			return $nilai;
			
		
		}
}