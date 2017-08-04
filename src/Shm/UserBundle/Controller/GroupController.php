<?php

namespace Shm\UserBundle\Controller;

use Shm\UserBundle\Entity\Group;
use Shm\UserBundle\Form\GroupType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class GroupController extends Controller
{
    /**
     * Show a Group Entity
     *
     * @param $id
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function editAction($id = null)
    {
        $enquiry = new Group();

        $form = $this->createForm(GroupType::class, $enquiry);

        $em = $this->getDoctrine()->getManager();

        $group = $em->getRepository("ShmUserBundle:Group")->find($id);

        if (!$group) {
            throw $this->createNotFoundException("Unable to find Group");
        }

        return $this->render("edit.html.twig", array(
            "group" => $group,
        ));
    }

    /**
     * Show a Group Entity
     */
    public function newAction()
    {
        $enquiry = new Group();

        $form = $this->createForm(GroupType::class, $enquiry);

        return $this->render("@ShmUser/Group/new.html.twig", array(
            "form" => $form->createView(),
        ));
    }
}
