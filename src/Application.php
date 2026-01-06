<?php

declare(strict_types=1);

namespace FastD;

use ErrorException;
use FastD\Config\FileParser;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Response;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use Symfony\Component\Yaml\Yaml;

final class Application extends Container
{
    protected string $name = 'fastd';

    protected string $environment;

    protected string $path;

    protected string $timezone;

    protected bool $booted = false;

    public function __construct(protected array $bootstrap)
    {
        $this->path = $bootstrap['path'];
        $this->timezone = $bootstrap['timezone'] ?? 'PRC';
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

    public function getBootstrap(): array
    {
        return $this->bootstrap;
    }

    /**
     * @param string $environment
     * @return void
     * @throws ErrorException
     */
    public function bootstrap(string $environment): void
    {
        if (!$this->booted) {
            $this->environment = $environment;
            $this->name = $this->bootstrap['app']['name'] ?? $this->name;
            date_default_timezone_set($this->timezone);

            // 获取环境变量配置
            $envVars = [];
            if (file_exists($this->path . '/.env.yml')) {
                $envVars = Yaml::parseFile($this->path . '/.env.yml');
            }
            $this->add('config', new FileParser($envVars));
            $this->registerServices(include $this->bootstrap['services']);
            $this->registerRoutes(include $this->bootstrap['routes']);
            $this->booted = true;
        }
    }

    public function defaultServices(): array
    {
        // 日志服务
        $logDir = $this->path . '/runtime/logs/' . date('Ym');
        if (!file_exists($logDir)) {
            if (!mkdir($logDir, 0755, true)) {
                throw new ErrorException(sprintf('log directory "%s" create failed', $logDir));
            }
        }
        $logFile = $logDir . '/' . $this->environment . '.log';

        $monolog = new Logger($this->environment, [new RotatingFileHandler($logFile, 100, $this->bootstrap['app']['log']['level'] ?? Level::Info)]);

        $collection = new RouteCollection();

        return [
            'logger' => $monolog,
            'router' => $collection,
            'dispatcher' => new RouteDispatcher($collection),
        ];
    }

    /**
     * @param array $services
     * @return void
     * @throws ErrorException
     */
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
            $router->addRoute($route[0], $route[1], $route[2], $route[3] ?? []);
        }
    }

    public function dispatch(ServerRequest $serverRequest): Response
    {
        return $this->get('dispatcher')->dispatch($serverRequest);
    }
}
