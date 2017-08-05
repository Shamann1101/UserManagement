<?php
//src/Shm/UserBundle/DataFixtures/ORM/UsersFixtures.php

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shm\UserBundle\Entity\User;

class UsersFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setFirstName("Admin");
        $user1->setLastName("Admin");
        $user1->setEmail("mail@mail.com");
        $user1->setState(true);
//        $user1->setGroup($manager->merge($this->));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFirstName("John");
        $user2->setLastName("Malkovich");
        $user2->setEmail("john@malkovich.com");
        $user2->setState(true);
        $manager->persist($user2);

        $manager->flush();
    }
}