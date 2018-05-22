<?php echo $this->s00_lib_output->output_info_dsn();?>
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/remun/pendidikan';?>">BKD Remun</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/remun/pendidikan';?>">Pendidikan</a></li>
</ul>
<br/>
<?php echo form_open('bkd/dosen/remun/pendidikan/true');?>
	<table cellspacing="5" cellpadding="10" width="80%">
		<tr>
			<td>Tahun Akademik</td>
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
			<td>Semester</td>
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
				<input type="submit" name="submit" value="Lihat Data Remun" class="btn btn-uin btn-inverse btn-small">
			</td>
		</tr>
	</table>
<?php echo form_close();?>
			
<div id="content">
<h2>BKD Remun Bidang <?php echo ucwords($title);?></h2>
<h3>Tahun Akadamik : <?php echo $ta;?>, Semester : <?php echo $smt;?></h3>

		<table border="0" cellspacing="0" class="table table-bordered table-hover">
			<tr>
				<th rowspan="2">No</th>
				<th rowspan="2" width="180">Jenis Kegiatan</th>
				<th colspan="2">Beban Kerja</th>
				<th rowspan="2">Masa Penugasan</th>
				<th colspan="2">Kinerja</th>
				<th rowspan="2">Rekomendasi</th>
				<th colspan="2" rowspan="2" style="border-right:none">Aksi</th>
			</tr>
			<tr>
				<th>Bukti Penugasan</th>
				<th>SKS</th>
				<th>Bukti Dokumen</th>
				<th>SKS</th>
			</tr>
		</table>
		
</div>
<p><a class="btn btn-uin btn-inverse btn-small" href="<?php echo base_url().'bkd/dosen/remun/tambah/'?>">Tambah</a></p>