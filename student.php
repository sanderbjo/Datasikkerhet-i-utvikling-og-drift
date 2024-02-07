<?php
require_once "includes/validate.php";
?>
<!DOCTYPE html>
<html lang="nb">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student</title>
    <link rel="stylesheet" href="/css/style.css">
</head>
<body>

<?php
require_once "modules/header.php"
?>

<h1>Student</h1>
<h2>Velg Emne</h2>

<?php
require "includes/db-connection.php";

$servername = "158.39.188.206";

$sql = "SELECT emnekode, navn FROM emne;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<a href='" . $row["emnekode"]. ".php'>" . $row["navn"]. "</a> <br>";
    }
} else {
    echo "0 results";
}
$conn->close();
?>

</body>
</html>

