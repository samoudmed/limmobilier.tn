<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Annonces;
use App\Entity\Reponses;
use App\Entity\Sender;
use App\Entity\Message;
use App\Form\MessageType;
use App\Form\ReponsesType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;

class MessagesController extends AbstractController {

    /**
     * @Route("/compte/mes-messages-{page}.html", name="mes_messages", methods={"GET"})
     */
    public function mesMessages(Request $request, $page = 1) {

        $user = $this->getUser();
        $messages = $this->getDoctrine()
                ->getRepository(Message::class)
                ->findBy(array('receiver' => $user->getId(), 'validated' => 1), array('id' => 'DESC'));

        return $this->render('default/compte/mes_messages.html.twig', ['messages' => $messages]);
    }

    /**
     * @Route("/compte/Messages", name="messages_list")
     */
    public function responseIndexAction(Request $request) {

        $user = $this->getUser();
        $messages = $this->getDoctrine()
                ->getRepository(Message::class)
                ->findBySender($user->getId(), array('id' => 'DESC'));

        return $this->render('default/compte/mes-messages-envoyes.html.twig', ['messages' => $messages]);
    }

    /**
     * @Route("/compte/details-message/{id}", name="message_show")
     */
    public function showMessageAction(Request $request, $id, MailerInterface $mailer) {

        $user = $this->getUser();
        $em = $this->getDoctrine()->getManager();
        $message = $this->getDoctrine()
                ->getRepository(Message::class)
                ->findOneById($id);

        $message->setStatut(0);
        $em->persist($message);
        $em->flush();

        $responses = $this->getDoctrine()
                ->getRepository(Reponses::class)
                ->findBy(array('first_message' => $message->getId()), array('id' => 'ASC'));

        $response = new Reponses();
        $form = $this->createForm(ReponsesType::class, $response);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $referer = $request->headers->get('referer');
            $em = $this->getDoctrine()->getManager();
            $response = $form->getData();
            $response->setFirstMessage($message);
            $response->setPreviousMessage($message);
            $response->setCreatedAt(new \DateTime('now'));
            $response->setUpdatedAt(new \DateTime('now'));
            $response->setSender($user);
            $response->setReceiver($message->getSender());
            $response->setStatut(1);
            $response->setIsDeleted(0);
            $message->setStatut(1);
            $em->persist($message);
            $em->persist($response);
            $em->flush();

            $email = (new TemplatedEmail())
                    ->from('contact@limmobilier.tn')
                    ->to($message->getSender()->getEmail())
                    ->subject('Réponse à votre message')
                    ->htmlTemplate('emails/reponse_message.html.twig')
                    ->context([
                'message' => $response->getMessage(),
                'nom' => $message->getSender()->getNom(),
            ]);

            $mailer->send($email);

            return new RedirectResponse($referer);
        }

        return $this->render('default/compte/details_message.html.twig', ['message' => $message, 'responses' => $responses, 'form' => $form->createView()]);
    }

    /**
     * @Route("/send-message", name="send_message")
     */
    public function sendFromDetails(Request $request, MailerInterface $mailer) {

        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $message = $form->getData();
            $sender = new Sender();
            $sender->setNom($request->request->get('name'));
            $sender->setEmail($request->request->get('email'));
            $sender->setTelephone($request->request->get('phone'));

            $referer = $request->headers->get('referer');
            $em = $this->getDoctrine()->getManager();

            $annonce = $this->getDoctrine()
                    ->getRepository(Annonces::class)
                    ->findOneById($request->request->get('id_annonce'));

            $em->persist($sender);
            $em->flush();
            $message->setCreatedAt(new \DateTime('now'));
            $message->setUpdatedAt(new \DateTime('now'));
            $message->setSender($sender);
            $message->setReceiver($annonce->getUser());
            $message->setValidated(0);
            $message->setStatut(0);
            $message->setIsDeleted(0);
            $message->setAnnonce($annonce);
            $em->persist($message);
            $em->flush();

            /*$email = (new TemplatedEmail())
                    ->from('contact@limmobilier.tn')
                    ->to($annonce->getUser()->getEmail())
                    ->subject('Un visiteur désire plus d\'informations.')
                    ->htmlTemplate('emails/details_message.html.twig')
                    ->context(['nom' => $annonce->getUser()->getNom(), 'prenom' => $annonce->getUser()->getPrenom(), 'sender_name' => $request->request->get('name'), 'mail' => $request->request->get('email'), 'telephone' => $request->request->get('phone'), 'annonce' => $annonce, 'message' => $message->getMessage()]);

            $mailer->send($email);*/

            return new RedirectResponse($referer);
        }

        return $this->render('default/compte/details_message.html.twig', ['message' => $message, 'form' => $form->createView()]);
    }

    public function countMessages(Request $request, EntityManagerInterface $entityManager) {

        $message = $entityManager
                ->getRepository(Message::class)
                ->findBy(['receiver' => $this->getUser(), 'statut' => 0]);

        return new Response(count($message));
    }

}
