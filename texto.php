<?php
    require 'admin/Database.php';

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    }

    $civilite = $last_name = $telephone = $brand = $items = $telephoneError = "";

    if(!empty($_POST)){
        
        $destinataire = checkInput($_POST['destinataire']);
        $textoText = $_POST['text'];
        $isSuccess = true;
         
        if(!isPhone($destinataire)){
            $telephoneError = "Ce n'est pas un numéro valide!";
            $isSuccess = false;
        }
        
        if($isSuccess){
            require __DIR__ . '/twilio-php-main/src/Twilio/autoload.php';
            // use Twilio\Rest\Client;

            $account_sid = 'AC5b9ee2ddbba440ddce73005dcdc723c5';
            $auth_token = '4d32b5781874b469a547e1d5268f019e';
            // In production, these should be environment variables. E.g.:
            // $auth_token = $_ENV["TWILIO_AUTH_TOKEN"]

            // A Twilio number you own with SMS capabilities
            $twilio_number = "+14159441287";

            $client = new Twilio\Rest\Client($account_sid, $auth_token);
            $client->messages->create(
                // Where to send a text message (your cell phone?)
                $destinataire,
                array(
                    'from' => $twilio_number,
                    'body' => $textoText
                )
            );

            header("Location: index.php");
        }
    }else{
        $db = Database::connect();
        $statement = $db->prepare(' SELECT cardex.civilite, cardex.last_name, cardex.phone, items.name AS machine 
                                    FROM cardex 
                                    LEFT JOIN items ON cardex.items_category = items.id 
                                    WHERE cardex.id IN (?)');
        $statement->execute(array($id));
        $client = $statement->fetch();
        $civilite   = $client['civilite'];
        $last_name  = $client['last_name'];
        $telephone      = $client['phone'];
        $items      = $client['machine'];
        Database::disconnect();
    }

    function checkInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function isPhone($var){
        $regExp = '#^\+[1-9]\d{1,12}$#';
        return preg_match($regExp, $var);
    }

?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Cardex</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <h1>Cardex Client</h1>
        <h2>Envoie d'un texto</h2>
            <form class = "form-message" role="form" action="<?php echo 'texto.php? id =' .$id;?>" method ="post">
                <div class="form-group">
                    <label for="destinataire">Destinataire :</label>
                    <input type="numero" name="destinataire" value = "+33<?php echo $telephone; ?>">
                    <p><?php echo $telephoneError; ?></p>
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
    </body>
</html>