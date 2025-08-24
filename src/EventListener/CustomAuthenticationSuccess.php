<?php

namespace App\EventListener;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Connexion;

class CustomAuthenticationSuccess {

    private $em;
    private $logger;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event) {
        $this->onAuthenticationSuccess($event->getRequest(), $event->getAuthenticationToken());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token) {
        
        if ($token->getUser()->getId() != 1) {
            $connexion = new Connexion();
            $connexion->setCreatedAt(new \DateTimeImmutable('now'));
            $connexion->setUser($token->getUser());
            $this->em->persist($connexion);
            $this->em->flush();

            $this->em->flush(); // If you don't do this, your changes will not be saved.
        }

        $response = new RedirectResponse('ok');

        return $response;
    }
}
