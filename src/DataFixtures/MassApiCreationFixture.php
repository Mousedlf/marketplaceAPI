<?php

namespace App\DataFixtures;

use App\Entity\API;
use App\Entity\Order;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class MassApiCreationFixture extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $apiRepository = $manager->getRepository(API::class);
        $faker = Factory::create();
        $user = new User();
        $user->setCreatedAt(new \DateTime());
        $user->setEmail($faker->email());
        $user->setPassword($faker->password());
        $manager->persist($user);
        $manager->flush();

        for ($i = 0; $i < 20; $i++) {
            $api = new API();
            $api->setCreatedBy($user);
            $api->setName($faker->name);
            $api->setDescription($faker->text);
            $manager->persist($api);
        }
        $manager->flush();


        for ($i = 0; $i < 10; $i++) {
            $order = new Order();
            $order->setTotal($faker->randomFloat());
            $order->setByUser($user);
            $order->addAPI($apiRepository->getRandomApi());
            $manager->persist($order);
        }

        $manager->flush();
    }
}
