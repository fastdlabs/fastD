<?php

declare(strict_types=1);

namespace FastD\Event;

use FastD\Runtime;

class BootedEvent extends Event
{
    public function __construct(public readonly Runtime $runtime)
    {
    }
}