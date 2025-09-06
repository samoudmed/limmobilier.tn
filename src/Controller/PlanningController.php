<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Entity\Annonces;
use App\Entity\User;
use App\Entity\Sender;
use App\Entity\Message;
use App\Form\MessageType;
use App\Form\ReponsesType;
use Symfony\Component\Finder\SplFileInfo;
use App\Repository\VillesRepository;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Finder\Finder;
use Cocur\Slugify\Slugify;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Description of PlanningController
 *
 * @author Guillaume
 */
class PlanningController extends AbstractController {

    /**
     * @Route("/compte/mon-planning.html", name="my_planning", methods={"GET"})
     */
    public function myPlanning(Request $request) {

        

        return $this->renderForm('default/compte/mon-planning.html.twig');
    }

}
