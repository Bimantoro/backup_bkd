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

	
	<h2>Data Narasumber/Pembicara Dosen</h2>
	<table class="table">
	<tr><th width="200">Tahun Akademik</th><td>: <?php echo $ta;?></td></tr>
	<tr><th>Semester</th><td>: <?php echo $smt;?></td></tr>
	</table>
	<table class="table table-bordered table-hover">
	<tr>
		<th width="20">No.</th>
		<th width="70">NIP</th>
		<th width="270" colspan="2">Uraian Kegiatan</th>
	</tr>
	<?php if(!empty($narasumber)){ 
		$no=0; foreach ($narasumber as $data){ $no++; 
		?>
	<tr>
		<td align="center"><?php echo $no;?>.</td>
		<td><?php echo $data->KD_DOSEN;?></td>
		<td>
			<div>
			Dosen : <?php echo $nm_dosen['_'.$data->KD_DOSEN];?></div>
			<b>Judul Acara :</b> <?php echo $data->JUDUL_ACARA;?>
			Lokasi Acara : <?php echo $data->LOKASI_ACARA;?><br/>
			Tanggal Mulai : <?php echo date('d/m/Y', strtotime($data->BT_MULAI));?><br/>
			Tanggal Selesai : <?php echo date('d/m/Y', strtotime($data->BT_SELESAI));?><br/>
			<div class="tgl">
			Laman Publikasi : <a href="<?php echo $data->LAMAN_PUBLIKASI;?>" target="_blank"><?php echo $data->LAMAN_PUBLIKASI;?></a>
			</div>
		</td>
	</tr>
	<?php }
	}else{	?>
	<tr><td colspan="5" align="center">BELUM ADA DATA PUBLIKASI YANG DAPAT DITAMPILKAN</td></tr>
	<?php } ?>
	</table>
	
	<?php echo $links;?>
		
</div>
</div>
<style>
.tgl{ font-size:0.8em; color:#888; font-style:italic;}
</style>