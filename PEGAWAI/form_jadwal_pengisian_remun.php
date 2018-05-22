<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src='<?php echo "http://akademik.uin-suka.ac.id/asset/js_jadwal/jquery-3.2.1.js"?>'></script>
    <script type="text/javascript" src='<?php echo "http://akademik.uin-suka.ac.id/asset/js_jadwal/jquery-ui.js"?>'></script>
    <script type="text/javascript" src='<?php echo "http://akademik.uin-suka.ac.id/asset/js_jadwal/jquery.timepicker.min.js"?>'></script>

    <link rel="stylesheet" type="text/css" href='<?php echo "http://akademik.uin-suka.ac.id/asset/js_jadwal/jquery-ui.css"?>'>
    <link rel="stylesheet" type="text/css" href='<?php echo "http://akademik.uin-suka.ac.id/asset/js_jadwal/jquery.timepicker.min.css"?>'>
</head>
<body>
</body>
	<style>
	.noborder tr, .noborder td{
		border: none;
		padding-left: 20px;
	}
</style>

<style type="text/css">
	.loader3:before,
	.loader3:after,
	.loader3 {
	  border-radius: 50%;
	  width: 2.5em;
	  height: 2.5em;
	  -webkit-animation-fill-mode: both;
	  animation-fill-mode: both;
	  -webkit-animation: load7 1.8s infinite ease-in-out;
	  animation: load7 1.8s infinite ease-in-out;
	}
	.loader3 {
	  font-size: 5px;
	  margin: 30px auto;
	  position: relative;
	  text-indent: -9999em;
	  -webkit-transform: translateZ(0);
	  -ms-transform: translateZ(0);
	  transform: translateZ(0);
	  -webkit-animation-delay: -0.16s;
	  animation-delay: -0.16s;
	}
	.loader3:before {
	  left: -3.5em;
	  -webkit-animation-delay: -0.32s;
	  animation-delay: -0.32s;
	}
	.loader3:after {
	  left: 3.5em;
	}
	.loader3:before,
	.loader3:after {
	  content: '';
	  position: absolute;
	  top: 0;
	}
	@-webkit-keyframes load7 {
	  0%,
	  80%,
	  100% {
		box-shadow: 0 2.5em 0 -1.3em #000000;
	  }
	  40% {
		box-shadow: 0 2.5em 0 0 #000000;
	  }
	}
	@keyframes load7 {
	  0%,
	  80%,
	  100% {
		box-shadow: 0 2.5em 0 -1.3em #000000;
	  }
	  40% {
		box-shadow: 0 2.5em 0 0 #000000;
	  }
	}
	.foo {
	  float: left;
	  width: 10px;
	  height: 10px;
	  margin: 5px;
	  border: 1px solid rgba(0, 0, 0, .2);
	  border-radius: 2px;
	}
	.magenta {
	  background: #FFEEBC;
	}
</style>
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
<?php
$crumbs = array(
    array('Admin Remunerasi' => '#'),
    array('Aturan Agregasi' => '/remunerasi_dosen/aturan/aturan_agregasi')
   );

$this->view->output_crumbs($crumbs);
?>
<style>
</style>

<script type="text/javascript">
	function get_jadwal(){
		$("#btn-tambah").show();
		$("#btn-simpan").hide();

		$.ajax({
			url 	: 'get_jadwal_pengisian_remun',
			type 	: 'GET',
			data 	: '',

			beforeSend : function(){
				$("#loading").show();
				$("#konten").hide();
			},

			success : function(result){
				//console.log(JSON.parse(result));
				$("#data-agregasi").empty();
				$("#data-agregasi").html(result);
				$("#loading").hide();
				$("#konten").show();

				/*$("#status").empty();
				$("#status").append("<option value='0'>TIDAK AKTIF</option>");
				$("#status").append("<option value='1'>AKTIF</option>");*/

				$("#tbl_tambah").removeAttr('disabled');
				//fill_jenis();
				$("#tgl_mulai").val(null);
				$("#jam_mulai").val(null);
				$("#tgl_selesai").val(null);
				$("#jam_selesai").val(null);
				/*$("#thn_akademik").val(null);
				$("#semester").val(null);*/

				$("#tgl_mulai").removeAttr('readonly');
				$("#jam_mulai").removeAttr('readonly');
				$("#tgl_selesai").removeAttr('readonly');
				$("#jam_selesai").removeAttr('readonly');
				$("#thn_akademik").removeAttr('readonly');
				$("#semester").removeAttr('readonly');
			}
		});

	}
	function fill_jenis(){
		$.ajax({
			url 	: 'get_status_dosen',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var r = JSON.parse(result);
				console.log(r);

				$("#status_dosen").empty();
				$.each(r, function(idx, val){
					var jenis_dosen
					if(val.status_dosen == 'DT'){
						nama_status_dosen = "DOSEN DENGAN TUGAS TAMBAHAN";
					}else if(val.status_dosen == 'DK'){
						nama_status_dosen = "DOSEN DENGAN TUGAS KHUSUS";
					}else if(val.status_dosen == 'DS'){
						nama_status_dosen = "DOSEN BIASA";
					}
					$("#status_dosen").append("<option value='"+val.status_dosen+"'>"+nama_status_dosen+"</option>");
				});
			}
		});
	}
	function get_edit(value){
		$("#btn-tambah").hide();
		$("#btn-simpan").show();

		$.ajax({
			url 	: 'get_edit_jadwal_pengisian',
			type 	: 'POST',
			data 	: 'id_jadwal='+value,

			beforeSend : function(){
				$("#loading").show();
			},
			success : function(data){
				var b = JSON.parse(data);
				console.log(b);

				var get_tgl_m = b.tgl_mulai;
				var get_tgl_s = b.tgl_selesai;
				var pecah = get_tgl_m.split(' ');
				var tgl_mulai = pecah[0];

				var tg = tgl_mulai.split('-');
				var thn = tg[0];
				var bln = tg[1];
				var hari = tg[2];

				var jam_mulai = pecah[1];
				//16:00:00
				var jm = jam_mulai.split(':');
				var jam = jm[0]+':'+jm[1];

				var grab = get_tgl_s.split(' ');
				var tgl_selesai = grab[0];
				var tl = tgl_selesai.split('-');
				var thns = tl[0];
				var blns = tl[1];
				var haris = tl[2];

				var jam_selesai = grab[1];
				//16:00:00
				var jms = jam_selesai.split(':');
				var jams = jms[0]+':'+jms[1];

				var tgl_mulai_isi = bln+'/'+hari+'/'+thn;
				var tgl_selesai_isi = blns+'/'+haris+'/'+thns;
				//var thn_akademik = b.kd_ta;

				$("#id_jadwal").val(b.id_jadwal);
				$("#tgl_mulai").val(tgl_mulai_isi);
				$("#jam_mulai").val(jam);
				$("#jam_selesai").val(jams);
				$("#tgl_selesai").val(tgl_selesai_isi);
				$("#thn_akademik").val(b.kd_ta);
				$("#semester").empty();
				if(b.kd_smt == 1){
					$("#semester").append("<option value=''>-- Pilih Semester --</option>");
					$("#semester").append("<option value='1' selected>Ganjil</option>");
					$("#semester").append("<option value='2'>Genap</option>");
				}else if(b.kd_smt == 2){
					$("#semester").append("<option value=''>-- Pilih Semester --</option>");
					$("#semester").append("<option value='1'>Ganjil</option>");
					$("#semester").append("<option value='2'  selected>Genap</option>");
				}
				$("#kategori").empty();
				if(b.kategori == 'remun'){
					$("#kategori").append("<option value=''>-- Pilih Kategori BKD --</option>");
					$("#kategori").append("<option value='remun' selected>BKD Remun</option>");
					$("#kategori").append("<option value='sertifikasi'>BKD Sertifikasi</option>");
				}else if(b.kategori == 'sertifikasi'){
					$("#kategori").append("<option value=''>-- Pilih Kategori BKD --</option>");
					$("#kategori").append("<option value='remun'>BKD Remun</option>");
					$("#kategori").append("<option value='sertifikasi' selected>BKD Sertifikasi</option>");
				}
				$("#status").empty();
				if(b.status == '1'){
					$("#status").append("<option value=''>-- Pilih Status --</option>");
					$("#status").append("<option value='1' selected>Aktif</option>");
					$("#status").append("<option value='0'>Tidak Aktif</option>");
				}else if(b.status == '0'){
					$("#status").append("<option value=''>-- Pilih Status --</option>");
					$("#status").append("<option value='1'>Aktif</option>");
					$("#status").append("<option value='0' selected>Tidak Aktif</option>");
				}				
				/*if(b.status_dosen == 'DS'){
					$("#status_dosen").append("<option value='DS' selected>DOSEN BIASA</option>");
					$("#status_dosen").append("<option value='DT'>DOSEN DENGAN TUGAS TAMBAHAN</option>");
					$("#status_dosen").append("<option value='DK'>DOSEN DENGAN TUGAS KHUSUS</option>");
				}else if(b.status_dosen == 'DT'){
					$("#status_dosen").append("<option value='DS'>DOSEN BIASA</option>");
					$("#status_dosen").append("<option value='DT' selected>DOSEN DENGAN TUGAS TAMBAHAN</option>");
					$("#status_dosen").append("<option value='DK'>DOSEN DENGAN TUGAS KHUSUS</option>");
				}else if(b.status_dosen == 'DK'){
					$("#status_dosen").append("<option value='DS'>DOSEN BIASA</option>");
					$("#status_dosen").append("<option value='DT'>DOSEN DENGAN TUGAS TAMBAHAN</option>");
					$("#status_dosen").append("<option value='DK' selected>DOSEN DENGAN TUGAS KHUSUS</option>");
				}*/

				/*$("#status").empty();*/
				/*if(b.status==1){
					$("#status").append("<option value='0'>TIDAK AKTIF</option>");
					$("#status").append("<option value='1' selected >AKTIF</option>");
				}else{
					$("#status").append("<option value='0' selected >TIDAK AKTIF</option>");
					$("#status").append("<option value='1'>AKTIF</option>");
				}*/
				$("#loading").hide();
				/*fill_jenis_edit(b.JENIS);

				$("#jenis").attr('readonly', 'readonly');
				$("#min").attr('readonly', 'readonly');
				$("#max").attr('readonly', 'readonly');*/



			}
		});
	}
	
	function simpan_jadwal(){
		var id_jadwal = $("#id_jadwal").val();
		var tgl_mulai = $("#tgl_mulai").val();
		var tgl = tgl_mulai.split('/');
		var bln = tgl[0];
		var hari = tgl[1];
		var thn = tgl[2];
		var tanggal_m = thn+'-'+bln+'-'+hari;

		var jam_mulai = $("#jam_mulai").val();
		var jm = jam_mulai.split(' ');
		var jam = jm[0];
		var tgl_mulai_isi = tanggal_m+' '+jam;

		var tgl_selesai = $("#tgl_selesai").val();
		var tgl2 = tgl_selesai.split('/');
		var bln2 = tgl2[0];
		var hari2 = tgl2[1];
		var thn2 = tgl2[2];
		var tanggal_s = thn2+'-'+bln2+'-'+hari2;

		var jam_selesai = $("#jam_selesai").val();
		var js = jam_selesai.split(' ');
		var jas = js[0];
		var tgl_selesai_isi = tanggal_s+' '+jas;
		
		var thn_akademik = $("#thn_akademik").val();
		var semester = $("#semester").val();
		var kategori = $("#kategori").val();
		var status = $("#status").val();

		if(thn_akademik == '' && semester == '' && kategori == '' && status == '' && tgl_mulai == '' && jam_mulai == '' && tgl_selesai == '' && jam_selesai == '' || thn_akademik == '' && semester == '' && kategori == '' && status == '' || tgl_mulai == '' && jam_mulai == '' && tgl_selesai == '' && jam_selesai == '' || tgl_mulai == '' || jam_mulai == '' || tgl_selesai == '' || jam_selesai == '' || thn_akademik == '' || semester == '' || kategori == '' ||status == ''){
			alert('Pastikan semua field isian sudah dipilih dan diisi. Tidak boleh ada field isian yang kosong atau belum dipilih.');
		}else{
			$.ajax({
			url 	: 'update_jadwal_pengisian',
			type 	: 'POST',
			data 	: "id_jadwal="+id_jadwal+"&tgl_mulai="+tgl_mulai_isi+"&tgl_selesai="+tgl_selesai_isi+"&thn_akademik="+thn_akademik+"&semester="+semester+"&kategori="+kategori+"&status="+status,

			beforeSend : function(){
				$("#loading").show();
				$("#konten").hide();
			},

			success : function(result){
				console.log(result);
				console.log(id_jadwal);
				console.log(tgl_mulai_isi);
				console.log(tgl_selesai_isi);
				console.log(thn_akademik);
				console.log(semester);

				if(result){
					//console.log('notif berhasil');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Jadwal Pengisian Remun Berhasil Diperbaharui');
					$("#notice").fadeOut(5000);
				}else{
					//console.log('notif gagal');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Jadwal Pengisian Remun Gagal Diperbaharui !');
					$("#notice").fadeOut(8000);
				}

				get_jadwal();
				$("#id_jadwal").val(null);
				$("#tgl_mulai").val(null);
				$("#tgl_selesai").val(null);
				$("#jam_mulai").val(null);
				$("#jam_selesai").val(null);
				/*$("#thn_akademik").val(null);
				$("#semester").val(null);*/
				$("#semester").empty();
				$("#semester").append("<option value=''>-- Pilih Semester --</option>");
				$("#semester").append("<option value='1'>Ganjil</option>");
				$("#semester").append("<option value='2'>Genap</option>");
				$("#kategori").empty();
				$("#kategori").append("<option value=''>-- Pilih Kategori BKD --</option>");
				$("#kategori").append("<option value='remun'>BKD Remun</option>");
				$("#kategori").append("<option value='sertifikasi'>BKD Sertifikasi</option>");
				$("#status").empty();
				$("#status").append("<option value=''>Pilih Status</option>");
				$("#status").append("<option value='1'>Aktif</option>");
				$("#status").append("<option value='0'>Tidak Aktif</option>");

				$("#loading").hide();
				$("#konten").show();

			}
		});
		}
	}
	function tambah_jadwal(){
		var tgl_mulai = $("#tgl_mulai").val();
		var jam_mulai = $("#jam_mulai").val();
		var tgl_selesai = $("#tgl_selesai").val();
		var jam_selesai = $("#jam_selesai").val();
		var thn_akademik = $("#thn_akademik").val();
		var semester = $("#semester").val();
		var kategori = $("#kategori").val();
		var status = $("#status").val();

		if(thn_akademik == '' && semester == '' && kategori == '' && status == '' && tgl_mulai == '' && jam_mulai == '' && tgl_selesai == '' && jam_selesai == '' || thn_akademik == '' && semester == '' && kategori == '' && status == '' || tgl_mulai == '' && jam_mulai == '' && tgl_selesai == '' && jam_selesai == '' || tgl_mulai == '' || jam_mulai == '' || tgl_selesai == '' || jam_selesai == '' || thn_akademik == '' || semester == '' || kategori == '' ||status == ''){
			alert('Pastikan semua field isian sudah dipilih dan diisi. Tidak boleh ada field isian yang kosong atau belum dipilih.');
		}else{
			$.ajax({
			url 	: 'tambah_jadwal_pengisian',
			type 	: 'POST',
			data 	: "tgl_mulai="+tgl_mulai+"&jam_mulai="+jam_mulai+"&tgl_selesai="+tgl_selesai+"&jam_selesai="+jam_selesai+"&thn_akademik="+thn_akademik+"&semester="+semester+"&kategori="+kategori+"&status="+status,

			beforeSend : function(){
				$("#loading").show();
				$("#konten").hide();
			},

			success : function(result){
				console.log(result);
				console.log(kategori);
				console.log(status);
				if(result){
					/*console.log('notif berhasil');*/
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Jadwal Pengisian Remun Berhasil Ditambahkan');
					$("#notice").fadeOut(5000);
				}else{
					//console.log('notif gagal');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Jadwal Pengisian Remun Gagal Ditambahkan !');
					$("#notice").fadeOut(8000);
				}

				get_jadwal();

				$("#id_jadwal").val(null);
				$("#tgl_mulai").val(null);
				$("#jam_mulai").val(null);
				$("tgl_selesai").val(null);
				$("jam_selesai").val(null);

				$("#semester").empty();
				$("#semester").append("<option value=''>-- Pilih Semester --</option>");
				$("#semester").append("<option value='1'>Ganjil</option>");
				$("#semester").append("<option value='2'>Genap</option>");
				$("#kategori").empty();
				$("#kategori").append("<option value=''>-- Pilih Kategori BKD --</option>");
				$("#kategori").append("<option value='remun'>BKD Remun</option>");
				$("#kategori").append("<option value='sertifikasi'>BKD Sertifikasi</option>");
				$("#status").empty();
				$("#status").append("<option value=''>-- Pilih Status --</option>");
				$("#status").append("<option value='1'>Aktif</option>");
				$("#status").append("<option value='0'>Tidak Aktif</option>");

				$("#loading").hide();
				$("#konten").show();

			}
		});
		}
	}
	function get_keterangan(){
		$.ajax({
			url 	: 'get_keterangan_jadwal',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				$("#ket-body").html(result);
			}
		});
	}
	get_keterangan();
	$(document).ready(function(){

		get_jadwal();

		$("#tbl_batal").on('click', function(){
			get_jadwal();
		});

		$("#tbl_tambah").on('click', function(){
			tambah_jadwal();
		});

		$("#tbl_simpan").on('click', function(){
			simpan_jadwal();
			// simpan_jadwal2();
		});
	});
</script>
 <script type="text/javascript">
        $(document).ready(function(){
            $('.input-tanggal').datepicker();
        });
        $(document).ready(function(){
            $('.timepicker').timepicker({
            	use24hours: true,
                timeFormat: 'HH:mm',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });
        });
    </script>
<br><br>
<table>
	<tr>
		<td colspan="2">
			<div class="container" id="notice" hidden>
				<div id="notice-field"><p id="notice-txt"></p></div>
			</div>
		</td>
	</tr>
</table>
<form method="POST" id="form-rule">
	<table class= "table noborder" width="100%">
		<tr>
			<td colspan="6"><h3>Form Jadwal Pengisian BKD Sertifikasi Dosen dan BKD Remun</h3></td>
		</tr>
		<tr>
			<td colspan="6"></td>
		</tr>
		<tr hidden>
			<td>
				<input type="text" name="id_jadwal" id="id_jadwal">
			</td>
		</tr>
		<tr>
			<td>Tanggal Mulai</td>
			<td><input type="text" name="tgl_mulai" id="tgl_mulai" class="input-tanggal"></td>

			<td>Jam Mulai</td>
			<td><input type="text" name="jam_mulai" id="jam_mulai" class="timepicker"></td>
		</tr>
		<tr>
			<td>Tanggal Selesai</td>
			<td><input type="text" name="tgl_selesai" id="tgl_selesai" class="input-tanggal"></td>

			<td>Jam Selesai</td>
			<td><input type="text" name="jam_selesai" id="jam_selesai" class="timepicker"></td>
		</tr>
		<tr>
			<td>Tahun Akademik</td>
			<td>
				<select name="thn_akademik" id="thn_akademik">
					<option value="">-- Pilih Tahun Akademik --</option>
					<?php
						$now = date('Y');
						for ($a=$now; $a>=$now-5; $a--) {
							$b = $a+1; $tahun = $a.'/'.$b;?>
								<option value="<?php echo $a;?>"><?php echo $tahun;?></option>	
						<?php }
					?>
				</select>
			</td>
			<td>Semester</td>
			<td>
				<select name="semester" id="semester">
					<option value="">-- Pilih Semester --</option>
					<option value="1">Ganjil</option>
					<option value="2">Genap</option>
				</select>
			</td>
		</tr>
		<tr>
			<td>Kategori</td>
			<td>
				<select name="kategori" id="kategori">
					<option value="">-- Pilih Kategori BKD --</option>
					<option value="remun">BKD Remun</option>
					<option value="sertifikasi">BKD Sertifikasi</option>
				</select>
			</td>
			<td>Status</td>
			<td>
				<select name="status" id="status">
					<option value="">-- Pilih Status --</option>
					<option value="1">Aktif</option>
					<option value="0">Tidak Aktif</option>
				</select>
			</td>
		</tr>
		<tr>
			<td colspan="6" style="text-align: right; padding-right: 20px;">
				<div id="btn-tambah"><button type="button" class="btn-uin btn btn-small btn-inverse" id="tbl_tambah">Tambah</button></div>
				<div id="btn-simpan" hidden><button type="button" class="btn-uin btn btn-small btn-inverse" style="margin-right: 10px;" id="tbl_simpan">Simpan</button><button type="button" class="btn-uin btn btn-small btn-inverse" id="tbl_batal">Batal</button></div>								
			</td>
		</tr>
	</table>
</form>
<br/>

<div id="loading" hidden>
			<div class="loader3">Loading...</div>
		</div>

<h3>Data Riwayat Pengisian Jadwal BKD Sertifikasi Dosen dan BKD Remun</h3>
<div class="table-responsive" id="konten">
	<table class="table table-bordered">
	<thead>
		<tr>
			<th>No.</th>
			<th>Tanggal Mulai</th>
			<th>Tanggal Selesai</th>
			<th>Tahun Akademik</th>
			<th>Semester</th>
			<th>Kategori</th>
			<th>Status</th>
			<th>Proses</th>
		</tr>
	</thead>
	<tbody id="data-agregasi">
					<tr><td colspan="8" align="center">Tidak Ada Data !</td></tr>
				</tbody>
</table>
</div>
<div>
			<strong>Keterangan</strong>
			<table style="margin: 5px 0 30px 0;">
				<tbody id="ket-body">
					
				</tbody>
			</table>
		</div>
</body>
</html>



