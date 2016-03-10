<?php

namespace BoardBundle\Controllers;

use BoardBundle\Controllers\Auth\AuthController;

/**
 * Class Layout
 *
 * @Route("/board")
 * @package BoardBundle\Controllers
 */
class Layout extends AuthController
{
    /**
     * @Route("/", name="board_index")
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
        return $this->render('components/header.twig');
    }

    /**
     * @Route("/nav", name="board_nav")
     */
    public function navAction()
    {
        return $this->render('components/nav.twig');
    }
}