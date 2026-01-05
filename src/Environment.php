<?php

declare(strict_types=1);

namespace FastD;

use ErrorException;
use FastD\Http\Response\Response;
use Throwable;

abstract class Environment
{
    protected static Application $application;

    /**
     * @param string $environment
     * @param Application $application
     * @throws ErrorException
     */
    public function __construct(string $environment, Application $application)
    {
        $application->add('runtime', $this);
        static::$application = $application;
        static::$application->bootstrap($environment);
    }

    public static function application(): Application
    {
        return static::$application;
    }

    abstract public function onInput(): mixed;

    abstract public function onOutput(mixed $output): void;

    abstract public function onError(Throwable $throwable): void;

    public function run(): void
    {
        try {
            $this->onOutput($this->onInput());
        } catch (Throwable $throwable) {
            $this->onError($throwable);
        }
    }
}
