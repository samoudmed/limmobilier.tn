<?php
namespace App\Service;

use App\Repository\AnnoncesRepository;
use Presta\SitemapBundle\Service\SitemapProviderInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Presta\SitemapBundle\Sitemap\Sitemap;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class AnnoncesSitemapProvider implements SitemapProviderInterface
{
    public function __construct(
        private AnnoncesRepository $annoncesRepository,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function buildSitemap(): array
    {
        $sitemap = new Sitemap();

        $annonces = $this->annoncesRepository->findAll();

        foreach ($annonces as $annonce) {
            $sitemap->add(
                new UrlConcrete(
                    $this->urlGenerator->generate(
                        'annonce_details',           // your route name
                        ['slug' => $annonce->getSlug(), 'id' => $annonce->getId()], // adjust params
                        UrlGeneratorInterface::ABSOLUTE_URL
                    ),
                    new \DateTime(),
                    UrlConcrete::CHANGEFREQ_WEEKLY,
                    1
                )
            );
        }

        // Return multiple sitemaps if needed
        return ['annonces' => $sitemap];
    }
}
