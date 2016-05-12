<?php

namespace WelcomeBundle\Orm\Test\Repository;

class FdDemoRepository extends \FastD\Database\Orm\Repository
{
    /**
     * @const mixed
     */
    const PRIMARY = \WelcomeBundle\Orm\Test\Field\FdDemo::PRIMARY;

    /**
     * @const mixed
     */
    const FIELDS = \WelcomeBundle\Orm\Test\Field\FdDemo::FIELDS;

    /**
     * @const mixed
     */
    const ALIAS = \WelcomeBundle\Orm\Test\Field\FdDemo::ALIAS;

    /**
     * @const mixed
     */
    const TABLE = \WelcomeBundle\Orm\Test\Field\FdDemo::TABLE;

}