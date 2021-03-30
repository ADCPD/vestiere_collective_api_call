<?php

namespace App\DataFixtures;

use App\Entity\Item;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ItemsFixtures
 * @package App\DataFixtures
 */
class ItemsFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->prepare($manager);
    }

    /**
     * Insert Item data if not exist
     *
     * @param ObjectManager $manager
     */
    private function prepare(ObjectManager $manager) {
        $items = $manager->getRepository('App:Item')->findAll();
        if (!$items) {
            $fakeItems = array (
                0 => array(
                        'picture' => 'http://placehold.it/128x128',
                        'name' => 'Accusage',
                        'priceCurrecy' => '',
                        'PriceAmount' => 6741.67707,
                    ),
                1 => array(
                        'picture' => 'http://placehold.it/128x128',
                        'name' => 'Opportech',
                        'priceCurrecy' => '',
                        'PriceAmount' => 7957.99453,
                    ),
                2 => array(
                        'picture' => 'http://placehold.it/128x128',
                        'name' => 'Cofine',
                        'priceCurrecy' => '',
                        'PriceAmount' => 7126.81868,
                    ),
                3 => array(
                        'picture' => 'http://placehold.it/128x128',
                        'name' => 'Senmao',
                        'priceCurrecy' => '',
                        'PriceAmount' => 2942.26552,
                    ),
                4 => array(
                        'picture' => 'http://placehold.it/128x128',
                        'name' => 'Turnling',
                        'priceCurrecy' => '',
                        'PriceAmount' => 538.36696,
                    ),
                5 => array(
                        'picture' => 'http://placehold.it/128x128',
                        'name' => 'Genmex',
                        'priceCurrecy' => '',
                        'PriceAmount' => 8699.5275,
                    ),
                6 => array(
                        'picture' => 'http://placehold.it/128x128',
                        'name' => 'Phormula',
                        'priceCurrecy' => '',
                        'PriceAmount' => 3190.71354,
                    ),
            );

            foreach ($fakeItems as $index => $value) {
                $item = new Item();
                $item->setName($value['name']);
                $item->setPriceAmount($value['PriceAmount']);
                $item->setPriceCurrency($value['PriceAmount']);
                $manager->persist($item);
            }

            $manager->flush();
        }
    }
}
