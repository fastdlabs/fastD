<?php

declare(strict_types=1);

namespace tests;

use FastD\Application;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Text;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

final class ResolverDependency
{
    public string $value = 'resolved by FastD container';
}

final class ResolverMiddleware implements MiddlewareInterface
{
    public function __construct(public readonly ResolverDependency $dependency)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return $handler->handle($request)->withHeader('X-Resolver-Middleware', $this->dependency->value);
    }
}

final class ResolverController implements MiddlewareInterface
{
    public function __construct(public readonly ResolverDependency $dependency)
    {
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        return new Text(200, $this->dependency->value);
    }
}

class ResolverTest extends TestCase
{
    public function testApplicationMatcherResolvesDependenciesFromContainer(): void
    {
        $application = new Application(include __DIR__ . '/app/bootstrap/fastcgi.php');
        $application->bootstrap('testing');
        $application->add(ResolverDependency::class, new ResolverDependency());

        $application->get('matcher')->routeCollection->get(
            '/resolver',
            ResolverController::class,
            [ResolverMiddleware::class]
        );

        $response = $application->dispatch(new ServerRequest('GET', '/resolver'));

        $this->assertSame('resolved by FastD container', (string) $response->getBody());
        $this->assertSame('resolved by FastD container', $response->getHeaderLine('X-Resolver-Middleware'));
    }
}
