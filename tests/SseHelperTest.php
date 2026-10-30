<?php

declare(strict_types=1);

namespace tests;

use FastD\Http\Response\SseResponse;
use FastD\Http\Response\StreamingResponseInterface;
use FastD\Http\Sse\EmitterInterface;
use FastD\Http\Sse\Event;
use PHPUnit\Framework\TestCase;

/**
 * sse() 助手测试。
 */
class SseHelperTest extends TestCase
{
    public function testHelperCreatesSseResponse(): void
    {
        $response = sse(['hello']);

        $this->assertInstanceOf(SseResponse::class, $response);
        $this->assertInstanceOf(StreamingResponseInterface::class, $response);
        $this->assertSame('text/event-stream; charset=UTF-8', $response->getHeaderLine('Content-Type'));
    }

    public function testOptionsAreForwarded(): void
    {
        $response = sse([], ['heartbeat' => 3, 'retry' => 1000, 'max_duration' => 30]);

        $this->assertSame(3, $response->getHeartbeat());
        $this->assertSame(1000, $response->getRetry());
        $this->assertSame(30, $response->getMaxDuration());
    }

    public function testClosureProducerIsKept(): void
    {
        $response = sse(function (EmitterInterface $emitter): void {
            $emitter->send(Event::data('ok', 'notice'));
        });

        $frames = [];
        ($response->getProducer())(new class($frames) implements EmitterInterface {
            public function __construct(public array &$frames)
            {
            }

            public function send(string|array|object $event): void
            {
                $this->frames[] = ($event instanceof Event ? $event : Event::data($event))->format();
            }

            public function comment(string $text): void
            {
                $this->frames[] = ': ' . $text . "\n\n";
            }

            public function retry(int $milliseconds): void
            {
                $this->frames[] = 'retry: ' . $milliseconds . "\n\n";
            }

            public function isClosed(): bool
            {
                return false;
            }

            public function close(): void
            {
            }
        });

        $this->assertSame(["event: notice\ndata: ok\n\n"], $frames);
    }
}
