<?php echo $this->s00_lib_output->output_info_dsn();?>
<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/narasumber';?>">Narasumber Kegiatan</a></li>
</ul>

<?php $this->load->view('dosen/form_history');?>

<h2>Data Narasumber/Pembicara</h2>
<h3>Tahun Akademik : <?php echo $this->session->userdata('ta');?>, Semester : <?php echo $this->session->userdata('smt');?></h3>
<table class="table table-bordered table-hover">
<tr>
	<th>No.</th>
	<th>Judul Acara</th>
	<th>Tgl. Mulai</th>
	<th>Tgl. Selesai</th>
	<th colspan="2">Aksi</th>
</tr>
<?php if(!empty($narasumber)){ $no=0; foreach ($narasumber as $data){ $no++; ?>
<tr>
	<td align="center"><?php echo $no;?>.</td>
	<td><?php echo $data['JUDUL_ACARA'];?></td>
	<td align="center"><?php echo $data['TGL_MULAI'];?></td>
	<td align="center"><?php echo $data['TGL_SELESAI'];?></td>
	<th align="center"><a href="<?php echo base_url().'bkd/dosen/bebankerja/narasumber/edit/'.$data['KD_NP'];?>" class="btn btn-small"><i class="icon icon-edit"></i> Edit</a></th>
	<th align="center"><a onclick="return hapus('Apakah Anda ingin menghapus data ini?')" href="<?php echo base_url().'bkd/dosen/bebankerja/narasumber/delete/'.$data['KD_NP'];?>" class="btn btn-small"><i class="icon icon-trash"></i> Hapus</a></th>
</tr>
<?php }}else{?>
<tr><td colspan="6" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>
<?php } ?>
</table>
<a href="<?php echo base_url().'bkd/dosen/bebankerja/narasumber/tambah';?>" class="btn btn-uin btn-small btn-inverse">Tambah</a>
<script>
function hapus(msg){
	var conf = confirm(msg);
	if(conf == true) return true;
	else return false;
}
</script>