<div id="content">
	<div>
		<?php 
		$text = 'Kinerja Dosen, Tahun Akademik <span style="">'.$ta.'</span>';
		$arr1 = array(	'app_text' 	=> $text, 
						'app_name' 	=> 'Beban Kerja Dosen', 
						'app_url'	=> 'dosen');

		$this->s00_lib_output->output_frontpage_dsn($arr1); ?>
	</div>
</div>