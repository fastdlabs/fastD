<?php

namespace WelcomeBundle\ORM\Test\Entities;

class TestEntity extends \FastD\Database\ORM\Entity
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @var mixed
     */
    protected $name;

    /**
     * @const mixed
     */
    const FIELDS = \WelcomeBundle\ORM\Test\Fields\Test::FIELDS;

    /**
     * @const mixed
     */
    const ALIAS = \WelcomeBundle\ORM\Test\Fields\Test::ALIAS;

    /**
     * @const mixed
     */
    const TABLE = \WelcomeBundle\ORM\Test\Fields\Test::TABLE;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     * @return $this
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return $this
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }
}