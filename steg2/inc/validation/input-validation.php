<?php

define("IV_ERR_OK", 0);

define("IV_ERR_UPLOAD_ERROR", 1);
define("IV_ERR_NOT_A_VALID_IMAGE", 2);

define("IV_ERR_NOT_A_VALID_EMAIL", 3);

define("IV_ERR_NOT_A_VALID_SUBJECT_PIN", 4);


function validateImage($file) {
    # Gyldige filtyper
    $allowedMimeTypes = array(/*'image/gif',*/ 'image/jpeg', 'image/png');
    $allowedExifTypes = array(/*IMAGETYPE_GIF,*/ IMAGETYPE_JPEG, IMAGETYPE_PNG);

    if ($file['error'] !== UPLOAD_ERR_OK)
        return IV_ERR_UPLOAD_ERROR;

    if (!in_array($file['type'], $allowedMimeTypes)) {
        return IV_ERR_NOT_A_VALID_IMAGE;
    }

    if (!in_array(exif_imagetype($file["tmp_name"]), $allowedExifTypes))
        return IV_ERR_NOT_A_VALID_IMAGE;

    return IV_ERR_OK;
}

function validateEmail(string $email) {
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return IV_ERR_NOT_A_VALID_EMAIL;
    return IV_ERR_OK;
}

function validateSubjectPin(string $pin) {
    if (!is_numeric($pin))
        return IV_ERR_NOT_A_VALID_SUBJECT_PIN;
    if (strlen($pin) !== 4)
        return IV_ERR_NOT_A_VALID_SUBJECT_PIN;
    
    return IV_ERR_OK;
}

function convertPngToJpg(string $pngFilePath, string $jpgFilePath) {
    $image = imagecreatefrompng($pngFilePath);
    if (!$image) 
        return false;

    
    $result = imagejpeg($image, $jpgFilePath, 80);
    imagedestroy($image);
    return $result;
}