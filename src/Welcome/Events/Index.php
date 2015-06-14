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

use Dobee\Protocol\Http\JsonResponse;
use Dobee\Protocol\Http\Request;
use Kernel\Events\EventAbstract;

/**
 * Class IndexController
 *
 * @package Welcome\Controllers
 */
class Index extends EventAbstract
{
    public function oneAction(Request $request)
    {
        return new JsonResponse($request->header->all());
    }

    public function twoAction(Request $request)
    {
        return $request->createRequest('http://www.baidu.com')->get();
    }

    public function uploadAction(Request $request)
    {
        $files = $request->getUploader([
            'save.path' => $this->get('kernel')->getRootPath().'/storage/cache',
            'max.size' => '10M',
        ])->uploading()->getUploadFiles();

        $uploadFiles = [];

        foreach ($files as $file) {
            $uploadFiles[] = [$file->getOriginalName()];
        }

        unset($files);

        return new JsonResponse($uploadFiles);
    }
}