<?php
# TODO: Må fjerne error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

$dbHostname = "localhost";
$dbUsername = "ApplicationUser";
$dbPassword = "AHYrPIR2XZe_KhCraeB7hMRDFutBnNtsZIvKgn-AOxk";
$dbName = "dsiku2";

$conn = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    exit("Can't connect to database\n" . $conn->connect_error);
}
