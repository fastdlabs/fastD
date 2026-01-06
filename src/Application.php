<?php

declare(strict_types=1);

namespace FastD;

use DateTimeZone;
use ErrorException;
use FastD\Config\FileParser;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Response;
use FastD\Routing\RouteCollection;
use FastD\Routing\RouteDispatcher;
use Symfony\Component\Yaml\Yaml;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Level;
use Monolog\Logger;
use RuntimeException;

final class Application extends Container
{
    public const VERSION = '8.0';

    protected bool $booted = false;

    public function __construct(protected array $bootstrap)
    {
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

    /**
     * include bootstrap config
     *
     * @param string $key
     * @return mixed
     */
    public function need(string $key): array
    {
        if (!isset($this->bootstrap[$key]) || !file_exists($this->bootstrap[$key])) {
            throw new RuntimeException(sprintf('The bootstrap["%s"] config does not exist', $key));
        }
        return include $this->bootstrap[$key];
    }

    /**
     * @param string $runtime
     * @return void
     * @throws ErrorException
     */
    public function bootstrap(string $runtime): void
    {
        if (!$this->booted) {
            $this->bootstrap['runtime'] = $runtime;
            date_default_timezone_set($this->bootstrap['timezone']);

            $this->registerServices($this->need('services'));
            $this->registerRoutes($this->need('routes'));
            $this->booted = true;
        }
    }

    public function defaultServices(): array
    {
        // 环境变量
        $vars = file_exists($this->bootstrap['root'] . '/.env.yml') ? Yaml::parseFile($this->bootstrap['root'] . '/.env.yml') : [];

        // 日志服务，初始化目录及根据执行环境保存日志信息
        $logDir = $this->bootstrap['log']['path'] . '/' . date('Ym');
        if (!file_exists($logDir)) {
            if (!mkdir($logDir, 0755, true)) {
                throw new ErrorException(sprintf('log directory "%s" create failed', $logDir));
            }
        }
        $logFile = $logDir . '/' . $this->bootstrap['runtime'] . '.log';
        $logger = new Logger($this->bootstrap['runtime'], [new RotatingFileHandler($logFile, 100, $this->bootstrap['log']['level'])], [], new DateTimeZone($this->getTimezone()));

        $collection = new RouteCollection();

        return [
            'config'        => new FileParser($vars),
            'logger'        => $logger,
            'routes'        => $collection,
            'dispatcher'    => new RouteDispatcher($collection),
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
        $collection = $this->get('routes');
        foreach ($routes as $route) {
            $collection->addRoute($route[0], $route[1], $route[2], $route[3] ?? []);
        }
    }

    public function dispatch(ServerRequest $serverRequest): Response
    {
        return $this->get('dispatcher')->dispatch($serverRequest);
    }
}
