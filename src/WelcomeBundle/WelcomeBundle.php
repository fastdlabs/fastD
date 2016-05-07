<?php

namespace WelcomeBundle;

use FastD\Framework\Bundle\Bundle;
use WelcomeBundle\Extensions\DemoExtensions;

class WelcomeBundle extends Bundle
{
    public function registerExtensions()
    {
        return [
            new DemoExtensions()
        ];
    }
}