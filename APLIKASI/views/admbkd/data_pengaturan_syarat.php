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

<script type="text/javascript">

	function get_kategori(){
		var bidang = $("#bidang").val();
		if(bidang != 'X0X'){
			$.ajax({
				url 	: 'get_kategori',
				type 	: 'POST',
				data 	: 'bidang='+bidang,

				success : function(result){
					var kat = JSON.parse(result);
					//console.log(kat);

					$("#kategori").empty();
					$("#kategori").append("<option value='X0X' > -- Pilih -- </option>");

					$.each(kat, function(index, value){
						if(value.KD_KAT != 4){
							$("#kategori").append("<option value='"+value.KD_KAT+"' > "+value.NM_KAT+" </option>");
						}
					});
				}
			});
		}else{
			$("#kategori").empty();
			$("#kategori").append("<option value='X0X' > -- Pilih -- </option>");
		}
	}

	function get_keterangan(){
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setting/get_keterangan_syarat',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				$("#ket-body").html(result);
			}
		});
	}

	get_keterangan();


	function get_syarat(){
		var jenis = $("#jenis").val();
		var bidang = $("#bidang").val();
		var kategori = $("#kategori").val();

		$("#btn-tambah").show();
		$("#btn-simpan").hide();
		$("#nilai").removeAttr('readonly');
		
		if(jenis != 'X0X' && bidang != 'X0X' && kategori != 'X0X'){
			$.ajax({
				url 	: 'get_pengaturan_syarat',
				type 	: 'POST',
				data 	: "jenis="+jenis+"&bidang="+bidang+"&kategori="+kategori,

				beforeSend : function(){
					$("#loading").show();
					$("#konten").hide();
				},

				success : function(result){
					//console.log(JSON.parse(result));
					$("#data-syarat").empty();
					$("#data-syarat").html(result);
					$("#loading").hide();
					$("#konten").show();

					$("#status").empty();
					$("#status").append("<option value='0'>TIDAK AKTIF</option>");
					$("#status").append("<option value='1'>AKTIF</option>");

					$("#tbl_tambah").removeAttr('disabled');
					$("#nilai").val('');
				}
			});
		}else{
			//console.log('clear table, disable form tambahan');
			$("#data-syarat").empty();
			$("#data-syarat").html("<tr><td colspan='8' align='center'>Tidak Ada Data !</td></tr>");
			$("#status").empty();
			$("#tbl_tambah").attr('disabled', 'disabled');

		}
	}

	function get_edit(value){
		$("#btn-tambah").hide();
		$("#btn-simpan").show();
		$.ajax({
			url 	: 'get_edit_syarat',
			type 	: 'POST',
			data 	: 'id_syarat='+value,

			success : function(data){
				var s = JSON.parse(data);
				//console.log(s);

				$("#nilai").val(s.NILAI);
				$("#id_syarat").val(s.ID_SYARAT);
				$("#status").empty();
				if(s.STATUS==1){
					$("#status").append("<option value='0'>TIDAK AKTIF</option>");
					$("#status").append("<option value='1' selected >AKTIF</option>");
				}else{
					$("#status").append("<option value='0' selected >TIDAK AKTIF</option>");
					$("#status").append("<option value='1'>AKTIF</option>");
				}

				$("#nilai").attr('readonly', 'readonly');



			}
		});
	}

	function tambah_syarat(){
		var jenis = $("#jenis").val();
		var bidang = $("#bidang").val();
		var nilai = $("#nilai").val();
		var status = $("#status").val();
		var kategori = $("#kategori").val();

		$.ajax({
			url 	: 'tambah_syarat',
			type 	: 'POST',
			data 	: "jenis="+jenis+"&bidang="+bidang+"&nilai="+nilai+"&status="+status+"&kategori="+kategori,

			beforeSend : function(){
				$("#loading").show();
				$("#konten").hide();
			},

			success : function(result){
				//console.log(result);
				if(result == 1){
					//console.log('notif berhasil');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Syarat Beban Kerja Berhasil Ditambahkan');
					$("#notice").fadeOut(5000);
				}else{
					//console.log('notif gagal');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Syarat Beban Kerja Gagal Ditambahkan !');
					$("#notice").fadeOut(8000);
				}

				get_syarat();
				$("#nilai").val(null);
				$("#loading").hide();
				$("#konten").show();

			}
		});
	}

	function simpan_syarat(){
		var id = $("#id_syarat").val();
		var jenis = $("#jenis").val();
		var bidang = $("#bidang").val();
		var nilai = $("#nilai").val();
		var status = $("#status").val();

		$.ajax({
			url 	: 'update_syarat',
			type 	: 'POST',
			data 	: "id="+id+"&jenis="+jenis+"&bidang="+bidang+"&nilai="+nilai+"&status="+status,

			beforeSend : function(){
				$("#loading").show();
				$("#konten").hide();
			},

			success : function(result){
				//console.log(result);
				if(result == 1){
					//console.log('notif berhasil');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Syarat Beban Kerja Berhasil Diperbaharui');
					$("#notice").fadeOut(5000);
				}else{
					//console.log('notif gagal');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Syarat Beban Kerja Gagal Diperbaharui !');
					$("#notice").fadeOut(8000);
				}

				get_syarat();
				$("#nilai").val(null);
				$("#loading").hide();
				$("#konten").show();

			}
		});
	}

	$(document).ready(function(){
		$("#jenis").on('change', function(){
			get_syarat();
		});

		$("#bidang").on('change', function(){
			get_kategori();
			//get_syarat();
		});

		$("#kategori").on('change', function(){
			get_syarat();
		});

		$("#tbl_batal").on('click', function(){
			get_syarat();
		});

		$("#tbl_tambah").on('click', function(){
			tambah_syarat();
		});

		$("#tbl_simpan").on('click', function(){
			simpan_syarat();
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
		<form method="POST" id="form-rule">
		<table class="table noborder">
			<tr>
				<td colspan="2"><h3>Pengaturan Syarat Beban Kerja</h3></td>
			</tr>

			<tr>
				<td colspan="2">
					<div class="container" id="notice" hidden>
						<div id="notice-field"><p id="notice-txt"></p></div>
					</div>
				</td>
			</tr>
			<tr>
				<td>	
					<table class="table noborder">
							<tr>
								<td style="width: 70px;">Jenis</td>
								<td>
									<select name="jenis" id="jenis">
										<option value="X0X"> -- Pilih -- </option>
										<?php foreach ($dosen as $d): ?>
											<option value="<?php echo $d['KD_JENIS_DOSEN'] ?>"><?php echo $d['NM_JENIS_DOSEN']; ?></option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>

							<tr>
								<td>Bidang</td>
								<td>
									<select name="bidang" id="bidang">
										<option value="X0X"> -- Pilih -- </option>
										<?php foreach ($bidang as $b): ?>
											<option value="<?php echo $b['KD_BIDANG'] ?>"><?php echo $b['NM_BIDANG']; ?></option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>

							<tr>
								<td>Kategori</td>
								<td>
									<select name="kategori" id="kategori">
										<option value="X0X"> -- Pilih -- </option>
									</select>
								</td>
							</tr>
						
					</table>
				</td>
				<td>
					<table>
						<tr hidden>
							<td>
								<input type="text" name="id_syarat" id="id_syarat">
							</td>
						</tr>
						<tr>
							<td>Syarat</td>
							<td>
								<input type="number" name="nilai" id="nilai" step="any" min='0.001'>
							</td>
						</tr>
						<tr>
							<td>Status</td>
							<td>
								<select name="status" id="status">

								</select>
							</td>
						</tr>
						<tr>
			</form>
							<td colspan="2" style="text-align: right; padding-right: 20px;">
								<div id="btn-tambah"><button type="button" class="btn-uin btn btn-small btn-inverse" disabled id="tbl_tambah">Tambah</button></div>
								<div id="btn-simpan" hidden><button type="button" class="btn-uin btn btn-small btn-inverse" style="margin-right: 10px;" id="tbl_simpan">Simpan</button><button type="button" class="btn-uin btn btn-small btn-inverse" id="tbl_batal">Batal</button></div>								
							</td>
						</tr>

					</table>
				</td>
			</tr>
		</table>
		
		
		<div id="loading" hidden>
			<div class="loader3">Loading...</div>
		</div>
		<div id="konten">
			<h3>Data Pengaturan Syarat Beban Kerja Dosen</h3>		
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>No.</th>
						<th>Jenis</th>
						<th>Bidang</th>
						<th>Kategori</th>
						<th>Syarat</th>
						<th>tanggal</th>
						<th>Status</th>
						<th>Proses</th>
					</tr>
				</thead>
				
				<tbody id="data-syarat">
					<tr><td colspan="8" align="center">Tidak Ada Data !</td></tr>
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