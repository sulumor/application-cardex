<?php

require 'vendor/autoload.php';

use App\Auth;

$erreur = null;
$password = '$2y$10$E53DM7cZhyjZmWO/orH0SupBhxJvmdyu9ppwrv0HAO8MNRfiarIPi'; 

if(!empty($_POST['pseudo']) && !empty($_POST['password'])){
    if($_POST['pseudo'] === 'Computer04SAS' && password_verify($_POST['password'], $password)){
        session_start();
        $_SESSION['connecte'] = 1;
        header('Location: ./index.php');
        exit();
    }else{
        $erreur = "Identifiants incorrects";
    }
}

if(Auth::est_connecte()){
    header('Location: ./index.php');
    exit();
}

$pageTitle = "Connexion";
require 'elements/header.php';
?>
<h1>Connexion au cardex</h1>
<form action="" method="post"> 
    <input class="login" type="text" name="pseudo" placeholder="Entrer votre login">
    <input class="login" type="password" name="password" placeholder="Entrer votre mot de passe">
    <button type="submit">Connexion</button>
</form>

<?php if($erreur):?>
    <div class="alert">
        <?= $erreur ?>
    </div>
<?php endif;?>