<?php

function pepperPassword(string $password) {
    $pepperFilePath = "/inc/password/pepper.txt";

    $pepperFile = fopen($pepperFilePath, "r");
    $pepper = fread($pepperFile, filesize($pepperFilePath));
    fclose($pepperFile);
    
    $pepperedPassword = hash_hmac("sha256", $password, $pepper);
    return $pepperedPassword;
}

function hashPassword(string $password) {
    $pepperedPassword = pepperPassword($password);
    return password_hash($pepperedPassword, PASSWORD_DEFAULT);
}
