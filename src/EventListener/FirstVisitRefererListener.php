<?php

// src/EventListener/FirstVisitRefererListener.php

namespace App\EventListener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FirstVisitRefererListener implements EventSubscriberInterface
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // Check if the referer is already stored in the session
        $referer = $this->session->get('firstVisitReferer');

        // If not, store the referer from the current request
        if (!$referer) {
            $referer = $request->headers->get('referer');
            $this->session->set('firstVisitReferer', $referer);
        }
    }

    public static function getSubscribedEvents()
    {
        return [
            KernelEvents::REQUEST => 'onKernelRequest',
        ];
    }
}

