<?php

require_once dirname(__DIR__)."../../pw/password.php";

function getPassword(\mysqli $conn, int $id) {
    $password = "";
    $stmt = $conn->prepare("SELECT password FROM user WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($password);
        $stmt->fetch();
    }
    $stmt->close();
    return $password;
}

# Passordet må være i plaintext
function setPassword(\mysqli $conn, int $id, string $password) {
    $password = hashPassword($password);
    $stmt = $conn->prepare("UPDATE user SET password = ? WHERE id = ?");
    $stmt->bind_param("si", $password, $id);
    $stmt->execute();
    if ($stmt->affected_rows === 1)
        $err = true;
    else 
        $err = false;
    $stmt->close();
    return $err;
}

function loginQuery(\mysqli $conn, string $email) {
    $data = [];

    $stmt = $conn->prepare("SELECT id, email, password, name, role_id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($data["id"], $data["email"], $data["password"], $data["name"], $data["role"]);
        $stmt->fetch();
    }
    $stmt->close();
    return $data;
}

function addUser(\mysqli $conn, string $email, string $password, string $name, int $role) {
    $hashedPassword = hashPassword($password);

    $stmt = $conn->prepare("INSERT INTO user (name, email, password, role_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $email, $hashedPassword, $role);
    $stmt->execute();

    if ($stmt->affected_rows === 1)
        $err = true;
    else 
        # $err = $stmt->errno;
        $err = false;
    $stmt->close();
    return $err;
}

function checkIfEmailExists(\mysqli $conn, string $email) {
    $exists = false;
    $stmt = $conn->prepare("SELECT email FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) 
        $exists = true;
    
    $stmt->close();
    return $exists;
}

function getId(\mysqli $conn, string $email) {
    $id = -1;
    $stmt = $conn->prepare("SELECT id FROM user WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($id);
        $stmt->fetch();
    }
    $stmt->close();
    return $id;
}

function setImage(\mysqli $conn, int $id, string $image) {
    $stmt = $conn->prepare("UPDATE user SET image = ? WHERE id = ?");
    $stmt->bind_param("si", $image, $id);
    $stmt->execute();
    if ($stmt->affected_rows === 1)
        $err = true;
    else 
        $err = false;
    $stmt->close();
    return $err;
}

function getAllLecturers(\mysqli $conn) {
    $result = [];
    $query = "SELECT id, name, image FROM user WHERE role_id = 1";
    $result = $conn->query($query);
    return $result;
}