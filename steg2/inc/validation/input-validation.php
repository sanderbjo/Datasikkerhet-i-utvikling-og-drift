<?php

define("IV_MINIMUM_PASSWORD_LENGTH", 6);


define("IV_E_ALL_OK", 0);

define("IV_E_UPLOAD_ERROR", 1);
define("IV_E_NOT_A_VALID_IMAGE", 2);

define("IV_E_NOT_A_VALID_EMAIL", 3);

define("IV_E_PASSWORD_TOO_SHORT", 4);


function validateImage(string $file) {
    # Gyldige filtyper
    $allowed_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);

    if ($file['error'] !== UPLOAD_ERR_OK)
        return IV_E_UPLOAD_ERROR;
    if (!in_array(exif_imagetype($file), $allowed_types))
        return IV_E_NOT_A_VALID_IMAGE;
    return IV_E_ALL_OK;
}

function validateEmail(string $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return IV_E_NOT_A_VALID_EMAIL;
    return IV_E_ALL_OK;
}

function validatePassword(string $password) {
    if (strlen($password) <= IV_MINIMUM_PASSWORD_LENGTH)
        return IV_E_PASSWORD_TOO_SHORT;
    return IV_E_ALL_OK;
}
