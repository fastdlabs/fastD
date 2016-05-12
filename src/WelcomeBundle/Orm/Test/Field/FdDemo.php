<?php

namespace WelcomeBundle\Orm\Test\Field;

class FdDemo
{
    /**
     * @const mixed
     */
    const FIELDS = array (
  'id' => 
  array (
    'alias' => 'id',
    'name' => 'id',
    'length' => '10',
    'type' => 'int',
    'notnull' => false,
    'unsigned' => false,
    'default' => 0,
  ),
);

    /**
     * @const mixed
     */
    const ALIAS = array (
  'id' => 'id',
);

    /**
     * @const mixed
     */
    const PRIMARY = null;

    /**
     * @const mixed
     */
    const TABLE = 'fd_demo';

}