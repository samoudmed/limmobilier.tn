<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Favorite;

class FavoriteController extends AbstractController {

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

}
