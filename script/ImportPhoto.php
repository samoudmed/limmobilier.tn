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

$photos = $db->select("SELECT *,a.id as annoncesid FROM `photos` p left JOIN annonces a on a.bien_id=p.bien_id"); //`id_part` > 86789



foreach ($photos as $photo) {

    $newPhoto = $newDb->select("SELECT * FROM `photos` WHERE `nom` LIKE '%" . $photo['nom'] . "%'");
    if(!$newPhoto) {
        
        $sqlVille = "INSERT INTO `photos` (`id`, `annonce_id`, `user_id`, `nom`, `featured`, `created_at`) VALUES (NULL, '".$photo['annoncesid']."', '".$photo['user_id']."', '".$photo['nom']."', '".$photo['featured']."', '2022-10-04 14:22:56.000000')";
        echo $sqlVille.'<br>';
        echo ';';
        $newDb->insertSQL($sqlVille);
    }

    
}
?>