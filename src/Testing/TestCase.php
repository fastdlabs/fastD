<?php

declare(strict_types=1);

namespace FastD\Testing;

use FastD\Application;
use FastD\Http\Request\ServerRequest;
use FastD\Runtime;
use FastD\Server\CgiServer;
use PHPUnit\Framework\TestCase as PHPUnit;
use Psr\Http\Message\ResponseInterface;
use RuntimeException;

/**
 * 框架提供的测试基类。
 *
 * 约定：在应用根目录执行 PHPUnit，即能自动找到 bootstrap 文件；
 * 目录结构不同时可通过 {@see TestCase::useBootstrap()} 或环境变量
 * FASTD_BOOTSTRAP 指定。
 */
class TestCase extends PHPUnit
{
    protected Runtime $runtime;

    /**
     * 测试运行时名称，会通过 container()->getRuntime() 暴露给被测代码。
     */
    protected string $runtimeName = 'testcase';

    /**
     * 显式指定的应用 bootstrap 文件，null 表示按约定自动探测。
     */
    protected static ?string $bootstrapPath = null;

    protected function setUp(): void
    {
        parent::setUp();

        $bootstrap = require $this->resolveBootstrapPath();

        if (!is_array($bootstrap)) {
            throw new RuntimeException('The application bootstrap file must return an array.');
        }

        $this->runtime = new CgiServer($this->runtimeName, new Application($bootstrap));

        // 注册配置、服务、监听器与路由，等价于一次完整的应用启动流程
        $this->runtime->bootstrap();
    }

    /**
     * 指定应用 bootstrap 文件（适用于自定义目录结构的项目）。
     */
    public static function useBootstrap(string $path): void
    {
        static::$bootstrapPath = $path;
    }

    /**
     * 依次尝试：FASTD_BOOTSTRAP 环境变量 → useBootstrap() 显式指定 → 项目根目录约定。
     */
    protected function resolveBootstrapPath(): string
    {
        $candidates = [];

        $fromEnv = getenv('FASTD_BOOTSTRAP');
        if (is_string($fromEnv) && '' !== $fromEnv) {
            $candidates[] = $fromEnv;
        }

        if (null !== static::$bootstrapPath) {
            $candidates[] = static::$bootstrapPath;
        }

        $candidates[] = getcwd() . '/src/bootstrap.php';
        $candidates[] = getcwd() . '/config/app.php';
        $candidates[] = getcwd() . '/bootstrap.php';

        foreach ($candidates as $file) {
            if (is_file($file)) {
                return $file;
            }
        }

        throw new RuntimeException(sprintf(
            'Unable to locate the application bootstrap file, tried: %s. ' .
            'Run PHPUnit from the project root, or set FASTD_BOOTSTRAP / call %s::useBootstrap().',
            implode(', ', $candidates),
            static::class
        ));
    }

    /**
     * 以给定请求分发到应用，返回响应。
     *
     * 传入的 Cookie 请求头会解析为 cookie params，便于串联多请求的会话/登录测试。
     */
    public function handleRequest(string $method, string $path, array $body = [], array $headers = []): ResponseInterface
    {
        $input = (new ServerRequest($method, $path, $headers))->withParsedBody($body);

        $cookieHeader = $input->getHeaderLine('Cookie');

        if ('' !== $cookieHeader) {
            $cookies = [];

            foreach (explode(';', $cookieHeader) as $pair) {
                $pair = trim($pair);

                if ('' === $pair || !str_contains($pair, '=')) {
                    continue;
                }

                [$name, $value] = explode('=', $pair, 2);
                $cookies[trim($name)] = urldecode(trim($value));
            }

            $input = $input->withCookieParams($cookies);
        }

        return $this->runtime->application->dispatch($input);
    }

    public function response(ResponseInterface $response, string $expected): void
    {
        $this->equalsResponse($response, $expected);
    }

    public function equalsResponse(ResponseInterface $response, string $expected): void
    {
        static::assertEquals($expected, (string) $response->getBody());
    }

    public function json(ResponseInterface $response, array $expected): void
    {
        $this->equalsJson($response, $expected);
    }

    public function equalsJson(ResponseInterface $response, array $expected): void
    {
        static::assertStringContainsString(
            'json',
            strtolower($response->getHeaderLine('Content-Type')),
            'The response is not a JSON response.'
        );
        static::assertEquals($expected, json_decode((string) $response->getBody(), true));
    }

    public function equalsStatus(ResponseInterface $response, int $statusCode): void
    {
        static::assertEquals($statusCode, $response->getStatusCode());
    }

    public function isSuccessful(ResponseInterface $response): void
    {
        $statusCode = $response->getStatusCode();

        static::assertGreaterThanOrEqual(200, $statusCode, sprintf('The response status code %d is not successful.', $statusCode));
        static::assertLessThan(300, $statusCode, sprintf('The response status code %d is not successful.', $statusCode));
    }

    public function isBadRequest(ResponseInterface $response): void
    {
        $this->equalsStatus($response, 400);
    }

    public function isNotFound(ResponseInterface $response): void
    {
        $this->equalsStatus($response, 404);
    }

    public function isServerError(ResponseInterface $response): void
    {
        $this->equalsStatus($response, 500);
    }
}
