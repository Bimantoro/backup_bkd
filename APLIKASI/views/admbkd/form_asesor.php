<div id="content">
	<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Beban Kerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/asesor/data';?>">Daftar Asesor</a></li>
		<li><a href="<?php echo base_url().'bkd/admbkd/asesor/register';?>">Tambah Asesor</a></li>
	</ul>
		
		<h2>Tambah Asesor</h2>
		<?php 
			if (empty($current_asesor)){
				$label = "Simpan";
				$nama="";
				$kd_dosen = "";
				$nira = "";
				$nm_pt = "";
				$rumpun = "";
				$bidang = "";
				$telp = "";
				$read = "";
				$action = "simpan_asesor";
			}
			else{
				foreach ($current_asesor as $data);
					$label = "Update";
					$nama= $data->NM_ASESOR;
					$kd_dosen = $data->KD_DOSEN;
					$nira = $data->NIRA;
					$nm_pt = $data->NM_PT;
					$rumpun = $data->RUMPUN;
					$bidang = $data->BIDANG_ILMU;
					$telp = $data->TELP;
					$read = "readonly";
					$action = "update_asesor";
			}
			#echo $action;
			?>
		
		<?php echo form_open('bkd/admbkd/asesor/'.$action);?>
		<table border="0" width="100%" cellspacing="0" cellpadding="5" class="table table-condensed">
		<tr>
			<td>NIRA</td>
			<td colspan="3"><input type="text" class="input-xlarge" size="30" name="nira" value="<?php echo $nira;?>" <?php echo $read;?> required></td>
		</tr>
		<tr>
			<td>KD Dosen/NIP</td>
			<td colspan="3"><input type="text" class="input-xlarge" size="30" name="kd_dosen" value="<?php echo $kd_dosen;?>"></td>
		</tr>
		<tr>
			<td>Nama Asesor</td>
			<td colspan="3"><input type="text"  class="input-xlarge" size="50" name="nm_asesor" value="<?php echo $nama;?>" required></td>
		</tr>
		<tr>
			<td>Nama PT</td>
			<td colspan="3"><textarea  class="input-xxlarge" name="nm_pt" cols="60" rows="2"><?php echo $nm_pt;?></textarea></td>
		</tr>
		<tr>
			<td>Rumpun</td>
			<td><input type="text"  class="input-xlarge" size="50" name="rumpun" value="<?php echo $rumpun;?>"></td>
		</tr>
		<tr>
			<td>Bidang Ilmu</td>
			<td><input type="text"  class="input-xlarge" size="50" name="bidang_ilmu" value="<?php echo $bidang;?>"></td>
		</tr>
		<tr>
			<td>Telp</td>
			<td><input type="text"  class="input-xlarge" size="50" name="telp" value="<?php echo $telp;?>"></td>
		</tr>
		<tr>
			<td></td>
			<td><input type="submit" name="submit" value="<?php echo $label;?>" class="btn btn-uin btn-inverse btn-small">
			<input type="reset" name="reset" value="Batal" class="btn btn-uin btn-small"></td>
		</tr>
		</table>
	</div>
</div>
