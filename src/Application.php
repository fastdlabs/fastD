<?php

declare(strict_types=1);

namespace FastD;

use DateTimeZone;
use ErrorException;
use FastD\Config\FileParser;
use FastD\Container\Container;
use FastD\Event\EventDispatcher;
use FastD\Event\ListenerProvider;
use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\RouteMatcher;
use Psr\Http\Message\ResponseInterface;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Psr\Http\Message\ServerRequestInterface;

final class Application extends Container
{
    public const VERSION = '8.0';

    public static Application $application;

    protected bool $booted = false;

    public function __construct(protected array $bootstrap)
    {
        Application::$application = $this;
    }

    public function getName(): string
    {
        return $this->bootstrap['name'];
    }

    public function getTimezone(): string
    {
        return $this->bootstrap['timezone'];
    }

    public function getRootPath(): string
    {
        return $this->bootstrap['root'];
    }

    public function getRuntime(): string
    {
        return $this->bootstrap['runtime'];
    }

    public function config(string $key): array
    {
        if (!isset($this->bootstrap[$key]) || !file_exists($this->bootstrap[$key])) {
            throw new ErrorException(sprintf('The bootstrap["%s"] config does not exist', $key));
        }
        return config()->parse($this->bootstrap[$key])->get($key);
    }

    public function bootstrap(string $runtime): void
    {
        if (!$this->booted) {
            $this->bootstrap['runtime'] = $runtime;
            date_default_timezone_set($this->bootstrap['timezone']);

            $this->registerEventListener(include $this->bootstrap['listener']);
            $this->registerServices(include $this->bootstrap['service']);
            $this->registerRoutes(include $this->bootstrap['route']);
            $this->booted = true;
        }
    }

    protected function defaultServices(): array
    {
        // 日志服务，初始化目录及根据执行环境保存日志信息
        $logDir = $this->bootstrap['log']['path'] . '/' . date('Ym');
        if (!file_exists($logDir)) {
            if (!mkdir($logDir, 0755, true)) {
                throw new ErrorException(sprintf('log directory "%s" create failed', $logDir));
            }
        }
        $logFile = $logDir . '/' . $this->bootstrap['runtime'] . '.log';
        $logger = new Logger($this->bootstrap['runtime'], [new RotatingFileHandler($logFile, 100, $this->bootstrap['log']['level'])], [], new DateTimeZone($this->getTimezone()));

        return [
            'config' => new FileParser(file_exists($this->bootstrap['root'] . '/.env.yml') ? $this->bootstrap['root'] . '/.env.yml' : []),
            'logger' => $logger,
            'matcher' => new RouteMatcher(new RouteCollection()),
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
        $collection = $this->get('matcher')->routeCollection;
        foreach ($routes as $route) {
            $collection->addRoute(...$route);
        }
    }

    public function registerEventListener(array $listeners): void
    {
        $listenerProvider = new ListenerProvider();
        foreach ($listeners as $listener) {
            $listenerProvider->addListener(new $listener);
        }
        $this->add('event', new EventDispatcher($listenerProvider));
    }

    public function dispatch(ServerRequestInterface $serverRequest): ResponseInterface
    {
        return $this->get('matcher')->dispatch($serverRequest);
    }
}
