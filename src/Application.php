<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      https://fastdlabs.com
 */

namespace FastD;


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
    const VERSION = 'v5.0.0(dev)';

    const MODE_FPM = 1;
    const MODE_SWOOLE = 2;
    const MODE_CLI = 3;

    /**
     * @var Application
     */
    public static $app;

    /**
     * @var string
     */
    protected $path;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var int
     */
    protected $mode = Application::MODE_FPM;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * Application constructor.
     * @param string $path
     * @param int $mode
     */
    public function __construct(string $path, int $mode = Application::MODE_FPM)
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
            $config = load($this->path.'/config/app.php');
            $this->name = $config['name'];

            date_default_timezone_set($config['timezone'] ?? 'PRC');

            foreach ($config['services'] as $service) {
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
    public function handleException(Throwable $throwable): Response
    {
        if (!$this->has('exception')) {
            throw $throwable;
        }

        return $this->get('exception')->handle($throwable);
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
}
