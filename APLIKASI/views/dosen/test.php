<script type="text/javascript">
$(document).ready(function(){
	$("#a").click(function(){
		var value = $(this).val();
		$("#x").val(value);
	});
});
</script>


	<input type="text" name="x" id="x">
	<input type="text" name="z" id="z" value="value Z">
	<button value="value Z" id="a">Set</button>
