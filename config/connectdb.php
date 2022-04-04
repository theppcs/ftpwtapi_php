<?php
function connect_MySQL_DB()
{
    $user = MySecret::$dbMyUser;
    $password =  MySecret::$dbMyPass;
    $database = MySecret::$dbMyDatabase;

    try {
        $options  = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_PERSISTENT => true,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8mb4' COLLATE 'utf8mb4_unicode_ci'"
        ];
        $db = new PDO(
            "mysql:host=localhost;dbname=$database;charset=utf8",
            $user,
            $password,
            $options
        );
        return $db;
    } catch (PDOException $e) {
        print "Error!: " . $e->getMessage() . "<br/>";
        die();
    }
}
