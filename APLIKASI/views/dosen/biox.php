		<h2><?php echo $title;?></h2>
		<?php foreach ($identity as $data);	?>
		<?php foreach ($email as $mail); ?>
		<?php foreach ($universitas as $pt);?>
		<table border="0" width="100%" cellspacing="0" cellpadding="5" class="table table-condensed table-hover">
		<tr>
			<td width="150" class="caption">No Sertifikat</td>
			<td colspan="3"> : 
				<?php 
					if (empty($data->NO_SERTIFIKAT)){
						echo $data->NIP;
					}else{
						echo $data->NO_SERTIFIKAT;
					}
				?>
			</td>
		</tr>
		<tr>
			<td class="caption">NIP</td>
			<td> : <?php echo $data->NIP;?></td>
			<td class="caption">NIDN</td>
			<td> : <?php echo $data->NIDN;?></td>
		</tr>
		<tr>
			<td class="caption">Nama</td>
			<td colspan="3"> : <?php echo ucwords(strtolower($data->NM_DOSEN));?></td>
		</tr>
		<tr>
			<td class="caption">Email</td>
			<td colspan="3"> : <?php echo $mail->EMAIL;?></td>
		</tr>
		<tr>
			<td class="caption">No HP</td>
			<td colspan="3"> : <?php echo $data->MOBILE;?></td>
		</tr>
		<tr>
			<td class="caption">Tanggal Lahir</td>
			<td> : <?php echo date('d/m/Y', strtotime($data->TGL_LAHIR));?></td>
			<td class="caption">Tempat lahir</td>
			<td> : <?php echo ucwords(strtolower($data->TMP_LAHIR));?></td>
		</tr>
		<tr><td colspan="4" class="batas">&nbsp;</td></tr>
		<tr>
			<td class="caption">Nama PT</td>
			<td colspan="3"> : <?php echo ucwords(strtolower($pt->NM_PT));?></td>
		</tr>
		<tr>
			<td class="caption">Alamat PT</td>
			<td colspan="3"> : <?php echo ucwords(strtolower($pt->ALAMAT));?></td>
		</tr>
		<tr>
			<td class="caption">Fakultas / Departemen</td>
			<td colspan="3"> : <?php echo ucwords(strtolower($data->NM_FAK));?></td>
		</tr>
		<tr>
			<td class="caption">Program Studi</td>
			<td colspan="3"> : <?php echo ucwords(strtolower($data->NM_PRODI));?></td>
		</tr>
		<tr>
			<td class="caption">Jab. Fungsional</td>
			<td> : <?php echo ucwords(strtolower($data->NM_JABATAN));?></td>
			<td class="caption">Golongan</td>
			<td> : <?php echo $data->NM_GOLONGAN;?></td>
		</tr>
		<tr>
			<td class="caption">Jenis</td>
			<td> : <?php echo "<b>".ucwords(strtolower($data->NM_JENIS_DOSEN))."</b>";?></td>
			<td colspan="2" class="caption">Periode Lab. Gubes Mulai Tahun : <b><?php if ($data->THN_PROF == 0) echo "-"; else echo $data->THN_PROF;?></b>
			</td>
		</tr>
		<tr><td colspan="4" class="batas">&nbsp;</td></tr>
		<tr>
			<td class="caption">Pendidikan S1</td>
			<td colspan="3">
				<?php
				if($data->S1 !== null){ $s1 = explode('#',$data->S1); $pt = $s1[0]; $jurusan = $s1[1]; $masuk = $s1[2]; $lulus = $s1[3];?>
					<table class="table">
						<tr><td width="100">PT</td><td>: <?php echo $pt;?></td></tr>
						<tr><td>Jurusan/Prodi</td><td>: <?php echo $jurusan;?></td></tr>
						<tr><td>Tanggal Masuk</td><td>: <?php echo $masuk;?></td></tr>
						<tr><td>Tanggal Lulus</td><td>: <?php echo $lulus;?></td></tr>
					</table>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td class="caption">Pendidikan S2</td>
			<td colspan="3">
				<?php
				if($data->S2 !== null){ $s1 = explode('#',$data->S2); $pt = $s1[0]; $jurusan = $s1[1]; $masuk = $s1[2]; $lulus = $s1[3];?>
					<table class="table">
						<tr><td width="100">PT</td><td>: <?php echo $pt;?></td></tr>
						<tr><td>Jurusan/Prodi</td><td>: <?php echo $jurusan;?></td></tr>
						<tr><td>Tanggal Masuk</td><td>: <?php echo $masuk;?></td></tr>
						<tr><td>Tanggal Lulus</td><td>: <?php echo $lulus;?></td></tr>
					</table>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td class="caption">Pendidikan S3</td>
			<td colspan="3">
				<?php
				if($data->S3 !== null){ $s1 = explode('#',$data->S3); $pt = $s1[0]; $jurusan = $s1[1]; $masuk = $s1[2]; $lulus = $s1[3];?>
					<table class="table">
						<tr><td width="100">PT</td><td>: <?php echo $pt;?></td></tr>
						<tr><td>Jurusan/Prodi</td><td>: <?php echo $jurusan;?></td></tr>
						<tr><td>Tanggal Masuk</td><td>: <?php echo $masuk;?></td></tr>
						<tr><td>Tanggal Lulus</td><td>: <?php echo $lulus;?></td></tr>
					</table>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<td class="caption">Bidang Ilmu</td>
			<td colspan="3"> : <?php echo $data->BIDANG_ILMU;?></td>
		</tr>
		<tr><td colspan="4" class="batas">&nbsp;</td></tr>
		<tr>
			<td class="caption">Asesor</td>
			<td colspan="3"> : <div style="float:right; width:98%">
				<?php //echo "Data Asesor belum terisi oleh fakultas...";
					foreach ($asdos as $data){
						if ($data->NIRA1 == NULL){
							echo "Asesor 1 belum anda pilih...<br/>";
						}else{ ?>
							<a class="btn" title="Lihat biodata asesor 1" href="<?php echo base_url().'bkd/dosen/biodata/asesor/'.$data->NIRA1;?>">1. <?php echo $data->NIRA1;?></a><br/>
						<?php }
						if ($data->NIRA2 == ''){
							echo "Asesor 2 belum anda pilih...<br/>";
						}else{ ?>
							<a class="btn" title="Lihat biodata asesor 2" href="<?php echo base_url().'bkd/dosen/biodata/asesor/'.$data->NIRA2;?>">2. <?php echo $data->NIRA2;?></a>
						<?php }
					}
				?></div>
			</td>
		</tr>
		</table>
