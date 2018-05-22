<?php echo form_open();?>
<table cellspacing="5" cellpadding="10" width="80%">
	<tr>
		<td>Tahun Akademik</td>
		<td>: 
			<select name="thn">
			<?php 
				$now = date('Y'); 
				for ($a=$now; $a>=$now-5; $a--){ 
					if($ta == $a){ $pilih = 'selected';}else {$pilih = '';}?>
					<option value="<?php echo $a;?>" <?php echo $pilih;?>><?php echo konv_label_ta($a);?></option>
			<?php }?>
			</select>
		</td>
	</tr>
	<tr>
		<td>Semester</td>
		<td>: 
			<select name="smt">
				<?php if($smt == 1){ ?>
				<option value="1" selected>SEMESTER GANJIL</option>
				<option value="2">SEMESTER GENAP</option>
				<?php }else{?>
				<option value="1">SEMESTER GANJIL</option>
				<option value="2" selected>SEMESTER GENAP</option>
				<?php }?>							
			</select>
			<input type="submit" name="submit" value="Lihat Data Kinerja" class="btn btn-uin btn-inverse btn-small">
		</td>
	</tr>
</table>
<?php echo form_close();?>