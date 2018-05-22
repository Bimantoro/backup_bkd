<script type="text/javascript" src="http://surat.uin-suka.ac.id/asset/js/jquery.tokeninput.js"></script>
<link rel="stylesheet" href="http://surat.uin-suka.ac.id/asset/css/token-input.css" type="text/css" />
<script type="text/javascript">
 	$(document).ready(function() {
		$("#dosen").tokenInput("http://akademik.uin-suka.ac.id/bkd/dosen/bebankerja/get_dosen_xxx");
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
<form action="" method="POST">
<table class="table">
	<tr id="partner-penelitian">
		<td>
			<div class="tujuan-surat">
				<div class="auto-surat grup">
				<div class="label-input">Dosen/Staff</div>
				<input type="text" name="dosen" id="dosen"/>
			</div>
		</td>
	</tr>
	<tr>
		<td><input type="submit" name="submit" value="GENERATE" class="btn btn-uin"></td>
	</tr>
</table>
</form>

<?php
	if(isset($_POST['submit'])){
		$data = $_POST['dosen'];
		$dosen = explode('<$>', $data);
		echo "<pre>";
			print_r($dosen);
		echo "</pre>";
	}
?>