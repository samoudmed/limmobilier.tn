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

        $stats = [];
        foreach ($annonces as $annonce) {
            $gouv = $annonce->getGouvernorat() ? $annonce->getGouvernorat()->getLabel() : 'Inconnu';
            $type = $annonce->getKind();
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
                $resultats[] = [
                    'gouvernorat' => $gouv,
                    'type' => $type,
                    'prix_moyen' => $moyennePrix,
                    'prix_m2' => $moyenneM2,
                    'nb_annonces' => $data['count']
                ];
            }
        }

        return $this->render('default/statistiques_immobilier.html.twig', [
            'stats' => $resultats
        ]);
    }
}
