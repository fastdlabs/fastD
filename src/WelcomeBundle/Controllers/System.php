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
use FastD\Http\Response;

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

    /**
     * @Route("/session", name="base.session")
     *
     * @param Request $request
     * @return Response
     */
    public function sessionAction(Request $request)
    {
        if (!$request->hasSession('name')) {
            $request->setSession('name', 'jan');
        }

        $name = $request->getSession('name');

        if (!$request->hasCookie('age')) {
            $request->setCookie('age', 18);
        }

        $age = $request->getCookie('age');

        return $this->render('system/session.twig', [
            'name' => $name,
            'age' => $age,
        ]);
    }

    /**
     * @Route("/upload", name="system.upload")
     *
     * @param Request $request
     * @return Response
     */
    public function uploadAction(Request $request)
    {
        $uploader = $request->getUploader();
        $files = [];
        if ($uploader->uploadTo($this->get('kernel')->getRootPath() . '/storage/files')) {
            $files = $uploader->getUploadedFiles();
        }

        return $this->render('system/files.twig', [
            'files' => $files
        ]);
    }
}