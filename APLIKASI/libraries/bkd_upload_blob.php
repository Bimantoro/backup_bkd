<?php 

class Bkd_upload_blob{
	

	function getBlob($table, $kolom, $kode){       
        $CI		=& get_instance();
        $data 	= $CI->s00_lib_api->get_api_json(
                    URL_API_BKD.'bkd_blob/dokumen',
                    'POST',
                    array(  'api_kode'      => 1500,
                            'api_subkode'   => 1,
                            'api_search'    => array($table, $kolom, $kode))
                    );
        return $data;
    }

    function insertBlob($table, $kolom, $arr, $where){       
        $CI =& get_instance();
        $data = $CI->s00_lib_api->get_api_json(
                    URL_API_BKD.'bkd_blob/dokumen',
                    'POST',
                    array(  'api_kode'      => 1500,
                            'api_subkode'   => 2,
                            'api_search'    => array($table, $kolom, $arr, $where))
                    );
        return $data;
    }

    function extensi($table, $kolom, $kode){       
        $CI =& get_instance();
        $data = $CI->s00_lib_api->get_api_json(
                    URL_API_BKD.'bkd_blob/file',
                    'POST',
                    array(  'api_kode'      => 1000,
                            'api_subkode'   => 1,
                            'api_search'    => array($table, $kolom, $kode))
                    );
        return $data;
    }

	
}