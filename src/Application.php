<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use DateTime;
use DateTimeZone;
use Exception;
use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\HttpException;
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\ServiceProvider\ConfigServiceProvider;
use FastD\ServiceProvider\LoggerServiceProvider;
use FastD\ServiceProvider\RouteServiceProvider;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application
 * @package FastD
 */
class Application extends Container
{
    /**
     * The FastD version.
     *
     * @const string
     */
    const VERSION = '3.1.0 (release candidate)';

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
    protected $name = 'fast-d';

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
     * @return void
     */
    public function bootstrap()
    {
        if (!$this->booted) {
            $config = load($this->path . '/config/app.php');

            $this->name = $config['name'];
            $this['time'] = new DateTime('now',
                new DateTimeZone($config['timezone'])
            );
            $this->add('config', new Config($config));

            $this->registerServicesProviders($config['services']);
            unset($config);
            $this->booted = true;
        }
    }

    /**
     * @param ServiceProviderInterface[] $services
     * @return void
     */
    protected function registerServicesProviders(array $services)
    {
        $this->register(new ConfigServiceProvider());
        $this->register(new RouteServiceProvider());
        $this->register(new LoggerServiceProvider());
        foreach ($services as $service) {
            $this->register(new $service);
        }
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     */
    public function handleRequest(ServerRequestInterface $request)
    {
        try {
            $this->add('request', $request);
            return $this->get('dispatcher')->dispatch($request);
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
     * @return Response
     */
    public function handleException($e)
    {
        $statusCode = ($e instanceof HttpException) ? $e->getStatusCode() : $e->getCode();

        $error = [
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString())
        ];

        if (!array_key_exists($statusCode, Response::$statusTexts)) {
            $statusCode = 500;
        }

        logger()->addError(request()->getMethod() . ' ' . request()->getUri()->getPath(), [
            'status' => $statusCode,
            'get' => request()->getQueryParams(),
            'post' => request()->getParsedBody(),
            'ip' => function_exists('swoole_get_local_ip') ? get_local_ip() : 'unknown',
            'error' => $error,
        ]);

        return json('dev' === env('env') ? $error : [
            'msg' => $error['msg'],
            'code' => $error['code'],
        ], $statusCode);
    }

    public function run()
    {
        $request = ServerRequest::createServerRequestFromGlobals();

        $response = $this->handleRequest($request);

        $this->handleResponse($response);
    }
}