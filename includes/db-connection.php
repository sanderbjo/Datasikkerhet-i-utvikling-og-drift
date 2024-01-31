<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbHostname = "";
$dbUsername = "";
$dbPassword = "";
$dbName = "";

$conn = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    exit("Can't connect to database\n" . $conn->connect_error);
}