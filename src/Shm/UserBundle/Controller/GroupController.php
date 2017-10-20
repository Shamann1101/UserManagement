<?php

namespace Shm\UserBundle\Controller;

use Shm\UserBundle\Entity\Group;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Group controller.
 *
 */
class GroupController extends Controller
{
    /**
     * Lists all group entities.
     *
     */
    public function indexAction(Request $request)
    {
        $pc = new PageController();
        $sort = $pc->sortPermission(array(
            $request->query->get("sort"),
            $request->query->get("direction")
        ), "group");

        $em = $this->getDoctrine()->getManager();

        $groups = $em->createQueryBuilder()
            ->select("g")
            ->from("ShmUserBundle:Group", "g")
            ->addOrderBy("g.".$sort["sort"], $sort["direction"])
            ->getQuery()
            ->getResult();

        return $this->render('ShmUserBundle:Group:index.html.twig', array(
            'groups' => $groups,
        ));
    }

    /**
     * Creates a new group entity.
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request)
    {
        $group = new Group();
        $form = $this->createForm('Shm\UserBundle\Form\GroupType', $group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($group);
            $em->flush();

            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));
        }

        return $this->render('ShmUserBundle:Group:new.html.twig', array(
            'group' => $group,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a group entity.
     *
     */
    public function showAction(Group $group)
    {
        $deleteForm = $this->createDeleteForm($group);

        return $this->render('ShmUserBundle:Group:show.html.twig', array(
            'group' => $group,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing group entity.
     *
     */
    public function editAction(Request $request, Group $group)
    {
        $deleteForm = $this->createDeleteForm($group);
        $editForm = $this->createForm('Shm\UserBundle\Form\GroupType', $group);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));
        }

        return $this->render('ShmUserBundle:Group:edit.html.twig', array(
            'group' => $group,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a group entity.
     *
     */
    public function deleteAction(Request $request, Group $group)
    {
        if (count($group->getUsers())) {

            $this->get('session')->getFlashBag()->add(
                'non-empty-notice',
                'Can not delete a non-empty group!'
            );

            return $this->redirectToRoute('groups_show', array('id' => $group->getId()));
        }

        $form = $this->createDeleteForm($group);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($group);
            $em->flush();
        }

        return $this->redirectToRoute('groups_index');
    }

    /**
     * Creates a form to delete a group entity.
     *
     * @param Group $group The group entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Group $group)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('groups_delete', array('id' => $group->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
