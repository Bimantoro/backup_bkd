<div id="content">
	<div>
	
	<!-- kompilasi lainnya -->
	<ul id="hover-menu">
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/fakultas';?>">Kompilasi Fakultas</a></li>
		<li><a id = "df" href="<?php echo base_url().'bkd/admbkd/kompilasi/fakultas';?>">Download Kompilasi Fakultas</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi';?>">Kompilasi Universitas</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi';?>">Download Kompilasi Universitas</a></li>
	</ul>
	<h2>Kompilasi Beban Kerja Dosen <br/>Tahun Akademik <?PHP echo $this->session->userdata('ta');?></h2>
	
	<!-- form pencarian -->
	<?php echo form_open('bkd/admbkd/kompilasi/search');?>
	<table class="table">
		<tr><td><input type="text" name="cari" size="50" class="input-xlarge" placeholder="Cari dosen"></td></tr>
	</table>
	<?php echo form_close();?>
	<!-- end -->
	
	<table border="0" class="table table-bordered" cellspacing="0" width="100%">
		<tr>
			<th>No</th>
			<th>No Sertifikat</th>
			<th>Nama Dosen</th>
			<th>Status</th>
			<th>Kesimpulan</th>
		</tr>
		<?php if (empty($dosen)){ ?>
		<tr><td colspan="5">Beban Kerja masih kosong pada tahun ajaran <?php echo $this->session->userdata("ta");?></td></tr>
		<?php } else { $no=0; $index=0; foreach ($dosen as $data) { $no++; ?>
		<tr>
			<td><?php echo $no;?></td>
			<td><?php if ($data->NO_SERTIFIKAT == "") echo $data->NIP; else echo $data->NO_SERTIFIKAT;?></td>
			<td><a class="link" href="<?php echo base_url().'bkd/admbkd/kompilasi/detail/'.$this->security->xss_clean($data->KD_DOSEN);?>"><?php echo ucwords(strtolower($data->NM_DOSEN));?></a></td>
			<td align="center"><?php echo $data->KD_JENIS_DOSEN;?></td>
			<td align="center"></td>
		</tr>
		<?php } 
		} ?>	
	</table>
	</div>
</div>