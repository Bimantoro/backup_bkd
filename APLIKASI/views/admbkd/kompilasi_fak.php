<div id="content">
	<div>
	
	<!-- kompilasi lainnya -->
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Beban Kerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/fakultas';?>">Kompilasi</a></li>
	</ul>
	<h2>Kompilasi Beban Kerja Dosen <br/>Tahun Akademik <?PHP echo $this->session->userdata('ta');?></h2>
		
	<table border="0" class="table table-bordered" cellspacing="0" width="100%">
		<tr>
			<th>No</th>
			<th>NIP</th>
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
			<td><a class="link" href="<?php echo base_url().'bkd/admbkd/kompilasi/detail/'.$this->security->xss_clean($data->KD_DOSEN);?>"><?php echo $data->NIP;?></a></td>
			<td><?php if ($data->NO_SERTIFIKAT == "") echo $data->NIP; else echo $data->NO_SERTIFIKAT;?></td>
			<td><?php echo ucwords(strtolower($data->NM_DOSEN));?></td>
			<td align="center"><?php echo $data->KD_JENIS_DOSEN;?></td>
			<td align="center"></td>
		</tr>
		<?php } 
		} ?>	
	</table>	

	<div class="page"><?php echo $page;?></div>

	</div>
</div>

<!-- download fakultas -->
<div id="modal-fakultas">
<?php echo form_open();?>
<table class="table table-condensed">
<tr>
	<td>Pilih Fakultas</td>
	<td>
		<select name="kd_fak">
		<?php foreach ($fakultas as $fak){ ?>
			<option value="<?php echo $fak->KD_FAK;?>"><?php echo $fak->KD_FAK;?></option>
		<?php } ?>
		</select>
	</td>
	<td><input type="submit" name="submit" value="Cetak" class="btn-inverse btn-uin"></td>
</tr>
</table>
<?php echo form_close();?>
</div>