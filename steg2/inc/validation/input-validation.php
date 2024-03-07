<?php

define("IV_ERR_OK", 0);

define("IV_ERR_UPLOAD_ERROR", 1);
define("IV_ERR_NOT_A_VALID_IMAGE", 2);

define("IV_ERR_NOT_A_VALID_EMAIL", 3);

function validateImage(string $file) {
    # Gyldige filtyper
    $allowed_types = array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG);

    if ($file['error'] !== UPLOAD_ERR_OK)
        return IV_ERR_UPLOAD_ERROR;
    if (!in_array(exif_imagetype($file), $allowed_types))
        return IV_ERR_NOT_A_VALID_IMAGE;
    return IV_ERR_OK;
}

function validateEmail(string $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return IV_ERR_NOT_A_VALID_EMAIL;
    return IV_ERR_OK;
}
