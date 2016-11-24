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
use FastD\Contract\AppKernel;
use FastD\Contract\ServiceProviderInterface;
use FastD\Http\ServerRequest;
use FastD\Provider\ConfigurableServiceProvider;
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
class App extends AppKernel
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
            $this->environment = getenv('ENV') ? getenv('ENV') : 'dev';
            $this->debug = $this->environment === 'prod' ? false : true;

            Debug::enable($this->isDebug());

            $this->container = new Container();

            $this->register(new ConfigurableServiceProvider());
            $this->register(new EventServiceProvider());
            $this->register(new RouteServiceProvider());
            $this->container->add('kernel', $this);

            static::$app = $this;
            $this->booted = true;
        }
    }

    /**
     * @param ServiceProviderInterface $serviceProvider
     * @return void
     */
    public function register(ServiceProviderInterface $serviceProvider)
    {
        $serviceProvider->register($this);
    }

    /**
     * @param $prefix
     * @param callable $callback
     * @return RouteCollection
     */
    public function route($prefix = null, callable $callback = null)
    {
        $router = $this->container->get(RouteServiceProvider::SERVICE_NAME);

        if (null === $prefix && null === $callback) {
            return $router;
        }

        return $router->group($prefix, $callback);
    }

    /**
     * @param ServerRequestInterface|null $serverRequest
     * @return void
     */
    public function run(ServerRequestInterface $serverRequest = null)
    {
        $serverRequest = null === $serverRequest ? ServerRequest::createFromGlobals() : $serverRequest;

        $this->container->get(EventServiceProvider::SERVICE_NAME)->trigger('request', [$this, $serverRequest]);
    }
}