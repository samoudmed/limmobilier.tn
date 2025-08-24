<?php

namespace App\Controller\Admin;

use App\Entity\Delegation;
use App\Form\DelegationType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/delegation")
 */
class DelegationController extends AbstractController
{
    /**
     * @Route("/", name="app_delegation_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $delegations = $entityManager
            ->getRepository(Delegation::class)
            ->findAll();

        return $this->render('admin/delegation/index.html.twig', [
            'delegations' => $delegations,
        ]);
    }

    /**
     * @Route("/new", name="app_delegation_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $delegation = new Delegation();
        $form = $this->createForm(DelegationType::class, $delegation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($delegation);
            $entityManager->flush();

            return $this->redirectToRoute('app_delegation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/delegation/new.html.twig', [
            'delegation' => $delegation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_delegation_show", methods={"GET"})
     */
    public function show(Delegation $delegation): Response
    {
        return $this->render('admin/delegation/show.html.twig', [
            'delegation' => $delegation,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_delegation_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Delegation $delegation, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(DelegationType::class, $delegation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_delegation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/delegation/edit.html.twig', [
            'delegation' => $delegation,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_delegation_delete", methods={"POST"})
     */
    public function delete(Request $request, Delegation $delegation, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$delegation->getId(), $request->request->get('_token'))) {
            $entityManager->remove($delegation);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_delegation_index', [], Response::HTTP_SEE_OTHER);
    }
}
