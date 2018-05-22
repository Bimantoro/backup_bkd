<script type="text/javascript">
$(document).ready(function(){
	$('.print').click(function(){
		$('#bg').fadeIn('fast');
		$('#loading').fadeIn('fast');
	});
});
</script>
<style>
	#bg{ 
		background-color:#555; 
		opacity:0.5;
		position:fixed;
		width:100%; height:100%;
		top:0; left:0;
		z-index:5;
		display:none;
	}
	#loading{
		position:fixed;
		width:20%;
		left:40%;
		top:20%;
		font-weight:bold;
		text-align:center;
		display:none;
		background-color:#FFF;
		border:solid 1px #AAA;
		padding:30px;
		z-index:10;
	}
</style>
<?php echo $this->s00_lib_output->output_info_dsn();?>
<ul id="crumbs">
	<li><a href="<?php echo base_url().'bkd/dosen/biodata';?>">Beban Kerja Dosen</a></li>
	<li><a href="<?php echo base_url().'bkd/dosen/bebankerja/cetak';?>">Cetak Laporan</a></li>
</ul>
<div id="bg"></div>
<div id="loading">Harap Menunggu...</div>
<div id="content">

	<div>
		<?php
		$a = $this->session->flashdata('msg');
    if($a!=null){?>
        <br>
        <div class="alert alert-<?php echo $a[0]?> alert-msg">
            <?php echo $a[1]?>
        </div>
    <?php }
	?>
		<h2>Cetak Dokumen</h2>
		<?php echo form_open('bkd/dosen/cetak_remun');?>
			<input type="hidden" name="kd_dosen" value="<?php echo $this->session->userdata('kd_dosen');?>">
			<input type="hidden" name="ta" value="<?php echo $this->session->userdata('ta');?>">
			<input type="hidden" name="smt" value="<?php echo $this->session->userdata('smt');?>">
			<input type="hidden" name="kd_prodi" value="<?php echo $this->session->userdata('kd_prodi');?>">
			<input type="hidden" name="kd_fak" value="<?php echo $this->session->userdata('kd_fak');?>">
			<table border="0" class="table table-condensed table-hover">
				<tr>
					<td>Tahun Akademik</td>
					<td>: 
						<select name="ta">
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
					</td>
				</tr>
				<tr>
					<td width="150">Jenis Dokumen</td>
					<td>: 
						<select name="jenis" class="input-xlarge">
							<!-- <option value="rbkd">Rencana Beban Kerja Dosen</option> -->
							<option value="lbkd">Laporan Remunerasi Dosen</option>
						</select>
					</td>
				</tr>
				<tr>
					<td></td>
					<td colspan="3"><input type="submit" name="submit" value="Cetak" class="btn btn-uin btn-inverse print"></td>
				</tr>				
			</table>
			
		<?php echo form_close(); ?>
	
	</div>

</div>
<script type="text/javascript" charset="utf-8">
      $(function(){
        setTimeout('closing_msg()', 10000)
      })

      function closing_msg(){
        $(".alert-msg").slideUp();
      }
    </script>