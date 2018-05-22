<div id="content">
	<div>
		<?php 
		$text = 'Sistem Kinerja Dosen <span style="">SEMESTER '.$smt.'</span>, Tahun Akademik <span style="">'.$ta.'</span>';
		$arr1 = array(	'app_text' 	=> $text, 
						'app_name' 	=> 'Asesor Kinerja Dosen', 
						'app_url'	=> 'verifikator/kinerja');

		$this->s00_lib_output->output_frontpage_dsn($arr1); ?>
	</div>
</div>