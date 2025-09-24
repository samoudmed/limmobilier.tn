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
 * @Route("/admin/dashboard")
 */
class DashboardController extends AbstractController {

    /**
     * @Route("/", name="app_dashborad", methods={"GET", "POST"}, requirements={"page"="\d+"})
     */
    public function index(Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator, $page = 1): Response {

        $annoncesRepository = $this->getDoctrine()->getRepository(Annonces::class);

        $countAdsCreatedThisMonth = $annoncesRepository->countAdsCreatedThisMonth();
        $countAdsCreatedLastMonth = $annoncesRepository->countAdsCreatedLastMonth();
        $countAdsCreatedToday = $annoncesRepository->countAdsCreatedToday();
        $allAnnoncesCount = $this->getDoctrine() ->getRepository(Annonces::class) ->findAllAds();
        $annonces = $annoncesRepository->findAnnonceOfDay();

        $newSubscribers = $this->getDoctrine()
                ->getRepository(User::class)
                ->countNewSubscribersThisMonth(); // example for subscribers

        return $this->render('admin/dashboard/dashboard.html.twig', [
                'countAdsCreatedThisMonth' => $countAdsCreatedThisMonth,
                'countAdsCreatedToday' => $countAdsCreatedToday,
                'allAnnoncesCount' => $allAnnoncesCount,
                'annonces' => $annonces,
                'newSubscribers' => $newSubscribers,
                'countAdsCreatedLastMonth' => $countAdsCreatedLastMonth
        ]);
    }
}