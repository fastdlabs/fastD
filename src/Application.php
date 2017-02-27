<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2016
 *
 * @link      https://www.github.com/janhuang
 * @link      http://www.fast-d.cn/
 */

namespace FastD;

use DateTime;
use DateTimeZone;
use Exception;
use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Container\ServiceProviderInterface;
use FastD\Http\HttpException;
use FastD\Http\Response;
use FastD\Http\ServerRequest;
use FastD\ServiceProvider\ConfigProvider;
use FastD\ServiceProvider\LoggerProvider;
use FastD\ServiceProvider\RouteProvider;
use Psr\Http\Message\ServerRequestInterface;
use Throwable;

/**
 * Class Application
 * @package FastD
 */
class Application extends Container
{
    /**
     * The FastD version.
     *
     * @const string
     */
    const VERSION = '3.0.0';

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
    protected $env;

    /**
     * @var string
     */
    protected $name = 'fast-d';

    /**
     * @var bool
     */
    protected $debug = false;

    /**
     * @var bool
     */
    protected $booted = false;

    /**
     * AppKernel constructor.
     *
     * @param $path
     */
    public function __construct($path)
    {
        $this->path = $path;

        static::$app = $this;

        $this->add('app', $this);

        $this->bootstrap();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getEnv()
    {
        return $this->env;
    }

    /**
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
    }

    /**
     * @return string
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @return void
     */
    public function bootstrap()
    {
        if (!$this->booted) {
            $config = new Config();

            $config->load($this->path . '/config/app.php');

            $this->name = $config->get('name', 'fast-d');

            $this->env = $config->get('env', false);

            $this['time'] = new DateTime('now',
                new DateTimeZone($config->get('timezone', 'PRC'))
            );

            $this->add('config', $config);

            $this->registerServicesProviders($config->get('providers', []));
            unset($config);
            $this->booted = true;
        }
    }

    /**
     * @param ServiceProviderInterface[] $services
     * @return void
     */
    protected function registerServicesProviders(array $services)
    {
        $this->register(new ConfigProvider());
        $this->register(new RouteProvider());
        $this->register(new LoggerProvider());
        foreach ($services as $service) {
            $this->register(new $service);
        }
    }

    /**
     * @param Response $response
     * @return int
     */
    public function handleResponse(Response $response)
    {
        $response->send();

        $request = $this->get('request');
        $log = [
            'statusCode' => $response->getStatusCode(),
            'params' => [
                'get' => $request->getQueryParams(),
                'post' => $request->getParsedBody(),
            ]
        ];

        if ($response->isSuccessful()) {
            logger()->addInfo($request->getMethod() . ' ' . $request->getUri()->getPath(), $log);
        } else {
            logger()->addError($request->getMethod() . ' ' . $request->getUri()->getPath(), $log);
        }

        return 0;
    }

    /**
     * @param ServerRequestInterface|null $serverRequest
     * @return Response
     */
    public function handleRequest(ServerRequestInterface $serverRequest = null)
    {
        if (null === $serverRequest) {
            $serverRequest = ServerRequest::createServerRequestFromGlobals();
        }

        $this->add('request', $serverRequest);

        return $this->get('dispatcher')->dispatch($serverRequest);
    }

    /**
     * @param Exception $e
     * @return Response
     */
    public function handleException($e)
    {
        $statusCode = $e->getCode();
        if ($e instanceof HttpException) {
            $statusCode = $e->getStatusCode();
        }

        $data = [
            'msg' => $e->getMessage(),
            'code' => $e->getCode(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
            'trace'=> explode("\n", $e->getTraceAsString()),
        ];

        $response = json($data);

        if (!array_key_exists($statusCode, $response::$statusTexts)) {
            $statusCode = 500;
        }

        return $response->withStatus($statusCode);
    }

    /**
     * @param ServerRequestInterface|null $serverRequest
     * @return int
     */
    public function run(ServerRequestInterface $serverRequest = null)
    {
        try {
            $response = $this->handleRequest($serverRequest);
        } catch (HttpException $exception) {
            $response = $this->handleException($exception);
        } catch (Exception $exception) {
            $response = $this->handleException($exception);
        } catch (Throwable $exception) {
            $response = $this->handleException($exception);
        }

        return $this->handleResponse($response);
    }
}