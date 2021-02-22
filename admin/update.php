<?php
    require 'Database.php';

    if(!empty($_GET['id'])){
        $id = checkInput($_GET['id']);
    }

    $civilite = $first_name = $last_name = $phone = $email = $brand = $items = $password = $affichage = $historique = $phoneError = $emailError = "";

    if(!empty($_POST)){
        $civilite   =checkInput($_POST['civilite']);
        $first_name = checkInput($_POST['first_name']);
        $last_name  = checkInput($_POST['last_name']);
        $phone      = checkInput($_POST['phone']);
        $email      = checkInput($_POST['email']);
        $brand      = checkInput($_POST['brand']);
        $items      = checkInput($_POST['items']);
        $password   = checkInput($_POST['password']);
        $historique = checkInput($_POST['historique']);
        $affichage  = checkInput($_POST['affichage']);
        $isSuccess  = true; 

        if(!empty($email)){
            if(!isEmail($email)){
                $emailError = "Ce n'est pas un email valide!";
                $isSuccess = false;
            }
        }

        if(!empty($phone)){
            if(!isPhone($phone)){
                $phoneError = "Ce n'est pas un numéro valide!";
                $isSuccess = false;
            }
        }

        if(empty($first_name) && empty($last_name) && empty($phone) && empty($email) && empty($password)){
            $isSuccess = false;
        }

        if($isSuccess){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE cardex set civilite = ?, affichage = ?, last_name = ?, first_name = ?, email = ?, phone = ?, items_category = ?, brand_category = ?, password = ?, historique = ? WHERE id = ?");
        $statement->execute(array($civilite, $affichage, $last_name, $first_name, $email, $phone, $items, $brand, $password,$historique, $id));
        Database::disconnect();
        header("Location: index.php");
        }
    }else{
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM cardex WHERE id = ?");
        $statement->execute(array($id));
        $client = $statement->fetch();
        $civilite   =$client['civilite'];
        $first_name = $client['first_name'];
        $last_name  = $client['last_name'];
        $phone      = $client['phone'];
        $email      = $client['email'];
        $brand      = $client['brand_category'];
        $items      = $client['items_category'];
        $password   = $client['password'];
        $historique = $client['historique'];
        $affichage  = $client['affichage'];
        Database::disconnect();
    }

    function checkInput($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    function isEmail($var){
        return filter_var($var, FILTER_VALIDATE_EMAIL);
    }

    function isPhone($var){
        $regExp = '/^[0-9]+$/';
        $result = preg_match($regExp, $var);
        return $result;
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Base de donnée</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">   
    </head>
    <body>
        <h1>Base de données client</h1>
        <h2>Modification d'un client</h2>
            <form class="form" role="form" action="<?php echo 'update.php?id='.$id; ?>" method="post">
                <div class="row"> 
                    <div class="form-group">
                        <label for="affichage"> Affichage </label>
                        <select class = "form-control" name="affichage">
                            <option selected = "selected" value="oui"> Oui </option>
                            <option value="non"> Non </option>
                        </select>
                    </div>                      
                    <div class="form-group">
                        <label for="civilite">Civilité</label>
                        <select name="civilite" class = "form-control">
                            <option value="Mr">Monsieur</option>
                            <option value="Mme">Madame</option>
                        </select>
                    </div>                    
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" name="last_name" value="<?php echo $last_name; ?>">
                    </div>                      
                    <div class="form-group">
                        <label for="firstname">Prenom</label>
                        <input type="text" name="first_name" value="<?php echo $first_name; ?>">
                    </div>  
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="text" name="email" value="<?php echo $email; ?>">
                        <p><?php echo $emailError;?></p>
                    </div>                    
                                           
                </div>
                <div class="row">                        
                    <div class="form-group histo">
                        <label for="historique">Historique</label>
                        <textarea  class="full" name="historique"><?= $historique?></textarea>
                    </div>                  
                </div>
                <div class="row">           
                    <div class="form-group">
                        <label for="phone">Téléphone</label>
                        <input type="text" name="phone" value="<?php echo $phone;?>">
                        <p><?php echo $phoneError;?></p>
                    </div>            
                    <div class="form-group">
                        <label for="items">Machine </label>
                        <select class="form-control" name="items">
                            <?php
                                $db = Database::connect();
                                foreach($db->query('SELECT * FROM items')as $row){
                                    if($row['id'] == $items)
                                        echo '<option selected = "selected" value="'.$row['id'].'">'.$row['name'].'</option>';
                                    else    
                                        echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                                }
                                Database::disconnect();
                            ?>
                        </select>    
                    </div>
                    <div class="form-group">
                        <label for="brand">Marque </label>
                        <select class="form-control" name="brand">
                            <?php
                                $db = Database::connect();
                                foreach($db->query('SELECT * FROM brand')as $row){
                                    if($row['id'] == $brand)
                                        echo '<option selected = "selected" value="'.$row['id'].'">'.$row['brand_name'].'</option>';
                                    else
                                        echo '<option value="'.$row['id'].'">'.$row['brand_name'].'</option>';
                                }
                                Database::disconnect();
                            ?>
                        </select>    
                    </div>
                    <div class="form-group">
                        <label for="password">Mot de Passe</label>
                        <input type="text" name="password" value="<?php echo $password; ?>">
                    </div>                    
                </div>
                <div class="btns-container">    
                    <button type="submit"><span class="fa fa-user-plus"></span> Modifier</button>
                    <a  href="index.php"><span class="fa fa-arrow-left"></span> Retour BDD</a>               
                    <a  href="../index.php"><span class="fa fa-arrow-left"></span> Retour Accueil</a>               
                </div>
            </form>
        </div>
    </body>
</html>    