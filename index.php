<?php

require 'vendor/autoload.php';

use App\{Database, Helpers, Auth};

Auth::force_utilisateur_connecte("./");

$civilite = $first_name = $last_name = $phone = $email  = $items = $password = $historique = $emptyError = $phoneError = $emailError = "";

    if(!empty($_POST)){
        $civilite   = Helpers::checkInput($_POST['civilite']);
        $first_name = Helpers::checkInput($_POST['first_name']);
        $last_name  = Helpers::checkInput($_POST['last_name']);
        $phone      = Helpers::checkInput($_POST['phone']);
        $email      = Helpers::checkInput($_POST['email']);
        $items      = Helpers::checkInput($_POST['items']);
        $password   = Helpers::checkInput($_POST['password']);
        $historique = Helpers::checkInput($_POST['historique']);

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
            $emptyError = "Veuillez remplir au moins un champs !";
            $isSuccess = false;
        }

        if(!empty($first_name) && !empty($last_name)){
            // Voir si le client exist déjà
            $db = Database::connect();
            $statement = $db->prepare("SELECT * FROM cardex WHERE last_name = :last_name AND first_name = :first_name");
            $statement ->execute([
                'last_name' => $last_name,
                'first_name' => $first_name
            ]);
            $test = $statement->fetch();
                //Si oui 
            if($test){
                $isSuccess = false;
                $statement = $db->prepare("UPDATE cardex set affichage = :affichage WHERE id = :id");
                $statement->execute([
                    'affichage' => 'oui', 
                    'id' => $test['id']
                ]);
                $civilite = $first_name = $last_name = $phone = $email = $items = $password = $historique = $emailError = $emptyError = $phoneError = "";
            }else{
                // Si non
                $emptyError = "Ce client n'existe pas";
                if((!empty($phone) || !empty($email) || !empty($password)) && $isSuccess){
                    $statement = $db->prepare("INSERT INTO cardex (civilite, last_name, first_name, email, phone, items_category, password, historique) values(?, ?, ?, ?, ?, ?, ?, ?)");
                    $statement->execute([$civilite, $last_name, $first_name, $email, $phone, $items, $password, $historique]);
                    $civilite = $first_name = $last_name = $phone = $email  = $items = $password = $historique = $emailError = $emptyError = $phoneError = "";
                }
            }
            Database::disconnect();
        }     
    }

    $pageTitle = "Cardex client";
    require 'elements/header.php';
?>
<h1>Cardex client</h1>

    <a href="sauvegarde.php">Sauvegarder la BDD</a>
    <a href="logout.php" class="logout">Se deconnecter</a>

<form class="form" role="form" action="index.php" method="post">
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
        <div class="form-group">
            <label for="phone">Téléphone</label>
            <input type="text" name="phone" value="<?= $phone; ?> ">
            <p><?= $phoneError;?></p>
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
            <label for="password">Mot de Passe</label>
            <input type="text" name="password" value="<?= $password; ?>">
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
    <p><?= $emptyError;?></p>
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
            <th>Password</th>
            <th>Historique</th>
            <th>Actions</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $db = Database::connect();
            $statement = $db->query('SELECT cardex.id, cardex.civilite, cardex.last_name, cardex.first_name, cardex.email, cardex.phone, cardex.password, cardex.historique, cardex.join_date, cardex.email_success, cardex.fixed, cardex.tel, items.name AS machine
                                        FROM cardex   
                                        LEFT JOIN items ON cardex.items_category = items.id
                                        WHERE affichage IN ("oui")
                                        ORDER BY cardex.join_date DESC ');
            
            while($cardex = $statement->fetch()){
                $style = $cardex['email_success'] !== '' ? 'success' : ' ';
                $stylechain = $cardex['fixed'] !== '' ? 'success' : ' ';
                $styletel = $cardex['tel'] !== '' ? 'success' : ' ';
                $enveloppe = $cardex['email_success'] !== '' ? '-open-o' : ' ';
                $chain = $cardex['fixed'] !== '' ? ' ' : '-broken';
                echo '<tr>';
                echo '<td>'.$cardex['join_date'].'</td>';
                echo '<td>'.$cardex['last_name'].'</td>';
                echo '<td>'.$cardex['first_name'].'</td>';
                echo '<td>'.$cardex['email'].'</td>';
                echo '<td> +33 '.$cardex['phone'].'</td>';
                echo '<td>' .$cardex['machine'].'</td>';
                echo '<td>' .$cardex['password'].'</td>';
                echo '<td>' .$cardex['historique'].'</td>';
                echo '<td class="action">'; 
                echo '<a href="admin/fixed.php?id=' .$cardex['id']. '" class = "' .$stylechain. '"><span class="fa fa-chain' . $chain . '"></span>';
                echo '<span class="tooltip"> Réparation effectuée le ' .$cardex['fixed']. '</span></a>';
                echo '<a href="message.php?id=' .$cardex['id']. '" class = "' .$style. '"><span class="fa fa-envelope' . $enveloppe . '"></span>';
                echo '<span class="tooltip"> Email envoyé le ' .$cardex['email_success']. '</span></a>';
                echo '<a href="admin/tel.php?id=' .$cardex['id']. '" class = "' .$styletel. '"><span class="fa fa-phone"></span>';
                echo '<span class="tooltip"> Texto envoyé le ' .$cardex['tel']. '</span></a>';
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