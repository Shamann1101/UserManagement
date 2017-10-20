<?php
//src/Shm/UserBundle/DataFixtures/ORM/UsersFixtures.php

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Shm\UserBundle\Entity\User;
use Shm\UserBundle\Entity\Group;

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
        $em = $this->container->get('doctrine')->getEntityManager('default');
        $groups = $em->getRepository('ShmUserBundle:Group');

        $admin = new User();
        $admin->setEnabled(true);
        $admin->setFirstName("admin");
        $admin->setLastName("admin");
        $admin->setEmail("admin@test.com");
        $admin->setUsername("admin");
        $password = $encoder->encodePassword($admin, 'admin');
        $admin->setPassword($password);
        $admin->setGroup($manager->merge($this->getReference("group-0")));
        $admin->addRole($groups->findOneById($admin->getGroup())->getRoles());
        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user[$i] = new User();
            $user[$i]->setEnabled(true);
            $user[$i]->setFirstName("test".$i);
            $user[$i]->setLastName("test".$i);
            $user[$i]->setEmail("test".$i."@test.com");
            $user[$i]->setUsername("test".$i);
            $password = $encoder->encodePassword($user[$i], 'test');
            $user[$i]->setPassword($password);
            $user[$i]->setGroup($manager->merge($this->getReference("group-".random_int(1, 2))));
            $user[$i]->addRole($groups->findOneById($user[$i]->getGroup())->getRoles());
            $manager->persist($user[$i]);
        }

        $manager->flush();
    }

    public function getOrder()
    {
        return 2;
    }
}