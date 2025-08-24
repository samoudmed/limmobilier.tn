<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController {

    /**
     * @Route("/login", name="app_login")
     */
    public function index(AuthenticationUtils $authenticationUtils): Response {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('login/index.html.twig', [
                    'last_username' => $lastUsername,
                    'error' => $error,
        ]);
    }

    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     */
    public function logout(): void {
        // controller can be blank: it will never be called!
        throw new \Exception('Don\'t forget to activate logout in security.yaml');
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        dd('success');
    }
    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        dd('failure');
    }
    
    /*public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) {

        $em = $this->getDoctrine()->getManager();
        $user = $token->getUser();

        $connexion = new Connexion();
        $connexion->setCreatedAt(new \DateTime('now'));
        $connexion->setCreatedAt($user);
        $em->persist($connexion);
        $em->flush();
        dd($connexion);
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        if ($user->getType() == 'employer') {
            // If is a admin or super admin we redirect to the backoffice area
            return new RedirectResponse($this->urlGenerator->generate('employer-dashboard'));
        } else {
            return new RedirectResponse($this->urlGenerator->generate('dashboard'));
        }
    }*/
}
