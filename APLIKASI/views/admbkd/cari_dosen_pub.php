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
<div id="content">
<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Beban Kerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/publikasi_dosen/'.$this->uri->segment(5);?>">Publikasi Dosen</a></li>
	</ul>
	<br/>
	<?php echo form_open();?>
	<table class="table table-hover">
		<tr><td colspan="3" class="title-bar"><i class="icon icon-search"></i> Masukkan Keyword</td></tr>
		<tr class="search">
			<td>
				<select name="subkey" class="subkey">
					<option value="JUDUL_PUBLIKASI">Judul Publikasi</option>
					<option value="KD_DOSEN">NIP Dosen</option>
				</select>
			</td>
			<td><input type="text" name="keyword" class="input-xlarge" placeholder="Masukkan keyword" required></td>
			<td><input type="submit" class="btn btn-mini btn-inverse btn-uin" value="Cari"></td>
		</tr>
	</table>
	<?php echo form_close();?>
	
	<h2>Publikasi Dosen</h2>
	<table class="table table-bordered" cellpadding="1">
	<tr>
		<th width="20">No.</th>
		<th width="70">NIP</th>
		<th width="270">Uraian Publikasi</th>
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
			</div>.
		</td>
	</tr>
	<?php }
	}else{	?>
	<tr><td colspan="3" class="alert alert-error">Tidak ditemukan data publikasi dengan <?php echo $subkey;?> <b><?php echo $keyword;?></b></td></tr>
	<?php } ?>
	</table>
	</div>
</div>
