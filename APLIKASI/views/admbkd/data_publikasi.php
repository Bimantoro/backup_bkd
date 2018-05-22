<div id="content">
<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Kompilasi</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/publikasi/'.$this->uri->segment(5);?>">Publikasi</a></li>
	</ul>

	<!-- form semester -->
		<?php $action = $this->uri->segment(5); ?>
		<br/><?php echo form_open();?>
			<input type="hidden" name="kode" value="<?php echo $this->uri->segment(4);?>">
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
						</select>
					</td>
				</tr>
				<tr>
					<th>Semester</th>
					<td>: 
						<select name="smt">
							<?php if($this->session->userdata('smt') == 'GANJIL'){ ?>
							<option value="GANJIL" selected>SEMESTER GANJIL</option>
							<option value="GENAP">SEMESTER GENAP</option>
							<?php }else{?>
							<option value="GANJIL">SEMESTER GANJIL</option>
							<option value="GENAP" selected>SEMESTER GENAP</option>
							<?php }?>							
						</select>
						<input type="submit" name="submit" value="Lihat Data Publikasi" class="btn btn-uin btn-inverse btn-small">
					</td>
				</tr>
			</table>
		<?php echo form_close();?>

	
	<h2>Publikasi Dosen</h2>
	<table class="table">
	<tr><th width="200">Tahun Akademik</th><td>: <?php echo $ta;?></td></tr>
	<tr><th>Semester</th><td>: <?php echo $smt;?></td></tr>
	</table>
	<table class="table table-bordered table-hover">
	<tr>
		<th width="20">No.</th>
		<th width="70">NIP</th>
		<th width="270" colspan="2">Uraian Publikasi</th>
	</tr>
	<?php if(!empty($publikasi)){ 
		$no=0; foreach ($publikasi as $data){ $no++; 
		?>
	<tr>
		<td align="center"><?php echo $no;?>.</td>
		<td><?php echo $data->KD_DOSEN;?></td>
		<td>
			<div>
			Dosen : <?php echo $nm_dosen['_'.$data->KD_DOSEN].'<br/>Penerbit : '.$data->PENERBIT;?></div>
			<b>Judul : </b> <?php echo $data->JUDUL_PUBLIKASI;?>
			<div class="tgl">
			<?php echo date('d/m/Y', strtotime($data->TANGGAL_PUB)).' Tingkat '.$data->TINGKAT; ?>
			</div>
		</td>

	</tr>
	<?php }
	}else{	?>
	<tr><td colspan="5" align="center">BELUM ADA DATA PUBLIKASI YANG DAPAT DITAMPILKAN</td></tr>
	<?php } ?>
	</table>
	
	<?php echo $links;?>
	
	<span class="btn-group pull-right">
		<a href="#" class="btn"><i class="icon-print"></i></a>
		<a target="_blank" href="<?php echo base_url().'bkd/admbkd/cetak/publikasi/'.substr($this->session->userdata('ta'),0,4).'/'.$this->session->userdata('smt');?>" class="btn">Semester</a>
		<a target="_blank" href="<?php echo base_url().'bkd/admbkd/cetak/publikasi/'.substr($this->session->userdata('ta'),0,4);?>" class="btn">Tahun Akademik</a>
		<a target="_blank" href="<?php echo base_url().'bkd/admbkd/cetak/publikasi';?>" class="btn">Semua</a>
	</span>
	
	
</div>
</div>
<style>
.tgl{ font-size:0.8em; color:#888; font-style:italic;}
</style>