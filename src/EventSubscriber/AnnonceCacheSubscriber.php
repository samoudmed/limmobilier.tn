<?php
namespace App\EventSubscriber;

use App\Entity\Annonces;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Contracts\Cache\TagAwareCacheInterface;
use Psr\Log\LoggerInterface;

class AnnonceCacheSubscriber implements EventSubscriber
{
    private TagAwareCacheInterface $cache;
    private LoggerInterface $logger;

    public function __construct(TagAwareCacheInterface $cache, LoggerInterface $logger)
    {
        $this->cache = $cache;
        $this->logger = $logger;
    }

    private function invalidateCache(LifecycleEventArgs $args): void
    {
        $entity = $args->getObject();

        if (!$entity instanceof Annonces) {
            return;
        }

        $this->cache->invalidateTags(['annonces']);
        $this->cache->invalidateTags(['annonce_'.$entity->getId()]);
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove,
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        $this->invalidateCache($args);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        $this->invalidateCache($args);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        $this->invalidateCache($args);
    }
}
