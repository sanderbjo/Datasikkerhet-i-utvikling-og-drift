<?php
$dbHostname = "localhost";
$dbUsername = "gruppe4";
$dbPassword = "pass4";
$dbName = "dsiku";

$conn = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    exit("Can't connect to database\n" . $conn->connect_error);
}

$name = $_POST['name'];
$email = $_POST['email'];
$password = $_POST['password'];

if(empty($name) || empty($email) || empty($password)) {
    echo'mangler data for å fylle inn';
}
else{
    $sql = "INSERT INTO bruker (navn, epost, passord, rolle_id) VALUES (?, ?, ?, 2)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $name, $email, $password);
    $stmt->execute();

    if ($stmt->affected_rows === 1) {
            echo 'velykket registrering';
    } else {
        echo 'ikke velykket registrering';
    }
    header('HTTP/1.1 201 Created');
}