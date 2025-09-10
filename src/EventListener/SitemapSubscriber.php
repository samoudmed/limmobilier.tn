<?php

namespace App\EventListener;

use App\Repository\AnnonceRepository;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    private AnnonceRepository $annonceRepository;

    public function __construct(AnnonceRepository $annonceRepository)
    {
        $this->annonceRepository = $annonceRepository;
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

        foreach ($this->annonceRepository->findAll() as $annonce) {
            $url = $event->getUrlGenerator()->generate('annonce_show', [
                'slug' => $annonce->getSlug(),
            ]);

            $urlContainer->addUrl(
                new UrlConcrete($url),
                'annonces'
            );
        }
    }
}
