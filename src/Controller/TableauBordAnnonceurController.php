<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class TableauBordAnnonceurController extends AbstractController
{
    /**
     * @Route("/compte/tableau-bord-annonceur", name="tableau_bord_annonceur")
     */
    public function index()
    {
        // Données statiques pour la démonstration
        $stats = [
            'nbAnnonces' => 12,
            'nbVues' => 340,
            'nbMessages' => 7,
            'nbClicksTel' => 15,
            'nbVisits' => 120
        ];
        $annonces = [
            [
                'id' => 1,
                'label' => 'Appartement S+2 à La Marsa',
                'kind' => 'Appartement',
                'delegation' => 'La Marsa',
                'prix' => 350000,
                'photo' => 'appartement1.jpg',
                'expiredAt' => new \DateTime('+30 days'),
                'view' => 120
            ],
            [
                'id' => 2,
                'label' => 'Villa avec piscine à Hammamet',
                'kind' => 'Villa',
                'delegation' => 'Hammamet',
                'prix' => 950000,
                'photo' => 'villa1.jpg',
                'expiredAt' => new \DateTime('+60 days'),
                'view' => 220
            ]
        ];
        return $this->render('default/compte/tableau_bord_annonceur.html.twig', [
            'stats' => $stats,
            'annonces' => $annonces
        ]);
    }
}
