<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/5/5
 * Time: 下午7:23
 * Github: https://www.github.com/janhuang
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 * WebSite: http://www.janhuang.me
 */

namespace WelcomeBundle\Controllers;

use FastD\Framework\Bundle\Controllers\Controller;
use FastD\Http\Request;
use FastD\Http\Response;

/**
 * Class Demo
 *
 * @Route("/demo")
 * @package WelcomeBundle\Controllers
 */
class Demo extends Controller
{
    /**
     * @Route("/route", name="demo")
     *
     * @return \FastD\Http\Response|string
     */
    public function indexAction()
    {
        return $this->render('base/route.twig');
    }

    /**
     * @Route("/control", name="control")
     *
     * @return \FastD\Http\Response|string
     */
    public function controlAction()
    {
        return $this->render('base/control.twig');
    }

    /**
     * @Route("/view", name="base.view")
     *
     * @return Response|string
     */
    public function viewAction()
    {
        return $this->render('base/view.twig', [
            'name' => 'janhuang'
        ]);
    }

    /**
     * @Route("/request", name="request")
     *
     * @param Request $request
     * @return Response
     */
    public function requestAction(Request $request)
    {
        if (!$request->hasSession('name')) {
            $request->setSession('name', 'jan');
        }

        $name = $request->getSession('name');

        if (!$request->hasCookie('age')) {
            $request->setCookie('age', 18);
        }

        $age = $request->getCookie('age');

        return $this->render('base/request.twig', [
            'name' => $name,
            'age' => $age,
        ]);
    }

    /**
     * @Route("/session", name="session.handler")
     *
     * @param Request $request
     * @return Response
     */
    public function sessionHandlerAction(Request $request)
    {
        $session = $request->getSessionHandle($this->getDefaultSessionStorage('session'));

        $session->set('name', 'janhuang'); // $request->setSession('name', 'janhuang');

        return $this->render('base/session.twig', [
            'session' => $session->get('name') // $request->getSession('name');
        ]);
    }

    /**
     * @Route("/config", name="config")
     *
     * @return \FastD\Http\Response|string
     */
    public function configAction()
    {
        $db = $this->getParameters('database');

        return $this->render('base/config.twig', [
            'config' => var_export($db, true),
            'path' => $this->getParameters('dynamic.path'),
            'name' => $this->getParameters('dynamic.custom'),
        ]);
    }
}