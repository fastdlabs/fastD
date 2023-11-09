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
use Monolog\Handler\RotatingFileHandler;
use RuntimeException;
use Monolog\Logger;
use Throwable;

/**
 * Class Runner
 * @package FastD
 */
abstract class Runtime
{
    protected static Application $application;

    public function __construct(Application $application)
    {
        $application->add('runtime', $this);
        static::$application = $application;
    }

    public static function application(): Application
    {
        return static::$application;
    }

    public function bootstrap()
    {
        // 初始化应用配置
        static::$application->bootstrap();
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
            throw new RuntimeException($errstr, 0, $errno, $errfile, $errline);
        };
        set_error_handler($errorHandler);
    }

    public function handleLogger(string $message, array $context = [])
    {
        app()->get('logger')->error($message, $context);
    }

    abstract public function handleException(Throwable $throwable);

    abstract public function handleInput();

    abstract public function handleOutput($output);

    abstract public function run(): void;
}
