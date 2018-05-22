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
</style>

<script type="text/javascript" src="<?php echo base_url('/asset/js_select2/select2.min.js'); ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url('/asset/css_select2/select2.min.css'); ?>">

<script type="text/javascript">

	function get_konversi(){
		$.ajax({
			url 	: 'get_data_konversi',
			type 	: 'GET',

			success : function(result){
				var konversi = JSON.parse(result);
				//console.log(konversi);

				var nomor = 1;
				var row = "";
				$("#data-konversi").empty();

				$.each(konversi, function(index, value){
					row += "<tr>";
					row += "<td align='center'>"+nomor+".</td>";
					row += "<td>"+value.NM_KAT_SERDOS+"</td>";
					row += "<td>"+value.NM_KAT_REMUN+"</td>";
					row += "<td><a class='btn btn-mini' value='"+value.KD_SERDOS+"' onclick='hapus_konversi("+value.KD_SERDOS+");'><i class='icon-trash'></i> hapus</a></td>";
					row += "</tr>";

					$("#data-konversi").append(row);
					row = "";
					nomor++;
				});

			}
		});
	}

	function get_kat_serdos(){
		$.ajax({
			url 	: 'get_kat_serdos',
			type 	: 'GET',

			success : function(result){
				var serdos = JSON.parse(result);
				console.log(serdos);
				$("#serdos").empty();
				$("#serdos").append("<option value='X0X'>-- Pilih --</option>");
				$.each(serdos, function(index, value){
					$("#serdos").append("<option value='"+value.KD_KAT+"'>"+value.NM_KAT+"</option>");
				});
			}
		});
	}

	function get_kat_remun(){
		$.ajax({
			url 	: 'get_kat_remun',
			type 	: 'GET',

			success : function(result){
				var remun = JSON.parse(result);
				//console.log(remun);
				$("#remun").empty();
				$("#remun").append("<option value='X0X'>-- Pilih --</option>");
				$.each(remun, function(index, value){
					$("#remun").append("<option value='"+value.KD_KAT+"'>"+value.NM_KAT+"</option>");
				});
			}	
		});
	}

	function hapus_konversi(val){
		var kd_kat = val;
		$.ajax({
			url 	: 'delete_data_konversi',
			type 	: 'POST',
			data 	: 'kd_kat='+kd_kat,

			success : function(result){
				//console.log(JSON.parse(result));
				if(result == 1){
					$("#notice").show();
						$("#notice-field").attr('class', 'alert alert-success');
						$("#notice-txt").html('Data Konversi Kategori Berhasil Dihapus');
						$("#notice").fadeOut(5000);
				}else{
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Data Konversi Kategori Gagal Dihapus !');
					$("#notice").fadeOut(8000);
				}
			}	
		});

		get_konversi();
		get_kat_serdos();
		get_kat_remun();

	}

	function tambah_konversi(){
		var kd_serdos = $("#serdos").val();
		var kd_remun  = $("#remun").val();

		if(kd_serdos != 'X0X' && kd_remun != 'X0X'){

			$.ajax({
				url 	: 'tambah_data_konversi',
				type 	: 'POST',
				data 	: 'kd_serdos='+kd_serdos+'&kd_remun='+kd_remun,

				success : function(result){
					console.log(JSON.parse(result));
					if(result == 1){
						$("#notice").show();
						$("#notice-field").attr('class', 'alert alert-success');
						$("#notice-txt").html('Data Konversi Kategori Berhasil Ditambahkan');
						$("#notice").fadeOut(5000);
					}else{
						$("#notice").show();
						$("#notice-field").attr('class', 'alert alert-danger');
						$("#notice-txt").html('Data Konversi Kategori Gagal Ditambahkan !');
						$("#notice").fadeOut(8000);
					}

					
				}	
			});

		}else{
			$("#notice").show();
			$("#notice-field").attr('class', 'alert alert-danger');
			$("#notice-txt").html('Data Konversi Kategori Gagal Ditambahkan !');
			$("#notice").fadeOut(8000);
		}

		get_konversi();
		get_kat_serdos();
		get_kat_remun();
	}



	$(document).ready(function(){

		get_konversi();
		get_kat_serdos();
		get_kat_remun();

		$("#serdos").select2({
				});

		$("#remun").select2({
				});

		$("#tbl_batal").on('click', function(){
			get_batas();
		});

		$("#btn-tambah").on('click', function(){
			tambah_konversi();
		});

		$("#tbl_simpan").on('click', function(){
			simpan_batas();
		});
	});

</script>
<body>
	<div>
		<div>
			<ul id="crumbs">
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/home">Admin Kinerja Dosen</a></li>
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/home">Pengaturan</a></li>
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/setting/pengaturan_syarat">Pengaturan Syarat Beban Kerja</a></li>
			</ul>
		</div>
	
		<br>
		<h3>Pengaturan Konversi Kategori</h3>
		<form method="POST" id="form-rule">
			<table class="table noborder">
					<tr>
						<td colspan="2">
							<div class="container" id="notice" hidden>
								<div id="notice-field"><p id="notice-txt"></p></div>
							</div>
						</td>
					</tr>
					<tr>
						<td style="width: 200px;">Kategori Sertifikasi Dosen</td>
						<td >
							<select name="serdos" id="serdos" style="width: 500px;">
								<option value="X0X"> -- Pilih -- </option>
							</select>
						</td>
					</tr>

		
					<tr>
						<td>Kategori Remunerasi Dosen</td>
						<td>
							<select name="remun" id="remun" style="width: 500px;">
								<option value="X0X"> -- Pilih -- </option>
							</select>
						</td>							
					</tr>
					<tr>
						<td align="right" colspan="2">
							<button class="btn-uin btn btn-small btn-inverse" id="btn-tambah">Tambah</button>
						</td>
					</tr>					
			</table>
	</form>
		
		
		<div id="loading" hidden>
			<div class="loader3">Loading...</div>
		</div>
		<div id="konten">
			<h3>Data Pengaturan Konversi</h3>		
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th rowspan="2" style="vertical-align: middle;">No.</th>
						<th colspan="2">Kategori</th>
						<th rowspan="2" style="vertical-align: middle;">Proses</th>
					</tr>
					<tr>
						<th>Sertifikasi</th>
						<th>remunerasi</th>
					</tr>
				</thead>
				
				<tbody id="data-konversi">
					<tr><td colspan="7" align="center">Tidak Ada Data !</td></tr>
				</tbody>
				

			</table>
		</div>
		<div>
			<strong>Keterangan</strong>
			<table style="margin: 5px 0 30px 0;">
				<tbody id="ket-body">
					
				</tbody>
				<!-- <tbody>
					<tr><td colspan="2" style="height:10px;"></td></tr>
					<tr>
						<td colspan="2" style="padding-left: 20px;"><b>Mode :</b></td>
					</tr>
					<tr><td colspan="2" style="height:10px;"></td></tr>
					<tr>
						<td style=""><span class="badge badge-info"><i class="icon-white icon-info"></i></span></td>
						<td style="">&nbsp; : Informasi Data SKPI.</td>
					</tr>
				</tbody> -->
			</table>
		</div>
	</div>

</body>