<style>
	.noborder tr, .noborder td{
		border: none;
		padding-left: 20px;
	}

	.foo {
	  float: left;
	  width: 10px;
	  height: 10px;
	  margin: 5px;
	  border: 1px solid rgba(0, 0, 0, .2);
	  border-radius: 2px;
	}

	.cyan {
	  background: #d9edf7;
	}

	.green {
		background: #ffee93;
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
		$("#loading").show();
		$("#konten").hide();
		$.ajax({
			url 	: 'get_data_konversi',
			type 	: 'GET',

			success : function(result){
				var konversi = JSON.parse(result);
				//console.log(konversi);

				var nomor = 1;
				var row = "";
				var jalur = "";
				var css = "";
				$("#data-konversi").empty();
				$.each(konversi, function(index, value){

					if(value.JALUR == 0 ){
						jalur = "Sertifikasi";
						css = 'info';
					}else{
						jalur = "Remunerasi";
						css = 'warning';
					}

					row += "<tr class='"+css+"'>";
					row += "<td align='center'>"+nomor+".</td>";
					row += "<td>"+value.NM_KAT_SERDOS+"</td>";
					row += "<td>"+value.NM_KAT_REMUN+"</td>";
					row += "<td>"+jalur+"</td>";
					row += "<td><a class='btn btn-mini' value='"+value.KD_SERDOS+"' onclick='pindah_konversi("+value.KD_SERDOS+")'><span class='icon-arrow-right'></span> Pindah</a></td>";
					row += "</tr>";

					$("#data-konversi").append(row);
					row = "";
					nomor++;
				});

				$("#loading").hide();
				$("#konten").show();

			}
		});
	}


	function pindah_konversi(val){
		var kd_kat = val;
		$.ajax({
			url 	: 'pindah_jalur_kat',
			type 	: 'POST',
			data 	: 'kd_kat='+kd_kat,

			success : function(result){
				console.log(result);
				if(result == 1){
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Jalur data berhasil dirubah');
					$("#notice").fadeOut(5000);
					get_konversi();
				}else{
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Jalur data gagal dirubah !');
					$("#notice").fadeOut(8000);
				}
			}	
		});

	}

	get_konversi();

	$(document).ready(function(){		

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
		<div id="loading" hidden>
			<div class="loader3">Loading...</div>
		</div>
					
		<div id="konten">
			<h3>Data Pengaturan Jalur Data</h3>
			<div class="container" id="notice" hidden>
				<div id="notice-field"><p id="notice-txt"></p></div>
			</div>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th rowspan="2" style="vertical-align: middle;">No.</th>
						<th colspan="2">Kategori</th>
						<th rowspan="2" style="vertical-align: middle;">Jalur</th>
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
					<tr>
						<td style="width: 10px;"><div class="foo cyan"></div></td>
						<td style="padding-left: 0px;">: Sertifikasi</td>
						<td style="width: 10px; padding-left: 20px;"><div class="foo green"></div></td>
						<td style="padding-left: 0px;">: Remunerasi</td>
					</tr>

					<tr>
						
					</tr>
				</tbody>
			</table>
		</div>
	</div>

</body>