<!DOCTYPE html>
<html>
<body>

<?php
   require "includes/db-connection.php";


   $sql = "INSERT INTO `svar` (`id`,`innhold`, `melding_id`, `bruker_id`) VALUES ('".$_POST["id"]."','".$_POST["svar"]."', '".$_POST["id"]."', '".$_POST["bruker_id"]."');";

   if ($conn->query($sql) === TRUE) {
    echo "Svaret ditt har blitt registert";

   } else {
    echo "Error: " . $sql . "<br>" . $conn->error;

   }

   $conn->close();
   ?>


</body>
</html>