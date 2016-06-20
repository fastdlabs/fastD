<?php

namespace WelcomeBundle\ORM\Test\Models;

class TestModel extends \FastD\Database\ORM\Model
{
    /**
     * @const mixed
     */
    const FIELDS = \WelcomeBundle\ORM\Test\Fields\Test::FIELDS;

    /**
     * @const mixed
     */
    const ALIAS = \WelcomeBundle\ORM\Test\Fields\Test::ALIAS;

    /**
     * @const mixed
     */
    const TABLE = \WelcomeBundle\ORM\Test\Fields\Test::TABLE;

}