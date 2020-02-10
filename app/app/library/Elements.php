<?php

use Phalcon\Mvc\User\Component;

class Elements extends Component
{
    protected $menu = [
        'index'  => [
            'caption' => 'Home',
            'action'  => 'index'
        ],
        'media'  => [
            'caption' => 'Media',
            'action'  => 'index'
        ],
        'actors' => [
            'caption' => 'Actors',
            'action'  => 'index'
        ],
    ];
    protected $loginLink = [
        'controller' => 'session',
        'caption'    => 'Log In/Sign Up',
        'action'     => 'index'
    ];

    public function getMenu()
    {
        $auth = $this->session->get('auth');
        if ($auth) {
            $this->loginLink['caption'] = 'Log Out';
            $this->loginLink['action'] = 'end';
        }

        $controllerName = $this->view->getControllerName();
        echo '<ul class="navbar-nav mr-auto">';
        foreach ($this->menu as $controller => $option) {
            if ($controllerName == $controller) {
                echo '<li class="nav-item active">';
            } else {
                echo '<li class="nav-item">';
            }

            echo $this->tag->linkTo(
                [
                    'action' => $controller . '/' . $option['action'],
                    'class'  => 'nav-link',
                    'text'   => $option['caption']
                ]
            );
            echo '</li>';
        }
        echo '</ul>';
        echo '<ul class="navbar-nav form-inline my-2 my-md-0">';
        echo '<li class="nav-item">';
        echo $this->tag->linkTo(
            [
                'action' => $this->loginLink['controller'] . '/' . $this->loginLink['action'],
                'class'  => 'nav-link',
                'text'   => $this->loginLink['caption']
            ]
        );
        echo '</li>';
        echo '</ul>';
    }
}