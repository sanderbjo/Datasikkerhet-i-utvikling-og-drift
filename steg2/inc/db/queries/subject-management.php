<?php

function checkIfSubjectExists(\mysqli $conn, string $code) {
    $exists = false;
    $stmt = $conn->prepare("SELECT code FROM subject WHERE code = ?");
    $stmt->bind_param("s", $code);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) 
        $exists = true;
    
    $stmt->close();
    return $exists;
}

function addSubject(\mysqli $conn, string $name, string $code, string $pin, int $id) {
    $stmt = $conn->prepare("INSERT INTO subject (name, code, pin, user_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssi", $name, $code, $pin, $id);
    $stmt->execute();
    
    if ($stmt->affected_rows === 1)
        $err = true;
    else 
        # $err = $stmt->errno;
        $err = false;
    $stmt->close();
    return $err;
}

function getAllSubjects(\mysqli $conn) {
    $result = [];
    $query = "SELECT id, name, code, pin, user_id FROM subject";
    $result = $conn->query($query);
    return $result;
}

function getSubject(\mysqli $conn, int $id) {
    $result = false;
    $stmt = $conn->prepare("SELECT id, name, code, pin, user_id FROM subject WHERE id = ?");
    $stmt->bind_param("s", $id);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows === 1) {
        $stmt->bind_result($result);
        $stmt->fetch();
    }
    $stmt->close();
    return $result;
}