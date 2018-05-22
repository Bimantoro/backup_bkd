<?php $this->load->view('fungsi');?>
<div id="content">
<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/verifikator/kinerja';?>">Asesor Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/verifikator/kinerja/dosen';?>">Rencana dan Kinerja</a></li>
		<li><a href="<?php echo base_url().'bkd/verifikator/kinerja/dosen/'.$this->uri->segment(5);?>">Data Dosen</a></li>
	</ul>
	<br/>
	<?php if($is_asesor == 0){?>
		<?php echo form_open();?>
		<table class="table table-hover">
			<!-- tr><td colspan="3" class="title-bar"><i class="icon icon-search"></i> Masukkan Keyword</td></tr -->
			<tr class="search">
				<td><input type="text" name="keyword" class="input-xxlarge" placeholder="Masukkan NIP atau Nama Dosen">
				<input type="submit" class="btn btn-mini btn-inverse btn-uin" value="Cari Dosen" style="margin-top:-10px;"></td>
			</tr>
		</table>
		<?php echo form_close();?>
	<?php }else{ $this->load->view('asesor/form_semester'); } ?>
	
	
	<table class="table table-hover table-bordered">
	<tr>
		<th width="30">No.</th>
		<th width="50">NIP</th>
		<th>Nama Dosen</th>
		<th>Program Studi</th>
		<th>Status</th>
		<th>Aksi</th>
	</tr>
	<?php if(!empty($dosen)){ $no=0; foreach ($dosen as $data){ $no++; ?>
	<tr>
		<td align="center"><?php echo $no;?>.</td>
		<td align="center"><?php echo $data['KD_DOSEN'];?></td>
		<td><?php echo $nm_dosen['_'.$data['KD_DOSEN']][0];?></td>
		<td><?php echo $nm_dosen['_'.$data['KD_DOSEN']][1];?></td>
		<td align="center" bgcolor="#FFFFDD"><?php echo $ds['_'.$data['KD_DOSEN']];?></td>
		<td align="center"><span class="btn-group">
			<a class="btn btn-small" href="<?php echo base_url().'bkd/verifikator/kinerja/daftar/'.$data['KD_DOSEN'];?>"><i class="icon-book"></i> BKD</a>
			<a class="btn btn-small" href="<?php echo base_url().'bkd/verifikator/kinerja/rencana/'.$data['KD_DOSEN'];?>"><i class="icon-list-alt"></i> RBKD</a>
		</span></td>
	</tr>
	<?php }}else{?>
	<tr><td colspan="6" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>
	<?php } ?>
	</table>
	
</div>
</div>
