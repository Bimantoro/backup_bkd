<script type="text/javascript">
	$(document).ready(function(){
		
		$(".btn-isi").click(function(){
			$("#modal").fadeIn();
		});
		
		$(".btn-isi2").click(function(){
			$("#modal100").fadeIn();
		});
		
		$(".icon-remove").click(function(){
			$("#modal").fadeOut();
		});
		
		$("#simpan-isi").click(function(){
			var data = $("#isi_penugasan").val();
			$("#modal").fadeOut('fast');
			$("#bukti").val(data);
			$("#bukti-label").text(data);
		});
		$("#simpan-isi100").click(function(){
			var data = $("#isi_penugasan100").val();
			$("#modal100").fadeOut('fast');
			$("#bukti2").val(data);
			$("#bukti-label2").text(data);
		});
	});
</script>
<div id="modal">
	<div class="title-bar">Isi Bukti Penugasan Remun
		<span class="close"><i class="icon-remove icon-white"></i></span>
	</div>
	<table class="table">
	<tr>
		<th>Bukti Penugasan Remun</th>
		<td><input type="text" name="bkt_penugasan" class="input-xxlarge" id="isi_penugasan"></td>
	</tr>
	<tr><td></td><td><input type="submit" name="submit" id="simpan-isi" value="Simpan Bukti Penugasan" class="btn btn-small btn-inverse btn-uin"></td></tr>
	</table>
</div>

<div id="modal100">
	<div class="title-bar">Isi Bukti Dokumen Remun
		<span class="close"><i class="icon-remove icon-white"></i></span>
	</div>
	<table class="table">
	<tr>
		<th>Bukti Dokumen	 Remun</th>
		<td><textarea name="bkt_penugasan" class="input" id="isi_penugasan100"></textarea></td>
	</tr>
	<tr><td></td><td><input type="submit" name="submit" id="simpan-isi100" value="Simpan Bukti Penugasan" class="btn btn-small btn-inverse btn-uin"></td></tr>
	</table>
</div>
<style>
	#modal, #modal100{
		position:fixed;
		left:30%; top:15%;
		border:solid 5px #444;
		border-radius:10px;
		background-color:#FFF;
		z-index:10;
		box-shadow:0 0 0 1000px #EEE;
		display:none;
	}
	.title-bar{
		padding:6px; font-weight:bold;
		color:#FFF; background-color:#444;
		border-bottom:solid 1px #444;
	}
	form{
		margin:10px;
	}
</style>