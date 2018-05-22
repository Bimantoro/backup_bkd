		<?php $action = $this->uri->segment(5); ?>
		<br/><?php echo form_open('bkd/admbkd/kompilasi/semester/'.$action);?>
			<input type="hidden" name="kode" value="<?php echo $this->uri->segment(4);?>">
			<table class="table">
				<tr>
					<th>Tahun Akademik</th>
					<td>: 
						<select name="thn">
							<?php 
								$now = date('Y'); 
								for ($a=$now; $a>=$now-5; $a--){ 
									$b = $a+1; $tahun = $a.'/'.$b;
									if($tahun == $this->session->userdata('ta')){ $pilih = 'selected';}else {$pilih = '';}?>
									<option value="<?php echo $a.'/'.$b;?>" <?php echo $pilih;?>><?php echo $a.'/'.$b;?></option>
							<?php }?>
						</select>
					</td>
				</tr>
				<tr>
					<th>Semester</th>
					<td>: 
						<select name="smt">
							<?php if($this->session->userdata('smt') == 'GANJIL'){ ?>
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
