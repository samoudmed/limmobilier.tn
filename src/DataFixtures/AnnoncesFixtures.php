<?php

namespace App\DataFixtures;

use App\Entity\Annonces;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AnnoncesFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i=0; $i<2000; $i++) {
            $annonces = new Annonces();
            $annonces->setLabel('Annonces '.$i);
            $annonces->setPrix(rand(100, 9999999));
            $annonces->setOffre('Location');
            $annonces->setDescription('Ok, I guess it *does* have a price');
            $manager->persist($annonces);

            // add more products

            $manager->flush();
        }
        
    }
}
