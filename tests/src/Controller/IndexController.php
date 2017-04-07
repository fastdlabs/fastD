<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace Controller;

use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Http\ServerRequest;

/**
 * @SWG\Info(title="演示API", version="0.1")
 *
 * Class IndexController
 */
class IndexController
{
    /**
     * @SWG\Get(
     *   path="/foo/{name}",
     *   summary="演示API示例",
     *   tags={"demo"},
     *   description="示例说明",
     *   consumes={"application/json", "application/xml"},
     *   produces={"application/json", "application/xml"},
     *   @SWG\Parameter(
     *     name="id",
     *     in="query",
     *     description="演示id",
     *     required=false,
     *     type="integer",
     *     @SWG\Items(type="integer", format="int32"),
     *     collectionFormat="csv"
     *   ),
     *   @SWG\Parameter(
     *     name="status",
     *     in="query",
     *     description="演示status",
     *     required=false,
     *     type="integer",
     *     enum={"available", "pending", "sold"}
     *   ),
     *   @SWG\Response(
     *     response=200,
     *     description="OK",
     *     examples={
     *          "application/json": {
     *              "id"="1",
     *              "status"="1"
     *          }
     *     },
     *     @SWG\Schema(
     *       type="integer"
     *     ),
     *     @SWG\Schema(
     *       type="object",
     *       @SWG\Property(property="error_code", type="integer", format="int32"),
     *       @SWG\Property(property="error_message", type="string")
     *     )
     *   ),
     *   @SWG\Response(response=400, description="Bad Request", @SWG\Schema(ref="#/definitions/User")),
     *   @SWG\Response(response=500, description="Internal Server Error")
     * )
     *
     * @param $request
     *
     * @return Response
     */
    public function welcome(ServerRequest $request)
    {
        return json([
            'foo' => 'bar',
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
            'db'    => $model->getDatabase()->info(),
        ]);
    }

    public function auth()
    {
        return json([
            'foo' => 'bar',
        ]);
    }
}
