<?php

// src/EventListener/InspectorListener.php
namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use App\Service\InspectorService;

class InspectorListener
{
    private InspectorService $inspector;

    public function __construct(InspectorService $inspector)
    {
        $this->inspector = $inspector;
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        $request = $event->getRequest();
        $ip = $request->getClientIp();
        $url = $request->getRequestUri();

        // Si IP bannie : bloquer immédiatement
        if ($this->inspector->isIpBanned($ip)) {
            throw new AccessDeniedHttpException("Votre IP est bannie.");
        }

        // Si URL suspecte, ban l’IP et bloque
        if ($this->inspector->isUrlSuspicious($url)) {
            $this->inspector->banIp($ip);
            throw new AccessDeniedHttpException("Accès interdit. Votre IP a été bannie.");
        }
    }
}
