<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD;


use ErrorException;
use FastD\Config\Config;
use FastD\Container\Container;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Throwable;

/**
 * Class Runner
 * @package FastD
 */
abstract class Runtime
{
    /**
     * @var Container
     */
    public static Container $container;

    protected string $environment = '';

    protected string $path = '';

    /**
     * @param string $environment
     * @param string $path
     * @throws ErrorException
     */
    public function __construct(string $environment, string $path)
    {
        $this->environment = $environment;
        $this->path = $path;
        $this->bootstrap();
    }

    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getEnvironment(): string
    {
        return $this->environment;
    }

    /**
     * @throws ErrorException
     */
    public function bootstrap()
    {
        static::$container = new Container();
        $config = load($this->path . '/src/config/app.php');
        date_default_timezone_set($config['timezone'] ?? 'PRC');
        static::$container->add('runtime', $this);
        static::$container->add('config', new Config($config));

        $this->registerServices($config['services']);
    }

    protected function registerServices($services): void
    {
        // 注册异常处理
        $exceptionHandler = function (Throwable $throwable) {
            $data = [
                'msg' => $throwable->getMessage(),
                'line' => $throwable->getLine(),
                'file' => $throwable->getFile(),
                'trace' => explode(PHP_EOL, $throwable->getTraceAsString()),
            ];
            $this->handleLogger($throwable->getMessage(), $data);
            $this->handleException($throwable);
        };
        set_exception_handler($exceptionHandler);
        $errorHandler = function ($errno, $errstr, $errfile, $errline) {
            // 将错误抛出作为异常
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        };
        set_error_handler($errorHandler);

        // 注册日志处理
        $logPath = $this->path . '/runtime/logs/' . date('Ym');
        $handler = new RotatingFileHandler($logPath . '/' . $this->environment . '.log', 100, Logger::INFO);
        $monolog = new Logger($this->environment);
        $monolog->pushHandler($handler);
        self::$container->add('logger', $monolog);

        // 注册自定义服务
        foreach ($services as $service) {
            static::$container->register(new $service);
        }
    }

    public function handleLogger(string $message, array $context = [])
    {
        logger()->error($message, $context);
    }

    abstract public function handleException(Throwable $throwable);

    abstract public function handleInput();

    abstract public function handleOutput($output);

    abstract public function run(): void;
}
