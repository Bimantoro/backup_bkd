<?php echo $this->s00_lib_output->output_info_dsn();?>
<?php $this->load->view('fungsi');?>
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/remun/kesimpulan';?>">BKD Remun</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/remun/kesimpulan';?>">Kesimpulan</a></li>
</ul>
<br/>
<?php $this->load->view('dosen/form_history_remun');?>		
<div id="content">
<h2>BKD Remun - <?php echo ucwords($title);?></h2>
<?php
	if(!empty($dosen)){
		foreach ($dosen as $data) {?>
			<table border="0" width="80%" class="table">
			<tr>
				<td>NIP / No.Sertifikat</td>
				<td>: <?php	echo _generate_nip($data->KD_DOSEN)." / ".$noser; ?></td>
			</tr>
			<tr>
				<td>Nama Lengkap</td>
				<td>: <?php echo $namaLengkap[0]->NM_PGW_F; ?></td>
			</tr>
			<tr>
				<td>Status</td>
				<td>: <?php echo "(".$this->session->userdata('jenis_dosen').") ".$nama_jenis_dosen; ?></td>
			</tr>
			<tr>
				<td>Tahun Akademik</td>
				<th align="left">: <?php echo $ta." - ".$smt;?></th>
			</tr>
		</table>
		<br/>
		<?php
			/*echo "<pre>";
			print_r($summary);
			echo "<pre>";*/
		?>
		<?php
		//cek ada data terlebih dahulu, ada atau tidak
			if(!empty($summary)){?>
				<!-- jika ada data -->
				<table border="0" class="table table-bordered table-hover" width="">
			<tr>
				<th align="center">No.</th>
				<th colspan="2">Keterangan</th>
				<th>Syarat</th>
				<th>Kinerja</th>
				<th>Beban Lebih</th>
				<th>Poin SKR</th>
				<th>Kesimpulan</th>
			</tr>
			<?php $no=1;?>
			<?php foreach ($summary as $smy): ?>
				<?php $index 	= 0; ?>
				<?php $panjang 	= count($smy); ?>
				<?php foreach ($smy as $key): ?>
					<?php if($index == 0){ $index++; ?>
						<tr>
							<td rowspan="<?php echo $panjang; ?>" align="center"><?php echo $no;?>.</td>
							<td rowspan="<?php echo $panjang; ?>" align="center"><?php echo ucwords(strtolower($key['GROUP'])); ?></td>
							<td align=""><?php echo $key['JUDUL']?></td>
							<td></td>
							<td align="center"></td>
							<td align="center"><?php echo $key['NILAI']?></td>
							<td align="center"><?php echo $key['POIN']?></td>
							<td align="center"></td>
						</tr>
					<?php }else{ $index++; ?>
						<tr>
							<td align=""><?php echo $key['JUDUL']?></td>
							<td></td>
							<td align="center"></td>
							<td align="center"><?php echo $key['NILAI']?></td>
							<td align="center"><?php echo $key['POIN']?></td>
							<td align="center"></td>
						</tr>
					<?php }  ?>
				<?php  endforeach ?>
				<?php $no++;?>
			<?php  endforeach ?>

			 <tr>
				<td align="center" colspan="6">Jumlah Kinerja Selama Periode Penilaian</td>
				<td align="center"><?php echo $kesimpulan['JML_POIN']?></td>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="center" colspan="6">Rata-rata Kinerja Per Bulan</td>
				<td align="center"><?php echo number_format($kesimpulan['RATA_POIN'], 2);?></td>
				<td align="center"></td>
			</tr>
			<tr>
				<td align="center" colspan="6">Nilai Kinerja Akademik (Komponen SKR)</td>
				<td align="center"><?php echo $kesimpulan['NILAI_KINERJA']."%";?></td>
				<td align="center"></td>
			</tr>
		</table>
			<?php }else{?>
				<!-- jika tidak ada data -->
				<?php echo "<h4>Belum ada data yang dapat ditampilkan!</h4>";?>
			<?php }
		?>
		
		<?php }
	}
?>		
</div>
