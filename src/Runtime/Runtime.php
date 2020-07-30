<?php
declare(strict_types=1);
/**
 * @author    jan huang <bboyjanhuang@gmail.com>
 * @copyright 2020
 *
 * @see      https://www.github.com/fastdlabs
 * @see      http://www.fastdlabs.com/
 */

namespace FastD\Runtime;


use FastD\Application;
use FastD\Container\Container;
use Throwable;

/**
 * Class Runner
 * @package FastD
 */
abstract class Runtime
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

        $application->bootstrap(static::$container, $this);
    }

    abstract public function handleLog(int $level, string $message, array $context = []);

    abstract public function handleException(Throwable $throwable);

    abstract public function handleInput();

    abstract public function handleOutput($output);

    abstract public function run();
}
