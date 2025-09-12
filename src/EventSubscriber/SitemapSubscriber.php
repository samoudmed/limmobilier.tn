<?php


namespace App\EventSubscriber;

use App\Repository\AnnoncesRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;
use Symfony\Component\String\Slugger\AsciiSlugger;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var AnnoncesRepository
     */
    private $annoncesRepository;
    private $urlGenerator; // Assuming you also inject this

    // The type-hint here must match the class you imported with the 'use' statement
    public function __construct(
        AnnoncesRepository $annoncesRepository,
        /* other dependencies */
    ) {
        $this->annoncesRepository = $annoncesRepository;
        // ...
    }

    /**
     * @inheritdoc
     */
    public static function getSubscribedEvents()
    {
        return [
            SitemapPopulateEvent::class => 'populate',
        ];
    }

    /**
     * @param SitemapPopulateEvent $event
     */
    public function populate(SitemapPopulateEvent $event): void
    {
        $this->registerAnnoncesUrls($event->getUrlContainer(), $event->getUrlGenerator());
    }

    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
     */
    public function registerAnnoncesUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        $annonces = $this->annoncesRepository->findAll();

        foreach ($annonces as $annonce) {
            $slug = $slugger->slug($annonce->getLabel())->lower();
            $urls->addUrl(
                new UrlConcrete(
                    $router->generate(
                        'annonce_details',           // your route name
                        ['label' => $slug, 'id' => $annonce->getId()], // adjust params
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'annonce'
            );
        }
    }
}