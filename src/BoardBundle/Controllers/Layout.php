<?php

namespace BoardBundle\Controllers;

use BoardBundle\Controllers\Auth\AuthController;
use FastD\Framework\Bundle\Controllers\Controller;

class Layout extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('index.twig');
    }

    /**
     * @Route("/header", name="board_header")
     */
    public function headerAction()
    {
        return $this->render('components/header.html');
    }

    /**
     * @Route("/nav", name="board_nav")
     */
    public function navAction()
    {
        return $this->render('components/nav.html');
    }
}