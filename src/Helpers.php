<?php

namespace App;

class Helpers{

    // Fonction pour nettoyer les champs
    public static function checkInput($data): string
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        $data = htmlentities($data);
        return $data;
    }

    // Fonction pour voir si c'est un email
    public static function isEmail($var)
    {
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    // Fonction pour voir si c'est un téléphone
    public static function isPhone($var)
    {
        $regExp = '/^[0-9]+$/';
        return preg_match($regExp, $var);
    }

    public static function retourText(string $civilite, string $last_name, string $items): string
    {
        return <<<HTML
        Bonjour $civilite $last_name,

        Veuillez noter que votre $items est disponible.
        Merci de passer au magasin aux heures d'ouvertures.

        Cordialement,
        Computer 04 SAS
        
        HTML;
        
    }

}