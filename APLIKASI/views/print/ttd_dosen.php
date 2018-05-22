<style>table{ font-size:5px; }</style><?php $nama_bulan = array(1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'); 
foreach ($dosen as $data);?>
	<table border="0" width="100%" cellspacing="4">
	<tr>
		<td colspan="2">&nbsp;</td>
		<td align="center">Yogyakarta, <?php echo $tgl_cetak;?></td>
	</tr>
	<tr><td colspan="3" align="center"><br/></td></tr>
	<tr>
		<td align="center" width="33%">
			Menyetujui,<br/>
			<?php echo $lb_kpro ?>
			<?php echo $sb_kpro; ?>
			<?php echo $nm_kpro; ?>
			<br/><br/><br/><br/><br/>
			<?php echo $nama_kaprodi."<br/>NIP. "._generate_nip($nip_kaprodi);?>
		</td>
		<td></td>
		<td align="center" width="33%">
			Dosen yang membuat,
			<br/><br/><br/><br/><br/><br/>
			<?php echo $dosen[0]->NM_PGW_F;?><br/>
			NIP. <?php echo _generate_nip($dosen[0]->KD_PGW);?>
		</td>		
	</tr>
	</table>
</div>
</body>
</html>

