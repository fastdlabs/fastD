<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\Annotation\Annotation;
use FastD\Container\Container;
use FastD\Standard\Bundle;
use FastD\Storage\Storage;
use FastD\Routing\Router;
use FastD\Http\Response;
use FastD\Config\Config;
use FastD\Database\Fdb;
use FastD\Http\Request;
use FastD\Event\Event;
use FastD\Debug\Debug;
use Routes;

/**
 * Class App
 *
 * @package FastD
 */
class App
{
    /**
     * The FastD application version.
     *
     * @const string
     */
    const VERSION = '3.0.x-dev';

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $rootPath;

    /**
     * @var string
     */
    protected $webPath;

    /**
     * @var bool
     */
    protected $debug;

    /**
     * @var Bundle[]
     */
    protected $bundles = [];

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * @var Container
     */
    protected $container;

    /**
     * App constructor.
     *
     * @param array $bootstrap
     */
    public function __construct(array $bootstrap)
    {
        $this->rootPath = $bootstrap['root.path'];

        $this->webPath = $bootstrap['web.path'];

        $this->environment = $bootstrap['env'] ?? 'dev';

        $this->debug = in_array($this->environment, ['dev', 'test']) ? true : false;

        $this->bundles = $bootstrap['bundles'] ?? [];
    }

    /**
     * Get custom bundles method.
     *
     * @return Bundle[]
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
     * Get application work space directory.
     *
     * @return string
     */
    public function getRootPath()
    {
        return $this->rootPath;
    }

    /**
     * @return string
     */
    public function getWebPath()
    {
        return $this->webPath;
    }

    /**
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * Bootstrap application.
     *
     * @return void
     */
    public function bootstrap()
    {
        if (!$this->booted) {

            $this->initializeContainer();
            $this->initializeRouting();
            $this->initializeConfigure();

            $this->booted = true;
        }
    }

    /**
     * Initialize application container.
     *
     * @return void
     */
    public function initializeContainer()
    {
        $this->container = new Container([
            'kernel.database' => Fdb::class,
            'kernel.config' => Config::class,
            'kernel.storage' => Storage::class,
            'kernel.routing' => Routes::getRouter(),
            'kernel.debug' => Debug::enable($this->isDebug()),
            'kernel.event' => Event::class,
        ]);

        $this->container->set('kernel.container', $this->container);
        $this->container->set('kernel', $this);
    }

    /**
     * Initialize application configuration.
     *
     * @return void
     */
    public function initializeConfigure()
    {
        $config = $this->container->singleton('kernel.config');

        if (!$this->isDebug()) {
            $config->load($this->getRootPath() . '/config.cache');
        }
    }

    /**
     * Loaded application routing.
     *
     * Loaded register bundle routes configuration.
     *
     * @return Router
     */
    public function initializeRouting()
    {
        $this->container->singleton('kernel.event');

        if ($this->isDebug()) {
            $this->scanRoutes();
        } else {
            include $this->getRootPath() . '/routes.cache';
        }
    }

    /**
     * Handle request.
     *
     * @return Response
     */
    public function createHttpRequestHandler()
    {
        $request = Request::createRequestHandle();

        $this->container->set('kernel.request', $request);

        $route = $this->getContainer()->singleton('kernel.routing')->match($request->getMethod(), $request->getPathInfo());

        $callback = $route->getCallback();

        list($controller, $action) = explode('@', $callback);

        $service = $this->getContainer()->set('request.callback', $controller)->get('request.callback');

        if (method_exists($service->singleton(), 'setContainer')) {
            $service->singleton()->setContainer($this->getContainer());
        }

        try {
            $service->__initialize();
        } catch (\Exception $e) {
        }

        return call_user_func_array([$service, $action], $route->getParameters());
    }

    /**
     * Scan all controller routes.
     *
     * @return void
     */
    public function scanRoutes()
    {
        $routes = [];

        $scan = function (Bundle $bundle) {
            $path = $bundle->getPath() . '/Controllers';
            if (!is_dir($path) || false === ($files = glob($path . '/*.php', GLOB_NOSORT | GLOB_NOESCAPE))) {
                return [];
            }

            $baseNamespace = $bundle->getNamespace() . '\\Controllers\\';

            $routes = [];
            foreach ($files as $file) {
                $className = $baseNamespace . pathinfo($file, PATHINFO_FILENAME);
                if (!class_exists($className)) {
                    continue;
                }

                $annotation = new Annotation($className, 'Action');
                foreach ($annotation as $annotator) {
                    if (null === ($route = $annotator->getParameter('Route'))) {
                        continue;
                    }
                    if (!isset($route['name'])) {
                        $route['name'] = $route[0];
                    }
                    $parameters = [
                        $route['name'],
                        str_replace('//', '/', $route[0]),
                        $annotator->getClassName() . '@' . $annotator->getName(),
                        isset($route['defaults']) ? $route['defaults'] : [],
                        isset($route['requirements']) ? $route['requirements'] : [],
                    ];
                    $method = null === $annotator->getParameter('Method') ? 'any' : strtolower($annotator->getParameter('Method')[0]);
                    $routes[$bundle->getName()][] = [
                        'method' => $method,
                        'parameters' => $parameters
                    ];
                    unset($route, $method, $parameters, $parent);
                }
                unset($annotation);
            }
            return $routes;
        };

        foreach ($this->getBundles() as $bundle) {
            $routes = array_merge($routes, $scan($bundle));
        }

        foreach ($routes as $prefix => $collection) {
            foreach ($collection as $route) {
                call_user_func_array("\\Routes::{$route['method']}", $route['parameters']);
            }
        }

        unset($routes, $scan);
    }

    /**
     * @return void
     */
    public function shutdown()
    {
        // TODO: Implement shutdown() method.
    }

    /**
     * Start Application.
     *
     * @return void
     */
    public function start()
    {
        // TODO: Implement start() method.
    }

    /**
     * Run framework into bootstrap file.
     *
     * @param array $bootstrap
     * @return void
     */
    public static function run($bootstrap)
    {
        $app = new static($bootstrap);

        $app->bootstrap();

        $response = $app->createHttpRequestHandler();

        $response->send();

        $app->shutdown($app);
    }
}