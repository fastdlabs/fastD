<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2019
 *
 * @see      https://www.github.com/fastdlabs
 * @see      https://fastdlabs.com
 */

namespace FastD;


use RuntimeException;
use FastD\Config\Config;
use FastD\Container\Container;
use FastD\Runtime\Runtime;
use Monolog\Logger;
use function Swoole\Coroutine\run;

/**
 * Class Application.
 */
final class Application
{
    const VERSION = 'v5.0.0(reborn-dev)';

    protected string $name;

    protected string $path;

    /**
     * Application constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param Container $container
     * @param Runtime $runtime
     */
    public function bootstrap(Container $container, Runtime $runtime): void
    {
        $config = load($this->path . '/config/app.php');

        date_default_timezone_set($config['timezone'] ?? 'PRC');
        $this->name = $config['name'] ?? 'fastd';

        $container->add('config', new Config($config));
        // 初始化异常处理
        $this->initExceptionHandle($runtime);
        // 初始化日志处理
        $this->initLoggerHandle($runtime);

        foreach ($config['services'] as $service) {
            $container->register(new $service);
        }
    }

    /**
     * @param Runtime $runtime
     */
    public function initExceptionHandle(Runtime $runtime): void
    {
        set_exception_handler([$runtime, 'handleException']);

        set_error_handler(function ($code, $message) {
            throw new RuntimeException($message, $code);
        }, E_ERROR);
    }

    /**
     * @param Runtime $runtime
     */
    public function initLoggerHandle(Runtime $runtime): void
    {
        $monolog = new Logger(app()->getName());

        $config = config()->get('logger');

        foreach ($config as $log) {
            list('handle' => $handle, 'path' => $path, 'level' => $level) = $log;
            if (empty($path)) {
                $logPath = app()->getPath() . '/runtime/log/' . date('Ymd') . '/' . app()->getName() . '.log';
            } else {
                if ($path[0] == '/') {
                    $logPath = $path;
                } else {
                    $logPath = app()->getPath() . '/runtime/log/' . date('Ymd') . '/' . $path;
                }
            }
            $dir = dirname($logPath);
            if (!file_exists($dir)) {
                mkdir($dir, 0755, true);
            }
            $handler = new $handle($logPath, $level);
            $monolog->pushHandler($handler);
        }

        container()->add('logger', $monolog);
    }
}
