<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Server;


use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Runtime;
use Throwable;

/**
 * Class FastCGI
 * @package FastD\FPM
 */
class FastCGI extends Runtime
{
    public function __construct($path)
    {
        parent::__construct('cgi', $path);
    }

    /**
     * @return ServerRequest
     */
    public function handleInput(): ServerRequest
    {
        return ServerRequest::createServerRequestFromGlobals();
    }

    /**
     * @param Response $output
     * @return void
     */
    public function handleOutput($output)
    {
        $output->send();
    }

    public function handleException(Throwable $throwable): void
    {
        $this->handleOutput(json([
            'msg' => $throwable->getMessage(),
            'file' => $throwable->getFile(),
            'line' => $throwable->getLine(),
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ], Response::HTTP_INTERNAL_SERVER_ERROR));
    }

    public function run(): void
    {
        try {
            $input = $this->handleInput();
            $output = static::$container->get('dispatcher')->dispatch($input);
            $this->handleOutput($output);
        } catch (Throwable $exception) {
            $this->handleException($exception);
        }
    }
}
