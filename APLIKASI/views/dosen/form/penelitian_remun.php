<?php
//print_r($value);
/*	$kd_kat = c;
	$nm_kat = $value->NM_KAT;
	$bt_mulai = date('d/m/Y',strtotime($value->BT_MULAI));
	$bt_selesai = date('d/m/Y',strtotime($value->BT_SELESAI));
	$judul = $value->JUDUL_PENELITIAN;
	$sumber_dana = $value->SUMBER_DANA;
	$jumlah_dana = $value->JUMLAH_DANA;
	$status_peneliti = $value->STATUS_PENELITI;
	$laman_publikasi = $value->LAMAN_PUBLIKASI;*/
?>
					
<table class="table table-responsive table-bordered table-hover">
	<tr><th colspan="4">Data Penelitian</th></tr>
	<!-- <tr>
		<td width="20%">Kategori</td>
		<td colspan="3">
			<select name="kd_kat" class="input-xxlarge" required>
			<?php foreach ($kategori as $kat){?>
			<?php if(isset($value->NM_KAT) and $value->NM_KAT==$kat->NM_KAT):?>
				<option value="<?php echo $kat->KD_KAT;?>"><?php echo $kat->NM_KAT;?></option>
				<?php else:?>
				<option value="<?php echo $kat->KD_KAT;?>"><?php echo $kat->NM_KAT;?></option>
				<?php endif ?>
			<?php } ?>
			</select>
		</td>
	</tr> -->
	<tr>
		<td>Kategori</td>
		<!-- <td colspan="3"><textarea name="jenis_kegiatan" id="autocomplete" class="autocompleteB input-xxlarge" placeholder="Tulis jenis kegiatan sesuai pilihan yang ada" onblur="return auto_fill('Penelitian')" required><?php if(isset($value->JUDUL_PENELITIAN)) echo $value->JUDUL_PENELITIAN;?></textarea></td> -->
		<td colspan="3">
			<select name="pend_kategori" id="kategori" class="input-xxlarge">
				<option>-Pilih Kategori-</option>
				<?php
					foreach ($list_kategori as $key) {?>
						<option value="<?php echo $key['KD_KAT'];?>"><?php echo $key['NM_KAT'];?></option>
					<?php }
				?>
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="4"><input type="hidden" name="kd_kat" id="kd_kat"></td>
	</tr>
	<tr>
		<td>Nama / jenis Kegiatan</td>
		<td colspan="3"><textarea name="judul_penelitian" class="input-xxlarge" id="judul" onblur="return auto_fill('Penelitian')" required><?php if(isset($value->JUDUL_PENELITIAN)) echo $value->JUDUL_PENELITIAN;?></textarea></td>
	</tr>
	<!-- <tr>
		<td>Tanggal Mulai</td>
		<td width="40%"><input type="text" class="datepicker input-medium" name="bt_mulai" value="<?php if(isset($value->BT_MULAI)) echo $value->BT_MULAI; ?>" readonly></td>
		<td width="15%">Tanggal Selesai</td>
		<td><input type="text" class="datepicker input-medium" name="bt_selesai" value="<?php if(isset($value->BT_SELESAI)) echo $value->BT_SELESAI;?>" readonly></td>
	</tr> -->
	<tr>
		<td>Tempat</td>
		<td><textarea name="tempat" class="input-xlarge"></textarea></td>
		<td><?php if(isset($current_data)){
			if($satuan == 'SKS'){
				$satuan = 'MHS';
			}else{
				$satuan = $satuan;
			}echo "Jumlah ".$satuan;}else{?><label id="label_jumlah" name="label_jumlah"></label><?php }?>
		</td>
			
		<!-- <td id="label_jml_bln">Jumlah Bulan</td> -->
		<td><input type="number" name="jml_mhs" id="jml_mhs" class="input-small" min="0"
			value="<?php if(isset($current_data)){
					echo $jml_mhs;
					}?>" required>
		</td>
	</tr>
	<tr>
		<td>Semester</td>
		<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('smt');?>"><?php echo $this->session->userdata('smt');?></option></select></td>
		<td>Tahun <br/>Akademik</td>
		<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option></select></td>
	</tr>
	<!-- <input type="hidden" name="kd_bk" value="<?php echo $kd_bk?>"/> -->
	<tr><th colspan="4">Data Beban Kerja</th></tr>
				<!-- <tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea name="jenis_kegiatan" id="kegiatan" class="input-xxlarge" required><?php if(isset($jns_keg)) echo $jns_keg;?></textarea></td>
				</tr> -->
				
				<tr>
					<td>Masa Penugasan</td>
					<td><input type="number" min="0" name="lama" id="lama" class="input-small" value="<?php echo $lama;?>" required></td>
					<td colspan="2">
						<select name="masa" id="masa" required>
							<option value="<?php echo $masa;?>"><?php echo $masa;?></option>
							<option value="Tahun">Tahun</option>
							<option value="Semester">Semester</option>
							<option value="Bulan">Bulan</option>
							<option value="Hari">Hari</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><b>Beban Kerja</b><br/>Bukti Penugasan</td>
					<td>
						<input type="hidden" name="bukti_penugasan" id="bukti">
						<p class="btn-group">
							<span class="btn btn-isi" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu"><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label">
						<?php 
							echo $bkt_penugasan;
						?></div>
					</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4">SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_penugasan;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td><b>Kinerja</b><br/>Bukti Dokumen</td>
					<td>
						<input type="hidden" name="bukti_dokumen" id="bukti2">
						<p class="btn-group">
							<span class="btn btn-isi2" title="Isi bukti penugasan"><i class="icon-pencil"></i></span>
							<span class="btn btn-cari2" title="Cari dokumen penugasan pada Arsip Dokumen"><i class="icon-search"></i></span>
							<span class="btn btn-upload2-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4">SKS : <input type="number" name="sks2" id="sks2" class="input-small" step="any" min="0" value="<?php echo $sks_bkt;?>" onblur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td>Capaian (%)</td>
					<td><input type="text" name="capaian" id="capaian" class="input-small" value="<?php echo $capaian;?>" readonly></td>
					<td>Rekomendasi</td>
					<td>
						<select name="rekomendasi">
							<option value="<?php echo $recomendasi;?>"><?php echo $caption;?></option>
							<option value="SELESAI">A. SELESAI</option>
							<option value="LANJUTKAN">B. LANJUTKAN</option>
							<option value="BEBAN LEBIH">C. BEBAN LEBIH</option>
							<option value="LAINNYA">D. LAINNYA</option>
						</select>
					</td>
				</tr>
	<!-- <tr>
		<td>Status Peneliti</td>
		<td><select name="status_peneliti" class="input-xlarge" required>
		<?php if(isset($value->STATUS_PENELITI)):?>
			<option value="<?php echo $value->STATUS_PENELITI;?>"><?php $value->STATUS_PENELITI;?></option>
		<?php endif ?>	
			<option value="KETUA">KETUA</option>
			<option value="WAKIL KETUA">WAKIL KETUA</option>
			<option value="ANGGOTA">SEKRETARIS/ANGGOTA</option>
		</select></td>
		<td colspan="2" class="link" id="partner-penelitian-btn" style="cursor:pointer"><i class="icon-user"></i> Partner Penelitian</td>
	</tr> -->

	<!-- <tr id="partner-penelitian" style="display:none">
		<td>Partner Penelitian</td>
		<td colspan="3">
			<div class="tujuan-surat">
				<div class="auto-surat grup">
					<div class="label-input">Dosen/Staff</div>
					<input type="text" name="dosen" id="dosen"/>
				</div>
				<div class="auto-surat grup">
					<div class="label-input">Mahasiswa</div>
					<input type="text" name="mahasiswa" id="mahasiswa" />
				</div>
				<div class="auto-surat grup">
					<div class="label-input">Lainnya</div>
					<table>
						<tr>
							<td><input type="text" name="lain[]" class="input-grup input-xlarge"/></td>
							<td></td>
						</tr>
						<tr id="last"></tr>
						<tr><td colspan="2"><a class="btn btn-small" id="addRow" style="margin:0 0 5px 0">Tambah</a></td></tr>
					</table>
				</div>
			</div>
		</td>
	</tr> -->
	<!-- end auto complete -->				
	<!-- <tr>
		<td>Sumber Dana</td>
		<td colspan="3"><input type="text" name="sumber_dana" class="input-xxlarge" value="<?php if(isset($value->SUMBER_DANA)) echo $value->SUMBER_DANA;?>"></td>
	</tr>
	<tr>
		<td>Jumlah Dana</td>
		<td colspan="3"><input type="number" name="jumlah_dana" class="input-medium" value="<?php if(isset($value->JUMLAH_DANA)) echo $value->JUMLAH_DANA;?>"></td>
	</tr>
	<tr>
		<td>Laman Publikasi</td>
		<td colspan="3"><input type="url" name="laman_publikasi" class="input-xxlarge" value="<?php if(isset($value->LAMAN_PUBLIKASI)) echo $value->LAMAN_PUBLIKASI;?>" placeholder="Masukkan URL"></td>
	</tr> -->
	