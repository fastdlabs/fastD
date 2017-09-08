<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD;

use ErrorException;
use Exception;
use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\HttpException;
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Logger\Logger;
use FastD\ServiceProvider\ConfigServiceProvider;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

/**
 * Class Application.
 */
class Application extends Container
{
    /**
     * The FastD version.
     *
     * @const string
     */
    const VERSION = '3.1.0';

    /**
     * @var Application
     */
    public static $app;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * AppKernel constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;

        static::$app = $this;

        $this->add('app', $this);

        $this->bootstrap();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    public function bootstrap()
    {
        if (!$this->booted) {
            $this->registerExceptionHandler();

            $config = load($this->path.'/config/app.php');

            $this->name = $config['name'];

            $this->add('config', new Config($config));
            $this->add('logger', new Logger(app()->getName()));

            $this->registerServicesProviders($config['services']);
            unset($config);
            $this->booted = true;
        }
    }

    /**
     * @param ServiceProviderInterface[] $services
     */
    protected function registerServicesProviders(array $services)
    {
        $this->register(new ConfigServiceProvider());
        foreach ($services as $service) {
            $this->register(new $service());
        }
    }

    protected function registerExceptionHandler()
    {
        error_reporting(-1);

        set_exception_handler([$this, 'handleException']);

        set_error_handler(function ($level, $message, $file = '', $line = 0, $context = []) {
            throw new ErrorException($message, 0, $level, $file, $line);
        });
    }

    /**
     * @param ServerRequestInterface $request
     *
     * @return Response
     */
    public function handleRequest(ServerRequestInterface $request)
    {
        $this->add('request', $request);

        try {
            $response = $this->get('dispatcher')->dispatch($request);
        } catch (Exception $exception) {
            $this->handleException($exception);
            $response = $this->renderException($exception);
        } catch (Throwable $exception) {
            $exception = new FatalThrowableError($exception);
            $this->handleException($exception);
            $response = $this->renderException($exception);
        }

        $this->add('response', $response);

        return $response;
    }

    /**
     * @param Response $response
     */
    public function handleResponse(Response $response)
    {
        $response->send();
    }

    /**
     * @param $e
     */
    public function handleException($e)
    {
        if (!$e instanceof Exception) {
            $e = new FatalThrowableError($e);
        }

        try {
            $trace = call_user_func(config()->get('exception.log'), $e);
        } catch (Exception $exception) {
            $trace = [
                'original' => explode("\n", $e->getTraceAsString()),
                'handler' => explode("\n", $exception->getTraceAsString()),
            ];
        }

        /*
         * TODO 如果在是在 console, 并且在 bootstrap 中发生异常, 将只会保存日志而没有抛出任何异常
         */
        logger()->log(Logger::ERROR, $e->getMessage(), $trace);
    }

    /**
     * @param Exception $e
     *
     * @return Response
     */
    public function renderException(Exception $e)
    {
        $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : $e->getCode();

        if (!array_key_exists($statusCode, Response::$statusTexts)) {
            $statusCode = 502;
        }

        return json(call_user_func(config()->get('exception.response'), $e), $statusCode);
    }

    /**
     * @return int
     */
    public function run()
    {
        $request = ServerRequest::createServerRequestFromGlobals();

        $response = $this->handleRequest($request);

        $this->handleResponse($response);

        return $this->shutdown($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface      $response
     *
     * @return int
     */
    public function shutdown(ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->offsetUnset('request');
        $this->offsetUnset('response');
        $this->offsetUnset('exception');
        unset($request, $response);

        return 0;
    }
}
