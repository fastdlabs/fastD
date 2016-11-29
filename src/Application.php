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
use FastD\Debug\Debug;
use FastD\Http\ServerRequest;
use FastD\Provider\ConfigurableServiceProvider;
use FastD\Provider\EventServiceProvider;
use FastD\Provider\RouteServiceProvider;
use FastD\Provider\StoreServiceProvider;
use Psr\Http\Message\ServerRequestInterface;

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
     * @var static
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

            Debug::enable($this->isDebug());

            $this->registerServices();

            $this->booted = true;
        }
    }

    /**
     * @return void
     */
    protected function registerServices()
    {
        $this->register(new EventServiceProvider());
        $this->register(new ConfigurableServiceProvider());
        $this->register(new RouteServiceProvider());
        $this->register(new StoreServiceProvider());
    }

    /**
     * @param ServerRequestInterface|null $serverRequest
     * @return void
     */
    public function run(ServerRequestInterface $serverRequest = null)
    {
        $serverRequest = null === $serverRequest ? ServerRequest::createServerRequestFromGlobals() : $serverRequest;

        $this['event']->trigger('request', [$this, $serverRequest]);
    }
}