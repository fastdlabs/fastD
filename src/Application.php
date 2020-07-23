<?php
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

/**
 * Class Application.
 */
final class Application
{
    const VERSION = 'v5.0.0(reborn-dev)';

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

    /**
     * @param Container $container
     * @param Runtime $runtime
     */
    public function bootstrap(Container $container, Runtime $runtime): void
    {
        $config = load($this->path.'/config/app.php');

        date_default_timezone_set($config['timezone'] ?? 'PRC');

        set_exception_handler([$runtime, 'handleException']);

        set_error_handler(function ($code, $message) {
            throw new RuntimeException($message, $code);
        }, $config['exception']['level'] ?? E_ERROR);

        $container->add('config', new Config($config));

        foreach ($config['services'] as $service) {
            $container->register(new $service());
        }
    }
}
