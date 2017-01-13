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
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\HttpException;
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\ServiceProvider\DatabaseServiceProvider;
use FastD\ServiceProvider\RouteServiceProvider;
use FastD\ServiceProvider\CacheServiceProvider;
use FastD\ServiceProvider\ConfigServiceProvider;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

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
    const VERSION = '3.0.0 (dev)';

    /**
     * @var Application
     */
    public static $app;

    /**
     * @var string
     */
    protected $appPath;

    /**
     * @var string
     */
    protected $name = 'Fast-D';

    /**
     * @var string
     */
    protected $environment = 'local';

    /**
     * @var bool
     */
    protected $debug = true;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * AppKernel constructor.
     *
     * @param $appPath
     */
    public function __construct($appPath)
    {
        $this->appPath = $appPath;

        static::$app = $this;

        $this['app'] = $this;

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
    public function isDebug()
    {
        return $this->debug;
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
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return string
     */
    public function getAppPath()
    {
        return $this->appPath;
    }

    /**
     * @return void
     */
    public function bootstrap()
    {
        if (!$this->booted) {
            $config = include $this->appPath . '/config/app.php';

            $this->environment = $config['environment'];

            $this->debug = 'prod' == $this->environment ? false : true;

            $this->name = $config['name'];

            $this['time'] = new DateTime('now',
                new DateTimeZone(isset($config['timezone']) ? $config['timezone'] : 'PRC')
            );
            $this['config'] = $config;

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
        $this->register(new DatabaseServiceProvider());
        $this->register(new CacheServiceProvider());
        foreach ($services as $service) {
            $this->register($service);
        }
    }

    /**
     * @param Response $response
     * @return int
     */
    public function handleResponse(Response $response)
    {
        $response->send();

        return 0;
    }

    /**
     * @param ServerRequestInterface|null $serverRequest
     * @return Response
     */
    public function handleRequest(ServerRequestInterface $serverRequest = null)
    {
        if (null === $serverRequest) {
            $serverRequest = ServerRequest::createServerRequestFromGlobals();
        }

        $this['request'] = $serverRequest;

        return $this['dispatcher']->dispatch($serverRequest);
    }

    /**
     * @param Exception $e
     * @return Response
     */
    public function handleException($e)
    {
        $statusCode = $e->getCode();
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }

        $response = response([
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace' => explode("\n", $e->getTraceAsString()),
        ]);

        if (!array_key_exists($statusCode, $response::$statusTexts)) {
            $statusCode = 500;
        }

        return $response->withStatus($statusCode);
    }

    /**
     * @param ServerRequestInterface|null $serverRequest
     * @return int
     */
    public function run(ServerRequestInterface $serverRequest = null)
    {
        try {
            $response = $this->handleRequest($serverRequest);
        } catch (HttpException $exception) {
            $response = $this->handleException($exception);
        } catch (Exception $exception) {
            $response = $this->handleException($exception);
        } catch (Throwable $exception) {
            $response = $this->handleException($exception);
        }

        return $this->handleResponse($response);
    }
}