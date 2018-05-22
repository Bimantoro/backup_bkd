<div id="content">
	<div>
		<ul id="crumbs">
			<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Admin Kinerja Dosen</a></li>
			<li><a href="<?php echo base_url().'bkd/admbkd/home';?>">Master</a></li>
			<li><a href="<?php echo base_url().'bkd/admbkd/asesor/import';?>">Import Asesor dari Excel</a></li>
		</ul>
		
		<h2>Import Data Asesor Beban Kerja Dosen</h2>
		<div class="bs-callout bs-callout-info">Untuk melakukan import data asesor, silakan Anda download berkas form asesor berikut</div>
		
		<?php echo form_open('bkd/admbkd/asesor/download');?>
		<table class="table">
			<tr>
				<td>Pilih Form Asesor Prodi</td>
				<td>
					<select name="kd_prodi" class="input-xlarge">
						<option value="">Form Asesor Prodi</option>
						<?php foreach($prodi as $p){ ?>
						<option value="<?php echo $p->KD_PRODI;?>">ASESOR PRODI <?php echo $p->NM_PRODI;?></option>
						<?php } ?>
					</select>
				</td>
				<td><input type="submit" name="submit" class="btn btn-uin btn-inverse" value="Download Berkas"></td>
			</tr>
		</table>
		<?php echo form_close(); ?>
		
		<div class="bs-callout bs-callout-info">Setelah berkas penilaian tersebut selesai anda isi, silakan upload kembali berkas tersebut melalui form di bawah ini.</div><br/>
		
		<?php
			# get messages 
			if (!empty($message)){
				echo $message;
			}
			if(!empty($status)){
				echo $status;
			}
		?>
		
		<?php echo form_open_multipart('bkd/admbkd/asesor/do_upload');?>
		<input type="file" name="userfile" id="userfile" class="txt_login customfile-input" style="left: -155.5px; top: 7.40625px;">
		<input type="submit" name="submit" value="Upload berkas daftar asesor" class="btn btn-uin btn-inverse">
		<?php echo form_close();?>		
		
		<div class="bs-callout bs-callout-error">Perhatian! Berkas import data asesor yang anda upload harus sesuai dengan berkas yang dapat di-download di halaman ini. Yaitu berkas Microsoft Excel dengan format XLS bukan XLSX.</div>
	</div>
</div>