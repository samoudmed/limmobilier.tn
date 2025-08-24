<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Newsletter;
use App\Entity\NewsletterGeo;
use App\Form\NewsletterType;
USE App\Form\NewsletterGeoType;
use App\Entity\Gouvernorat;
use App\Entity\Delegation;
use App\Entity\Villes;

class NewsletterController extends AbstractController {

    public function buildNewsletterForm(Request $request) {

        $newsletter = new Newsletter();
        $form = $this->createForm(NewsletterType::class, $newsletter);

        return $this->render('default/_newsletter.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/newsletter.html", name="newsletter")
     */
    public function newsletter(Request $request) {

        $em = $this->getDoctrine()->getManager();
        $email = $request->request->get('email');

        $newsletter = $this->getDoctrine()
                ->getRepository(Newsletter::class)
                ->findOneByEmail($email);

        if (!$newsletter) {
            $newsletter = new Newsletter();
            $newsletter->setEmail($email);
            $newsletter->setUnsubscribed(0);
            $em->persist($newsletter);
            $em->flush();
        }

        return $this->json($newsletter->getId());
    }

    /**
     * @Route("/unsubscribe-{id}.html", name="unsubscribe", methods={"GET"})
     */
    public function unsubscribe(Request $request, $id) {

        $em = $this->getDoctrine()->getManager();
        $newsletter = $this->getDoctrine()
                ->getRepository(Newsletter::class)
                ->findOneById($id);

        $newsletter->setUnsubscribed(0);
        $em->persist($newsletter);
        $em->flush();

        return $this->render('default/unsubscribe.html.twig');
    }

    /**
     * @Route("/preference-{id}.html", name="preference")
     */
    public function preference(Request $request, $id) {

        $newsletterGeo = new NewsletterGeo();
        $form = $this->createForm(NewsletterGeoType::class, $newsletterGeo);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $newsletter = $this->getDoctrine()
                    ->getRepository(Newsletter::class)
                    ->findOneById($id);

            $newsletterGeo = $form->getData();
            dd($form->getData());
            if ($request->request->get('gouvernorat')) {
                $gouvernoratId = $request->request->get('gouvernorat');
                $gouvernorat = $this->getDoctrine()
                        ->getRepository(Gouvernorat::class)
                        ->findOneById($gouvernoratId);
                $newsletterGeo->setGouvernorat($gouvernorat);
            }

            if ($request->request->get('delegation')) {
                $delegationId = $request->request->get('delegation');
                $delegation = $this->getDoctrine()
                        ->getRepository(Delegation::class)
                        ->findOneById($delegationId);
                $newsletterGeo->setDelegation($delegation);
            }

            if ($request->request->get('city')) {
                $villeId = $request->request->get('city');
                $ville = $this->getDoctrine()
                        ->getRepository(Villes::class)
                        ->findOneById($villeId);
                $newsletterGeo->setVille($ville);
            }
            
            $newsletterGeo->setNewsletter($newsletter);

            $em->persist($newsletterGeo);
            $em->flush();

            $this->addFlash(
                    'success', 'Votre message a été ajouté avec succès!'
            );
        }

        return $this->render('default/preference.html.twig', ['form' => $form->createView()]);
    }

}
