<?php

use Phppot\NewDataSource;

require_once 'NewDataSource.php';

$newDb = new NewDataSource();
$newConn = $newDb->getConnection();


$gouvernorat = file_get_contents('http://www.codepostalpro.com/tunisie.html');
$links = [];
$document = new DOMDocument;
$document->loadHTML($gouvernorat);
$xPath = new DOMXPath($document);

$anchorTags = $xPath->evaluate("//div[@class=\"span7\"]//a/@href");
foreach ($anchorTags as $anchorTag) {
    $links[] = $anchorTag->nodeValue;
}

foreach ($links as $link) {

    $link = substr($link, 1);
    echo '<br><br>Gouvernorat : ' . $link . '<br>';
    switch ($link) {
        case '/tunisie/ariana.html':
            $idG = 1;

            break;
        case '/tunisie/beja.html':
            $idG = 2;

            break;
        case '/tunisie/ben-arous.html':
            $idG = 3;

            break;
        case '/tunisie/bizerte.html':
            $idG = 4;

            break;
        case '/tunisie/gabes.html':
            $idG = 5;

            break;
        case '/tunisie/gafsa.html':
            $idG = 6;

            break;
        case '/tunisie/jendouba.html':
            $idG = 7;

            break;
        case '/tunisie/kairouan.html':
            $idG = 8;

            break;
        case '/tunisie/kasserine.html':
            $idG = 9;

            break;
        case '/tunisie/kebili.html':
            $idG = 10;

            break;
        case '/tunisie/la-manouba.html':
            $idG = 11;

            break;
        case '/tunisie/le-kef.html':
            $idG = 12;

            break;
        case '/tunisie/mahdia.html':
            $idG = 13;

            break;
        case '/tunisie/medenine.html':
            $idG = 14;

            break;
        case '/tunisie/monastir.html':
            $idG = 15;

            break;
        case '/tunisie/nabeul.html':
            $idG = 16;

            break;
        case '/tunisie/sfax.html':
            $idG = 17;

            break;
        case '/tunisie/sidi-bouzid.html':
            $idG = 18;

            break;
        case '/tunisie/siliana.html':
            $idG = 19;

            break;
        case '/tunisie/sousse.html':
            $idG = 20;

            break;
        case '/tunisie/tataouine.html':
            $idG = 21;

            break;
        case '/tunisie/tozeur.html':
            $idG = 22;

            break;
        case '/tunisie/tunis.html':
            $idG = 23;

            break;
        case '/tunisie/zaghouan.html':
            $idG = 24;

            break;
        default:
            break;
    }
    $delegation = file_get_contents('http://www.codepostalpro.com/' . $link);
    $linksDelegation = [];
    $document = new DOMDocument;
    $document->loadHTML($delegation);
    $xPath = new DOMXPath($document);
    $anchorTags = $xPath->evaluate("//div[@class=\"span7\"]//a/@href");
    foreach ($anchorTags as $anchorTag) {
        $linksDelegation[] = $anchorTag->nodeValue;
    }
    foreach ($linksDelegation as $linkDelegation) {
        $linkDelegation = substr($linkDelegation, 1);
        $deleg = explode('/', $linkDelegation);
        $nbr = (strlen($deleg[3]) - 5);
        $delega = $newDb->select("SELECT * FROM `delegation` WHERE `label` LIKE '" . str_replace('-', ' ', substr($deleg[3], 0, $nbr)) . "'");
        if ($delega) {
            $page = file_get_contents('http://www.codepostalpro.com/' . $linkDelegation);

            $dom = new domDocument;

            $dom->loadHTML($page);
            $dom->preserveWhiteSpace = false;
            $tables = $dom->getElementsByTagName('table');

            $rows = $tables->item(0)->getElementsByTagName('tr');

            foreach ($rows as $row) {
                $cols = $row->getElementsByTagName('td');
                if($cols->length > 0) {
                    $ville = $newDb->select("SELECT * FROM `villes` WHERE `label` LIKE '".addslashes($cols[0]->nodeValue)."'");
                    if($ville) {
                        //$ville = $newDb->insertSQL("UPDATE `villes` SET `delegation_id` = '".$delega[0]['id']."', `gouvernorat_id` = '".$idG."' WHERE `villes`.`id` = '".$ville[0]['id']."'");
                    } else {
                        echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;Ville : ' . $cols[0]->nodeValue . ' not ok <br>';
                        $newDb->insertSQL("INSERT INTO `villes` (`id`, `label`, `delegation_id`, `gouvernorat_id`, `pays_id`) VALUES (NULL, '".addslashes($cols[0]->nodeValue)."', '".$delega[0]['id']."', '".$idG."', '1')");
                    }
                }
            }
        } else {
            echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;Delegation : ' . str_replace('-', ' ', substr($deleg[3], 0, $nbr)) . ' not ok <br>';
        }



        /* $doc->loadHTML($page);
          $divs = $doc->getElementsByTagName('a');

          foreach ($divs as $div) {
          if($div->textContent != '' && $div->textContent != 'Code Postal PRO' && $div->textContent != 'Accueil' && $div->textContent != 'contact' && $div->textContent != 'Conditions d\'utilisation' && $div->textContent != 'A propos' && $div->textContent != 'Elyes ZITARI') {
          echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ville : '.$div->textContent.'<br>';
          if($div->textContent != '') {
          //
          //
          }
          }
          } */
        sleep(1);
    }

    sleep(1);
}

