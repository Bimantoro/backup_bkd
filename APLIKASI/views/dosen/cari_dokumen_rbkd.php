<script type="text/javascript">
	$(document).ready(function(){
		$("#surat").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_surat");
		$("#surat3").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_surat");
		
		$(".btn-cari").click(function(){
			$("#modal2").fadeIn();
		});
		
		$(".btn-cari2").click(function(){
			$("#modal3").fadeIn();
		});
		
		$(".icon-remove").click(function(){
			$("#modal3").fadeOut();
			$("#modal2").fadeOut();
			$("#modal100").fadeOut();
		});
		
		$("#simpan-cari3").click(function(){
			var data = $("#surat3").val();
			if(data.split('<$>').length > 1){
				alert('Silakan pilih salah satu file penugasan');
			}else{
				var value = data.split(':');
				$("#modal3").fadeOut('fast');
				$("#bukti2").val(value[0]+':'+ value[1]);
				$("#bukti-label2").text('Surat: '+value[1]);
			}
		});

		$("#simpan-cari").click(function(){
			var data = $("#surat").val();
			if(data.split('<$>').length > 1){
				alert('Silakan pilih salah satu file penugasan');
			}else{
				var value = data.split(':');
				$("#modal2").fadeOut('fast');
				$("#bukti").val(value[0]+':'+ value[1]);
				$("#bukti-label").text('Surat: '+value[1]);
			}
		});
	});
</script>
<style>
.grup{
	background-color: #EEEEEE;
}
.auto-surat{
	float: left;
	margin: 0px 0px 10px 0px;
	border: 1px solid #CCCCCC;
	border-radius: 4px;
	padding: 1px;
	width: 566px;
}
.label-input {
	width: 64px;
	float: left;
	padding:8px;
}
.tujuan-surat input:focus{
	box-shadow:none;
}
</style>
<div id="modal2">
	<div class="title-bar">Cari Bukti Penugasan
		<span class="close"><i class="icon-remove icon-white"></i></span>
	</div>
	<table class="table table-condensed">
	<tr>
		<th>Bukti Penugasan</th>
		<td>
			<div class="tujuan-surat">
				<div class="auto-surat grup">
					<div class="label-input"><i class="icon icon-search"></i> </div>
					<input type="text" name="surat" id="surat" placeholder="Masukkan keyword untuk mencari dokumen surat"/>
				</div>
			</div>
		</td>
	</tr>
	<tr><td></td><td><input type="submit" id="simpan-cari" name="submit" value="Simpan Bukti Penugasan" class="btn btn-small btn-inverse btn-uin"></td></tr>
	</table>
</div>

<div id="modal3">
	<div class="title-bar">Cari Bukti Dokumen
		<span class="close"><i class="icon-remove icon-white"></i></span>
	</div>
	<table class="table table-condensed">
	<tr>
		<th>Bukti Dokumen</th>
		<td>
			<div class="tujuan-surat">
				<div class="auto-surat grup">
					<div class="label-input"><i class="icon icon-search"></i> </div>
					<input type="text" name="surat" id="surat3"/>
				</div>
			</div>
		</td>
	</tr>
	<tr><td></td><td><input type="submit" id="simpan-cari3" name="submit" value="Simpan Bukti Dokumen" class="btn btn-small btn-inverse btn-uin"></td></tr>
	</table>
</div>
<style>
	#modal2, #modal3{
		position:fixed;
		left:30%; top:15%;
		border:solid 5px #444;
		border-radius:10px;
		background-color:#FFF;
		z-index:1;
		box-shadow:0 0 0 1000px #EEE;
		display:none;
	}
	.title-bar2{
		padding:6px; font-weight:bold;
		color:#FFF; background-color:#444;
		border-bottom:solid 1px #444;
	}
	form2{
		margin:10px;
	}
	.close{ color:#FFF;}
</style>