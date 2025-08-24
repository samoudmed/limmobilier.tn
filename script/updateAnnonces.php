<?php

// Tiers / Account
use Phppot\NewDataSource;

require_once 'NewDataSource.php';


$newDb = new NewDataSource();
$newConn = $newDb->getConnection();

$annonces = $newDb->select("select * from annonces"); //`id_part` > 86789

foreach ($annonces as $key => $annonce) {
    $ville = $newDb->select("select * from villes where id='".$annonce['ville_id']."'"); //`id_part` > 86789
    $newDb->insertSQL("UPDATE `annonces` SET `gouvernorat_id` = '".$ville[0]['gouvernorat_id']."', `delegation_id` = '".$ville[0]['delegation_id']."' WHERE `annonces`.`id` = '".$annonce['id']."';");
}
?>