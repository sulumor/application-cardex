<?php

require 'vendor/autoload.php';
use App\{Database, Helpers};

if(!empty($_GET['id'])){
        $id = Helpers::checkInput($_GET['id']);
    }

    if(!empty($_POST['id'])){
        $id = Helpers::checkInput($_POST['id']);
        $db = Database::connect();
        $statement = $db->prepare("UPDATE cardex set affichage = :affichage, email_success = :email_success, fixed = :fixed WHERE id = :id");
        $statement->execute([
            'affichage' => 'non',
            'email_success' => '',
            'fixed' => '',
            'id' => $id
        ]);
        Database::disconnect();
        header("Location: index.php");
    }

    $pageTitle = "Affichage client";
    require 'elements/header.php';
?>

<h1>Cardex client</h1>
<h2>Affichage client</h2>
    <form class="form-affichage" role="form" action="affichage.php" method="post">
        <input type="hidden" name="id" value="<?= $id; ?>"/>
        <p>Etes vous sûr de ne plus afficher ce client?</p>
        <div class="btns-container">
            <button type="submit"> Oui </button>
            <a href="index.php"> Non</a>
        </div>
    </form>

<?php require 'elements/footer.php' ?>    