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
	function get_sub(){
		$.ajax({
			url		: '<?php echo base_url(); ?>/bkd/admbkd/setting/get_sub',
			type	: 'POST',
			data 	: $("#form-rule").serialize(),

			success : function(data){
				$("#subgroup").html(data); 
				clear_form();
				clear_table();
			}
		});
	}

	function get_rule(){
		$.ajax({
			url		: '<?php echo base_url(); ?>/bkd/admbkd/setting/get_pengaturan_sks',
			type	: 'POST',
			data 	: $("#form-rule").serialize(),

			beforeSend	: function(){
				$("#loading").show();
				$("#konten").hide();
				
			},

			success : function(data){
				$("#loading").hide();
				$("#konten").show();
				$("#data-rule").html(data); 
				clear_form();
				fill_form();
				$("#tbl_tambah").removeAttr("disabled");
			}
		});
	}

	function get_edit(value){
		$("#btn-tambah").hide();
		$("#btn-simpan").show();
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setting/get_edit',
			type 	: 'POST',
			data 	: 'id_rule='+value,

			success : function(data){
				//console.log(data);
				var temp = JSON.parse(data);
				console.log(temp);
				//$("#id_rule").val(cek.ID);

				var curr_id_rule = temp['RULE'].ID_RULE;
				var curr_batas 	 = temp['RULE'].BATAS;
				var curr_mode 	 = temp['RULE'].KD_MODE;
				var curr_opt 	 = temp['RULE'].OPERATOR;
				var curr_sts 	 = temp['RULE'].STATUS;
				var curr_nilai 	 = temp['RULE'].NILAI;

				var mode 	= temp['MODE'];
				var opt 	= temp['OPERATOR'];
				var sts 	= temp['STATUS'];

				var sct  	= '';

				$("#mode").empty();
				$("#operator").empty();
				$("#status").empty();

				$("#id_rule").val(curr_id_rule);
				$("#batas").val(curr_batas);
				$("#nilai").val(curr_nilai);
				
				
				$.each(mode, function(index, obj){
					sct = '';
					if(obj.KD_MODE == curr_mode){ sct = 'selected';	}
					$("#mode").append("<option value="+obj.KD_MODE+" "+sct+">"+obj.NM_MODE+"</option>");
				});				
				
				$.each(opt, function(index, obj){
					sct = '';
					if(obj.OPERATOR == curr_opt){ sct = 'selected';	}
					$("#operator").append("<option value='"+obj.OPERATOR+"' "+sct+">"+obj.NM_OPERATOR+"</option>");
				});
				
				$.each(sts, function(index, obj){
					sct = '';
					if(index == curr_sts){ sct = 'selected'; }
					$("#status").append("<option value='"+index+"' "+sct+">"+obj+"</option>");
				});


			}
		});
	}

	function clear_form(){
		$("#id_rule").empty();
		$("#mode").empty();
		$("#operator").empty();
		$("#status").empty();
		$("#nilai").val(null);
		$("#batas").val(null);

		$("#btn-tambah").show();
		$("#btn-simpan").hide();

		$("#tbl_tambah").attr("disabled", "disabled");
	}

	function clear_table(){
		$("#data-rule").empty();
		$("#data-rule").append("<tr><td colspan=8 align='center'> Tidak Ada Data !</td></tr>");
	}

	function fill_form(){
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setting/fill_form',
			type 	: 'GET',
			data 	: "",

			success : function(data){

				var temp = JSON.parse(data);
				console.log(temp);
				//$("#id_rule").val(cek.ID);

				var mode 	= temp['MODE'];
				var opt 	= temp['OPERATOR'];
				var sts 	= temp['STATUS'];		
				
				$.each(mode, function(index, obj){
					$("#mode").append("<option value="+obj.KD_MODE+" >"+obj.NM_MODE+"</option>");
				});				
				
				$.each(opt, function(index, obj){
					$("#operator").append("<option value='"+obj.OPERATOR+"' >"+obj.NM_OPERATOR+"</option>");
				});
				
				var sct = '';
				$.each(sts, function(index, obj){
					sct = '';
					if(index == 0){ sct = 'selected'; }
					$("#status").append("<option value='"+index+"' "+sct+">"+obj+"</option>");
				});

			}
		});
	}

	function tambah_rule(){
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setting/tambah_rule',
			type 	: 'POST',
			data 	: $("#form-rule").serialize(),

			success : function(data){
				// data = JSON.parse(data);
				// console.log(data);
				if(data == 1){
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Pengaturan Nilai SKS Berhasil Ditambahkan');
					$("#notice").fadeOut(5000);
					get_rule();
				}else{
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Pengaturan Nilai SKS Gagal Ditambahkan');
					$("#notice").fadeOut(8000);
					
				}
			}
		});
	}

	function simpan_rule(){
		$.ajax({
			url 	: '<?php echo base_url(); ?>/bkd/admbkd/setting/update_rule',
			type 	: 'POST',
			data 	: $("#form-rule").serialize(),

			success : function(data){
				if(data == 1){
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-success');
					$("#notice-txt").html('Pengaturan Nilai SKS Berhasil Diperbaharui');
					$("#notice").fadeOut(5000);
					get_rule();
				}else{
					$("#notice").show();
					$("#notice-field").attr('class', 'alert alert-danger');
					$("#notice-txt").html('Pengaturan Nilai SKS Gagal Diperbaharui');
					$("#notice").fadeOut(8000);
					
				}
			}
		});
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

</script>
<body>
	<div>
		<div>
			<ul id="crumbs">
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/home">Admin Kinerja Dosen</a></li>
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/home">Pengaturan</a></li>
				<li><a href="http://akademik.uin-suka.ac.id/bkd/admbkd/setting/pengaturan_sks">Daftar Pengaturan SKS</a></li>
			</ul>
		</div>
	
		<br>
		<form method="POST" id="form-rule">
		<table class="table noborder">
			<tr>
				<td><h3>Pengaturan</h3></td>
				<td><h3>Formulir Pengaturan Syarat Beban Kerja</h3></td>
			</tr>
			<tr>
				<td>	
					<table class="table noborder">
							<tr>
								<td style="width: 70px;">Jenis</td>
								<td>
									<select name="group" id="group" onchange="get_sub();">
										<option value="X0X"> -- Pilih -- </option>
										<?php foreach ($group as $g): ?>
											<option value="<?php echo $g['ID_G'] ?>"><?php echo $g['NM_GROUP']; ?></option>
										<?php endforeach ?>
									</select>
								</td>
							</tr>

							<tr>
								<td>Bidang</td>
								<td>
									<select name="subgroup" id="subgroup" onchange="get_rule();">
										<option value="X0X"> -- Pilih -- </option>
									</select>
								</td>
							</tr>
						
					</table>
				</td>
				<td>
					<table>
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
								<div id="btn-tambah"><button type="button" class="btn-uin btn btn-small btn-inverse" disabled id="tbl_tambah" onclick="tambah_rule();">Tambah</button></div>
								<div id="btn-simpan" hidden><button type="button" class="btn-uin btn btn-small btn-inverse" onclick="simpan_rule();" style="margin-right: 10px;">Simpan</button><button type="button" class="btn-uin btn btn-small btn-inverse" onclick="get_rule();">Batal</button></div>								
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
						<th>Syarat</th>
						<th>tanggal</th>
						<th>Status</th>
						<th>Proses</th>
					</tr>
				</thead>
				
				<tbody id="data-rule">
					<tr>
						<td colspan="8" align="center">Tidak Ada Data !</td>
					</tr>
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