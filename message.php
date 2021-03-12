<?php
    require 'vendor/autoload.php';
    use App\{Database, Helpers};

    if(!empty($_GET['id'])){
        $id = Helpers::checkInput($_GET['id']);
    }

    $civilite = $last_name = $email = $brand = $items = $emailError = "";

    if(!empty($_POST)){
        $destinataire = Helpers::checkInput($_POST['destinataire']);
        $sujet = Helpers::checkInput($_POST['sujet']);
        $emailText = Helpers::checkInput($_POST['text']);
	    $id = Helpers::checkInput($_GET['id']);
        $isSuccess = true;
                
        if(!Helpers::isEmail($destinataire)){
            $isSuccess = false;
            $emailError = "Ce n'est pas un email valide";
        }

        if($isSuccess){
            $headers = [ 
                'From' => 'computer04magasin@orange.fr',
                'Reply-To' => 'computer04magasin@orange.fr'
            ];

            if(mail($destinataire, $sujet, $emailText,$headers)){
                $db = Database::connect();
                $statement = $db->prepare("UPDATE cardex set email_success = :email_success WHERE id = :id");
                $statement->execute([
                    'email_success' => date('d/m/y H:i'), 
                    'id' => $id
                ]);
                Database::disconnect();
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
        $statement->execute([$id]);
        $client = $statement->fetch();
        $civilite   = $client['civilite'];
        $last_name  = $client['last_name'];
        $email      = $client['email'];
        $items      = $client['machine'];
        Database::disconnect();
    }

    $pageTitle = "Envoie d'un email";
    require 'elements/header.php';
?>
<h1>Cardex Client</h1>
    <h2>Envoie d'un email</h2>
        <form class = "form-message" role="form" action="<?= 'message.php?id=' .$id;?>" method ="post">
            <div class="form-group">
                <label for="destinataire">Destinataire :</label>
                <input type="text" name="destinataire" value = "<?= $email; ?>">
                <p><?= $emailError; ?></p>
            </div>
            <div class="form-group">
                <label for="sujet">Sujet :</label>
                <input type="text" name="sujet" value = "Reparation de votre <?= $items; ?> chez Computer 04 SAS.">
            </div>
            <div class = "form-group">
                <label for="text">Message: </label>
                <textarea name="text"cols="auto" rows="9"><?= Helpers::retourText($civilite, $last_name, $items)?></textarea>
            </div>
            <div class="btns-container">
                <button type="submit">Envoyer </button>
                <a class="btn"  href="index.php">Retour</a> 
            </div>    
        </form>
    </div>

<?php require 'elements/footer.php';   
                