<?php $this->load->view('fungsi');?>
<!-- <script type="text/javascript" src="http://surat.uin-suka.ac.id/asset/js/jquery.tokeninput.js"></script> -->
<?php
	function TanggalIndo($date){
							$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
						 
							$tahun = substr($date, 6, 4);
							$bulan = substr($date, 3, 2);
							$tgl   = substr($date, 0, 2);
						 	/*date_default_timezone_set("Asia/Jakarta");*/
							$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun/*." ".date('h:i:s a')*/;		
							return($result);
						}
	?>
<div id="content">
<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/dosen/verifikator';?>">Verifikator Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/verifikator/dosen';?>">Rencana dan Kinerja</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/verifikator/dosen/'.$this->uri->segment(5);?>">Data Dosen</a></li>
	</ul>
	
	<?php
    $a = $this->session->flashdata('msg');
    if($a!=null){?>
    	<br>
        <div class="alert alert-<?php echo $a[0]?> alert-msg">
            <?php echo $a[1]?>
        </div>
    <?php }
?>
	<br/>
	<?php
	if(!empty($nira)){?>
		<h3>Asesor Sertifikasi Dosen</h3>
		<br>
		<h4>Daftar Dosen yang di Asesori</h4>
		<br>
		<div>
			<table class="table table-bordered table-hover">
				<tr>
					<th>No</th>
					<th>NIP</th>
					<th>Nama</th>
					<th>Status Dosen</th>
					<th>Verifikator</th>
					<th>Tgl Verifikasi</th>
					<th>Status Veifikasi</th>
					<th>Aksi</th>
				</tr>
				<?php
					if(!empty($data_dosen)){?>
						<?php
							$no = 1;
							foreach ($data_dosen as $dd) {?>
								<tr>
									<td align="center"><?php echo $no;?>.</td>
									<td><?php echo $dd['KD_DOSEN'];?></td>
									<td><?php echo $dd['NM_DOSEN'];?></td>
									<td align="center"><?php echo $dd['STATUS'];?></td>
									<td><?php echo $dd['VERIFIKATOR'];?></td>
									<?php
										if($dd['TGL_VERIFIKASI'] == '-'){
											$tgl_verivikasi = $dd['TGL_VERIFIKASI'];
										}else{
											$tgl_verivikasi = TanggalIndo($dd['TGL_VERIFIKASI']);
										}
									?>
									<td><?php echo $tgl_verivikasi;?></td>
									<td align="center"><?php echo $dd['STATUS_VERIFIKASI'];?></td>
									<td align='center'><span class='btn-group'><a class='btn btn-small' href='<?php echo base_url().'bkd/dosen/verifikator/daftar_sertifikasi/'. $dd['KD_DOSEN']?>' target="blank"><i class='icon-book'></i> BKD</a></span></td>
								</tr>
							<?php $no++;}
						?>
					<?php }else{?>
						<tr>
							<td colspan="8">Belum ada data dosen</td>
						</tr>
					<?php }
				?>
				
			</table>
		</div>
	<?php }else{?>
		<h4>Anda tidak termasuk verifikator sertifikasi Dosen</h4>
	<?php }
	?>
	<?php
		if(!empty($data_dosen)){
			echo "<strong>Keterangan</strong>";
		
			echo "<table style='margin: 5px 0 30px 0;''>";
				echo "<tr>";
					
					echo "<td>";
						echo "<table style='margin: 5px 0 30px 0;''>
								<tbody>";
						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Status Verifikasi :</b></td></tr>";
						
						
						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "
						<tr>
							<td style='padding-left: 20px;'><span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span></td>
							<td> &nbsp; : Belum diverifikasi</b></td>
						</tr>
						";

						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "
						<tr>
							<td style='padding-left: 20px;'><span class='badge badge-warning'> <i class='icon-white icon-info'></i></span></td>
							<td> &nbsp; : Sudah diverifikasi tetapi masih ada yang perlu diperbaiki</b></td>
						</tr>
						";

						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "
						<tr>
							<td style='padding-left: 20px;'><span class='badge badge-success'> <i class='icon-white icon-ok'></i></span></td>
							<td> &nbsp; : Sudah diverifikasi dan sudah selesai</b></td>
						</tr>
						";
						echo "</tbody>
							</table>";
					echo "</td>";
					echo "<td>";
						echo "<table style='margin: 5px 0 30px 0;''>";
						echo	"<tbody>";
						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "<tr><td colspan='2' style='padding-left: 20px;'><b>Status Dosen :</b></td></tr>";
						echo "<tr><td colspan='2' style='height:10px;'></td></tr>";
						echo "
						<tr>
							<td style='padding-left: 20px;'>DS</td>
							<td> &nbsp; : Dosen Biasa</b></td>
						</tr>
						<tr>
							<td style='padding-left: 20px;'>DT</td>
							<td> &nbsp; : Dosen dengan Tugas Tambahan</b></td>
						</tr>
						<tr>
							<td style='padding-left: 20px;'>DK</td>
							<td> &nbsp; : Dosen dengan Tugas Khusus</b></td>
						</tr>
						";
						echo 	"</tbody>";
						echo "</table>";
					echo "</td>";
				echo "</tr>
			</table>";
		}
	?>

</div>
</div>

	<script type="text/javascript" charset="utf-8">
      $(function(){
        setTimeout('closing_msg()', 5000)
      })

      function closing_msg(){
        $(".alert-msg").slideUp();
      }
    </script>

