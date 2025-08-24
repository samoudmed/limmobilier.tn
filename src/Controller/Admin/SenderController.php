<?php

namespace App\Controller\Admin;

use App\Entity\Sender;
use App\Form\SenderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/sender")
 */
class SenderController extends AbstractController
{
    /**
     * @Route("/", name="app_sender_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $senders = $entityManager
            ->getRepository(Sender::class)
            ->findAll();

        return $this->render('admin/sender/index.html.twig', [
            'senders' => $senders,
        ]);
    }

    /**
     * @Route("/new", name="app_sender_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $sender = new Sender();
        $form = $this->createForm(SenderType::class, $sender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($sender);
            $entityManager->flush();

            return $this->redirectToRoute('app_sender_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/sender/new.html.twig', [
            'sender' => $sender,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sender_show", methods={"GET"})
     */
    public function show(Sender $sender): Response
    {
        return $this->render('admin/sender/show.html.twig', [
            'sender' => $sender,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_sender_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Sender $sender, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SenderType::class, $sender);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_sender_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/sender/edit.html.twig', [
            'sender' => $sender,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_sender_delete", methods={"POST"})
     */
    public function delete(Request $request, Sender $sender, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$sender->getId(), $request->request->get('_token'))) {
            $entityManager->remove($sender);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_sender_index', [], Response::HTTP_SEE_OTHER);
    }
}
