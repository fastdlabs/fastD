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
use FastD\Http\HttpException;
use FastD\Http\JsonResponse;
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Provider\RouteServiceProvider;
use FastD\Provider\ConfigServiceProvider;
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
    protected $name = 'fast.d';

    /**
     * @var string
     */
    protected $timezone = 'PRC';

    /**
     * @var string
     */
    protected $environment = 'local';

    /**
     * Application runtime path
     *
     * @var string
     */
    protected $runtime = '';

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

        $this->runtime = $appPath . '/runtime';

        static::$app = $this;

        $this['app'] = $this;
        $this['date'] = new DateTime('now', new DateTimeZone($this->timezone));

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
    public function getRuntime()
    {
        return $this->runtime;
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

            $this->timezone = $config['timezone'];

            $this->registerServicesProviders();

            $this->booted = true;
        }
    }

    /**
     * @return void
     */
    protected function registerServicesProviders()
    {
        $this->register(new ConfigServiceProvider());
        $this->register(new RouteServiceProvider());
        $services = include $this->appPath . '/config/services.php';
        foreach ($services as $service) {
            $this->register($service);
        }
    }

    /**
     * @param Response $response
     * @return int
     */
    public function response(Response $response)
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

        $route = $this['router']->match($serverRequest->getMethod(), $serverRequest->getUri()->getRelationPath());

        if (is_string(($callback = $route->getCallback()))) {
            list($controller, $action) = explode('@', $callback);
            $this
                ->injectOn('controller', '\\Http\\Controller\\' . $controller)
                ->withMethod($action)
                ->withArguments($route->getParameters())
            ;
        } else if (is_callable($callback)) {
            $this
                ->injectOn('controller', $callback)
                ->withArguments($route->getParameters())
            ;
        }

        return $this->make('controller');
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

        $response = new JsonResponse([
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
            'trace' => explode("\n", $e->getTraceAsString()),
        ]);

        if (!array_key_exists($statusCode, $response::$statusTexts)) {
            $statusCode = 500;
        }

        $response->withStatus($statusCode);

        return $response;
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

        return $this->response($response);
    }
}