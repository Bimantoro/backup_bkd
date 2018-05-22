<?php
if (!defined ('BASEPATH')) exit ('Direct access not allowed...');

class Spy extends CI_Controller{

	function __construct(){
	
		$ip = $_SERVER['REMOTE_ADDR'];
		$mac = $_SERVER['HTTP_X_FORWARDED_FOR'];
		
		$spy = array(
			'ip' => $ip,
			'mac'	=> $mac
		);
		
		print_r($spy);
	
	}
	
	function index(){
	
	}
	
	function test(){
        
		
	}
}
?>
