<?php

require_once "/inc/password/password.php";

function getPassword(\mysqli $conn, int $id) {
    $password = "";
    $stmt = $conn->prepare("SELECT passord FROM bruker WHERE id = ?");
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
    $stmt = $conn->prepare("UPDATE bruker SET passord = ? WHERE id = ?");
    $stmt->bind_param("si", $password, $id);
    $stmt->execute();
    if ($stmt->affected_rows === 1)
        return true;
        $stmt->close();
    return false;
}

function loginQuery(\mysqli $conn, string $email) {
    $data = [];

    $stmt = $conn->prepare("SELECT id, epost, passord, navn, rolle_id FROM bruker WHERE epost = ?");
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