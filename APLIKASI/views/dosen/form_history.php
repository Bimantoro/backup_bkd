		<br/>
		<?php 
			$cek_ta = $this->session->userdata('ta_bk');
			if($cek_ta){
				$temp_ta = $cek_ta;
			}else{
				$temp_ta = $this->session->userdata('ta');
			}

			$cek_smt = $this->session->userdata('smt_bk');
			if($cek_smt){
				$temp_smt = $cek_smt;
			}else{
				$temp_smt = $this->session->userdata('smt');
			}
		?>
		<?php 
		if($this->uri->segment(4) == 'kesimpulan'){ ?>
			<!-- kesimpulan -->
			<?php echo form_open('bkd/dosen/bebankerja/kesimpulan');?>
				<input type="hidden" name="kode" value="<?php echo $this->uri->segment(4);?>">
				<table cellspacing="5" cellpadding="10" width="80%">
					<tr>
						<td>Tahun Akademik</td>
						<td>: 
							<select name="thn">
							<?php 
								$now = date('Y'); 
								for ($a=$now; $a>=$now-5; $a--){ 
									$b = $a+1; $tahun = $a.'/'.$b;
									if($tahun == $temp_ta){ $pilih = 'selected';}else {$pilih = '';}?>
									<option value="<?php echo $a.'/'.$b;?>" <?php echo $pilih;?>><?php echo $a.'/'.$b;?></option>
							<?php }?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Semester</td>
						<td>: 
							<select name="smt">
								<?php if($temp_smt == 'GANJIL'){ ?>
								<option value="GANJIL" selected>SEMESTER GANJIL</option>
								<option value="GENAP">SEMESTER GENAP</option>
								<?php }else{?>
								<option value="GANJIL">SEMESTER GANJIL</option>
								<option value="GENAP" selected>SEMESTER GENAP</option>
								<?php }?>							
							</select>
							<input type="submit" name="submit" value="Lihat Data Beban Kerja" class="btn btn-uin btn-inverse btn-small">
						</td>
					</tr>
				</table>
			<?php echo form_close();?>
		
		<?php }else if ($this->uri->segment(4) == 'narasumber'){ ?>

			<?php echo form_open('bkd/dosen/bebankerja/narasumber');?>
				<input type="hidden" name="kode" value="<?php echo $this->uri->segment(4);?>">
				<table cellspacing="5" cellpadding="10" width="80%">
					<tr>
						<td>Tahun Akademik</td>
						<td>: 
							<select name="thn">
							<?php 
								$now = date('Y'); 
								for ($a=$now; $a>=$now-5; $a--){ 
									$b = $a+1; $tahun = $a.'/'.$b;
									if($tahun == $temp_ta){ $pilih = 'selected';}else {$pilih = '';}?>
									<option value="<?php echo $a.'/'.$b;?>" <?php echo $pilih;?>><?php echo $a.'/'.$b;?></option>
							<?php }?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Semester</td>
						<td>: 
							<select name="smt">
								<?php if($temp_smt == 'GANJIL'){ ?>
								<option value="GANJIL" selected>SEMESTER GANJIL</option>
								<option value="GENAP">SEMESTER GENAP</option>
								<?php }else{?>
								<option value="GANJIL">SEMESTER GANJIL</option>
								<option value="GENAP" selected>SEMESTER GENAP</option>
								<?php }?>							
							</select>
							<input type="submit" name="submit" value="Lihat Data Narasumber" class="btn btn-uin btn-inverse btn-small">
						</td>
					</tr>
				</table>
			<?php echo form_close();?>

		
		<?php } else{ ?>
			<!-- beban kerja -->
			<?php echo form_open('bkd/dosen/bebankerja/history/'.$this->uri->segment(5).'/true');?>
				<input type="hidden" name="kode" value="<?php echo $this->uri->segment(5);?>">
				<table cellspacing="5" cellpadding="10" width="80%">
					<tr>
						<td>Tahun Akademik</td>
						<td>: 
							<select name="thn">
							<?php 
								$now = date('Y'); 
								for ($a=$now; $a>=$now-5; $a--){ 
									$b = $a+1; $tahun = $a.'/'.$b;
									if($tahun == $temp_ta){ $pilih = 'selected';}else {$pilih = '';}?>
									<option value="<?php echo $a.'/'.$b;?>" <?php echo $pilih;?>><?php echo $a.'/'.$b;?></option>
							<?php }?>
							</select>
						</td>
					</tr>
					<tr>
						<td>Semester</td>
						<td>: 
							<select name="smt">
								<?php if($temp_smt == 'GANJIL'){ ?>
								<option value="GANJIL" selected>SEMESTER GANJIL</option>
								<option value="GENAP">SEMESTER GENAP</option>
								<?php }else{?>
								<option value="GANJIL">SEMESTER GANJIL</option>
								<option value="GENAP" selected>SEMESTER GENAP</option>
								<?php }?>							
							</select>
							<input type="submit" name="submit" value="Lihat Data Beban Kerja" class="btn btn-uin btn-inverse btn-small">
						</td>
					</tr>
				</table>
			<?php echo form_close();?>
		<?php } ?>