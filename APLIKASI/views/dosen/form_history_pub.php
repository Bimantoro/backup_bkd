		<br/><?php echo form_open();?>
			<table cellspacing="5" cellpadding="10" width="80%">
				<tr>
					<td>Tahun Akademik</td>
					<td>: 
						<select name="thn">
						<option value="<?php echo $this->session->userdata('ta');?>"><?php echo $this->session->userdata('ta');?></option>
						<?php $now = date('Y')-1; for ($a=$now-1; $a>=$now-5; $a--){ $b=$a+1; ?><option value="<?php echo $a.'/'.$b;?>"><?php echo $a.'/'.$b;?></option><?php } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Semester</td>
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
