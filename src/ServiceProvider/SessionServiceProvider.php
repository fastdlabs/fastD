<?php

declare(strict_types=1);

namespace FastD\ServiceProvider;

use InvalidArgumentException;
use Throwable;
use FastD\Application;
use FastD\Config\FileParser;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\Session\Handler\NativeFileSessionHandler;
use FastD\Http\Session\Handler\RedisSessionHandler;
use FastD\Http\Session\Handler\SwooleTableSessionHandler;
use FastD\Http\Session\SessionMiddleware;

/**
 * 注册 HTTP Session 能力（fastd/http 的 PSR-15 中间件）。
 *
 * 设计要点：
 * - 默认关闭，由应用在 config/service.php 显式登记后才生效；
 * - handler 在 Application::bootstrap() 阶段创建，满足 Swoole\Table
 *   “必须在 worker fork 前创建”的约束；
 * - 中间件 push 到 matcher，沿用 cache 组件 ServerRequestCacheProvider 的既有约定。
 */
final class SessionServiceProvider implements ServiceProviderInterface
{
    public function register(Container $container): void
    {
        try {
            $config = $container->config('session');
        } catch (Throwable) {
            $root = $container instanceof Application ? $container->getRootPath() : getcwd();
            $file = $root . '/config/session.php';
            $parser = $container->has('config') ? $container->get('config') : null;
            $config = $parser instanceof FileParser && is_file($file)
                ? (array) $parser->parse($file)->get('session')
                : [];
        }

        if (empty($config['enabled'])) {
            return;
        }

        $handlerClass = $config['handler'] ?? 'auto';

        if (!is_string($handlerClass)) {
            throw new InvalidArgumentException('Session handler must be a class name or an auto/native/swoole_table/redis alias.');
        }

        $handlerClass = ltrim($handlerClass, '\\');
        $handlerClass = match ($handlerClass) {
            'auto' => 'swoole' === $container->getRuntime()
                ? SwooleTableSessionHandler::class
                : NativeFileSessionHandler::class,
            'swoole_table' => SwooleTableSessionHandler::class,
            'native_file' => NativeFileSessionHandler::class,
            'redis' => RedisSessionHandler::class,
            default => $handlerClass,
        };

        $ttl = (int) ($config['ttl'] ?? 1440);
        $maxDataSize = (int) ($config['swoole_table']['max_data_size'] ?? 4096);

        $handler = match ($handlerClass) {
            SwooleTableSessionHandler::class => new SwooleTableSessionHandler(
                $ttl,
                (int) ($config['swoole_table']['size'] ?? 2048),
                $maxDataSize,
            ),
            NativeFileSessionHandler::class => new NativeFileSessionHandler(
                (string) ($config['file']['path'] ?? ''),
                $ttl,
            ),
            RedisSessionHandler::class => $this->createRedisHandler($config, $ttl, $maxDataSize),
            default => throw new InvalidArgumentException(sprintf('Unsupported session handler "%s".', $handlerClass)),
        };

        $container->add('session.handler', $handler);

        $cookie = $config['cookie'] ?? [];

        $middleware = new SessionMiddleware(
            $handler,
            (string) ($cookie['name'] ?? 'FASTD_SESSID'),
            (string) ($cookie['path'] ?? '/'),
            (string) ($cookie['domain'] ?? ''),
            (bool) ($cookie['secure'] ?? false),
            (bool) ($cookie['http_only'] ?? true),
            (string) ($cookie['same_site'] ?? 'Lax'),
            function (string $message, object $session) use ($container): void {
                if ($container->has('logger')) {
                    $container->get('logger')->error($message, [
                        'session_id' => method_exists($session, 'getId') ? $session->getId() : null,
                    ]);
                }
            },
            (int) ($config['gc_probability'] ?? 0),
        );

        $container->add('session.middleware', $middleware);
        $container->get('matcher')->push($middleware);
    }

    private function createRedisHandler(array $config, int $ttl, int $maxDataSize): RedisSessionHandler
    {
        if (!class_exists(RedisSessionHandler::class)) {
            throw new InvalidArgumentException('Redis session handler requires fastd/http >= 8.2.');
        }

        $redisConfig = (array) ($config['redis'] ?? []);
        $redis = new \Redis();

        $host = (string) ($redisConfig['host'] ?? '127.0.0.1');
        $port = (int) ($redisConfig['port'] ?? 6379);
        $timeout = (float) ($redisConfig['timeout'] ?? 1.0);

        try {
            if (!$redis->connect($host, $port, $timeout)) {
                throw new InvalidArgumentException(sprintf('Unable to connect to Redis at %s:%d.', $host, $port));
            }

            $password = $redisConfig['password'] ?? null;
            if (is_string($password) && $password !== '' && !$redis->auth($password)) {
                throw new InvalidArgumentException('Redis authentication failed.');
            }

            $database = (int) ($redisConfig['database'] ?? 0);
            if ($database > 0 && !$redis->select($database)) {
                throw new InvalidArgumentException(sprintf('Unable to select Redis database %d.', $database));
            }
        } catch (\Throwable $throwable) {
            throw new InvalidArgumentException(
                sprintf('Unable to initialize Redis session storage at %s:%d: %s', $host, $port, $throwable->getMessage()),
                0,
                $throwable,
            );
        }

        return new RedisSessionHandler(
            $redis,
            $ttl,
            (string) ($redisConfig['prefix'] ?? 'fastd:session:'),
            $maxDataSize,
        );
    }
}
