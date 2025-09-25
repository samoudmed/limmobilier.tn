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
use App\Form\MessageType;
use App\Form\ContactType;
use App\Form\AnnoncesType;
use Symfony\Component\Finder\SplFileInfo;
use App\Form\NewsletterType;
use App\Entity\Newsletter;
use App\Entity\Message;
use App\Entity\Contact;
use App\Repository\VillesRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Finder\Finder;
use Cocur\Slugify\Slugify;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Description of StatistiquesController
 *
 * @author Mohamed
 */
class StatistiquesController extends AbstractController {

    /**
     * @Route("/statistiques-immobilier.html", name="statistiques_immobilier", methods={"GET"})
     */
    public function index()
    {
        $annonces = $this->getDoctrine()
            ->getRepository(Annonces::class)
            ->findBy(['deleted' => 0, 'published' => 1]);

        // Tableau de prix de référence au m² (exemple, à compléter selon vos besoins)
        $prixReference = [
            'Tunis' => [
                'Appartement' => 2500,
                'Villa' => 3500,
                'Terrain' => 800
            ],
            'Ariana' => [
                'Appartement' => 2200,
                'Villa' => 3200,
                'Terrain' => 700
            ],
            // ... autres gouvernorats et types
        ];

        // ...existing code for stats and resultats...
        $stats = [];
        foreach ($annonces as $annonce) {
            $gouv = $annonce->getGouvernorat() ? $annonce->getGouvernorat()->getLabel() : 'Inconnu';
            $type = $annonce->getKind()->getLabel();
            $surface = $annonce->getSurface() ?: 1;
            $prix = $annonce->getPrix() ?: 0;
            if (!isset($stats[$gouv])) {
                $stats[$gouv] = [];
            }
            if (!isset($stats[$gouv][$type])) {
                $stats[$gouv][$type] = [
                    'totalPrix' => 0,
                    'totalSurface' => 0,
                    'count' => 0
                ];
            }
            $stats[$gouv][$type]['totalPrix'] += $prix;
            $stats[$gouv][$type]['totalSurface'] += $surface;
            $stats[$gouv][$type]['count']++;
        }

        // Calcul des moyennes
        $resultats = [];
        foreach ($stats as $gouv => $types) {
            foreach ($types as $type => $data) {
                $moyennePrix = $data['count'] ? round($data['totalPrix'] / $data['count'], 2) : 0;
                $moyenneM2 = $data['totalSurface'] ? round($data['totalPrix'] / $data['totalSurface'], 2) : 0;
                $refM2 = isset($prixReference[$gouv][$type]) ? $prixReference[$gouv][$type] : null;
                $resultats[] = [
                    'gouvernorat' => $gouv,
                    'type' => $type,
                    'prix_moyen' => $moyennePrix,
                    'prix_m2' => $moyenneM2,
                    'nb_annonces' => $data['count'],
                    'ref_m2' => $refM2
                ];
            }
        }

        return $this->render('default/statistiques_immobilier.html.twig', [
            'stats' => $resultats,
            'prixReference' => $prixReference
        ]);
    }

    // Méthode utilitaire pour accéder au tableau de référence
    private function getPrixReference()
    {
        return [
            'Tunis' => [
                'Appartement' => 2500,
                'Villa' => 3500,
                'Terrain' => 800
            ],
            'Ariana' => [
                'Appartement' => 2200,
                'Villa' => 3200,
                'Terrain' => 700
            ],
            // ... autres gouvernorats et types
        ];
    }

    /**
    * @Route("/statistiques-tunisie.html", name="statistiques_tunisie", methods={"GET"})
    */
    public function statistiquesTunisie()
    {
        $annonces = $this->getDoctrine()
            ->getRepository(Annonces::class)
            ->findBy(['deleted' => 0, 'published' => 1]);

        $prixM2List = [];
        $prixList = [];
        $gouvStats = [];
        $gouvernorats = [];
        foreach ($annonces as $annonce) {
            $gouv = $annonce->getGouvernorat() ? $annonce->getGouvernorat()->getLabel() : 'Inconnu';
            $type = $annonce->getKind()->getLabel();
            $surface = $annonce->getSurface() ?: 1;
            $prix = $annonce->getPrix() ?: 0;
            $prixM2 = $surface ? $prix / $surface : 0;
            $prixM2List[] = $prixM2;
            $prixList[] = $prix;
            if (!in_array($gouv, $gouvernorats)) {
                $gouvernorats[] = $gouv;
            }
            if (!isset($gouvStats[$gouv])) {
                $gouvStats[$gouv] = [];
            }
            $gouvStats[$gouv][] = $prixM2;
        }

        $prix_moyen = count($prixM2List) ? round(array_sum($prixM2List) / count($prixM2List), 0) : 0;
        sort($prixM2List);
        $count = count($prixM2List);
        $prix_median = $count ? ($count % 2 ? $prixM2List[floor($count/2)] : ($prixM2List[$count/2-1]+$prixM2List[$count/2])/2) : 0;
        $nb_annonces = count($annonces);

        // Evolution fictive (à remplacer par vos vraies données temporelles)
        $evolution_labels = ['2021','2022','2023','2024','2025'];
        $evolution_data = [1200, 1350, 1500, 1700, $prix_moyen];

        // Couleurs gouvernorat selon prix moyen
        $gouv_colors = [];
        foreach ($gouvStats as $gouv => $prixArr) {
            $moy = count($prixArr) ? array_sum($prixArr)/count($prixArr) : 0;
            // Gradient bleu-rouge selon prix
            $color = $moy < 1000 ? '#4fc3f7' : ($moy < 2000 ? '#81c784' : '#e57373');
            $gouv_colors[$gouv] = $color;
        }

        return $this->render('default/statistiques_tunisie.html.twig', [
            'prix_moyen' => $prix_moyen,
            'prix_median' => $prix_median,
            'nb_annonces' => $nb_annonces,
            'evolution_labels' => $evolution_labels,
            'evolution_data' => $evolution_data,
            'gouvernorats' => $gouvernorats,
            'gouv_colors' => $gouv_colors
        ]);
    }    

        /**
     * @Route("/prix-map-tunisie.html", name="prix_map_tunisie", methods={"GET"})
     */
    public function prixMapTunisie()
    {
        $annonces = $this->getDoctrine()
            ->getRepository(Annonces::class)
            ->findBy(['deleted' => 0, 'published' => 1]);

        $gouvernorats = [];
        $prix_appart = [];
        $prix_villa = [];
        $prix_terrain = [];
        $stats = [];
        foreach ($annonces as $annonce) {
            $gouv = $annonce->getGouvernorat() ? $annonce->getGouvernorat()->getLabel() : 'Inconnu';
            $type = $annonce->getKind()->getLabel();
            $surface = $annonce->getSurface() ?: 1;
            $prix = $annonce->getPrix() ?: 0;
            if (!in_array($gouv, $gouvernorats)) {
                $gouvernorats[] = $gouv;
            }
            if (!isset($stats[$gouv])) {
                $stats[$gouv] = [
                    'Appartement' => ['totalPrix' => 0, 'totalSurface' => 0, 'count' => 0],
                    'Villa' => ['totalPrix' => 0, 'totalSurface' => 0, 'count' => 0],
                    'Terrain' => ['totalPrix' => 0, 'totalSurface' => 0, 'count' => 0]
                ];
            }
            if (isset($stats[$gouv][$type])) {
                $stats[$gouv][$type]['totalPrix'] += $prix;
                $stats[$gouv][$type]['totalSurface'] += $surface;
                $stats[$gouv][$type]['count']++;
            }
        }

        foreach ($gouvernorats as $gouv) {
            // Appartement
            $dataA = $stats[$gouv]['Appartement'];
            $prix_appart[$gouv] = $dataA['count'] ? round($dataA['totalPrix'] / $dataA['totalSurface'], 0) : null;
            // Villa
            $dataV = $stats[$gouv]['Villa'];
            $prix_villa[$gouv] = $dataV['count'] ? round($dataV['totalPrix'] / $dataV['totalSurface'], 0) : null;
            // Terrain
            $dataT = $stats[$gouv]['Terrain'];
            $prix_terrain[$gouv] = $dataT['count'] ? round($dataT['totalPrix'] / $dataT['totalSurface'], 0) : null;
        }

        return $this->render('default/prix_map_tunisie.html.twig', [
            'gouvernorats' => $gouvernorats,
            'prix_appart' => $prix_appart,
            'prix_villa' => $prix_villa,
            'prix_terrain' => $prix_terrain
        ]);
    }
}
