<?php

// Fonction pour nettoyer les champs
function checkInput($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    $date = htmlentities($data);
    return $data;
}

// Fonction pour voir si c'est un email
function isEmail($var){
    return filter_var($var, FILTER_VALIDATE_EMAIL);
}

// Fonction pour voir si c'est un téléphone
function isPhone($var){
    $regExp = '/^[0-9]+$/';
    return preg_match($regExp, $var);
}