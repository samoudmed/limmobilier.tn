<?php

namespace App\Controller\Admin;

use App\Entity\Message;
use App\Form\MessageType;
use App\Repository\MessageRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Knp\Component\Pager\PaginatorInterface;
use App\Entity\Annonces;

/**
 * @Route("/admin/messages")
 */
class MessageController extends AbstractController
{
    /**
     * @Route("/{page}", name="app_message_index", methods={"GET"}, requirements={"page"="\d+"})
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, $page = 1): Response
    {
        $messagesAll = $entityManager
                ->getRepository(Message::class)
                ->findAll();

        $messages = $paginator->paginate(
                $messagesAll, /* query NOT result */
                $page, /* page number */
                50 /* limit per page */
        );
        
        return $this->render('admin/message/index.html.twig', [
                    'messages' => $messages,
                    'messagesAll' => $messagesAll
        ]);
        
        return $this->render('admin/message/index.html.twig', [
            'messagesAll' => $messagesAll,
            'messages' => $messages,
        ]);
    }

    /**
     * @Route("/new", name="app_message_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $message->setStatut(1);
            $message->setIsDeleted(0);
            $message->setCreatedAt(new \DateTime());
            $message->setUpdatedAt(new \DateTime());
            $message->setAnnonce();
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/message/new.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/details/{id}", name="app_message_show", methods={"GET"})
     */
    public function show(Message $message): Response
    {
        return $this->render('admin/message/show.html.twig', [
            'message' => $message,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="app_message_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/message/edit.html.twig', [
            'message' => $message,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/delete/{id}", name="app_message_delete", methods={"POST"})
     */
    public function delete(Request $request, Message $message, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$message->getId(), $request->request->get('_token'))) {
            $entityManager->remove($message);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_message_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route("/validate", name="app_message_validate", methods={"POST"})
     */
    public function validate(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {

        $message = $entityManager
                ->getRepository(Message::class)
                ->findOneById($request->request->get('id'));

        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneBy(array('id' => $message->getAnnonce()));
        
        $statut = filter_var($request->request->get('statut'), FILTER_VALIDATE_BOOLEAN);
        $message->setStatut($statut);
        
        $entityManager->persist($message);
        $entityManager->flush();
        

        if ($statut == true) {
            $email = (new TemplatedEmail())
                    ->from('contact@limmobilier.tn')
                    ->to($message->getReceiver()->getEmail())
                    ->subject('Un visiteur désire plus d\'informations')
                    // path of the Twig template to render
                    ->htmlTemplate('emails/details_message.html.twig')

                    // pass variables (name => value) to the template
                    ->context([
                'message' => $message,
                'annonce' => $annonce,
            ]);

            $mailer->send($email);
        }

        return new Response('ok');
    }
    
    
}
