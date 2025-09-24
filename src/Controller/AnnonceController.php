<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Annonces;
use App\Entity\Photos;
use App\Entity\Villes;
use App\Entity\Gouvernorat;
use App\Entity\Delegation;
use App\Entity\Favorite;
use App\Form\UserType;
use App\Form\AnnoncesType;
use Symfony\Component\Finder\SplFileInfo;
use Knp\Component\Pager\PaginatorInterface;
use App\Service\ResizePhoto;
use App\Service\ManagePhoto;
use App\Service\PdfAnnonceGenerator;

class AnnonceController extends AbstractController {

    /**
     * @Route("/compte/ajouter-annonce.html", name="ajouter_annonce", methods={"POST", "GET"})
     */
    public function newAnnonce(Request $request) {

        $annonce = new Annonces();

        $ville = new Villes();
        $form = $this->createForm(AnnoncesType::class, $annonce);

        $form->handleRequest($request);
        $gouvernorats = $this->getDoctrine()
                ->getRepository(Gouvernorat::class)
                ->findAll();

        if ($form->isSubmitted() && $form->isValid()) {

            $typeOffre = $request->request->get('offre');
            $expired_at = $request->request->get('annonces')['expired_at'];
            $dateExpired = new \DateTime();
            $dateExpired->modify('+' . $expired_at . ' days');

            $ville = $this->getDoctrine()
                    ->getRepository(Villes::class)
                    ->findOneById($request->request->get('city'));

            $gouvernorat = $this->getDoctrine()
                    ->getRepository(Gouvernorat::class)
                    ->findOneById($request->request->get('annonces')['gouvernorat']);

            $delegation = $this->getDoctrine()
                    ->getRepository(Delegation::class)
                    ->findOneById($request->request->get('delegation'));

            $em = $this->getDoctrine()->getManager();

            $annonce = $form->getData();
            $annonce->setUser($this->getUser());
            $annonce->setVille($ville);
            $annonce->setGouvernorat($gouvernorat);
            $annonce->setDelegation($delegation);
            $annonce->setCreatedAt(new \DateTime('now'));
            $annonce->setUpdatedAt(new \DateTime('now'));
            $annonce->setDescription(html_entity_decode($annonce->getDescription()));
            $annonce->setExpiredAt($dateExpired->format('Y-m-d H:i:s'));
            $annonce->setCreatedAt(new \DateTime('now'));
            $annonce->setUpdatedAt(new \DateTime('now'));
            $annonce->setStatut($this->getUser()->isActive());
            $annonce->setPublished(1);
            $annonce->setDeleted(0);
            $annonce->setView(0);
            $em->persist($annonce);
            $em->flush();

            //upload photos
            if ($request->files->get('nom')) {
                $this->savePhoto($request->files->get('nom'), 1, $annonce, $user);
            }

            if ($request->files->get('photos')) {
                foreach ($request->files->get('photos') as $file2) {
                    $this->savePhoto($file2, 0, $annonce, $user);
                }
            }

            $this->addFlash(
                'success', 'Votre Annonce a été ajoutée avec succès!'
            );

            $event = new AnnoncePublishedEvent($annonce);
            $this->dispatcher->dispatch($event, 'annonce.published');
        
            return $this->redirectToRoute('mes_annonces');
        }

        return $this->render('default/compte/ajouter_annonce.html.twig', ['bien' => $annonce, 'form' => $form->createView(), 'gouvernorats' => $gouvernorats]);
    }

    /**
     * @Route("/compte/modifier-annonce-{id}.html", name="annonce_update", methods={"GET", "POST"})
     */
    public function editAnnonce(Request $request, $id) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneById($id);

        if ($annonce->getUser()->getId() == $this->getUser()->getId()) {
            $photos = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findByAnnonce($annonce);

            if (!$annonce) {
                throw $this->createNotFoundException('No guest found for id ' . $id);
            }

            $form = $this->createForm(AnnoncesType::class, $annonce);

            $form->handleRequest($request);

            $villes = $this->getDoctrine()
                    ->getRepository(Villes::class)
                    ->findAll();

            if ($form->isSubmitted() && $form->isValid()) {

                $typeOffre = $request->request->get('offre');

                $prix = $request->request->get('prix');
                $file = $request->files->get('nom');

                $ville = $this->getDoctrine()
                        ->getRepository(Villes::class)
                        ->findOneById($request->request->get('annonces')['ville']);

                $delegation = $this->getDoctrine()
                        ->getRepository(Delegation::class)
                        ->findOneById($request->request->get('annonces')['delegation']);

                $gouvernorat = $this->getDoctrine()
                        ->getRepository(Gouvernorat::class)
                        ->findOneById($request->request->get('annonces')['gouvernorat']);

                $em = $this->getDoctrine()->getManager();

                $annonce = $form->getData();
                $annonce->setUser($this->getUser());
                $annonce->setVille($ville);
                $annonce->setDelegation($delegation);
                $annonce->setGouvernorat($gouvernorat);
                $annonce->setCreatedAt(new \DateTime('now'));
                $annonce->setUpdatedAt(new \DateTime('now'));
                $em->persist($annonce);

                $expired_at = $request->request->get('annonces')['expired_at'];
                $dateExpired = new \DateTime();
                $dateExpired->modify('+' . $expired_at . ' days');
                $dateExpired->format('Y-m-d H:i:s');
                $annonce->setExpiredAt($dateExpired);
                $annonce->setUpdatedAt(new \DateTime('now'));

                $em->persist($annonce);
                $em->flush();

                //upload photos
                if ($file) {
                    $this->savePhoto($file, 1, $annonce, $user);
                }

                if ($request->files->get('photos')) {
                    foreach ($request->files->get('photos') as $file2) {
                        $this->savePhoto($file2, 0, $annonce, $user);
                    }
                }

                $this->addFlash(
                        'success', 'Votre Annonce a été ajouté avec succès!'
                );
            }

            $annonce = $this->getDoctrine()
                    ->getRepository(Annonces::class)
                    ->findOneById($id);

            $photos = $this->getDoctrine()
                    ->getRepository(Photos::class)
                    ->findByAnnonce($annonce);
            dd($photos);
            return $this->render('default/compte/modifier_annonce.html.twig', ['annonce' => $annonce, 'form' => $form->createView(), 'villes' => $villes, 'photos' => $photos]);
        } else {
            return $this->redirectToRoute('mes_annonces');
        }
    }

    public function savePhoto($fichier, $featured, $annonce, $user) {

        $em = $this->getDoctrine()->getManager();
        $info = new SplFileInfo($fichier->getClientOriginalName(), PATHINFO_EXTENSION, 'test');
        $fileName = md5(date('Y-m-d H:i:s.u') . rand(1000, 9999)) . '.' . $info->getExtension();
        $fichier->move($this->getParameter('photo_directory'), $fileName);

        $photo = new Photos();
        $photo->setNom($fileName);
        $photo->setFeatured($featured);
        $photo->setCreatedAt(new \DateTime('now'));
        $photo->setAnnonce($annonce);
        $photo->setUser($user);
        $em->persist($photo);
        $em->flush();

        $this->generatePhoto($fileName);
    }

    public function generatePhoto($filename) {

        $path_parts = pathinfo($this->getParameter('photo_directory') . '/' . $filename);
        $managePhoto = new ResizePhoto($this->getParameter('photo_directory') . '/' . $filename);

        $managePhoto->resizeImage(86, 50, 'crop');
        $managePhoto->saveImage($this->getParameter('photo_directory') . '/86x50/' . $filename, 100);

        $managePhoto->resizeImage(263, 175, 'crop');
        $managePhoto->saveImage($this->getParameter('photo_directory') . '/263x175/' . $filename, 100);

        $managePhoto->resizeImage(263, 175, 'crop');
        $managePhoto->saveImage($this->getParameter('photo_directory') . '/263x175/webp/' . $filename . '.webp', 100);

        $managePhoto->resizeImage(848, 682, 'crop');
        $managePhoto->saveImage($this->getParameter('photo_directory') . '/848x682/' . $filename, 100);

        $managePhoto->resizeImage(848, 682, 'crop');
        $managePhoto->saveImage($this->getParameter('photo_directory') . '/848x682/webp/' . $filename . '.webp', 100);
    }

    /**
     * @Route("/compte/delete-annonce-{id}.html", name="annonce_delete", methods={"GET"})
     */
    public function deleteAnnonce($id) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $em = $this->getDoctrine()->getManager();
        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneById($id);

        if (!$annonce) {
            throw $this->createNotFoundException('No guest found for id ' . $id);
        }
        $annonce->setPublished(0);
        $annonce->setDeleted(1);

        $em->persist($annonce);
        $em->flush();

        return $this->redirectToRoute('mes_annonces');
    }

    /**
     * @Route("/compte/publier-annonce-{id}-{statut}.html", name="annonce_published", methods={"GET"})
     */
    public function publishedAnnonced(Request $request, $id, $statut) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneBy(array('user' => $this->getUser(), 'id' => $id), array('id' => 'DESC'));

        if (!$annonce) {
            throw $this->createNotFoundException('No guest found for id ' . $id);
        }


        $em = $this->getDoctrine()->getManager();
        $published = ($statut == 1) ? 0 : 1;
        $annonce->setPublished($published);
        $annonce->setUpdatedAt(new \DateTime('now'));

        $em->persist($annonce);
        $em->flush();
        $this->addFlash(
                'success', 'Votre Annonce a été ajouté avec succès!'
        );

        //return $this->redirectToRoute('homepage');
        $redirect = $request->headers->get('referer');
        return $this->redirect($redirect);
    }

    /**
     * @Route("/compte/mes-annonces-{page}.html", name="mes_annonces", methods={"GET"})
     */
    public function mesAnnonces(Request $request, PaginatorInterface $paginator, ManagePhoto $managePhoto, $page = 1) {

        $allAnnonces = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findBy(array('user' => $this->getUser(), 'deleted' => 0), array('id' => 'DESC'));

        $annoncesList = $managePhoto->getFeaturedPhoto($allAnnonces);

        $annonces = $paginator->paginate(
                $annoncesList, /* query NOT result */
                $request->query->getInt('page', $page), /* page number */
                10 /* limit per page */
        );

        return $this->render('default/compte/mes_annonces.html.twig', ['annonces' => $annonces]);
    }

    /**
     * @Route("/compte/delete-photo/{id}", name="photo_delete")
     */
    public function photoDelete(Request $request, $id) {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        $redirect = $request->headers->get('referer');
        $em = $this->getDoctrine()->getManager();
        $photo = $this->getDoctrine()
                        ->getRepository(Photos::class)->findOneById($id);

        if (!$photo) {
            throw $this->createNotFoundException('No guest found for id ' . $id);
        }

        $em->remove($photo);
        $em->flush();

        return $this->redirect($redirect);
    }

    /**
     * @Route("/favorite", name="favorite")
     */
    public function favoriteAction(Request $request) {
        $id = $request->request->get('id');

        $annonce = $this->getDoctrine()
                ->getRepository(Annonces::class)
                ->findOneById($id);

        $em = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $favorite = new Favorite();
        $favorite->setAnnonce($annonce);
        $favorite->setUser($user);
        $em->persist($favorite);

        $em->flush();
        return $this->json('succes');
    }

    /**
     * @Route("/compte/mon-profil.html", name="my_profile", methods={"GET", "POST"})
     */
    public function myProfil(Request $request) {

        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $data = $form->getData();
            if ($form->get('logo')->getData()) {

                $file = $form->get('logo')->getData();
                if (($file->guessExtension() == 'png') || ($file->guessExtension() == 'jpg') || ($file->guessExtension() == 'jpeg')) {
                    $fileName = md5(date('Y-m-d H:i:s:u')) . '.' . $file->guessExtension();

                    // moves the file to the directory where brochures are stored
                    $file->move($this->getParameter('logo_directory'), $fileName);

                    // updates the 'brochure' property to store the PDF file name
                    // instead of its contents

                    $data->setLogo($fileName);
                } else {
                    $data->setLogo('avatar.png');
                }
            } else {
                $data->setLogo('avatar.png');
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($data);
            $em->flush();
        }

        return $this->render('default/compte/profil.html.twig', ['form' => $form->createView(), 'setting' => $user]);
    }

    /**
     * @Route("/annonce/pdf/{id}", name="annonce_pdf", methods={"GET"})
     */
    public function pdfAnnonce($id)
    {
        $annonce = $this->getDoctrine()
            ->getRepository(Annonces::class)
            ->findOneById($id);
        if (!$annonce) {
            throw $this->createNotFoundException('Annonce non trouvée');
        }
        $photosEntities = $this->getDoctrine()
            ->getRepository(Photos::class)
            ->findByAnnonce($annonce);
        $photos = [];
        foreach ($photosEntities as $photo) {
            $photos[] = $this->getParameter('photo_directory_web') . '/263x175/' . $photo->getNom();
        }
        $pdfGenerator = $this->get('App\\Service\\PdfAnnonceGenerator');
        $pdfContent = $pdfGenerator->generate($annonce, $photos);

        return new \Symfony\Component\HttpFoundation\Response($pdfContent, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="annonce_' . $annonce->getId() . '.pdf"'
        ]);
    }
}
