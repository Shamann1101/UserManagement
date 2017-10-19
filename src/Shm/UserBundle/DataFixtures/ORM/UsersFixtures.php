<?php
//src/Shm/UserBundle/DataFixtures/ORM/UsersFixtures.php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shm\UserBundle\Entity\User;
use Shm\UserBundle\Entity\Group;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UsersFixtures extends AbstractFixture implements FixtureInterface, ContainerAwareInterface
{
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $encoder = $this->container->get('security.password_encoder');

        for ($i = 0; $i < 10; $i++) {
            $user[$i] = new User();
            $user[$i]->setEnabled(true);
            $user[$i]->setFirstName("test".$i);
            $user[$i]->setLastName("test".$i);
            $user[$i]->setEmail("test".$i."@test.com");
            $user[$i]->setUsername("test".$i);
            $password = $encoder->encodePassword($user[$i], 'test');
            $user[$i]->setPassword($password);
            $user[$i]->setGroup($manager->merge($this->getReference("group-".($i % 3))));
            $manager->persist($user[$i]);

        }
/*
        $user1 = new User();
        $user1->setFirstName("Admin");
        $user1->setLastName("Admin");
        $user1->setEmail("mail@mail.com");
        $user1->setState(true);
        $user1->setGroup($manager->merge($this->getReference("group-0")));
        $manager->persist($user1);

        $user2 = new User();
        $user2->setFirstName("John");
        $user2->setLastName("Malkovich");
        $user2->setEmail("john@malkovich.com");
        $user2->setState(true);
        $user2->setGroup($manager->merge($this->getReference("group-1")));
        $manager->persist($user2);
*/
        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}