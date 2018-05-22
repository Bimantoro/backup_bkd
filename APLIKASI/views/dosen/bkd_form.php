<?php
		# set value 
		if (empty($current_data)){
			$kd_bk = '';
			$jns_keg = '';
			$bkt_penugasan = '';
			$sks_penugasan = 0;
			$ms_penugasan = ''; $lama = ''; $masa = '';
			$bkt_dokumen = '';
			$sks_bkt = 0;
			$recomendasi = '';
			$caption = '- Rekomendasi -';
			$jml_jam = 0;
			$capaian = '';
			$file_penugasan = ''; $fp = '';
			$file_capaian = ''; $fc = '';
			$outcome = '';
			
			# data detail pendidikan
			$kd_kat = ''; $nm_kat = '- Kategori Publikasi -';
			$nm_kat = '- Pilih Kategori -';
			$nm_kegiatan = $jns_keg;
			$jenjang = '- Pilih Jenjang -';
			$tempat = '';
			$jml_mhs = 0;
			
			# === data detail penelitian
			$bt_mulai = '';
			$bt_selesai = '';
			$judul = '';
			$dosen_partner = '';
			$sumber_dana = '';
			$jumlah_dana = 0;
			$status_peneliti = '- Pilih status -';
			$laman_publikasi = '';
			
			# === data detail narasumber
			$lokasi_acara = '';
			$kd_tingkat = '';
			$nm_tingkat = '- Pilih Tingkat -';
			
			$action = 'simpan_bk_hybrid';
			$btn = 'Simpan';
		}else{
			foreach ($current_data as $value);
				$kd_bk = $value->KD_BK;
				$jns_keg = $value->JENIS_KEGIATAN;
				$bkt_penugasan = $value->BKT_PENUGASAN;
				$sks_penugasan = $value->SKS_PENUGASAN;
				$ms_penugasan = $value->MASA_PENUGASAN; $a = explode(' ',$ms_penugasan); $lama = $a[0]; $masa = $a[1];
				$bkt_dokumen = $value->BKT_DOKUMEN;
				$sks_bkt = $value->SKS_BKT;
				$recomendasi = $value->REKOMENDASI;
				$caption = $recomendasi;
				$jml_jam = $value->JML_JAM;
				$capaian = $value->CAPAIAN;
				$file_penugasan = $value->FILE_PENUGASAN;
				$file_capaian = $value->FILE_CAPAIAN;
				$outcome = $value->OUTCOME;
				if($file_penugasan == 0 || $file_penugasan == '') $fp = 'Tidak ada file penugasan'; 
					else $fp = '<a href='.base_url().'uploads/DataBkd/FilesBebanKerja/'.$file_penugasan.'>Lihat File</a>';
				if($file_capaian == 0 || $file_capaian == '') $fc = 'Tidak ada file capaian'; 
					else $fc = '<a href='.base_url().'uploads/DataBkd/FilesBebanKerja/'.$file_capaian.'>Lihat File</a>';
				#data pendidikan
				if(isset($kode)){
					if($kode == 'A'){
						$kd_kat = $value->KD_KAT;
						$nm_kat = $value->NM_KAT;
						$nm_kegiatan = $jns_keg;
						$jenjang = $value->JENJANG;
						$tempat = $value->TEMPAT;
						$jml_mhs = $value->JML_MHS;
					}
					# data detail pendidikan
					else if($kode == 'B'){
						$kd_kat = $value->KD_KAT;
						$nm_kat = $value->NM_KAT;
						$bt_mulai = date('d/m/Y',strtotime($value->BT_MULAI));
						$bt_selesai = date('d/m/Y',strtotime($value->BT_SELESAI));
						$judul = $value->JUDUL_PENELITIAN;
						$sumber_dana = $value->SUMBER_DANA;
						$jumlah_dana = $value->JUMLAH_DANA;
						$status_peneliti = $value->STATUS_PENELITI;
						$laman_publikasi = $value->LAMAN_PUBLIKASI;
					}else if ($kode == 'C'){
						$kd_kat = $value->KD_KAT;
						$nm_kat = $value->NM_KAT;
						$bt_mulai = date('d/m/Y',strtotime($value->BT_MULAI));
						$bt_selesai = date('d/m/Y',strtotime($value->BT_SELESAI));
						$judul = $value->JUDUL_PENGABDIAN;
						$sumber_dana = $value->SUMBER_DANA;
						$jumlah_dana = $value->JUMLAH_DANA;
					}else if($kode == 'F'){
						$kd_tingkat = $value->KD_TINGKAT;
						$nm_tingkat = $value->NM_TINGKAT;
						$judul = $value->JUDUL_ACARA;
						$bt_mulai = date('d/m/Y',strtotime($value->BT_MULAI));
						$bt_selesai = date('d/m/Y',strtotime($value->BT_SELESAI));
						$lokasi_acara = $value->LOKASI_ACARA;
						$laman_publikasi = $value->LAMAN_PUBLIKASI;
						$status_peneliti = $value->STATUS_PENELITI;
					}
				}	
				#echo $file_capaian.'#'.$file_penugasan;
			$action = 'update_bk';
			$btn = 'Update';
			#print_r($current_data);
		} 
	?>			<input type="hidden" name="kd_bk" value="<?php echo $kd_bk?>"/>
				<tr><th colspan="4">Data Beban Kerja</th></tr>
				<!-- <tr>
					<td>Nama/Jenis Kegiatan</td>
					<td colspan="3"><textarea name="jenis_kegiatan" id="kegiatan" class="input-xxlarge" required><?php if(isset($jns_keg)) echo $jns_keg;?></textarea></td>
				</tr> -->
				<tr>
					<td>Semester</td>
					<td><select name="semester" class="input-medium" readonly><option value="<?php echo $this->session->userdata('smt');?>"><?php echo $this->session->userdata('smt');?></option></select></td>
					<td>Tahun <br/>Akademik</td>
					<td><select name="thn_akademik" class="input-medium" readonly><option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option></select></td>
				</tr>
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
							<span class="btn btn-upload-disabled" title="Untuk mengupload dokumen penugasan, Silakan simpan data terlebih dahulu" disabled><i class="icon-upload"></i></span>
						</p>
						 <div style="font-size: 11px;line-height: 15px;margin-bottom: 10px;color: #777;">
							Untuk mengupload dokumen, Silakan simpan data terlebih dahulu
						</div>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label"><?php echo $bkt_penugasan;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4">SKS : <input type="number" name="sks1" id="sks1" step="any" min="0" max="16" class="input-small" value="<?php echo $sks_penugasan;?>" onBlur="return autosum()"></td>
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
                        <div style="font-size: 11px;line-height: 15px;margin-bottom: 10px;color: #777;">
							Untuk mengupload dokumen, Silakan simpan data terlebih dahulu
						</div>
					</td>
					<td colspan="2" class="bs-callout-info"><div id="bukti-label2"><?php echo $bkt_dokumen;?></div></td>
				</tr>
				<tr>
					<td></td>
					<td colspan="4">SKS : <input type="number" name="sks2" id="sks2" class="input-small" step="any" min="0" value="<?php echo $sks_bkt;?>" onBlur="return autosum()"></td>
					<td></td>
				</tr>
				<tr>
					<td>Capaian (%)</td>
					<td><input type="number" name="capaian" id="capaian" value="<?php echo $capaian;?>" class="input-small" readonly></td>
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