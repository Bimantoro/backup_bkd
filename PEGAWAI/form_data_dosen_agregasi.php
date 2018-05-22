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

	.magenta {
	  background: #b31010;
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

	var dosen = null;
	
	function fill_fakultas(){
		$.ajax({
			url 	: 'get_data_fakultas',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var fak = JSON.parse(result);
				//console.log(fak);
				$('#fak').empty();
				$('#fak').append("<option value='X0X'>-- Pilih --</option>");
				$.each(fak, function(index, value){
					$('#fak').append("<option value='"+value.KD_FAK+"'>"+value.NM_FAK+"</option>");
				});
			}
		});
	}

	function fill_prodi(){
		var fak = $('#fak').val();
		if(fak != 'X0X'){
			$.ajax({
				url 	: 'get_data_prodi',
				type 	: 'POST',
				data 	: 'fak='+fak,

				success : function(result){
					var prod = JSON.parse(result);
					//console.log(prod);
					$('#prod').empty();
					$('#prod').append("<option value='X0X'>-- Pilih --</option>");
					$.each(prod, function(index, value){
						$('#prod').append("<option value='"+value.KD_PRODI+"'>"+value.NM_PRODI_J+"</option>");
					});
				}
			});
		}else{
			$('#prod').empty();
			$('#prod').append("<option value='X0X'>-- Pilih --</option>");
		}
	}

	function fill_asal(){
		$.ajax({
			url 	: 'get_asal_dosen',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var asal = JSON.parse(result);
				$('#asal').empty();
				$.each(asal, function(index, value){
					$('#asal').append("<option value='"+value.KD+"'>"+value.ASAL+"</option>");
				});
			}
		});
	}

	function fill_jenis(){
		$.ajax({
			url 	: 'get_jenis_dosen',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var jenis = JSON.parse(result);
				$('#jenis').empty();
				$.each(jenis, function(index, value){
					$('#jenis').append("<option value='"+value.KD+"'>"+value.JENIS+"</option>");
				});
			}
		});
	}

	function fill_status(){
		$.ajax({
			url 	: 'get_status_dosen',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var status = JSON.parse(result);
				$('#status').empty();
				$.each(status, function(index, value){
					$('#status').append("<option value='"+value.KD+"'>"+value.STATUS+"</option>");
				});

			}
		});
	}

	function fill_sts_dsn(){
		$.ajax({
			url 	: 'get_ds',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				var status = JSON.parse(result);
				$('#sts').empty();
				$.each(status, function(index, value){
					$('#sts').append("<option value='"+value.KD+"'>"+value.STATUS+"</option>");
				});
			}
		});
	}

	function fill_thn(){
		$.ajax({
			url 	: 'get_thn',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				//console.log(JSON.parse(result));
				var thn = JSON.parse(result);
				$('#thn').empty();
				$.each(thn, function(i,val){
					$('#thn').append("<option value='"+val.KD_TA+"' "+val.STATUS+">"+val.TA+"</option>");
				});
			}
		});
	}

	function fill_bulan(){
		$.ajax({
			url 	: 'get_bulan',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				//console.log(JSON.parse(result));
				var bln = JSON.parse(result);
				$('#bln').empty();
				$.each(bln, function(i,val){
					$('#bln').append("<option value='"+val.KD_BLN+"' "+val.STATUS+" >"+val.NM_BLN+"</option>");
				});

			}
		});
	}

	function get_data_dosen(){
		var fak 	= $('#fak').val();
		var prod 	= $('#prod').val();
		var asal 	= $('#asal').val();
		var jenis 	= $('#jenis').val();
		var status 	= $('#status').val();
		var ta 		= $('#thn').val();
		var bln 	= $('#bln').val();

		$.ajax({
			url 	: 'get_data_dosen',
			type 	: 'POST',
			data 	: 'fak='+fak+'&prod='+prod+'&asal='+asal+'&jenis='+jenis+'&status='+status+'&ta='+ta+'&bln='+bln,

			beforeSend : function(){
				loading();
			},

			success : function(result){
				//console.log(JSON.parse(result));
				$('#data-dosen').empty();

				if(result==0){
					dosen = null;
					$('#tbl-filter').hide();
					$('#data-dosen').append("<tr><td colspan='10' align='center'>Tidak Ada Data untuk Ditampilkan !</td></tr>");
				}else{
					var dsn = JSON.parse(result);
					dosen = dsn;
					//console.log(dsn);
					var row ='';
					var nomer = 1;
					var opsi = '';
					$.each(dsn, function(index, value){
						opsi = '';
						if((value.JENIS == 'DT' && value.IKU == 'BU') || (value.JENIS == 'DK' && value.SKP == 'BU') || value.PRESENSI == 'BU'){
							opsi = "class = 'error'";
						}
						row += "<tr "+opsi+">";
						row += "<td align='center' >"+nomer+".</td>";
						row += "<td align='center'>"+value.KD_DOSEN+"</td>";
						row += "<td >"+value.NAMA+"</td>";
						row += "<td align='center'>"+value.JENIS+"</td>";

						if(value.PRESENSI == 'BU'){
							row += "<td align='center'>Belum Upload</td>";
						}else{
							row += "<td align='center'>"+value.PRESENSI+"%</td>";
						}
						
						row += "<td align='center'>"+value.SKR+"%</td>";

						if(value.IKU == 'BU' && value.JENIS == 'DT'){
							row += "<td align='center'>Belum Upload</td>";
						}else if(value.IKU == 'BU'){
							row += "<td align='center'> - </td>";
						}else{
							row += "<td align='center'>"+value.IKU+"%</td>";
						}
					
						if(value.SKP == 'BU' && value.JENIS == 'DK'){
							row += "<td align='center'>Belum Upload</td>";
						}else if(value.SKP == 'BU'){
							row += "<td align='center'> - </td>";
						}else{
							row += "<td align='center'>"+value.SKP+"%</td>";
						}
						
						row += "<td align='center'>"+value.IKD+"%</td>";
						row += "<td align='center'>"+value.TOTAL+"%</td>";
						row += "</tr>"
						$('#data-dosen').append(row);
						nomer++;
						row = "";

					});
					$('#tbl-filter').show();
				}
				
				unloading();
			}
		});
	}

	function get_data_dosen_2(){
		var fak 	= $('#fak').val();
		var prod 	= $('#prod').val();
		var asal 	= $('#asal').val();
		var jenis 	= $('#jenis').val();
		var status 	= $('#status').val();
		var ta 		= $('#ta').val();
		var per 	= $('#per').val();

		$.ajax({
			url 	: 'get_data_dosen_2',
			type 	: 'POST',
			data 	: 'fak='+fak+'&prod='+prod+'&asal='+asal+'&jenis='+jenis+'&status='+status+'&ta='+ta+'&per='+per,

			beforeSend : function(){
				loading();
			},

			success : function(result){
				//console.log(JSON.parse(result));
				$('#data-dosen').empty();
				//result = 0;
				if(result==0){
					dosen = null;
					$('#tbl-filter').hide();
					$('#data-dosen').append("<tr><td colspan='10' align='center'>Tidak Ada Data untuk Ditampilkan !</td></tr>");
				}else{
					var dsn = JSON.parse(result);
					dosen = dsn;
					//console.log(dsn);
					var row ='';
					var nomer = 1;
					var opsi = '';
					$.each(dsn, function(index, value){
						opsi = '';
						if((value.JENIS == 'DT' && value.IKU == 'BU') || (value.JENIS == 'DK' && value.SKP == 'BU') || value.PRESENSI == 'BU'){
							opsi = "class = 'error'";
						}
						row += "<tr "+opsi+">";
						row += "<td align='center' >"+nomer+".</td>";
						row += "<td align='center'>"+value.KD_DOSEN+"</td>";
						row += "<td >"+value.NAMA+"</td>";
						row += "<td align='center'>"+value.JENIS+"</td>";

						if(value.PRESENSI == 'BU'){
							row += "<td align='center'>Belum Upload</td>";
						}else{
							row += "<td align='center'>"+value.PRESENSI+"%</td>";
						}
						
						row += "<td align='center'>"+value.SKR+"%</td>";

						if(value.IKU == 'BU' && value.JENIS == 'DT'){
							row += "<td align='center'>Belum Upload</td>";
						}else if(value.IKU == 'BU'){
							row += "<td align='center'> - </td>";
						}else{
							row += "<td align='center'>"+value.IKU+"%</td>";
						}
					
						if(value.SKP == 'BU' && value.JENIS == 'DK'){
							row += "<td align='center'>Belum Upload</td>";
						}else if(value.SKP == 'BU'){
							row += "<td align='center'> - </td>";
						}else{
							row += "<td align='center'>"+value.SKP+"%</td>";
						}
						
						row += "<td align='center'>"+value.IKD+"%</td>";
						row += "<td align='center'>"+value.TOTAL+"%</td>";
						row += "</tr>"
						$('#data-dosen').append(row);
						nomer++;
						row = "";

					});
					$('#tbl-filter').show();
				}
				
				unloading();
			}
		});
	}

	function get_data_filter(){
		var filter = $('#sts').val();
		var dsn = dosen;
		var opsi = '';
		$('#data-dosen').empty();
		if(filter == 'X0X'){
			var row ='';
			var nomer = 1;
			$.each(dsn, function(index, value){
				opsi = '';
				if((value.JENIS == 'DT' && value.IKU == 'BU') || (value.JENIS == 'DK' && value.SKP == 'BU') || value.PRESENSI == 'BU'){
					opsi = "class = 'error'";
				}
				row += "<tr "+opsi+">";
				row += "<td align='center' >"+nomer+".</td>";
				row += "<td align='center'>"+value.KD_DOSEN+"</td>";
				row += "<td >"+value.NAMA+"</td>";
				row += "<td align='center'>"+value.JENIS+"</td>";
				if(value.PRESENSI == 'BU'){
					row += "<td align='center'>Belum Upload</td>";
				}else{
					row += "<td align='center'>"+value.PRESENSI+"%</td>";
				}
				
				row += "<td align='center'>"+value.SKR+"%</td>";

				if(value.IKU == 'BU' && value.JENIS == 'DT'){
					row += "<td align='center'>Belum Upload</td>";
				}else if(value.IKU == 'BU'){
					row += "<td align='center'> - </td>";
				}else{
					row += "<td align='center'>"+value.IKU+"%</td>";
				}
			
				if(value.SKP == 'BU' && value.JENIS == 'DK'){
					row += "<td align='center'>Belum Upload</td>";
				}else if(value.SKP == 'BU'){
					row += "<td align='center'> - </td>";
				}else{
					row += "<td align='center'>"+value.SKP+"%</td>";
				}
				row += "<td align='center'>"+value.IKD+"%</td>";
				row += "<td align='center'>"+value.TOTAL+"%</td>";
				row += "</tr>"
				$('#data-dosen').append(row);
				nomer++;
				row = "";

			});
		}else{
			var row ='';
			var nomer = 1;
			$.each(dsn, function(index, value){
				if(value.JENIS == filter){
					opsi = '';
					if((value.JENIS == 'DT' && value.IKU == 'BU') || (value.JENIS == 'DK' && value.SKP == 'BU') || value.PRESENSI == 'BU'){
						opsi = "class = 'error'";
					}
					row += "<tr "+opsi+">";
					row += "<td align='center' >"+nomer+".</td>";
					row += "<td align='center'>"+value.KD_DOSEN+"</td>";
					row += "<td >"+value.NAMA+"</td>";
					row += "<td align='center'>"+value.JENIS+"</td>";
					if(value.PRESENSI == 'BU'){
					row += "<td align='center'>Belum Upload</td>";
					}else{
						row += "<td align='center'>"+value.PRESENSI+"%</td>";
					}
					
					row += "<td align='center'>"+value.SKR+"%</td>";

					if(value.IKU == 'BU' && value.JENIS == 'DT'){
						row += "<td align='center'>Belum Upload</td>";
					}else if(value.IKU == 'BU'){
						row += "<td align='center'> - </td>";
					}else{
						row += "<td align='center'>"+value.IKU+"%</td>";
					}
				
					if(value.SKP == 'BU' && value.JENIS == 'DK'){
						row += "<td align='center'>Belum Upload</td>";
					}else if(value.SKP == 'BU'){
						row += "<td align='center'> - </td>";
					}else{
						row += "<td align='center'>"+value.SKP+"%</td>";
					}
					row += "<td align='center'>"+value.IKD+"%</td>";
					row += "<td align='center'>"+value.TOTAL+"%</td>";
					row += "</tr>"
					$('#data-dosen').append(row);
					nomer++;
					row = "";
				}
			});

			if(nomer-1 == 0){
				$('#data-dosen').append("<tr><td colspan='10' align='center'>Tidak Ada Data untuk Ditampilkan !</td></tr>");
			}
		}
	}


	var periode = '';
	var current = '';

	function get_data_periode(){
		$.ajax({
			url		: 'fill_form_ta',
			type	: 'GET',

			success : function(result){
				var r = JSON.parse(result);
				//console.log(r);
				periode = r.PERIODE;
				current = r.CURR;

				fill_ta();
				fill_periode();
			}
		});
	}

	get_data_periode();

	function fill_ta(){
		$('#ta').empty();
		$.each(periode, function(index, value){
			var temp_ta = index+"/"+(parseInt(index)+1);		
			if(parseInt(index) == parseInt(current.KD_TA)){
				$('#ta').append("<option value='"+index+"' selected>"+temp_ta+"</option>");
			}else{
				$('#ta').append("<option value='"+index+"'>"+temp_ta+"</option>");
			}			
		});
	}

	function fill_periode(){
		var curr_ta = $('#ta').val();
		$('#per').empty();
		$.each(periode, function(index, value){
			//var temp_ta = value+"/"+value+1;		
			if(parseInt(index) == parseInt(curr_ta)){
				 $.each(value, function(idx, val){
					var temp_sct = '';
					if(parseInt(idx) == 1){
						if(parseInt(current.KD_PER) == 1 && parseInt(index) == parseInt(current.KD_TA)){
							temp_sct = 'selected';
						}						
						$('#per').append("<option value='"+idx+"' "+temp_sct+">GANJIL</option>");
					}else{
						if(parseInt(current.KD_PER) == 2 && parseInt(index) == parseInt(current.KD_TA)){
							temp_sct = 'selected';
						}						
						$('#per').append("<option value='"+idx+"' "+temp_sct+">GENAP</option>");
					}
				 });
			}			
		});
	}

	function fill_periode_opt(){
		var curr_ta = $('#ta').val();
		$('#per').empty();
		$.each(periode, function(index, value){
			//var temp_ta = value+"/"+value+1;		
			if(parseInt(index) == parseInt(curr_ta)){
				 $.each(value, function(idx, val){
					var temp_sct = '';
					if(parseInt(idx) == 1){				
						$('#per').append("<option value='"+idx+"'>GANJIL</option>");
					}else{						
						$('#per').append("<option value='"+idx+"'>GENAP</option>");
					}
				 });
			}			
		});
	}

	function loading(){
		$('#loading').show();
		$('#konten').hide();
	}

	function unloading(){
		$('#loading').hide();
		$('#konten').show();
	}

	function hide_all(){
		$('#loading').hide();
		$('#konten').hide();
	}


	$(document).ready(function(){

		fill_fakultas();
		fill_asal();
		fill_jenis();
		fill_status();
		fill_sts_dsn();
		fill_thn();
		fill_bulan();

		$("#fak").on('change', function(){
			fill_prodi();
		});

		$("#sts").on('change', function(){
			get_data_filter();
		});

		$("#ta").on('change', function(){
			fill_periode_opt();
		});

		$("#btn-lihat").on('click', function(){
			get_data_dosen_2();
		});

		// $("#tbl_simpan").on('click', function(){
		// 	simpan_batas();
		// });
	});

</script>

<?php
$crumbs = array(
    array('Admin Remunerasi Dosen' => '#'),
    array('Data Agregasi Dosen' => 'remunerasi_dosen/data_dosen/data_dosen_agregasi')
   );

$this->view->output_crumbs($crumbs);
?>
<br>
<div>
	<!-- <h3>Data Agregasi Remunerasi Dosen</h3> -->
	<table class="table noborder">
		<tr>
			<td>
				<table class="table noborder">
					<tr>
						<td style="width: 110px;"> <b>Fakultas</b></td>
						<td>
							<select name="fak" id="fak"">
								<option> -- Pilih -- </option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Program Studi</b></td>
						<td>
							<select name="prod" id="prod"">
								<option> -- Pilih -- </option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Asal Dosen</b></td>
						<td>
							<select name="asak" id="asal"">
								<option> -- Pilih -- </option>
							</select>
						</td>
					</tr>
					<tr>
						<td ><b>Jenis Dosen</b></td>
						<td>
							<select name="jenis" id="jenis"">
								<option> -- Pilih -- </option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Status Dosen</b></td>
						<td>
							<select name="status" id="status"">
								<option> -- Pilih -- </option>
							</select>
						</td>
					</tr>
				</table>
			</td>
			<td>
				<table class="table noborder">		
				<!-- 	<tr style="width: 120px;">
						<td><b>Tahun</b></td>
						<td> 
							<select name="thn" id="thn">
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Bulan</b></td>
						<td> 
							<select name="bln" id="bln">						
							</select>
						</td>
					</tr> -->
					<tr style="width: 120px;">
						<td><b>TA</b></td>
						<td> 
							<select name="ta" id="ta">
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Periode</b></td>
						<td> 
							<select name="per" id="per">						
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: right;">
							<button class="btn-uin btn btn-inverse btn-small" id="btn-lihat">Lihat Dosen</button>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	
</div>

<div id="loading" hidden>
	<!-- <div class="loader3">Loading...</div> -->
	<div align="center">
		<br>
		<br>
		<img src="http://static.uin-suka.ac.id/images/loading.gif">
		<p style="text-align: center;">Harap Menunggu ...</p>
	</div>
</div>

<div id="konten" hidden style="padding-left: 20px;">
	<h3>Data Agregasi Dosen</h3>
	<div hidden id="tbl-filter">
		<table class="table noborder">
			<tr style="width: 20px;">
				<td style="padding-left: 0px; width: 200px;"><b>Tampilkan Berdasarkan Status</b></td>
				<td style="padding-left: 0px;" align="left"> 
					<select name="sts" id="sts">
					</select>
				</td>
			</tr>	
		</table>
	</div>	
	<table class="table table-bordered table-hover table-responsive">
		<thead>
			<tr>
				<th>No.</th>
				<th>NIP</th>
				<th>Nama</th>
				<th>Status</th>
				<th>Kehadiran</th>
				<th>SKR</th>
				<th>IKU</th>
				<th>SKP</th>
				<th>IKD</th>
				<th>TOTAL</th>
			</tr>
		</thead>
		<tbody id="data-dosen">
		</tbody>

	</table>

	<div>
		<table class="table noborder">
			<tr>
				<td colspan="2"><b>Keterangan :</b></td>
			</tr>
			<tr>
				<td style="width: 10px;"><div class="foo magenta"></div></td>
				<td style="padding-left: 0px;">: Terdapat Poin Agregasi yang belum terpenuhi</td>
			</tr>		
		</table>
	</div>
</div>



