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

<script>

	function get_poin(){
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/poin_remun',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var poin = JSON.parse(result);
				//console.log(poin);

				var handler = $("#poin-konten");
				var nomer = 1;
				$.each(poin, function(index, pts){
					var newRow = $("<tr>");
					newRow.html("<td align='center'>"+nomer+".</td><td align='center'>"+pts.JENJANG+"</td><td align='center'>"+pts.KELAS+"</td><td align='center'>"+pts.JABATAN+"</td><td align='center'>"+pts.SEMESTER+"</td><td align='center'>"+pts.KATEGORI+"</td><td align='center'>"+pts.POIN+"</td><td align='center'>"+pts.SATUAN+"</td>");
					handler.append(newRow);
					nomer++;
				});
			}
		});
	}

	function get_jbr(){
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/get_jbr',
			type 	: 'GET',
			data  	: '',

			success : function(result){
				var kat = JSON.parse(result);
				//console.log(kat);
				$.each(kat, function(index, value){
					$("#group").append("<option value='"+value.KD_JBR+"'>"+value.NM_JBR+"</option>");
				});
			}
		});
	}

	function get_sub(){
		var kd_jbr = $("#group").val();
		loading();
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/get_kat_remun',
			type 	: 'POST',
			data  	: 'kd_jbr='+kd_jbr,

			success : function(result){
				var sub = JSON.parse(result);
				//console.log(sub);
				$("#subgroup").empty();
				$("#subgroup").append("<option value='X0X'> -- Pilih -- </option>");

				$.each(sub, function(index, value){
					$("#subgroup").append("<option value='"+value.KD_KAT+"'>"+value.NM_KAT+"</option>");
				});

				clear_table();
				unloading();
				$("#formulir").hide();

			}
		});
	}

	function get_poin_remun(){
		var kd_kat = $("#subgroup").val();
		loading();
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/get_poin_remun',
			type 	: 'POST',
			data  	: 'kd_kat='+kd_kat,

			success : function(result){
				var poin = JSON.parse(result);
				console.log(poin);


				var handler = $("#poin-konten");
				handler.empty();
				var nomer = 1;
				var jumlah = poin.length;

				if(jumlah <= 0){
					clear_table();
				}else{
					$.each(poin, function(index, pts){
						var newRow = $("<tr>");
						var button = "<button type='button' class='btn btn-mini' value="+pts.KD_P+" onclick='get_edit("+pts.KD_P+");'><i class='icon-edit'></i> Edit</button>";

						var btnedit = "<a class='btn btn-mini' value="+pts.KD_P+" onclick='get_edit("+pts.KD_P+");'><i class='icon-edit'></i> Edit</a>";
						var btndlt 	= "<a class='btn btn-mini' value="+pts.KD_P+" onclick='hapus_poin("+pts.KD_P+");'><i class='icon-trash'></i> hapus</a>";

						var btn    = "<p class='btn-group'>"+btnedit+" "+btndlt+"</p>";
						newRow.html("<td align='center'>"+nomer+".</td><td align='center'>"+pts.JENJANG+"</td><td align='center'>"+pts.KELAS+"</td><td align='center'>"+pts.JABATAN+"</td><td align='center'>"+pts.SEMESTER+"</td><td align='center'>"+pts.POIN+"</td><td align='center'>"+pts.SATUAN+"</td><td align='center'>"+btn+"</td>");
						handler.append(newRow);
						nomer++;
					});
				}

				unloading();
				var sel = document.getElementById("subgroup");
				var text= sel.options[sel.selectedIndex].text;
				var value = kd_kat;//sel.options[sel.selectedIndex].value;

				if(value != 'X0X'){
					$("#judul").html("<p style='color: black;'>Daftar Poin Remunerasi Kategori : <b style='color: #A4884A;'>"+text+"</b></p>");
					$("#formulir").show();
				}else{
					$("#judul").html("Daftar Poin Remunerasi");
					$("#formulir").hide();
				}

				
				//fill_form_pendidikan();
				$("#btn-simpan").hide();
				$("#btn-tambah").show();

				$("#jenjang").removeAttr('readonly');
				$("#jabatan").removeAttr('readonly');
				$("#kelas").removeAttr('readonly');
				$("#semester").removeAttr('readonly');
				$("#satuan").removeAttr('readonly');
				

				
			}
		});

	}

	fill_form_pendidikan();

	function clear_table(){
		var handler = $("#poin-konten");
		handler.empty();
		handler.append("<tr><td colspan='8' align='center'>Tidak Ada Data untuk Ditampilkan !</td></tr>");
		//$("#subgroup").val(null);
	}

	function clear_form_pendidikan(){
		$("#id_p").val('');
		$("#jabatan").empty();
		$("#kelas").empty();
		$("#jenjang").empty();
		$("#semester").empty();
		$("#poin").val('');
		$("#satuan").val('');
	}

	function loading(){
		$("#loading").show();
		$("#konten").hide();
	}

	function unloading(){
		$("#loading").hide();
		$("#konten").show();
	}

	function loadingedit(){
		$("#loading").show();
		$("#form-pendidikan").hide();
	}

	function unloadingedit(){
		$("#loading").hide();
		$("#form-pendidikan").show();
	}

	function get_edit(val){
		var id_p  = val;
		loadingedit();
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/get_edit',
			type 	: 'POST',
			data 	: 'id_p='+id_p,

			success : function(result){
				var r = JSON.parse(result);

				var poin = r.POIN;
				var jab  = r.JABATAN;
				var jjg  = r.JENJANG;
				var kls  = r.KELAS;
				var smt  = r.SEMESTER;

				var cek = '';
				// console.log(r);
				clear_form_pendidikan();

				$.each(jab, function(idx, val){
					cek = '';
					if(val.ID_JABATAN == poin.JABATAN){
						cek = 'selected';
					}
					$("#jabatan").append("<option value='"+val.ID_JABATAN+"' "+cek+">"+val.NM_JABATAN+"</option>");
				});

				$.each(jjg, function(idx, val){
					cek = '';
					if(val.ID_JENJANG == poin.JENJANG){
						cek = 'selected';
					}
					$("#jenjang").append("<option value='"+val.ID_JENJANG+"' "+cek+">"+val.NM_JENJANG+"</option>");
				});

				$.each(kls, function(idx, val){
					cek = '';
					if(val.KD_KELAS == poin.KELAS){
						cek = 'selected';
					}
					$("#kelas").append("<option value='"+val.KD_KELAS+"' "+cek+">"+val.NM_KELAS+"</option>");
				});

				$.each(smt, function(idx, val){
					cek = '';
					if(val.KD_SEMESTER == poin.SEMESTER){
						cek = 'selected';
					}
					$("#semester").append("<option value='"+val.KD_SEMESTER+"' "+cek+">"+val.NM_SEMESTER+"</option>");
				});

				$("#poin").val(poin.POIN);
				$("#satuan").val(poin.SATUAN);
				$("#id_p").val(poin.KD_P);

				$("#jenjang").attr('readonly', 'readonly');
				$("#jabatan").attr('readonly', 'readonly');
				$("#kelas").attr('readonly', 'readonly');
				$("#semester").attr('readonly', 'readonly');
				$("#satuan").attr('readonly', 'readonly');

				$("#btn-tambah").hide();
				$("#btn-simpan").show();
				unloadingedit();
			}
		});
	}

	function fill_form_pendidikan(){
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/get_form_remun',
			type  	: 'GET',
			data 	: '',

			success	: function(result){
				var r = JSON.parse(result);
				//console.log(r);

				var jab = r.JABATAN;
				var jjg = r.JENJANG;
				var kls = r.KELAS;
				var smt = r.SEMESTER;

				clear_form_pendidikan();

				$.each(jab, function(idx, val){
					$("#jabatan").append("<option value='"+val.ID_JABATAN+"'>"+val.NM_JABATAN+"</option>");
				});

				$.each(jjg, function(idx, val){
					$("#jenjang").append("<option value='"+val.ID_JENJANG+"'>"+val.NM_JENJANG+"</option>");
				});

				$.each(kls, function(idx, val){
					$("#kelas").append("<option value='"+val.KD_KELAS+"'>"+val.NM_KELAS+"</option>");
				});

				$.each(smt, function(idx, val){
					$("#semester").append("<option value='"+val.KD_SEMESTER+"'>"+val.NM_SEMESTER+"</option>");
				});


			}

		});
	}

	function hapus_poin(val){
		var kd_p = val;
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/hapus_poin_remun',
			type 	: 'POST',
			data 	: 'kd_p='+kd_p,

			beforeSend : function(){
				alert('Apakah anda yakin untuk menghapus pengaturan poin ?');
			},
			success : function(data){
				if(data == 1){
					$("#notice-tbl").show();
					$("#notice-tbl-field").attr('class', 'alert alert-success');
					$("#notice-tbl-txt").html('Pengaturan Poin Remunerasi Berhasil Dihapus');
					$("#notice-tbl").fadeOut(5000);
					get_poin_remun();
				}else{
					$("#notice-tbl").show();
					$("#notice-tbl-field").attr('class', 'alert alert-danger');
					$("#notice-tbl-txt").html('Pengaturan Poin Remunerasi Gagal Dihapus !');
					$("#notice-tbl").fadeOut(8000);
					
				}
			}
		});
	}

	function tambah_poin(){
		var kd_jbk 	= $("#group").val();
		var kd_kat 	= $("#subgroup").val();
		var jenjang = $("#jenjang").val();
		var kelas 	= $("#kelas").val();
		var jabatan = $("#jabatan").val();
		var semester = $("#semester").val();
		var poin 	= $("#poin").val();
		var satuan 	= $("#satuan").val();

		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/tambah_poin_remun',
			type 	: 'POST',
			data 	: 'kd_jbk='+kd_jbk+'&kd_kat='+kd_kat+'&jenjang='+jenjang+'&kelas='+kelas+'&jabatan='+jabatan+'&semester='+semester+'&poin='+poin+'&satuan='+satuan,

			success : function(data){
				//console.log(JSON.parse(data));
				if(data == 1){
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Pengaturan Poin Remunerasi Berhasil Ditambahkan');
					$("#notice").fadeOut(5000);
					get_poin_remun();
				}else if(data == 0){
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Pengaturan Poin Remunerasi Gagal Ditambahkan !');
					$("#notice").fadeOut(8000);
					
				}else{
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-warning');
					$("#notice-txt").html('Terdapat Duplikasi Poin Remunerasi !');
					$("#notice").fadeOut(8000);
				}
			}
		});

	}

	function update_poin(){
		var kd_p 	= $("#id_p").val();
		var kd_kat 	= $("#subgroup").val();
		var jenjang = $("#jenjang").val();
		var kelas 	= $("#kelas").val();
		var jabatan = $("#jabatan").val();
		var semester = $("#semester").val();
		var poin 	= $("#poin").val();
		var satuan 	= $("#satuan").val();

		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setremun/update_poin_remun',
			type 	: 'POST',
			data 	: 'kd_kat='+kd_kat+'&kd_p='+kd_p+'&jenjang='+jenjang+'&kelas='+kelas+'&jabatan='+jabatan+'&semester='+semester+'&poin='+poin+'&satuan='+satuan,

			success : function(data){
				//console.log(JSON.parse(data));
				data = JSON.parse(data);
				if(data == true){
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Pengaturan Poin Remunerasi Berhasil Diperbaharui');
					$("#notice").fadeOut(5000);
					get_poin_remun();
				}else{
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Pengaturan Poin Remunerasi Gagal Diperbaharui !');
					$("#notice").fadeOut(8000);
					
				}
			}
		});
	}

	get_jbr();
	//clear_table();
	//get_poin();
	
</script>

<script>
	$(document).ready(function() {
				$("#subgroup").select2({
				});
			});
</script>

<body>
	<div>
		<div>
			<ul id="crumbs">
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/home">Admin Kinerja Dosen</a></li>
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/home">Pengaturan Poin Remun</a></li>
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/setting/pengaturan_sks">Daftar Pengaturan Poin Remun</a></li>
			</ul>
		</div>
		<!-- kategori -->
		<br>
		<div id="sct-kategori">
			<h3>Kategori Poin Remunerasi</h3>
			<table class="table noborder">
				<tr>
					<td style="width: 90px;">Kategori</td>
					<td>
						<select name="group" id="group" onchange="get_sub();">
							<option> -- Pilih -- </option>
						</select>
					</td>
				</tr>

				<tr>
					<td>Sub Kategori</td>
					<td>
						<select name="subgroup" id="subgroup" onchange="get_poin_remun();">
							<option value="X0X"> -- Pilih -- </option>
						</select>
					</td>
				</tr>
			
			</table>
		</div>
		
		<!-- formulir -->
		<div id="formulir" hidden>
			<br>
			<h3>Formulir Poin Remunerasi</h3>
			<div id="form-pendidikan">
				<form id="cekcek">
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
								<table class="table noborder">
									<tr hidden>
										<td >id_p</td>
										<td><input type="text" name="id_p" id="id_p"></td>
									</tr>
									<tr>
										<td style="width: 90px;">Jenjang</td>
										<td>
											<select name="jenjang" id="jenjang">
												<option></option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Kelas</td>
										<td>
											<select name="kelas" id="kelas">
												<option></option>
											</select>
										</td>
									</tr>
									<tr>
										<td style="width: 90px;">Jabatan</td>
										<td>
											<select name="jabatan" id="jabatan">
												<option></option>
											</select>
										</td>
									</tr>
								</table>
							</td>
							<td>
								<table class="table noborder">				
									<tr>
										<td>Semester</td>
										<td>
											<select name="semester" id="semester">
												<option></option>
											</select>
										</td>
									</tr>
									<tr>
										<td>Poin</td>
										<td><input type="number" min="1" step="any" name="poin" id="poin"></td>
									</tr>
									<tr>
										<td>Satuan</td>
										<td><input type="text" name="satuan" id="satuan"></td>
									</tr>
									<tr>
										<td colspan="2" style="text-align: right; padding-right: 50px;">
											<div id="btn-tambah"><button type="button" class="btn-uin btn btn-small btn-inverse" id="tbl_tambah" onclick="tambah_poin();">Tambah</button></div>
											<div id="btn-simpan" hidden><button type="button" class="btn-uin btn btn-small btn-inverse" onclick="update_poin();" style="margin-right: 10px;">Simpan</button><button type="button" class="btn-uin btn btn-small btn-inverse" onclick="get_poin_remun();">Batal</button></div>								
										</td>
									</tr>
								</table>
							</td>
						</tr>
						
					</table>
					
				</form>
			</div>
		</div>

		<!-- konten -->
		<br>
		<div id="loading" hidden>
			<div class="loader3">Loading...</div>
		</div>
		<div id="konten">
			<h3 id="judul">Daftar Poin Remunerasi</h3>
			<div class="container" id="notice-tbl" hidden>
				<div id="notice-tbl-field"><p id="notice-tbl-txt"></p></div>
			</div>
			<table class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>No.</th>
						<th>Jenjang</th>
						<th>Kelas</th>
						<th>Jabatan</th>
						<th>Semester</th>
						<th>Poin</th>
						<th>Satuan</th>
						<th style="width: 10px;">Proses</th>
					</tr>
				</thead>
				<tbody id="poin-konten">
					<tr>
						<td colspan="8" align="center"> Tidak Ada Data untuk Ditampilkan ! </td>
					</tr>
				</tbody>

			</table>
		</div>
	</div>
</body>