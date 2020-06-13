<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Exception;


use FastD\Application;
use FastD\Http\Response;
use Throwable;

/**
 * Class ExceptionHandler
 * @package FastD\Exception
 */
class ExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * ExceptionHndlerInterface constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {

    }

    /**
     * @param Throwable $throwable
     * @return Response
     * @throws Throwable
     */
    public function handle(Throwable $throwable): Response
    {
        if (Application::MODE_CLI === app()->getMode() || !app()->isBooted()) {
            throw $throwable;
        }

        return json([
            'msg' => 'server interval error',
            'code' => -1,
            'trace' => explode(PHP_EOL, $throwable->getTraceAsString())
        ], 500);
    }
}
