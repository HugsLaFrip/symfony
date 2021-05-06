<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        for ($i = 0; $i < 20; $i++) {
            $customer = new Customer;

            $customer->firstName = "Prénom" . $i;
            $customer->lastName = "Nom" . $i;
            $customer->email = "customer$i@mail.fr";

            $manager->persist($customer);
        }

        $manager->flush();
    }
}
