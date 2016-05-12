<?php

namespace WelcomeBundle\Orm\Test\Field;

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
    'length' => '10) unsigne',
    'type' => 'int',
    'notnull' => false,
    'unsigned' => false,
    'default' => 0,
  ),
  'trueName' => 
  array (
    'alias' => 'trueName',
    'name' => 'true_name',
    'length' => '10',
    'type' => 'varchar',
    'notnull' => false,
    'unsigned' => false,
    'default' => '',
  ),
  'telNumber' => 
  array (
    'alias' => 'telNumber',
    'name' => 'tel_number',
    'length' => '20',
    'type' => 'char',
    'notnull' => false,
    'unsigned' => false,
    'default' => '',
  ),
  'username' => 
  array (
    'alias' => 'username',
    'name' => 'username',
    'length' => '32',
    'type' => 'varchar',
    'notnull' => false,
    'unsigned' => false,
    'default' => '',
  ),
  'password' => 
  array (
    'alias' => 'password',
    'name' => 'password',
    'length' => '32',
    'type' => 'varchar',
    'notnull' => false,
    'unsigned' => false,
    'default' => '',
  ),
  'email' => 
  array (
    'alias' => 'email',
    'name' => 'email',
    'length' => '32',
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
  'true_name' => 'trueName',
  'tel_number' => 'telNumber',
  'username' => 'username',
  'password' => 'password',
  'email' => 'email',
);

    /**
     * @const mixed
     */
    const PRIMARY = 'id';

    /**
     * @const mixed
     */
    const TABLE = 'test';

}