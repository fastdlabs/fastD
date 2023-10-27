<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace fastd\server;


use ErrorException;
use FastD\Config\Config;
use FastD\Container\Container;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;

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
        $this->bootstrap(new Container());
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
    public function bootstrap(Container $container)
    {
        static::$container = $container;
        $config = load($this->path . '/src/config/app.php');
        date_default_timezone_set($config['timezone'] ?? 'PRC');
        $container->add('runtime', $this);
        $container->add('config', new Config($config));
        $this->handleException();
        $this->handleLogger();
        foreach ($config['services'] as $service) {
            $container->register(new $service);
        }
    }

    public function handleLogger(): void
    {
        $monolog = new Logger($this->environment);
        $defaultLogPath = $this->path . '/runtime/logs/' . date('Ym') . '/' . $this->getEnvironment() . '.log';
        $logPath = $this->path . '/runtime/logs/' . date('Ym') . '/';
        $handler = new RotatingFileHandler($logPath ?? $defaultLogPath, 100);
        $monolog->pushHandler($handler);
        self::$container->add('logger', $monolog);
    }

    public function handleException(): void
    {
        $exceptionHandler = function ($exception) {
            $output = json([
                'msg' => $exception->getMessage(),
                'line' => $exception->getLine(),
                'file' => $exception->getFile(),
                'trace' => explode(PHP_EOL, $exception->getTraceAsString()),
            ]);
            $this->handleOutput($output);
        };
        set_exception_handler($exceptionHandler);
        $errorHandler = function ($errno, $errstr, $errfile, $errline) {
            // 将错误抛出作为异常
            throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
        };
        set_error_handler($errorHandler);
    }

    /**
     * 输入包括HTTP，命令行
     *
     * @return mixed
     */
    abstract public function handleInput();

    /**
     * 输出
     *
     * @param $output
     * @return mixed
     */
    abstract public function handleOutput($output);

    abstract public function run(): void;
}
