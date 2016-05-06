<?php

namespace WelcomeBundle\Controllers;

use FastD\Framework\Bundle\Controllers\Controller;
use FastD\Http\Request;

/**
 * @Route("/welcomebundle")
 */
class Index extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('welcome.twig');
    }
}