<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbHostname = "localhost";
$dbUsername = "gruppe4";
$dbPassword = "pass4";
$dbName = "dsiku";

$conn = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    exit("Can't connect to database\n" . $conn->connect_error);
}