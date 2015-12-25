<?php

namespace Welcome\Repository;

use FastD\Database\ORM\Repository;

class TestRepository extends Repository
{
    /**
     * @var string
     */
    protected $table = 'test';

    /**
     * @var array
     */
    protected $structure = [
        'id' => [
            'type' => 'int',
            'name' => 'id',
            'length'=> 10,
        ],
        'trueName' => [
            'type' => 'varchar',
            'name' => 'true_name',
            'length'=> 10,
        ],
        'tel' => [
            'type' => 'char',
            'name' => 'tel_number',
            'length'=> 20,
        ],
    ];

    /**
     * @var array
     */
    protected $fields = ['id' => 'id','true_name' => 'trueName','tel_number' => 'tel'];

    /**
     * @var string
     */
    protected $entity = 'Welcome\Entity\Test';


}