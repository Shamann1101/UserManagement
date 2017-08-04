<?php
//src/Shm/UserBundle/DataFixtures/ORM/GroupsFixtures.php

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shm\UserBundle\Entity\Group;

class GroupsFixtures implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $group1 = new Group();
        $group1->setName("Admins");
        $group1->setRules(777);
        $manager->persist($group1);

        $group2 = new Group();
        $group2->setName("Users");
        $group2->setRules(000);
        $manager->persist($group2);

        $manager->flush();
    }
}