<?php if(!empty($pesan)){ echo '<div class="bs-callout bs-callout-error">'.$pesan.'</div>'; }else{ ?>
<?php
	function TanggalIndo($date){
							$BulanIndo = array("Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember");
						 
							$tahun = substr($date, 0, 4);
							$bulan = substr($date, 5, 2);
							$tgl   = substr($date, 8, 2);
						 	/*date_default_timezone_set("Asia/Jakarta");*/
							$result = $tgl . " " . $BulanIndo[(int)$bulan-1] . " ". $tahun/*." ".date('h:i:s a')*/;		
							return($result);
						}
	?>
<div id="content">
	<div>
		<?php 
		$text = 'Sistem Kinerja Dosen <span style="">SEMESTER '.$smt.'</span>, Tahun Akademik <span style="">'.$ta.'</span>';
		$arr1 = array(	'app_text' 	=> $text, 
						'app_name' 	=> 'Kinerja Dosen', 
						'app_url'	=> 'dosen');

		$this->s00_lib_output->output_frontpage_dsn($arr1); ?>
	</div>
	<?php
		#format tanggal jadwal BKD SERTIFIKASI
		$tgl_mulai = $jadwal_sertifikasi->tgl_mulai;
		$pecah = explode(" ", $tgl_mulai);
		$tgl_tata = $pecah[0];
		
		$jam_m = $pecah[1];
		$pecah_jam = substr($jam_m, 0, 5);
		$jam_tata = $pecah_jam.' WIB';
		
		$tgl_selesai = $jadwal_sertifikasi->tgl_selesai;
		$bagi = explode(" ", $tgl_selesai);
		$tgl_s_tata = $bagi[0];

		$jam_s = $bagi[1];
		$bagi_jam = substr($jam_s, 0, 5);
		$jam_s_tata = $bagi_jam.' WIB';

		#format tanggal jadwal BKD REMUN
		$tgl_mulai_remun = $jadwal_remun->tgl_mulai;
		$pecah_remun = explode(" ", $tgl_mulai_remun);
		$tgl_tata_remun = $pecah_remun[0];

		$jam_m_remun = $pecah_remun[1];
		$pecah_jam_remun = substr($jam_m_remun, 0, 5);
		$jam_remun_tata = $pecah_jam_remun.' WIB';

		$tgl_selesai_remun = $jadwal_remun->tgl_selesai;
		$bagi_remun = explode(" ", $tgl_selesai_remun);
		$tgl_s_tata_remun = $bagi_remun[0];

		$jam_s_remun = $bagi_remun[1];
		$bagi_jam_remun = substr($jam_s_remun, 0, 5);
		$jam_s_tata_remun = $bagi_jam_remun.' WIB';

	?>
	<?php
		$tgl_skrng = date('Y-m-d H:i:s');
		$tgl_mulai_pengisian_bkd_serdos = date('Y-m-d H:i:s', strtotime($tgl_mulai));
		$tgl_selesai_pengisian_bkd_serdos = date('Y-m-d H:i:s', strtotime($tgl_selesai));

		$tgl_mulai_pengisian_bkd_remun = date('Y-m-d H:i:s', strtotime($tgl_mulai_remun));
		$tgl_selesai_pengisian_bkd_remun = date('Y-m-d H:i:s', strtotime($tgl_selesai_remun));

		if($tgl_skrng >= $tgl_mulai_pengisian_bkd_serdos AND $tgl_skrng<=$tgl_selesai_pengisian_bkd_serdos || $tgl_skrng >= $tgl_mulai_pengisian_bkd_remun AND $tgl_skrng<=$tgl_selesai_pengisian_bkd_remun){?>
			<div class='bs-callout bs-callout-success' style="display : none;"><p><b><?php echo $namaLengkap[0]->NM_PGW_F?></b>, Saat ini anda sudah dapat melakukan pengisian BKD Sertifikasi Dosen dan BKD Remun</p></div>
		<?php }else{?>
			<div class="bs-callout bs-callout-error" style="display:none;"><p>Mohon Maaf <b><?php echo $namaLengkap[0]->NM_PGW_F?></b>, Anda belum dapat melakukan Pengisian BKD Sertifikasi Dosen dan BKD Remun, karena ada syarat-syarat yang belum terpenuhi, sebagai berikut :</p></div>
		<?php }
	?>
	

	<h2 style="display: none;">Syarat Pengisian BKD Sertifikasi Dosen dan BKD Remun SEMESTER <?php echo $smt;?>, Tahun Akademik <?php echo $ta;?></h2>
	<table class="table table-bordered" style="display: none;">
		<thead>
			<tr>
				<th>No.</th>
				<th>Jenis</th>
				<th>Syarat</th>
				<th>Isi</th>
				<th>Hubungi</th>
				<th>Status</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>1.</td>
				<td>BKD Sertifikasi Dosen</td>
				<td><?php echo "Tanggal Pengisian : ".TanggalIndo($tgl_tata).' '.$jam_tata.' s/d '.TanggalIndo($tgl_s_tata).' '.$jam_s_tata?></td>
				<td><?php echo TanggalIndo(date('Y-m-d'));?></td>
				<td>Petugas PTIPD</td>
				<td></td>
			</tr>
			<tr>
				<td>2.</td>
				<td>BKD Remunerasi Dosen</td>
				<td><?php echo "Tanggal Pengisian : ".TanggalIndo($tgl_tata_remun)." ".$jam_remun_tata.' s/d '.TanggalIndo($tgl_s_tata_remun).' '.$jam_s_tata_remun?></td>
				<td><?php echo TanggalIndo(date('Y-m-d'));?></td>
				<td>Petugas PTIPD</td>
				<td></td>
			</tr>
			
		</tbody>
	</table>
</div>
<style>
	#message{
		width:60%;
		position:fixed;
		left:20%;
		margin:auto;
		text-align:center;
		background-color:#FF0000;
		color:#FFF;
		border:solid 1px #FFF;
		padding:5px;
		z-index:100;
	}
</style>
<?php } ?>