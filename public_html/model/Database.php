<?php

class Database
{

    public static function connexionDDB()
    {
        $pdo_options[PDO::ATTR_ERRMODE] = PDO::ERRMODE_EXCEPTION;
        $bdd = new PDO(DSN, DB_USERNAME, DB_PASSWORD, $pdo_options);

        return $bdd;

    }



}

?>