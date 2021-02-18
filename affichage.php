<?php
    require 'admin/Database.php';

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
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        
    </head>
    <body>
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
    </body>
</html>    