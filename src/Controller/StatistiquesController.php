<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonces;

/**
 * Description of StatistiquesController
 *
 * @author Guillaume
 */
class StatistiquesController extends AbstractController {

    /**
     * @Route("/compte/statistiques-{page}.html", name="statistiques")
     */
    public function statistiques(Request $request, $page = 1) {

        $prixMoyenLocation = $prixMoyenVente = $totalPrixLocation = $totalPrixVente = $nbrVente = $nbrLocation = array();
        $annonces = $this->getDoctrine()
                    ->getRepository(Annonces::class)
                    ->findForStatistique();
        
        foreach ($annonces as $k => $value) {

            if (($value->getOffre() == 'Location') && ($value->getPrix() > 0) && ($value->getSurface() > 0)) {
                if (array_key_exists($value->getDelegation()->getLabel(), $nbrLocation)) {
                    $nbrLocation[$value->getDelegation()->getLabel()] =+  $value->getSurface();
                    $totalPrixLocation[$value->getDelegation()->getLabel()] += $value->getPrix();
                } else {
                    $nbrLocation[$value->getDelegation()->getLabel()] = $value->getSurface();
                    $totalPrixLocation[$value->getDelegation()->getLabel()] = $value->getPrix();
                }
                
                $prixMoyenLocation[$value->getDelegation()->getLabel()] = $totalPrixLocation[$value->getDelegation()->getLabel()] / $nbrLocation[$value->getDelegation()->getLabel()];
            }

            if (($value->getOffre() == 'Vente') && ($value->getPrix() > 0)  && ($value->getSurface() > 0)) {
                if (array_key_exists($value->getDelegation()->getLabel(),$nbrVente)) {
                    $nbrVente[$value->getDelegation()->getLabel()] =+ $value->getSurface();
                    $totalPrixVente[$value->getDelegation()->getLabel()] += $value->getPrix();
                } else {
                    $nbrVente[$value->getDelegation()->getLabel()] = $value->getSurface();
                    $totalPrixVente[$value->getDelegation()->getLabel()] = $value->getPrix();
                }

                $prixMoyenVente[$value->getDelegation()->getLabel()] = $totalPrixVente[$value->getDelegation()->getLabel()] / $nbrVente[$value->getDelegation()->getLabel()];
            }
            //
            $ville = $value->getVille();
        }

        return $this->render('default/compte/statistiques.html.twig', ['prixMoyenLocation' => $prixMoyenLocation, 'prixMoyenVente' => $prixMoyenVente, 'ville' => $ville]);
    }

}
