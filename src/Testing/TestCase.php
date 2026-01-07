<?php

declare(strict_types=1);

namespace FastD\Testing;

use FastD\Application;
use PHPUnit\Framework\TestCase as PHPUnit;
use Psr\Http\Message\ResponseInterface;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Json;
use FastD\Http\Response\Text;
use FastD\Server\CgiServer;
use FastD\Runtime;

class TestCase extends PHPUnit
{
    protected Runtime $runtime;

    protected function setUp(): void
    {
        parent::setUp();

        $bootstrap = include getcwd() . '/config/app.php';

        $this->runtime = new CgiServer('testcase', new Application($bootstrap));
    }

    public function handleRequest(string $method, string $path, array $body = [], array $headers = []): ResponseInterface
    {
        $input = new ServerRequest($method, $path, $headers);

        $input->withParsedBody($body);

        return app()->get('dispatcher')->dispatch($input);
    }

    public function response(ResponseInterface $response, $assert): void
    {
        $this->equalsResponse($response, $assert);
    }

    public function equalsResponse(ResponseInterface $response, $assert): void
    {
        static::assertEquals((string) $response->getBody(), $assert);
    }

    public function json(ResponseInterface $response, array $assert): void
    {
        $this->equalsJson($response, $assert);
    }

    public function equalsJson(ResponseInterface $response, array $assert): void
    {
        static::assertEquals((string) $response->getBody(), (string) new Json($assert));
    }

    public function equalsJsonResponseHasKey(ResponseInterface $response, string $key): void
    {
        $json = (string) $response->getBody();
        $array = json_decode($json, true);
        if (is_string($key)) {
            $keys = [$key];
        } else {
            $keys = $key;
        }
        foreach ($keys as $key) {
            static::assertArrayHasKey($key, $array);
        }
    }

    public function equalsStatus(ResponseInterface $response, $statusCode): void
    {
        static::assertEquals($response->getStatusCode(), $statusCode);
    }

    public function isServerInterval(ResponseInterface $response): void
    {
        static::assertEquals(500, $response->getStatusCode());
    }

    public function isBadRequest(ResponseInterface $response): void
    {
        static::assertEquals(400, $response->getStatusCode());
    }

    public function isNotFound(ResponseInterface $response): void
    {
        static::assertEquals(404, $response->getStatusCode());
    }

    public function isSuccessful(ResponseInterface $response): void
    {
        static::assertEquals(200, $response->getStatusCode());
    }
}
