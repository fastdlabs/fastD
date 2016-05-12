<?php

namespace WelcomeBundle\Orm\Test\Entity;

class Demo extends \FastD\Database\Orm\Entity implements \ArrayAccess
{
    /**
     * @const mixed
     */
    const PRIMARY = \WelcomeBundle\Orm\Test\Field\Demo::PRIMARY;

    /**
     * @const mixed
     */
    const FIELDS = \WelcomeBundle\Orm\Test\Field\Demo::FIELDS;

    /**
     * @const mixed
     */
    const ALIAS = \WelcomeBundle\Orm\Test\Field\Demo::ALIAS;

    /**
     * @const mixed
     */
    const TABLE = \WelcomeBundle\Orm\Test\Field\Demo::TABLE;

    /**
     * @var mixed
     */
    protected $id;

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