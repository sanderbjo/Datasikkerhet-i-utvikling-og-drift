<!DOCTYPE html>
<html>
<body>

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

