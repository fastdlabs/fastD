<?php

namespace FastD;

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

    protected array $boostrap = [];

    protected bool $booted = false;

    /**
     * @param array $boostrap
     */
    public function __construct(array $boostrap)
    {
        $this->environment = $boostrap['env'] ?: $this->environment;
        $this->path = $boostrap['path'];
        $this->boostrap = $boostrap;
        unset($boostrap);
    }

    public function bootstrap(): void
    {
        if (!$this->booted) {
            [
                'app' => $config,
                'routes' => $routes,
                'services' => $services,
            ] = $this->boostrap;

            $config = include $config;
            $this->name = $config['name'] ?: $this->name;
            $this->timezone = $config['timezone'] ?: $this->timezone;
            date_default_timezone_set($this->timezone);

            // 获取环境变量配置
            $variables = file_exists($this->path . '/.env.yml') ? load($this->path . '/.env.yml') : [];
            $this->add('config', new Config($config, $variables));
            $this->registerServices(include $services);
            $this->registerRoutes(include $routes);
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

    public function getBoostrap(): array
    {
        return $this->boostrap;
    }

    public function defaultServices(): array
    {
        // 日志服务
        $logPath = $this->getPath() . '/runtime/logs/' . date('Ym');
        $logFile = $logPath . '/' . $this->getEnvironment() . '.log';
        $monolog = new Logger($this->getEnvironment());
        $monolog->pushHandler(new RotatingFileHandler($logFile, 100, Logger::INFO));

        return [
            'logger' => $monolog,
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
        $collection = new RouteCollection();
        $dispatcher = new RouteDispatcher($collection);
        foreach ($routes as $route) {
            $collection->addRoute($route[0], $route[1], $route[2]);
        }
        $this->add('router', $collection);
        $this->add('dispatcher', $dispatcher);
    }

    public function dispatch(ServerRequest $serverRequest): Response
    {
        return $this->get('dispatcher')->dispatch($serverRequest);
    }
}
