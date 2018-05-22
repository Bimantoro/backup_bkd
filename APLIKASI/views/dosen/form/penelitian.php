<?php
/*echo "<pre>";
print_r($value);
echo "<pre>";*/
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
<table class="table table-condensed">
	<tr><th colspan="4">Data Penelitian</th></tr>
	<tr>
		<td width="20%">Kategori</td>
		<td colspan="3">
			<select name="pend_kategori" id="kategori" class="input-xxlarge" <?php if(ISSET($current_data)){?>
						readonly="readonly"
					<?php }?>>
							<?php
								if(ISSET($current_data)){
									foreach ($list_kategori as $key) {?>
												<?php
													if($key['NM_KAT'] == $nm_kat){?>
														<option value="<?php echo $nm_kat;?>" selected><?php echo $nm_kat;?></option>
													<?php }else{?>
														<option value="<?php echo $key['KD_KAT'];?>"><?php echo $key['NM_KAT'];?></option>
													<?php }
												?>
										<?php }	
								}else{?>
										<option>-Pilih Kategori-</option>
										<?php
											foreach ($list_kategori as $key) {?>
												<option value="<?php echo $key['KD_KAT'];?>"><?php echo $key['NM_KAT'];?></option>
											<?php }
										?>
								<?php }
							?>
						</select>
		</td>
	</tr>
	<!-- <tr>
		<td>Nama/Jenis Kegiatan</td>
		<td colspan="3"><textarea name="jenis_kegiatan" id="autocomplete" class="autocompleteB input-xxlarge" placeholder="Tulis jenis kegiatan sesuai pilihan yang ada" onblur="return auto_fill('Penelitian')" required><?php if(isset($value->JUDUL_PENELITIAN)) echo $value->JUDUL_PENELITIAN;?></textarea></td>
	</tr> -->
	<tr>
		<td colspan="4"><input type="hidden" name="kd_kat" id="kd_kat" value="<?php if(ISSET($value->KD_KAT)){echo $value->KD_KAT;}?>"></td>
	</tr>
	<tr>
		<td>Nama / Judul Penelitian</td>
		<td colspan="3"><textarea name="jenis_kegiatan" class="input-xxlarge" id="judul" onblur="return auto_fill('Penelitian')" required><?php if(isset($value->JUDUL_PENELITIAN)) echo $value->JUDUL_PENELITIAN;?></textarea></td>
	</tr>
	<tr id="tr_jumlah_mhs">
		<td>Jumlah Dosen Yang Diasesori</td>
		<td colspan="3"><input type="number" name="jml_mhs" id="jml_mhs" class="input-small" min="0" value="<?php if(isset($current_data)){echo $value->JML_MHS;} ?>" required></td>
	</tr>
	<tr>
		<td>Tanggal Mulai</td>
		<td width="40%"><input type="text" class="datepicker input-medium" name="bt_mulai" value="<?php if(isset($value->BT_MULAI)) echo $value->BT_MULAI; ?>" readonly></td>
		<td width="15%">Tanggal Selesai</td>
		<td><input type="text" class="datepicker input-medium" name="bt_selesai" value="<?php if(isset($value->BT_SELESAI)) echo $value->BT_SELESAI;?>" readonly></td>
	</tr>
	<tr>
		<td>Status Peneliti</td>
		<td>
			<?php
				$arr= array("KETUA"=>"KETUA", "WAKIL KETUA"=>"WAKIL KETUA", "ANGGOTA"=>"ANGGOTA");
			?>
			<select name="status_peneliti" class="input-xlarge" required>
				<?php
					if(isset($current_data)){?>
						<?php
							foreach ($arr as $a=>$value) {?>
								<?php
									if($arr = $value->STATUS_PENELITI){?>
										<option value="<?php echo $value->STATUS_PENELITI;?>"><?php echo $value->STATUS_PENELITI;?></option>
									<?php }else{?>
										<option value="<?php echo $value;?>"><?php echo $a;?></option>
									<?php }
								?>
								
							<?php }
						?>
					<?php }else{?>
						<?php
							foreach ($arr as $a=>$value) {?>
								<option value="<?php echo $value;?>"><?php echo $a;?></option>
							<?php }
						?>
					<?php }
				?>
				
			</select>
			<!-- <select name="status_peneliti" class="input-xlarge" required>
		<?php if(isset($value->STATUS_PENELITI)):?>
			<option value="<?php echo $value->STATUS_PENELITI;?>"><?php $value->STATUS_PENELITI;?></option>
		<?php endif ?>	
			<option value="KETUA">KETUA</option>
			<option value="WAKIL KETUA">WAKIL KETUA</option>
			<option value="ANGGOTA">SEKRETARIS/ANGGOTA</option>
		</select> --></td>
		<td colspan="2" class="link" id="partner-penelitian-btn" style="cursor:pointer"><i class="icon-user"></i> Partner Penelitian</td>
	</tr>

	<tr id="partner-penelitian" style="display:none">
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
	</tr>
	<!-- end auto complete -->				
	<tr>
		<td>Sumber Dana</td>
		<td colspan="3"><input type="text" name="sumber_dana" class="input-xxlarge" value="<?php if(isset($current_data)){echo $current_data[0]->SUMBER_DANA;}?>"></td>
	</tr>
	<tr>
		<td>Jumlah Dana</td>
		<td colspan="3"><input type="number" name="jumlah_dana" class="input-medium" value="<?php if(isset($current_data)){echo $current_data[0]->JUMLAH_DANA;}?>"></td>
	</tr>
	<tr>
		<td>Laman Publikasi</td>
		<td colspan="3"><input type="url" name="laman_publikasi" class="input-xxlarge" value="<?php if(isset($current_data)){echo $current_data[0]->LAMAN_PUBLIKASI;}?>" placeholder="Masukkan URL"></td>
	</tr>
	