<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Annonces;
use App\Entity\Photos;
use App\Entity\User;
use App\Entity\Villes;
use App\Entity\Gouvernorat;
use App\Entity\Delegation;
use App\Entity\Kind;
use App\Repository\VillesRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Finder\Finder;
use Cocur\Slugify\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\Search;
use \Liip\ImagineBundle\Imagine\Cache\CacheManager;

class VillesController extends AbstractController {

    /**
     * @Route("/annonces/{offre}/type/{type}/region/{ville}/{idVille}/{page}", name="annonce_liste_type_ville", methods={"GET"}, requirements={"ville"="[0-9a-zA-Z][0-9a-zA-Z\-_]{1,99}"}, defaults={"page": 1})
     */
    public function annonceListeTypeVille(Request $request, $offre, $type, $ville, $idVille, PaginatorInterface $paginator, $page = 1) {

        $offre = ucfirst($offre);
        $slugify = new Slugify();
        $oType = $this->getDoctrine()->getRepository(Kind::class)->findOneBySlug($type);
        $annoncesList = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findByBienTypeVille($offre, $oType, $idVille);

        $ville = $this->getDoctrine()
                ->getRepository(Villes::class)
                ->findOneById($idVille);

        $villes = $this->villes();

        $annoncesList = $this->getFeaturedPhoto($annoncesList);

        $annonces = $paginator->paginate(
                $annoncesList, /* query NOT result */
                $page, /* page number */
                24 /* limit per page */
        );

        if ((count($annonces->getItems()) === 0) && ($page > 1)) {
            return $this->redirectToRoute('annonce_liste_type_ville', ['offre' => $offre, 'type' => $type, 'ville' => $slugify->slugify($ville), 'idVille' => $idVille, 'page' => 1]);
        }

        return $this->render('default/list-ville.html.twig', ['annoncesList' => $annoncesList, 'annonces' => $annonces, 'offre' => $offre, 'type' => $oType, 'page' => $request->attributes->getInt('page'), 'villes' => $villes, 'ville' => $ville, 'idville' => $idVille]);
    }

    public function getFeaturedPhoto($annonces) {

        foreach ($annonces as $k => $annonce) {

            $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneBy(array('annonce' => $annonce, 'featured' => 1));
            if(!$photo) {
                $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneBy(array('annonce' => $annonce));
            }
            $annonces[$k]->photo = (isset($photo)) ? $photo->getNom() : 'default-img.png';
        }

        return $annonces;
    }

    public function villes() {

        $villes = $this->getDoctrine()
                ->getRepository(Villes::class)
                ->findAll();

        return $villes;
    }
}
