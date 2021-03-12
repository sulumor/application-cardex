<?php

namespace App;


class Update{
    public static function upLogo(string $data, int $id)
    {
        $db = Database::connect();
        $statement = $db->prepare("UPDATE cardex set $data = ? WHERE id = ?");
        $statement->execute([date('d/m/y H:i'), $id]);
        Database::disconnect();
    }
}