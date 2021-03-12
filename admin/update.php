<?php
    require '../vendor/autoload.php';
    use App\{Database, Helpers};

    if(!empty($_GET['id'])){
        $id = Helpers::checkInput($_GET['id']);
    }

    $civilite = $first_name = $last_name = $phone = $email = $items = $password = $affichage = $historique = $phoneError = $emailError = "";

    if(!empty($_POST)){
        $civilite   = Helpers::checkInput($_POST['civilite']);
        $first_name = Helpers::checkInput($_POST['first_name']);
        $last_name  = Helpers::checkInput($_POST['last_name']);
        $phone      = Helpers::checkInput($_POST['phone']);
        $email      = Helpers::checkInput($_POST['email']);
        $items      = Helpers::checkInput($_POST['items']);
        $password   = Helpers::checkInput($_POST['password']);
        $historique = Helpers::checkInput($_POST['historique']);
        $affichage  = Helpers::checkInput($_POST['affichage']);
        $isSuccess  = true; 

        if(!empty($email)){
            if(!Helpers::isEmail($email)){
                $emailError = "Ce n'est pas un email valide!";
                $isSuccess = false;
            }
        }

        if(!empty($phone)){
            if(!Helpers::isPhone($phone)){
                $phoneError = "Ce n'est pas un numéro valide!";
                $isSuccess = false;
            }
        }

        if(empty($first_name) && empty($last_name) && empty($phone) && empty($email) && empty($password)){
            $isSuccess = false;
        }

        if($isSuccess){
        $db = Database::connect();
        $statement = $db->prepare("UPDATE cardex set civilite = ?, affichage = ?, last_name = ?, first_name = ?, email = ?, phone = ?, items_category = ?, password = ?, historique = ? WHERE id = ?");
        $statement->execute([$civilite, $affichage, $last_name, $first_name, $email, $phone, $items, $password,$historique, $id]);
        Database::disconnect();
        header("Location: ../index.php");
        }
    }else{
        $db = Database::connect();
        $statement = $db->prepare("SELECT * FROM cardex WHERE id = ?");
        $statement->execute([$id]);
        $client = $statement->fetch();
        $civilite   =$client['civilite'];
        $first_name = $client['first_name'];
        $last_name  = $client['last_name'];
        $phone      = $client['phone'];
        $email      = $client['email'];
        $items      = $client['items_category'];
        $password   = $client['password'];
        $historique = $client['historique'];
        $affichage  = $client['affichage'];
        Database::disconnect();
    }

    $style = "../style/style.css";
    $pageTitle = "Modifier une fiche client";
    require '../elements/header.php';
?>
<h1>Base de données client</h1>
<h2>Modification d'un client</h2>
    <form class="form" role="form" action="<?= 'update.php?id='.$id; ?>" method="post">
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
                <input type="text" name="last_name" value="<?= $last_name; ?>">
            </div>                      
            <div class="form-group">
                <label for="firstname">Prenom</label>
                <input type="text" name="first_name" value="<?= $first_name; ?>">
            </div>  
            <div class="form-group">
                <label for="email">Email</label>
                <input type="text" name="email" value="<?= $email; ?>">
                <p><?= $emailError;?></p>
            </div>                    
                                    
        </div>
        <div class="row">                        
            <div class="form-group histo">
                <label for="historique">Historique</label>
                <textarea  class="full" name="historique">-<?= date('d/m/y') ?>- <?= "\n" ?><?= $historique?></textarea>
            </div>                  
        </div>
        <div class="row">           
            <div class="form-group">
                <label for="phone">Téléphone</label>
                <input type="text" name="phone" value="<?= $phone;?>">
                <p><?= $phoneError;?></p>
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
                <label for="password">Mot de Passe</label>
                <input type="text" name="password" value="<?= $password; ?>">
            </div>                    
        </div>
        <div class="btns-container">    
            <button type="submit"><span class="fa fa-user-plus"></span> Modifier</button>
            <a  href="index.php"><span class="fa fa-arrow-left"></span> Retour BDD</a>               
            <a  href="../index.php"><span class="fa fa-arrow-left"></span> Retour Accueil</a>               
        </div>
    </form>
</div>

<?php require '../elements/footer.php'?>