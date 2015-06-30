<?php
/**
 * Created by PhpStorm.
 * User: janhuang
 * Date: 15/3/11
 * Time: 下午3:57
 * Github: https://www.github.com/janhuang 
 * Coding: https://www.coding.net/janhuang
 * SegmentFault: http://segmentfault.com/u/janhuang
 * Blog: http://segmentfault.com/blog/janhuang
 * Gmail: bboyjanhuang@gmail.com
 */

namespace Kernel;

use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Debug\Debug;
use FastD\Logger\Logger;
use FastD\Protocol\Http\Request;
use FastD\Protocol\Http\Response;

/**
 * Class AppKernel
 *
 * @package FastD\Framework
 */
abstract class AppKernel implements TerminalInterface
{
    const VERSION = 'v0.1.x';

    /**
     * @var string
     */
    private $environment;

    /**
     * @var string
     */
    private $rootPath;

    /**
     * App containers.
     * Storage app component.
     *
     * @var array
     */
    protected $components = array(
        'kernel.template'   => 'FastD\\Template\\Template',
        'kernel.logger'     => 'FastD\\Logger\\Logger',
        'kernel.database'   => 'FastD\\Database\\Database',
        'kernel.storage'    => 'FastD\\Storage\\StorageManager',
        'kernel.request'    => 'FastD\\Protocol\\Http\\Request::createRequestHandle',
    );

    /**
     * @var Container
     */
    private $container;

    /**
     * @var bool
     */
    private $debug;

    /**
     * @var \Kernel\Bundle[]
     */
    private $bundles = array();

    /**
     * @var static
     */
    protected static $app;

    /**
     * Constructor. Initialize framework components.
     *
     * @param $env
     */
    protected function __construct($env)
    {
        $this->environment = $env;

        $this->debug = 'prod' === $this->environment ? false : true;

        $this->components = array_merge(
            $this->registerHelpers(),
            $this->components
        );

        $this->bundles = $this->registerBundles();
    }

    /**
     * @return \Kernel\Bundle[]
     */
    public function getBundles()
    {
        return $this->bundles;
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->debug;
    }

    /**
     * Get application running environment.
     *
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Register project bundle.
     *
     * @return \Kernel\Bundle[]
     */
    abstract public function registerBundles();

    /**
     * Register application plugins.
     *
     * @return array
     */
    abstract public function registerHelpers();

    /**
     * Register application configuration
     *
     * @param Config $config
     * @return void
     */
    abstract public function registerConfiguration(Config $config);

    /**
     * @return array
     */
    abstract public function registerConfigVariable();

    /**
     * Bootstrap application. Loading cache,bundles,configuration,router and other.
     *
     * @return void
     */
    public function boot()
    {
        $this->initializeContainer();

        $this->initializeConfigure();

        Debug::enable();

        $this->initializeRouting();
    }

    /**
     * Initialize application container.
     *
     * @return void
     */
    public function initializeContainer()
    {
        $this->container = new Container($this->components);

        $this->container->set('kernel', $this);

        if (!class_exists('\\Make')) {
            include __DIR__ . '/../make.php';
        }
    }

    /**
     * Initialize application configuration.
     *
     * @return Config
     */
    public function initializeConfigure()
    {
        $config = new Config();

        $variables = array_merge($this->registerConfigVariable(), array(
            'root.path' => $this->getRootPath(),
            'env'       => $this->getEnvironment(),
            'debug'     => $this->isDebug(),
            'version'   => AppKernel::VERSION,
        ));

        $config->setVariable($variables);

        $config->load($this->getRootPath() . '/config/config.php');

        $this->registerConfiguration($config);

        $this->container->set('kernel.config', $config);
    }

    /**
     * Loaded application routing.
     *
     * Loaded register bundle routes configuration.
     */
    public function initializeRouting()
    {
        if (!class_exists('\\Routes')) {
            include __DIR__ . '/../../vendor/fastd/routing/src/FastD/Routing/Routes.php';
        }

        include __DIR__ . '/../routes.php';

        foreach ($this->getBundles() as $bundle) {
            if (file_exists($routes = $bundle->getRootPath() . '/Resources/config/routes.php')) {
                include $routes;
            }
        }

        $this->container->set('kernel.routing', \Routes::getRouter());
    }

    /**
     * @param Request $request
     * @return \FastD\Routing\Route
     */
    public function detachRoute(Request $request)
    {
        $router = $this->container->get('kernel.routing');

        $route = $router->match($request->getPathInfo());

        $router->matchMethod($request->getMethod(), $route);

        $router->matchFormat($request->getFormat(), $route);

        return $route;
    }

    /**
     * Handle http request.
     *
     * @param Request $request
     * @return Response
     */
    public function handleHttpRequest(Request $request)
    {
        $this->container->set('kernel.request', $request);

        $route = $this->detachRoute($request);

        $callback = $route->getCallback();

        if (is_array($callback)) {
            $event = $callback[0];
            $handle = $callback[1];
        } else {
            list ($event, $handle) = explode('@', $callback);
        }

        $event = $this->container->set('callback', $event)->get('callback');
        if (method_exists($event, 'setContainer')) {
            $event->setContainer($this->container);
        }
        $response = $this->container->getProvider()->callServiceMethod($event, $handle, $route->getParameters());

        if ($response instanceof Response) {
            return $response;
        }

        return new Response($response);
    }

    /**
     * Application running terminate. Now, The application should be exit.
     * Here application can save request log in log.
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function terminate(Request $request, Response $response)
    {
        if (!$this->isDebug()) {
            $context = [
                'request_date'  => date('Y-m-d H:i:s', $request->getRequestTime()),
                'response_date' => date('Y-m-d H:i:s', microtime(true)),
                'ip'            => $request->getClientIp(),
                'format'        => $request->getFormat(),
                'method'        => $request->getMethod(),
                'status_code'   => $response->getStatusCode(),
                '_GET'          => $request->query->all(),
                '_POST'         => $request->request->all(),
            ];
            Logger::createLogger($this->container->get('kernel.config')->get('logger.access'))->info('request', $context);
        }
    }

    /**
     * Get application work space directory.
     *
     * @return string
     */
    public function getRootPath()
    {
        if (null === $this->rootPath) {
            $this->rootPath = dirname((new \ReflectionClass($this))->getFileName());
        }

        return $this->rootPath;
    }

    /**
     * @param string $env
     * @return static
     */
    public static function create($env = null)
    {
        if (null === static::$app) {
            static::$app = new static($env);
        }

        return static::$app;
    }
}