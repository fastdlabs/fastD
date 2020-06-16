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
    protected array $options = [];

    /**
     * ExceptionHndlerInterface constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param Throwable $throwable
     * @return Response
     * @throws Throwable
     */
    public function handle(Throwable $throwable): Response
    {
        // Web 端输出
        if (app()->getMode() == Application::MODE_FPM) {
            $response = json([
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'code' => $throwable->getCode(),
                'msg' => $throwable->getTraceAsString()
            ]);

            return $response;
        }
    }
}
