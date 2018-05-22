<?php echo $this->s00_lib_output->output_info_dsn();?>
<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/akademik';?>">Kegiatan Akademik</a></li>
</ul>

<h2>Data Kegiatan Akademik</h2>
<?php #print_r($kegiatan);?>
<table class="table table-bordered table-hover">
<tr>
	<th>No.</th>
	<th>Judul Kegiatan</th>
	<th>Tgl. Mulai</th>
	<th>Tgl. Selesai</th>
	<th colspan="2">Aksi</th>
</tr>
<?php if(!empty($kegiatan)){ $no=0; foreach ($kegiatan as $data){ $no++; ?>
<tr>
	<td align="center"><?php echo $no;?>.</td>
	<td><?php echo $data['JUDUL_KEGIATAN'];?></td>
	<td align="center"><?php echo $data['TGL_MULAI'];?></td>
	<td align="center"><?php echo $data['TGL_SELESAI'];?></td>
	<th align="center"><a href="<?php echo base_url().'bkd/dosen/bebankerja/akademik/edit/'.$data['KD_KA'];?>" class="btn btn-small"><i class="icon icon-edit"></i> Edit</a></th>
	<th align="center"><a onclick="return hapus('Apakah Anda ingin menghapus data ini?')" href="<?php echo base_url().'bkd/dosen/bebankerja/akademik/delete/'.$data['KD_KA'];?>" class="btn btn-small"><i class="icon icon-trash"></i> Hapus</a></th>
</tr>
<?php }}else{?>
<tr><td colspan="6" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>
<?php } ?>
</table>
<a href="<?php echo base_url().'bkd/dosen/bebankerja/akademik/tambah';?>" class="btn btn-uin btn-small btn-inverse">Tambah</a>
<script>
function hapus(msg){
	var conf = confirm(msg);
	if(conf == true) return true;
	else return false;
}
</script>