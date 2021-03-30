<?php

namespace App\DataFixtures;

use App\Entity\Seller;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class SellerFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->prepare($manager);
        $manager->flush();
    }

    /**
     * Insert new saller data if not exists
     * @param ObjectManager $manager
     */
    private function prepare(ObjectManager $manager) {
        $sellers = $manager->getRepository('App:Seller')->findAll();
        if (!$sellers) {
            $fakeCompany = [
                'Miboo', 'Eidel', 'Skinder'
            ];

            for ($i=0; $i<=count($fakeCompany)-1; $i++) {
                $sellers = new Seller();
                $sellers->setName($fakeCompany[$i]);
                $manager->persist($sellers);
            }
            $manager->flush();
        }

    }
}
