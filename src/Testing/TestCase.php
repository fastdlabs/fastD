<?php

declare(strict_types=1);

namespace FastD\Testing;

use FastD\Application;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\JsonResponse;
use FastD\Http\Response\Response;
use FastD\Runtime;
use FastD\Server\CgiServer;

class TestCase extends \PHPUnit\Framework\TestCase
{
    protected Runtime $runtime;

    protected function setUp(): void
    {
        parent::setUp();

        $bootstrap = include getcwd() . '/config/app.php';

        $this->runtime = new CgiServer('testcase', new Application($bootstrap));
    }

    public function handleRequest(string $method, string $path, array $body = [], array $headers = []): Response
    {
        $input = new ServerRequest($method, $path, $headers);

        $input->withParsedBody($body);

        return app()->get('dispatcher')->dispatch($input);
    }

    public function response(Response $response, $assert): void
    {
        $this->equalsResponse($response, $assert);
    }

    public function equalsResponse(Response $response, $assert): void
    {
        static::assertEquals((string) $response->getBody(), $assert);
    }

    public function json(Response $response, array $assert): void
    {
        $this->equalsJson($response, $assert);
    }

    public function equalsJson(Response $response, array $assert): void
    {
        static::assertEquals((string) $response->getBody(), (string) new JsonResponse($assert));
    }

    public function equalsJsonResponseHasKey(Response $response, string $key): void
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

    public function equalsStatus(Response $response, $statusCode): void
    {
        static::assertEquals($response->getStatusCode(), $statusCode);
    }

    public function isServerInterval(Response $response): void
    {
        static::assertEquals(500, $response->getStatusCode());
    }

    public function isBadRequest(Response $response): void
    {
        static::assertEquals(400, $response->getStatusCode());
    }

    public function isNotFound(Response $response): void
    {
        static::assertEquals(404, $response->getStatusCode());
    }

    public function isSuccessful(Response $response): void
    {
        static::assertEquals(200, $response->getStatusCode());
    }
}
