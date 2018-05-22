<script type="text/javascript">
$(document).ready(function(){
	$('.print').click(function(){
		$('#bg').fadeIn('fast');
		$('#loading').fadeIn('fast');
	});
});
</script>
<style>
	#bg{ 
		background-color:#555; 
		opacity:0.5;
		position:fixed;
		width:100%; height:100%;
		top:0; left:0;
		z-index:5;
		display:none;
	}
	#loading{
		position:fixed;
		width:20%;
		left:40%;
		top:20%;
		font-weight:bold;
		text-align:center;
		display:none;
		background-color:#FFF;
		border:solid 1px #AAA;
		padding:30px;
		z-index:10;
	}
</style>
<div id="bg"></div>
<div id="loading">Harap Menunggu...</div>
<div id="content">
	<div>
	
	<!-- kompilasi lainnya -->
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Kompilasi</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/semester/'.$this->uri->segment(5);?>">Kompilasi Semester</a></li>
	</ul>
	
	<?php $this->load->view('admbkd/form_semester');?>
	
	<h2>Kompilasi Beban Kerja Dosen</h2>
	<table class="table table-hover">
	<?php if($this->session->userdata('adm_univ') != ''){?>
	<tr><th>Perguruan Tinggi</th><th>:</th><td><?php echo $dept;?></td></tr>
	<?php }?>
	<?php if($this->session->userdata('adm_fak') != ''){?>
	<tr><th>Fakultas</th><th>:</th><td><?php echo $dept;?></td></tr>
	<?php } ?>
	<?php if($this->session->userdata('adm_pro') != ''){?>
	<tr><th>Program Studi</th><th>:</th><td><?php echo $dept;?></td></tr>
	<?php } ?>
	<tr><th>Semester</th><th>:</th><td><?php echo $smt.'-'.$ta;?></td></tr>
	</table>

	<?php if($this->uri->segment(5) == null){$hal = 1;}else{$hal = $this->uri->segment(5);}?>
	<table border="0" class="table table-bordered">
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">No Sertifikat</th>
			<th rowspan="2">Nama Dosen</th>
			<th colspan = "4">Semester <?php echo ucwords($smt);?></th>
			<th rowspan="2">Kewajiban<br/>Khusus<br/>Profesor</th>
			<th rowspan="2">Status</th>
			<th rowspan="2">Kesimpulan</th>
		</tr>
		<tr>
			<th>Pd</th>
			<th>Pl</th>
			<th>Pg</th>
			<th>Pk</th>
		</tr>
		<?php if (empty($kompilasi)){ ?>
		<tr><td colspan="10" align="center">BEBAN KERJA MASIH KOSONG PADA TAHUN AJARAN <?php echo $ta;?></td></tr>
		<?php } else { $no=0; foreach ($kompilasi as $data) {
			if($data->NO_SERTIFIKAT == '') $noser = '-'; else $noser = $data->NO_SERTIFIKAT;
			#if(in_array($data->KD_DOSEN, $dosen_tetap)){
				 $no++; 
		?>
		<tr>
			<td align="center"><?php echo $no;?>.</td>
			<td><?php echo $noser;?></td>
			<td><a class="link" href="<?php echo base_url().'bkd/admbkd/kompilasi/detail/'.$this->security->xss_clean($data->KD_DOSEN);?>"><?php echo $namaDosen['_'.$data->KD_DOSEN];?></a></td>
			<?php if($smt == 'GANJIL'){ 
				$tot_ganjil = $data->PD1 + $data->PL1 + $data->PG1 + $data->PK1;
				if($tot_ganjil >= 12 && $tot_ganjil <= 16){ $kesimpulan = '<span class="badge badge-success">M</span>'; } else {$kesimpulan = '<span class="badge badge-important">T</span>'; } ?>
				<td align="center"><?php echo (float)$data->PD1;?></td>
				<td align="center"><?php echo (float)$data->PL1;?></td>
				<td align="center"><?php echo (float)$data->PG1;?></td>
				<td align="center"><?php echo (float)$data->PK1;?></td>
				<td align="center"><?php echo (float)$data->PR1;?></td>
				<td align="center" bgcolor="#FFFFDD"><?php echo $ds['_'.$data->KD_DOSEN];?></td>
				<td align="center"><?php echo $kesimpulan;?></td>
			<?php }else{ 
				$tot_genap = $data->PD2 + $data->PL2 + $data->PG2 + $data->PK2;
				if($tot_genap >= 12 && $tot_genap <= 16){ $kesimpulan = '<span class="badge badge-success">M</span>'; }
				else {$kesimpulan = '<span class="badge badge-important">T</span>'; } ?>
				<td align="center"><?php echo (float)$data->PD2;?></td>
				<td align="center"><?php echo (float)$data->PL2;?></td>
				<td align="center"><?php echo (float)$data->PG2;?></td>
				<td align="center"><?php echo (float)$data->PK2;?></td>
				<td align="center"><?php echo (float)$data->PR2;?></td>
				<td align="center" bgcolor="#FFFFDD"><?php echo $ds['_'.$data->KD_DOSEN];?></td>
				<td align="center"><?php echo $kesimpulan;?></td>
			<?php } ?>
		</tr>
		<?php } 
		} ?>	
	</table>
	</div>
	<?php 
		if ($this->session->userdata('adm_univ') != ''){ 
			$action = '';
			$action2 = '';
		}else{
			if($this->session->userdata('adm_fak') != ''){
				$action = '_fakultas';
				$action2 = 'fakultas';
			}else{
				if($this->session->userdata('adm_pro') != ''){
					$action = '_prodi';
					$action2 = 'prodi';
				}
			}
		}
	?>
	<?php echo $links;?>
	<span class="btn-group pull-right">
		<a href="<?php echo base_url().'bkd/admbkd/cetak/semester/'.$action2;?>" class="btn print"><i class="icon icon-print"></i> Cetak Kompilasi</a>
		<a href="<?php echo base_url().'bkd/admbkd/cetak/semester_export_excel'.$action;?>" class="btn"><i class="icon icon-share"></i> Export to Excel</a>
	</span>
</div>
<style>
	a.link{text-decoration:underline; color:black;} 
	a {text-decoration:underline; color:black;} 
</style>
<?php #echo $this->session->userdata('jabatan');?>
