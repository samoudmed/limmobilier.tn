<?php

namespace App\Controller;

use App\Repository\AnnonceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

class SitemapController extends AbstractController
{
    #[Route('/generate-sitemap', name: 'generate_sitemap')]
    public function generateSitemap(AnnonceRepository $annonceRepository): Response
    {
        $urls = [];
        $hostname = $this->getParameter('app.site_url'); // Défini dans `services.yaml`

        // Pages principales
        $urls[] = ['loc' => $hostname, 'priority' => '1.0'];
        $urls[] = ['loc' => $hostname . '/contact', 'priority' => '0.8'];
        $urls[] = ['loc' => $hostname . '/about', 'priority' => '0.8'];

        // Récupérer toutes les annonces
        $annonces = $annonceRepository->findAll();

        foreach ($annonces as $annonce) {
            $urls[] = [
                'loc' => $hostname . '/annonce/' . $annonce->getSlug(),
                'lastmod' => $annonce->getUpdatedAt()->format('Y-m-d'),
                'priority' => '0.7'
            ];
        }

        // Générer le contenu XML
        $xmlContent = $this->renderView('sitemap/sitemap.xml.twig', ['urls' => $urls]);

        // Sauvegarde dans public/sitemap.xml
        file_put_contents($this->getParameter('kernel.project_dir') . '/public/sitemap.xml', $xmlContent);

        return new Response('Sitemap generated successfully!', Response::HTTP_OK);
    }
}
