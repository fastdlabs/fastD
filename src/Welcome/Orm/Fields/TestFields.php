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
    'length' => 10,
    'notnull' => false,
    'unsigned' => true,
    'default' => NULL,
  ),
  'trueName' => 
  array (
    'alias' => 'trueName',
    'name' => 'true_name',
    'length' => 10,
    'notnull' => true,
    'unsigned' => false,
    'default' => NULL,
  ),
  'tel' => 
  array (
    'alias' => 'tel',
    'name' => 'tel_number',
    'length' => 20,
    'notnull' => true,
    'unsigned' => false,
    'default' => NULL,
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
  'tel_number' => 'tel',
);

}