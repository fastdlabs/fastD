<?php

declare(strict_types=1);

namespace tests;

use FastD\Application;
use FastD\Http\Request\ServerRequest;
use FastD\Http\Session\Handler\NativeFileSessionHandler;
use FastD\Http\Session\SessionInterface;
use FastD\Http\Session\SessionMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

/**
 * SessionServiceProvider 集成测试：Provider 注册链路 + 请求分发注入会话 + Cookie 下发。
 */
class SessionProviderTest extends TestCase
{
    private string $sessionDir;

    protected function setUp(): void
    {
        parent::setUp();

        $this->sessionDir = __DIR__ . '/app/runtime/session';
        $this->cleanupSessionDir();

        $application = new Application(include __DIR__ . '/app/bootstrap/fastcgi-session.php');
        $application->bootstrap('testing');
    }

    protected function tearDown(): void
    {
        $this->cleanupSessionDir();

        parent::tearDown();
    }

    public function testProviderRegistersSessionServices(): void
    {
        $this->assertTrue(container()->has('session.handler'));
        $this->assertTrue(container()->has('session.middleware'));
        $this->assertInstanceOf(NativeFileSessionHandler::class, container()->get('session.handler'));
        $this->assertInstanceOf(SessionMiddleware::class, container()->get('session.middleware'));
    }

    public function testDispatchInjectsSessionAndEmitsCookie(): void
    {
        $response = container()->dispatch(new ServerRequest('GET', '/'));

        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame('ok', (string) $response->getBody());
        $this->assertMatchesRegularExpression(
            '/FASTD_SESSID=[a-f0-9]{32}/',
            $response->getHeaderLine('Set-Cookie')
        );
        $this->assertNotEmpty(glob($this->sessionDir . '/fastd_session_*') ?: []);
    }

    public function testSessionHelperReadsSessionFromRequest(): void
    {
        $middleware = container()->get('session.middleware');

        $probe = new class implements RequestHandlerInterface {
            public ?SessionInterface $session = null;

            public function handle(ServerRequestInterface $request): ResponseInterface
            {
                $this->session = session($request);

                return text('ok');
            }
        };

        $middleware->process(new ServerRequest('GET', '/'), $probe);

        $this->assertInstanceOf(SessionInterface::class, $probe->session);
        $this->assertMatchesRegularExpression('/^[a-f0-9]{32}$/', $probe->session->getId());
    }

    private function cleanupSessionDir(): void
    {
        foreach (glob($this->sessionDir . '/*') ?: [] as $file) {
            if (is_file($file)) {
                @unlink($file);
            }
        }
    }
}
