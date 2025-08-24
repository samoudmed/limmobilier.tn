<?php

// src/AppBundle/Service/FileUploader.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Photos;

class ManagePhoto {

    private $em;
    
    public function __construct(EntityManagerInterface $em) {
        $this->em = $em;
    }

    public function getFeaturedPhoto($annonces) {

        foreach ($annonces as $k => $annonce) {
            $photo = $this->em->getRepository(Photos::class)
                    ->findOneBy(array('annonce' => $annonce, 'featured' => 1));
            if(!$photo) {
                $photos = $this->em->getRepository(Photos::class)
                    ->findBy(array('annonce' => $annonce));
                if($photos) {
                    $photo = $photos[0];
                }
                
            }
            
            $annonces[$k]->photo = (isset($photo)) ? $photo->getNom() : 'default-img.png';
        }

        return $annonces;
    }

}
