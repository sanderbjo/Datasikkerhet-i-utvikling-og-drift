<?php
require_once "includes/validate.php";
?>

<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logg inn</title>
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>

<?php
require_once "modules/header.php"
?>

<h1>Foreleser</h1>
<h2>Meldinger</h2>
<div class="meldinger"> <div>

<?php
   require "includes/db-connection.php";

   $urlq = $_SERVER['QUERY_STRING'];

   parse_str($urlq, $queryArray);
   $id = $queryArray['id'];

   $servername = "158.39.188.206";

   $sql = "SELECT bruker_id,emnekode FROM emne WHERE bruker_id = '".$id."';";
   $result = $conn->query($sql);


   if ($result->num_rows > 0) {
       while ($row = $result->fetch_assoc()) {
           $emnekode = $row["emnekode"];
       }
   } else {
       echo "0 results";
   }
   $sql = "SELECT id,innhold,emne_emnekode FROM melding WHERE emne_emnekode = '".$emnekode."';";
   $result = $conn->query($sql);

   if ($result->num_rows > 0) {
       while ($row = $result->fetch_assoc()) {
           echo "meldings id: " . $row["id"]. "<br>Melding: " . $row["innhold"]."<br>emnekode: ".$row["emne_emnekode"]. "<br>
           <form action='svar.php' method='post'>
           Svar: <input type='text' name='svar'><br>
           <input type='hidden' name='bruker_id' value='".$id."'>
           <input type='hidden' name='emnekode' value='".$row["emne_emnekode"]."'>
           <input type='hidden' name='id' value='".$row["id"]."'>
           <input type='submit' value='Send inn ditt svar!'>
           </form>";
       }
   } else {
       echo "0 results";
   }
   $conn->close();
   ?>


</body>
</html>