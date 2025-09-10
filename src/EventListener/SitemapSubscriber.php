<?php

namespace App\EventListener;

use App\Repository\AnnonceRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Presta\SitemapBundle\Event\SitemapPopulateEvent;
use Presta\SitemapBundle\Service\UrlContainerInterface;
use Presta\SitemapBundle\Sitemap\Url\UrlConcrete;

class SitemapSubscriber implements EventSubscriberInterface
{
    /**
     * @var BlogPostRepository
     */
    private $blogPostRepository;

    /**
     * @param AnnonceRepository $annonceRepository
     */
    public function __construct(AnnonceRepository $annonceRepository)
    {
        $this->annonceRepository = $annonceRepository;
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
        $this->registerBlogPostsUrls($event->getUrlContainer(), $event->getUrlGenerator());
    }

    /**
     * @param UrlContainerInterface $urls
     * @param UrlGeneratorInterface $router
     */
    public function registerBlogPostsUrls(UrlContainerInterface $urls, UrlGeneratorInterface $router): void
    {
        $annonces = $this->annonceRepository->findAll();

        foreach ($annonces as $annonce) {
            $urls->addUrl(
                new UrlConcrete(
                    $router->generate(
                        'annonce',
                        ['slug' => $annonce->getSlug()],
                        UrlGeneratorInterface::ABSOLUTE_URL
                    )
                ),
                'annonce'
            );
        }
    }
}