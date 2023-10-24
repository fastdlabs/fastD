<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Runtime\FPM;


use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Runtime\Runtime;
use Throwable;
use Monolog\Logger;

/**
 * Class FastCGI
 * @package FastD\FPM
 */
class FastCGI extends Runtime
{
    /**
     * @param Throwable $throwable
     */
    public function handleException(Throwable $throwable): void
    {
        $output = json([
            'msg' => $throwable->getMessage(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ]);

        $this->handleLog(Logger::ERROR, $throwable->getMessage(), [
            'msg' => $throwable->getMessage(),
            'line' => $throwable->getLine(),
            'file' => $throwable->getFile(),
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
        ]);

        $this->handleOutput($output);
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
    public function handleOutput($output): void
    {
        $output->send();
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
