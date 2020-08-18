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

    public function handleLog(int $level, string $message, array $context = []): void
    {
        logger()->addRecord($level, $message, $context);
    }

    abstract public function handleException(Throwable $throwable): void ;

    abstract public function handleInput();

    abstract public function handleOutput($output);

    abstract public function run();
}
