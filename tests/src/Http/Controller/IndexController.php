<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Http\Controller;

use FastD\Http\JsonResponse;
use FastD\Http\ServerRequest;
use FastD\Middleware\Delegate;

/**
 *
 * @SWG\Info(title="My First API", version="0.1")
 *
 * Class IndexController
 * @package Http\Controller
 */
class IndexController
{
    /**
     *
     * @SWG\Get(
     *     path="/api/resource.json",
     *     @SWG\Response(response="200", description="An example resource")
     * )
     *
     * @param ServerRequest $request
     * @return \FastD\Http\JsonResponse
     */
    public function welcome(ServerRequest $request)
    {
        return json([
            'foo' => 'bar'
        ]);
    }

    /**
     * @param ServerRequest $request
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
     * @return JsonResponse
     */
    public function middleware(ServerRequest $serverRequest)
    {
        return json([
            'foo' => 'bar'
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
            'db' => $model->getDatabase()->info()
        ]);
    }

    public function auth()
    {
        return json([
            'foo' => 'bar'
        ]);
    }
}