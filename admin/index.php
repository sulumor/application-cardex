<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Base de données</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link rel="stylesheet" href="../style.css">
    </head>
    <body>
    <h1>Base de données client</h1>
    <h2>Liste des clients <a href="../index.php" ><span class="fa fa-angle-double-left"></span> Cardex Client</a></h2>
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
                <th>Marque</th>
                <th>Password</th>
                <th>Historique</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
                require'Database.php';
                $db = Database::connect();
                $recherche = [];
                
                if(empty($_POST) || $_POST['recherche'] === ''){
                    $statement = $db->query('SELECT cardex.id, cardex.civilite, cardex.last_name, cardex.first_name, cardex.email, cardex.phone, cardex.password, cardex.historique, cardex.join_date, items.name AS machine, brand.brand_name AS marque
                    FROM cardex 
                    LEFT JOIN brand ON cardex.brand_category = brand.id  
                    LEFT JOIN items ON cardex.items_category = items.id
                    ORDER BY cardex.last_name ASC');

                    while($cardex = $statement->fetch()){
                        echo '<tr>';
                        echo '<td>'.$cardex['join_date'].'</td>';
                        echo '<td>'.$cardex['civilite'].'</td>';
                        echo '<td>'.$cardex['last_name'].'</td>';
                        echo '<td>'.$cardex['first_name'].'</td>';
                        echo '<td>'.$cardex['email'].'</td>';
                        echo '<td> +33'.$cardex['phone'].'</td>';
                        echo '<td>' .$cardex['machine'].'</td>';
                        echo '<td>' .$cardex['marque'].'</td>';
                        echo '<td>' .$cardex['password'].'</td>';
                        echo '<td>' .$cardex['historique']. '</td>';
                        echo '<td class="action">';
                        echo '<a href="update.php?id=' .$cardex['id']. '"><span class="fa fa-pencil"></span></a>';
                        echo '<a href="delete.php?id=' .$cardex['id']. '"><span class="fa fa-remove"></span></a>';  
                        echo '</td>';
                        echo '</tr>';
                    }
                }else if(!empty($_POST)){
                    $recherche[] = $_POST['recherche'];
                    $statement = $db->prepare("SELECT cardex.id, cardex.civilite, cardex.last_name, cardex.first_name, cardex.email, cardex.phone, cardex.password, cardex.historique, cardex.join_date, items.name AS machine, brand.brand_name AS marque
                    FROM cardex 
                    LEFT JOIN brand ON cardex.brand_category = brand.id  
                    LEFT JOIN items ON cardex.items_category = items.id
                    WHERE cardex.last_name LIKE CONCAT(?, '%')");
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
                            echo '<td>' .$cardex['marque'].'</td>';
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
    </body>
</html>