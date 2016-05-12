<?php

namespace WelcomeBundle\Orm\Test\Repository;

class DemoRepository extends \FastD\Database\Orm\Repository
{
    /**
     * @const mixed
     */
    const PRIMARY = \WelcomeBundle\Orm\Test\Field\Demo::PRIMARY;

    /**
     * @const mixed
     */
    const FIELDS = \WelcomeBundle\Orm\Test\Field\Demo::FIELDS;

    /**
     * @const mixed
     */
    const ALIAS = \WelcomeBundle\Orm\Test\Field\Demo::ALIAS;

    /**
     * @const mixed
     */
    const TABLE = \WelcomeBundle\Orm\Test\Field\Demo::TABLE;

}