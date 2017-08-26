<?php
// src/Shm/UserBundle/Entity/Group.php

namespace Shm\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;
use Shm\UserBundle\Entity\User;

/**
 * @ORM\Entity
 * @ORM\Table(name="groups")
 */
class Group
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=10)
     * @Assert\NotBlank()
     * @Assert\Length(max=10)
     */
    protected $name;

    /**
     * @ORM\Column(type="string")
     * @Assert\NotBlank()
     */
    protected $roles;


    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="group")
     */
    protected $users;

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Group
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set rules
     *
     * @param integer $roles
     *
     * @return Group
     */
    public function setRoles($roles)
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Get rules
     *
     * @return string
     */
    public function getRoles()
    {
        return $this->roles;
//        return array($this->roles);
    }

    /**
     * Add user
     *
     * @param \Shm\UserBundle\Entity\User $user
     *
     * @return Group
     */
    public function addUser(\Shm\UserBundle\Entity\User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param \Shm\UserBundle\Entity\User $user
     */
    public function removeUser(\Shm\UserBundle\Entity\User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }
}
