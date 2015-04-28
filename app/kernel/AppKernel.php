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

use Dobee\Config\Config;
use Dobee\Container\Container;
use Dobee\Http\Request;
use Dobee\Http\Response;

/**
 * Class AppKernel
 *
 * @package Dobee\Framework
 */
abstract class AppKernel implements TerminalInterface
{
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
        'kernel.template'   => 'Dobee\\Template\\TemplateManager',
        'kernel.logger'     => 'Dobee\\Logger\\logger',
        'kernel.database'   => 'Dobee\\Database\\DriverManager',
        'kernel.storage'    => 'Dobee\\Storage\\StorageManager',
        'kernel.request'    => 'Dobee\\Http\\Request::createGlobalRequest',
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
     * @return static
     */
    final public function __clone()
    {
        return static::$app;
    }

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
            $this->registerPlugins(),
            array(
                'plugins.upload' => 'Dobee\\Plugins\\Uploaded\\Uploader',
                'plugins.paging' => 'Dobee\\Plugins\\Paging\\Pagination',
            ),
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
    public function getDebug()
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
    abstract public function registerPlugins();

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

//        $this->initializeException();

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
            include __DIR__ . '/Make.php';
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

        $this->container->set('kernel.config', $config);

        $variables = array_merge($this->registerConfigVariable(), array(
            'root.path' => $this->getRootPath(),
            'env'       => $this->getEnvironment(),
            'debug'     => $this->getDebug(),
        ));

        $config->setVariable($variables);

        $this->registerConfiguration($config);
    }

    /**
     * initialize framework error.
     */
    public function initializeException()
    {
        if (!$this->getDebug()) {
            error_reporting(0);
        }

        $exceptionHandler = new ExceptionHandler($this);

        set_exception_handler(array($exceptionHandler, 'handleException'));

        set_error_handler(function ($error_code, $error_str, $error_file, $error_line) {
            throw new \ErrorException($error_str, Response::HTTP_INTERNAL_SERVER_ERROR, 1, $error_file, $error_line);
        });

        register_shutdown_function(function () use ($exceptionHandler) {
            $error = error_get_last();
            if ($error && in_array($error['type'], array(1, 4, 16, 64, 256, 4096, E_ALL))) {
                $exceptionHandler->handleException(new \ErrorException($error['message'], $error['type'], 1, $error['file'], $error['line']));
            }
        });

        unset($exceptionHandler);
    }

    /**
     * Loaded application routing.
     */
    public function initializeRouting()
    {
        if (!class_exists('\\Routes')) {
            include $this->getRootPath() . '/../vendor/dobee/routing/src/Dobee/Routing/Routes.php';
        }

        include $this->getRootPath() . '/routes.php';

        $this->container->set('kernel.routing', \Routes::getRouter());
    }

    /**
     * @param Request $request
     * @return \Dobee\Routing\Route
     */
    public function detachRoute(Request $request)
    {
        $router = $this->container->get('kernel.routing');

        return $router->match($request->getPathInfo());
    }

    /**
     * Handle http request.
     *
     * @param Request $request
     * @return Response
     */
    public function handleHttpRequest(Request $request)
    {
        $route = $this->detachRoute($request);

        if (!is_callable($response = $route->getCallback())) {
            list ($event, $handle) = explode('@', $response);

            if (method_exists(($event = new $event()), 'setContainer')) {
                $event->setContainer($this->container);
            }

            $response = $this->container->getProvider()->callServiceMethod($event, $handle, $route->getParameters());
        } else {
            $response = $response();
        }

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
        if (!$this->getDebug()) {
            $this->container->get('kernel.logger')->logRequest($request, $response);
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