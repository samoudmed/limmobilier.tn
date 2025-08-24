<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;

/**
 * @Route("/admin/user")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/{page}", name="app_user_index", methods={"GET"}, requirements={"page"="\d+"})
     */
    public function index(EntityManagerInterface $entityManager, PaginatorInterface $paginator, $page = 1): Response
    {
        $usersAll = $entityManager
                ->getRepository(User::class)
                ->findBy(array(), array('id' => 'DESC'));

        $users = $paginator->paginate(
                $usersAll, /* query NOT result */
                $page, /* page number */
                50 /* limit per page */
        );

        return $this->render('admin/user/index.html.twig', [
                    'users' => $users,
                    'usersAll' => $usersAll
        ]);
    }

    /**
     * @Route("/new", name="app_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/details/{id}", name="app_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/modifier/{id}", name="app_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userRepository->add($user, true);

            return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/validate", name="app_user_validate", methods={"POST"})
     */
    public function validate(Request $request, EntityManagerInterface $entityManager, MailerInterface $mailer): Response {

        $user = $entityManager
                ->getRepository(User::class)
                ->findOneById($request->request->get('id'));

        $statut = filter_var($request->request->get('statut'), FILTER_VALIDATE_BOOLEAN);
        $user->setActive($statut);
        $user->setIsVerified($statut);
        $entityManager->persist($user);
        $entityManager->flush();
        

        if ($statut == true) {
            $email = (new TemplatedEmail())
                    ->from('contact@limmobilier.tn')
                    ->to($user->getEmail())
                    ->subject('Votre compte est approuvée')
                    // path of the Twig template to render
                    ->htmlTemplate('emails/compte_approuvee.html.twig')

                    // pass variables (name => value) to the template
                    ->context([
                        'nom' => $user
                    ]);

            $mailer->send($email);
        }

        return new Response('ok');
    }
    
    /**
     * @Route("/{id}", name="app_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
