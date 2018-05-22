<div id="content">
<div>
	<?php 
		// set value 
		if (empty($current_data)){
			$kd_bk = '';
			$jns_keg = '';
			$bkt_penugasan = '';
			$sks_penugasan = '';
			$ms_penugasan = '';
			$bkt_dokumen = '';
			$sks_bkt = '';
			$recomendasi = '';
			$caption = '-- Rekomendasi --';
			$action = 'simpan_bk';
			$btn = 'Simpan';
			$class="current";
		}else{
			foreach ($current_data as $value);
			$kd_bk = $value->KD_BK;
			$jns_keg = $value->JENIS_KEGIATAN;
			$bkt_penugasan = $value->BKT_PENUGASAN;
			$sks_penugasan = $value->SKS_PENUGASAN;
			$ms_penugasan = $value->MASA_PENUGASAN;
			$bkt_dokumen = $value->BKT_DOKUMEN;
			$sks_bkt = $value->SKS_BKT;
			$recomendasi = $value->REKOMENDASI;
			$caption = $recomendasi;
			$action = 'update_bk';
			$btn = 'Update';
			$class="";
		}
	?>
	<?php 
		// cek kode
		switch($kode){
			case "A" : $title = 'pendidikan'; break;
			case "B" : $title = 'penelitian'; break;
			case "C" : $title = 'pengabdian'; break;
			case "D" : $title = 'penunjang'; break;
		}
		
	?>
	
	<div id="option-menu1">
		<ul id="hover-menu">
			<li><a href="<?php echo base_url().'dosen/bebankerja/data/'.$kode;?>">Lihat Data</a>
			<li><a href="<?php echo base_url().'dosen/bebankerja/'.$title;?>" class="<?php echo $class;?>">Tambah Data</a>
			<li><a href="<?php echo base_url().'dosen/bebankerja/history/'.$kode;?>">History</a>
		</ul><div style="clear:both"></div>
	</div>
	
	<h2><?php echo ucwords($title);?></h2>
	<?php echo validation_errors();?>
	<?php echo form_open('dosen/bebankerja/'.$action); ?>
	<!-- hidden value -->
	<input type="hidden" name="kd_bk" value="<?php echo $kd_bk;?>">
	<input type="hidden" name="kd_dosen" value="<?php echo $this->session->userdata("kd_dosen");?>">
	<input type="hidden" name="ta" value="<?php echo $this->session->userdata("thn");?>">
	<input type="hidden" name="kd_jbk" value="<?php echo $kode;?>">
	
	
	<table border="0" cellspacing="0" class="form" width="100%">
		<tr><td class="ket" colspan="2">Silakan masukkan data sesuai dengan kolom yang tersedia</td></tr>
		<tr>
			<td>Jenis Kegiatan</td>
			<td><textarea name="jenis_kegiatan" cols="50" rows="3"><?php echo $jns_keg;?></textarea></td>
		</tr>
		<tr>
			<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
			<td><textarea name="bukti_penugasan" cols="50" rows="3"><?php echo $bkt_penugasan;?></textarea></td>
		</tr>
		<tr>
			<td></td>
			<td>SKS : <input type="number" step="any"name="sks1" min="0" max="16" size="3" value="<?php echo $sks_penugasan;?>"></td>
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
			<td>SKS : <input type="number" step="any"name="sks2" min="0" max="16" size="3" value="<?php echo $sks_bkt;?>"></td>
		</tr>
		<tr>
			<td>Rekomendasi</td>
			<td>
				<select name="rekomendasi">
					<option value="<?php echo $recomendasi;?>"><?php echo $caption;?></option>
					<option value="selesai">Selesai</option>
					<option value="lanjutkan">Lanjutkan</option>
					<option value="beben lebih">Beban Lebih</option>
					<option value="lainnya">Lainnya</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="7">
				<input type="submit" name="submit" Value="<?php echo $btn;?>" class="btn btn-primary btn-mini">
				<input type="reset" name="reset" Value="Cancel" class="btn btn-mini">
			</td>
		</tr>
	</table>
</div>	
</div>