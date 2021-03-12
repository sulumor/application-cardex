<?php

namespace App;

class Auth{
    public static function est_connecte(){
        if(session_status() === PHP_SESSION_NONE){
            session_start();
        }
        return !empty($_SESSION['connecte']);
    }
    
    public static function force_utilisateur_connecte(string $path):void{
        if(!self::est_connecte()){
            header("Location: {$path}login.php");
            exit();
        }
    }
}