<?php defined('BASEPATH') OR exit('No direct script access allowed');
 
/**
 *
 * @package		Revitalisasi SIA
 * @subpackage  SIA Staff
 * @category    KRS
 * @created 	3-12-2012, Wihikan MAwi Wijna
*/
 
// This can be removed if you use __autoload() in config.php OR use Modular Extensions
//require APPPATH.'/libraries/REST_Controller.php';
 
class Ikd_mhs extends CI_Controller
{
	protected $builtInMethods;
 
	public function __construct() {
		parent::__construct();
		$this->load->model('ikd_mahasiswa/mdl_ikd_mhs','mdl_1000');
	}
	
	function index() { echo 'ikd_mahasiswa'; }	
	
	/* function coba($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_coba(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	} */
	

	function cek_ikd_lengkap($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query =$this->mdl_1000->_mdl_ikd_proc_cekisilengkap($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);
	}	
	
	function outSoal($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query =$this->mdl_1000->outSoal(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function cekInsOrUpd($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query =$this->mdl_1000->cekInsOrUpd($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function getIdPengisiIkd($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query =$this->mdl_1000->getIdPengisiIkd($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function outCekJwban($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->outCekJwban($api_search[0],$api_search[1],$api_search[2]);
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function outCekKomen($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->outCekKomen($api_search[0],$api_search[1],$api_search[2]);
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function cekSdhKuesioner($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cekSdhKuesioner($api_search[0],$api_search[1],$api_search[2]);
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function cekDetailJwb($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cekDetailJwb($api_search[0],$api_search[1]);
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function inDetailJwb($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->inDetailJwb($api_search[0],$api_search[1],$api_search[2]);
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function cekSudahIkd($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				case 1: $query = $this->mdl_1000->cekSudahIkd($api_search[0],$api_search[1],$api_search[2]); break;
				case 2: $query = $this->mdl_1000->cek_isi_Kuesioner($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break;
				default:  $query = array(); break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function updDetailJwb($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->updDetailJwb($api_search[0],$api_search[1],$api_search[2]);
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function delPengisi($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->delPengisi($api_search[0],$api_search[1],$api_search[2]);
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function delDetailJwb($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->delDetailJwb($api_search[0]);
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function inPengisi($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->inPengisi($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function updPengisi($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->updPengisi($api_search[0],$api_search[1],$api_search[2],$api_search[3]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function cekHslPerhitungan($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cekHslPerhitungan($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function inHslPerhitungan($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->inHslPerhitungan($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5],$api_search[6],$api_search[7],$api_search[8],$api_search[9]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function updHslPerhitungan($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->updHslPerhitungan($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function outJawaban($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query =$this->mdl_1000->outJawaban(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function cekwaktukuesioner($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_cek_waktu(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function cekvalidasikuesioner($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_cekvalidasikuesioner($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function getmatkulterakhirmhs($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_getmatkulterakhirmhs($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function cobaguna($format = 'json'){
		/* $kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->cobaguna(); 
				break; 
			} 
		break;		
		} */
		#$query = $this->mdl_1000->cobaguna(); 
		/* $query = $this->db->query("SELECT G.NIM, A.KD_KRS, DECODE(A.STATUS_ULANG,'B','BARU','U','ULANG') STATUS_ULANG, A.NILAI, A.BOBOT_NILAI, E.TA, F.NM_SMT ,B.KD_KELAS,B.KD_KUR,B.KD_PRODI,B.KD_MK,B.KD_TA, B.KD_SMT,B.KELAS_PARAREL,B.KD_DOSEN,B.TATAP,B.SKS,B.MIN_PESERTA,B.MAX_PESERTA,B.TERISI,B.KD_HARI,B.HARI,B.KD_RUANG,B.KETERANGAN,B.JAM_MULAI,B.NO_RUANG,B.SEMESTER_PAKET,B.NM_PRODI,B.JAM_SELESAI,B.NM_MK,B.NM_MK_SINGKAT,B.JENIS_MK,B.NM_JENIS_MK,B.NM_DOSEN,B.NIP,B.JADWAL1,B.JADWAL2,C.URUT 
									FROM SIA.D_DETAIL_KRS A, SIA.V_KELAS B, SIA.D_URUT_KELAS C , SIA.D_KRS D, SIA.D_TA E, SIA.D_SEMESTER F, SIA.D_MAHASISWA G
									WHERE A.KD_KELAS = B.KD_KELAS AND A.KD_KRS = C.KD_KRS (+) AND A.KD_KELAS = C.KD_KELAS (+) AND D.NIM = '10651025' AND D.KD_KRS = A.KD_KRS AND 
									D.SEMESTER = '7' AND F.KD_SMT = D.KD_SMT AND 
									E.KD_TA = '2012' AND 
									G.NIM = '10651025' AND G.STATUS = 'A'")->result_array();  */
		//$query = $this->db->query("SELECT * FROM SIA.D_URUT_KELAS WHERE ROWNUM <3")->result_array();
		$query = $this->db->query("SELECT B.NM_MK 
FROM SIA.D_DETAIL_KRS A, SIA.V_KELAS B WHERE A.KD_KRS = (SELECT C.KD_KRS FROM SIA.D_KRS C WHERE C.NIM = '10651025' AND C.KD_TA = '2012' AND C.KD_SMT = '2') AND A.KD_KELAS = B.KD_KELAS")->result_array();
		$this->sia_api_lib_format->output($query, $format);		
	}	
	
	function gettampiltahun($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_gettampiltahun($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function getsoalkuesioneraktif($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_getsoalkuesioneraktif(); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function gettampilsoal($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_gettampilsoal($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function inserthasilkuesioner($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_inserthasilkuesioner($api_search[0]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function getlihatnilai($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_getlihatnilai($api_search[0],$api_search[1],$api_search[2]); 
				break; 
			} 
		break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function gettahunsmthasilikd($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				default: 
					case 1: $query = $this->mdl_1000->mdl_ikd_gettahunhasilikd($api_search[0]); break; 
					case 2: $query = $this->mdl_1000->mdl_ikd_getsmthasilikd($api_search[0]); break; 
			} 
			break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
	function kepuasan($format = 'json'){
		$kode 			= (int)preg_replace("/[^0-9]/", "", $_POST['api_kode']);
		$subkode 		= (int)preg_replace("/[^0-9]/", "", $_POST['api_subkode']);			
		$api_search 	= $this->input->post('api_search');		
		switch($kode){
			case 1000: switch($subkode){
				case 1: $query = $this->mdl_1000->get_kategori_kepuasan($api_search[0],$api_search[1]); break; 
				case 2: $query = $this->mdl_1000->get_pertanyaan_per_kat($api_search[0],$api_search[1],$api_search[2]); break; 
				case 3: $query = $this->mdl_1000->get_jawaban_mhs_tasmtnim($api_search[0],$api_search[1],$api_search[2]); break; 
				case 4: $query = $this->mdl_1000->get_puas_point(); break; 
				case 5: $query = $this->mdl_1000->get_max_puas_point($api_search[0],$api_search[1]); break; 
				case 6: $query = $this->mdl_1000->cek_jawab_pertanyaan_kepuasan($api_search[0],$api_search[1],$api_search[2],$api_search[3]); break; 
				case 101: $query = $this->mdl_1000->jawab_pertanyaan_kepuasan($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); break; 
				case 201: $query = $this->mdl_1000->jawab_pertanyaan_kepuasan_baru($api_search[0],$api_search[1],$api_search[2],$api_search[3],$api_search[4],$api_search[5]); break; 
				default: $query = array(); break;
			} 
			break;		
		}
		$this->sia_api_lib_format->output($query, $format);		
	}
	
}
?>