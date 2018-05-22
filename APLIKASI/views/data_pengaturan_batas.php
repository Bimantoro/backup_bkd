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


	function get_batas(){
		$("#btn-tambah").show();
		$("#btn-simpan").hide();
		
		$.ajax({
			url 	: 'get_pengaturan_batas',
			type 	: 'GET',
			data 	: '',

			beforeSend : function(){
				$("#loading").show();
				$("#konten").hide();
			},

			success : function(result){
				//console.log(JSON.parse(result));
				$("#data-batas").empty();
				$("#data-batas").html(result);
				$("#loading").hide();
				$("#konten").show();

				$("#status").empty();
				$("#status").append("<option value='0'>TIDAK AKTIF</option>");
				$("#status").append("<option value='1'>AKTIF</option>");

				$("#tbl_tambah").removeAttr('disabled');
				fill_jenis();
				$("#max").val(null);
				$("#min").val(null);

				$("#jenis").removeAttr('readonly');
				$("#min").removeAttr('readonly');
				$("#max").removeAttr('readonly');
			}
		});
	}

	function fill_jenis(){
		$.ajax({
			url 	: 'get_jenis_dosen',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var r = JSON.parse(result);
				console.log(r);

				$("#jenis").empty();
				$.each(r, function(idx, val){
					$("#jenis").append("<option value='"+val.KD_JENIS_DOSEN+"'>"+val.NM_JENIS_DOSEN+"</option>");
				});
			}
		});
	}

	function get_edit(value){
		$("#btn-tambah").hide();
		$("#btn-simpan").show();

		$.ajax({
			url 	: 'get_edit_batas',
			type 	: 'POST',
			data 	: 'id_syarat='+value,

			success : function(data){
				var b = JSON.parse(data);
				console.log(b);

				$("#max").val(b.MAX);
				$("#min").val(b.MIN);
				$("#id_batas").val(b.ID_SYARAT);
				$("#status").empty();
				if(b.STATUS==1){
					$("#status").append("<option value='0'>TIDAK AKTIF</option>");
					$("#status").append("<option value='1' selected >AKTIF</option>");
				}else{
					$("#status").append("<option value='0' selected >TIDAK AKTIF</option>");
					$("#status").append("<option value='1'>AKTIF</option>");
				}

				fill_jenis_edit(b.JENIS);

				$("#jenis").attr('readonly', 'readonly');
				$("#min").attr('readonly', 'readonly');
				$("#max").attr('readonly', 'readonly');



			}
		});
	}

	function fill_jenis_edit(jenis){
		var j = jenis;
		$.ajax({
			url 	: 'get_jenis_dosen',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var r = JSON.parse(result);
				$("#jenis").empty();
				$.each(r, function(idx, val){
					if(j == val.KD_JENIS_DOSEN){
						$("#jenis").append("<option value='"+val.KD_JENIS_DOSEN+"' selected>"+val.NM_JENIS_DOSEN+"</option>");
					}else{
						$("#jenis").append("<option value='"+val.KD_JENIS_DOSEN+"'>"+val.NM_JENIS_DOSEN+"</option>");
					}
				});
			}
		});
	}

	function tambah_batas(){
		var jenis = $("#jenis").val();
		var max = $("#max").val();
		var min = $("#min").val();
		var status = $("#status").val();

		$.ajax({
			url 	: 'tambah_batas',
			type 	: 'POST',
			data 	: "jenis="+jenis+"&max="+max+"&min="+min+"&status="+status,

			beforeSend : function(){
				$("#loading").show();
				$("#konten").hide();
			},

			success : function(result){
				console.log(result);
				if(result == 1){
					//console.log('notif berhasil');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Batas Beban Kerja Berhasil Ditambahkan');
					$("#notice").fadeOut(5000);
				}else{
					//console.log('notif gagal');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Batas Beban Kerja Gagal Ditambahkan !');
					$("#notice").fadeOut(8000);
				}

				get_batas();
				$("#max").val(null);
				$("#min").val(null);
				$("#loading").hide();
				$("#konten").show();

			}
		});
	}

	function simpan_batas(){
		var id = $("#id_batas").val();
		var jenis = $("#jenis").val();
		var max = $("#max").val();
		var min = $("#min").val();
		var status = $("#status").val();

		$.ajax({
			url 	: 'update_batas',
			type 	: 'POST',
			data 	: "id="+id+"&jenis="+jenis+"&max="+max+"&min="+min+"&status="+status,

			beforeSend : function(){
				$("#loading").show();
				$("#konten").hide();
			},

			success : function(result){
				console.log(result);
				if(result == 1){
					//console.log('notif berhasil');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Batas Beban Kerja Berhasil Diperbaharui');
					$("#notice").fadeOut(5000);
				}else{
					//console.log('notif gagal');
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Batas Beban Kerja Gagal Diperbaharui !');
					$("#notice").fadeOut(8000);
				}

				get_batas();
				$("#id_batas").val(null);
				$("#max").val(null);
				$("#min").val(null);
				$("#loading").hide();
				$("#konten").show();

			}
		});
	}

	$(document).ready(function(){

		get_batas();

		$("#tbl_batal").on('click', function(){
			get_batas();
		});

		$("#tbl_tambah").on('click', function(){
			tambah_batas();
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
		<form method="POST" id="form-rule">
		<table class="table noborder">
			<tr>
				<td colspan="2"><h3>Pengaturan Batas Beban Kerja</h3></td>
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

							<tr hidden>
								<td>
									<input type="text" name="id_batas" id="id_batas">
								</td>
							</tr>
							
							<tr>
								<td>Status</td>
								<td>
									<select name="status" id="status">

									</select>
								</td>							
							</tr>
							
						
					</table>
				</td>
				<td>
					<table class="table noborder">
						<tr>
							<td>Minimal SKS</td>
							<td>
								<input type="number" name="min" id="min" step="any" min='0.001'>
							</td>
						</tr>
						<tr>
							<td>Maksimal SKS</td>
							<td>
								<input type="number" name="max" id="max" step="any" min='0.001'>
							</td>
						</tr>
						<tr>
								<td colspan="2" style="text-align: right; padding-right: 20px;">
									<div id="btn-tambah"><button type="button" class="btn-uin btn btn-small btn-inverse" disabled id="tbl_tambah">Tambah</button></div>
									<div id="btn-simpan" hidden><button type="button" class="btn-uin btn btn-small btn-inverse" style="margin-right: 10px;" id="tbl_simpan">Simpan</button><button type="button" class="btn-uin btn btn-small btn-inverse" id="tbl_batal">Batal</button></div>								
								</td>
							</tr>
					</table>
				</td>
			</tr>
		</table>
	</form>
		
		
		<div id="loading" hidden>
			<div class="loader3">Loading...</div>
		</div>
		<div id="konten">
			<h3>Data Pengaturan Batas Beban Kerja Dosen</h3>		
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>No.</th>
						<th>Jenis</th>
						<th>Minimal</th>
						<th>Maksimal</th>
						<th>tanggal</th>
						<th>Status</th>
						<th>Proses</th>
					</tr>
				</thead>
				
				<tbody id="data-batas">
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