<script type="text/javascript">
	$(document).ready(function(){
		$(".icon-remove").click(function(){
			$("#modal10").fadeOut();
			$("#modal20").fadeOut();
		});
		
		$(".btn-upload").click(function(){
			$("#modal10").fadeIn();
		});
		
		$("#simpan-upload").click(function(){
			/*var nama = $("#nama").val();
			var file = $("#userfile").val();*/
			var nama = document.getElementById('nama').value;
			var file = document.getElementById('userfile').value;
			var filename = file.replace(/^.*\\/, "");
			var ext = file.split('.').pop();
			if(ext == 'JPG' || ext == 'jpg' || ext == 'JPEG' || ext == 'jpeg' || ext == 'PDF' || ext == 'pdf'){
				if(nama !== ''){
					$("#modal10").fadeOut('fast');
					$("#bukti-label").text(nama+ ' : '+ filename);
					$("#bukti").val('file:'+ filename);
				}else{
					$("#message").html("<div class='alert alert-error'>Nama bukti penugasan harus diisi</div>");
					return false;
				}
			}else{
				$("#message").html("<div class='alert alert-error'>Mohon maaf, tipe file yang diupload tidak sesuai</div>");
				return false;
			}
		});

	});
</script>
<div id="modal10">
	<div class="title-bar">Upload File Penugasan Remun
		<span class="close"><i class="icon-remove icon-white"></i></span>
	</div>
	<div id="message" style="margin:10px"></div>
	<table class="table">
		<tr>
			<th>Nama Bukti Penugasan Remun</th>
			<td><input type="text" name="bukti" id="nama" class="input-xxlarge" placeholder="Masukkan nama file penugasan" required></td>
		</tr>
		<tr>
			<th>File Penugasan Remun</th>
			<td><input type="file" name="file_upload" id="userfile" class="btn btn-small"><br/>
				<div><div class="bs-callout-info" style="margin-top:5px; font-style:italic; border-left:solid 5px skyblue; padding:4px">Type file yang diperbolehkan adalah <b>JPG</b> atau <b>PDF</b></div></div>
			</td>
		</tr>
		<tr><td></td><td><input type="submit" id="simpan-upload" name="submit" value="Upload File Penugasan" class="btn btn-small btn-inverse btn-uin"></td></tr>
	</table>
</div>
<style>
	#modal10, #modal20{
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