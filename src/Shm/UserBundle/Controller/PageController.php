<?php

namespace Shm\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('ShmUserBundle:Page:index.html.twig');
    }

    public function usersAction()
    {
        return $this->render('ShmUserBundle:Page:users.html.twig');
    }

    public function groupsAction()
    {
        return $this->render('ShmUserBundle:Page:groups.html.twig');
    }
}
