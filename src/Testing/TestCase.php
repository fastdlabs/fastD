<?php

declare(strict_types=1);

namespace FastD\Testing;

use FastD\Application;
use FastD\Http\Request\ServerRequest;
use FastD\Runtime;
use FastD\Server\CgiServer;
use PHPUnit\Framework\TestCase as PHPUnit;
use Psr\Http\Message\ResponseInterface;

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

        $input = $input->withParsedBody($body);

        return container()->got('dispatcher')->dispatch($input);
    }
}
