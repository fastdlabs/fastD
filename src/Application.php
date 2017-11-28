<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      https://fastdlabs.com
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
use FastD\Servitization\Client\Client;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Symfony\Component\Debug\Exception\FatalThrowableError;
use Throwable;

/**
 * Class Application.
 */
class Application extends Container
{
    const VERSION = 'v3.2.0';

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

    /**
     * Application bootstrap.
     */
    public function bootstrap()
    {
        if (!$this->booted) {
            $config = load($this->path.'/config/app.php');

            $this->name = $config['name'];

            $this->add('config', new Config($config));
            $this->add('logger', new Logger($this->name));
            $this->add('client', new Client());

            $this->registerExceptionHandler();
            $this->registerServicesProviders($config['services']);
            unset($config);
            $this->booted = true;
        }
    }

    protected function registerExceptionHandler()
    {
        error_reporting(-1);

        set_exception_handler([$this, 'handleException']);

        set_error_handler(function ($level, $message, $file = '', $line = 0) {
            throw new ErrorException($message, 0, $level, $file, $line);
        });
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

    /**
     * @param ServerRequestInterface $request
     *
     * @return Response|\Symfony\Component\HttpFoundation\Response
     */
    public function handleRequest(ServerRequestInterface $request)
    {
        $this->add('request', $request);

        try {
            $response = $this->get('dispatcher')->dispatch($request);
            logger()->log(Logger::INFO, $response->getStatusCode(), [
                'method' => $request->getMethod(),
                'path' => $request->getUri()->getPath(),
            ]);
        } catch (Exception $exception) {
            $response = $this->handleException($exception);
        } catch (Throwable $exception) {
            $exception = new FatalThrowableError($exception);
            $response = $this->handleException($exception);
        }

        $this->add('response', $response);

        return $response;
    }

    /**
     * @param Response|\Symfony\Component\HttpFoundation\Response $response
     */
    public function handleResponse($response)
    {
        $response->send();
    }

    /**
     * @param $e
     *
     * @return Response
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

        $this->add('exception', $e);

        logger()->log(Logger::ERROR, $e->getMessage(), $trace);

        $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : $e->getCode();

        if (!array_key_exists($statusCode, Response::$statusTexts)) {
            $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
        }

        return json(call_user_func(config()->get('exception.response'), $e), $statusCode);
    }

    /**
     * Started application.
     *
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
     * @param ServerRequestInterface                                       $request
     * @param ResponseInterface|\Symfony\Component\HttpFoundation\Response $response
     *
     * @return int
     */
    public function shutdown(ServerRequestInterface $request, $response)
    {
        $this->offsetUnset('request');
        $this->offsetUnset('response');
        if ($this->offsetExists('exception')) {
            $this->offsetUnset('exception');
        }
        unset($request, $response);

        return 0;
    }
}
