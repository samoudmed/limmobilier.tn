<?php

namespace App\Controller\Admin;

use App\Entity\Traffic;
use App\Form\TrafficType;
use App\Repository\TrafficRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/traffic")
 */
class TrafficController extends AbstractController
{
    /**
     * @Route("/", name="app_traffic_index", methods={"GET"})
     */
    public function index(TrafficRepository $trafficRepository): Response
    {
        return $this->render('admin/traffic/index.html.twig', [
            'traffic' => $trafficRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_traffic_new", methods={"GET", "POST"})
     */
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $traffic = new Traffic();
        $form = $this->createForm(TrafficType::class, $traffic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($traffic);
            $entityManager->flush();

            return $this->redirectToRoute('app_traffic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/traffic/new.html.twig', [
            'traffic' => $traffic,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_traffic_show", methods={"GET"})
     */
    public function show(Traffic $traffic): Response
    {
        return $this->render('admin/traffic/show.html.twig', [
            'traffic' => $traffic,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_traffic_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Traffic $traffic, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(TrafficType::class, $traffic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_traffic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/traffic/edit.html.twig', [
            'traffic' => $traffic,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_traffic_delete", methods={"POST"})
     */
    public function delete(Request $request, Traffic $traffic, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$traffic->getId(), $request->request->get('_token'))) {
            $entityManager->remove($traffic);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_traffic_index', [], Response::HTTP_SEE_OTHER);
    }
}
