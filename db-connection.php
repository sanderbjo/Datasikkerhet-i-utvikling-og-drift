<?php
$servername = "localhost";
$username = "gruppe4";
$password = "pass4";
$dbname = "dsiku";

// Opprett tilkobling
$conn = new mysqli($servername, $username, $password, $dbname);

// Sjekk tilkoblingsstatus
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
