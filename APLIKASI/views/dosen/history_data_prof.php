<script type="text/javascript">
	function hapus(msg){
		var conf = confirm(msg);
		if (conf == true) return true;
		else return false;
	}
</script>
<?php echo $this->s00_lib_output->output_info_dsn();?>
<div id="content">
<div>
	<!-- form -->
    <div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/dosen/biodata/';?>">Beban Kerja Dosen</a>
			<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/profesor';?>">Khusus Profesor</a>
		</ul><div style="clear:both"></div>
	</div>

		<?php $this->load->view('dosen/form_history_prof');?>

	<h2>Data Beban Kerja Profesor</h2> <h3>Tahun Akademik <?php echo $ta;?>, Semester <?php echo $smt;?></h3>
	<!-- tampilkan beban kerja yang telah diambil -->
	<table border="0" cellspacing="0" class="table table-bordered table-hover" width="100%">
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">Jenis Kegiatan</th>
			<th colspan="2">Beban Kerja</th>
			<th rowspan="2">Masa Penugasan</th>
			<th colspan="2">Kinerja</th>
			<th rowspan="2">Rekomendasi</th>
		</tr>
		<tr>
			<th>Bukti Penugasan</th>
			<th>SKS</th>
			<th>Bukti Dokumen</th>
			<th>SKS</th>
		</tr>
		<?php 
			if (empty($data_beban_prof)){
					echo '<tr><td colspan="9" align="center">BELUM ADA DATA YANG DAPAT DITAMPILKAN</td></tr>';
			}else{
				$no=0; $sks_beban = 0; $sks_bukti = 0;
				foreach ($data_beban_prof as $bp){ $no++; 
					$sks_beban = $sks_beban+$bp->SKS_PENUGASAN; 
					$sks_bukti = $sks_bukti+$bp->SKS_BKT;
				?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $bp->JENIS_KEGIATAN;?></td>
					<td><?php echo $bp->BKT_PENUGASAN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_PENUGASAN;?></td>
					<td><?php echo $bp->MASA_PENUGASAN;?></td>
					<td><?php echo $bp->BKT_DOKUMEN;?></td>
					<td align="center"><?php echo (float)$bp->SKS_BKT;?></td>
					<td><?php echo $bp->REKOMENDASI;?></td>
				</tr>
			<?php } ?>
			<style>.total{font-weight:bold;}</style>
			<tr class="total">
				<td></td>
				<td colspan="2">Jumlah Beban Kerja</td>
				<td align="center"><?php echo $sks_beban; ?></td>
				<td colspan="2">Jumlah Kinerja</td>
				<td align="center"><?php echo $sks_bukti; ?></td>
				<td colspan="5"></td>
			</tr>
		<?php } ?>
	</table>
</div>
</div>