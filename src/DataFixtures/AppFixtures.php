<?php

namespace App\DataFixtures;

use App\Entity\Customer;
use App\Entity\Invoice;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');

        $admin = new User;
        $admin->firstName = $faker->firstName;
        $admin->lastName = $faker->lastName;
        $admin->email = "admin@mail.fr";
        $admin->password = "password";
        $admin->roles = ["ROLE_ADMIN", "ROLE_COMPTABLE"];

        $manager->persist($admin);

        $moderator = new User;
        $moderator->firstName = $faker->firstName;
        $moderator->lastName = $faker->lastName;
        $moderator->email = "moderator@mail.fr";
        $moderator->password = "password";
        $moderator->roles = ["ROLE_MODERATOR"];

        $manager->persist($moderator);

        for ($u = 0; $u < 5; $u++) {
            $user = new User;

            $user->firstName = $faker->firstName();
            $user->lastName = $faker->lastName();
            $user->email = "user$u@mail.fr";
            $user->password = "password";

            $manager->persist($user);

            for ($i = 0; $i < mt_rand(5, 10); $i++) {
                $customer = new Customer;

                $customer->firstName = $faker->firstName();
                $customer->lastName = $faker->lastName();
                $customer->email = $faker->email();
                $customer->user = $user;

                $manager->persist($customer);

                for ($j = 0; $j < mt_rand(3, 5); $j++) {
                    $invoice = new Invoice;

                    $invoice->amount = $faker->numberBetween(10000, 100000);
                    $invoice->createdAt = $faker->dateTimeBetween('-6 months');
                    $invoice->customer = $customer;

                    $manager->persist($invoice);
                }
            }
        }

        $manager->flush();
    }
}
