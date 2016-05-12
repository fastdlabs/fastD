<?php

namespace WelcomeBundle\Orm\Test\Repository;

class TestRepository extends \FastD\Database\Orm\Repository
{
    /**
     * @const mixed
     */
    const PRIMARY = \WelcomeBundle\Orm\Test\Field\Test::PRIMARY;

    /**
     * @const mixed
     */
    const FIELDS = \WelcomeBundle\Orm\Test\Field\Test::FIELDS;

    /**
     * @const mixed
     */
    const ALIAS = \WelcomeBundle\Orm\Test\Field\Test::ALIAS;

    /**
     * @const mixed
     */
    const TABLE = \WelcomeBundle\Orm\Test\Field\Test::TABLE;

}