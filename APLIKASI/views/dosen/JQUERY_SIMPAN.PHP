<form name="frm" method="POST" action="">
 <input type="text" name="name" id="name" value="" />
 <input type="text" name="last_name" id="last_name" value="" />
 <input type="submit" name="Update" id="update" value="Update" />
</form>

<SCRIPT TYPE="TEXT/JAVASCRIPT">
$("#update").click(function(e) {
  e.preventDefault();
  var name = $("#name").val(); 
  var last_name = $("#last_name").val();
  var dataString = 'name='+name+'&last_name='+last_name;
  $.ajax({
    type:'POST',
    data:dataString,
    url:'insert.php',
    success:function(data) {
      alert(data);
    }
  });
});
</SCRIPT>

<?php
  $name = $_POST['name'];
  $last_name = $_POST['last_name'];
  $insert = "insert into TABLE_NAME values('$name','$last_name')";// Do Your Insert Query
  if(mysql_query($insert)) {
   echo "Success";
  } else {
   echo "Cannot Insert";
  }
?>