<?php

namespace App\Controller\Admin;

use App\Entity\Meta;
use App\Entity\Annonces;
use App\Form\MetaType;
use App\Repository\MetaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MetaController extends AbstractController {

    /**
     * @Route("/admin/meta", name="app_meta_index", methods={"GET"})
     */
    public function index(MetaRepository $metaRepository): Response {
        return $this->render('admin/meta/index.html.twig', [
                    'metas' => $metaRepository->findAll(),
        ]);
    }

    /**
     * @Route("/admin/new", name="app_meta_new", methods={"GET", "POST"})
     */
    public function new(Request $request, MetaRepository $metaRepository): Response {
        $metum = new Meta();
        $form = $this->createForm(MetaType::class, $metum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $metaRepository->add($metum, true);

            return $this->redirectToRoute('app_meta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/meta/new.html.twig', [
                    'metum' => $metum,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/{id}/show", name="app_meta_show", methods={"GET"})
     */
    public function show(Meta $metum): Response {
        return $this->render('admin/meta/show.html.twig', [
                    'metum' => $metum,
        ]);
    }

    /**
     * @Route("/admin/{id}/edit", name="app_meta_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Meta $metum, MetaRepository $metaRepository): Response {
        $form = $this->createForm(MetaType::class, $metum);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $metaRepository->add($metum, true);

            return $this->redirectToRoute('app_meta_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/meta/edit.html.twig', [
                    'metum' => $metum,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/admin/{id}/delete", name="app_meta_delete", methods={"POST"})
     */
    public function delete(Request $request, Meta $metum, MetaRepository $metaRepository): Response {
        if ($this->isCsrfTokenValid('delete' . $metum->getId(), $request->request->get('_token'))) {
            $metaRepository->remove($metum, true);
        }

        return $this->redirectToRoute('app_meta_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/getMeta", name="getMeta")
     */
    public function getMeta($idEntity, $entity) {

        $meta = $this->getDoctrine()
                ->getRepository(Meta::class)
                ->findOneBy(['entity' => $entity, 'idEntity' => $idEntity]);

        if (($entity == 'annonce') && ($meta == null)) {

            $annonce = $this->getDoctrine()
                    ->getRepository(Annonces::class)
                    ->findOneBy(array('id' => $idEntity));

            $meta = new Meta();

            $title = $annonce->getOffre();

            if ($annonce->getKind() == 'Appartement') {
                $title .= ' ' . $annonce->getKind();
            } elseif ($annonce->getKind() == 'Autre') {
                $title .= ' immobilier';
            } else {
                $title .= ' ' . $annonce->getKind();
            }

            $title .= ' à ' . $annonce->getDelegation().' '.$annonce->getLabel();

            $meta->setTitle(substr($title, 0, 70));
            $meta->setDescription(substr('Tunisie annonce immobilier ' . strip_tags($annonce->getLabel() . ' ' . $annonce->getOffre() . ' de ' . $annonce->getKind() . ' à ' . $annonce->getDelegation()), 0, 150));
        }
        //dd($meta);
        return $this->render('default/__meta.html.twig', ['meta' => $meta]);
    }

    /**
     * @Route("/getTextFooter", name="getTextFooter")
     */
    public function getTextFooter($idEntity, $entity) {

        $meta = $this->getDoctrine()
                ->getRepository(TextFooter::class)
                ->findOneBy(['entity' => 'index', 'idEntity' => '1']);
        dd($meta);
        return $this->render('default/__textFooter.html.twig', ['meta' => $meta]);
    }
}
