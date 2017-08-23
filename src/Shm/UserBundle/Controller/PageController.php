<?php

namespace Shm\UserBundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

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
            "user" => ["id", "last_name", "first_name", "email", "state", "date_create", "group"],
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
