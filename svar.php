<!DOCTYPE html>
<html>
<body>

<?php
   require "includes/db-connection.php";
   $sql = "INSERT INTO `svar` (`innhold`, `melding_id`, `bruker_id`) VALUES ('".$_POST["svar"]."', '".$_POST["id"]."', '".$_POST["bruker_id"]."');";
   

   if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }
  

  $conn->close();
   ?>


</body>
</html>