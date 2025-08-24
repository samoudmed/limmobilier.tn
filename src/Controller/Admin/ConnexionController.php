<?php

namespace App\Controller\Admin;

use App\Entity\Connexion;
use App\Form\ConnexionType;
use App\Repository\ConnexionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


#[Route('/admin/connexion')]
class ConnexionController extends AbstractController
{
    #[Route('/', name: 'app_connexion_index', methods: ['GET'])]
    public function index(ConnexionRepository $connexionRepository): Response
    {
        //dd($connexionRepository->findAll());
        return $this->render('admin/connexion/index.html.twig', [
            'connexions' => $connexionRepository->findBy(array(), array('id' => 'DESC')),
        ]);
    }

    #[Route('/new', name: 'app_connexion_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $connexion = new Connexion();
        $form = $this->createForm(ConnexionType::class, $connexion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($connexion);
            $entityManager->flush();

            return $this->redirectToRoute('app_connexion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/connexion/new.html.twig', [
            'connexion' => $connexion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_connexion_show', methods: ['GET'])]
    public function show(Connexion $connexion): Response
    {
        return $this->render('admin/connexion/show.html.twig', [
            'connexion' => $connexion,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_connexion_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Connexion $connexion, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ConnexionType::class, $connexion);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_connexion_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/connexion/edit.html.twig', [
            'connexion' => $connexion,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_connexion_delete', methods: ['POST'])]
    public function delete(Request $request, Connexion $connexion, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$connexion->getId(), $request->request->get('_token'))) {
            $entityManager->remove($connexion);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_connexion_index', [], Response::HTTP_SEE_OTHER);
    }
}
