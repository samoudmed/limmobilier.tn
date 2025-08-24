<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\ManagePhoto;
use App\Entity\Annonces;
use App\Entity\Gouvernorat;
use App\Entity\Kind;
use App\Entity\Villes;

class GouvernoratController extends AbstractController
{
     /**
     * @Route("/gouvernorat/{slug}/{page}", name="annonce_liste_gouvernorat", methods={"GET"}, requirements={"gouvernorat"="[0-9a-zA-Z][0-9a-zA-Z\-_]{1,99}"}, defaults={"page": 1})
     */
    public function annonceListeGouvernorat($slug, Request $request, PaginatorInterface $paginator, ManagePhoto $managePhoto, $page = 1) {
        
        $gouvernorat = $this->getDoctrine()
                ->getRepository(Gouvernorat::class)
                ->findOneBySlug($slug);
        $annoncesList = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByGouvernorat($gouvernorat);
        
        $annonces = $paginator->paginate(
                $annoncesList, /* query NOT result */
                $request->attributes->getInt('page', 1), /* page number */
                24 /* limit per page */
        );
        
        $annonces = $managePhoto->getFeaturedPhoto($annonces);
        
        if ((count($annonces->getItems()) === 0) && ($page > 1)) {
            return $this->redirectToRoute('annonce_liste_gouvernorat', ['slug' => strtolower($gouvernorat->getSlug())]);
        }

        return $this->render('default/list-gouvernorat.html.twig', ['annoncesList' => $annoncesList, 'annonces' => $annonces, 'page' => $request->attributes->getInt('page'), 'villes' => $this->villes(), 'gouvernorat' => $gouvernorat]);
    }
    
    /**
     * @Route("{offre}/{type}/gouvernorat/{slug}/{page}", name="annonce_liste_type_gouvernorat", methods={"GET"}, requirements={"slug"="[0-9a-zA-Z][0-9a-zA-Z\-_]{1,99}", "page"="\d+"}, defaults={"page": 1})
     */
    public function annonceListeTypeGouvernorat(Request $request, $slug, $offre, $type, PaginatorInterface $paginator, ManagePhoto $managePhoto, $page = 1) {

        $type = $this->getDoctrine()->getRepository(Kind::class)->findOneBySlug($type);
        $gouvernorat = $this->getDoctrine()
                ->getRepository(Gouvernorat::class)
                ->findOneBySlug($slug);
        $annoncesList = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByBienTypeGouvernorat(ucfirst($offre), $type, $gouvernorat);

        $annonces = $paginator->paginate(
                $annoncesList, /* query NOT result */
                $request->attributes->getInt('page', 1), /* page number */
                24 /* limit per page */
        );
        
        $annonces = $managePhoto->getFeaturedPhoto($annonces);
        if ((count($annonces->getItems()) === 0) && ($page > 1)) {
            return $this->redirectToRoute('annonce_liste_type_gouvernorat', ['slug' => $gouvernorat->getSlug(), 'offre' => $offre, 'type' => $type]);
        }

        return $this->render('default/list-gouvernorat.html.twig', ['annoncesList' => $annoncesList, 'annonces' => $annonces, 'offre' => ucfirst($offre), 'type' => $type, 'gouvernorat' => $gouvernorat, 'villes' => $this->villes() , 'page' => $page]);
    }
   
    
    public function villes() {

        $villes = $this->getDoctrine()
                ->getRepository(Villes::class)
                ->findAll();

        return $villes;
    }
}
