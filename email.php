<?php
    require 'class/Database.php';
    require 'admin/function.php';

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    }

    $civilite = $last_name = $email = $brand = $items = $emailError = "";

    if(!empty($_POST)){
        $destinataire = checkInput($_POST['destinataire']);
        $sujet = $_POST['sujet'];
        $emailText = $_POST['text'];
        $isSuccess = true;
                
        if(!isEmail($destinataire)){
            $isSuccess = false;
            $emailError = "Ce n'est pas un email valide";
        }

        if($isSuccess){
            $headers=array(
                "From" => "computer04magasin@orange.fr",
                "Reply to" =>"computer04magasin@orange.fr"
            );

            if(mail($destinataire, $sujet, $emailText,$headers)){
                header("Location: index.php");
            }else{
                echo "Echec de l'envoie de l'email à $destinataire";
            }
        }
    }else{
        $db = Database::connect();
        $statement = $db->prepare('SELECT cardex.civilite, cardex.last_name, cardex.email, items.name AS machine 
        FROM cardex 
        LEFT JOIN items ON cardex.items_category = items.id 
        WHERE cardex.id IN (?)');
        $statement->execute(array($id));
        $client = $statement->fetch();
        $civilite   = $client['civilite'];
        $last_name  = $client['last_name'];
        $email      = $client['email'];
        $items      = $client['machine'];
        Database::disconnect();
    }

    $pageTitle = "Envoie email";
    require 'elements/header.php';
 
?>

<h1>Cardex Client</h1>
<h2>Envoie d'un email</h2>
    <form class = "form-message" role="form" action="<?php echo 'message.php? id =' .$id;?>" method ="post">
        <div class="form-group">
            <label for="destinataire">Destinataire :</label>
            <input type="text" name="destinataire" value = "<?php echo $email; ?>">
            <p><?php echo $emailError; ?></p>
        </div>
        <div class="form-group">
            <label for="sujet">Sujet :</label>
            <input type="text" name="sujet" value = "Réparation de votre <?php echo $items; ?> chez Computer 04 SAS.">
        </div>
        <div class = "form-group">
            <label for="text">Message: </label>
            <textarea name="text"cols="auto" rows="9">
Bonjour <?php echo $civilite; ?> <?php echo $last_name; ?>, 
                    
    Veuillez noter que votre <?php echo $items;?> est disponible.
                    
    Merci de passer au magasin aux heures d'ouvertures.
                    
Cordialement,
                    
    Computer 04 SAS
            </textarea>
        </div>
        <div class="btns-container">
            <button type="submit">Envoyer </button>
            <a class="btn"  href="index.php">Retour</a> 
        </div>    
    </form>
</div>

<?php require 'footer.php' ?>
                