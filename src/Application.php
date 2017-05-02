<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @see      https://www.github.com/janhuang
 * @see      http://www.fast-d.cn/
 */

namespace FastD;

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
            $this->add('response', $response);

            return $response;
        } catch (Exception $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * @param Response $response
     */
    public function handleResponse(Response $response)
    {
        $response->send();
    }

    /**
     * @param Exception $e
     *
     * @return Http\JsonResponse
     */
    public function handleException(Exception $e)
    {
        $this->add('exception', $e);

        $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : $e->getCode();

        if (!array_key_exists($statusCode, Response::$statusTexts)) {
            $statusCode = 502;
        }

        $handle = config()->get('exception.response');

        return json($handle($e), $statusCode);
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
        logger()->log($response->getStatusCode(), $request->getMethod().' '.$request->getUri()->getPath());

        $this->offsetUnset('request');
        $this->offsetUnset('response');
        $this->offsetUnset('exception');
        unset($request, $response);

        return 0;
    }
}
