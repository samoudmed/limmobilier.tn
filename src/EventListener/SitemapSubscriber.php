<?php

namespace App\EventListener;

use App\Repository\AnnoncesRepository;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapSubscriber implements EventSubscriberInterface
{
    private AnnoncesRepository $annoncesRepository;
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(AnnoncesRepository $annoncesRepository, UrlGeneratorInterface $urlGenerator)
    {
        $this->annoncesRepository = $annoncesRepository; // must match property name
        $this->urlGenerator = $urlGenerator;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            SitemapPopulateEvent::class => 'onSitemapPopulate',
        ];
    }

    public function onSitemapPopulate(SitemapPopulateEvent $event): void
    {
        $urlContainer = $event->getUrlContainer();

        // Use injected repository
        $annonces = $this->annoncesRepository->findActive(24);

        foreach ($annonces as $annonce) {
            $url = $this->urlGenerator->generate('annonce_show', [
                'slug' => $annonce->getSlug(),
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $urlContainer->addUrl(
                new UrlConcrete($url),
                'annonces'
            );
        }
    }
}


