<?php
    require '../vendor/autoload.php';
    use App\{Database, Helpers};

    if(!empty($_GET['id'])){
        $id = Helpers::checkInput($_GET['id']);
    }

    if(!empty($_POST['id'])){
        $id = Helpers::checkInput($_POST['id']);
        $db = Database::connect();
        $statement = $db->prepare('DELETE FROM cardex WHERE id = ?');
        $statement->execute([$id]);
        Database::disconnect();
        header("Location: index.php");
    }
    
    $style = "../style/style.css";
    $pageTitle = "Suppression d'un client";
    require '../elements/header.php';
?>

<h1>Base de données client</h1>
<h2>Suppression d'un client</h2>
<form class="form-affichage" role="form" action="delete.php" method="post">
    <input type="hidden" name="id" value="<?= $id; ?>"/>
    <p>Etes vous sur de vouloir supprimer cette fiche client?</p>
    <div class="btns-container">
        <button type="submit">Oui </button>
        <a href="index.php">Non</a>
    </div>
</form>
    
<?php require '../elements/footer.php'?>