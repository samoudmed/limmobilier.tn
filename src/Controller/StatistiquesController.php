    /**
     * @Route("/estimation-bien.html", name="estimation_bien", methods={"GET", "POST"})
     */
    public function estimationBien(Request $request)
    {
        // Gouvernorats et types pour le formulaire
        $gouvernorats = array_keys($this->getPrixReference());
        $types = [];
        foreach ($this->getPrixReference() as $gouv => $typeArr) {
            foreach (array_keys($typeArr) as $type) {
                if (!in_array($type, $types)) {
                    $types[] = $type;
                }
            }
        }

        $resultat = null;
        if ($request->isMethod('POST')) {
            $gouv = $request->request->get('gouvernorat');
            $type = $request->request->get('type');
            $surfaceTotale = (float)$request->request->get('surface_totale');
            $surfaceBat = (float)$request->request->get('surface_batie');
            $chambres = (int)$request->request->get('chambres');
            $options = $request->request->get('options', []);

            $prixRef = $this->getPrixReference();
            $prixM2 = isset($prixRef[$gouv][$type]) ? $prixRef[$gouv][$type] : 0;
            $prixBase = $surfaceTotale * $prixM2;

            // Bonus options (exemple simple)
            $bonus = 0;
            if (is_array($options)) {
                foreach ($options as $opt) {
                    if ($opt === 'jardin') $bonus += 0.05 * $prixBase;
                    if ($opt === 'garage') $bonus += 0.03 * $prixBase;
                    if ($opt === 'piscine') $bonus += 0.08 * $prixBase;
                    if ($opt === 'terrasse') $bonus += 0.02 * $prixBase;
                }
            }
            $prixEstime = $prixBase + $bonus;

            $resultat = [
                'prix_estime' => round($prixEstime, 0),
                'prix_m2' => $prixM2,
                'gouvernorat' => $gouv,
                'type' => $type,
                'surface_totale' => $surfaceTotale,
                'surface_batie' => $surfaceBat,
                'chambres' => $chambres,
                'options' => $options
            ];
        }

        return $this->render('default/estimation_bien.html.twig', [
            'gouvernorats' => $gouvernorats,
            'types' => $types,
            'resultat' => $resultat
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
}
