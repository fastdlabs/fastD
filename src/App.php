<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\Annotation\Annotation;
use FastD\Container\Container;
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Standard\Bundle;
use FastD\Storage\Storage;
use FastD\Http\Response;
use FastD\Config\Config;
use FastD\Database\Fdb;
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

        $this->environment = isset($bootstrap['env']) ? $bootstrap['env'] : 'dev';

        $this->debug = in_array($this->environment, ['dev', 'test']) ? true : false;

        $this->bundles = $bootstrap['bundles'];
        unset($bootstrap['bundles']);

        $this->bootstrap($bootstrap);
    }

    /**
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
    public function getRootDir()
    {
        return $this->rootPath;
    }

    /**
     * @return string
     */
    public function getWebDir()
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
     * @param $bootstrap
     * @return void
     */
    public function bootstrap(array $bootstrap = [])
    {
        if (!$this->booted) {

            $this->setupContainer();

            $this->container->set('kernel.config', new Config($bootstrap, $this->isDebug() ? null : $this->getWebDir()));

            $this->container->set('kernel.routing', new RouteCollection());

            foreach ($this->getBundles() as $bundle) {
                $this->registeredBundle($bundle);
            }

            $this->booted = true;
        }
    }

    /**
     * @param Bundle $bundle
     */
    public function registeredBundle(Bundle $bundle)
    {
        $bundle->setContainer($this->container);

        $bundle->setUp();

        $this->bundles[] = $bundle;
    }

    /**
     * Initialize application container.
     *
     * @return void
     */
    public function setupContainer()
    {
        $this->container = new Container([
            'kernel.database' => Fdb::class,
            'kernel.config' => Config::class,
            'kernel.storage' => Storage::class,
            'kernel.debug' => Debug::enable($this->isDebug()),
        ]);

        $this->container->set('kernel.container', $this->container);
        $this->container->set('kernel', $this);
    }

    /**
     * @param ServerRequest $serverRequest
     * @return mixed
     */
    public function handleHttpRequest(ServerRequest $serverRequest)
    {
        $this->container->set('kernel.request', $serverRequest);

        $route = $this->getContainer()->singleton('kernel.routing')->match($serverRequest->getMethod(), $serverRequest->server->getPathInfo());

        list($controller, $action) = $route->getCallback();

        $service = $this->getContainer()->set('request.handle', $controller)->get('request.handle');

        $service->singleton()->setContainer($this->getContainer());

        return call_user_func_array([$service, $action], $route->getParameters());
    }

    /**
     * @param Response $response
     * @return void
     */
    public function shutdown(Response $response)
    {
        unset($this);
    }
}