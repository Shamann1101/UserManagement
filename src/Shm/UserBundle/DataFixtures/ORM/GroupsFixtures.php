<?php
//src/Shm/UserBundle/DataFixtures/ORM/GroupsFixtures.php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Shm\UserBundle\Entity\Group;

class GroupsFixtures extends AbstractFixture implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $group1 = new Group();
        $group1->setName("Admins");
        $group1->setRoles("ROLE_ADMIN");
        $manager->persist($group1);

        $group2 = new Group();
        $group2->setName("Users");
        $group2->setRoles("ROLE_USER");
        $manager->persist($group2);

        $manager->flush();

        $this->addReference('group-0', $group1);
        $this->addReference('group-1', $group2);
    }

    public function getOrder()
    {
        return 1;
    }
}
