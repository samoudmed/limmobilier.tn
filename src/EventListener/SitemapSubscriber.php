<?php

namespace App\EventListener;

use App\Repository\AnnoncesRepository;
use App\Entity\Annonces;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SitemapSubscriber implements EventSubscriberInterface
{
    private UrlGeneratorInterface $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
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

        // Fetch active annonces (limit 24)
        $annonces = $this->getDoctrine()->getRepository(Annonces::class)->findAll();

        foreach ($annonces as $annonce) {
            $url = $this->urlGenerator->generate('annonce_details', [
                'slug' => $annonce->getSlug(),
            ], UrlGeneratorInterface::ABSOLUTE_URL);

            $urlContainer->addUrl(
                new UrlConcrete($url),
                'annonces'
            );
        }
    }
}

