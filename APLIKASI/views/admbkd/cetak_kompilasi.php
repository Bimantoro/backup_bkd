<style>
	@page { margin: 0.2in 0.5in 0.2in 0.5in;}
	body {font-family:helvetica; font-size:12px; }
	#header{
		border-bottom:solid 1px #aaa;
		font-weight:bold;
		font-size:1.3em;
		text-align:center;
		margin-bottom:10px;
		padding:10px;
	}
	.total{ 
		font-weight:bold;
		text-align:center;
	}
	.title{
		text-align:center;
		text-transform:uppercase;
		font-size:1.2em;
		font-weight:bold;
	}
	#print-area{
		margin:auto;
	}
	.list{
		margin-bottom:30px;
		font-size:12px;
		border-left:solid 1px #aaa;
		border-bottom:solid 1px #aaa;
	}
	.list td{
		padding:5px;
		border-top:solid 1px #aaa;
		border-right:solid 1px #aaa;
	}
	.list th{
		background-color:#ddd;
		padding:5px;
		border-top:solid 1px #aaa;
		border-right:solid 1px #aaa;
	}
	.list tr:nth-child(even){
		background-color:#eee;
	}
	.list tr:nth-child(odd){
		background-color:#fff;
	}
	.assignment{
		height:80px;
	}
	label{
		width:150px;
		float:left;
		font-weight:bold;
	}
	a{ text-decoration:none; color:#222;}
</style>
<?php $this->load->view('fungsi');?>
<?php $tahun  = substr($this->session->userdata("thn"),0,4);?>
	<div id="content">
	<div>
<div id="print-area">
	<h2>REKAP UNIVERSITAS</h2>
	<label>UNIVERSITAS ISLAM NEGERI SUNAN KALIJAGA</label><label style="width:350"> : <?php //echo $this->session->userdata("nm_pt");?></label><br>
	
	<table border="0" class="list" cellspacing="0" width="100%" style="margin-top:20px">
		<tr>
			<th rowspan="2">No</th>
			<th rowspan="2">No Sertifikat</th>
			<th rowspan="2">Nama Dosen</th>
			<th colspan="4">Semester Gasal</th>
			<th colspan="4">Semester Genap</th>
			<th colspan="3">Kewajiban <br/>Khusus Profesor</th>
			<th rowspan="2">Status</th>
			<th rowspan="2">Kesimpulan</th>
		</tr>
		<tr>
			<th>Pd</th>
			<th>Pl</th>
			<th>Pg</th>
			<th>Pk</th>
			<th>Pd</th>
			<th>Pl</th>
			<th>Pg</th>
			<th>Pk</th>
			<th>A</th>
			<th>B</th>
			<th>C</th>
		</tr>
		<?php if (empty($dosen)){ ?>
		<tr><td colspan="16">Beban Kerja masih kosong pada tahun ajaran <?php echo $this->session->userdata("thn");?></td></tr>
		<?php } else { $no=0; $index=0; foreach ($dosen as $data) { $no++; ?>
		<tr>
			<td><?php echo $no;?></td>
			<td><?php if ($data->NO_SERTIFIKAT == "") echo $data->NIP; else echo $data->NO_SERTIFIKAT;?></td>
			<td><a href="<?php echo base_url().'universitas/kompilasi/index/'.$this->security->xss_clean($data->KD_DOSEN);?>"><?php echo ucwords(strtolower($data->NM_DOSEN));?></a></td>
			<td align="center"><?php $a = tot_bkd_semester($this->security->xss_clean($data->KD_DOSEN), 'GASAL','A'); echo $a;?></td>
			<td align="center"><?php $b = tot_bkd_semester($this->security->xss_clean($data->KD_DOSEN), 'GASAL','B'); echo $b;?></td>
			<td align="center"><?php $c = tot_bkd_semester($this->security->xss_clean($data->KD_DOSEN), 'GASAL','C'); echo $c;?></td>
			<td align="center"><?php $d = tot_bkd_semester($this->security->xss_clean($data->KD_DOSEN), 'GASAL','D'); echo $d;?></td>
			<td align="center"><?php $a1 = tot_bkd_semester($this->security->xss_clean($data->KD_DOSEN), 'GENAP','A'); echo $a1;?></td>
			<td align="center"><?php $b1 = tot_bkd_semester($this->security->xss_clean($data->KD_DOSEN), 'GENAP','B'); echo $b1;?></td>
			<td align="center"><?php $c1 = tot_bkd_semester($this->security->xss_clean($data->KD_DOSEN), 'GENAP','C'); echo $c1;?></td>
			<td align="center"><?php $d1 = tot_bkd_semester($this->security->xss_clean($data->KD_DOSEN), 'GENAP','D'); echo $d1;?></td>
			<td align="center"><?php $prof_a = tot_bkd_prof($this->security->xss_clean($data->KD_DOSEN), $tahun,'A'); echo $prof_a;?></td>
			<td align="center"><?php $prof_b = tot_bkd_prof($this->security->xss_clean($data->KD_DOSEN), $tahun,'B'); echo $prof_b;?></td>
			<td align="center"><?php $prof_c = tot_bkd_prof($this->security->xss_clean($data->KD_DOSEN), $tahun,'C'); echo $prof_c;?></td>
			<td align="center">
				<?php 
					$tot_1 = $a + $b + $c + $d;
					$tot_2 = $a1 + $b1 + $c1 + $d1;
					if (($a >= 3) && ($tot_1 <= 16)) $st1 = 1; else	$st1 = 0;
					if (($a1 >= 3) && ($tot_2 <= 16)) $st2 = 1;	else $st2 = 0;
					// cek status
					if (($st1 == 1) && ($st2 == 1)){
						echo 'M';
					}else{
						echo 'T';
					}
				?>
			</td>
			<td align="center"></td>
		</tr>
		<?php } 
		} ?>
	</table>
</div>	
	
	</div>
</div>