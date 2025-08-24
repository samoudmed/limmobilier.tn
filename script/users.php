<?php

// Tiers / Account
use Phppot\NewDataSource;
use Phppot\DataSource;

require_once 'NewDataSource.php';
require_once 'DataSource.php';

$db = new DataSource();
$conn = $db->getConnection();

$newDb = new NewDataSource();
$newConn = $newDb->getConnection();

$users = $db->select("SELECT * FROM `users`"); //`id_part` > 86789



foreach ($users as $user) {

    $newUser = $newDb->select("SELECT * FROM `user` WHERE `id` = '" . $user['id'] . "'");
    if(!$newUser) {
        
        $sqlVille = "INSERT INTO `user` (`id`, `nom`, `prenom`, `email`, `password`, `telephone`, `type`, `agence`, `logo`, `is_active`, `is_verified`, `roles`, `ip`) VALUES ('". addslashes($user['id'])."', '". addslashes($user['nom'])."', '". addslashes($user['prenom'])."', '". addslashes($user['email'])."', '". addslashes($user['password'])."', '". addslashes($user['telephone'])."', '". addslashes($user['type'])."', '". addslashes($user['agence'])."', '". addslashes($user['logo'])."', '". addslashes($user['enabled'])."', '". addslashes($user['enabled'])."', '[\"ROLE_USER\"]', '')";
        echo $sqlVille.'<br>';
        echo ';';
        $newDb->insertSQL($sqlVille);
    }

    
}
?>