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
    protected string $environment = 'fastcgi';

    /**
     * @var Container
     */
    public static Container $container;

    /**
     * @var Application
     */
    public static Application $application;

    /**
     * @param string $environment
     * @param Application $application
     */
    public function __construct(string $environment, Application $application)
    {
        $this->environment = $environment;

        static::$application = $application;

        static::$container = new Container();

        $application->bootstrap(static::$container, $this);
    }

    /**
     * @return string
     */
    public function getEnvironment()
    {
        return $this->environment;
    }

    /**
     * 日志处理
     *
     * @param int $level
     * @param string $message
     * @param array $context
     */
    public function handleLog(int $level, string $message, array $context = []): void
    {
        logger()->addRecord($level, $message, $context);
    }

    /**
     * 异常处理，当程序触发异常的时候，会统一进入此处进行处理
     *
     * @param Throwable $throwable
     */
    abstract public function handleException(Throwable $throwable): void ;

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

    abstract public function run();
}
