<?php
    require 'class/Database.php';
    require 'admin/function.php';

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    }

    if(!empty($_POST['id'])){
        $id = checkInput($_POST['id']);
        $affichage = 'non';
        $db = Database::connect();
        $statement = $db->prepare("UPDATE cardex set affichage = ? WHERE id = ?");
        $statement->execute(array($affichage,$id));
        Database::disconnect();
        header("Location: index.php");
    }

    $pageTitle = "Affichage client";
    require 'elements/header.php';
?>
<h1>Cardex client</h1>

<h2>Affichage client</h2>

    <form class="form-affichage" role="form" action="affichage.php" method="post">
        <input type="hidden" name="id" value="<?php echo $id; ?>"/>
        <p class="alert alert-warning">Etes vous sûr de ne plus afficher ce client?</p>
        <div class="btns-container">
            <button type="submit"> Oui </button>
            <a href="index.php"> Non</a>
        </div>
    </form>
   
<?php require 'elements/footer.php' ?>