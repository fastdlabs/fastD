<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace Controller;

use Exception;
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Http\ServerRequest;

/**
 * Class IndexController.
 */
class IndexController
{
    /**
     * @param ServerRequest $request
     *
     * @return Response
     * @throws Exception
     */
    public function welcome(ServerRequest $request)
    {
        abort(404);
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

    /**
     * @return Response
     */
    public function model()
    {
        $model = model('demo');

        return json([
            'model' => get_class($model),
            'db' => $model->getDatabase()->info(),
            'list' => $model->select(),
        ]);
    }

    /**
     * @return Response
     */
    public function auth()
    {
        return json([
            'foo' => 'bar',
        ]);
    }

    /**
     * @throws Exception
     */
    public function abort()
    {
        abort(400);
    }

    /**
     * @return Response
     */
    public function queue()
    {
        queue()->push('demo queue');

        return json([
            'msg' => 'hello queue',
        ]);
    }
}

