<?php

namespace Welcome\Orm\Fields;

class TestFields
{
    /**
     * @const string
     */
    const PRIMARY = 'id';

    /**
     * Const array
     * @const array
     */
     const FIELDS =
array (
  'id' => 
  array (
    'alias' => 'id',
    'name' => 'id',
    'length' => '10',
    'notnull' => true,
    'unsigned' => true,
    'default' => NULL,
  ),
  'trueName' => 
  array (
    'alias' => 'trueName',
    'name' => 'true_name',
    'length' => '10',
    'notnull' => true,
    'unsigned' => false,
    'default' => NULL,
  ),
  'telNumber' => 
  array (
    'alias' => 'telNumber',
    'name' => 'tel_number',
    'length' => '20',
    'notnull' => true,
    'unsigned' => false,
    'default' => NULL,
  ),
  'nickName' => 
  array (
    'alias' => 'nickName',
    'name' => 'nick_name',
    'length' => '20',
    'notnull' => true,
    'unsigned' => false,
    'default' => '',
  ),
  'age' => 
  array (
    'alias' => 'age',
    'name' => 'age',
    'length' => '2',
    'notnull' => true,
    'unsigned' => false,
    'default' => '1',
  ),
  'gender' => 
  array (
    'alias' => 'gender',
    'name' => 'gender',
    'length' => '1',
    'notnull' => true,
    'unsigned' => false,
    'default' => '1',
  ),
);

     /**
      * Const fields alias.
      * @const array
      */
     const ALIAS =
array (
  'id' => 'id',
  'true_name' => 'trueName',
  'tel_number' => 'telNumber',
  'nick_name' => 'nickName',
  'age' => 'age',
  'gender' => 'gender',
);

}