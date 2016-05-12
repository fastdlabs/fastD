<?php

namespace WelcomeBundle\Orm\Test\Entity;

class FdDemo extends \FastD\Database\Orm\Entity
{
    /**
     * @var mixed
     */
    protected $id;

    /**
     * @const mixed
     */
    const PRIMARY = \WelcomeBundle\Orm\Test\Field\FdDemo::PRIMARY;

    /**
     * @const mixed
     */
    const FIELDS = \WelcomeBundle\Orm\Test\Field\FdDemo::FIELDS;

    /**
     * @const mixed
     */
    const ALIAS = \WelcomeBundle\Orm\Test\Field\FdDemo::ALIAS;

    /**
     * @const mixed
     */
    const TABLE = \WelcomeBundle\Orm\Test\Field\FdDemo::TABLE;

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
}