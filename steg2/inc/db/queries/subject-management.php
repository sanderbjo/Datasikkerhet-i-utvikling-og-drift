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

function addSubject(\mysqli $conn, string $name, string $code, string $pin) {
    $stmt = $conn->prepare("INSERT INTO subject (name, code, pin) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $code, $pin);
    $stmt->execute();
    
    if ($stmt->affected_rows === 1)
        $err = true;
    else 
        # $err = $stmt->errno;
        $err = false;
    $stmt->close();
    return $err;
}
