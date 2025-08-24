<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\Annonces;
use App\Entity\Newsletter;
use App\Entity\Photos;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mime\Address;

class CronJobController extends AbstractController {

    /**
     * @Route("/archived.html", name="archived")
     */
    public function archived() {

        $em = $this->getDoctrine()->getManager();
        $annonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->getOldAds();
        foreach ($annonces as $annonce) {
            $annonce->setArchived(1);
            $em->persist($annonce);
            $em->flush();
            $photos = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findBy(array('annonce' => $annonce));
            foreach ($photos as $photo) {
                if (file_exists('/home/limmobilier/public_html/public/uploads/photos/' . $photo)) {
                    unlink('/home/limmobilier/public_html/public/uploads/photos/' . $photo);
                }
                if (file_exists('/home/limmobilier/public_html/public/uploads/photos/263x175/' . $photo)) {
                    unlink('/home/limmobilier/public_html/public/uploads/photos/263x175/' . $photo);
                }
                if (file_exists('/home/limmobilier/public_html/public/uploads/photos/263x175/webp/' . $photo . '.webp')) {
                    unlink('/home/limmobilier/public_html/public/uploads/photos/263x175/webp/' . $photo . '.webp');
                }
                if (file_exists('/home/limmobilier/public_html/public/uploads/photos/848x682/' . $photo)) {
                    unlink('/home/limmobilier/public_html/public/uploads/photos/848x682/' . $photo);
                }
                if (file_exists('/home/limmobilier/public_html/public/uploads/photos/848x682/webp/' . $photo . '.webp')) {
                    unlink('/home/limmobilier/public_html/public/uploads/photos/848x682/webp/' . $photo . '.webp');
                }
                if (file_exists('/home/limmobilier/public_html/public/uploads/photos/86x50/' . $photo)) {
                    unlink('/home/limmobilier/public_html/public/uploads/photos/86x50/' . $photo);
                }
            }
        }

        return new Response();
    }

}
