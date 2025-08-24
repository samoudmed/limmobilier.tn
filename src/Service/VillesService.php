<?php

// src/AppBundle/Service/FileUploader.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Villes;

class VillesService {

    private $em;

    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function list() {

        $villes =  $this->em->getDoctrine()
                ->getRepository(Villes::class)
                ->findBy([], ['label' => 'asc']);

        return $villes;
    }
}
