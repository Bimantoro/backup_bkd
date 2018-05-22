<div id="content">
<div>
	<?php 
		// set value 
		if (empty($current_data)){
			$kd_bkp = '';
			$jenis_keg = '';
			$kd_jenis_keg = '';
			$nm_jenis_keg = '--';
			$bkt_penugasan = '';
			$sks_penugasan = '';
			$ms_penugasan = '';
			$bkt_dokumen = '';
			$sks_bkt = '';
			$recomendasi = '';
			$tahun = '-- Tahun --';
			$caption = '-- Rekomendasi --';
			$action = 'simpan_bkp';
			$btn = 'Simpan';
			$class="current";
		}else{
			foreach ($current_data as $value);
			$kd_bkp = $value->KD_BKP;
			$jenis_keg = $value->JENIS_KEGIATAN;
			$kd_jenis_keg = $value->KD_JENIS_KEG;
			$nm_jenis_keg = ucwords(strtolower($value->NM_KEGIATAN));
			$bkt_penugasan = $value->BKT_PENUGASAN;
			$sks_penugasan = $value->SKS_PENUGASAN;
			$ms_penugasan = $value->MASA_PENUGASAN;
			$bkt_dokumen = $value->BKT_DOKUMEN;
			$sks_bkt = $value->SKS_BKT;
			$recomendasi = $value->REKOMENDASI;
			$tahun = $value->TAHUN;
			$caption = $recomendasi;
			$action = 'update_bkp';
			$btn = 'Update';
			$class="";
		}
		$title = 'Beban Kerja Khusus Profesor';
	?>

	<div id="option-menu1">
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'dosen/bebankerja/profesor';?>">Lihat Data</a>
			<li><a href="<?php echo base_url().'dosen/bebankerja/profesor/input';?>" class="<?php echo $class; ?>">Tambah Data</a>
			<li><a href="<?php echo base_url().'dosen/bebankerja/profesor/history/';?>">History</a>
		</ul><div style="clear:both"></div>
	</div>

	<h2>Input Beban Kerja Profesor</h2>
	

	<?php echo form_open('dosen/bebankerja/'.$action); ?>
	<?php echo validation_errors();?>
	<input type="hidden" name="kd_dosen" value="<?php echo $this->session->userdata("kd_dosen");?>">
	<input type="hidden" name="kd_bkp" value="<?php echo $kd_bkp;?>">
	
	<table class="table table-condensed">
		<tr><td class="ket" colspan="2">Silakan masukkan data sesuai dengan kolom yang tersedia</td></tr>
		<tr>
			<td>Jenis Kegiatan</td>
			<td><textarea name="jenis_kegiatan" cols="50" rows="3"><?php echo $jenis_keg;?></textarea></td>
		</tr>
		<tr>
			<td>Jenis</td>
			<td>
				<select name="jenis" required>
					<option value="<?php echo $kd_jenis_keg;?>"><?php echo $kd_jenis_keg.' &nbsp;'.$nm_jenis_keg;?></option>
					<option value="A"> A &nbsp; Menulis Buku</option>
					<option value="B"> B &nbsp; Karya Ilmiah</option>
					<option value="C"> C &nbsp; Menyebar Luaskan Gagasan</option>
				</select>
			</td>			
		</tr>
		<tr>
			<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
			<td><textarea name="bukti_penugasan" cols="50" rows="3"><?php echo $bkt_penugasan;?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td>SKS : <input type="number" step="any" min="0" max="16" name="sks1" size="3" value="<?php echo $sks_penugasan;?>"></td>
		</tr>
		<tr>
			<td>Masa Penugasan</td>
			<td><textarea name="masa_penugasan" cols="50" rows="3"><?php echo $ms_penugasan;?></textarea></td>
		</tr>
		<tr>
			<td><b>Kinerja</b><br/>Bukti Dokumen</td>
			<td><textarea name="bukti_dokumen" cols="50" rows="3"><?php echo $bkt_dokumen;?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td>SKS : <input type="number" step="any" min="0" max="16" name="sks2" size="3" value="<?php echo $sks_bkt;?>"></td>
		</tr>
		<tr>
			<td>Rekomendasi</td>
			<td>
				<select name="rekomendasi">
					<option value="<?php echo $recomendasi;?>"><?php echo $caption;?></option>
					<option value="selesai">Selesai</option>
					<option value="lanjutkan">Lanjutkan</option>
					<option value="beban_lebih">Beban Lebih</option>
					<option value="lainnya">Lainnya</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Tahun</td>
			<td>
				<select name="tahun" required>
					<option value="<?php echo $tahun;?>"><?php echo $tahun;?></option>
					<?php for ($t = 2010; $t<=2020; $t++){ ?>
						<option value="<?php echo $t;?>"><?php echo $t;?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="8">
				<input type="submit" name="submit" Value="<?php echo $btn;?>">
				<input type="reset" name="reset" Value="Cancel">
			</td>
		</tr>	</table>
	<!-- 
	<table border="0" cellspacing="0" class="form" width="100%">
		<tr>
			<th rowspan="2">Jenis Kegiatan</th>
			<th rowspan="2">Jenis</th>
			<th colspan="2">Beban Kerja</th>
			<th rowspan="2">Masa Penugasan</th>
			<th colspan="2">Kinerja</th>
			<th rowspan="2">Rekomendasi</th>
			<th rowspan="2" style="border-right:none">Tahun</th>
		</tr>
		<tr>
			<th>Bukti Penugasan</th>
			<th>SKS</th>
			<th>Bukti Dokumen</th>
			<th>SKS</th>
		</tr>
		<tr>
			<td><textarea name="jenis_kegiatan" cols="50" rows="3"><?php echo $jenis_keg;?></textarea></td>
			<td>
				<select name="jenis" required class="jenis">
					<option value="<?php echo $kd_jenis_keg;?>"><?php echo $kd_jenis_keg.' &nbsp;'.$nm_jenis_keg;?></option>
					<option value="A"> A &nbsp; Menulis Buku</option>
					<option value="B"> B &nbsp; Karya Ilmiah</option>
					<option value="C"> C &nbsp; Menyebar Luaskan Gagasan</option>
				</select>
			</td>
			<td><textarea name="bukti_penugasan" cols="50" rows="3"><?php echo $bkt_penugasan;?></textarea></td>
			<td><input type="number" step="any" min="0" max="16" name="sks1" size="3" value="<?php echo $sks_penugasan;?>"></td>
			<td><textarea name="masa_penugasan" cols="50" rows="3"><?php echo $ms_penugasan;?></textarea></td>
			<td><textarea name="bukti_dokumen" cols="50" rows="3"><?php echo $bkt_dokumen;?></textarea></td>
			<td><input type="number" step="any" min="0" max="16" name="sks2" size="3" value="<?php echo $sks_bkt;?>"></td>
			<td>
				<select name="rekomendasi">
					<option value="<?php echo $recomendasi;?>"><?php echo $caption;?></option>
					<option value="selesai">Selesai</option>
					<option value="lanjutkan">Lanjutkan</option>
					<option value="beban_lebih">Beban Lebih</option>
					<option value="lainnya">Lainnya</option>
				</select>
			</td>
			<td>
				<select name="tahun" required>
					<option value="<?php echo $tahun;?>"><?php echo $tahun;?></option>
					<?php for ($t = 2010; $t<=2020; $t++){ ?>
						<option value="<?php echo $t;?>"><?php echo $t;?></option>
					<?php } ?>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="8">
				<input type="submit" name="submit" Value="<?php echo $btn;?>">
				<input type="reset" name="reset" Value="Cancel">
			</td>
		</tr>
	</table>
	<?php echo form_close(); ?>
	
	<!-- tampilkan beban kerja yang telah diambil 
	<table border="0" cellspacing="0" class="list" width="100%">
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">Jenis Kegiatan</th>
			<th colspan="2">Beban Kerja</th>
			<th rowspan="2">Masa Penugasan</th>
			<th colspan="2">Kinerja</th>
			<th rowspan="2">Rekomendasi</th>
			<th colspan="3" rowspan="2" style="border-right:none">Action</th>
		</tr>
		<tr>
			<th>Bukti Penugasan</th>
			<th>SKS</th>
			<th>Bukti Dokumen</th>
			<th>SKS</th>
		</tr>
		<?php 
			if (empty($data_beban_prof)){
				echo '<tr><td colspan="9" align="center">'.$title.' masih kosong...</td></tr>';
			}else{
				$no=0;
				foreach ($data_beban_prof as $bp){ $no++; ?>
				<tr>
					<td><?php echo $no;?></td>
					<td><?php echo $bp->JENIS_KEGIATAN;?></td>
					<td><?php echo $bp->BKT_PENUGASAN;?></td>
					<td align="center"><?php echo $bp->SKS_PENUGASAN;?></td>
					<td><?php echo $bp->MASA_PENUGASAN;?></td>
					<td><?php echo $bp->BKT_DOKUMEN;?></td>
					<td align="center"><?php echo $bp->SKS_BKT;?></td>
					<td><?php echo $bp->REKOMENDASI;?></td>
					<td><a href="<?php echo site_url().'dosen/bebankerja/edit_bkp/'.$bp->KD_BKP;?>">Edit</a></td>
					<td> | </td>
					<td><a class="edit" onclick="return hapus('Hapus?')" href="<?php echo site_url().'dosen/bebankerja/hapus_bkp/'.$bp->KD_BKP;?>">Hapus</a></td>
				</tr>
			<?php } ?>
			<tr class="total">
				<td></td>
				<td colspan="2">Jumlah Beban Kerja</td>
				<td><?php foreach($jml_beban_prof as $jml); echo $jml->JML_BEBAN_SKS; ?></td>
				<td colspan="2">Jumlah Kinerja</td>
				<td><?php foreach($jml_kinerja_prof as $jml); echo $jml->JML_KINERJA; ?></td>
				<td colspan="4"></td>
			</tr>
		<?php } ?>
	</table>	
	-->
	
</div>
</div>