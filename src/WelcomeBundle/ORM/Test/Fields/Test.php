<?php

namespace WelcomeBundle\ORM\Test\Fields;

class Test
{
    /**
     * @const mixed
     */
    const FIELDS = array (
  'id' => 
  array (
    'alias' => 'id',
    'name' => 'id',
    'length' => 11,
    'type' => 'int',
    'notnull' => false,
    'unsigned' => false,
    'default' => 0,
  ),
  'name' => 
  array (
    'alias' => 'name',
    'name' => 'name',
    'length' => 20,
    'type' => 'varchar',
    'notnull' => false,
    'unsigned' => false,
    'default' => '',
  ),
);

    /**
     * @const mixed
     */
    const ALIAS = array (
  'id' => 'id',
  'name' => 'name',
);

    /**
     * @const mixed
     */
    const TABLE = 'test';

}