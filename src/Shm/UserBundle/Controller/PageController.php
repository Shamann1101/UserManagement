<?php

namespace Shm\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PageController extends Controller
{
    public function indexAction()
    {
        return $this->render('ShmUserBundle:Page:index.html.twig');
    }
}
