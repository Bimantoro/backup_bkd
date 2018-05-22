<!-- kompilasi lainnya -->
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Kinerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Kompilasi</a></li>
	<li><a href="<?php echo base_url().'bkd/admbkd/kompilasi/akademik';?>">Kegiatan Akademik</a></li>
</ul>

<h2>Data Kegiatan Akademik</h2>
<?php #print_r($kegiatan);?>
<table class="table table-bordered table-hover">
<tr>
	<th>No.</th>
	<th>NIP</th>
	<th>Nama Dosen</th>
	<th>Judul Kegiatan</th>
	<th>Tgl. Mulai</th>
	<th>Tgl. Selesai</th>
</tr>
<?php if(!empty($kegiatan)){ $no=0; foreach ($kegiatan as $data){ $no++; ?>
<tr>
	<td align="center"><?php echo $no;?>.</td>
	<td><?php echo $data['KD_DOSEN'];?></td>
	<td><?php echo $namaDosen['_'.$data['KD_DOSEN']];?></td>
	<td><?php echo $data['JUDUL_KEGIATAN'];?></td>
	<td align="center"><?php echo $data['TGL_MULAI'];?></td>
	<td align="center"><?php echo $data['TGL_SELESAI'];?></td>
</tr>
<?php }}else{?>
<tr><td colspan="6" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>
<?php } ?>
</table>
	<span class="pull-left">
		<?php echo $links;?>
	</span>
<script>
function hapus(msg){
	var conf = confirm(msg);
	if(conf == true) return true;
	else return false;
}
</script>