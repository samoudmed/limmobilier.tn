<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Recherche;
use App\Entity\Villes;
use App\Entity\Delegation;
use App\Entity\Gouvernorat;
use App\Entity\Kind;

/**
 * Description of Search
 *
 * @author Guillaume
 */
class Search {

    private $em;
    
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function saveRecherche($values) {

        $recherche = new Recherche();
        
        $ville = $this->em->getRepository(Villes::class)
                ->findOneById($values->request->get('ville'));
        if($ville) {
            $recherche->setVille($ville);
        }
        
        $delegation = $this->em->getRepository(Delegation::class)
                ->findOneById($values->request->get('delegation'));
        if($delegation) {
            $recherche->setDelegation($delegation);
        }
        
        $gouvernorat = $this->em->getRepository(Gouvernorat::class)
                ->findOneById($values->request->get('gouvernorat'));
        if($gouvernorat) {
            $recherche->setGouvernorat($gouvernorat);
        }
        
        $oKind = $this->em->getRepository(Kind::class)->findOneById($values->request->get('type'));
        
        
        $recherche->setOffre($values->request->get('offre'));
        $recherche->setKind($oKind);
        
        $recherche->setVille($ville);
        $recherche->setVille($ville);
        $recherche->setPieces($values->request->get('chambres'));
        $recherche->setSurfaceMin(intval($values->request->get('surfaceMin')));
        $recherche->setSurfaceMax(intval($values->request->get('surfaceMax')));
        $recherche->setPrixMin(intval($values->request->get('prixMin')));
        $recherche->setPrixMax(intval($values->request->get('prixMax')));
        $recherche->setDate(new \DateTime('now'));
        
        $this->em->persist($recherche);
        $this->em->flush();
    }

}
