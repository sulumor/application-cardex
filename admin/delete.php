<?php
    require '../class/Database.php';
    require 'function.php';

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    }

    if(!empty($_POST['id'])){
        $id = checkInput($_POST['id']);
        $db = Database::connect();
        $statement = $db->prepare('DELETE FROM cardex WHERE id = ?');
        $statement->execute(array($id));
        Database::disconnect();
        header("Location: index.php");
    }

    $pageTitle = "Suppression d'un client";
    require '../elements/header.php';
?>

<h1>Base de données client</h1>
<h2>Suppression d'un client</h2>
<form class="form-affichage" role="form" action="delete.php" method="post">
    <input type="hidden" name="id" value="<?php echo $id; ?>"/>
    <p>Etes vous sur de vouloir supprimer cette fiche client?</p>
    <div class="btns-container">
        <button type="submit">Oui </button>
        <a href="index.php">Non</a>
    </div>
</form>
    
<?php require '../elements/footer.php' ?>