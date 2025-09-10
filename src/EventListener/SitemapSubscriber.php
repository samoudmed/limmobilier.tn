<?php

namespace App\EventListener;

use App\Repository\AnnoncesRepository;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    private AnnoncesRepository $annoncesRepository;

    public function __construct(AnnoncesRepository $annoncesRepository)
    {
        $this->annoncesRepository = $annoncesRepository;
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

        foreach ($this->annoncesRepository->findAll() as $annonce) {
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
