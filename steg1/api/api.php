<?php
$con = new mysqli("158.39.188.206/", "gruppe4", "pass4", "dsiku");

// if($con){
//     echo "DB connected";
// }else{
//     echo "Database connection failed";
// }


if($_SERVER['REQUEST_METHOD'] === 'GET') {
    //side med oversikt over alle emner
    if($_SERVER['REQUEST_URI'] === '/emne') {
        $sql = "SELECT * FROM emne";
        $result = $mysqli->query($sql);

        $data = array();
        while ($row = $result->fetch_assoc()) {
            $data[] = $row;
        }

        header('Content-Type: application/json');
        echo json_encode($data);
    }

    //profilside, med side om din bruker
    if(preg_match('/^\/users\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
        $id = $matches[1];

        $sql = "SELECT * FROM bruker where id = ?";
        $stmt = $mysqli->$stmt_prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        $data = array();
        $data[] = $user;

        header('Content-Type: application/json');
        echo json_encode($data);
    }
    //TODO lage hente mld
    if(preg_match('/^\/emne\/(\d+)$/', $_SERVER['REQUEST_URI'], $matches)) {
        $id = $matches[1];

        $sql = "SELECT * FROM melding where emne_emnekode = ?";
        $stmt = $mysqli->$stmt_prepare($sql);
        $stmt->bind_param("s", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $emne = $result->fetch_assoc();

        $data = array();
        $data[] = $emne;

        header('Content-Type: application/json');
        echo json_encode($data);
    }


    //registrere bruker
    // $id = $_SERVER['REQUEST_URI']
} else if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($_SERVER['REQUEST_URI'] === '/users)') {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $password = $_POST['password'];

        $sql = 'INSERT INTO bruker (name, email, password) VALUES (?, ?, ?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sss', $name, $email, $password);
        $stmt->execute();

        header('HTTP/1.1 201 Created');
    }
    //login
    if($_SERVER['REQUEST_URI'] === '/login') {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $valid_password = password_verify($password, $valid_password);

        if($valid_password === true) {
            session_start();
            $_SESSION['email'] = $email;
            header('Location: /home');
        } else {
            echo'<p>Feil brukernavn eller passord.</p>';
        }
    }
    //TODO Gjøre ferdig sende mld
    //sende mld
    if($_SERVER['REQUEST_URI'] === '/emne') {
        $user_id = $_SESSION['user_id'];
        $topic_id = $_POST['topic_id'];
        $message = $_POST['meessage'];

        $sql = 'INSERT INTO melding(bruker_id, emne_emnekode, innhold) VALUES (? ? ?)';
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('iss', $user_id, $topic_id, $message);
        $stmt->execute();

        header('HTTP/1.1 201 Created');
    }

}

?>