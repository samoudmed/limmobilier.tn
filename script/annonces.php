<?php

// Tiers / Account
use Phppot\DataSource;
use Phppot\NewDataSource;

require_once 'DataSource.php';
require_once 'NewDataSource.php';

$db = new DataSource();
$conn = $db->getConnection();

$newDb = new NewDataSource();
$newConn = $newDb->getConnection();

$annonces = $db->select("select *, a.id as aid, a.user_id as userid from annonces a left join biens b on b.id = a.bien_id left join options o on b.options_id = o.id"); //`id_part` > 86789

foreach ($annonces as $key => $annonce) {
    $nannonce = $newDb->select("select * from annonces WHERE `id` = '".$annonce['aid']."'"); //`id_part` > 86789

    //$expired_at = $annonce['created_at'];
    if(!isset($nannonce[0]['id'])) {
        /*print_r($annonce);
    $dt = strtotime($annonce['created_at']); 
    echo date("Y-m-d", strtotime("+1 month", $dt));
    die();*/
        //echo ($annonce['aid']).' - ';
        $sqlInsert = "INSERT INTO `annonces` (`id`, `ville_id`, `delegation_id`, `gouvernorat_id`, `pays_id`, `type_id`, `user_id`, `label`, `surface`, `adresse`, `localisation_map`, `description`, `offre`, `prix`, `view`, `annee_construction`, `etage`, `pieces`, `climatiseur`, `piscine`, `parking`, `chauffage`, `capacite`, `internet`, `meuble`, `salle_bain`, `securite`, `ascenseur`, `cheminee`, `cuisine_equipe`, `jacuzzi`, `jardin`, `electricite`, `gaz`, `telephone`, `eau`, `assainissement`, `permis_construction`, `vue`, `orientation`, `disponibilite`, `statut`, `published`, `deleted`, `created_at`, `updated_at`, `expired_at`) "
            . "VALUES (".$annonce['aid'].", ".$annonce['ville_id'].", NULL, NULL, 1, ".$annonce['type_id'].", "
            . "".$annonce['userid'].", '".addslashes($annonce['label'])."', "
            . "".(($annonce['surface'] == NULL )? "NULL" : $annonce['surface']).", "
            . "'".addslashes($annonce['adresse'])."', "
            . "'".addslashes($annonce['localisation_map'])."', "
            . "'".addslashes($annonce['description'])."', "
            . "'".$annonce['type_offre']."', "
            . "".$annonce['prix'].", "
            . "".$annonce['view'].", "
            . "". (($annonce['annee_construction'] == NULL )? "NULL" : $annonce['annee_construction']) . ", "
            . "'etage', "
            . "".(($annonce['nbr_pieces'] == NULL )? "NULL" : $annonce['nbr_pieces']).", "
            . "".(($annonce['climatiseur'] == NULL )? "NULL" : $annonce['climatiseur']).", "
            . "".(($annonce['piscine'] == NULL )? "NULL" : $annonce['piscine']).", "
            . "".(($annonce['parking'] == NULL )? "NULL" : $annonce['parking']).", "
            . "".(($annonce['chauffage'] == NULL )? "NULL" : $annonce['chauffage']).", "
            . "NULL, "
            . "".(($annonce['internet'] == NULL )? "NULL" : $annonce['internet']).", "
            . "".(($annonce['meuble'] == NULL )? "NULL" : $annonce['meuble']).", "
            . "".(($annonce['salle_bain'] == NULL )? "NULL" : $annonce['salle_bain']).", "
            . "".(($annonce['securite'] == NULL )? "NULL" : $annonce['securite']).", "
            . "".(($annonce['ascenseur'] == NULL )? "NULL" : $annonce['ascenseur']).", "
            . "".(($annonce['chemine'] == NULL )? "NULL" : $annonce['chemine']).", "
            . "".(($annonce['cuisine_equipe'] == NULL )? "NULL" : $annonce['cuisine_equipe']).", "
            . "".(($annonce['jacuzzi'] == NULL )? "NULL" : $annonce['jacuzzi']).", "
            . "NULL, "
            . "".(($annonce['electricite'] == NULL )? "NULL" : $annonce['electricite']).", "
            . "".(($annonce['gaz'] == NULL )? "NULL" : $annonce['gaz']).", "
            . "".(($annonce['telephone'] == NULL )? "NULL" : $annonce['telephone']).", "
            . "".(($annonce['eau'] == NULL )? "NULL" : $annonce['eau']).", "
            . "".(($annonce['assainissement'] == NULL )? "NULL" : $annonce['assainissement']).", "
            . "NULL, "
            . "".(($annonce['vue'] == NULL )? "NULL" : $annonce['vue']).", "
            . "'orientation', "
            . "'".(($annonce['disponibilite'] == NULL )? "NULL" : $annonce['disponibilite'])."', "
            . "".(($annonce['statut'] == NULL )? "NULL" : $annonce['statut']).", "
            . "".$annonce['is_published'].", "
            . "".$annonce['is_deleted'].", "
            . "'".$annonce['created_at']."', "
            . "'".$annonce['updated_at']."', "
            . "'".$annonce['exprired_at']."')";

        if(!$newDb->insertSQL($sqlInsert)) {
            print_r($sqlInsert);
            echo ' ; ';
        }
    }
    // $extraLigne = "SELECT * FROM `or35_product_extrafields` WHERE `fk_object` = '".$lot['id_part']."'";
    // if (isset($extraLigne)) {
    //$annonce['annee_construction']
    /**/

    /**/
    //}
}
?>