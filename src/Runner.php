<?php
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD;


use FastD\Container\Container;

/**
 * Class Runner
 * @package FastD
 */
class Runner
{
    public static Container $container;

    public static Application $application;

    protected string $path;

    /**
     * Application constructor.
     * @param Application $application
     */
    public function __construct(Application $application)
    {
        static::$application = $application;

        static::$container = new Container();

        $this->bootstrap(static::$container);
    }

    public function bootstrap(Container $container): void
    {
        app()->bootstrap($container);
    }

    public function start(): void
    {
        $stream = app()->handleInput();

        app()->handleOutput($stream);
    }
}
