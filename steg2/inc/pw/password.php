<?php

define("PV_CON_MINIMUM_PASSWORD_LENGTH", 6);

define("PV_ERR_OK", 0);
define("PV_ERR_PASSWORD_TOO_SHORT", 1);

function validatePassword(string $password) {
    if (strlen($password) <= PV_CON_MINIMUM_PASSWORD_LENGTH)
        return PV_ERR_PASSWORD_TOO_SHORT;
    return PV_ERR_OK;
}

function pepperPassword(string $password) {
    # $pepperFilePath = dirname(__DIR__)."/pepper.txt";

    # $pepperFile = fopen($pepperFilePath, "r");
    # $pepper = fread($pepperFile, filesize($pepperFilePath));
    # fclose($pepperFile);
    $pepper = "859f6639726e44eb51ee241d3a2610e37122a03381f4b31476e06885cef18461";
    $pepperedPassword = hash_hmac("sha256", $password, $pepper);
    return $pepperedPassword;
}

function hashPassword(string $password) {
    $pepperedPassword = pepperPassword($password);
    return password_hash($pepperedPassword, PASSWORD_DEFAULT);
}
