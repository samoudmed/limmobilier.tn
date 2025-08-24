<?php

namespace App\Controller\Admin;

use App\Entity\Kind;
use App\Form\TypesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/types")
 */
class TypesController extends AbstractController
{
    /**
     * @Route("/", name="app_types_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $types = $entityManager
            ->getRepository(Kind::class)
            ->findAll();

        return $this->render('admin/types/index.html.twig', [
            'types' => $types,
        ]);
    }

    /**
     * @Route("/new", name="app_types_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = new Types();
        $form = $this->createForm(TypesType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('app_types_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/types/new.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_types_show", methods={"GET"})
     */
    public function show(Types $type): Response
    {
        return $this->render('admin/types/show.html.twig', [
            'type' => $type,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_types_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Types $type, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TypesType::class, $type);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_types_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/types/edit.html.twig', [
            'type' => $type,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_types_delete", methods={"POST"})
     */
    public function delete(Request $request, Types $type, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$type->getId(), $request->request->get('_token'))) {
            $entityManager->remove($type);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_types_index', [], Response::HTTP_SEE_OTHER);
    }
}
