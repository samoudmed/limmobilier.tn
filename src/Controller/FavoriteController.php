<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Entity\Favorite;
use App\Entity\Annonces;
use Symfony\Component\Finder\SplFileInfo;
use App\Repository\VillesRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Finder\Finder;
use Cocur\Slugify\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\ManagePhoto;

class FavoriteController extends AbstractController {

    /**
     * @Route("/favorite/toggle/{id}", name="favorite_toggle", methods={"POST"})
     */
    public function toggleFavorite($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Non authentifié'], 403);
        }
        $annonce = $em->getRepository(Annonces::class)->find($id);
        if (!$annonce) {
            return $this->json(['error' => 'Annonce introuvable'], 404);
        }
        $favoriteRepo = $em->getRepository(Favorite::class);
        $favorite = $favoriteRepo->findOneBy(['user' => $user, 'annonce' => $annonce]);
        if ($favorite) {
            $em->remove($favorite);
            $em->flush();
            return $this->json(['status' => 'removed']);
        } else {
<<<<<<< HEAD
            $favorite = new \App\Entity\Favorite();
=======
            $favorite = new Favorite();
>>>>>>> 07fb2a2889b3aa766a10ca5dba9fd335068dc11c
            $favorite->setUser($user);
            $favorite->setAnnonce($annonce);
            $em->persist($favorite);
            $em->flush();
            return $this->json(['status' => 'added']);
        }
    }

    /**
     * @Route("/favorite", name="favorite")
     */
    public function favoriteAction(Request $request) {
        $id = $request->request->get('id');

        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneById($id);

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $favorite = new Favorite();
        $favorite->setAnnonce($annonce);
        $favorite->setUser($user);
        $em->persist($favorite);

        $em->flush();
        return $this->json('succes');
    }

    /**
     * @Route("/compte/mes-favoris-{page}.html", name="mes_favoris", methods={"GET"})
     */
    public function mesFavoris(Request $request, PaginatorInterface $paginator, ManagePhoto $managePhoto, $page = 1) {

        $annoncesList = array();
        $favoris = $this->getDoctrine()
                ->getRepository(Favorite::class)
                ->findByUser($this->getUser());

        foreach ($favoris as $favori) {
            $annoncesList[] = $favori->getAnnonce();
        }

        if (isset($annoncesList)) {
            $annoncesList = $managePhoto->getFeaturedPhoto($annoncesList);
        }

        $annonces = $paginator->paginate(
                $annoncesList, /* query NOT result */
                $page, /* page number */
                10 /* limit per page */
        );

        return $this->render('default/compte/favoris.html.twig', ['annonces' => $annonces]);
    }

}
