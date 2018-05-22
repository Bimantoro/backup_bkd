<script>
	function hapus(msg){
		var conf = confirm(msg);
		if (conf == true) return true;
		else return false;
	}
</script>
<div id="content">
<div>
	<!-- menu atas -->
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Master</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/asesor/data';?>">Daftar Asesor</a></li>
	</ul>
	
	<h2>Daftar Asesor</h2>
	<?php echo form_open();?>
	<table cellspacing="10">
		<tr>
			<td><input type="text" name = "keyword" class="input-xlarge" placeholder="Masukkan nama asesor/asal PT"></td>
			<td>&nbsp;&nbsp;<input type="submit" class="btn btn-uin btn-inverse" value="Cari Asesor" style="margin-top:-10px"></td>
		</tr>
	</table>
	<?php echo form_close();?>
	
	<table border="0" cellspacing="0" class="table table-bordered" width="100%">
		<tr>
			<th>No.</th>
			<th>NIRA</th>
			<th>KD Dosen/NIP</th>
			<th>Nama</th>
			<th>Telp</th>
			<th colspan="2">Action</th>
		</tr>
	<?php
		if (empty($asesor)){
			echo "<td colspan='7' align='center'>TIDAK ADA YANG DAPAT DITAMPILKAN</th>";
		}else{	
			if(empty($this->uri->segment(5))) $no=0; 
			else $no = $this->uri->segment(5);
			foreach ($asesor as $as){ $no++; ?>
		<tr>
			<td align="center"><?php echo $no;?>.</td>
			<td><a class="link" href="<?php echo base_url().'bkd/admbkd/asesor/detail/'.$as->NIRA;?>"><?php echo $as->NIRA;?></a></td>
			<td><?php echo $as->KD_DOSEN;?></td>
			<td><?php echo $as->NM_ASESOR;?></td>
			<td><?php echo $as->TELP;?></td>
			<td><a class="btn btn-mini" href="<?php echo base_url().'bkd/admbkd/asesor/register/'.$as->NIRA;?>"><i class="icon-edit"></i></a></td>
			<td><a class="btn btn-mini" onclick="return hapus('Anda yakin akan menghapus data asesor ini?')" href="<?php echo base_url().'bkd/admbkd/asesor/delete_asesor/'.$as->NIRA;?>">
			<i class="icon-trash"></i></a></td>
		</tr>
	<?php }
	} ?>
	</table>
	<span class="pull-left">
		<?php echo $links;?>
	</span>
	<span class="pull-right">
		<a class="btn btn-inverse btn-uin btn-small" href="<?php echo base_url().'bkd/admbkd/asesor/register';?>">Tambah Asesor</a>
	</span>
</div>
</div>