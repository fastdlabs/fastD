<?php

declare(strict_types=1);

namespace tests;

use FastD\Http\Request\ServerRequest;
use FastD\Http\Response\Text;
use FastD\Routing\Collection\RouteCollection;
use FastD\Routing\RouteMatcher;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Swoole\Coroutine;
use Swoole\Coroutine\Channel;

final class ConcurrencyMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $id = (string) $request->getAttribute('id');

        // 制造协程交错，验证每个请求使用独立的 Dispatcher/Matched
        Coroutine::sleep(0.002);

        return $handler->handle($request)->withHeader('X-Route-Id', $id);
    }
}

final class ConcurrencyHandler implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $id = (string) $request->getAttribute('id');

        Coroutine::sleep(0.002);

        return new Text(200, $id);
    }
}

final class RoutingConcurrencyTest extends TestCase
{
    public function testConcurrentDispatchKeepsRequestStateIsolated(): void
    {
        $collection = new RouteCollection();
        $collection->get('/concurrency/{id}', ConcurrencyHandler::class, [ConcurrencyMiddleware::class]);

        $matcher = new RouteMatcher($collection);
        $total = 50;
        $results = [];

        Coroutine\run(function () use ($matcher, $total, &$results): void {
            $channel = new Channel($total);

            for ($i = 0; $i < $total; $i++) {
                Coroutine::create(function () use ($matcher, $channel, $i): void {
                    $response = $matcher->dispatch(new ServerRequest('GET', '/concurrency/' . $i));

                    $channel->push([
                        'expected' => (string) $i,
                        'body' => (string) $response->getBody(),
                        'header' => $response->getHeaderLine('X-Route-Id'),
                    ]);
                });
            }

            for ($i = 0; $i < $total; $i++) {
                $results[] = $channel->pop();
            }
        });

        $this->assertCount($total, $results);

        foreach ($results as $result) {
            $this->assertSame($result['expected'], $result['body']);
            $this->assertSame($result['expected'], $result['header']);
        }
    }
}
