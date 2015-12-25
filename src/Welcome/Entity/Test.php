<?php

namespace Welcome\Entity;

use FastD\Database\ORM\Entity;

class Test extends Entity
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
    protected $fields = [
        'id' => 'id','true_name' => 'trueName','tel_number' => 'tel'
    ];

    /**
     * @var string|null
     */
    protected $repository = 'Welcome\Repository\TestRepository';
    
    /**
     * @var int
     */
    protected $id;

    /**
     * @var string
     */
    protected $trueName;

    /**
     * @var string
     */
    protected $telNumber;

    
    /**
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $trueName
     * @return $this
     */
    public function setTrueName($trueName)
    {
        $this->trueName = $trueName;

        return $this;
    }

    /**
     * @return string
     */
    public function getTrueName()
    {
        return $this->trueName;
    }

    /**
     * @param string $telNumber
     * @return $this
     */
    public function setTelNumber($telNumber)
    {
        $this->telNumber = $telNumber;

        return $this;
    }

    /**
     * @return string
     */
    public function getTelNumber()
    {
        return $this->telNumber;
    }
}