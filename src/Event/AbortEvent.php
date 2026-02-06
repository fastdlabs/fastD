<?php

declare(strict_types=1);

namespace FastD\Event;

use FastD\Runtime;

class AbortEvent extends Event
{
    public function __construct(public readonly Runtime $runtime)
    {
    }
}