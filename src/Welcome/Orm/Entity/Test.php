<?php

namespace Welcome\Orm\Entity;

use FastD\Database\ORM\Entity;

class Test extends Entity
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
    protected $repository = 'Welcome\Orm\Repository\TestRepository';

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
     * @var string
     */
    protected $nickName;

    /**
     * @var int
     */
    protected $age;

    /**
     * @var int
     */
    protected $gender;
    /**
     * getId
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * setId
     *
     * @param int $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * getTrueName
     *
     * @return string
     */
    public function getTrueName()
    {
        return $this->trueName;
    }

    /**
     * setTrueName
     *
     * @param string $trueName
     * @return $this
     */
    public function setTrueName($trueName)
    {
        $this->trueName = $trueName;

        return $this;
    }

    /**
     * getTelNumber
     *
     * @return string
     */
    public function getTelNumber()
    {
        return $this->telNumber;
    }

    /**
     * setTelNumber
     *
     * @param string $telNumber
     * @return $this
     */
    public function setTelNumber($telNumber)
    {
        $this->telNumber = $telNumber;

        return $this;
    }

    /**
     * getNickName
     *
     * @return string
     */
    public function getNickName()
    {
        return $this->nickName;
    }

    /**
     * setNickName
     *
     * @param string $nickName
     * @return $this
     */
    public function setNickName($nickName)
    {
        $this->nickName = $nickName;

        return $this;
    }

    /**
     * getAge
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * setAge
     *
     * @param int $age
     * @return $this
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * getGender
     *
     * @return int
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * setGender
     *
     * @param int $gender
     * @return $this
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }
}