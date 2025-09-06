<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Contact;
use App\Form\ContactType;

class ContentController extends AbstractController {

    /**
     * @Route("/mentions-legales.html", name="mentions_legales", methods={"GET"})
     */
    public function mentions(Request $request) {

        return $this->render('default/mentions.html.twig');
    }

    /**
     * @Route("/faq.html", name="FAQ", methods={"GET"})
     */
    public function faq(Request $request) {

        return $this->render('default/faq.html.twig');
    }

    /**
     * @Route("/confidentialites.html", name="confidentialites", methods={"GET"})
     */
    public function confidentialites(Request $request) {

        return $this->render('default/confidentialites.html.twig');
    }

    public function utilisation(Request $request) {

        return $this->render('default/utilisation.html.twig');
    }

    /**
     * @Route("/contact.html", name="contact")
     */
    public function contact(Request $request, MailerInterface $mailer) {

        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $em = $this->getDoctrine()->getManager();
            $contact = $form->getData();

            $contact->setCreatedAt(new \DateTime('now'));
            $contact->setUpdatedAt(new \DateTime('now'));
            $em->persist($contact);
            $em->flush();

            $email = (new TemplatedEmail())
                    ->from('contact@limmobilier.tn')
                    ->to('samoud.mohamed@gmail.com')
                    ->subject('Contact')
                    // path of the Twig template to render
                    ->htmlTemplate('emails/contact.html.twig')

                    // pass variables (name => value) to the template
                    ->context([
                'nom' => $contact->getNom() . ' ' . $contact->getPrenom(),
                'contact' => $contact,
            ]);

            $mailer->send($email);

            $this->addFlash(
                    'success', 'Votre Annonce a été ajouté avec succès!'
            );
        }

        return $this->render('default/contact.html.twig', ['form' => $form->createView()]);
    }

}
