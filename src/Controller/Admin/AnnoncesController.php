<?php

namespace App\Controller\Admin;

use App\Entity\Annonces;
use App\Form\AnnoncesType;
use App\Repository\AnnoncesRepository;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpKernel\Profiler\Profiler;

/**
 * @Route("/admin/annonces")
 */
class AnnoncesController extends AbstractController {

    /**
     * @Route("/{page}", name="app_annonces_index", methods={"GET", "POST"}, requirements={"page"="\d+"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, $page = 1): Response {

        $filters = array();
        if ($request->getMethod() == 'POST') {

            $filters['statut'] =  $request->request->get('statut');
            $annoncesAll = $entityManager
                    ->getRepository(Annonces::class)
                    ->filter($filters);
        } else {
            $annoncesAll = $entityManager
                    ->getRepository(Annonces::class)
                    ->findAll();
        }

        $annonces = $paginator->paginate(
                $annoncesAll, /* query NOT result */
                $page, /* page number */
                50 /* limit per page */
        );

        return $this->render('admin/annonces/index.html.twig', [
                    'annonces' => $annonces,
                    'annoncesAll' => $annoncesAll
        ]);
    }

    /**
     * @Route("/user/{id}/{page}", name="app_user_annonces", methods={"GET"}, requirements={"page"="\d+"})
     */
    public function userAnnonces(EntityManagerInterface $entityManager, PaginatorInterface $paginator, $id, $page = 1): Response {

        $user = $entityManager
                ->getRepository(User::class)
                ->findById($id);
        
        $annoncesAll = $entityManager
                ->getRepository(Annonces::class)
                ->findByUser($user);

        $annonces = $paginator->paginate(
                $annoncesAll, /* query NOT result */
                $page, /* page number */
                50 /* limit per page */
        );
        
        return $this->render('admin/annonces/index.html.twig', [
                    'annonces' => $annonces,
                    'annoncesAll' => $annoncesAll
        ]);
    }

    /**
     * @Route("/details/{id}", name="app_annonces_show", methods={"GET"})
     */
    public function show(Annonces $annonce): Response {
        
        return $this->render('admin/annonces/show.html.twig', [
                    'annonce' => $annonce,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_annonces_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response {
        
        $form = $this->createForm(AnnoncesType::class, $annonce);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            // return $this->redirectToRoute('app_annonces_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/annonces/edit.html.twig', [
                    'annonce' => $annonce,
                    'form' => $form,
        ]);
    }

    /**
     * @Route("/validate", name="app_annonces_validate", methods={"POST"})
     */
    public function validate(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {

        $annonce = $entityManager
                ->getRepository(Annonces::class)
                ->findOneById($request->request->get('id'));

        $statut = filter_var($request->request->get('statut'), FILTER_VALIDATE_BOOLEAN);
        $annonce->setStatut($statut);
        $entityManager->persist($annonce);
        $entityManager->flush();
        

        if ($statut == true) {
            $email = (new TemplatedEmail())
                    ->from('contact@limmobilier.tn')
                    ->to($annonce->getUser()->getEmail())
                    ->subject('Votre annonce est approuvée')
                    // path of the Twig template to render
                    ->htmlTemplate('emails/annonce_publier.html.twig')

                    // pass variables (name => value) to the template
                    ->context([
                'nom' => $annonce->getUser(),
                'annonce' => $annonce,
            ]);

            $mailer->send($email);
        }

        return new Response('ok');
    }
    
    /**
     * @Route("/refus", name="app_annonces_refus", methods={"POST"})
     */
    public function refus(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {

        $annonce = $entityManager
                ->getRepository(Annonces::class)
                ->findOneById($request->request->get('id'));

        $email = (new TemplatedEmail())
                ->from('contact@limmobilier.tn')
                ->to($annonce->getUser()->getEmail())
                ->subject('Votre annonce n\'a pas été acceptée')
                // path of the Twig template to render
                ->htmlTemplate('emails/refus.html.twig')

                // pass variables (name => value) to the template
                ->context([
            'nom' => $annonce->getUser(),
            'annonce' => $annonce,
        ]);

        $mailer->send($email);

        return new Response('ok');
    }

    /**
     * @Route("/{id}", name="app_annonces_delete", methods={"POST"})
     */
    public function delete(Request $request, Annonces $annonce, EntityManagerInterface $entityManager): Response {
        
        if ($this->isCsrfTokenValid('delete' . $annonce->getId(), $request->request->get('_token'))) {
            $entityManager->remove($annonce);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_annonces_index', [], Response::HTTP_SEE_OTHER);
    }

}
