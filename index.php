<?php
    require 'class/Database.php';
    require 'admin/function.php';
    

    $civilite = $first_name = $last_name = $phone = $email = $brand = $items = $password = $historique = $emptyError = $phoneError = $emailError = "";

    if(!empty($_POST)){
        $civilite = checkInput($_POST['civilite']);
        $first_name = checkInput($_POST['first_name']);
        $last_name  = checkInput($_POST['last_name']);
        $phone      = checkInput($_POST['phone']);
        $email      = checkInput($_POST['email']);
        $brand      = checkInput($_POST['brand']);
        $items      = checkInput($_POST['items']);
        $password   = checkInput($_POST['password']);
        $historique = checkInput($_POST['historique']);

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
            $emptyError = "Veuillez remplir au moins un champs !";
            $isSuccess = false;
        }

        if(!empty($first_name) && !empty($last_name)){
            // Voir si le client exist déjà
            $db = Database::connect();
            $statement = $db->prepare("SELECT * FROM cardex WHERE last_name = ? AND first_name = ?");
            $statement ->execute(array($last_name, $first_name));
            $test = $statement->fetch();
                //Si oui 
            if($test){
                $isSuccess = false;
                $statement = $db->prepare("UPDATE cardex set affichage = ? WHERE id = ?");
                $statement->execute(array('oui', $test['id']));
                $civilite = $first_name = $last_name = $phone = $email = $brand = $items = $password = $historique = $emailError = $emptyError = $phoneError = "";
            }else{
                // Si non
                $emptyError = "Ce client n'existe pas";
                if((!empty($phone) || !empty($email) || !empty($password)) && $isSuccess){
                    $statement = $db->prepare("INSERT INTO cardex (civilite, last_name, first_name, email, phone, items_category, brand_category, password, historique) values(?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $statement->execute(array($civilite, $last_name, $first_name, $email, $phone, $items, $brand, $password, $historique));
                    $civilite = $first_name = $last_name = $phone = $email = $brand = $items = $password = $historique = $emailError = $emptyError = $phoneError = "";
                }
            }
            Database::disconnect();
        }     
    }
    $pageTitle = "Suivie Clientèle";
    require 'elements/header.php';
?>
<h1>Cardex client</h1>
<a href="class/BackupMySQL.php">Sauvegarder la BDD</a>
<form class="form" role="form" action="index.php" method="POST">
    <div class="row">
        <div class="form-group">
            <label for="civilite">Civilité </label>
            <select name="civilite" class = "form-control">
                <option value="Mr">Mr</option>
                <option value="Mme">Mme</option>
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
        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" name="phone" value="<?php echo $phone; ?> ">
            <p><?php echo $phoneError;?></p>
        </div>
    </div>    
    <div class="row">
        <div class="form-group">
            <label for="items">Machine </label>
            <select class="form-control" name="items">
                <?php
                    $db = Database::connect();
                    foreach($db->query('SELECT * FROM items ORDER BY id ASC')as $row){
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
        <div class="form-group">
            <label for="historique">Historique</label>
            <textarea name="historique" style="width:650px; padding:5px;"> - <?= date('d/m/y')?> -</textarea>
        </div> 
    </div>    
    <div class="btns-container">    
        <button type="submit"><span class="fa fa-user-plus"></span> Ajouter</button>
        <a href="admin/index.php"><span class="fa fa-address-book-o"></span> Base de donnée</a>
    </div> 
    <p><?php echo $emptyError;?></p>
</form>        
<table>
    <thead>
        <tr>
            <th>Date </th>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Machine</th>
            <th>Marque</th>
            <th>Password</th>
            <th>Historique</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $db = Database::connect();
            $statement = $db->query('SELECT cardex.id, cardex.civilite, cardex.last_name, cardex.first_name, cardex.email, cardex.phone, cardex.password, cardex.historique, cardex.join_date, cardex.email_success, items.name AS machine, brand.brand_name AS marque
                                        FROM cardex 
                                        LEFT JOIN brand ON cardex.brand_category = brand.id  
                                        LEFT JOIN items ON cardex.items_category = items.id
                                        WHERE affichage IN ("oui")
                                        ORDER BY cardex.join_date DESC ');
            
            while($cardex = $statement->fetch()){
                $style = $cardex['email_success'] !== '' ? 'success' : ' ';
                $enveloppe = $cardex['email_success'] !== '' ? '-open-o' : ' ';
                echo '<tr>';
                echo '<td>'.$cardex['join_date'].'</td>';
                echo '<td>'.$cardex['last_name'].'</td>';
                echo '<td>'.$cardex['first_name'].'</td>';
                echo '<td>'.$cardex['email'].'</td>';
                echo '<td> +33 '.$cardex['phone'].'</td>';
                echo '<td>' .$cardex['machine'].'</td>';
                echo '<td>' .$cardex['marque'].'</td>';
                echo '<td>' .$cardex['password'].'</td>';
                echo '<td>' .$cardex['historique'].'</td>';
                echo '<td class="action">'; 
                echo '<a href="email.php?id=' .$cardex['id']. '" class = "' .$style. '"><span class="fa fa-envelope'. $enveloppe .'"></span>';
                echo '<span class="tooltip"> Email envoyé le ' .$cardex['email_success'] . '</span></a>';
                echo '<a href="admin/update.php?id=' .$cardex['id']. '"><span class ="fa fa-save"></span></a>';
                echo '<a href="affichage.php?id=' .$cardex['id']. '"><span class ="fa fa-check-circle-o"></span></a>';
                echo '</td>';
                echo '</tr>';
                
            }

            Database::disconnect();
        ?>
    </tbody>
</table>


<?php require 'elements/footer.php' ?>