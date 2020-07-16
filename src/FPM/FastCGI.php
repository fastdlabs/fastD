<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\FPM;


use FastD\Application;
use FastD\Http\ServerRequest;
use FastD\Http\Stream;
use Throwable;

/**
 * Class FastCGI
 * @package FastD\FPM
 */
class FastCGI extends Application
{
    const REQUEST = 'request';

    public function handleInput(): Stream
    {
        try {
            $request = container()->get(FastCGI::REQUEST);
            $response = container()->get('dispatcher')->dispatch($request);
            $response->sendHeaders();
            return $response->getBody();
        } catch (Throwable $exception) {
            return $this->handleException($exception);
        }
    }

    public function handleOutput(Stream $stream): void
    {
        echo (string)$stream;

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }
    }

    public function start(): void
    {
        container()->add(FastCGI::REQUEST, ServerRequest::createServerRequestFromGlobals());

        $this->handleOutput($this->handleInput());

        $this->shutdown();
    }

    public function shutdown(): void
    {
        container()->offsetUnset(FastCGI::REQUEST);
    }
}
