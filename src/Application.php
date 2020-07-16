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
use FastD\Http\Stream;
use Throwable;
use FastD\Container\Container;

/**
 * Class Application.
 */
abstract class Application
{
    const VERSION = 'v5.0.0(reborn-dev)';

    const EXCEPTION = 'exception';

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

    public function bootstrap(Container $container): void
    {
        $config = load($this->path.'/config/app.php');

        date_default_timezone_set($config['timezone'] ?? 'PRC');

        $container->add('config', new Config($config));

        foreach ($config['services'] as $service) {
            $container->register(new $service());
        }

        unset($config);
    }

    public function handleException(Throwable $throwable): Stream
    {
        $stream = container()->get(Application::EXCEPTION)->handle($throwable);
        // 开启日志功能
        if (container()->has('logger')) {
            // 处理日志
            logger()->error($throwable->getMessage(), [
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTraceAsString()
            ]);
        }
        // 如果没有接受Stream，系统自动丢弃此变量
        return $stream;
    }

    abstract public function handleInput(): Stream;

    abstract public function handleOutput(Stream $stream): void ;
}
