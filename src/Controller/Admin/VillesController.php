<?php

namespace App\Controller\Admin;

use App\Entity\Villes;
use App\Form\VillesType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/villes")
 */
class VillesController extends AbstractController
{
    /**
     * @Route("/", name="app_villes_index", methods={"GET"})
     */
    public function index(EntityManagerInterface $entityManager): Response
    {
        $villes = $entityManager
            ->getRepository(Villes::class)
            ->findAll();

        return $this->render('admin/villes/index.html.twig', [
            'villes' => $villes,
        ]);
    }

    /**
     * @Route("/new", name="app_villes_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $ville = new Villes();
        $form = $this->createForm(VillesType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ville);
            $entityManager->flush();

            return $this->redirectToRoute('app_villes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/villes/new.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_villes_show", methods={"GET"})
     */
    public function show(Villes $ville): Response
    {
        return $this->render('admin/villes/show.html.twig', [
            'ville' => $ville,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_villes_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Villes $ville, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VillesType::class, $ville);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_villes_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/villes/edit.html.twig', [
            'ville' => $ville,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_villes_delete", methods={"POST"})
     */
    public function delete(Request $request, Villes $ville, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$ville->getId(), $request->request->get('_token'))) {
            $entityManager->remove($ville);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_villes_index', [], Response::HTTP_SEE_OTHER);
    }
}
