<?php
//src/Shm/UserBundle/DataFixtures/ORM/UsersFixtures.php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shm\UserBundle\Entity\User;
use Shm\UserBundle\Entity\Group;

class UsersFixtures extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $user1 = new User();
        $user1->setFirstName("Admin");
        $user1->setLastName("Admin");
        $user1->setEmail("mail@mail.com");
        $user1->setState(true);
        $user1->setGroup($manager->merge($this->getReference("group-1")));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFirstName("John");
        $user2->setLastName("Malkovich");
        $user2->setEmail("john@malkovich.com");
        $user2->setState(true);
        $user1->setGroup($manager->merge($this->getReference("group-2")));
        $manager->persist($user2);

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}