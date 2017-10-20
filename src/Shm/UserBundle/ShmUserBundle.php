<?php

namespace Shm\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class ShmUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
