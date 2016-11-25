<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use FastD\Container\Container;
use FastD\Http\ServerRequest;
use FastD\Provider\EventServiceProvider;
use FastD\Provider\RouteServiceProvider;
use FastD\Routing\RouteCollection;
use FastD\Debug\Debug;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class App
 *
 * @package FastD
 */
class App extends Container
{
    /**
     * The FastD version.
     *
     * @const string
     */
    const VERSION = '3.0.0 (dev)';

    /**
     * @var string
     */
    protected $appPath;

    /**
     * @var Container
     */
    protected $container;

    /**
     * @var static
     */
    public static $app;

    /**
     * @var string
     */
    protected $environment;

    /**
     * @var bool
     */
    protected $debug;

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

        $this->bootstrap();
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
     * @return Container
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @return void
     */
    public function bootstrap()
    {
        if (!$this->booted) {
            static::$app = $this;

            Debug::enable($this->isDebug());

            $this->container = new Container();
            $this->container->add('kernel', $this);

            $this->register(new EventServiceProvider());

            $this->booted = true;

            $this[EventServiceProvider::SERVICE_NAME]->trigger('bootstrap', [$this]);
        }
    }

    /**
     * Enable debug mode.
     *
     * @return void
     */
    public function enableDebug()
    {
        $this->debug = true;
    }

    /**
     * @param $prefix
     * @param callable $callback
     * @return RouteCollection
     */
    public function route($prefix = null, callable $callback = null)
    {
        if (null === $prefix && null === $callback) {
            return $this[RouteServiceProvider::SERVICE_NAME];
        }

        return $this[RouteServiceProvider::SERVICE_NAME]->group($prefix, $callback);
    }

    /**
     * @param ServerRequestInterface|null $serverRequest
     * @return void
     */
    public function run(ServerRequestInterface $serverRequest = null)
    {
        $serverRequest = null === $serverRequest ? ServerRequest::createFromGlobals() : $serverRequest;

        $this[EventServiceProvider::SERVICE_NAME]->trigger('request', [$this, $serverRequest]);
    }
}