<?php
    require 'Database.php';

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

    function checkInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Suivi client</title>
        <meta charset="UTF-8">
        <title>Base de données</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   
    </head>
    <body>
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
    </body>        
</html>    