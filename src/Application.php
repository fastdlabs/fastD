<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      https://fastdlabs.com
 */

namespace FastD;


use FastD\Config\Config;
use Throwable;
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\Container\Container;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

/**
 * Class Application.
 */
final class Application extends Container
{
    const VERSION = 'v5.0.0(reborn-dev)';

    const MODE_FPM = 1;
    const MODE_SWOOLE = 2;
    const MODE_CLI = 3;

    /**
     * @var Application
     */
    public static Application $app;

    /**
     * @var string
     */
    protected string $path;

    /**
     * @var string
     */
    protected string $name;

    /**
     * @var int
     */
    protected int $mode = Application::MODE_FPM;

    /**
     * @var bool
     */
    protected bool $booted = false;

    /**
     * Application constructor.
     * @param string $path
     * @param int $mode
     */
    public function __construct(string $path = __DIR__, int $mode = Application::MODE_FPM)
    {
        $this->path = $path;
        $this->mode = $mode;

        static::$app = $this;

        $this->add('app', $this);

        $this->bootstrap();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return bool
     */
    public function isBooted(): bool
    {
        return $this->booted;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return int
     */
    public function getMode(): int
    {
        return $this->mode;
    }

    /**
     * Application bootstrap.
     */
    public function bootstrap(): void
    {
        if (!$this->booted) {
            $app = load($this->path.'/config/app.php');

            $this->name = $app['name'];
            date_default_timezone_set($app['timezone'] ?? 'PRC');

            $config = new Config($app);
            $this->add('config', $config);

            foreach ($app['services'] as $service) {
                $this->register(new $service());
            }

            $this->booted = true;
            unset($config);
        }
    }

    /**
     * @param Throwable $throwable
     * @return Response
     * @throws Throwable
     */
    public function handleException(Throwable $throwable): ?Response
    {
        $response = $this->get('exception')->handle($throwable);

        if ($this->isBooted()) {
            return $response;
        }

        $this->handleResponse($response);

        return null;
    }

    /**
     * @param ServerRequestInterface $request
     * @return Response
     * @throws Throwable
     */
    public function handleRequest(ServerRequestInterface $request): Response
    {
        try {
            $this->add('request', $request);
            return $this->get('dispatcher')->dispatch($request);
        } catch (Throwable $exception) {
            return $this->handleException($exception);
        }
    }

    /**
     * @param Response $response
     * @return void
     */
    public function handleResponse($response): void
    {
        $response->send();
    }

    /**
     * @return int
     * @throws Throwable
     */
    public function run(): int
    {
        $request = ServerRequest::createServerRequestFromGlobals();

        $response = $this->handleRequest($request);

        $this->handleResponse($response);

        return $this->shutdown($request, $response);
    }

    /**
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     *
     * @return int
     */
    public function shutdown(ServerRequestInterface $request, ResponseInterface $response): int
    {
        $this->offsetUnset('request');
        $this->offsetUnset('response');

        unset($request, $response);

        return 0;
    }
}
