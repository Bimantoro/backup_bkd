<?php echo $this->s00_lib_output->output_info_dsn();?>
<?php $this->load->view('fungsi');?>
<style>.total{font-weight:bold;}</style>
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Kinerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">BKD</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/kesimpulan';?>">Kesimpulan</a></li>
</ul>
<div id="content">
	<div>
	
	<?php $this->load->view('dosen/form_history');?>	
	
	<h2>Kesimpulan</h2>
	<?php 
	if(!empty($dosen)){
		foreach ($dosen as $data); ?>
		
		<!-- Data Dosen -->
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
		
		<!-- hasil kinerja -->
		<table border="0" class="table table-bordered table-hover" width="80%">
			<tr>
				<th>Keterangan</th>
				<th>Syarat</th>
				<th>Kinerja</th>
				<th>Beban Lebih</th>
				<th>Kesimpulan</th>
			</tr>
			<!-- table body -->
			<tr>
				<td>Pendidikan</td>
				<td align="center"><?php echo $syarat_pendidikan;?></td>
				<td align="center"><?php echo (float)$kinerja_pendidikan;?></td>
				<td align="center"><?php echo $bl_a;?></td>
				<td align="center"><?php echo $kesimpulan_pendidikan;?></td>
			</tr>
			<tr>
				<td>Penelitian</td>
				<td align="center"><?php echo $syarat_penelitian;?></td>
				<td align="center"><?php echo (float)$kinerja_penelitian;?></td>
				<td align="center"><?php echo $bl_b;?></td>
				<td align="center"><?php echo $kesimpulan_penelitian;?></td>
			</tr>
			<tr>
				<td>Pendidikan + Penelitian</td>
				<td align="center"><?php echo $syarat_pp;?></td>
				<td align="center"><?php echo (float)$kinerja_pp;?></td>
				<td align="center"><?php echo $bl_a + $bl_b;?></td>
				<td align="center"><?php echo $kesimpulan_pp;?></td>
			</tr>
			<tr>
				<td>Pengabdian + Tambahan</td>
				<td align="center"><?php echo $syarat_pt;?></td>
				<td align="center"><?php echo (float)$kinerja_pt;?></td>
				<td align="center"><?php echo $bl_c + $bl_d;?></td>
				<td align="center"><?php echo $kesimpulan_pt;?></td>
			</tr>
			<tr class="total">
				<td>Total Kinerja</td>
				<td align="center">Min <?php echo $min; ?> sks, Maks <?php echo $max; ?> sks</td>
				<td align="center"><?php echo (float)$total_kinerja;?></td>
				<td align="center"><?php echo $bl_a + $bl_b + $bl_c + $bl_d;?></td>
				<td align="center"><?php echo $kesimpulan;?></td>
			</tr>
		</table>
		
		<!-- kesimpulan -->
			<?php
				if ($kesimpulan_pendidikan !== 'M' && $kesimpulan_pp !== 'M' || $kesimpulan == 'T'){
					$keterangan = "<span class='badge badge-important'><i class='icon-white icon-remove'></i></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='red'><b>TIDAK MEMENUHI SYARAT UU</b></font>";
				}else{
					$keterangan = "<span class='badge badge-success'><i class='icon-white icon-ok'></i></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<font color='green'><b>KETERANGAN : MEMENUHI SYARAT UU</b></font>";
				}
				echo $keterangan;
			?>
			
			
			
			<P></P>
			<!-- kesimpulan untuk beban profesor dalam waktu 3 tahun sejak mulai tahun profesor -->
			<?php if ($this->session->userdata("jenis_dosen") == 'PT' || $this->session->userdata("jenis_dosen") == 'PR'){?>
			
			<h2>Kewajiban Khusus Profesor</h2>
			<table border="0" class="table table-bordered table-hover" width="80%" cellspacing="0">
				<tr>
					<th>Tahun</th>
					<th>Menulis Buku</th>
					<th>Karya Ilmiah</th>
					<th>Menyebarluaskan Gagasan</th>
					<th>Total</th>
				</tr>
				<tr>
					<td align="center"><?php echo $data->THN_PROF;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_a as $jml_a); echo (float)$jml_a->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_b as $jml_b); echo (float)$jml_b->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_c as $jml_c); echo (float)$jml_c->JML_KINERJA;?></td>
					<td align="center"><?php $tot_a = $jml_a->JML_KINERJA + $jml_b->JML_KINERJA + $jml_c->JML_KINERJA; echo (float)$tot_a; ?></td>
				</tr>
				<tr>
					<td align="center"><?php echo $data->THN_PROF+1;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_a1 as $jml_a1); echo (float)$jml_a1->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_b1 as $jml_b1); echo (float)$jml_b1->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_c1 as $jml_c1); echo (float)$jml_c1->JML_KINERJA;?></td>
					<td align="center"><?php $tot_b = $jml_a1->JML_KINERJA + $jml_b1->JML_KINERJA + $jml_c1->JML_KINERJA; echo (float)$tot_b;?></td>
				</tr>
				<tr>
					<td align="center"><?php echo $data->THN_PROF+2;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_a2 as $jml_a2); echo (float)$jml_a2->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_b2 as $jml_b2); echo (float)$jml_b2->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_c2 as $jml_c2); echo (float)$jml_c2->JML_KINERJA;?></td>
					<td align="center"><?php $tot_c = $jml_a2->JML_KINERJA + $jml_b2->JML_KINERJA + $jml_c2->JML_KINERJA; echo (float)$tot_c; ?></td>
				</tr>
				<!-- <tr>
					<td align="center"><?php echo $data->THN_PROF+3;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_a3 as $jml_a2); echo (float)$jml_a2->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_b3 as $jml_b2); echo (float)$jml_b2->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_c3 as $jml_c2); echo (float)$jml_c2->JML_KINERJA;?></td>
					<td align="center"><?php $tot_d = $jml_a2->JML_KINERJA + $jml_b2->JML_KINERJA + $jml_c2->JML_KINERJA; echo (float)$tot_d; ?></td>
				</tr>
				<tr>
					<td align="center"><?php echo $data->THN_PROF+4;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_a4 as $jml_a2); echo (float)$jml_a2->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_b4 as $jml_b2); echo (float)$jml_b2->JML_KINERJA;?></td>
					<td align="center"><?php foreach ($kinerja_tahunan_c4 as $jml_c2); echo (float)$jml_c2->JML_KINERJA;?></td>
					<td align="center"><?php $tot_e = $jml_a2->JML_KINERJA + $jml_b2->JML_KINERJA + $jml_c2->JML_KINERJA; echo (float)$tot_e; ?></td>
				</tr> -->
				<tr class="total">
					<td colspan="4" align="left">Total dalam 3 (tiga) tahun</td>
					<td align="center"><?php echo $tot_a+$tot_b+$tot_c; ?></td>
				</tr>
			</table>
			<div class="ket" style="font-size:12px; text-align:center; width:80%">
				Syarat menurut UU = 9 sks dalam 3 tahun (masing-masing bidang minimal 3 sks)<br/>
				Kesimpulan : akan dinilai pada tahun laporan <?php echo $data->THN_PROF+2;?>
			</div>
			<?php } ?>
	<?php }else{ echo "Sepertinya ada gangguan service...";}?>
	</div>
</div>