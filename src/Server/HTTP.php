<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
 */

namespace FastD\Swoole;

use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Swoole\Server\HTTPServer;

/**
 * Class HTTPServer.
 */
class HTTP extends HTTPServer
{
    public function handleRequest(ServerRequest $request): Response
    {
        $response = app()->handleRequest($request);

        return $response;
    }
}
