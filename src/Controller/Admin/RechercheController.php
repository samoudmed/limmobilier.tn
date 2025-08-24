<?php

namespace App\Controller\Admin;

use App\Entity\Recherche;
use App\Form\RechercheType;
use App\Repository\RechercheRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @Route("/admin/recherche")
 */
class RechercheController extends AbstractController
{
    /**
     * @Route("/", name="app_recherche_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        
        $countByOffre = $entityManager
                ->getRepository(Recherche::class)
                ->countByOffre();

        $countByVille = $entityManager
                ->getRepository(Recherche::class)
                ->countByVille();
        
        $countByType = $entityManager
                ->getRepository(Recherche::class)
                ->countByType();
        

        return $this->render('admin/recherche/index.html.twig', [
            'countByOffre' => $countByOffre,
            'countByVille' => $countByVille,
            'countByType' => $countByType,
        ]);
    }

    /**
     * @Route("/{id}", name="app_recherche_show", methods={"GET"})
     */
    public function show(Recherche $recherche): Response
    {
        return $this->render('admin/recherche/show.html.twig', [
            'recherche' => $recherche,
        ]);
    }

}
