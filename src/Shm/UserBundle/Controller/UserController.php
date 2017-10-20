<?php

namespace Shm\UserBundle\Controller;

use Shm\UserBundle\Entity\Group;
use Shm\UserBundle\Entity\User;
use Shm\UserBundle\Form\UserType;
use Shm\UserBundle\Form\UserEditType;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use FOS\UserBundle\Controller\RegistrationController as BaseController;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\FOSUserEvents;

/**
 * User controller.
 *
 */
class UserController extends BaseController
{
    /**
     * Lists all user entities.
     *
     */
    public function indexAction(Request $request)
    {
        $pc = new PageController();
        $sort = $pc->sortPermission(array(
            $request->query->get("sort"),
            $request->query->get("direction")
        ), "user");

        $currentPage = (int)$request->query->get("cur") ?: 1;
        $maxResults = (int)$request->query->get("max") ?: 10;
        $firstResult = ($currentPage - 1) * $maxResults;

        $em = $this->getDoctrine()->getManager();

        $count = $em->createQueryBuilder()
            ->select("u.id")
            ->from("ShmUserBundle:User", "u")
            ->getQuery()
            ->getResult();

        $users = $em->createQueryBuilder()
            ->select("u")
            ->from("ShmUserBundle:User", "u")
            ->addOrderBy("u.".$sort["sort"], $sort["direction"])
            ->setFirstResult($firstResult)
            ->setMaxResults($maxResults)
            ->getQuery()
            ->getResult();

        $navBar = array(
            "activate" => false,
        );

        if (($count = count($count)) > $maxResults) {
            $navBar["activate"] = true;
            $navBar["current"] = $currentPage;
            $navBar["start"] = 1;
            $navBar["end"] = ($count % $maxResults) ? floor($count / $maxResults) + 1 : ($count / $maxResults);
            $navBar["prev"] = (($currentPage - 1) > 0) ?($currentPage - 1): 1;
            $navBar["next"] = (($currentPage + 1) < $navBar["end"]) ?($currentPage + 1): $navBar["end"];
        }

        return $this->render('ShmUserBundle:User:index.html.twig', array(
            'users' => $users,
            'query' => array(
                'max' => $maxResults,
                'sort' => $sort["sort"],
                'direction' => $sort["direction"],
            ),
            'navBar' => $navBar,
        ));
    }

    /**
     * Creates a new user entity.
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function newAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();
        $form = $this->createForm('Shm\UserBundle\Form\UserType', $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $password = $passwordEncoder->encodePassword($user, $user->getPlainPassword());
            $user->setPassword($password);
            $groups = $this->getDoctrine()->getRepository(Group::class);
            $user->addRole($groups->findOneById($user->getGroup())->getRoles());
            $user->setEnabled(true);
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return $this->redirectToRoute('users_show', array('id' => $user->getId()));
        }

        return $this->render('ShmUserBundle:User:new.html.twig', array(
            'user' => $user,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a user entity.
     *
     */
    public function showAction(User $user)
    {
        $deleteForm = $this->createDeleteForm($user);

        return $this->render('ShmUserBundle:User:show.html.twig', array(
            'user' => $user,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing user entity.
     *
     */
    public function editAction(Request $request, User $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('Shm\UserBundle\Form\UserEditType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_show', array('id' => $user->getId()));
        }

        return $this->render('ShmUserBundle:User:edit.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     */
    public function deleteAction(Request $request, User $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('users_index');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(User $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('users_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
