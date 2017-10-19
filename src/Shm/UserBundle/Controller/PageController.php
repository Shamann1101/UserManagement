<?php

namespace Shm\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Shm\UserBundle\Entity\Group;
use Shm\UserBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class PageController extends Controller
{

    public function indexAction()
    {
        return $this->render('ShmUserBundle:Page:index.html.twig');
    }

    public function adminAction()
    {
        return new Response('<html><body>Admin page!</body></html>');
    }

    public function loginAction(Request $request, AuthenticationUtils $authUtils)
    {
        // get the login error if there is one
        $error = $authUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authUtils->getLastUsername();

        if ($error) {
            $this->get('session')->getFlashBag()->add(
                'LastAuthenticationError',
                $error
            );
/*
            \Doctrine\Common\Util\Debug::dump($user = $this->getDoctrine()->getManager()->find('ShmUserBundle:User', 1));
            echo '<br><br>';
            \Doctrine\Common\Util\Debug::dump($group = $this->getDoctrine()->getManager()->find('ShmUserBundle:Group', 1));
            echo '<br><br>';
            print_r($user->getRoles());
            echo '<br><br>';
            echo $group->getRoles();

            die();
*/
        }

        return $this->render('@ShmUser/Page/login.html.twig', array(
            'last_username' => $lastUsername,
            'error'         => $error,
        ));
    }

    /**
     * @param $param string|array
     * @param $type string
     * @return array|null
     */
    public function sortPermission ($param, $type) {

        $accessed_types = ["group", "user"];

        if (!in_array($type, $accessed_types)) {

            return null;

        }

        if (!is_array($param) && !is_string($param)) {

            return null;

        } elseif (is_string($param)) {

            $param = str_split($param, strlen($param));

        }

        $accessed_sorts = [
            "direction" => ["ASC", "DESC"],
            "group" => ["id", "name", "roles"],
            "user" => ["id", "username", "lastName", "firstName", "email", "enabled", "dateCreate", "group"],
        ];

        $accessed_permission = ["direction"];

        $sort = [
            "sort" => "id",
            "direction" => "ASC"
        ];

        foreach ($accessed_types as $accessed_type) {
            if ($type != $accessed_type) {
                continue;
            }

            $accessed_permission[] = $type;

            foreach ($param as $key => $value) {
                foreach ($accessed_permission as $item) {
                    if (in_array($value, $accessed_sorts[$item])) {
                        if ($item == "direction") {
                            $sort["direction"] = $value;
                        } else {
                            $sort["sort"] = $value;
                        }
                    }
                }
            }

        }

        return $sort;

    }
}
