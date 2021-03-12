<?php

require '../vendor/autoload.php';
use App\{Database, Auth, Helpers};

Auth::force_utilisateur_connecte("../");

$style = "../style/style.css";
$pageTitle = "Base de donnée clientèle";
require '../elements/header.php';

?>

<h1>Base de données client</h1>
<h2>Liste des clients <a href="../index.php" ><span class="fa fa-angle-double-left"></span> Cardex Client</a><a href="../sauvegarde.php">Sauvegarder la BDD</a></h2>
<form action="index.php" method="post" role="form" class="recherche">
    <input type="text" name="recherche" placeholder="Recherche d'un client">
    <button type="submit">Recherche</button>   
</form>
<table>
    <thead>
        <tr>
            <th>Date </th>
            <th>Civilité</th>
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
            $recherche = [];
            
            if(empty($_POST) || $_POST['recherche'] === ''){
                $page = $_GET['p'] ?? 1;
                $limit = 8;
                $count = count($db->query('SELECT * FROM cardex')->fetchAll());
                $pages = ceil($count / $limit);
                $offset = ($page - 1) * $limit;
                $statement = $db->query("SELECT cardex.id, cardex.civilite, cardex.last_name, cardex.first_name, cardex.email, cardex.phone, cardex.password, cardex.historique, cardex.join_date, items.name AS machine
                FROM cardex   
                LEFT JOIN items ON cardex.items_category = items.id
                ORDER BY cardex.last_name ASC
                LIMIT $limit OFFSET $offset");

                while($cardex = $statement->fetch()){
                    echo '<tr>';
                    echo '<td>'.$cardex['join_date'].'</td>';
                    echo '<td>'.$cardex['civilite'].'</td>';
                    echo '<td>'.$cardex['last_name'].'</td>';
                    echo '<td>'.$cardex['first_name'].'</td>';
                    echo '<td>'.$cardex['email'].'</td>';
                    echo '<td> +33'.$cardex['phone'].'</td>';
                    echo '<td>' .$cardex['machine'].'</td>';
                    echo '<td>' .$cardex['password'].'</td>';
                    echo '<td>' .$cardex['historique']. '</td>';
                    echo '<td class="action">';
                    echo '<a href="update.php?id=' .$cardex['id']. '"><span class="fa fa-pencil"></span></a>';
                    echo '<a href="delete.php?id=' .$cardex['id']. '"><span class="fa fa-remove"></span></a>';  
                    echo '</td>';
                    echo '</tr>';
                }
                

            }else if(!empty($_POST)){
                $recherche[] = Helpers::checkInput($_POST['recherche']);
                $statement = $db->prepare("SELECT cardex.id, cardex.civilite, cardex.last_name, cardex.first_name, cardex.email, cardex.phone, cardex.password, cardex.historique, cardex.join_date, items.name AS machine
                FROM cardex  
                LEFT JOIN items ON cardex.items_category = items.id
                WHERE cardex.last_name LIKE CONCAT('%', ?, '%')");
                $statement->execute($recherche);
                

                if($statement->rowCount()>0){
                    while($cardex = $statement->fetch()){
                        echo '<tr>';
                        echo '<td>'.$cardex['join_date'].'</td>';
                        echo '<td>'.$cardex['civilite'].'</td>';
                        echo '<td>'.$cardex['last_name'].'</td>';
                        echo '<td>'.$cardex['first_name'].'</td>';
                        echo '<td>'.$cardex['email'].'</td>';
                        echo '<td> +33'.$cardex['phone'].'</td>';
                        echo '<td>' .$cardex['machine'].'</td>';
                        echo '<td>' .$cardex['password'].'</td>';
                        echo '<td>' .$cardex['historique'].'</td>';
                        echo '<td class="action">';
                        echo '<a href="update.php?id=' .$cardex['id']. '"><span class="fa fa-pencil"></span></a>';
                        echo '<a href="delete.php?id=' .$cardex['id']. '"><span class="fa fa-remove"></span></a>';  
                        echo '</td>';
                        echo '</tr>';
                    }
                }else echo '<tr><td colspan = 10>Aucun résultat trouvé </td></tr>';
            }
            Database::disconnect();
        ?>
    </tbody>
</table>
</br>

    <?php if($pages > 1 && $page > 1): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ["p" => $page - 1]))?>" class="precedent">&laquo; Page précedente</a>
    <?php endif ?>

    <?php if($pages > 1 && $page < $pages): ?>
        <a href="?<?= http_build_query(array_merge($_GET, ["p" => $page + 1])) ?>" class="suivant">Page suivante &raquo;</a>
    <?php endif ?>



<?php require '../elements/footer.php'?>