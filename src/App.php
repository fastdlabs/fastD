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
use FastD\Routing\RouteCollection;
use FastD\Http\Response;
use FastD\Config\Config;
use FastD\Debug\Debug;

/**
 * Class App
 *
 * @package FastD
 */
class App
{
    /**
     * The FastD version.
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
    protected $appPath;

    /**
     * @var string
     */
    protected $webPath;

    /**
     * @var bool
     */
    protected $debug;

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
        $this->bootstrap($bootstrap);
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
     * @param $bootstrap
     * @return void
     */
    public function bootstrap(array $bootstrap = [])
    {
        if (!$this->booted) {
            $this->appPath = $bootstrap['app.path'];
            $this->webPath = $bootstrap['web.path'];
            $this->environment = isset($bootstrap['env']) ? $bootstrap['env'] : 'dev';
            $this->debug = in_array($this->environment, ['dev', 'test']) ? true : false;

            Debug::enable($this->isDebug());

            $this->container = new Container();

            $this->container->add('kernel', $this);
            $this->container->add('kernel.container', $this->container);
            $this->container->add('kernel.config', new Config($bootstrap, $this->isDebug() ? null : $this->getWebPath()));
            $this->container->add('kernel.routing', new RouteCollection());

            $this->booted = true;
        }
    }

    /**
     * @param ServerRequest $serverRequest
     * @return Response
     */
    public function handleHttpRequest(ServerRequest $serverRequest = null)
    {
        if (null === $serverRequest) {
            $serverRequest = ServerRequest::createFromGlobals();
        }

        $this->container->add('kernel.request', $serverRequest);

        return new Response('hello world');
    }

    /**
     * @return void
     */
    public function shutdown()
    {
        unset($this);
    }
}