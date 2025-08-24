<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonces;
use App\Service\ManagePhoto;
use App\Entity\Villes;
use App\Entity\Delegation;
use App\Entity\Kind;
use Knp\Component\Pager\PaginatorInterface;

class DelegationsController extends AbstractController {

    /**
     * @Route("{offre}/{type}/delegation/{slug}/{page}", name="annonce_liste_type_delegation", methods={"GET"}, requirements={"delegation"="[0-9a-zA-Z][0-9a-zA-Z\-_]{1,99}", "page"="\d+"}, defaults={"page": 1})
     */
    public function annonceListeTypeDelegation(Request $request, $slug, $offre, $type, PaginatorInterface $paginator, ManagePhoto $managePhoto, $page = 1) {

        $oType = $this->getDoctrine()->getRepository(Kind::class)->findOneBySlug($type);
        $delegation = $this->getDoctrine()
                ->getRepository(Delegation::class)
                ->findOneBySlug($slug);
        if(!$delegation) {
            return $this->redirectToRoute('homepage');
        }
        $annoncesList = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByBienTypeDelegation(ucfirst($offre), $oType, $delegation);

        

        $annonces = $paginator->paginate(
                $annoncesList, /* query NOT result */
                $request->attributes->getInt('page', 1), /* page number */
                24 /* limit per page */
        );
        
        $annonces = $managePhoto->getFeaturedPhoto($annonces);
        if ((count($annonces->getItems()) === 0) && ($page > 1)) {
            return $this->redirectToRoute('annonce_liste_type_delegation', ['slug' => $delegation->getSlug(), 'offre' => $offre, 'type' => $type]);
        }

        return $this->render('default/list-delegation.html.twig', ['page' => $page, 'annoncesList' => $annoncesList, 'annonces' => $annonces, 'offre' => ucfirst($offre), 'type' => $oType, 'delegation' => $delegation]);
    }

    /**
     * @Route("/delegation/{slug}/{page}", name="annonce_liste_delegation", methods={"GET"}, requirements={"delegation"="[0-9a-zA-Z][0-9a-zA-Z\-_]{1,99}"}, defaults={"page": 1})
     */
    public function annonceListeDelegation(Request $request, $slug, PaginatorInterface $paginator, ManagePhoto $managePhoto, $page = 1) {

        $delegation = $this->getDoctrine()
                ->getRepository(Delegation::class)
                ->findOneBySlug($slug);
        if(!$delegation) {
            return $this->redirectToRoute('homepage');
        }
        
        $annoncesList = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByDelegation($delegation);

        

        $annonces = $paginator->paginate(
                $annoncesList, /* query NOT result */
                $request->attributes->getInt('page', 1), /* page number */
                24 /* limit per page */
        );
        
        $annonces = $managePhoto->getFeaturedPhoto($annonces);
        if ((count($annonces->getItems()) === 0) && ($page > 1)) {
            return $this->redirectToRoute('annonce_liste_delegation', ['slug' => $delegation->getSlug()]);
        }

        return $this->render('default/list-delegation.html.twig', ['annoncesList' => $annoncesList, 'annonces' => $annonces, 'page' => $page, 'delegation' => $delegation]);
    }

    public function villes() {

        $villes = $this->getDoctrine()
                ->getRepository(Villes::class)
                ->findAll();

        return $villes;
    }
}
