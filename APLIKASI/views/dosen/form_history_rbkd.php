		<br/><?php echo form_open('bkd/dosen/bebankerja/rbkd/'.$this->uri->segment(5).'/true');?>
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
									if($tahun == $this->session->userdata('r_ta')){ $pilih = 'selected';}else {$pilih = '';}?>
									<option value="<?php echo $a.'/'.$b;?>" <?php echo $pilih;?>><?php echo $a.'/'.$b;?></option>
							<?php }?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Semester</td>
					<td>: 
						<select name="smt">
							<?php if($this->session->userdata('r_smt') == 'GANJIL'){ ?>
							<option value="GANJIL" selected>SEMESTER GANJIL</option>
							<option value="GENAP">SEMESTER GENAP</option>
							<?php }else{?>
							<option value="GANJIL">SEMESTER GANJIL</option>
							<option value="GENAP" selected>SEMESTER GENAP</option>
							<?php }?>							
						</select>
						<input type="submit" name="submit" value="Lihat Data RBKD" class="btn btn-uin btn-inverse btn-small">
					</td>
				</tr>
			</table>
		<?php echo form_close();?>