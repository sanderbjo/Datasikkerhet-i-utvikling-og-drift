<?php
$dbHostname = "localhost";
$dbUsername = "gruppe4";
$dbPassword = "pass4";
$dbName = "dsiku";

$conn = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    exit("Can't connect to database\n" . $conn->connect_error);
}
// $url =  $_SERVER['REQUEST_URI'];
// $url_components = parse_url($url);
// parse_str($url_components['query'], $params);
 if($_GET['emnekode']) {
    $emnekode = $_GET['emnekode'];
    $sql = "SELECT * FROM melding WHERE emne_emnekode = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $emnekode);
    $stmt->execute();
    $result = $stmt->get_result();
    $emne = $result->fetch_assoc();

    $data = array();
    while($row = $result->fetch_assoc()) {
        $data[] = $row;
    }
    header('Content-Type: application/json');
    echo json_encode($data,JSON_PRETTY_PRINT);
}
else{
    echo $_GET['emnekode'];

    $sql = "SELECT * FROM emne";
    $result = $conn->query($sql);

    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Frigjør ressurser
    $result->close();

    header('Content-Type: application/json');
    echo json_encode($data,JSON_PRETTY_PRINT);
}