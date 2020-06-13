<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\ServiceProvider;


use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\Response;

class ExceptionServiceProvider implements ServiceProviderInterface
{
    /**
     * @param Container $container
     * @return void
     */
    public function register(Container $container): void
    {
        $adapter = $config['adapter'];

        $container->add('exception', new $adapter($config['options']));

//
//        error_reporting(config()->get('error.level'));
//
//        set_exception_handler([$this, 'handleException']);
//
//        set_error_handler(function ($level, $message, $file, $line) {
//            throw new ErrorException($message, 0, $level, $file, $line);
//        });
    }

    public function handleException(string $handler_class)
    {
        try {
            $trace = call_user_func(config()->get('exception.log'), $e);
        } catch (Exception $exception) {
            $trace = [
                'original' => explode("\n", $e->getTraceAsString()),
                'handler' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        logger()->log(Logger::ERROR, $e->getMessage(), $trace);

        if (EnvironmentObject::make()->isCli()) {
            throw $e;
        }

        $status = ($e instanceof HttpException) ? $e->getStatusCode() : $e->getCode();

        if ( ! array_key_exists($status, Response::$statusTexts)) {
            $status = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        $resposne = json(call_user_func(config()->get('exception.response'), $e), $status);
        if ( ! $this->isBooted()) {
            $this->handleResponse($resposne);
        }

        $this->get('em')->trigger(static::ON_EXCEPTION);

        return $resposne;
    }
}
