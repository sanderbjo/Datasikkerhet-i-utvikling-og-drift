<!DOCTYPE html>
<html>
<body>

<?php
   require "includes/db-connection.php";

   echo "svar: ".$_POST["svar"];
   echo "id: ".$_POST["id"];
   echo "bruker_id: ".$_POST["bruker_id"];

   $sql = "INSERT INTO `svar` (`id`, `innhold`, `melding_id`, `bruker_id`) VALUES ('3', '".$_POST["svar"]."', '".$_POST["id"]."', '".$_POST["bruker_id"]."');";
   $result = $conn->query($sql);

  $conn->close();
   ?>


</body>
</html>