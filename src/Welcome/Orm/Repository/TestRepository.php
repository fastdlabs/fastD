<?php

namespace Welcome\Orm\Repository;

use FastD\Database\ORM\Repository;

class TestRepository extends Repository
{
    /**
     * @const string
     */
    const PRIMARY = \Welcome\Orm\Fields\TestFields::PRIMARY;

    /**
     * Fields const
     * @const array
     */
    const FIELDS = \Welcome\Orm\Fields\TestFields::FIELDS;

    /**
     * Fields alias
     * @const array
     */
    const ALIAS = \Welcome\Orm\Fields\TestFields::ALIAS;

    /**
     * @var string
     */
    protected $table = 'test';

    /**
     * @var string|null
     */
    protected $entity = 'Welcome\Orm\Entity\Test';

}