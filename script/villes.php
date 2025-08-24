<?php

// Tiers / Account
use Phppot\NewDataSource;

require_once 'NewDataSource.php';

$newDb = new NewDataSource();
$newConn = $newDb->getConnection();

$delegations = $newDb->select("SELECT * FROM `delegation` ORDER BY `id` DESC"); //`id_part` > 86789
$i = 0;
foreach ($delegations as $key => $delegation) {

    while ($i++ < 2308) {
        $annonce = $newDb->select("SELECT * FROM `annonces` WHERE id= '".$i."'");
        if(!$annonce) {
            break;
        }
    }
    
    echo "ecrire un petit text d'une offre d'annonce vente maison '".$delegation['label']."'<br>";
 
    $query = "INSERT INTO `annonces` (`id`, `ville_id`, `delegation_id`, `gouvernorat_id`, `pays_id`, `type_id`, `user_id`, `label`, `surface`, `adresse`, `localisation_map`, `description`, `offre`, `prix`, `instalment`, `view`, `real_view`, `annee_construction`, `etage`, `pieces`, `climatiseur`, `piscine`, `parking`, `chauffage`, `capacite`, `internet`, `meuble`, `salle_bain`, `securite`, `ascenseur`, `cheminee`, `cuisine_equipe`, `jacuzzi`, `jardin`, `electricite`, `gaz`, `telephone`, `eau`, `assainissement`, `permis_construction`, `vue`, `orientation`, `disponibilite`, `statut`, `created_at`, `updated_at`, `expired_at`, `published`, `deleted`) VALUES ('.$i.', NULL, '".$delegation['id']."', '".$delegation['gouvernorat_id']."', NULL, '2', '1', 'Ravissante maison de 3 pièces à Hawaria', NULL, NULL, NULL, '<p>Découvrez cette charmante maison de 3 pièces située dans le quartier recherché de Hawaria. Idéale pour les couples ou les petites familles, cette maison offre un espace de vie confortable et pratique.</p>\r\n\r\n<p>L&#39;intérieur de la maison est lumineux et accueillant. Vous trouverez un salon convivial où vous pourrez vous détendre et recevoir vos proches. La cuisine fonctionnelle est équipée de tout ce dont vous avez besoin pour préparer de délicieux repas.</p>\r\n\r\n<p>La maison comprend également deux chambres à coucher spacieuses, offrant un espace privé pour se reposer et se ressourcer. La salle de bains est moderne et bien entretenue.</p>\r\n\r\n<p>Profitez également d&#39;un jardin privé où vous pourrez profiter du plein air, planter des fleurs ou créer un espace de détente extérieur. Le quartier de Hawaria est réputé pour son ambiance tranquille et sa proximité avec les commodités locales telles que les écoles, les commerces et les parcs.</p>\r\n\r\n<p>Ne manquez pas cette belle opportunité de devenir propriétaire d&#39;une maison confortable à Hawaria. Contactez-nous dès maintenant pour planifier une visite et découvrir tout le charme de cette maison de 3 pièces.</p>', 'Vente', NULL, NULL, '129', '7', NULL, NULL, NULL, '0', '0', '0', '0', NULL, '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '0', '2023-05-17', '1', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2023-06-01', '1', '0')";

    $newDb->insertSQL($query);
}
?>