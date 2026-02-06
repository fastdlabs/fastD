<?php

declare(strict_types=1);

namespace FastD;

use ErrorException;
use FastD\Container\Container;
use FastD\Event\BootedEvent;
use FastD\Event\AbortEvent;
use FastD\Event\FinishEvent;
use Throwable;

abstract class Runtime
{
    public function __construct(public readonly string $environment, public readonly Application $application)
    {
    }

    public function bootstrap(): void
    {
        $this->application->bootstrap($this->environment);
    }

    public function booted(): void
    {
        $this->bootstrap();
        $this->application->got('event')->dispatch(new BootedEvent($this));
    }

    public function process(): void
    {
        try {
            $this->output($this->input());
            $this->application->got('event')->dispatch(new FinishEvent($this));
        } catch (Throwable $throwable) {
            $this->abort($throwable);
            $this->application->got('event')->dispatch(new AbortEvent($this));
        }
    }

    abstract public function input(): mixed;

    abstract public function output(mixed $output): void;

    abstract public function abort(Throwable $throwable): void;

    public function run(): void
    {
        $this->booted();
        $this->process();
    }
}
