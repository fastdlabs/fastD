<?php
/**
 *
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD\Framework\Kernel;

use FastD\Standard\Commands\CommandAware;
use FastD\Container\ContainerInterface;
use Symfony\Component\Finder\Finder;
use FastD\Annotation\Annotation;
use FastD\Container\Container;
use FastD\Console\Console;
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

abstract class AppKernel extends Terminal implements AppKernelInterface
{
    /**
     * The FastD application version.
     *
     * @const string
     */
    const VERSION = '2.1.0';

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var string
     */
    protected $rootPath;
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
     * @var ContainerInterface
     */
    protected $container;

    /**
     * App constructor.
     *
     * @param array $bootstrap
     */
    public function __construct(array $bootstrap)
    {
        $this->rootPath = $bootstrap['root.path'] ?? '.';

        $this->environment = $bootstrap['env'] ?? 'dev';

        $this->debug = in_array((string)$this->environment, ['dev', 'test']) ? true : false;

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
     * @param ContainerInterface $containerInterface
     * @return $this
     */
    public function setContainer(ContainerInterface $containerInterface)
    {
        $this->container = $containerInterface;

        return $this;
    }

    /**
     * @return ContainerInterface
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
            'kernel.database'   => Fdb::class,
            'kernel.config'     => Config::class,
            'kernel.storage'    => Storage::class,
            'kernel.routing'    => Routes::getRouter(),
            'kernel.debug'      => Debug::enable($this->isDebug()),
            'kernel.event'      => Event::class,
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
        } catch (\Exception $e) {}

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
            $path = $bundle->getRootPath() . '/Controllers';
            if (!is_dir($path)) {
                return [];
            }

            $baseNamespace = $bundle->getNamespace() . '\\Controllers\\';
            $finder = new Finder();
            $files = $finder->name('*.php')->in($path)->files();

            $routes = [];
            foreach ($files as $file) {
                $className = $baseNamespace . pathinfo($file->getFileName(), PATHINFO_FILENAME);
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
     * Scan commands.
     *
     * @param Console $console
     * @return void
     */
    public function scanCommands(Console $console)
    {
        foreach ($this->getBundles() as $bundle) {
            $dir = $bundle->getRootPath() . '/Commands';
            if (!is_dir($dir)) {
                continue;
            }
            $finder = new Finder();
            foreach ($finder->in($dir)->name('*Command.php')->files() as $file) {
                $class = $bundle->getNamespace() . '\\Commands\\' . pathinfo($file, PATHINFO_FILENAME);
                $command = new $class();
                if ($command instanceof CommandAware) {
                    $console->addCommand($command);
                }
            }
        }
    }
}