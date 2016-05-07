<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 16/5/7
 * Time: 上午11:55
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

/**
 * Class System
 *
 * @Route("/system")
 *
 * @package WelcomeBundle\Controllers
 */
class System extends Controller
{
    /**
     * @Route("/services", name="system.services")
     *
     * @param Request $request
     * @return \FastD\Http\Response
     */
    public function servicesAction(Request $request)
    {
        $name = $this->get('name')->getName();

        $agent = $this->get('agent')->getAgent();

        return $this->render('system/services.twig', [
            'name' => $name,
            'agent' => $agent,
        ]);
    }
}