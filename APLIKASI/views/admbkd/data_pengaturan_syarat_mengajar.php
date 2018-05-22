<style>
	.noborder tr, .noborder td {
		border: none;
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
</style>

<script type="text/javascript" src="<?php echo base_url('/asset/js_select2/select2.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/asset/css_select2/select2.min.css'); ?>">

<script type="text/javascript">
	function get_data(){
		$.ajax({
			url 	: 'get_pengaturan_mengajar',
			type 	: 'GET',

			success : function(result){
				
				var s = JSON.parse(result);
				var j = s.length;

				if(j > 0){
					var row = "";
					var nomor = 1;

					var aktif = "<span class='badge badge-success'> <i class='icon-white icon-ok'></i></span>";
					var unaktif = "<span class='badge badge-important'> <i class='icon-white icon-remove'></i> </span>";

					var status = "";
					var btn = "";

					$("#data-konten").empty();
					$.each(s, function(index, value){
						//console.log('testing');

						if(value.STATUS == 0){
							status = unaktif;
							btn = "<button class='btn btn-success btn-small' onclick='enable("+value.ID_SYARAT+");'> Ubah Status </button>";
						}else{
							status = aktif;
							btn = "<button class='btn btn-danger btn-small' onclick='disable("+value.ID_SYARAT+");'> Ubah Status </button>";
						}

						//console.log(s);

						row += "<tr>";
						row += "<td align='center'>"+nomor+".</td>";
						row += "<td align='center'>"+value.JENIS+"</td>";
						row += "<td align='center'>"+value.BATAS+"</td>";
						row += "<td align='center'>"+value.TGL+"</td>";
						row += "<td align='center'>"+status+"</td>";
						row += "<td align='center'>"+btn+"</td>";
						row += "<tr>";

						$("#data-konten").append(row);
						row = "";
						nomor++;
					});
				}else{
					$("#data-konten").empty();
					$("#data-konten").append("<tr><td colspan='8' align='center'> Tidak Ada Data untuk Ditampilkan ! </td></tr>");
				}
				
			}
		});
	}

	function tambah_data(){
		var jenis 	= $("#jenis").val();
		var status 	= $("#status").val();
		var batas   = $("#batas").val();

		if(batas >= 0){
			$.ajax({
				url 	: 'tambah_pengaturan_mengajar',
				type 	: 'POST',
				data 	: 'jenis='+jenis+'&status='+status+'&batas='+batas,

				success : function(result){
					if(result == 1){
						$("#notice").show();
						$("#notice-field").attr('class', 'alert alert-success');
						$("#notice-txt").html('Batas Minimal Mahasiswa Berhasil Ditambah');
						$("#notice").fadeOut(5000);
					}else{
						$("#notice").show();
						$("#notice-field").attr('class', 'alert alert-danger');
						$("#notice-txt").html('Batas Minimal Mahasiswa Gagal Ditambah !');
						$("#notice").fadeOut(8000);
					}
				}
			});
		}

		$("#batas").val("");
		get_data();
	}

	function enable(id_syarat){
		$.ajax({
			url 	: 'enable_pengaturan_mengajar',
			type 	: 'POST',
			data 	: 'id='+id_syarat,

			success : function(result){
				if(result == 1){
					$("#notice-tbl").show();
					$("#notice-tbl-field").attr('class', 'alert alert-success');
					$("#notice-tbl-txt").html('Batas Minimal Mahasiswa Berhasil di Aktifkan');
					$("#notice-tbl").fadeOut(5000);
				}else{
					$("#notice-tbl").show();
					$("#notice-tbl-field").attr('class', 'alert alert-danger');
					$("#notice-tbl-txt").html('Batas Minimal Mahasiswa Gagal di Aktifkan !');
					$("#notice-tbl").fadeOut(8000);
				}
			}
		});

		get_data();
	}

	function disable(id_syarat){
		$.ajax({
			url 	: 'disable_pengaturan_mengajar',
			type 	: 'POST',
			data 	: 'id='+id_syarat,

			success : function(result){
				if(result == 1){
					$("#notice-tbl").show();
					$("#notice-tbl-field").attr('class', 'alert alert-success');
					$("#notice-tbl-txt").html('Batas Minimal Mahasiswa Berhasil di Non Aktifkan');
					$("#notice-tbl").fadeOut(5000);
				}else{
					$("#notice-tbl").show();
					$("#notice-tbl-field").attr('class', 'alert alert-danger');
					$("#notice-tbl-txt").html('Batas Minimal Mahasiswa Gagal di Non Aktifkan !');
					$("#notice-tbl").fadeOut(8000);
				}
			}
		});

		get_data();
	}

	get_data();
</script>

<script>
	$(document).ready(function() {
		$("#btn-tambah").on('click', function(){
			tambah_data();
		});
	});
</script>

<body>
	<div>
		<div>
			<ul id="crumbs">
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/home">Admin Kinerja Dosen</a></li>
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/home">Pengaturan Poin Remun</a></li>
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/setting/pengaturan_sks">Daftar Pengaturan Batas Mengajar</a></li>
			</ul>
		</div>
		<!-- kategori -->
		<br>
		<div id="sct-kategori">
			<h3>Formulir Penambahan Pengaturan Batas</h3>
			<table class="table noborder">
				<tr>
					<td colspan="2">
						<div class="container" id="notice" hidden>
							<div id="notice-field"><p id="notice-txt"></p></div>
						</div>
					</td>
				</tr>
				<tr>
					<td>
						<table>
							<tr>
								<td style="width: 120px;">Jenis Matakuliah</td>
								<td>
									<select name="jenis" id="jenis">
										<option value="WAJIB"> WAJIB </option>
										<option value="PILIHAN"> PILIHAN </option>
									</select>
								</td>
							</tr>
							<tr>
								<td>Minimal Mahasiswa</td>
								<td>
									<input type="number" name="batas" id="batas" min="0">
								</td>
							</tr>
						</table>
					</td>
					<td>
						<table>
							<tr>
								<td>Status</td>
								<td>
									<select name="status" id="status">
										<option value="0"> TIDAK AKTIF </option>
										<option value="1"> AKTIF </option>
									</select>
								</td>
							</tr>
							<tr>
								<td colspan="2" align="right">
									<button class="btn-uin btn btn-small btn-inverse" id="btn-tambah" name="btn-tambah"> Tambah </button>
								</td>
							</tr>
							
						</table>
					</td>
				</tr>	
			</table>
		</div>
		

		<!-- konten -->
		<br>
		<div id="loading" hidden>
			<div class="loader3">Loading...</div>
		</div>
		<div id="konten">
			<h3 id="judul">Daftar Batas Mengajar Matakuliah Remunerasi</h3>
			<div class="container" id="notice-tbl" hidden>
				<div id="notice-tbl-field"><p id="notice-tbl-txt"></p></div>
			</div>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>No.</th>
						<th>Jenis MK</th>
						<th>Batas Mahasiswa</th>
						<th>Tanggal</th>
						<th>Status</th>
						<th style="width: 90px;">Proses</th>
					</tr>
				</thead>
				<tbody id="data-konten">
					<tr>
						<td colspan="8" align="center"> Tidak Ada Data untuk Ditampilkan ! </td>
					</tr>
				</tbody>

			</table>
		</div>
	</div>
</body>