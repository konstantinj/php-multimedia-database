<?php

use Phalcon\Mvc\User\Component;

class AclHelper extends Component
{
    public function isAllowed($controller, $action)
    {
        $auth = $this->di->getShared('session')->get('auth');
        if (!$auth) {
            $role = 'Guests';
        } else {
            $role = 'Users';
        }
        $acl = $this->di->getShared('acl');

        return $acl->isAllowed($role, $controller, $action);
    }
}
