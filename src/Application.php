<?php

namespace FastD;

use ErrorException;
use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

class Application extends Container
{
    public const VERSION = '5.0.0';

    protected string $environment = 'fastcgi';

    protected string $name = 'fastd';

    protected string $path;

    protected string $timezone = 'PRC';

    protected array $bootstrap = [];

    protected bool $booted = false;

    /**
     * @param array $bootstrap
     */
    public function __construct(array $bootstrap)
    {
        $this->environment = $bootstrap['env'] ?: $this->environment;
        $this->path = $bootstrap['path'];
        $this->bootstrap = $bootstrap;
        unset($bootstrap);
    }

    /**
     * @throws ErrorException
     */
    public function bootstrap(): void
    {
        if (!$this->booted) {

            $config = $this->getBootstrap('app');
            $this->name = $config['name'] ?? $this->name;
            $this->timezone = $config['timezone'] ?? $this->timezone;
            date_default_timezone_set($this->timezone);

            // 获取环境变量配置
            $variables = file_exists($this->path . '/.env.yml') ? load($this->path . '/.env.yml') : [];
            $this->add('config', new Config($config, $variables));
            $this->registerServices($this->getBootstrap('services'));
            $this->registerRoutes($this->getBootstrap('routes'));
            $this->booted = true;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getTimezone(): string
    {
        return $this->timezone;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getEnvironment(): string
    {
        return $this->environment;
    }

    public function getBootstrap(string $name = 'app'): array
    {
        if (!isset($this->bootstrap[$name])) {
            throw new ErrorException(sprintf('bootstrap name "%s" not exists', $name));
        }
        if (is_string($this->bootstrap[$name])) {
            $this->bootstrap[$name] = include $this->bootstrap[$name];
        }
        return $this->bootstrap[$name];
    }

    public function defaultServices(): array
    {
        // 日志服务
        $logDirectory = $this->getPath() . '/runtime/logs/' . date('Ym');
        if (!file_exists($logDirectory)) {
            mkdir($logDirectory, 0755, true);
        }
        $logFile = $logDirectory . '/' . $this->getEnvironment() . '.log';

        $monolog = new Logger($this->getEnvironment(), [new RotatingFileHandler($logFile, 100, $this->bootstrap['app']['log']['level'] ?? Logger::INFO)]);

        $collection = new RouteCollection();

        return [
            'logger' => $monolog,
            'router' => $collection,
            'dispatcher' => new RouteDispatcher($collection),
        ];
    }

    public function registerServices(array $services): void
    {
        $defaultServices = $this->defaultServices();
        foreach ($defaultServices as $name => $service) {
            $this->add($name, $service);
        }

        foreach ($services as $service) {
            $this->register(new $service);
        }
    }

    public function registerRoutes(array $routes): void
    {
        $router = $this->get('router');
        foreach ($routes as $route) {
            $router->addRoute($route[0], $route[1], $route[2]);
        }
    }

    public function dispatch(ServerRequest $serverRequest): Response
    {
        return $this->get('dispatcher')->dispatch($serverRequest);
    }
}
