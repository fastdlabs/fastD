<?php

namespace AdminBundle\Controllers;

use Dobee\Framework\Controller\Controller;

class IndexController extends Controller
{
    /**
     * @Route("/", name="adminbundle_index")
     */
    public function indexAction()
    {
        return 'hello world';
    }
}