<?php

namespace FastD\Server\Events;

interface CallbackEventsInterface
{
    public function onCallback(): bool;
}