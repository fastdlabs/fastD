<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/14
 * Time: 下午3:23
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Welcome\Events;

use FastD\Debug\Exceptions\RedirectException;
use FastD\Protocol\Http\JsonResponse;
use FastD\Protocol\Http\Request;
use Kernel\Events\RestEvent;
use Kernel\Events\TemplateEvent;

/**
 * Class Index
 *
 * @package Welcome\Events
 */
class Index extends RestEvent
{
    public function welcomeAction(Request $request)
    {
        echo $request->getRootPath();
        return $this->responseJson(['a' => 'janhuang']);
    }

    public function viewAction()
    {
        return 'demo';
    }

    public function diAction(Request $request)
    {
        return new JsonResponse($request->query->all());
    }

    public function dbAction()
    {
        $read = $this->getConnection('read');

        return $read->getConnectionInfo();
    }

    public function oneAction(Request $request)
    {
        return new JsonResponse($request->header->all());
    }

    public function twoAction(Request $request)
    {
        return $request->createRequest($this->generateUrl('/one'))->delete();
    }

    public function uploadAction(Request $request)
    {
        $files = $request
            ->getUploader([
                'save.path' => $this->get('kernel')->getRootPath().'/storage/cache',
                'max.size' => '10M',
            ])
            ->uploading()
            ->getUploadFiles();

        return new JsonResponse($files);
    }
}