<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

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
