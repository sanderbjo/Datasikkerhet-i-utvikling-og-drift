<?php
$dbHostname = "localhost";
$dbUsername = "gruppe4";
$dbPassword = "pass4";
$dbName = "dsiku";

$conn = new mysqli($dbHostname, $dbUsername, $dbPassword, $dbName);

if ($conn->connect_error) {
    exit("Can't connect to database\n" . $conn->connect_error);
}
if($_POST['email'] && $_POST['password']){
    $email = $_POST['email'];
    $password = $_POST['password'];
    //echo $email;
    //echo $password;
    $stmt = $conn->prepare("SELECT id, epost, passord FROM bruker WHERE epost = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $resultId = $resultRoleId = -1;
        $resultEmail = $resultPassword = "";
        $stmt->bind_result($resultId, $resultEmail, $resultPassword);
        $stmt->fetch();
        //echo $resultPassword;
        if($resultPassword === $password) {
            session_start();
            $_SESSION['email'] = $email;
            echo'logged in';
            //header('Location: /home');
        } else {
            echo'<p>Feil brukernavn eller passord.</p>';
        }
    }
} else {
    echo 'ikke logget inn';
}