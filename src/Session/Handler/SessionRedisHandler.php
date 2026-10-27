<?php

declare(strict_types=1);

namespace FastD\Session\Handler;

use FastD\Http\Session\SessionGcInterface;
use FastD\Http\Session\SessionHandlerInterface;
use RuntimeException;

/**
 * Redis Session Handler。
 *
 * 连接延迟到第一次读写时创建，并按 PID 隔离。这样 FastD 在 Swoole
 * fork 前注册 handler 时不会把父进程的 Redis socket 复制给多个 worker。
 */
final class SessionRedisHandler implements SessionHandlerInterface, SessionGcInterface
{
    private ?SessionHandlerInterface $handler = null;

    private int $pid = 0;

    public function __construct(
        private array $config = [],
        private int $ttl = 1440,
        private int $maxDataSize = 8192,
    ) {
        if (!extension_loaded('redis')) {
            throw new RuntimeException('The redis extension is required by SessionRedisHandler.');
        }
    }

    public function read(string $sessionId): array
    {
        return $this->handler()->read($sessionId);
    }

    public function write(string $sessionId, array $data): bool
    {
        return $this->handler()->write($sessionId, $data);
    }

    public function destroy(string $sessionId): bool
    {
        return $this->handler()->destroy($sessionId);
    }

    public function exists(string $sessionId): bool
    {
        return $this->handler()->exists($sessionId);
    }

    public function gc(int $maxLifetime = 0): int
    {
        $handler = $this->handler();

        return $handler instanceof SessionGcInterface ? $handler->gc($maxLifetime) : 0;
    }

    private function handler(): SessionHandlerInterface
    {
        $pid = getmypid();

        if ($this->handler !== null && $this->pid === $pid) {
            return $this->handler;
        }

        $handlerClass = 'FastD\\Http\\Session\\Handler\\RedisSessionHandler';
        if (!class_exists($handlerClass)) {
            throw new RuntimeException('SessionRedisHandler requires fastd/http >= 8.2.');
        }

        $redis = new \Redis();
        $host = (string) ($this->config['host'] ?? '127.0.0.1');
        $port = (int) ($this->config['port'] ?? 6379);
        $timeout = (float) ($this->config['timeout'] ?? 1.0);

        if (!@$redis->connect($host, $port, $timeout)) {
            throw new RuntimeException(sprintf('Unable to connect to Redis at %s:%d.', $host, $port));
        }

        $password = $this->config['password'] ?? null;
        if (is_string($password) && $password !== '' && !$redis->auth($password)) {
            throw new RuntimeException('Redis authentication failed.');
        }

        $database = (int) ($this->config['database'] ?? 0);
        if ($database > 0 && !$redis->select($database)) {
            throw new RuntimeException(sprintf('Unable to select Redis database %d.', $database));
        }

        $this->handler = new $handlerClass(
            $redis,
            $this->ttl,
            (string) ($this->config['prefix'] ?? 'fastd:session:'),
            $this->maxDataSize,
        );
        $this->pid = $pid;

        return $this->handler;
    }
}
