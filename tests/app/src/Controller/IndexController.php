<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace Controller;

use FastD\Http\JsonResponse;
use FastD\Http\ServerRequest;

/**
 * @SWG\Info(title="演示API", version="0.1")
 *
 * Class IndexController
 */
class IndexController
{
    /**
     * @param ServerRequest $request
     *
     * @return JsonResponse
     */
    public function welcome(ServerRequest $request)
    {
        return json([
            'foo' => $request->getParam('foo', 'bar'),
        ]);
    }

    /**
     * @param ServerRequest $request
     *
     * @return JsonResponse
     */
    public function sayHello(ServerRequest $request)
    {
        return json([
            'foo' => $request->getAttribute('name'),
        ]);
    }

    /**
     * @param ServerRequest $serverRequest
     *
     * @return JsonResponse
     */
    public function middleware(ServerRequest $serverRequest)
    {
        return json([
            'foo' => 'bar',
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function db()
    {
        return json(
            database()->info()
        );
    }

    public function model()
    {
        $model = model('demo');

        return json([
            'model' => get_class($model),
            'db' => $model->getDatabase()->info(),
            'list' => $model->select(),
        ]);
    }

    public function auth()
    {
        return json([
            'foo' => 'bar',
        ]);
    }
}
