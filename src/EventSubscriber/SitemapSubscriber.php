<?php


namespace App\EventSubscriber;

use App\Entity\Annonces;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var AnnoncesRepository
     */
    private $annoncesRepository;

    /**
     * @param AnnoncesRepository $annoncesRepository
     */
    public function __construct(AnnoncesRepository $annoncesRepository)
    {
        $this->annoncesRepository = $annoncesRepository;
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
            $urls->addUrl(
                new UrlConcrete(
                    $router->generate(
                        'annonce_show',
                        ['slug' => $annonce->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'annonce'
            );
        }
    }
}