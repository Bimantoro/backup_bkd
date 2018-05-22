<style>
	table{
		font-size:5px;
	}
</style>
<?php $nama_bulan = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'); ?>
<?php foreach ($dosen as $data);?>
	<!-- persetujuan --><br/><br/><br/>
	<table border="0" width="100%" cellspacing="2">
	<tr><td colspan="3" align="center"><h3>PERNYATAAN DOSEN</h3>
		<p  align="center" style="font-style:italic; font-weight:bold">
		Saya dosen yang membuat laporan kinerja ini menyatakan
		bahwa semua aktivitas dan bukti pendukungnya adalah benar
		aktivitas saya dan saya sanggup menerima sanksi apapun termasuk
		penghentian tunjangan dan mengembalikan yang sudah
		diterima apabila pernyataan ini dikemudian hari terbukti tidak
		benar.
		</p>
	</td></tr>
	<tr>
		<td colspan="2">&nbsp;</td>
		<td align="center">Yogyakarta, <?php echo $tgl_cetak;?></td>
	</tr>
	<tr><td colspan="3"><br/></td></tr>
	<tr>
		<td colspan="3" align="center">
			Dosen yang membuat,
			<br/><br/><br/><br/><br/><br/>
			<?php echo $dosen[0]->NM_PGW_F;?><br/>
			NIP. <?php echo _generate_nip($dosen[0]->KD_PGW);?>
		</td>		
	</tr>
	<tr><td colspan="3" align="center"><h3>PERNYATAAN ASESOR</h3>
		<p align="center" style="font-style:italic; font-weight:bold">
			Saya sudah memeriksa kebenaran dokumen yang ditunjukkan
			dan bisa menyetujui laporan evaluasi Remunerasi Dosen ini
		</p><br/>
	</td></tr>
	
	<!-- Asesor -->
	<tr>
		<td width="33%" align="center">
			Asesor I
			<br/><br/><br/><br/>
			<?php echo $asesor_1_2[0]['nama'];?><br/>
			NIP. <?php echo $asesor_1_2[0]['nip'];?>
		</td>		
		<td></td>
		<td width="33%" align="center">
			Asesor II
			<br/><br/><br/><br/>
			<?php echo $asesor_1_2[1]['nama'];?><br/>
			NIP. <?php echo $asesor_1_2[1]['nip'];?>
		</td>		
	</tr>
	<tr>
		<td colspan="3" align="center">
		Mengesahkan,<br/><?php echo $nama_fakultas;?>
			<br/><br/><br/><br/><br/>
			<?php echo $nama_dekan;?><br/>
			NIP. <?php echo _generate_nip($nip_dekan);?>
		</td>
	</tr>
	</table>
</div>
</body>
</html>