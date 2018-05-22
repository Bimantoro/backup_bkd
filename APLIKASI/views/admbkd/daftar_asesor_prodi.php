<style>
a.link{ color:#222; text-decoration:underline;}
.title-bar{ font-weight:bold; color:#555;}
.subkey{
	background-color:#EEE;
	color:#999;
	font-weight:normal;
	font-style:italic;
	padding:5px;
}
select option{ padding:8px; }
.search td{ border-bottom:solid 1px #DDD; }
</style>
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
		<li><a href="<?php echo base_url().'bkd/admbkd/asesor/prodi';?>">Asesor Program Studi</a></li>
	</ul>
	<pre><?php #print_r($this->session->all_userdata());?></pre>
	<!-- pilih prodi --><br/>
	<?php echo form_open();?>
	<table class="table table-hover">
		<tr><td colspan="2" class="title-bar"><i class="icon icon-search"></i> Pilih Program Studi</td></tr>
		<tr class="search">
			<td>
				<select name="kd_prodi" class="input-xlarge">
					<option value="">- PILIH PRODI -</option>
					<?php foreach($prodi as $p){ ?>
					<option value="<?php echo $p->KD_PRODI;?>">PRODI <?php echo $p->NM_PRODI;?></option>
					<?php } ?>
				</select>
			</td>
			<td><input type="submit" name="submit" class="btn btn-uin btn-inverse" value="Lihat"></td>
		</tr>
	</table>
	<?php echo form_close(); ?>
	
	<?php
		if (empty($asesor)){
			echo "<table class='table'><tr><td colspan='7'>Tidak ada data asesor prodi <b>".$nm_prodi."</b> yang dapat ditampilkan...</td></tr></table>";
		}else{?>
		<h3>Daftar Asesor Prodi : <?php echo ($nm_prodi) ? $nm_prodi : '-';?></h3>
		<table border="0" cellspacing="0" class="table table-bordered">
		<tr>
			<th>No</th>
			<th>NIRA</th>
			<th>KD Dosen/NIP</th>
			<th>Nama</th>
			<th>Telp</th>
			<th colspan="2">Aksi</th>
		</tr>
		<?php 
			if(empty($this->uri->segment(6))) $no=0; 
			else $no = $this->uri->segment(6);
			foreach ($asesor as $as){ $no++; ?>
		<tr>
			<td><?php echo $no;?></td>
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
	<p>
		<?php echo $links;?>
	</p>
</div>
</div>