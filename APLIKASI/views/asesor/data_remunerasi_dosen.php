<?php $this->load->view('fungsi');?>
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
	function TanggalIndo(date){
		var BulanIndo = ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"];
		var tahun = date.substr(6,4);
		var bulan = date.substr(3,2);
		var tgl = date.substr(0,2);
		var result = tgl+' '+BulanIndo[bulan-1]+' '+tahun;

		return result;
	}
</script>
<script type="text/javascript">
	function fill_asal(){
		$.ajax({
			url 	: '<?php echo base_url('bkd/dosen/verifikator/get_asal_dosen')?>',
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
			url 	: '<?php echo base_url('bkd/dosen/verifikator/get_jenis_dosen')?>',
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
			url 	: '<?php echo base_url('bkd/dosen/verifikator/get_status_dosen')?>',
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

	}
	function get_data_dosen(){
		//var fak 	= $('#fak').val();
		var prod 	= $('#prod').val();
		var asal 	= $('#asal').val();
		var jenis 	= $('#jenis').val();
		var status 	= $('#status').val();
		$.ajax({
			url 	: '<?php echo base_url('bkd/dosen/verifikator/get_data_dosen')?>',
			type 	: 'POST',
			data 	: 'prod='+prod+'&asal='+asal+'&jenis='+jenis+'&status='+status,
			beforeSend : function(){
				<?php if(!empty($asesor)){?>
					loading();
					<?php }?>
			},

			success : function(result){
				console.log(JSON.parse(result));
				$('#data-dosen').empty();

				if(result==0){
					dosen = null;
					//$('#tbl-filter').hide();
					$('#data-dosen').append("<tr><td colspan='8' align='center'>Tidak Ada Data untuk Ditampilkan !</td></tr>");
				}else{
					var dsn = JSON.parse(result);
					dosen = dsn;
					console.log(dsn);
					var row ='';
					var nomer = 1;
					var opsi = '';
					var verifikasi ='';
					var tgl_verifikasi;
					$.each(dsn, function(index, value){
						opsi = '';
						/*if((value.JENIS == 'DT' && value.IKU == 0) || (value.JENIS == 'DK' && value.SKP == 0)){
							opsi = "class = 'error'";
						}*/
						row += "<tr "+opsi+">";
						row += "<td align='center' >"+nomer+".</td>";
						row += "<td align='center'>"+value.KD_DOSEN+"</td>";
						row += "<td>"+value.NAMA+"</td>";
						row += "<td align='center'>"+value.JENIS+"</td>";
						row += "<td align='center'>"+value.VERIFIKATOR+"</td>";
						
						if(value.TGL_VERIFIKASI == '-'){
							 tgl_verifikasi = value.TGL_VERIFIKASI;
						}else{
							tgl_verifikasi = TanggalIndo(value.TGL_VERIFIKASI);
						}
						row += "<td align='center'>"+tgl_verifikasi+"</td>";
						row += "<td align='center'>"+value.STATUS+"</td>";
						/*row += "<td>"+aksi+"</td>";*/
						row +=  "<td align='center'><span class='btn-group'><a class='btn btn-small' href='<?php echo base_url().'bkd/dosen/verifikator/daftar_remunerasi/'?>"+value.KD_DOSEN+"' target='blank'><i class='icon-book'></i> BKD</a></span></td>";
						row += "</tr>"
						$('#data-dosen').append(row);
						nomer++;
						row = "";

					});
					get_keterangan();
					/*$('#tbl-filter').show();*/
				}
				
				unloading();
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
	function get_keterangan(){
		$.ajax({
			url 	: 'get_keterangan_syarat',
			type 	: 'GET',
			data 	: '',

			success : function(result){
				$("#ket-body").html(result);
			}
		});
	}
</script>
<script type="text/javascript">

	$(document).ready(function(){
		fill_asal();
		fill_jenis();
		fill_status();
		fill_sts_dsn();
		get_data_dosen();
		/*$("#fak").on('change', function(){
			fill_prodi();
		});*/

		/*$("#sts").on('change', function(){
			get_data_filter();
		});*/

		$("#btn-lihat").on('click', function(){
			get_data_dosen();
			
		});

		// $("#tbl_simpan").on('click', function(){
		// 	simpan_batas();
		// });
	});
</script>
<?php 
	/*echo "<pre>";
	print_r($prodi);
	echo "<pre>";*/
?>
<div id="content">
<div>
	<ul id="crumbs">
		<li><a href="<?php echo base_url().'bkd/dosen/verifikator';?>">Verifikator Kinerja Dosen</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/verifikator/dosen';?>">Rencana dan Kinerja</a></li>
		<li><a href="<?php echo base_url().'bkd/dosen/verifikator/dosen/'.$this->uri->segment(5);?>">Data Dosen</a></li>
	</ul>
	<?php
    $a = $this->session->flashdata('msg');
    if($a!=null){?>
    	<br>
        <div class="alert alert-<?php echo $a[0]?> alert-msg">
            <?php echo $a[1]?>
        </div>
    <?php }
?>
	<br/>
	<?php
		if(!empty($asesor)){?>
			<h3>Asesor Remunerasi Dosen</h3>
	<div>
	<!-- <h3>Data Agregasi Remunerasi Dosen</h3> -->
	<table class="table noborder">
		<tr>
			<td>
				<table class="table noborder">
					<tr>
						<td style="width: 110px;"> <b>Progam Studi</b></td>
						<td>
							<select name="prod" id="prod">
								<option value=""> -- Pilih -- </option>
								<?php
									foreach ($prodi as $p) {?>
										<option value="<?php echo $p['KD_PRODI']?>"><?php echo $p['NM_PRODI_J']?></option>
									<?php }
								?>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Asal Dosen</b></td>
						<td>
							<select name="asal" id="asal"">
								<option> -- Pilih -- </option>
								<!-- <option value="0">Dalam Program Studi</option> -->
							</select>
						</td>
					</tr>
					<tr>
						<td ><b>Jenis Dosen</b></td>
						<td>
							<select name="jenis" id="jenis"">
								<option> -- Pilih -- </option>
								<!-- <option>Semua</option>
								<option>Dosen Tetap PNS</option>
								<option>Dosen Luar Biasa</option> -->
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Status Dosen</b></td>
						<td>
							<select name="status" id="status"">
								<option> -- Pilih -- </option>
								<!-- <option>Semua</option>
								<option>Aktif Mengajar</option>
								<option>Tidak Aktif Mengajar</option> -->
							</select>
						</td>
					</tr>
					<tr>
						<td colspan="2" style="text-align: right;"><button class="btn-uin btn btn-inverse btn-small" id="btn-lihat">Lihat Dosen</button></td>
					</tr>
				</table>
			</td>
			<!-- <td>
				<table class="table noborder">		
					
					<tr>
						<td colspan="2" style="text-align: right;">
							<button class="btn-uin btn btn-inverse btn-small" id="btn-lihat">Lihat Dosen</button>
						</td>
					</tr>
				</table>
			</td> -->
		</tr>
	</table>
	
</div>
		<?php }else{?>
		<h4>Anda tidak termasuk verifikator remunerasi dosen</h4>
		<?php }
	?>
	

</div>
<div id="loading" hidden>
	<div class="loader3">Loading...</div>
</div>
<div id="konten" hidden style="padding-left: 20px;">
	<h3 style="<?php if(!empty($asesor)){?> display: "";<?php }else{?> display: none;<?php }?>">Daftar Nama Dosen</h3>
	<!-- <div hidden id="tbl-filter">
		<table class="table noborder">
			<tr style="width: 20px;">
				<td style="padding-left: 0px; width: 200px;"><b>Tampilkan Berdasarkan Status</b></td>
				<td style="padding-left: 0px;" align="left"> 
					<select name="sts" id="sts">
					</select>
				</td>
			</tr>	
		</table>
	</div>	 -->
	<table class="table table-bordered table-hover table-responsive" style="<?php if(!empty($asesor)){?> display: "";<?php }else{?> display: none;<?php }?>">
		<thead>
			<tr>
				<th>No.</th>
				<th>NIP</th>
				<th>Nama</th>
				<th>Status Dosen</th>
				<th>Verifikator</th>
				<th>Tgl Verifikasi</th>
				<th>Status Verifikasi</th>
				<th>Aksi</th>
			</tr>
		</thead>
		<tbody id="data-dosen">

		</tbody>

	</table>

	<!-- <div>
		<table class="table noborder">
			<tr>
				<td colspan="2"><b>Keterangan :</b></td>
			</tr>
			<tr>
				<td style="width: 10px;"><div class="foo magenta"></div></td>
				<td style="padding-left: 0px;">: Terdapat Poin IKU/SKP yang belum terpenuhi</td>
			</tr>		
		</table>
	</div> -->
</div>
</div>
<div id="ket-body">
			<!-- <strong>Keterangan</strong> -->
			<!-- <table style="margin: 5px 0 30px 0;">
				<tbody id="ket-body">
					
				</tbody>
			</table> -->
		</div>
<script type="text/javascript" charset="utf-8">
      $(function(){
        setTimeout('closing_msg()', 5000)
      })

      function closing_msg(){
        $(".alert-msg").slideUp();
      }
    </script>
