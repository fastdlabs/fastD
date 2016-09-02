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
use FastD\Annotation\Reader;
use FastD\Container\Container;
use FastD\Routing\RouteCollection;
use FastD\Standard\Bundle;
use FastD\Storage\Storage;
use FastD\Http\Response;
use FastD\Config\Config;
use FastD\Database\Fdb;
use FastD\Http\Request;
use FastD\Debug\Debug;

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

        $this->initializeContainer();

        $config = new Config();

        foreach ($bootstrap as $key => $value) {
            $config->set($key, $value);
        }

        $this->container->set('kernel.config', $config);
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

            $this->initializeRouting();

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
            'kernel.routing'    => RouteCollection::class,
            'kernel.debug'      => Debug::enable($this->isDebug()),
        ]);

        $this->container->set('kernel.container', $this->container);
        $this->container->set('kernel', $this);
    }

    /**
     * Loaded application routing.
     *
     * Loaded register bundle routes configuration.
     *
     * @return void
     */
    public function initializeRouting()
    {
        $routing = $this->getContainer()->singleton('kernel.routing');

        foreach ($this->getBundles() as $bundle) {
            $path = $bundle->getPath() . '/Http/Controllers';
            if (!is_dir($path) || false === ($files = glob($path . '/*.php', GLOB_NOSORT | GLOB_NOESCAPE))) {
                continue;
            }

            $baseNamespace = $bundle->getNamespace() . '\\Http\\Controllers\\';

            foreach ($files as $file) {
                $className = $baseNamespace . pathinfo($file, PATHINFO_FILENAME);
                if (!class_exists($className)) {
                    continue;
                }

                $reader = new Reader([
                    'route' => function () use ($routing) {

                    }
                ]);
                $methods = $reader->getAnnotations($className)->getMethodAnnotations();
                foreach ($methods as $method) {

                }
            }
        }
    }

    /**
     * Handle request.
     *
     * @param Request $request
     * @return Response
     */
    public function handleHttpRequest(Request $request)
    {
        try {
            $this->container->set('kernel.request', $request);

            $route = $this->getContainer()->singleton('kernel.routing')->match($request->getMethod(), $request->getPathInfo());

            list($controller, $action) = $route->getCallback();

            $service = $this->getContainer()->set('request.handle', $controller)->get('request.handle');

            $service->singleton()->setContainer($this->getContainer());

            return call_user_func_array([$service, $action], $route->getParameters());
        } catch (\RuntimeException $e) {
            $msg = 'server interval error.';
            if ($this->isDebug()) {
                $msg = $e->getMessage();
            }

            return new Response($msg);
        }
    }

    /**
     * @return void
     */
    public function shutdown()
    {
        // TODO: Implement shutdown() method.
    }
}