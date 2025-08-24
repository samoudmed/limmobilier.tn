<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Annonces;
use App\Entity\Villes;
use App\Entity\Photos;
use App\Entity\Newsletter;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class MailingController extends AbstractController
{
    /**
     * @Route("/mailling.html", name="mailling")
     */
    public function index(Request $request, MailerInterface $mailer) {

        
        $subscribers = $this->getDoctrine()
                ->getRepository(Newsletter::class)
                ->findByUnsubscribed(0);
        
        foreach ($subscribers as $subscriber) {
            
            $annonces = array();
            $preferences = $subscriber->getNewsletterGeo();
            $result = $this->getDoctrine()
                    ->getRepository(Annonces::class)
                    ->findActive(24);

                $annonces = array_merge($annonces, $result);
            
            
            if(count($annonces)) {
                $annonces = $this->getFeaturedPhoto($annonces);
                $email = (new TemplatedEmail())
                    ->from(new Address('contact@limmobilier.tn', 'LImmobilier.tn'))
                    ->to(new Address($subscriber->getEmail()))
                    //->to(new Address('samoud.mohamed@gmail.com'))    
                    ->subject('Découvrez les nouvelles annonces immobilières sur limmobilier.tn')

                    // path of the Twig template to render
                    ->htmlTemplate('emails/newsletter.html.twig')

                    // pass variables (name => value) to the template
                    ->context(['id' => $subscriber->getId(), 'name' => $subscriber->getNom(), 'lastName' => $subscriber->getPrenom(), 'annonces' => $annonces])
                ;
                //$mailer->send($email);
            }
            
            return $this->render('emails/newsletter.html.twig', ['id' => $subscriber->getId(), 'name' => $subscriber->getNom(), 'lastName' => $subscriber->getPrenom(), 'annonces' => $annonces]);
        }

        return new Response();
    }
    
    public function getFeaturedPhoto($annonces) {

        foreach ($annonces as $k => $annonce) {

            $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneBy(array('annonce' => $annonce, 'featured' => 1));
            if(!$photo) {
                $photo = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findOneBy(array('annonce' => $annonce));
            }
            $annonces[$k]->photo = (isset($photo)) ? $photo->getNom() : 'default-img.png';
        }

        return $annonces;
    }
}
