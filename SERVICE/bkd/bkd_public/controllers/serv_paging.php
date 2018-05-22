<?php defined('BASEPATH') OR exit('No direct script access allowed');

//BUAT SABBANA 25-11-2013
 
class Serv_paging extends CI_Controller
{
	protected $builtInMethods;
 
	public function __construct(){
		parent::__construct();
	
	}
 
	function index(){ echo '<h1>Z000 BKD SERV</h1>'; }
	
	
	
	//SQL -- LETAKNYA DI SERVICE
	
	function q20000_sql_limit($sql = '', $start = 1, $offset = false){
		//equivalent in mySQL: select * from x limit $start, $offset 
		
		if($offset != false){
			//$start += $offset-1;
		}
		
		$sql1 = "SELECT * FROM (select IQRY.*, rownum IQR_NUM FROM (".$sql.") IQRY WHERE rownum <= ".$start.")";
		
		if($offset != false){
			$sql1 .= " WHERE IQR_NUM >= ".$offset;
		}
		
		return $sql1;
	}
 
	function p10000_get_total(){
		$query = $this->db->query("SELECT COUNT(*) AS TOTALE FROM BKD.BKD_DOSEN A LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN")->result_array();
		return json_encode($query);
		
		#$this->sia_api_lib_format->output($query, 'json');
	}
	
	function p20000_get_limit($start = 1, $offset = false){
		$sql = $this->q20000_sql_limit("SELECT A.KD_DOSEN, A.KD_PRODI, A.KD_FAK, A.NO_SERTIFIKAT, B.NIP, B.NM_DOSEN FROM BKD.BKD_DOSEN A LEFT JOIN SIA.D_DOSEN B ON A.KD_DOSEN = B.KD_DOSEN ORDER BY B.NM_DOSEN",$start, $offset);
		
		$query = $this->db->query($sql)->result_array();
		return json_encode($query);
		
		#$this->sia_api_lib_format->output($query, 'json');
	}
	
	
	
	
	
	
	
	//PAGINATION	--> letaknya sbenernya si di controller:
	
	function r10000_pagination_config($link, $total, $hal, $ovr = array()){
		$config = array();
		$config['total_rows'] = $total;
		$config['cur_page'] = (int)preg_replace("/[^0-9]/", "", $hal);
		
		if(!array_key_exists('base_url',$ovr)) { $config['base_url'] = site_url('archive/'.$link.'/'); }
		else { $config['base_url'] = site_url($ovr['base_url'].$link); }
		if(!array_key_exists('per_page',$ovr)) { $config['per_page'] = 5; } else { $config['per_page'] = $ovr['per_page']; }
		if(!array_key_exists('uri_segment',$ovr)) { $config['uri_segment'] = 3; } else { $config['uri_segment'] = $ovr['uri_segment']; }
		if(!array_key_exists('prefix',$ovr)) { $config['prefix'] = 'page'; } else { $config['prefix'] = $ovr['prefix']; }
		if(!array_key_exists('suffix',$ovr)) { $config['suffix'] = '.html'; } else { $config['suffix'] = $ovr['suffix']; }
		if(!array_key_exists('first_url',$ovr)) { $config['first_url'] = 'page0.html'; } else { $config['first_url'] = $ovr['first_url']; }
		if(!array_key_exists('use_page_numbers',$ovr)) { $config['use_page_numbers'] = true; } else { $config['use_page_numbers'] = $ovr['use_page_numbers']; }
		
		return $config;
	}
	
	public function test($hal = 1){
		$data = array();
		
		//init library
		$this->load->library('pagination');
								
		//get total entry of sql
		$api1 			= json_decode($this->p10000_get_total(), true); #print_r($api1);
		$data['totale'] = intval($api1[0]['TOTALE']);
		
		//set common pagination config
		$cust_config	= array('base_url' => '', 'prefix' => '', 'suffix' => '', 'first_url' => 1);
		$create_config 	= $this->r10000_pagination_config('z000_bkd_serv/y1000_test',$data['totale'], $hal, $cust_config); 
		
		//calculate total number of page
		$data['totpage'] 	= ceil($data['totale']/$create_config['per_page']);
		
		//get current entry of page
		//echo $create_config['cur_page'].'_'.$create_config['per_page'];
		$lim_start		= $create_config['cur_page']*$create_config['per_page'];
		$lim_offset		= ($create_config['cur_page']-1)*$create_config['per_page']+1;
		
		$data['artikel'] 	= json_decode($this->p20000_get_limit($lim_start, $lim_offset), true);
			
		//init pagination
		$this->pagination->initialize($create_config); 
		$data['pagination'] = $this->pagination->create_links();
		
		echo '<h2>data dosen untuk halaman ke-'.$hal.':</h2><br>';
		echo '<pre>'; print_r($data['artikel']);
		echo '</pre>';
		
		echo '<b>navigasi halaman:</b><br>';
		echo $data['pagination'];
	}
	
}
?>