<!DOCTYPE html>
<html>
<body>

<?php
   require "includes/db-connection.php";

   $sql = "INSERT INTO `svar` (`id`,`innhold`, `melding_id`, `bruker_id`) VALUES ('".$_POST["id"]."','".$_POST["svar"]."', '".$_POST["id"]."', '".$_POST["bruker_id"]."');";

  try {

    if ($conn->query($sql) === TRUE) {
        echo "Svaret ditt har blitt registrert";
    } else {
        echo "Det oppstod en feil";
    }
} catch (mysqli_sql_exception $e) {
    // Check if it's a duplicate entry error
    if ($e->getCode() === 1062) { // 1062 is the MySQL error code for duplicate entry
        echo "Denne meldingen har allerede et svar!";
    } else {
        // Handle other database-related errors
        echo "det skjedde en uforvented database feil!";
    }
}
   echo "<br><a href='foreleser.php?id=".$_POST["bruker_id"]."'>tilbake til foreleser siden</a>";
   $conn->close();
   ?>


</body>
</html>