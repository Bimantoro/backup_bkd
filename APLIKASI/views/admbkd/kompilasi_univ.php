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
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/'.$this->uri->segment(4);?>">Kompilasi Tahunan</a></li>
	</ul>
	
	<!-- select tahun -->
	<br/>
	<?php echo form_open();?>
	<table class="table">
		<tr>
			<th>Tahun Akademik</th>
			<td>:
				<select name="thn">
							<?php 
								$now = date('Y'); 
								for ($a=$now; $a>=$now-5; $a--){ 
									$b = $a+1; $tahun = $a.'/'.$b;
									if($tahun == $this->session->userdata('ta')){ $pilih = 'selected';}else {$pilih = '';}?>
									<option value="<?php echo $a.'/'.$b;?>" <?php echo $pilih;?>><?php echo $a.'/'.$b;?></option>
							<?php }?>
				<input type="submit" name="submit" value="Lihat Data Beban Kerja" class="btn btn-uin btn-inverse btn-small">
			</td>
		</tr>
	</table>
	<?php echo form_close();?>
	
	
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
	<tr><th>Tahun</th><th>:</th><td><?php echo $ta;?></td></tr>
	</table>
	<?php if($this->uri->segment(5) == null){$hal = 1;}else{$hal = $this->uri->segment(5);}?>
	<table border="0" class="table table-bordered">
		<tr>
			<th rowspan="2">No.</th>
			<th rowspan="2">No Sertifikat</th>
			<th rowspan="2">Nama Dosen</th>
			<th colspan = "4">Semester Gasal</th>
			<th colspan = "4">Semester Genap</th>
			<th rowspan="2">Kewajiban<br/>Khusus<br/>Profesor</th>
			<th rowspan="2">Status</th>
			<th rowspan="2">Kesimpulan</th>
		</tr>
		<tr>
			<th>Pd</th>
			<th>Pl</th>
			<th>Pg</th>
			<th>Pk</th>
			<th>Pd</th>
			<th>Pl</th>
			<th>Pg</th>
			<th>Pk</th>
		</tr>
		<?php if (empty($kompilasi)){ ?>
		<tr><td colspan="14" align="center">BEBAN KERJA MASIH KOSONG PADA TAHUN AJARAN <?php echo strtoupper($this->session->userdata("ta"));?></td></tr>
		<?php } else { 
			if(empty($this->uri->segment(5))) $no=0; 
			else $no = $this->uri->segment(5);
			foreach ($kompilasi as $data) { 
			if($data->NO_SERTIFIKAT == '') $noser = '-'; else $noser = $data->NO_SERTIFIKAT;
				$tot_ganjil = $data->PD1 + $data->PL1 + $data->PG1 + $data->PK1;
				$tot_genap = $data->PD2 + $data->PL2 + $data->PG2 + $data->PK2;
				$tot_tahun = $tot_ganjil + $tot_genap;
				if($tot_tahun >= 24 && $tot_tahun <= 32){ $kesimpulan = '<span class="badge badge-success">M</span>'; } else {$kesimpulan = '<span class="badge badge-important">T</span>'; }
			$no++;
		?>
		<tr>
			<td><?php echo $no;?>.</td>
			<td><?php echo $noser;?></td>
			<td><a class="link" href="<?php echo base_url().'bkd/admbkd/kompilasi/detail/'.$this->security->xss_clean($data->KD_DOSEN);?>"><?php echo $namaDosen['_'.$data->KD_DOSEN];?></a></td>
			<td align="center"><?php echo (float)$data->PD1;?></td>
			<td align="center"><?php echo (float)$data->PL1;?></td>
			<td align="center"><?php echo (float)$data->PG1;?></td>
			<td align="center"><?php echo (float)$data->PK1;?></td>
			<td align="center"><?php echo (float)$data->PD2;?></td>
			<td align="center"><?php echo (float)$data->PL2;?></td>
			<td align="center"><?php echo (float)$data->PG2;?></td>
			<td align="center"><?php echo (float)$data->PK2;?></td>
			<td align="center"><?php echo (float)$data->PR1+$data->PR2;?></td>
			<td align="center"><?php echo $data->KD_JENIS_DOSEN;?></td>
			<td align="center"><?php echo $kesimpulan;?></td>
		</tr>
		<?php }
		} ?>	
	</table>
	</div>
	<?php 
		if ($this->session->userdata('adm_univ') != ''){ 
			$link = base_url().'bkd/admbkd/cetak';
			$link_excel = base_url().'bkd/admbkd/cetak/export_excel';
		}else{
			if($this->session->userdata('adm_fak') != ''){
				$link = base_url().'bkd/admbkd/cetak/fakultas';
				$link_excel = base_url().'bkd/admbkd/cetak/export_excel_fakultas';
			}else{
				if($this->session->userdata('adm_pro') != ''){
					$link = base_url().'bkd/admbkd/cetak/prodi';
					$link_excel = base_url().'bkd/admbkd/cetak/export_excel_prodi';
				}
			}
		}
	?>
	<p>
		<?php echo $links;?>
	</p>
	<p class="btn-group">
		<a href="<?php echo $link;?>" class="btn print"><i class="icon icon-print"></i> Cetak Kompilasi</a>
		<a href="<?php echo $link_excel;?>" class="btn"><i class="icon icon-share"></i> Export to Excel</a>
	</p>
</div>
<style>
	a.link{text-decoration:underline; color:black;} 
	a {text-decoration:underline; color:black;} 
</style>