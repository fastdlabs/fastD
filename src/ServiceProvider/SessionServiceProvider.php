<?php

declare(strict_types=1);

namespace FastD\ServiceProvider;

use ErrorException;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\Session\Handler\NativeFileSessionHandler;
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
        } catch (ErrorException) {
            $file = container()->getRootPath() . '/config/session.php';
            $config = is_file($file) ? (array) config()->parse($file)->get('session') : [];
        }

        if (empty($config['enabled'])) {
            return;
        }

        $handlerName = (string) ($config['handler'] ?? 'auto');
        $useTable = 'swoole_table' === $handlerName
            || ('auto' === $handlerName && 'swoole' === $container->getRuntime());

        $handler = $useTable
            ? new SwooleTableSessionHandler(
                (int) ($config['ttl'] ?? 1440),
                (int) ($config['swoole_table']['size'] ?? 2048),
                (int) ($config['swoole_table']['max_data_size'] ?? 4096),
            )
            : new NativeFileSessionHandler(
                (string) ($config['file']['path'] ?? ''),
                (int) ($config['ttl'] ?? 1440),
            );

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
            function (string $message, object $session): void {
                error($message, ['session_id' => method_exists($session, 'getId') ? $session->getId() : null]);
            },
            (int) ($config['gc_probability'] ?? 0),
        );

        $container->add('session.middleware', $middleware);
        $container->get('matcher')->push($middleware);
    }
}
